<?php

namespace Tests\Feature;

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

    public function test_opac_page_is_successful(): void
    {
        $response = $this->get(route('opac'));
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
}
