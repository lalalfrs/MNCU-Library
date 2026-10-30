<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\EksemplarBuku;
use App\Models\PeminjamanBuku;
use App\Models\Pengguna;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportAdminController extends Controller
{
    /**
     * Tampilkan laporan komprehensif perpustakaan berdasarkan filter tahun, bulan, atau minggu.
     * Sesuai alur flowchart:
     * - Report -> Pilih tahun, bulan, atau minggu ->
     *   1. Menampilkan total koleksi buku
     *   2. Menampilkan total anggota member
     *   3. Menampilkan total user yang meminjam buku
     *   4. Menampilkan total buku yang belum dikembalikan
     */
    public function index(Request $request): View
    {
        $periode = (string) $request->query('periode', 'bulan'); // 'minggu', 'bulan', 'tahun', 'semua'
        $tahun = (int) $request->query('tahun', now()->year);
        $bulan = (int) $request->query('bulan', now()->month);
        $minggu = (int) $request->query('minggu', max(1, min(5, (int) ceil(now()->day / 7))));

        [$startDate, $endDate, $periodeLabel] = $this->resolveDateRange($periode, $tahun, $bulan, $minggu);

        // 1. MENAMPILKAN TOTAL KOLEKSI BUKU
        $totalJudulBuku = Buku::count();
        $totalEksemplarFisik = EksemplarBuku::count();
        $totalEksemplarTersedia = EksemplarBuku::where('status', 'Tersedia')->count();
        $totalEksemplarDipinjam = EksemplarBuku::where('status', 'Dipinjam')->count();

        // 2. MENAMPILKAN TOTAL ANGGOTA MEMBER
        $totalAnggotaMember = Pengguna::where('is_member', true)->count();
        $totalMemberMahasiswa = Pengguna::where('is_member', true)
            ->whereHas('peran', fn ($q) => $q->where('nama_peran', 'Mahasiswa'))
            ->count();
        $totalMemberDosen = Pengguna::where('is_member', true)
            ->whereHas('peran', fn ($q) => $q->where('nama_peran', 'Dosen'))
            ->count();
        $memberBaruPeriode = Pengguna::where('is_member', true)
            ->whereBetween('member_sejak', [$startDate, $endDate])
            ->count();

        // 3. MENAMPILKAN TOTAL USER YANG MEMINJAM BUKU (pada periode terpilih)
        $peminjamQuery = PeminjamanBuku::with(['pengguna.peran', 'pengguna.prodi', 'buku.kategoriBuku', 'eksemplar'])
            ->whereBetween('tanggal_pinjam', [$startDate->toDateString(), $endDate->toDateString()]);

        $totalUserMeminjam = (clone $peminjamQuery)->distinct('id_pengguna')->count('id_pengguna');
        $totalTransaksiPeminjaman = (clone $peminjamQuery)->count();
        $daftarPeminjamanPeriode = (clone $peminjamQuery)->latest('tanggal_pinjam')->get();

        // 4. MENAMPILKAN TOTAL BUKU YANG BELUM DIKEMBALIKAN
        $belumKembaliQuery = PeminjamanBuku::with(['pengguna.peran', 'pengguna.prodi', 'buku', 'eksemplar'])
            ->whereNull('tanggal_kembali')
            ->whereIn('status', ['Dipinjam', 'Terlambat', 'Menunggu Verifikasi']);

        $totalBukuBelumKembali = (clone $belumKembaliQuery)->count();
        $daftarBukuBelumKembali = (clone $belumKembaliQuery)->orderBy('tanggal_jatuh_tempo')->get();

        $tahunList = range(now()->year, now()->year - 4);

        return view('admin.reports.index', compact(
            'periode',
            'tahun',
            'bulan',
            'minggu',
            'periodeLabel',
            'startDate',
            'endDate',
            'totalJudulBuku',
            'totalEksemplarFisik',
            'totalEksemplarTersedia',
            'totalEksemplarDipinjam',
            'totalAnggotaMember',
            'totalMemberMahasiswa',
            'totalMemberDosen',
            'memberBaruPeriode',
            'totalUserMeminjam',
            'totalTransaksiPeminjaman',
            'daftarPeminjamanPeriode',
            'totalBukuBelumKembali',
            'daftarBukuBelumKembali',
            'tahunList'
        ));
    }

    /**
     * Unduh berkas rekapitulasi laporan dalam format CSV resmi perpustakaan.
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        $periode = (string) $request->query('periode', 'bulan');
        $tahun = (int) $request->query('tahun', now()->year);
        $bulan = (int) $request->query('bulan', now()->month);
        $minggu = (int) $request->query('minggu', max(1, min(5, (int) ceil(now()->day / 7))));

        [$startDate, $endDate, $periodeLabel] = $this->resolveDateRange($periode, $tahun, $bulan, $minggu);

        $filename = 'Laporan_Perpustakaan_MNCU_'.str_replace(' ', '_', $periodeLabel).'.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () use ($startDate, $endDate, $periodeLabel): void {
            $handle = fopen('php://output', 'w');
            // Write UTF-8 BOM for Excel compatibility
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, ['LAPORAN REKAPITULASI PERPUSTAKAAN MNC UNIVERSITY']);
            fputcsv($handle, ['Periode', $periodeLabel]);
            fputcsv($handle, ['Tanggal Dibuat', now()->translatedFormat('d F Y H:i:s')]);
            fputcsv($handle, []);

            // Ringkasan 4 Pilar Flowchart
            fputcsv($handle, ['--- RINGKASAN DATA (4 PILAR FLOWCHART) ---']);
            fputcsv($handle, ['Metrik', 'Nilai', 'Keterangan']);
            fputcsv($handle, ['Total Koleksi Buku (Judul)', Buku::count(), 'Total seluruh judul buku terdaftar']);
            fputcsv($handle, ['Total Koleksi Fisik (Eksemplar)', EksemplarBuku::count(), 'Total seluruh eksemplar fisik']);
            fputcsv($handle, ['Total Anggota Member', Pengguna::where('is_member', true)->count(), 'Mahasiswa & Dosen berstatus member']);
            fputcsv($handle, ['Total User yang Meminjam (Periode)', PeminjamanBuku::whereBetween('tanggal_pinjam', [$startDate->toDateString(), $endDate->toDateString()])->distinct('id_pengguna')->count('id_pengguna'), "Pemustaka unik yang meminjam pada {$periodeLabel}"]);
            fputcsv($handle, ['Total Buku yang Belum Dikembalikan', PeminjamanBuku::whereNull('tanggal_kembali')->whereIn('status', ['Dipinjam', 'Terlambat', 'Menunggu Verifikasi'])->count(), 'Status Dipinjam, Terlambat, atau Menunggu Verifikasi']);
            fputcsv($handle, []);

            // Tabel Buku Belum Dikembalikan
            fputcsv($handle, ['--- DATA BUKU YANG BELUM DIKEMBALIKAN ---']);
            fputcsv($handle, ['No', 'ID Peminjaman', 'Nama Member', 'NIM/NIDN', 'Judul Buku', 'No. Barcode', 'Tanggal Pinjam', 'Jatuh Tempo', 'Status', 'Hari Keterlambatan']);

            $belumKembali = PeminjamanBuku::with(['pengguna', 'buku', 'eksemplar'])
                ->whereNull('tanggal_kembali')
                ->whereIn('status', ['Dipinjam', 'Terlambat', 'Menunggu Verifikasi'])
                ->orderBy('tanggal_jatuh_tempo')
                ->get();

            $no = 1;
            foreach ($belumKembali as $pinjam) {
                fputcsv($handle, [
                    $no++,
                    $pinjam->id_peminjaman,
                    $pinjam->pengguna?->nama_lengkap ?? '-',
                    $pinjam->pengguna?->nomor_identitas ?? '-',
                    $pinjam->buku?->judul_buku ?? '-',
                    $pinjam->eksemplar?->nomor_barcode ?? '-',
                    $pinjam->tanggal_pinjam ? $pinjam->tanggal_pinjam->format('Y-m-d') : '-',
                    $pinjam->tanggal_jatuh_tempo ? $pinjam->tanggal_jatuh_tempo->format('Y-m-d') : '-',
                    $pinjam->status,
                    $pinjam->hitungHariTerlambat(),
                ]);
            }

            fputcsv($handle, []);

            // Tabel Transaksi Peminjaman pada Periode Terpilih
            fputcsv($handle, ["--- DAFTAR TRANSAKSI PEMINJAMAN PERIODE: {$periodeLabel} ---"]);
            fputcsv($handle, ['No', 'ID Peminjaman', 'Nama Member', 'NIM/NIDN', 'Judul Buku', 'Tanggal Pinjam', 'Jatuh Tempo', 'Tanggal Kembali', 'Status']);

            $transaksiPeriode = PeminjamanBuku::with(['pengguna', 'buku'])
                ->whereBetween('tanggal_pinjam', [$startDate->toDateString(), $endDate->toDateString()])
                ->latest('tanggal_pinjam')
                ->get();

            $noT = 1;
            foreach ($transaksiPeriode as $trx) {
                fputcsv($handle, [
                    $noT++,
                    $trx->id_peminjaman,
                    $trx->pengguna?->nama_lengkap ?? '-',
                    $trx->pengguna?->nomor_identitas ?? '-',
                    $trx->buku?->judul_buku ?? '-',
                    $trx->tanggal_pinjam ? $trx->tanggal_pinjam->format('Y-m-d') : '-',
                    $trx->tanggal_jatuh_tempo ? $trx->tanggal_jatuh_tempo->format('Y-m-d') : '-',
                    $trx->tanggal_kembali ? $trx->tanggal_kembali->format('Y-m-d') : 'Belum Kembali',
                    $trx->status,
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }

    /**
     * Hitung batas awal dan akhir tanggal berdasarkan filter periode.
     *
     * @return array{0: Carbon, 1: Carbon, 2: string}
     */
    private function resolveDateRange(string $periode, int $tahun, int $bulan, int $minggu): array
    {
        if ($periode === 'tahun') {
            $start = Carbon::createFromDate($tahun, 1, 1)->startOfDay();
            $end = Carbon::createFromDate($tahun, 12, 31)->endOfDay();
            $label = "Tahun {$tahun}";
        } elseif ($periode === 'minggu') {
            $monthStart = Carbon::createFromDate($tahun, $bulan, 1)->startOfDay();
            $startDay = ($minggu - 1) * 7 + 1;
            $endDay = min($minggu * 7, $monthStart->daysInMonth);
            $start = Carbon::createFromDate($tahun, $bulan, $startDay)->startOfDay();
            $end = Carbon::createFromDate($tahun, $bulan, $endDay)->endOfDay();
            $label = "Minggu ke-{$minggu} ({$start->translatedFormat('d M')} - {$end->translatedFormat('d M Y')})";
        } elseif ($periode === 'semua') {
            $start = Carbon::create(2020, 1, 1)->startOfDay();
            $end = Carbon::now()->endOfDay();
            $label = 'Semua Waktu';
        } else {
            $start = Carbon::createFromDate($tahun, $bulan, 1)->startOfDay();
            $end = Carbon::createFromDate($tahun, $bulan, 1)->endOfMonth()->endOfDay();
            $label = Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F Y');
        }

        return [$start, $end, $label];
    }
}
