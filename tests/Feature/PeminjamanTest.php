<?php

namespace Tests\Feature;

use App\Models\Buku;
use App\Models\EksemplarBuku;
use App\Models\PeminjamanBuku;
use App\Models\Pengguna;
use Database\Seeders\PerpustakaanSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PeminjamanTest extends TestCase
{
    use RefreshDatabase;

    protected Pengguna $admin;

    protected Pengguna $studentHasLoan;

    protected Pengguna $studentNoLoan;

    protected EksemplarBuku $availableExemplar;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PerpustakaanSeeder::class);

        $this->admin = Pengguna::where('email', 'arifin@mncu.ac.id')->firstOrFail();
        $this->studentHasLoan = Pengguna::where('id_pengguna', 'MNCU00004')->firstOrFail(); // Fauzi has PJ00001
        $this->studentNoLoan = Pengguna::where('id_pengguna', 'MNCU00006')->firstOrFail();  // Nabila has PJ00002 (Kembali)
        $this->availableExemplar = EksemplarBuku::where('status', 'Tersedia')->firstOrFail();
    }

    /**
     * Tamu (guest) yang belum login tidak dapat mengakses halaman manajemen peminjaman.
     */
    public function test_guests_cannot_access_admin_peminjaman(): void
    {
        $this->get('/admin/peminjaman')->assertRedirect(route('login'));
        $this->get('/admin/peminjaman/create')->assertRedirect(route('login'));
    }

    /**
     * Super Admin dapat melihat halaman index peminjaman beserta metrik sirkulasi & keterlambatan.
     */
    public function test_admin_can_view_peminjaman_index_and_stats(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.peminjaman.index'));

        $response->assertStatus(200);
        $response->assertSee('Sirkulasi Peminjaman');
        $response->assertSee('Keterlambatan Buku');
        $response->assertSee('Total Peminjaman');
        $response->assertSee('Sedang Dipinjam');
        $response->assertSee('Keterlambatan');
        $response->assertSee('Selesai Kembali');
        $response->assertSee('Catat Peminjaman Baru');
        $response->assertSee('API Sanctum');
    }

    /**
     * Super Admin dapat membuka halaman form peminjaman baru.
     */
    public function test_admin_can_view_create_peminjaman_page(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.peminjaman.create'));

        $response->assertStatus(200);
        $response->assertSee('Catat Transaksi Peminjaman Buku');
        $response->assertSee('Pilih Mahasiswa');
        $response->assertSee('Validasi Eligibilitas');
        $response->assertSee('Pilih Buku');
    }

    /**
     * Sesuai flowchart: Jika ada buku belum dikembalikan, user TIDAK BISA meminjam buku.
     */
    public function test_eligibility_validation_blocks_student_with_unreturned_books(): void
    {
        // Coba pinjam untuk mahasiswa yang masih punya tanggungan (MNCU00004)
        $response = $this->actingAs($this->admin)->post(route('admin.peminjaman.store'), [
            'id_pengguna' => $this->studentHasLoan->id_pengguna,
            'id_eksemplar' => $this->availableExemplar->id_eksemplar,
            'tanggal_pinjam' => now()->toDateString(),
            'tanggal_jatuh_tempo' => now()->addDays(14)->toDateString(),
        ]);

        $response->assertSessionHasErrors('id_pengguna');
        $this->assertTrue($this->studentHasLoan->memilikiTanggunganPinjaman());
    }

    /**
     * Sesuai flowchart: Mahasiswa tanpa tanggungan buku dapat meminjam dan status buku (eksemplar) berkurang menjadi Dipinjam.
     */
    public function test_eligible_student_can_borrow_book_and_exemplar_status_decreases(): void
    {
        $this->assertFalse($this->studentNoLoan->memilikiTanggunganPinjaman());
        $this->assertEquals('Tersedia', $this->availableExemplar->status);

        $response = $this->actingAs($this->admin)->post(route('admin.peminjaman.store'), [
            'id_pengguna' => $this->studentNoLoan->id_pengguna,
            'id_eksemplar' => $this->availableExemplar->id_eksemplar,
            'tanggal_pinjam' => now()->toDateString(),
            'tanggal_jatuh_tempo' => now()->addDays(14)->toDateString(),
            'catatan' => 'Peminjaman untuk bahan tugas akhir',
        ]);

        $response->assertRedirect(route('admin.peminjaman.index'));
        $response->assertSessionHas('success');

        // Verifikasi status eksemplar berkurang menjadi Dipinjam
        $this->availableExemplar->refresh();
        $this->assertEquals('Dipinjam', $this->availableExemplar->status);

        // Verifikasi transaksi tercatat di database
        $this->assertDatabaseHas('peminjaman_buku', [
            'id_pengguna' => $this->studentNoLoan->id_pengguna,
            'id_eksemplar' => $this->availableExemplar->id_eksemplar,
            'status' => 'Dipinjam',
        ]);
    }

    /**
     * Sesuai flowchart: Validasi pengembalian buku gagal jika data peminjaman tidak ditemukan atau sudah dikembalikan.
     */
    public function test_return_validation_fails_for_non_existent_or_already_returned_loan(): void
    {
        // PJ00002 sudah dikembalikan sebelumnya di seeder
        $response = $this->actingAs($this->admin)->post(route('admin.peminjaman.kembalikan', 'PJ00002'));

        $response->assertSessionHasErrors('id_peminjaman');
    }

    /**
     * Sesuai flowchart: Pengembalian buku berhasil dan status buku (eksemplar) kembali bertambah menjadi Tersedia.
     */
    public function test_return_book_successfully_increases_exemplar_status_to_tersedia(): void
    {
        $loan = PeminjamanBuku::where('id_peminjaman', 'PJ00001')->firstOrFail();
        $eksemplar = $loan->eksemplar;
        $this->assertEquals('Dipinjam', $eksemplar->status);

        $response = $this->actingAs($this->admin)->post(route('admin.peminjaman.kembalikan', $loan->id_peminjaman), [
            'catatan' => 'Buku diserahkan langsung di loket sirkulasi.',
        ]);

        $response->assertRedirect(route('admin.peminjaman.index'));
        $response->assertSessionHas('success');

        $loan->refresh();
        $eksemplar->refresh();

        $this->assertEquals('Kembali', $loan->status);
        $this->assertNotNull($loan->tanggal_kembali);
        $this->assertEquals('Tersedia', $eksemplar->status);
    }

    /**
     * Pengujian keterlambatan: Menampilkan durasi hari keterlambatan tanpa pembebanan sanksi denda.
     */
    public function test_overdue_calculation_displays_late_days_without_sanction_fine(): void
    {
        $loan = PeminjamanBuku::where('id_peminjaman', 'PJ00001')->firstOrFail();

        $this->assertTrue($loan->isTerlambat());
        $this->assertGreaterThan(0, $loan->hitungHariTerlambat());

        $response = $this->actingAs($this->admin)->get(route('admin.peminjaman.show', $loan->id_peminjaman));
        $response->assertStatus(200);
        $response->assertSee('Informasi Keterlambatan');
        $response->assertSee('tidak diberlakukan sanksi denda finansial');
    }

    /**
     * Admin dapat mengirimkan notifikasi peringatan bahwa batas waktu pengembalian telah lewat.
     */
    public function test_admin_can_send_overdue_warning_notification(): void
    {
        $loan = PeminjamanBuku::where('id_peminjaman', 'PJ00001')->firstOrFail();

        $response = $this->actingAs($this->admin)->post(route('admin.peminjaman.kirim-peringatan', $loan->id_peminjaman));

        $response->assertSessionHas('success');
        $loan->refresh();
        $this->assertTrue($loan->notifikasi_peringatan_dikirim);
        $this->assertNotNull($loan->tanggal_notifikasi);
    }

    /**
     * Sanctum API: Cek eligibilitas mahasiswa meminjam buku.
     */
    public function test_sanctum_api_cek_eligibilitas_endpoint(): void
    {
        Sanctum::actingAs($this->admin);

        // 1. Mahasiswa yang punya tanggungan (MNCU00004) -> 422 Non-eligible
        $responseBlocked = $this->getJson("/api/peminjaman/cek-eligibilitas/{$this->studentHasLoan->id_pengguna}");
        $responseBlocked->assertStatus(422)
            ->assertJson([
                'success' => true,
                'data' => [
                    'eligible' => false,
                ],
            ]);

        // 2. Mahasiswa tanpa tanggungan (MNCU00006) -> 200 Eligible
        $responsePassed = $this->getJson("/api/peminjaman/cek-eligibilitas/{$this->studentNoLoan->id_pengguna}");
        $responsePassed->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'eligible' => true,
                ],
            ]);
    }

    /**
     * Sanctum API: Alur lengkap peminjaman dan pengembalian buku via API.
     */
    public function test_sanctum_api_can_create_loan_and_return_book(): void
    {
        Sanctum::actingAs($this->admin);

        // 1. Pinjam buku via Sanctum API
        $responsePinjam = $this->postJson('/api/peminjaman', [
            'id_pengguna' => $this->studentNoLoan->id_pengguna,
            'id_eksemplar' => $this->availableExemplar->id_eksemplar,
            'tanggal_pinjam' => now()->toDateString(),
            'tanggal_jatuh_tempo' => now()->addDays(14)->toDateString(),
            'catatan' => 'Sirkulasi via Sanctum API',
        ]);

        $responsePinjam->assertStatus(201)
            ->assertJson([
                'success' => true,
            ]);

        $idPeminjaman = $responsePinjam->json('data.id_peminjaman');

        // Status eksemplar harus berkurang
        $this->assertEquals('Dipinjam', $this->availableExemplar->fresh()->status);

        // 2. Validasi pengembalian via API
        $responseValidasi = $this->postJson('/api/peminjaman/validasi-pengembalian', [
            'id_peminjaman' => $idPeminjaman,
        ]);
        $responseValidasi->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        // 3. Kembalikan buku via Sanctum API
        $responseKembali = $this->postJson("/api/peminjaman/{$idPeminjaman}/kembalikan", [
            'catatan' => 'Kembali via API',
        ]);

        $responseKembali->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'status' => 'Kembali',
                    'status_eksemplar' => 'Tersedia',
                ],
            ]);

        // Status eksemplar harus kembali Tersedia
        $this->assertEquals('Tersedia', $this->availableExemplar->fresh()->status);
    }

    /**
     * Admin dapat menginput data keterlambatan dan sanksi pemustaka yang belum mengembalikan buku.
     */
    public function test_admin_can_record_keterlambatan_and_sanksi_for_overdue_loan(): void
    {
        $overdueLoan = PeminjamanBuku::whereNull('tanggal_kembali')->firstOrFail();

        $response = $this->actingAs($this->admin)->post(route('admin.peminjaman.input-keterlambatan', $overdueLoan->id_peminjaman), [
            'sanksi' => 'Penangguhan Peminjaman 1 Bulan',
            'status_sanksi' => 'Aktif',
            'catatan_admin' => 'Pemustaka belum mengembalikan buku tepat waktu setelah dikirim notifikasi.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $overdueLoan->refresh();
        $this->assertEquals('Penangguhan Peminjaman 1 Bulan', $overdueLoan->sanksi);
        $this->assertEquals('Aktif', $overdueLoan->status_sanksi);
        $this->assertNotNull($overdueLoan->tanggal_sanksi);
    }
}
