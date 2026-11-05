<?php

namespace Tests\Feature;

use App\Models\Buku;
use App\Models\EksemplarBuku;
use App\Models\PeminjamanBuku;
use App\Models\Pengguna;
use Database\Seeders\PerpustakaanSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
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
    }

    /**
     * Halaman index dan detail peminjaman tetap aman dan tampil rapi jika ada permohonan pinjam dengan eksemplar null (misal permohonan OPAC ditolak).
     */
    public function test_admin_peminjaman_renders_cleanly_when_loan_has_null_eksemplar(): void
    {
        $rejectedLoan = PeminjamanBuku::create([
            'id_peminjaman' => 'PJTEST99',
            'uuid' => (string) Str::uuid(),
            'id_pengguna' => $this->studentNoLoan->id_pengguna,
            'id_buku' => 'MNCU00001',
            'id_eksemplar' => null,
            'tanggal_pinjam' => now()->toDateString(),
            'tanggal_jatuh_tempo' => now()->addDays(7)->toDateString(),
            'status' => 'Ditolak',
            'alasan_penolakan' => 'Stok belum tersedia',
        ]);

        $resIndex = $this->actingAs($this->admin)->get(route('admin.peminjaman.index'));
        $resIndex->assertStatus(200);
        $resIndex->assertSee('PJTEST99');
        $resIndex->assertSee('Eksemplar Belum Ditentukan');

        $resShow = $this->actingAs($this->admin)->get(route('admin.peminjaman.show', $rejectedLoan->id_peminjaman));
        $resShow->assertStatus(200);
        $resShow->assertSee('PJTEST99');
        $resShow->assertSee('Eksemplar fisik belum ditetapkan atau permohonan telah ditolak.');
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
     * Setiap user boleh meminjam maksimal 3 buku:
     * Mahasiswa masih dapat meminjam buku ke-2 dan ke-3, namun ditolak pada buku ke-4.
     */
    public function test_eligibility_validation_allows_up_to_three_books_and_blocks_fourth(): void
    {
        // Fauzi (MNCU00004) baru memiliki 1 buku pinjaman aktif (PJ00001) dari seeder
        $this->assertEquals(1, $this->studentHasLoan->jumlahPinjamanAktif());
        $this->assertFalse($this->studentHasLoan->mencapaiBatasPinjaman());

        $availableCopies = EksemplarBuku::where('status', 'Tersedia')->take(3)->get();
        $this->assertCount(3, $availableCopies);

        // Pinjam buku ke-2 -> Berhasil
        $res2 = $this->actingAs($this->admin)->post(route('admin.peminjaman.store'), [
            'id_pengguna' => $this->studentHasLoan->id_pengguna,
            'id_eksemplar' => $availableCopies[0]->id_eksemplar,
            'tanggal_pinjam' => now()->toDateString(),
            'tanggal_jatuh_tempo' => now()->addDays(14)->toDateString(),
        ]);
        $res2->assertRedirect(route('admin.peminjaman.index'));
        $this->assertEquals(2, $this->studentHasLoan->fresh()->jumlahPinjamanAktif());

        // Pinjam buku ke-3 -> Berhasil
        $res3 = $this->actingAs($this->admin)->post(route('admin.peminjaman.store'), [
            'id_pengguna' => $this->studentHasLoan->id_pengguna,
            'id_eksemplar' => $availableCopies[1]->id_eksemplar,
            'tanggal_pinjam' => now()->toDateString(),
            'tanggal_jatuh_tempo' => now()->addDays(14)->toDateString(),
        ]);
        $res3->assertRedirect(route('admin.peminjaman.index'));
        $this->assertEquals(3, $this->studentHasLoan->fresh()->jumlahPinjamanAktif());
        $this->assertTrue($this->studentHasLoan->fresh()->mencapaiBatasPinjaman());

        // Coba pinjam buku ke-4 -> Ditolak karena sudah mencapai batas maksimal 3 buku
        $res4 = $this->actingAs($this->admin)->post(route('admin.peminjaman.store'), [
            'id_pengguna' => $this->studentHasLoan->id_pengguna,
            'id_eksemplar' => $availableCopies[2]->id_eksemplar,
            'tanggal_pinjam' => now()->toDateString(),
            'tanggal_jatuh_tempo' => now()->addDays(14)->toDateString(),
        ]);

        $res4->assertSessionHasErrors('id_pengguna');
        $this->assertEquals(3, $this->studentHasLoan->fresh()->jumlahPinjamanAktif());
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
        $response->assertSee('Rp 500 / hari');
        $response->assertSee('Cash / Tunai');
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
     * Sanctum API: Cek eligibilitas mahasiswa meminjam buku (batas maksimal 3 buku).
     */
    public function test_sanctum_api_cek_eligibilitas_endpoint(): void
    {
        Sanctum::actingAs($this->admin);

        // 1. Mahasiswa dengan 1 pinjaman aktif (MNCU00004) masih eligible (sisa kuota 2 dari 3 buku)
        $responseOneLoan = $this->getJson("/api/peminjaman/cek-eligibilitas/{$this->studentHasLoan->id_pengguna}");
        $responseOneLoan->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'eligible' => true,
                    'active_loans_count' => 1,
                    'max_loans' => 3,
                    'remaining_quota' => 2,
                ],
            ]);

        // Tambahkan 2 pinjaman aktif lagi sehingga mencapai 3 buku
        $extraCopies = EksemplarBuku::where('status', 'Tersedia')->take(2)->get();
        foreach ($extraCopies as $copy) {
            PeminjamanBuku::create([
                'id_pengguna' => $this->studentHasLoan->id_pengguna,
                'id_buku' => $copy->id_buku,
                'id_eksemplar' => $copy->id_eksemplar,
                'tanggal_pinjam' => now()->toDateString(),
                'tanggal_jatuh_tempo' => now()->addDays(14)->toDateString(),
                'status' => 'Dipinjam',
            ]);
            $copy->update(['status' => 'Dipinjam']);
        }

        // 2. Mahasiswa yang sudah mencapai 3 pinjaman aktif -> 422 Non-eligible
        $responseBlocked = $this->getJson("/api/peminjaman/cek-eligibilitas/{$this->studentHasLoan->id_pengguna}");
        $responseBlocked->assertStatus(422)
            ->assertJson([
                'success' => true,
                'data' => [
                    'eligible' => false,
                    'active_loans_count' => 3,
                    'max_loans' => 3,
                    'remaining_quota' => 0,
                ],
            ]);

        // 3. Mahasiswa tanpa tanggungan (MNCU00006) -> 200 Eligible
        $responsePassed = $this->getJson("/api/peminjaman/cek-eligibilitas/{$this->studentNoLoan->id_pengguna}");
        $responsePassed->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'eligible' => true,
                    'active_loans_count' => 0,
                    'max_loans' => 3,
                    'remaining_quota' => 3,
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

    /**
     * Denda dihitung Rp 500 per hari keterlambatan dan metode pembayaran default adalah Cash.
     */
    public function test_overdue_loan_calculates_fine_at_500_per_day_cash(): void
    {
        $loan = PeminjamanBuku::whereNull('tanggal_kembali')->firstOrFail();
        $loan->update([
            'tanggal_pinjam' => now()->subDays(10)->toDateString(),
            'tanggal_jatuh_tempo' => now()->subDays(3)->toDateString(),
            'status' => 'Terlambat',
            'hari_keterlambatan' => 3,
        ]);

        $this->assertEquals(3, $loan->hitungHariTerlambat());
        $this->assertEquals(1500, $loan->hitungNominalDenda()); // 3 hari * 500 = 1500
        $this->assertTrue($loan->memilikiDenda());
    }

    /**
     * Form denda masuk ke Inbox user yang telat mengembalikan buku dengan opsi Cash dan tarif Rp 500/hari.
     */
    public function test_user_inbox_displays_denda_form_with_cash_option(): void
    {
        $overdueLoan = PeminjamanBuku::whereNull('tanggal_kembali')->firstOrFail();
        $overdueLoan->update([
            'id_pengguna' => $this->studentHasLoan->id_pengguna,
            'tanggal_pinjam' => now()->subDays(10)->toDateString(),
            'tanggal_jatuh_tempo' => now()->subDays(4)->toDateString(),
            'status' => 'Terlambat',
            'hari_keterlambatan' => 4,
            'nominal_denda' => 2000,
            'status_pembayaran_denda' => 'Belum Lunas',
            'metode_pembayaran_denda' => 'Cash',
        ]);

        $response = $this->actingAs($this->studentHasLoan)->get(route('inbox'));

        $response->assertStatus(200);
        $response->assertSee('Form Tagihan Denda Keterlambatan');
        $response->assertSee('Rp 500 / hari');
        $response->assertSee('Cash / Tunai');
        $response->assertSee('Belum Lunas');
        $response->assertSee('Rp 2.000');
        $response->assertSee(route('inbox.denda', $overdueLoan->id_peminjaman));
    }

    /**
     * Admin dapat mengubah status pelunasan denda bermodel dropdown (Belum Lunas -> Lunas).
     */
    public function test_admin_can_update_denda_payment_status_via_dropdown(): void
    {
        $overdueLoan = PeminjamanBuku::whereNull('tanggal_kembali')->firstOrFail();
        $overdueLoan->update([
            'tanggal_jatuh_tempo' => now()->subDays(2)->toDateString(),
            'status' => 'Terlambat',
            'hari_keterlambatan' => 2,
            'nominal_denda' => 1000,
            'status_pembayaran_denda' => 'Belum Lunas',
        ]);

        // 1. Admin mengubah status menjadi Lunas
        $response = $this->actingAs($this->admin)->post(route('admin.peminjaman.update-status-denda', $overdueLoan->id_peminjaman), [
            'status_pembayaran_denda' => 'Lunas',
            'catatan_denda' => 'Pembayaran tunai diterima langsung di meja sirkulasi.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $overdueLoan->refresh();
        $this->assertEquals('Lunas', $overdueLoan->status_pembayaran_denda);
        $this->assertEquals('Cash', $overdueLoan->metode_pembayaran_denda);
        $this->assertNotNull($overdueLoan->tanggal_pembayaran_denda);
        $this->assertEquals($this->admin->id_pengguna, $overdueLoan->id_pustakawan_denda);

        // 2. Admin dapat mengubah kembali status menjadi Belum Lunas jika diperlukan
        $responseRevert = $this->actingAs($this->admin)->post(route('admin.peminjaman.update-status-denda', $overdueLoan->id_peminjaman), [
            'status_pembayaran_denda' => 'Belum Lunas',
        ]);
        $responseRevert->assertRedirect();

        $overdueLoan->refresh();
        $this->assertEquals('Belum Lunas', $overdueLoan->status_pembayaran_denda);
        $this->assertNull($overdueLoan->tanggal_pembayaran_denda);
    }

    /**
     * Superadmin dan Admin dapat membuka dan mencetak dokumen rincian denda resmi A4
     * yang memuat tanda tangan peminjam yang terlambat dan nama pustakawan.
     */
    public function test_superadmin_can_print_formal_a4_denda_document_with_signatures(): void
    {
        $overdueLoan = PeminjamanBuku::whereNull('tanggal_kembali')->firstOrFail();
        $overdueLoan->update([
            'tanggal_jatuh_tempo' => now()->subDays(5)->toDateString(),
            'status' => 'Terlambat',
            'hari_keterlambatan' => 5,
            'nominal_denda' => 2500,
            'status_pembayaran_denda' => 'Belum Lunas',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.peminjaman.cetak-denda', $overdueLoan->id_peminjaman));

        $response->assertStatus(200);
        $response->assertSee('Surat Rincian Denda Keterlambatan Pengembalian Buku');
        $response->assertSee('Universitas MNC');
        $response->assertSee('Rp 500 / hari');
        $response->assertSee('Cash / Tunai');
        $response->assertSee('Rp 2.500');
        $response->assertSee('5 Hari');
        // Tanda tangan peminjam yang terlambat
        $response->assertSee('Peminjam / Pemustaka Yang Terlambat');
        $response->assertSee($overdueLoan->pengguna->nama_lengkap);
        // Tanda tangan & nama pustakawan
        $response->assertSee('Petugas Pustakawan / Administrasi Sirkulasi');
        $response->assertSee($this->admin->nama_lengkap);
    }

    /**
     * Pemustaka yang telat dapat melihat lembar dokumen rincian dendanya dari inbox.
     */
    public function test_user_can_view_own_denda_slip_from_inbox(): void
    {
        $overdueLoan = PeminjamanBuku::whereNull('tanggal_kembali')->firstOrFail();
        $overdueLoan->update([
            'id_pengguna' => $this->studentHasLoan->id_pengguna,
            'tanggal_jatuh_tempo' => now()->subDays(2)->toDateString(),
            'status' => 'Terlambat',
            'hari_keterlambatan' => 2,
            'nominal_denda' => 1000,
            'status_pembayaran_denda' => 'Belum Lunas',
        ]);

        $response = $this->actingAs($this->studentHasLoan)->get(route('inbox.denda', $overdueLoan->id_peminjaman));

        $response->assertStatus(200);
        $response->assertSee('Surat Rincian Denda Keterlambatan');
        $response->assertSee($this->studentHasLoan->nama_lengkap);
        $response->assertSee('Rp 1.000');
    }

    /**
     * Admin peminjaman metrics accurately account for all statuses and display badges correctly.
     */
    public function test_admin_peminjaman_metrics_and_status_badges(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.peminjaman.index'));

        $response->assertStatus(200);
        $response->assertSee('Total Peminjaman');
        $response->assertSee('Sedang Dipinjam');
        $response->assertSee('Selesai Kembali');
    }

    /**
     * Staff dapat melihat seluruh sirkulasi di halaman riwayat profil.
     */
    public function test_staff_can_view_all_circulation_in_profile_riwayat(): void
    {
        $response = $this->actingAs($this->admin)->get(route('profile.riwayat', ['lingkup' => 'seluruh']));

        $response->assertStatus(200);
        $response->assertSee('Mode Akses Administrator &amp; Pustakawan', false);
        $response->assertSee('Seluruh Sirkulasi Perpustakaan');
    }
}
