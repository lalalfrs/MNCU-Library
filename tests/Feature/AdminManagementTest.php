<?php

namespace Tests\Feature;

use App\Models\Berita;
use App\Models\Buku;
use App\Models\JenisBuku;
use App\Models\KategoriBuku;
use App\Models\Penerbit;
use App\Models\Pengguna;
use App\Models\Peran;
use Database\Seeders\PerpustakaanSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PerpustakaanSeeder::class);
    }

    /**
     * Tamu yang belum login harus dialihkan ke halaman login saat membuka modul manajemen.
     */
    public function test_guests_are_redirected_to_login_when_accessing_admin(): void
    {
        $this->get('/admin/users')->assertRedirect(route('login'));
        $this->get('/admin/buku')->assertRedirect(route('login'));
        $this->get('/admin/acara')->assertRedirect(route('login'));
        $this->get('/admin/berita')->assertRedirect(route('login'));
    }

    /**
     * User yang sudah login dapat melihat tombol sidebar drawer dan tautan manajemen CRUD.
     */
    public function test_authenticated_user_sees_sidebar_and_management_options(): void
    {
        $admin = Pengguna::where('email', 'arifin@mncu.ac.id')->first();

        $response = $this->actingAs($admin)->get(route('beranda'));

        $response->assertStatus(200);
        $response->assertSee('Panel Kontrol & Manajemen Perpustakaan', false);
        $response->assertSee('Manajemen User');
        $response->assertSee('Manajemen Buku');
        $response->assertSee('Manajemen Acara');
        $response->assertSee('Manajemen Berita');
        $response->assertSee('Buka Menu Sidebar');
    }

    /**
     * User yang login dapat mengakses seluruh halaman index CRUD admin.
     */
    public function test_authenticated_user_can_view_all_admin_crud_index_pages(): void
    {
        $admin = Pengguna::where('email', 'arifin@mncu.ac.id')->first();

        $this->actingAs($admin)->get(route('admin.users.index'))->assertStatus(200)->assertSee('Manajemen Pengguna Perpustakaan');
        $this->actingAs($admin)->get(route('admin.books.index'))->assertStatus(200)->assertSee('Manajemen Katalog Koleksi Buku');
        $this->actingAs($admin)->get(route('admin.acara.index'))->assertStatus(200)->assertSee('Manajemen Acara');
        $this->actingAs($admin)->get(route('admin.berita.index'))->assertStatus(200)->assertSee('Manajemen Berita');
    }

    /**
     * Pengujian CRUD User: Tambah, edit, dan hapus user.
     */
    public function test_user_crud_flow(): void
    {
        $admin = Pengguna::where('email', 'arifin@mncu.ac.id')->first();
        $peran = Peran::first();

        // 1. Create
        $response = $this->actingAs($admin)->post(route('admin.users.store'), [
            'nama_lengkap' => 'Budi Santoso Testing',
            'email' => 'budi.test@mncu.ac.id',
            'nomor_identitas' => 'TEST-0099',
            'id_peran' => $peran->id_peran,
            'kata_sandi' => 'secret12345',
            'kata_sandi_confirmation' => 'secret12345',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('pengguna', ['email' => 'budi.test@mncu.ac.id']);

        $createdUser = Pengguna::where('email', 'budi.test@mncu.ac.id')->first();

        // 2. Edit
        $responseEdit = $this->actingAs($admin)->put(route('admin.users.update', $createdUser->id_pengguna), [
            'nama_lengkap' => 'Budi Santoso Diperbarui',
            'email' => 'budi.test@mncu.ac.id',
            'nomor_identitas' => 'TEST-0099',
            'id_peran' => $peran->id_peran,
        ]);

        $responseEdit->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('pengguna', ['nama_lengkap' => 'Budi Santoso Diperbarui']);

        // 3. Delete
        $responseDelete = $this->actingAs($admin)->delete(route('admin.users.destroy', $createdUser->id_pengguna));
        $responseDelete->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseMissing('pengguna', ['id_pengguna' => $createdUser->id_pengguna]);
    }

    /**
     * Pengujian CRUD Acara: Tambah dan ubah status.
     */
    public function test_acara_crud_flow(): void
    {
        $admin = Pengguna::where('email', 'arifin@mncu.ac.id')->first();

        $response = $this->actingAs($admin)->post(route('admin.acara.store'), [
            'nama_acara' => 'Workshop Literasi Digital 2026',
            'kategori_acara' => 'Workshop',
            'ringkasan_acara' => 'Pelatihan penelusuran referensi ilmiah bereputasi',
            'deskripsi_acara' => 'Acara diselenggarakan secara hybrid di perpustakaan MNC University.',
            'narasumber' => 'Dr. Arifin',
            'lokasi_acara' => 'Auditorium MNC University Lt. 5',
            'tanggal_mulai' => now()->addDays(5)->format('Y-m-d\TH:i'),
            'tanggal_selesai' => now()->addDays(5)->addHours(3)->format('Y-m-d\TH:i'),
            'kuota' => 75,
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.acara.index'));
        $this->assertDatabaseHas('acara_perpustakaan', ['nama_acara' => 'Workshop Literasi Digital 2026']);
    }

    /**
     * Pengujian CRUD Berita: Tambah berita baru.
     */
    public function test_berita_crud_flow(): void
    {
        $admin = Pengguna::where('email', 'arifin@mncu.ac.id')->first();

        $response = $this->actingAs($admin)->post(route('admin.berita.store'), [
            'judul_berita' => 'Peluncuran Fitur Baru Perpustakaan Digital 2026',
            'kategori_berita' => 'Pengumuman',
            'deskripsi_berita' => 'Ringkasan singkat rilis fitur manajemen katalog dan acara.',
            'isi_berita' => 'Berikut penjelasan lengkap sistem baru di Perpustakaan MNC University.',
            'tanggal_publikasi' => now()->format('Y-m-d'),
        ]);

        $response->assertRedirect(route('admin.berita.index'));
        $this->assertDatabaseHas('berita', ['judul_berita' => 'Peluncuran Fitur Baru Perpustakaan Digital 2026']);
    }

    /**
     * Pengujian CRUD Buku: Tambah buku baru.
     */
    public function test_book_crud_flow(): void
    {
        $admin = Pengguna::where('email', 'arifin@mncu.ac.id')->first();
        $jenis = JenisBuku::first();
        $kategori = KategoriBuku::first();
        $penerbit = Penerbit::first();

        $response = $this->actingAs($admin)->post(route('admin.books.store'), [
            'judul_buku' => 'Pengantar Pemrograman Modern dengan Laravel 12',
            'id_jenis_buku' => $jenis->id_jenis_buku,
            'id_kategori_buku' => $kategori->id_kategori_buku,
            'id_penerbit' => $penerbit->id_penerbit,
            'isbn' => '978-602-000-111-2',
            'tahun_terbit' => 2026,
            'jumlah_halaman' => 380,
            'deskripsi' => 'Buku pegangan lengkap teknik arsitektur web modern.',
        ]);

        $response->assertRedirect(route('admin.books.index'));
        $this->assertDatabaseHas('buku', ['judul_buku' => 'Pengantar Pemrograman Modern dengan Laravel 12']);
    }

    /**
     * Pengujian pembuatan buku dengan pengarang baru yang diketik manual via keyboard.
     */
    public function test_book_crud_supports_manual_author_creation_with_mncu_id(): void
    {
        $admin = Pengguna::where('email', 'arifin@mncu.ac.id')->first();
        $jenis = JenisBuku::first();
        $kategori = KategoriBuku::first();

        $response = $this->actingAs($admin)->post(route('admin.books.store'), [
            'judul_buku' => 'Machine Learning & Deep Neural Network 2026',
            'id_jenis_buku' => $jenis->id_jenis_buku,
            'id_kategori_buku' => $kategori->id_kategori_buku,
            'nama_penulis_manual' => 'Prof. Dr. Ir. Budi Rahardjo, M.Sc.',
            'sampul_buku' => 'https://images.unsplash.com/photo-1532012164546-f432f2e3edd4',
        ]);

        $response->assertRedirect(route('admin.books.index'));

        // Pastikan penulis baru tersimpan dengan ID MNCUXXXXX
        $this->assertDatabaseHas('penulis', [
            'nama_penulis' => 'Prof. Dr. Ir. Budi Rahardjo, M.Sc.',
        ]);

        $newBook = Buku::where('judul_buku', 'Machine Learning & Deep Neural Network 2026')->first();
        $this->assertNotNull($newBook);
        $this->assertMatchesRegularExpression('/^MNCU\d{5}$/', $newBook->id_buku);
        $this->assertCount(1, $newBook->penulis);
        $this->assertSame('Prof. Dr. Ir. Budi Rahardjo, M.Sc.', $newBook->penulis->first()->nama_penulis);
        $this->assertMatchesRegularExpression('/^MNCU\d{5}$/', $newBook->penulis->first()->id_penulis);
    }

    /**
     * Pengujian visualisasi katalog buku mendukung mode tampilan Grid dan Tabel (Row).
     */
    public function test_book_index_supports_grid_and_table_view_modes(): void
    {
        $admin = Pengguna::where('email', 'arifin@mncu.ac.id')->first();

        // 1. Tampilan default (table)
        $responseTable = $this->actingAs($admin)->get(route('admin.books.index'));
        $responseTable->assertStatus(200);
        $responseTable->assertSee('id="books-table-view"', false);
        $responseTable->assertSee('id="books-grid-view"', false);

        // 2. Tampilan eksplisit grid
        $responseGrid = $this->actingAs($admin)->get(route('admin.books.index', ['view' => 'grid']));
        $responseGrid->assertStatus(200);
        $responseGrid->assertSee('id="books-grid-view"', false);
    }
}
