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

class StatisticAdminController extends Controller
{
    /**
     * Tampilkan statistik perpustakaan berdasarkan filter tahun, bulan, atau minggu
     * Sesuai alur flowchart:
     * - Statistik -> Pilih tahun, bulan, atau minggu ->
     *   1. Statistik data member
     *   2. Statistik member yang mengembalikan buku & buku yang dikembalikan
     *   3. Statistik member yang meminjam & buku yang dipinjam
     *   4. Statistik data buku yang ditambah
     */
    public function index(Request $request): View
    {
        $periode = (string) $request->query('periode', 'bulan'); // 'minggu', 'bulan', 'tahun', 'semua'
        $tahun = (int) $request->query('tahun', now()->year);
        $bulan = (int) $request->query('bulan', now()->month);
        $minggu = (int) $request->query('minggu', max(1, min(5, (int) ceil(now()->day / 7))));

        [$startDate, $endDate, $periodeLabel] = $this->resolveDateRange($periode, $tahun, $bulan, $minggu);

        // 1. STATISTIK DATA MEMBER
        $memberBaruQuery = Pengguna::with(['peran', 'prodi'])
            ->where('is_member', true)
            ->whereBetween('member_sejak', [$startDate, $endDate]);

        $totalMemberBaru = (clone $memberBaruQuery)->count();
        $memberBaruList = (clone $memberBaruQuery)->latest('member_sejak')->limit(15)->get();

        $totalMemberAktif = Pengguna::where('is_member', true)->count();
        $totalMemberMahasiswa = Pengguna::where('is_member', true)
            ->whereHas('peran', fn ($q) => $q->where('nama_peran', 'Mahasiswa'))
            ->count();
        $totalMemberDosen = Pengguna::where('is_member', true)
            ->whereHas('peran', fn ($q) => $q->where('nama_peran', 'Dosen'))
            ->count();

        // 2. STATISTIK MEMBER YANG MENGEMBALIKAN BUKU & BUKU YANG DIKEMBALIKAN
        $pengembalianQuery = PeminjamanBuku::with(['pengguna.peran', 'pengguna.prodi', 'buku.kategoriBuku', 'eksemplar'])
            ->whereNotNull('tanggal_kembali')
            ->whereBetween('tanggal_kembali', [$startDate->toDateString(), $endDate->toDateString()]);

        $totalBukuDikembalikan = (clone $pengembalianQuery)->count();
        $totalMemberMengembalikan = (clone $pengembalianQuery)->distinct('id_pengguna')->count('id_pengguna');
        $pengembalianList = (clone $pengembalianQuery)->latest('tanggal_kembali')->limit(15)->get();

        // 3. STATISTIK MEMBER YANG MEMINJAM & BUKU YANG DIPINJAM
        $peminjamanQuery = PeminjamanBuku::with(['pengguna.peran', 'pengguna.prodi', 'buku.kategoriBuku', 'eksemplar'])
            ->whereBetween('tanggal_pinjam', [$startDate->toDateString(), $endDate->toDateString()]);

        $totalBukuDipinjam = (clone $peminjamanQuery)->count();
        $totalMemberMeminjam = (clone $peminjamanQuery)->distinct('id_pengguna')->count('id_pengguna');
        $peminjamanList = (clone $peminjamanQuery)->latest('tanggal_pinjam')->limit(15)->get();

        // 4. STATISTIK DATA BUKU YANG DITAMBAH
        $bukuBaruQuery = Buku::with(['kategoriBuku', 'penerbit', 'eksemplar'])
            ->whereBetween('created_at', [$startDate, $endDate]);

        $totalBukuDitambah = (clone $bukuBaruQuery)->count();
        $bukuBaruList = (clone $bukuBaruQuery)->latest('created_at')->limit(15)->get();
        $totalEksemplarDitambah = EksemplarBuku::whereBetween('created_at', [$startDate, $endDate])->count();

        $tahunList = range(now()->year, now()->year - 4);

        return view('admin.statistics.index', compact(
            'periode',
            'tahun',
            'bulan',
            'minggu',
            'periodeLabel',
            'startDate',
            'endDate',
            'totalMemberBaru',
            'memberBaruList',
            'totalMemberAktif',
            'totalMemberMahasiswa',
            'totalMemberDosen',
            'totalBukuDikembalikan',
            'totalMemberMengembalikan',
            'pengembalianList',
            'totalBukuDipinjam',
            'totalMemberMeminjam',
            'peminjamanList',
            'totalBukuDitambah',
            'totalEksemplarDitambah',
            'bukuBaruList',
            'tahunList'
        ));
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
