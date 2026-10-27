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

        // 1. Validasi status keanggotaan member perpustakaan
        if (! $user->isMember()) {
            return [
                'eligible' => false,
                'message' => 'User tidak bisa meminjam buku: Pengguna belum terdaftar sebagai member resmi perpustakaan. Mahasiswa atau dosen harus menemui pustakawan atau super admin secara langsung untuk aktivasi keanggotaan.',
                'user' => $user,
                'active_loans' => [],
            ];
        }

        // 2. Ambil semua peminjaman aktif yang belum dikembalikan
        $activeLoans = PeminjamanBuku::with(['buku', 'eksemplar'])
            ->where('id_pengguna', $user->id_pengguna)
            ->whereNull('tanggal_kembali')
            ->whereIn('status', ['Dipinjam', 'Terlambat', 'Menunggu Verifikasi'])
            ->get();

        if ($activeLoans->isNotEmpty()) {
            return [
                'eligible' => false,
                'message' => 'User tidak bisa meminjam buku: Member masih memiliki '.$activeLoans->count().' buku pinjaman yang belum dikembalikan.',
                'user' => $user,
                'active_loans' => $activeLoans->toArray(),
            ];
        }

        return [
            'eligible' => true,
            'message' => 'Member memenuhi syarat dan dapat meminjam buku.',
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

        // Jika sudah lewat jadwal pengembalian: Mengirim notifikasi peringatan ke user bahwa waktu telah lewat batas
        if ($isTerlambat && ! $peminjaman->notifikasi_peringatan_dikirim) {
            $peminjaman->update([
                'status' => $peminjaman->status === 'Menunggu Verifikasi' ? 'Menunggu Verifikasi' : 'Terlambat',
                'hari_keterlambatan' => $hariTerlambat,
                'notifikasi_peringatan_dikirim' => true,
                'tanggal_notifikasi' => Carbon::now(),
            ]);
        }

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
     * Alur Pengembalian Buku oleh User Sesuai Flowchart:
     * 1. Validasi pengembalian buku:
     *    - Cabang 1: Tidak ada data peminjaman -> Pengembalian buku gagal.
     *    - Cabang 2: Pernah meminjam -> Buku berhasil dikembalikan oleh user -> Status buku akan kembali bertambah.
     *    - Cabang 3: Jika sudah lewat jadwal pengembalian -> Mengirim notifikasi peringatan ke user bahwa waktu telah lewat batas.
     *      -> User melakukan pengembalian buku (Ya) -> Buku berhasil dikembalikan oleh user -> Status buku akan kembali bertambah.
     *      -> User belum melakukan pengembalian buku -> Admin input data keterlambatan (via inputKeterlambatanDanSanksi).
     *
     * @return array{sukses: bool, status_alur: string, sudah_lewat_jadwal: bool, hari_keterlambatan: int, message: string, peminjaman: ?PeminjamanBuku}
     */
    public function kembalikanBukuOlehUser(
        string $idPeminjaman,
        string $idPengguna,
        ?string $catatan = null
    ): array {
        $validasi = $this->validasiPengembalian($idPeminjaman);

        // Cabang 1: Tidak ada data peminjaman -> Pengembalian buku gagal
        if (! $validasi['sukses'] || ! $validasi['peminjaman']) {
            return [
                'sukses' => false,
                'status_alur' => 'gagal',
                'sudah_lewat_jadwal' => false,
                'hari_keterlambatan' => 0,
                'message' => $validasi['message'],
                'peminjaman' => null,
            ];
        }

        /** @var PeminjamanBuku $peminjaman */
        $peminjaman = $validasi['peminjaman'];

        if ($peminjaman->id_pengguna !== $idPengguna) {
            return [
                'sukses' => false,
                'status_alur' => 'gagal',
                'sudah_lewat_jadwal' => false,
                'hari_keterlambatan' => 0,
                'message' => 'Pengembalian buku gagal: Data transaksi peminjaman tidak sesuai dengan akun Anda.',
                'peminjaman' => null,
            ];
        }

        $hariTerlambat = $validasi['hari_terlambat'];
        $sudahLewatJadwal = $validasi['terlambat'];

        // Cabang 3: Jika sudah lewat jadwal pengembalian -> Notifikasi peringatan telah dikirim/dicatat pada validasiPengembalian.
        // User melakukan pengembalian buku (Ya) -> Loop kembali ke Validasi -> Cabang 2: Pernah meminjam
        // -> Buku berhasil dikembalikan oleh user -> Status buku akan kembali bertambah (eksemplar menjadi 'Tersedia').
        $peminjamanSelesai = DB::transaction(function () use ($peminjaman, $hariTerlambat, $catatan): PeminjamanBuku {
            $catatanKeterlambatan = $catatan;
            if ($hariTerlambat > 0 && empty($catatanKeterlambatan)) {
                $catatanKeterlambatan = "Dikembalikan terlambat {$hariTerlambat} hari.";
            }

            // 1. Buku berhasil dikembalikan oleh user (status menjadi Kembali)
            $peminjaman->update([
                'tanggal_kembali' => Carbon::now()->toDateString(),
                'status' => 'Kembali',
                'hari_keterlambatan' => $hariTerlambat,
                'catatan_keterlambatan' => $catatanKeterlambatan,
            ]);

            // 2. Status buku akan kembali bertambah (eksemplar status kembali 'Tersedia')
            $peminjaman->eksemplar->update([
                'status' => 'Tersedia',
            ]);

            return $peminjaman->fresh(['buku', 'eksemplar', 'pengguna']);
        });

        $pesan = "Buku \"{$peminjamanSelesai->buku->judul_buku}\" berhasil dikembalikan oleh user. Status buku telah kembali bertambah menjadi Tersedia di rak.";
        if ($sudahLewatJadwal) {
            $pesan .= " Tercatat keterlambatan selama {$hariTerlambat} hari.";
        }

        return [
            'sukses' => true,
            'status_alur' => 'berhasil',
            'sudah_lewat_jadwal' => $sudahLewatJadwal,
            'hari_keterlambatan' => $hariTerlambat,
            'message' => $pesan,
            'peminjaman' => $peminjamanSelesai,
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
     * Otomatis mengirim / mencatat notifikasi peringatan bahwa batas waktu peminjaman telah lewat.
     */
    public function sinkronisasiStatusKeterlambatan(): int
    {
        $today = Carbon::now()->toDateString();

        $overdueLoans = PeminjamanBuku::whereNull('tanggal_kembali')
            ->where(function ($q): void {
                $q->whereIn('status', ['Dipinjam', 'Terlambat', 'Menunggu Verifikasi']);
            })
            ->whereDate('tanggal_jatuh_tempo', '<', $today)
            ->get();

        $updatedCount = 0;
        foreach ($overdueLoans as $loan) {
            $hariTerlambat = $loan->hitungHariTerlambat(Carbon::now());
            $newStatus = $loan->status === 'Menunggu Verifikasi' ? 'Menunggu Verifikasi' : 'Terlambat';
            $loan->update([
                'status' => $newStatus,
                'hari_keterlambatan' => $hariTerlambat,
                'notifikasi_peringatan_dikirim' => true,
                'tanggal_notifikasi' => $loan->tanggal_notifikasi ?: Carbon::now(),
            ]);
            $updatedCount++;
        }

        return $updatedCount;
    }

    /**
     * Mahasiswa / pemustaka mengajukan pengembalian buku mandiri via website katalog OPAC.
     * Status peminjaman beralih menjadi 'Menunggu Verifikasi' (menunggu approval admin / pustakawan).
     * Tetap diperbolehkan meskipun telah lewat batas waktu pengembalian (jatuh tempo).
     */
    public function ajukanPengembalian(string $idPeminjaman, string $idPengguna, ?string $catatan = null): PeminjamanBuku
    {
        $peminjaman = PeminjamanBuku::with(['buku', 'eksemplar', 'pengguna'])->findOrFail($idPeminjaman);

        if ($peminjaman->id_pengguna !== $idPengguna) {
            throw ValidationException::withMessages([
                'id_peminjaman' => ['Anda tidak memiliki hak akses untuk mengajukan pengembalian buku ini.'],
            ]);
        }

        if ($peminjaman->tanggal_kembali !== null) {
            throw ValidationException::withMessages([
                'id_peminjaman' => ['Buku ini sudah tercatat dikembalikan ke perpustakaan sebelumnya.'],
            ]);
        }

        if ($peminjaman->status === 'Menunggu Verifikasi') {
            throw ValidationException::withMessages([
                'id_peminjaman' => ['Pengajuan pengembalian buku ini sudah dikirim dan sedang menunggu verifikasi admin atau pustakawan.'],
            ]);
        }

        $hariTerlambat = $peminjaman->hitungHariTerlambat(Carbon::now());

        $peminjaman->update([
            'status' => 'Menunggu Verifikasi',
            'tanggal_pengajuan_kembali' => Carbon::now(),
            'catatan_pengajuan_kembali' => $catatan,
            'hari_keterlambatan' => $hariTerlambat,
        ]);

        return $peminjaman->fresh(['buku', 'eksemplar', 'pengguna']);
    }

    /**
     * Admin menolak pengajuan pengembalian (misal buku belum diserahkan ke meja sirkulasi atau fisik rusak).
     */
    public function tolakPengajuanPengembalian(string $idPeminjaman, ?string $alasan = null): PeminjamanBuku
    {
        $peminjaman = PeminjamanBuku::with(['buku', 'eksemplar', 'pengguna'])->findOrFail($idPeminjaman);

        if ($peminjaman->status !== 'Menunggu Verifikasi') {
            throw ValidationException::withMessages([
                'id_peminjaman' => ['Hanya transaksi peminjaman berstatus Menunggu Verifikasi yang dapat ditolak pengajuannya.'],
            ]);
        }

        $isTerlambat = $peminjaman->isTerlambat();
        $statusRevert = $isTerlambat ? 'Terlambat' : 'Dipinjam';

        $peminjaman->update([
            'status' => $statusRevert,
            'tanggal_pengajuan_kembali' => null,
            'catatan_pengajuan_kembali' => null,
            'catatan_keterlambatan' => $alasan ? "Pengajuan pengembalian ditolak admin: {$alasan}" : $peminjaman->catatan_keterlambatan,
        ]);

        return $peminjaman->fresh(['buku', 'eksemplar', 'pengguna']);
    }

    /**
     * Input data keterlambatan dan sanksi oleh Admin (sesuai flowchart penanganan keterlambatan):
     * Ketika user belum mengembalikan buku setelah waktu lewat batas, user menerima sanksi.
     */
    public function inputKeterlambatanDanSanksi(
        string $idPeminjaman,
        string $sanksi,
        ?string $catatan = null
    ): PeminjamanBuku {
        $peminjaman = PeminjamanBuku::with(['pengguna', 'buku', 'eksemplar'])->findOrFail($idPeminjaman);

        if ($peminjaman->tanggal_kembali !== null) {
            throw ValidationException::withMessages([
                'id_peminjaman' => ['Buku sudah dikembalikan sebelumnya. Sanksi keterlambatan aktif hanya dapat dicatat untuk pinjaman yang belum kembali.'],
            ]);
        }

        $hariTerlambat = $peminjaman->hitungHariTerlambat(Carbon::now());

        $peminjaman->update([
            'status' => 'Terlambat',
            'hari_keterlambatan' => max(1, $hariTerlambat),
            'sanksi' => $sanksi,
            'status_sanksi' => 'Aktif',
            'tanggal_sanksi' => Carbon::now(),
            'catatan_keterlambatan' => $catatan ?: "Sanksi diberikan: {$sanksi}. Terlambat {$hariTerlambat} hari dan belum mengembalikan buku.",
            'notifikasi_peringatan_dikirim' => true,
            'tanggal_notifikasi' => $peminjaman->tanggal_notifikasi ?: Carbon::now(),
        ]);

        return $peminjaman->fresh(['pengguna', 'buku', 'eksemplar']);
    }
}
