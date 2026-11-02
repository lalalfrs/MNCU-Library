<?php

namespace Tests\Feature;

use App\Models\Pengguna;
use Database\Seeders\PerpustakaanSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StatisticAndReportTest extends TestCase
{
    use RefreshDatabase;

    protected Pengguna $superAdmin;

    protected Pengguna $pustakawan;

    protected Pengguna $mahasiswa;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(PerpustakaanSeeder::class);

        $this->superAdmin = Pengguna::where('email', 'arifin@mncu.ac.id')->firstOrFail();
        $this->pustakawan = Pengguna::where('email', 'sarah.amalia@mncu.ac.id')->firstOrFail();
        $this->mahasiswa = Pengguna::where('email', 'fauzi.mhs@mncu.ac.id')->firstOrFail();
    }

    /**
     * OPAC menampilkan tombol Manajemen Data Member, Statistik, dan Report untuk Super Admin dan Pustakawan.
     */
    public function test_opac_displays_member_management_statistic_and_report_buttons_for_staff(): void
    {
        // 1. Super Admin
        $respAdmin = $this->actingAs($this->superAdmin)->get(route('opac'));
        $respAdmin->assertStatus(200);
        $respAdmin->assertSee('Manajemen Data Member');
        $respAdmin->assertSee('Statistik');
        $respAdmin->assertSee('Report');

        // 2. Pustakawan
        $respPustakawan = $this->actingAs($this->pustakawan)->get(route('opac'));
        $respPustakawan->assertStatus(200);
        $respPustakawan->assertSee('Manajemen Data Member');
        $respPustakawan->assertSee('Statistik');
        $respPustakawan->assertSee('Report');

        // 3. Mahasiswa reguler tidak melihat tombol manajemen ini
        $respMhs = $this->actingAs($this->mahasiswa)->get(route('opac'));
        $respMhs->assertStatus(200);
        $respMhs->assertDontSee('Manajemen Data Member');
        $respMhs->assertDontSee('title="Buka Statistik Perpustakaan (Sesuai Flowchart)"', false);
        $respMhs->assertDontSee('title="Buka Laporan & Rekapitulasi Perpustakaan (Sesuai Flowchart)"', false);
    }

    /**
     * Halaman Statistik dapat diakses dan merender 4 pilar flowchart.
     */
    public function test_statistic_page_renders_four_flowchart_branches_and_filters(): void
    {
        $response = $this->actingAs($this->superAdmin)->get(route('admin.statistics.index'));

        $response->assertStatus(200);
        $response->assertSee('Statistik Perpustakaan MNC University');
        $response->assertSee('1. Statistik Data Member');
        $response->assertSee('2. Statistik Pengembalian Buku');
        $response->assertSee('3. Statistik Peminjaman Buku');
        $response->assertSee('4. Statistik Buku yang Ditambah');

        // Tes filter mingguan, bulanan, tahunan
        $respMinggu = $this->actingAs($this->superAdmin)->get(route('admin.statistics.index', [
            'periode' => 'minggu',
            'tahun' => now()->year,
            'bulan' => now()->month,
            'minggu' => 1,
        ]));
        $respMinggu->assertStatus(200);
        $respMinggu->assertSee('Minggu ke-1');

        $respTahun = $this->actingAs($this->pustakawan)->get(route('admin.statistics.index', [
            'periode' => 'tahun',
            'tahun' => now()->year,
        ]));
        $respTahun->assertStatus(200);
        $respTahun->assertSee('Tahun '.now()->year);
    }

    /**
     * Halaman Report dapat diakses dan merender 4 metrik ringkasan flowchart.
     */
    public function test_report_page_renders_four_flowchart_metrics_and_allows_csv_export(): void
    {
        $response = $this->actingAs($this->superAdmin)->get(route('admin.reports.index'));

        $response->assertStatus(200);
        $response->assertSee('Laporan Rekapitulasi Perpustakaan');
        $response->assertSee('1. Total Koleksi Buku');
        $response->assertSee('2. Total Anggota Member');
        $response->assertSee('3. Total User Meminjam Buku');
        $response->assertSee('4. Buku Belum Dikembalikan');
        $response->assertSee('Cetak PDF');
        $response->assertSee('PERPUSTAKAAN MNC UNIVERSITY');
        $response->assertSee('Dr. H. Muhammad Arifin, M.Hum.');

        // Tes Export CSV
        $respCsv = $this->actingAs($this->superAdmin)->get(route('admin.reports.export-csv'));
        $respCsv->assertStatus(200);
        $this->assertStringContainsString('text/csv', (string) $respCsv->headers->get('content-type'));
    }

    /**
     * Mahasiswa diblokir dari mengakses halaman statistik dan laporan.
     */
    public function test_students_cannot_access_statistics_and_reports(): void
    {
        $respStat = $this->actingAs($this->mahasiswa)->get(route('admin.statistics.index'));
        $respStat->assertStatus(403);

        $respReport = $this->actingAs($this->mahasiswa)->get(route('admin.reports.index'));
        $respReport->assertStatus(403);
    }
}
