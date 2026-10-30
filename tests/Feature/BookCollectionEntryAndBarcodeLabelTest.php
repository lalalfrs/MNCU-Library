<?php

namespace Tests\Feature;

use App\Models\Buku;
use App\Models\EksemplarBuku;
use App\Models\Penerbit;
use App\Models\Pengguna;
use App\Services\BarcodeService;
use Database\Seeders\PerpustakaanSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BookCollectionEntryAndBarcodeLabelTest extends TestCase
{
    use RefreshDatabase;

    protected Pengguna $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PerpustakaanSeeder::class);
        $this->admin = Pengguna::where('email', 'arifin@mncu.ac.id')->firstOrFail();
    }

    /**
     * Halaman form tambah koleksi baru tidak menggunakan dropdown select
     * melainkan input manual teks keyboard dan memiliki field dimensi serta kode barcode.
     */
    public function test_create_book_page_uses_keyboard_inputs_without_dropdowns(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.books.create'));

        $response->assertStatus(200);

        // Memastikan tidak ada tag select untuk kategori, format, penerbit, atau pengarang
        $response->assertDontSee('<select name="id_kategori_buku"', false);
        $response->assertDontSee('<select name="id_jenis_buku"', false);
        $response->assertDontSee('<select name="id_penerbit"', false);
        $response->assertDontSee('<select name="id_penulis"', false);

        // Memastikan ada input teks keyboard dengan datalist
        $response->assertSee('name="nama_kategori"', false);
        $response->assertSee('name="nama_jenis_buku"', false);
        $response->assertSee('name="nama_penerbit"', false);
        $response->assertSee('name="nama_penulis"', false);

        // Memastikan field dimensi dan kode barcode untuk registrasi eksemplar tersedia
        $response->assertSee('name="dimensi"', false);
        $response->assertSee('name="kode_barcode"', false);
        $response->assertSee('Dimensi / Ukuran Fisik');
        $response->assertSee('Kode Barcode / Nomor Inventaris Fisik');
    }

    /**
     * Admin dapat menambahkan buku baru sepenuhnya dengan mengetik manual via keyboard
     * termasuk kategori baru, jenis baru, pengarang baru, dimensi fisik, dan kode barcode inventaris.
     */
    public function test_admin_can_store_book_using_manually_typed_keyboard_inputs(): void
    {
        $payload = [
            'judul_buku' => 'Kecerdasan Buatan dan Robotika Modern',
            'nama_kategori' => 'Robotika & AI Terapan',
            'nama_jenis_buku' => 'Buku Referensi Khusus',
            'nama_penerbit' => 'Penerbit MNCU Press',
            'nama_penulis' => 'Dr. Ir. Hendra Saputra, M.T.',
            'dimensi' => '16 x 24 cm; 480 hlm.',
            'isbn' => '978-623-999-01-2',
            'tahun_terbit' => 2026,
            'edisi' => 'Edisi Pertama',
            'jumlah_halaman' => 480,
            'deskripsi' => 'Buku teks komprehensif tentang algoritma otonom dan robotika cerdas.',
            'kata_kunci' => 'AI, Robotika, Python, Machine Learning',
            'stok_awal' => 1,
            'kode_barcode' => 'BARCODE-ROBOT-001',
            'nomor_panggil_dasar' => '006.3 HEN k',
            'lokasi_rak_dasar' => 'Lantai 3, Rak AI-01',
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.books.store'), $payload);

        $book = Buku::where('judul_buku', 'Kecerdasan Buatan dan Robotika Modern')->first();
        $this->assertNotNull($book);

        $response->assertRedirect(route('admin.books.index'));
        $response->assertSessionHas('success');
        $response->assertSessionHas('cetak_label_url');

        // Verifikasi relasi otomatis dibuat/disambungkan
        $this->assertEquals('16 x 24 cm; 480 hlm.', $book->dimensi);
        $this->assertEquals('Robotika & AI Terapan', $book->kategoriBuku?->nama_kategori);
        $this->assertEquals('Buku Referensi Khusus', $book->jenisBuku?->nama_jenis_buku);
        $this->assertEquals('Penerbit MNCU Press', $book->penerbit?->nama_penerbit);
        $this->assertEquals('Dr. Ir. Hendra Saputra, M.T.', $book->penulis->first()?->nama_penulis);

        // Verifikasi eksemplar fisik didaftarkan dengan kode barcode yang diketik manual
        $eksemplar = EksemplarBuku::where('id_buku', $book->id_buku)->first();
        $this->assertNotNull($eksemplar);
        $this->assertEquals('BARCODE-ROBOT-001', $eksemplar->kode_inventaris);
        $this->assertEquals('006.3 HEN k c.1', $eksemplar->nomor_panggil);
        $this->assertEquals('Lantai 3, Rak AI-01', $eksemplar->lokasi_rak);
    }

    /**
     * Halaman cetak label stiker menghasilkan tata letak 8cm x 5cm (80mm x 50mm)
     * dengan barcode SVG yang digenerate dan seluruh rincian buku.
     */
    public function test_print_label_sticker_view_renders_correctly(): void
    {
        $book = Buku::firstOrFail();
        $book->update([
            'dimensi' => '21 cm',
            'deskripsi' => 'Sinopsis lengkap buku pengujian cetak stiker perpustakaan.',
            'kata_kunci' => 'Pemrograman, Algoritma, Struktur Data',
        ]);
        $eksemplar = $book->eksemplar->first();

        $response = $this->actingAs($this->admin)->get(route('admin.books.cetak-label', [
            'buku' => $book->id_buku,
            'eksemplar' => $eksemplar?->id_eksemplar,
        ]));

        $response->assertStatus(200);

        // Memastikan ukuran 80mm x 50mm untuk cetak stiker termal / PDF
        $response->assertSee('80mm 50mm', false);
        $response->assertSee('PERPUSTAKAAN MNC UNIVERSITY');
        $response->assertSee($book->judul_buku);
        $response->assertSee($book->sampul_url, false);
        $response->assertSee('21 cm');
        $response->assertSee('Sinopsis lengkap buku pengujian cetak stiker perpustakaan.');
        $response->assertSee('Pemrograman, Algoritma, Struktur Data');

        // Memastikan barcode SVG dirender
        $response->assertSee('<svg', false);
        $response->assertSee('preserveAspectRatio="none"', false);

        if ($eksemplar) {
            $response->assertSee($eksemplar->kode_inventaris);
        }
    }

    /**
     * Layanan BarcodeService menghasilkan barcode Code 128 yang valid secara murni di PHP.
     */
    public function test_barcode_service_generates_valid_svg(): void
    {
        $service = app(BarcodeService::class);
        $svg = $service->generateSvg('INV-MNCU-12345', 40, true);

        $this->assertStringStartsWith('<svg xmlns="http://www.w3.org/2000/svg"', $svg);
        $this->assertStringContainsString('rect x=', $svg);
        $this->assertStringContainsString('INV-MNCU-12345', $svg);
        $this->assertStringEndsWith('</svg>', trim($svg));
    }

    /**
     * Admin dapat mengunggah file gambar sampul buku (JPG/PNG/WEBP) dengan batasan maksimal 200 KB.
     */
    public function test_admin_can_upload_book_cover_image_up_to_200kb(): void
    {
        Storage::fake('public');

        // File valid 150 KB (di bawah 200 KB)
        $validFile = UploadedFile::fake()->create('cover.webp', 150, 'image/webp');

        $response = $this->actingAs($this->admin)->post(route('admin.books.store'), [
            'judul_buku' => 'Buku Pemrograman Web Modern',
            'nama_kategori' => 'Teknologi Informasi',
            'nama_jenis_buku' => 'Buku Cetak',
            'sampul_buku_file' => $validFile,
        ]);

        $response->assertRedirect(route('admin.books.index'));

        $book = Buku::where('judul_buku', 'Buku Pemrograman Web Modern')->firstOrFail();
        $this->assertNotNull($book->sampul_buku);
        Storage::disk('public')->assertExists($book->sampul_buku);
    }

    /**
     * Unggahan file sampul buku yang melebihi 200 KB harus ditolak validasi.
     */
    public function test_uploading_book_cover_exceeding_200kb_fails_validation(): void
    {
        Storage::fake('public');

        // File tidak valid 250 KB (melebihi 200 KB)
        $oversizedFile = UploadedFile::fake()->create('heavy_cover.jpg', 250, 'image/jpeg');

        $response = $this->actingAs($this->admin)->post(route('admin.books.store'), [
            'judul_buku' => 'Buku Terlalu Berat',
            'nama_kategori' => 'Teknologi',
            'nama_jenis_buku' => 'Buku Cetak',
            'sampul_buku_file' => $oversizedFile,
        ]);

        $response->assertSessionHasErrors('sampul_buku_file');
    }

    /**
     * Unggahan file poster acara yang melebihi 200 KB harus ditolak validasi.
     */
    public function test_uploading_event_poster_exceeding_200kb_fails_validation(): void
    {
        Storage::fake('public');

        $oversizedFile = UploadedFile::fake()->create('heavy_event.png', 250, 'image/png');

        $response = $this->actingAs($this->admin)->post(route('admin.acara.store'), [
            'nama_acara' => 'Webinar Nasional AI 2026',
            'kategori_acara' => 'Seminar',
            'tanggal_mulai' => now()->addDays(5)->toDateTimeString(),
            'gambar_acara_file' => $oversizedFile,
        ]);

        $response->assertSessionHasErrors('gambar_acara_file');
    }

    /**
     * Unggahan file poster acara valid di bawah 200 KB berhasil disimpan.
     */
    public function test_uploading_event_poster_under_200kb_succeeds(): void
    {
        Storage::fake('public');

        $validFile = UploadedFile::fake()->create('event_poster.webp', 180, 'image/webp');

        $response = $this->actingAs($this->admin)->post(route('admin.acara.store'), [
            'nama_acara' => 'Webinar Nasional AI 2026',
            'kategori_acara' => 'Seminar',
            'tanggal_mulai' => now()->addDays(5)->toDateTimeString(),
            'gambar_acara_file' => $validFile,
        ]);

        $response->assertRedirect(route('admin.acara.index'));
        $this->assertDatabaseHas('acara_perpustakaan', ['nama_acara' => 'Webinar Nasional AI 2026']);
    }
}
