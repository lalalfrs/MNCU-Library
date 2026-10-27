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

class OpacPeminjamanTest extends TestCase
{
    use RefreshDatabase;

    protected Pengguna $admin;

    protected Pengguna $studentHasLoan;

    protected Pengguna $studentNoLoan;

    protected EksemplarBuku $availableExemplar;

    protected Buku $targetBook;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PerpustakaanSeeder::class);

        $this->admin = Pengguna::where('email', 'arifin@mncu.ac.id')->firstOrFail();
        $this->studentHasLoan = Pengguna::where('id_pengguna', 'MNCU00004')->firstOrFail(); // Fauzi has PJ00001
        $this->studentNoLoan = Pengguna::where('id_pengguna', 'MNCU00006')->firstOrFail();  // Nabila has returned book (eligible)
        $this->availableExemplar = EksemplarBuku::where('status', 'Tersedia')->firstOrFail();
        $this->targetBook = $this->availableExemplar->buku;
    }

    /**
     * Tamu yang belum login tidak dapat mengecek eligibilitas maupun meminjam buku.
     */
    public function test_guest_cannot_check_eligibility_or_borrow_via_opac(): void
    {
        $resElig = $this->getJson(route('opac.cek-eligibilitas'));
        $resElig->assertStatus(401);

        $resPinjam = $this->postJson(route('opac.pinjam'), [
            'id_buku' => $this->targetBook->id_buku,
            'tanggal_jatuh_tempo' => now()->addDays(14)->toDateString(),
        ]);
        $resPinjam->assertStatus(401);
    }

    /**
     * Mahasiswa yang masih memiliki buku belum dikembalikan diblokir dari peminjaman baru sesuai flowchart.
     */
    public function test_student_with_unreturned_loans_is_blocked_from_borrowing(): void
    {
        $response = $this->actingAs($this->studentHasLoan)
            ->getJson(route('opac.cek-eligibilitas'));

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data' => [
                'eligible' => false,
            ],
        ]);

        $responseData = $response->json('data');
        $this->assertFalse($responseData['eligible']);
        $this->assertStringContainsString('User tidak bisa meminjam buku', $responseData['message']);
        $this->assertNotEmpty($responseData['active_loans']);

        // Jika mencoba mengirim form peminjaman, sistem menolak dengan kode 422
        $borrowResponse = $this->actingAs($this->studentHasLoan)
            ->postJson(route('opac.pinjam'), [
                'id_buku' => $this->targetBook->id_buku,
                'id_eksemplar' => $this->availableExemplar->id_eksemplar,
                'tanggal_jatuh_tempo' => now()->addDays(7)->toDateString(),
            ]);

        $borrowResponse->assertStatus(422);
    }

    /**
     * Mahasiswa yang tidak memiliki pinjaman aktif eligible untuk meminjam buku.
     */
    public function test_student_without_active_loans_is_eligible_to_borrow(): void
    {
        $response = $this->actingAs($this->studentNoLoan)
            ->getJson(route('opac.cek-eligibilitas'));

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data' => [
                'eligible' => true,
                'active_loans' => [],
            ],
        ]);
    }

    /**
     * Alur peminjaman buku mandiri di katalog OPAC berjalan sukses:
     * - Tanggal pengambilan auto-set hari ini
     * - Tanggal pengembalian diinput oleh user
     * - Status eksemplar buku berkurang menjadi 'Dipinjam'
     * - Nama peminjam tampil di katalog OPAC sehingga admin dan pemustaka lain dapat melihat.
     */
    public function test_student_can_successfully_borrow_book_via_opac(): void
    {
        $initialAvailable = EksemplarBuku::where('id_buku', $this->targetBook->id_buku)
            ->where('status', 'Tersedia')
            ->count();

        $returnDate = now()->addDays(14)->toDateString();

        $response = $this->actingAs($this->studentNoLoan)
            ->postJson(route('opac.pinjam'), [
                'id_buku' => $this->targetBook->id_buku,
                'id_eksemplar' => $this->availableExemplar->id_eksemplar,
                'tanggal_jatuh_tempo' => $returnDate,
                'catatan' => 'Peminjaman mandiri riset skripsi',
            ]);

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
            'data' => [
                'judul_buku' => $this->targetBook->judul_buku,
                'kode_inventaris' => $this->availableExemplar->kode_inventaris,
                'tanggal_pinjam' => now()->toDateString(),
                'tanggal_jatuh_tempo' => $returnDate,
                'nama_peminjam' => $this->studentNoLoan->nama_lengkap,
            ],
        ]);

        // Verifikasi database: Peminjaman tercatat dan status eksemplar berubah menjadi Dipinjam
        $this->assertDatabaseHas('peminjaman_buku', [
            'id_pengguna' => $this->studentNoLoan->id_pengguna,
            'id_buku' => $this->targetBook->id_buku,
            'id_eksemplar' => $this->availableExemplar->id_eksemplar,
            'status' => 'Dipinjam',
        ]);

        $latestLoan = PeminjamanBuku::where('id_pengguna', $this->studentNoLoan->id_pengguna)
            ->where('id_eksemplar', $this->availableExemplar->id_eksemplar)
            ->latest('id_peminjaman')
            ->first();
        $this->assertNotNull($latestLoan);
        $this->assertEquals(now()->toDateString(), $latestLoan->tanggal_pinjam->format('Y-m-d'));
        $this->assertEquals($returnDate, $latestLoan->tanggal_jatuh_tempo->format('Y-m-d'));

        $this->assertEquals('Dipinjam', $this->availableExemplar->fresh()->status);

        // Verifikasi bahwa stok tersedia berkurang 1
        $currentAvailable = EksemplarBuku::where('id_buku', $this->targetBook->id_buku)
            ->where('status', 'Tersedia')
            ->count();
        $this->assertEquals($initialAvailable - 1, $currentAvailable);

        // Verifikasi katalog OPAC menampilkan nama peminjam secara publik ke admin dan pemustaka lain
        $opacResponse = $this->get(route('opac', ['q' => $this->targetBook->judul_buku]));
        $opacResponse->assertStatus(200);
        $opacResponse->assertSee('Sedang Dipinjam:');
        $opacResponse->assertSee($this->studentNoLoan->nama_lengkap);
    }

    /**
     * Validasi via Sanctum token untuk endpoint API peminjaman buku.
     */
    public function test_sanctum_authenticated_user_can_borrow_via_api(): void
    {
        Sanctum::actingAs($this->studentNoLoan, ['*']);

        // Cek eligibilitas via API Sanctum
        $responseElig = $this->getJson("/api/peminjaman/cek-eligibilitas/{$this->studentNoLoan->id_pengguna}");
        $responseElig->assertStatus(200);
        $responseElig->assertJson([
            'success' => true,
            'data' => [
                'eligible' => true,
            ],
        ]);

        // Cari eksemplar tersedia lain
        $exemplar = EksemplarBuku::where('status', 'Tersedia')->firstOrFail();

        // Pinjam via API Sanctum
        $responsePinjam = $this->postJson('/api/peminjaman', [
            'id_buku' => $exemplar->id_buku,
            'id_eksemplar' => $exemplar->id_eksemplar,
            'tanggal_pinjam' => now()->toDateString(),
            'tanggal_jatuh_tempo' => now()->addDays(7)->toDateString(),
            'catatan' => 'Peminjaman via Sanctum API',
        ]);

        $responsePinjam->assertStatus(201);
        $this->assertEquals('Dipinjam', $exemplar->fresh()->status);
    }

    /**
     * Pemustaka yang sedang meminjam buku dapat mengajukan pengembalian via OPAC sekalipun lewat jatuh tempo (terlambat).
     */
    public function test_borrower_can_request_return_via_opac_even_if_overdue(): void
    {
        // Setup peminjaman yang sudah lewat tempo (terlambat)
        $overdueLoan = PeminjamanBuku::where('id_pengguna', $this->studentHasLoan->id_pengguna)
            ->whereNull('tanggal_kembali')
            ->firstOrFail();

        $overdueLoan->update([
            'tanggal_jatuh_tempo' => now()->subDays(5)->toDateString(),
            'status' => 'Terlambat',
            'hari_keterlambatan' => 5,
        ]);

        $this->assertEquals('Terlambat', $overdueLoan->fresh()->status);

        // Ajukan pengembalian via OPAC oleh peminjam
        $response = $this->actingAs($this->studentHasLoan)
            ->postJson(route('opac.ajukan-pengembalian'), [
                'id_peminjaman' => $overdueLoan->id_peminjaman,
                'catatan' => 'Buku sudah saya bawa ke meja sirkulasi lantai 2.',
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data' => [
                'id_peminjaman' => $overdueLoan->id_peminjaman,
                'status' => 'Menunggu Verifikasi',
            ],
        ]);

        $freshLoan = $overdueLoan->fresh();
        $this->assertEquals('Menunggu Verifikasi', $freshLoan->status);
        $this->assertNotNull($freshLoan->tanggal_pengajuan_kembali);
        $this->assertEquals('Buku sudah saya bawa ke meja sirkulasi lantai 2.', $freshLoan->catatan_pengajuan_kembali);
    }

    /**
     * User lain tidak dapat mengajukan pengembalian untuk peminjaman milik orang lain.
     */
    public function test_unauthorized_user_cannot_request_return_for_other_users_loan(): void
    {
        $loan = PeminjamanBuku::where('id_pengguna', $this->studentHasLoan->id_pengguna)
            ->whereNull('tanggal_kembali')
            ->firstOrFail();

        $response = $this->actingAs($this->studentNoLoan)
            ->postJson(route('opac.ajukan-pengembalian'), [
                'id_peminjaman' => $loan->id_peminjaman,
            ]);

        $response->assertStatus(403);
    }

    /**
     * Admin dapat menyetujui pengajuan pengembalian buku dan eksemplar kembali tersedia.
     */
    public function test_admin_can_approve_return_request(): void
    {
        $loan = PeminjamanBuku::where('id_pengguna', $this->studentHasLoan->id_pengguna)
            ->whereNull('tanggal_kembali')
            ->firstOrFail();

        $loan->update([
            'status' => 'Menunggu Verifikasi',
            'tanggal_pengajuan_kembali' => now(),
        ]);

        $exemplar = $loan->eksemplar;
        $this->assertEquals('Dipinjam', $exemplar->status);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.peminjaman.kembalikan', $loan->id_peminjaman), [
                'catatan_kondisi' => 'Kondisi buku baik dan lengkap.',
            ]);

        $response->assertRedirect(route('admin.peminjaman.index'));

        $freshLoan = $loan->fresh();
        $this->assertEquals('Kembali', $freshLoan->status);
        $this->assertNotNull($freshLoan->tanggal_kembali);
        $this->assertEquals('Tersedia', $exemplar->fresh()->status);
    }

    /**
     * Admin dapat menolak pengajuan pengembalian jika fisik buku belum diterima di meja sirkulasi.
     */
    public function test_admin_can_reject_return_request(): void
    {
        $loan = PeminjamanBuku::where('id_pengguna', $this->studentHasLoan->id_pengguna)
            ->whereNull('tanggal_kembali')
            ->firstOrFail();

        $loan->update([
            'status' => 'Menunggu Verifikasi',
            'tanggal_pengajuan_kembali' => now(),
        ]);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.peminjaman.tolak-pengembalian', $loan->id_peminjaman), [
                'alasan' => 'Fisik buku belum ada di meja sirkulasi.',
            ]);

        $response->assertRedirect(route('admin.peminjaman.index'));

        $freshLoan = $loan->fresh();
        $this->assertNotEquals('Menunggu Verifikasi', $freshLoan->status);
        $this->assertNull($freshLoan->tanggal_kembali);
        $this->assertStringContainsString('Fisik buku belum ada di meja sirkulasi', (string) $freshLoan->catatan_keterlambatan);
        $this->assertEquals('Dipinjam', $freshLoan->eksemplar->status);
    }

    /**
     * Flowchart Cabang 1:
     * Validasi pengembalian buku -> Tidak ada data peminjaman -> Pengembalian buku gagal.
     */
    public function test_flowchart_branch_1_return_fails_when_no_active_loan(): void
    {
        // studentNoLoan tidak memiliki peminjaman aktif
        $response = $this->actingAs($this->studentNoLoan)
            ->postJson(route('opac.kembalikan'), [
                'id_buku' => $this->targetBook->id_buku,
            ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'flowchart_step' => 'pengembalian_gagal',
        ]);
        $this->assertStringContainsString('Pengembalian buku gagal: Tidak ada data peminjaman aktif', $response->json('message'));
    }

    /**
     * Flowchart Cabang 2:
     * Validasi pengembalian buku -> Pernah meminjam -> Buku berhasil dikembalikan oleh user -> Status buku akan kembali bertambah (Tersedia).
     */
    public function test_flowchart_branch_2_successful_return_by_user_restores_book_status(): void
    {
        $activeLoan = PeminjamanBuku::where('id_pengguna', $this->studentHasLoan->id_pengguna)
            ->whereNull('tanggal_kembali')
            ->firstOrFail();

        $exemplar = $activeLoan->eksemplar;
        $this->assertEquals('Dipinjam', $exemplar->status);

        $initialAvailableCount = EksemplarBuku::where('id_buku', $activeLoan->id_buku)
            ->where('status', 'Tersedia')
            ->count();

        // User melakukan pengembalian langsung via endpoint opac.kembalikan
        $response = $this->actingAs($this->studentHasLoan)
            ->postJson(route('opac.kembalikan'), [
                'id_peminjaman' => $activeLoan->id_peminjaman,
                'catatan' => 'Buku dikembalikan mandiri oleh mahasiswa.',
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'flowchart_step' => 'kembali_bertambah',
        ]);
        $response->assertJsonPath('data.status_buku_eksemplar', 'Tersedia');

        // Verifikasi database: Status peminjaman menjadi Kembali dan eksemplar menjadi Tersedia
        $freshLoan = $activeLoan->fresh();
        $this->assertEquals('Kembali', $freshLoan->status);
        $this->assertNotNull($freshLoan->tanggal_kembali);
        $this->assertEquals('Tersedia', $exemplar->fresh()->status);

        // Verifikasi stok buku bertambah kembali
        $finalAvailableCount = EksemplarBuku::where('id_buku', $activeLoan->id_buku)
            ->where('status', 'Tersedia')
            ->count();
        $this->assertEquals($initialAvailableCount + 1, $finalAvailableCount);
    }

    /**
     * Flowchart Cabang 3:
     * Jika sudah lewat jadwal pengembalian -> Mengirim notifikasi peringatan ke user bahwa waktu telah lewat batas ->
     * User melakukan pengembalian buku: Ya -> Validasi pengembalian buku -> Buku berhasil dikembalikan oleh user -> Status buku kembali bertambah.
     */
    public function test_flowchart_branch_3_overdue_warning_and_successful_return_flow(): void
    {
        $overdueLoan = PeminjamanBuku::where('id_pengguna', $this->studentHasLoan->id_pengguna)
            ->whereNull('tanggal_kembali')
            ->firstOrFail();

        $overdueLoan->update([
            'tanggal_jatuh_tempo' => now()->subDays(3)->toDateString(),
            'status' => 'Terlambat',
            'hari_keterlambatan' => 3,
            'notifikasi_peringatan_dikirim' => false,
        ]);

        // 1. User membuka OPAC -> sistem mengirim notifikasi peringatan ke user bahwa waktu telah lewat batas
        $opacResponse = $this->actingAs($this->studentHasLoan)->get(route('opac'));
        $opacResponse->assertStatus(200);
        $opacResponse->assertSee('Notifikasi Peringatan: Batas Waktu Pengembalian Telah Lewat!');
        $opacResponse->assertSee($overdueLoan->buku->judul_buku);

        // Verifikasi flag notifikasi telah terkirim
        $this->assertTrue((bool) $overdueLoan->fresh()->notifikasi_peringatan_dikirim);

        // 2. User melakukan pengembalian buku: "Ya"
        $returnResponse = $this->actingAs($this->studentHasLoan)
            ->postJson(route('opac.kembalikan'), [
                'id_peminjaman' => $overdueLoan->id_peminjaman,
                'konfirmasi_lewat_jadwal' => true,
            ]);

        $returnResponse->assertStatus(200);
        $returnResponse->assertJson([
            'success' => true,
            'flowchart_step' => 'kembali_bertambah',
        ]);

        $freshLoan = $overdueLoan->fresh();
        $this->assertEquals('Kembali', $freshLoan->status);
        $this->assertEquals('Tersedia', $overdueLoan->eksemplar->fresh()->status);
    }

    /**
     * Flowchart Cabang 3 (Jalur jika belum dikembalikan):
     * Jika sudah lewat jadwal pengembalian dan user belum mengembalikan -> Admin input data keterlambatan.
     */
    public function test_flowchart_branch_3_admin_inputs_lateness_data_when_unreturned(): void
    {
        $overdueLoan = PeminjamanBuku::where('id_pengguna', $this->studentHasLoan->id_pengguna)
            ->whereNull('tanggal_kembali')
            ->firstOrFail();

        $overdueLoan->update([
            'tanggal_jatuh_tempo' => now()->subDays(4)->toDateString(),
            'status' => 'Terlambat',
        ]);

        // Admin mencatat data keterlambatan dan sanksi denda
        $adminResponse = $this->actingAs($this->admin)
            ->from(route('admin.peminjaman.index'))
            ->post(route('admin.peminjaman.input-keterlambatan', $overdueLoan->id_peminjaman), [
                'sanksi' => 'Peringatan tertulis ke mahasiswa dan penangguhan peminjaman 7 hari',
                'catatan_keterlambatan' => 'Terlambat 4 hari belum mengembalikan buku ke sirkulasi.',
            ]);

        $adminResponse->assertRedirect(route('admin.peminjaman.index'));
        $adminResponse->assertSessionHas('success');

        $freshLoan = $overdueLoan->fresh();
        $this->assertEquals('Peringatan tertulis ke mahasiswa dan penangguhan peminjaman 7 hari', $freshLoan->sanksi);
        $this->assertStringContainsString('Terlambat 4 hari', (string) $freshLoan->catatan_keterlambatan);
    }
}
