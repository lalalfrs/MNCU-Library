<?php

namespace Tests\Feature;

use App\Models\Buku;
use App\Models\EksemplarBuku;
use App\Models\JenisBuku;
use App\Models\KategoriBuku;
use App\Models\Penerbit;
use App\Models\Pengguna;
use Database\Seeders\PerpustakaanSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
     * Pastikan halaman OPAC menampilkan metrik inventaris dan status ketersediaan stok buku.
     */
    public function test_opac_displays_real_time_stock_and_availability_badges(): void
    {
        $response = $this->get(route('opac'));

        $response->assertStatus(200);
        $response->assertSee('Katalog OPAC Perpustakaan');
        $response->assertSee('Total Judul');
        $response->assertSee('Total Fisik');
        $response->assertSee('Siap Pinjam');
        $response->assertSee('Algoritma & Struktur Data');
        $response->assertSee('Tersedia:');
        $response->assertSee('Rincian &amp; Stok Eksemplar', false);
        $response->assertSee('Tanya Pustakawan');
    }

    /**
     * Pastikan filter ketersediaan stok di OPAC berfungsi menyaring data.
     */
    public function test_opac_supports_stock_status_filter(): void
    {
        $response = $this->get(route('opac', ['status' => 'Hanya Tersedia']));

        $response->assertStatus(200);
        $response->assertSee('Algoritma & Struktur Data');
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

        // 2. Perbarui Status Eksemplar (misal dipinjam)
        $responseUpdate = $this->actingAs($admin)->put(route('admin.books.eksemplar.update', [$book->id_buku, $eksemplar->id_eksemplar]), [
            'kode_inventaris' => 'INV-TEST-0099',
            'nomor_panggil' => '005.1 TEST c.99',
            'lokasi_rak' => 'Lantai 2, Rak Khusus 01',
            'status' => 'Dipinjam',
        ]);

        $responseUpdate->assertRedirect(route('admin.books.show', $book->id_buku));
        $this->assertDatabaseHas('eksemplar_buku', [
            'id_eksemplar' => $eksemplar->id_eksemplar,
            'status' => 'Dipinjam',
        ]);

        // 3. Hapus Eksemplar
        $responseDelete = $this->actingAs($admin)->delete(route('admin.books.eksemplar.destroy', [$book->id_buku, $eksemplar->id_eksemplar]));
        $responseDelete->assertRedirect(route('admin.books.show', $book->id_buku));
        $this->assertDatabaseMissing('eksemplar_buku', [
            'id_eksemplar' => $eksemplar->id_eksemplar,
        ]);
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
}
