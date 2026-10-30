<?php

namespace Tests\Feature;

use App\Models\Pengguna;
use App\Models\VisitorLog;
use Database\Seeders\PerpustakaanSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LibraryPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_beranda_page_is_successful(): void
    {
        $response = $this->get(route('beranda'));
        $response->assertStatus(200);
        $response->assertSee('Perpustakaan MNC University');
    }

    public function test_beranda_page_renders_news_and_events_carousel(): void
    {
        $this->seed(PerpustakaanSeeder::class);

        $response = $this->get(route('beranda'));
        $response->assertStatus(200);
        $response->assertSee('carousel-berita');
        $response->assertSee('carousel-acara');
        $response->assertSee('btn-berita-prev');
        $response->assertSee('btn-berita-next');
        $response->assertSee('btn-acara-prev');
        $response->assertSee('btn-acara-next');
        $response->assertSee('dots-berita');
        $response->assertSee('dots-acara');
        $response->assertSee('carousel-item-3');
    }

    public function test_chat_page_is_successful(): void
    {
        $response = $this->get(route('chat'));
        $response->assertStatus(200);
        $response->assertSee('Chatbot & Bantuan');
    }

    public function test_chat_api_returns_json_reply(): void
    {
        $response = $this->postJson(route('chat'), ['message' => 'kapan jam operasional buka?']);
        $response->assertStatus(200);
        $response->assertJsonStructure(['reply', 'time']);
        $this->assertStringContainsString('Senin', $response->json('reply'));
    }

    public function test_chat_api_handles_perpanjangan_buku(): void
    {
        $response = $this->postJson(route('chat'), ['message' => 'Bagaimana cara perpanjang pinjaman buku?']);
        $response->assertStatus(200);
        $this->assertStringContainsString('Perpanjangan', $response->json('reply'));
        $this->assertStringContainsString('7 hari', $response->json('reply'));
    }

    public function test_chat_api_handles_turnitin(): void
    {
        $response = $this->postJson(route('chat'), ['message' => 'Berapa persen batas similarity Turnitin untuk skripsi?']);
        $response->assertStatus(200);
        $this->assertStringContainsString('Turnitin', $response->json('reply'));
        $this->assertStringContainsString('similarity', $response->json('reply'));
    }

    public function test_chat_api_handles_bebas_pustaka(): void
    {
        $response = $this->postJson(route('chat'), ['message' => 'Apa syarat bebas pustaka wisuda?']);
        $response->assertStatus(200);
        $this->assertStringContainsString('Bebas Pustaka', $response->json('reply'));
        $this->assertStringContainsString('Repository', $response->json('reply'));
    }

    public function test_chat_api_handles_denda(): void
    {
        $response = $this->postJson(route('chat'), ['message' => 'Berapa denda kalau telat kembalikan buku?']);
        $response->assertStatus(200);
        $this->assertStringContainsString('Rp1.000', $response->json('reply'));
    }

    public function test_chat_api_handles_buku_hilang(): void
    {
        $response = $this->postJson(route('chat'), ['message' => 'Bagaimana jika buku yang dipinjam hilang?']);
        $response->assertStatus(200);
        $this->assertStringContainsString('Hilang', $response->json('reply'));
        $this->assertStringContainsString('ISBN', $response->json('reply'));
    }

    public function test_chat_api_handles_ruang_diskusi(): void
    {
        $response = $this->postJson(route('chat'), ['message' => 'Apakah ada fasilitas ruang diskusi kelompok?']);
        $response->assertStatus(200);
        $this->assertStringContainsString('Ruang Diskusi', $response->json('reply'));
    }

    public function test_chat_api_handles_reservasi_buku(): void
    {
        $response = $this->postJson(route('chat'), ['message' => 'Bagaimana booking atau reservasi buku?']);
        $response->assertStatus(200);
        $this->assertStringContainsString('Reservasi', $response->json('reply'));
    }

    public function test_chat_api_handles_ejournal(): void
    {
        $response = $this->postJson(route('chat'), ['message' => 'Bagaimana cara akses remote jurnal luar kampus?']);
        $response->assertStatus(200);
        $this->assertStringContainsString('E-Journal', $response->json('reply'));
    }

    public function test_opac_page_is_successful(): void
    {
        $user = Pengguna::first();
        if (! $user) {
            $this->seed(PerpustakaanSeeder::class);
            $user = Pengguna::first();
        }

        $response = $this->actingAs($user)->get(route('opac'));
        $response->assertStatus(200);
        $response->assertSee('Katalog OPAC');
        $response->assertSee('Algoritma & Struktur Data');
    }

    public function test_repository_page_is_successful(): void
    {
        $response = $this->get(route('repository'));
        $response->assertStatus(200);
        $response->assertSee('Repository Karya Ilmiah');
    }

    public function test_ejournal_page_is_successful(): void
    {
        $response = $this->get(route('ejournal'));
        $response->assertStatus(200);
        $response->assertSee('Portal E-Journal');
        $response->assertSee('DOAJ');
    }

    public function test_ojs_page_is_successful(): void
    {
        $response = $this->get(route('ojs'));
        $response->assertStatus(200);
        $response->assertSee('Portal Jurnal OJS per Program Studi');
    }

    public function test_ebook_page_is_successful(): void
    {
        $response = $this->get(route('ebook'));
        $response->assertStatus(200);
        $response->assertSee('Koleksi E-Book');
    }

    public function test_tentang_kami_page_is_successful(): void
    {
        $response = $this->get(route('tentang-kami'));
        $response->assertStatus(200);
    }

    public function test_kontak_page_is_successful(): void
    {
        $response = $this->get(route('kontak'));
        $response->assertStatus(200);
    }

    public function test_pustakawan_page_is_successful(): void
    {
        $response = $this->get(route('pustakawan'));
        $response->assertStatus(200);
    }

    public function test_kehadiran_page_displays_and_increments_visitor_statistics(): void
    {
        $response = $this->get(route('kehadiran.index'));
        $response->assertStatus(200);
        $response->assertSee('Daftar Kehadiran Pengunjung Perpustakaan');

        $initialStats = VisitorLog::getStatistics();

        $postResponse = $this->post(route('kehadiran.store'), [
            'nama_pengunjung' => 'Budi Santoso',
            'kategori_pengunjung' => 'Umum',
            'identitas_pengunjung' => 'Jl. Kedoya Raya No. 12, Jakarta Barat',
            'keperluan' => 'Membaca referensi bisnis',
        ]);

        $postResponse->assertRedirect(route('kehadiran.index'));
        $this->assertDatabaseHas('log_kunjungan', [
            'nama_pengunjung' => 'Budi Santoso',
            'identitas_pengunjung' => 'Jl. Kedoya Raya No. 12, Jakarta Barat',
        ]);

        $updatedStats = VisitorLog::getStatistics();
        $this->assertSame($initialStats['today'] + 1, $updatedStats['today']);
        $this->assertSame($initialStats['month'] + 1, $updatedStats['month']);
        $this->assertSame($initialStats['total'] + 1, $updatedStats['total']);
    }
}
