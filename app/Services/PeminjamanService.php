<?php

namespace App\Services;

use App\Models\EksemplarBuku;
use App\Models\PeminjamanBuku;
use App\Models\Pengguna;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PeminjamanService
{
    /**
     * Validasi eligibilitas mahasiswa / pemustaka untuk meminjam buku sesuai flowchart:
     * - Jika ada buku belum dikembalikan -> User tidak bisa meminjam buku.
     * - Tidak ada buku yang belum dikembalikan -> Memenuhi syarat (Eligible).
     *
     * @return array{eligible: bool, message: string, user: Pengguna, active_loans: array<int, mixed>}
     */
    public function cekEligibilitas(string $idPengguna): array
    {
        $user = Pengguna::with('peran')->findOrFail($idPengguna);

        // Ambil semua peminjaman aktif yang belum dikembalikan
        $activeLoans = PeminjamanBuku::with(['buku', 'eksemplar'])
            ->where('id_pengguna', $user->id_pengguna)
            ->whereNull('tanggal_kembali')
            ->whereIn('status', ['Dipinjam', 'Terlambat'])
            ->get();

        if ($activeLoans->isNotEmpty()) {
            return [
                'eligible' => false,
                'message' => 'User tidak bisa meminjam buku: Mahasiswa masih memiliki '.$activeLoans->count().' buku pinjaman yang belum dikembalikan.',
                'user' => $user,
                'active_loans' => $activeLoans->toArray(),
            ];
        }

        return [
            'eligible' => true,
            'message' => 'Mahasiswa memenuhi syarat dan dapat meminjam buku.',
            'user' => $user,
            'active_loans' => [],
        ];
    }

    /**
     * Input data pinjam buku dan simpan transaksi peminjaman baru:
     * 1. Validasi eligibilitas mahasiswa (jika ada tanggungan, tolak).
     * 2. Validasi eksemplar buku (harus Tersedia).
     * 3. Buat catatan peminjaman buku.
     * 4. Status buku berkurang (status eksemplar diubah menjadi 'Dipinjam').
     */
    public function buatPeminjaman(
        string $idPengguna,
        string $idEksemplar,
        string $tanggalPinjam,
        string $tanggalJatuhTempo,
        ?string $catatan = null
    ): PeminjamanBuku {
        $eligibilitas = $this->cekEligibilitas($idPengguna);

        if (! $eligibilitas['eligible']) {
            throw ValidationException::withMessages([
                'id_pengguna' => [$eligibilitas['message']],
            ]);
        }

        $eksemplar = EksemplarBuku::with('buku')->findOrFail($idEksemplar);

        if ($eksemplar->status !== 'Tersedia') {
            throw ValidationException::withMessages([
                'id_eksemplar' => ["Eksemplar buku {$eksemplar->kode_inventaris} sedang berstatus '{$eksemplar->status}' dan tidak dapat dipinjam."],
            ]);
        }

        return DB::transaction(function () use ($idPengguna, $eksemplar, $tanggalPinjam, $tanggalJatuhTempo, $catatan): PeminjamanBuku {
            // 1. Buat data peminjaman
            $peminjaman = PeminjamanBuku::create([
                'id_pengguna' => $idPengguna,
                'id_buku' => $eksemplar->id_buku,
                'id_eksemplar' => $eksemplar->id_eksemplar,
                'tanggal_pinjam' => $tanggalPinjam,
                'tanggal_jatuh_tempo' => $tanggalJatuhTempo,
                'status' => 'Dipinjam',
                'hari_keterlambatan' => 0,
                'catatan_keterlambatan' => $catatan,
            ]);

            // 2. Status buku berkurang (eksemplar menjadi Dipinjam)
            $eksemplar->update([
                'status' => 'Dipinjam',
            ]);

            return $peminjaman->fresh(['buku', 'eksemplar', 'pengguna']);
        });
    }

    /**
     * Validasi pengembalian buku sesuai flowchart:
     * - Jika tidak ada data peminjaman aktif -> Pengembalian buku gagal.
     * - Jika pernah meminjam (aktif) -> Sukses, deteksi apakah ada keterlambatan.
     *
     * @return array{sukses: bool, message: string, peminjaman: ?PeminjamanBuku, terlambat: bool, hari_terlambat: int}
     */
    public function validasiPengembalian(string $idPeminjaman): array
    {
        $peminjaman = PeminjamanBuku::with(['pengguna', 'buku', 'eksemplar'])
            ->find($idPeminjaman);

        if (! $peminjaman || $peminjaman->tanggal_kembali !== null) {
            return [
                'sukses' => false,
                'message' => 'Pengembalian buku gagal: Data transaksi peminjaman tidak ditemukan atau buku telah dikembalikan sebelumnya.',
                'peminjaman' => null,
                'terlambat' => false,
                'hari_terlambat' => 0,
            ];
        }

        $hariTerlambat = $peminjaman->hitungHariTerlambat(Carbon::now());
        $isTerlambat = $hariTerlambat > 0;

        $pesan = $isTerlambat
            ? "Peminjaman terdeteksi terlambat {$hariTerlambat} hari dari batas jadwal pengembalian."
            : 'Peminjaman valid dan tepat waktu.';

        return [
            'sukses' => true,
            'message' => $pesan,
            'peminjaman' => $peminjaman,
            'terlambat' => $isTerlambat,
            'hari_terlambat' => $hariTerlambat,
        ];
    }

    /**
     * Proses pengembalian buku dari mahasiswa:
     * 1. Validasi transaksi aktif.
     * 2. Hitung keterlambatan (hanya simpan & tampilkan durasi keterlambatan, tanpa sanksi denda).
     * 3. Status buku kembali bertambah (status eksemplar diubah menjadi 'Tersedia').
     * 4. Update status peminjaman menjadi 'Kembali'.
     */
    public function prosesPengembalian(string $idPeminjaman, ?string $catatan = null): PeminjamanBuku
    {
        $validasi = $this->validasiPengembalian($idPeminjaman);

        if (! $validasi['sukses'] || ! $validasi['peminjaman']) {
            throw ValidationException::withMessages([
                'id_peminjaman' => [$validasi['message']],
            ]);
        }

        /** @var PeminjamanBuku $peminjaman */
        $peminjaman = $validasi['peminjaman'];
        $hariTerlambat = $validasi['hari_terlambat'];

        return DB::transaction(function () use ($peminjaman, $hariTerlambat, $catatan): PeminjamanBuku {
            $catatanKeterlambatan = $catatan;
            if ($hariTerlambat > 0 && empty($catatanKeterlambatan)) {
                $catatanKeterlambatan = "Dikembalikan terlambat {$hariTerlambat} hari.";
            }

            // 1. Update status peminjaman menjadi Selesai / Kembali
            $peminjaman->update([
                'tanggal_kembali' => Carbon::now()->toDateString(),
                'status' => 'Kembali',
                'hari_keterlambatan' => $hariTerlambat,
                'catatan_keterlambatan' => $catatanKeterlambatan,
            ]);

            // 2. Status buku akan kembali bertambah (eksemplar kembali Tersedia)
            $peminjaman->eksemplar->update([
                'status' => 'Tersedia',
            ]);

            return $peminjaman->fresh(['buku', 'eksemplar', 'pengguna']);
        });
    }

    /**
     * Kirim notifikasi peringatan ke user bahwa waktu peminjaman telah lewat batas.
     */
    public function kirimPeringatanKeterlambatan(string $idPeminjaman): PeminjamanBuku
    {
        $peminjaman = PeminjamanBuku::with(['pengguna', 'buku'])->findOrFail($idPeminjaman);

        if ($peminjaman->tanggal_kembali !== null) {
            throw ValidationException::withMessages([
                'id_peminjaman' => ['Buku sudah dikembalikan, tidak dapat mengirim peringatan keterlambatan.'],
            ]);
        }

        $peminjaman->update([
            'status' => 'Terlambat',
            'hari_keterlambatan' => $peminjaman->hitungHariTerlambat(Carbon::now()),
            'notifikasi_peringatan_dikirim' => true,
            'tanggal_notifikasi' => Carbon::now(),
        ]);

        return $peminjaman->fresh(['pengguna', 'buku', 'eksemplar']);
    }

    /**
     * Perbarui status keterlambatan untuk seluruh transaksi peminjaman aktif yang melewati jatuh tempo.
     */
    public function sinkronisasiStatusKeterlambatan(): int
    {
        $today = Carbon::now()->toDateString();

        $overdueLoans = PeminjamanBuku::whereNull('tanggal_kembali')
            ->where('status', 'Dipinjam')
            ->whereDate('tanggal_jatuh_tempo', '<', $today)
            ->get();

        $updatedCount = 0;
        foreach ($overdueLoans as $loan) {
            $hariTerlambat = $loan->hitungHariTerlambat(Carbon::now());
            $loan->update([
                'status' => 'Terlambat',
                'hari_keterlambatan' => $hariTerlambat,
            ]);
            $updatedCount++;
        }

        return $updatedCount;
    }
}
