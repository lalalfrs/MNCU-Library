<?php

namespace Tests\Feature;

use App\Models\Buku;
use App\Models\EksemplarBuku;
use App\Models\JenisBuku;
use App\Models\KategoriBuku;
use App\Models\PeminjamanBuku;
use App\Models\Penerbit;
use App\Models\Pengguna;
use Database\Seeders\PerpustakaanSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class BookStockAndOpacTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PerpustakaanSeeder::class);
    }

    /**
     * Pastikan tamu yang belum login diarahkan ke halaman login saat membuka OPAC.
     */
    public function test_opac_redirects_unauthenticated_guest_to_login(): void
    {
        $response = $this->get(route('opac'));

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('info');
    }

    /**
     * Pastikan halaman OPAC menampilkan metrik inventaris dan status ketersediaan stok buku saat user telah login.
     */
    public function test_opac_displays_real_time_stock_and_availability_badges_for_authenticated_user(): void
    {
        $user = Pengguna::first();

        $response = $this->actingAs($user)->get(route('opac'));

        $response->assertStatus(200);
        $response->assertSee('Katalog OPAC Perpustakaan');
        $response->assertSee('Algoritma & Struktur Data');
        $response->assertSee('Filter Pencarian');
        $response->assertSee('Detail Informasi Buku');
    }

    /**
     * Pastikan fitur flowchart (Kategori Subjek, Populer, Baru) dan filter box bekerja dengan baik.
     */
    public function test_opac_supports_flowchart_features_and_filters(): void
    {
        $user = Pengguna::first();

        // 1. Fitur Kategori Subjek
        $responseSubjek = $this->actingAs($user)->get(route('opac', ['fitur' => 'subjek']));
        $responseSubjek->assertStatus(200);
        $responseSubjek->assertSee('Pilih Kategori Subjek Koleksi');

        // 2. Fitur Koleksi Populer
        $responsePopuler = $this->actingAs($user)->get(route('opac', ['fitur' => 'populer', 'sort' => 'populer']));
        $responsePopuler->assertStatus(200);
        $responsePopuler->assertSee('Koleksi Terpopuler Perpustakaan');

        // 3. Fitur Koleksi Baru
        $responseBaru = $this->actingAs($user)->get(route('opac', ['fitur' => 'terbaru', 'sort' => 'terbaru']));
        $responseBaru->assertStatus(200);
        $responseBaru->assertSee('Koleksi Baru dan Diperbarui');

        // 4. Pencarian berdasarkan Kriteria Tertentu
        $responseSearch = $this->actingAs($user)->get(route('opac', ['q' => 'Python', 'kriteria' => 'judul']));
        $responseSearch->assertStatus(200);
        $responseSearch->assertSee('Algoritma & Struktur Data');

        // 5. Box Filter Lanjutan (Pengarang, Tahun, DDC)
        $responseBox = $this->actingAs($user)->get(route('opac', ['filter_pengarang' => 'Rian', 'filter_tahun' => '2024']));
        $responseBox->assertStatus(200);
        $responseBox->assertSee('Algoritma & Struktur Data');
    }

    /**
     * Pastikan pencatatan kunjungan penelusuran buku via endpoint recordKunjungan bekerja.
     */
    public function test_opac_record_kunjungan_increments_views(): void
    {
        $user = Pengguna::first();
        $book = Buku::first();

        $response = $this->actingAs($user)->postJson(route('opac.kunjungan', $book->id_buku));

        $response->assertStatus(200);
        $response->assertJson(['success' => true, 'id_buku' => $book->id_buku]);
        $this->assertGreaterThanOrEqual(1, $response->json('jumlah_kunjungan'));
    }

    /**
     * Pastikan pencarian OPAC berdasarkan kriteria koleksi (judul, pengarang, nomor panggil) berfungsi sesuai flowchart.
     */
    public function test_opac_supports_search_by_criteria(): void
    {
        $user = Pengguna::first();

        // 1. Pencarian kriteria judul
        $responseJudul = $this->actingAs($user)->get(route('opac', ['q' => 'Algoritma', 'kriteria' => 'judul']));
        $responseJudul->assertStatus(200);
        $responseJudul->assertSee('Algoritma & Struktur Data');

        // 2. Pencarian kriteria pengarang
        $responseAuthor = $this->actingAs($user)->get(route('opac', ['q' => 'Rian Hidayat', 'kriteria' => 'pengarang']));
        $responseAuthor->assertStatus(200);
        $responseAuthor->assertSee('Algoritma & Struktur Data');

        // 3. Pencarian kriteria kustom yang tidak cocok
        $responseMismatch = $this->actingAs($user)->get(route('opac', ['q' => 'Python', 'kriteria' => 'pengarang']));
        $responseMismatch->assertStatus(200);
        $responseMismatch->assertSee('Koleksi Tidak Ditemukan');
    }

    /**
     * Pastikan saat koleksi tidak ditemukan, pesan tidak ditemukan dan tombol coba kata kunci lain ditampilkan (loop F -> B).
     */
    public function test_opac_displays_not_found_message_with_loop_back_action_when_no_match(): void
    {
        $user = Pengguna::first();

        $response = $this->actingAs($user)->get(route('opac', ['q' => 'KataKunciYangPastiTidakAda12345']));

        $response->assertStatus(200);
        $response->assertSee('Koleksi Tidak Ditemukan');
        $response->assertSee('Coba Kata Kunci Lain');
        $response->assertSee('Lihat Semua Koleksi');
    }

    /**
     * Pastikan halaman manajemen buku admin menampilkan metrik global dan kolom stok eksemplar.
     */
    public function test_admin_book_index_displays_inventory_stock_metrics_and_column(): void
    {
        $admin = Pengguna::where('email', 'arifin@mncu.ac.id')->first();

        $response = $this->actingAs($admin)->get(route('admin.books.index'));

        $response->assertStatus(200);
        $response->assertSee('Total Judul');
        $response->assertSee('Total Eksemplar');
        $response->assertSee('Stok Siap Pinjam');
        $response->assertSee('Sedang Dipinjam');
        $response->assertSee('Ketersediaan Stok');
        $response->assertSee('Tersedia');
    }

    /**
     * Pastikan admin dapat membuka halaman detail buku dan melihat seluruh daftar eksemplar fisik.
     */
    public function test_admin_can_view_book_detail_and_exemplar_inventory(): void
    {
        $admin = Pengguna::where('email', 'arifin@mncu.ac.id')->first();
        $book = Buku::first();

        $response = $this->actingAs($admin)->get(route('admin.books.show', $book->id_buku));

        $response->assertStatus(200);
        $response->assertSee($book->judul_buku);
        $response->assertSee('Detail &amp; Stok Eksemplar', false);
        $response->assertSee('Registrasi Eksemplar Fisik Baru');
        $response->assertSee('Daftar Eksemplar Fisik');
        $response->assertSee('Kode Inventaris');
        $response->assertSee('Nomor Panggil');
    }

    /**
     * Pastikan admin dapat menambah, memperbarui status, dan menghapus eksemplar fisik buku.
     */
    public function test_admin_can_add_update_and_delete_physical_exemplar(): void
    {
        $admin = Pengguna::where('email', 'arifin@mncu.ac.id')->first();
        $book = Buku::first();

        // 1. Tambah Eksemplar Baru
        $responseAdd = $this->actingAs($admin)->post(route('admin.books.eksemplar.store', $book->id_buku), [
            'kode_inventaris' => 'INV-TEST-0099',
            'nomor_panggil' => '005.1 TEST c.99',
            'lokasi_rak' => 'Lantai 2, Rak Khusus 01',
            'status' => 'Tersedia',
        ]);

        $responseAdd->assertRedirect(route('admin.books.show', $book->id_buku));
        $this->assertDatabaseHas('eksemplar_buku', [
            'id_buku' => $book->id_buku,
            'kode_inventaris' => 'INV-TEST-0099',
            'status' => 'Tersedia',
        ]);

        $eksemplar = EksemplarBuku::where('kode_inventaris', 'INV-TEST-0099')->first();

        // 2. Perbarui Status Eksemplar (mengubah ke Dipinjam harus menyertakan peminjam dan jatuh tempo)
        $student = Pengguna::where('id_pengguna', 'MNCU00006')->firstOrFail();
        $responseUpdate = $this->actingAs($admin)->put(route('admin.books.eksemplar.update', [$book->id_buku, $eksemplar->id_eksemplar]), [
            'kode_inventaris' => 'INV-TEST-0099',
            'nomor_panggil' => '005.1 TEST c.99',
            'lokasi_rak' => 'Lantai 2, Rak Khusus 01',
            'status' => 'Dipinjam',
            'id_pengguna' => $student->id_pengguna,
            'tanggal_jatuh_tempo' => now()->addDays(14)->toDateString(),
        ]);

        $responseUpdate->assertRedirect(route('admin.books.show', $book->id_buku));
        $this->assertDatabaseHas('eksemplar_buku', [
            'id_eksemplar' => $eksemplar->id_eksemplar,
            'status' => 'Dipinjam',
        ]);
        $this->assertDatabaseHas('peminjaman_buku', [
            'id_eksemplar' => $eksemplar->id_eksemplar,
            'id_pengguna' => $student->id_pengguna,
            'status' => 'Dipinjam',
        ]);

        // 3. Kembalikan status ke Tersedia sebelum dihapus
        $this->actingAs($admin)->put(route('admin.books.eksemplar.update', [$book->id_buku, $eksemplar->id_eksemplar]), [
            'kode_inventaris' => 'INV-TEST-0099',
            'status' => 'Tersedia',
        ]);

        // 4. Hapus Eksemplar
        $responseDelete = $this->actingAs($admin)->delete(route('admin.books.eksemplar.destroy', [$book->id_buku, $eksemplar->id_eksemplar]));
        $responseDelete->assertRedirect(route('admin.books.show', $book->id_buku));
        $this->assertDatabaseMissing('eksemplar_buku', [
            'id_eksemplar' => $eksemplar->id_eksemplar,
        ]);
    }

    /**
     * Mengubah status eksemplar menjadi Dipinjam tanpa data peminjam wajib ditolak sistem.
     */
    public function test_admin_cannot_change_exemplar_to_borrowed_without_borrower_data(): void
    {
        $admin = Pengguna::where('email', 'arifin@mncu.ac.id')->firstOrFail();
        $book = Buku::firstOrFail();
        $eksemplar = EksemplarBuku::where('id_buku', $book->id_buku)->where('status', 'Tersedia')->firstOrFail();

        $response = $this->actingAs($admin)->put(route('admin.books.eksemplar.update', [$book->id_buku, $eksemplar->id_eksemplar]), [
            'kode_inventaris' => $eksemplar->kode_inventaris,
            'status' => 'Dipinjam',
        ]);

        $response->assertSessionHasErrors(['id_pengguna', 'tanggal_jatuh_tempo']);
        $this->assertEquals('Tersedia', $eksemplar->fresh()->status);
    }

    /**
     * Mengubah status eksemplar menjadi Dipinjam untuk mahasiswa yang memiliki tanggungan ditolak sesuai flowchart.
     */
    public function test_admin_cannot_change_exemplar_to_borrowed_for_ineligible_student(): void
    {
        $admin = Pengguna::where('email', 'arifin@mncu.ac.id')->firstOrFail();
        $studentBlocked = Pengguna::where('id_pengguna', 'MNCU00004')->firstOrFail(); // Fauzi has active loan
        $book = Buku::firstOrFail();
        $eksemplar = EksemplarBuku::where('id_buku', $book->id_buku)->where('status', 'Tersedia')->firstOrFail();

        $response = $this->actingAs($admin)->put(route('admin.books.eksemplar.update', [$book->id_buku, $eksemplar->id_eksemplar]), [
            'kode_inventaris' => $eksemplar->kode_inventaris,
            'status' => 'Dipinjam',
            'id_pengguna' => $studentBlocked->id_pengguna,
            'tanggal_jatuh_tempo' => now()->addDays(7)->toDateString(),
        ]);

        $response->assertSessionHas('error');
        $this->assertEquals('Tersedia', $eksemplar->fresh()->status);
    }

    /**
     * Mengubah status eksemplar dari Dipinjam kembali ke Tersedia otomatis menyelesaikan peminjaman (pengembalian buku).
     */
    public function test_admin_changing_exemplar_from_borrowed_to_available_completes_the_loan(): void
    {
        $admin = Pengguna::where('email', 'arifin@mncu.ac.id')->firstOrFail();
        $student = Pengguna::where('id_pengguna', 'MNCU00006')->firstOrFail();
        $book = Buku::firstOrFail();
        $eksemplar = EksemplarBuku::where('id_buku', $book->id_buku)->where('status', 'Tersedia')->firstOrFail();

        // 1. Ubah ke Dipinjam
        $this->actingAs($admin)->put(route('admin.books.eksemplar.update', [$book->id_buku, $eksemplar->id_eksemplar]), [
            'kode_inventaris' => $eksemplar->kode_inventaris,
            'status' => 'Dipinjam',
            'id_pengguna' => $student->id_pengguna,
            'tanggal_jatuh_tempo' => now()->addDays(14)->toDateString(),
        ]);

        $this->assertEquals('Dipinjam', $eksemplar->fresh()->status);

        // 2. Ubah kembali ke Tersedia (Pengembalian Buku)
        $responseReturn = $this->actingAs($admin)->put(route('admin.books.eksemplar.update', [$book->id_buku, $eksemplar->id_eksemplar]), [
            'kode_inventaris' => $eksemplar->kode_inventaris,
            'status' => 'Tersedia',
        ]);

        $responseReturn->assertRedirect(route('admin.books.show', $book->id_buku));
        $this->assertEquals('Tersedia', $eksemplar->fresh()->status);

        // Verifikasi transaksi peminjaman selesai (status Kembali dan tanggal_kembali terisi)
        $loan = PeminjamanBuku::where('id_eksemplar', $eksemplar->id_eksemplar)
            ->latest('id_peminjaman')
            ->firstOrFail();

        $this->assertEquals('Kembali', $loan->status);
        $this->assertNotNull($loan->tanggal_kembali);
    }

    /**
     * Pastikan penambahan buku baru dengan input stok awal otomatis menghasilkan eksemplar di database.
     */
    public function test_admin_book_create_with_initial_stock_generates_exemplars(): void
    {
        $admin = Pengguna::where('email', 'arifin@mncu.ac.id')->first();
        $jenis = JenisBuku::first();
        $kategori = KategoriBuku::first();
        $penerbit = Penerbit::first();

        $response = $this->actingAs($admin)->post(route('admin.books.store'), [
            'judul_buku' => 'Buku Uji Coba Stok Otomatis',
            'id_jenis_buku' => $jenis->id_jenis_buku,
            'id_kategori_buku' => $kategori->id_kategori_buku,
            'id_penerbit' => $penerbit->id_penerbit,
            'isbn' => '978-602-999-001-1',
            'tahun_terbit' => 2026,
            'stok_awal' => 3,
            'nomor_panggil_dasar' => '005.1 UJI a',
            'lokasi_rak_dasar' => 'Lantai 2, Rak Uji',
        ]);

        $response->assertRedirect(route('admin.books.index'));

        $newBook = Buku::where('judul_buku', 'Buku Uji Coba Stok Otomatis')->first();
        $this->assertNotNull($newBook);
        $this->assertCount(3, $newBook->eksemplar);
        $this->assertSame(3, $newBook->eksemplar()->where('status', 'Tersedia')->count());
        $this->assertDatabaseHas('eksemplar_buku', [
            'id_buku' => $newBook->id_buku,
            'nomor_panggil' => '005.1 UJI a c.1',
            'lokasi_rak' => 'Lantai 2, Rak Uji',
            'status' => 'Tersedia',
        ]);
    }

    /**
     * OPAC dapat mencari buku yang sedang dipinjam berdasarkan kriteria peminjam atau status dipinjam.
     */
    public function test_opac_can_search_and_filter_by_borrower_name(): void
    {
        $user = Pengguna::first();

        // Cari dengan kriteria peminjam (Fauzi meminjam di seeder)
        $responseBorrower = $this->actingAs($user)->get(route('opac', [
            'q' => 'Fauzi',
            'kriteria' => 'peminjam',
        ]));

        $responseBorrower->assertStatus(200);
        $responseBorrower->assertSee('Sedang Dipinjam');
        $responseBorrower->assertSee('Fauzi');

        // Filter status Dipinjam
        $responseStatus = $this->actingAs($user)->get(route('opac', [
            'status_filter' => 'Dipinjam',
        ]));

        $responseStatus->assertStatus(200);
        $responseStatus->assertSee('Dipinjam');
    }

    /**
     * Admin dapat menambahkan kata kunci saat membuat buku dan memperbarui kata kunci langsung dari detail buku.
     */
    public function test_admin_can_add_book_with_keywords_and_update_from_detail_page(): void
    {
        $admin = Pengguna::where('email', 'arifin@mncu.ac.id')->firstOrFail();
        $jenis = JenisBuku::firstOrFail();
        $kategori = KategoriBuku::firstOrFail();
        $penerbit = Penerbit::firstOrFail();

        // 1. Admin menambahkan buku baru lengkap dengan kata kunci
        $responseCreate = $this->actingAs($admin)->post(route('admin.books.store'), [
            'judul_buku' => 'Dasar Pemrograman Web Modern',
            'id_jenis_buku' => $jenis->id_jenis_buku,
            'id_kategori_buku' => $kategori->id_kategori_buku,
            'id_penerbit' => $penerbit->id_penerbit,
            'isbn' => '978-602-123-456-7',
            'tahun_terbit' => 2025,
            'kata_kunci' => 'Coding, Pembelajaran, Frontend, Javascript',
        ]);

        $responseCreate->assertRedirect(route('admin.books.index'));

        $book = Buku::where('judul_buku', 'Dasar Pemrograman Web Modern')->firstOrFail();
        $this->assertEquals('Coding, Pembelajaran, Frontend, Javascript', $book->kata_kunci);
        $this->assertEquals(['Coding', 'Pembelajaran', 'Frontend', 'Javascript'], $book->kata_kunci_list);

        // 2. Admin membuka detail buku dan melihat kata kunci
        $responseShow = $this->actingAs($admin)->get(route('admin.books.show', $book->id_buku));
        $responseShow->assertStatus(200);
        $responseShow->assertSee('Kata Kunci Pencarian OPAC');
        $responseShow->assertSee('Coding');
        $responseShow->assertSee('Frontend');

        // 3. Admin memperbarui kata kunci langsung dari halaman detail buku
        $responseUpdateKw = $this->actingAs($admin)->post(route('admin.books.kata-kunci.update', $book->id_buku), [
            'kata_kunci' => 'Coding, WebDev, Fullstack, Vue',
        ]);

        $responseUpdateKw->assertRedirect(route('admin.books.show', $book->id_buku));
        $this->assertEquals('Coding, WebDev, Fullstack, Vue', $book->fresh()->kata_kunci);
        $this->assertContains('Fullstack', $book->fresh()->kata_kunci_list);
    }

    /**
     * User dapat mencari koleksi di katalog OPAC berdasarkan kata kunci baik melalui pencarian umum maupun kriteria kata kunci.
     */
    public function test_opac_search_by_keywords_via_general_and_specific_criteria(): void
    {
        $user = Pengguna::firstOrFail();

        // 1. Pencarian umum (semua kriteria) dengan query 'Coding'
        $resGeneral = $this->actingAs($user)->get(route('opac', [
            'q' => 'Coding',
            'kriteria' => 'semua',
        ]));
        $resGeneral->assertStatus(200);
        $resGeneral->assertSee('Algoritma &amp; Struktur Data dengan Python', false);
        $resGeneral->assertSee('Coding');

        // 2. Pencarian spesifik kriteria 'kata_kunci' dengan query 'hiburan' (buku fiksi Bumi Manusia)
        $resFiction = $this->actingAs($user)->get(route('opac', [
            'q' => 'hiburan',
            'kriteria' => 'kata_kunci',
        ]));
        $resFiction->assertStatus(200);
        $resFiction->assertSee('Bumi Manusia');
        $resFiction->assertSee('Hiburan');

        // 3. Pencarian spesifik kriteria 'kata_kunci' dengan query 'Pembelajaran'
        $resLearning = $this->actingAs($user)->get(route('opac', [
            'q' => 'Pembelajaran',
            'kriteria' => 'kata_kunci',
        ]));
        $resLearning->assertStatus(200);
        $resLearning->assertSee('Algoritma &amp; Struktur Data dengan Python', false);
    }

    /**
     * Admin dapat menghapus buku beserta seluruh eksemplar dan riwayat peminjamannya secara tuntas tanpa error foreign key constraint.
     */
    public function test_admin_can_delete_book_and_clean_up_all_copies_and_loans(): void
    {
        $admin = Pengguna::where('email', 'arifin@mncu.ac.id')->firstOrFail();
        $student = Pengguna::where('id_pengguna', 'MNCU00004')->firstOrFail();

        // Buat buku sementara beserta eksemplar dan simulasi data pinjaman
        $book = Buku::create([
            'id_buku' => 'MNCU99998',
            'uuid' => (string) Str::uuid(),
            'id_jenis_buku' => 'MNCU00001',
            'id_kategori_buku' => 'MNCU00001',
            'id_penerbit' => 'MNCU00001',
            'judul_buku' => 'Buku Uji Hapus Cascade',
            'isbn' => '978-000-0000-98-8',
            'tahun_terbit' => 2024,
            'deskripsi' => 'Deskripsi buku uji coba',
        ]);

        $eksemplar = EksemplarBuku::create([
            'id_buku' => $book->id_buku,
            'kode_inventaris' => 'INV-TEST-DEL-98',
            'status' => 'Dipinjam',
            'lokasi_rak' => 'Lantai 2',
        ]);

        $peminjaman = PeminjamanBuku::create([
            'id_peminjaman' => 'PJ99998',
            'uuid' => (string) Str::uuid(),
            'id_pengguna' => $student->id_pengguna,
            'id_buku' => $book->id_buku,
            'id_eksemplar' => $eksemplar->id_eksemplar,
            'tanggal_pinjam' => now()->toDateString(),
            'tanggal_jatuh_tempo' => now()->addDays(14)->toDateString(),
            'status' => 'Dipinjam',
        ]);

        $this->assertDatabaseHas('buku', ['id_buku' => 'MNCU99998']);
        $this->assertDatabaseHas('eksemplar_buku', ['id_eksemplar' => $eksemplar->id_eksemplar]);
        $this->assertDatabaseHas('peminjaman_buku', ['id_peminjaman' => 'PJ99998']);

        $response = $this->actingAs($admin)->delete(route('admin.books.destroy', $book->id_buku));

        $response->assertRedirect(route('admin.books.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('buku', ['id_buku' => 'MNCU99998']);
        $this->assertDatabaseMissing('eksemplar_buku', ['id_eksemplar' => $eksemplar->id_eksemplar]);
        $this->assertDatabaseMissing('peminjaman_buku', ['id_peminjaman' => 'PJ99998']);
    }

    /**
     * Admin dapat menghapus satu eksemplar fisik buku beserta data peminjaman terkait tanpa kendala integrity violation.
     */
    public function test_admin_can_delete_exemplar_safely(): void
    {
        $admin = Pengguna::where('email', 'arifin@mncu.ac.id')->firstOrFail();
        $book = Buku::firstOrFail();

        $eksemplar = EksemplarBuku::create([
            'id_buku' => $book->id_buku,
            'kode_inventaris' => 'INV-TEST-DEL-EX01',
            'status' => 'Tersedia',
            'lokasi_rak' => 'Lantai 2, Rak Uji',
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.books.eksemplar.destroy', [$book->id_buku, $eksemplar->id_eksemplar]));

        $response->assertRedirect(route('admin.books.show', $book->id_buku));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('eksemplar_buku', ['id_eksemplar' => $eksemplar->id_eksemplar]);
    }
}
