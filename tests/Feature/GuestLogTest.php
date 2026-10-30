<?php

namespace Tests\Feature;

use App\Models\GuestLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuestLogTest extends TestCase
{
    use RefreshDatabase;

    public function test_beranda_page_displays_guest_log_form(): void
    {
        $response = $this->get(route('beranda'));

        $response->assertStatus(200);
        $response->assertSee('Absensi Pengunjung');
        $response->assertSee('Buku Tamu');
        $response->assertSee('guest-log-form');
    }

    public function test_guest_can_submit_attendance_as_mahasiswa(): void
    {
        $payload = [
            'nama' => 'Azka Pratama',
            'kategori' => 'Mahasiswa',
            'identitas' => '2023010045',
        ];

        $response = $this->post(route('guest-log.store'), $payload);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Selamat datang, Azka Pratama! Absensi berhasil dicatat.');

        $this->assertDatabaseHas('guest_logs', [
            'nama' => 'Azka Pratama',
            'kategori' => 'Mahasiswa',
            'identitas' => '2023010045',
        ]);

        $log = GuestLog::first();
        $this->assertNotNull($log->waktu_masuk);
    }

    public function test_guest_can_submit_attendance_as_dosen_staf(): void
    {
        $payload = [
            'nama' => 'Dr. Budi Santoso',
            'kategori' => 'Dosen / Staf',
            'identitas' => '198501012010121001',
        ];

        $response = $this->post(route('guest-log.store'), $payload);

        $response->assertRedirect();
        $this->assertDatabaseHas('guest_logs', [
            'nama' => 'Dr. Budi Santoso',
            'kategori' => 'Dosen / Staf',
            'identitas' => '198501012010121001',
        ]);
    }

    public function test_guest_can_submit_attendance_as_umum(): void
    {
        $payload = [
            'nama' => 'Siti Rahmawati',
            'kategori' => 'Umum',
            'identitas' => 'Jl. Kebon Sirih No. 12, Jakarta Pusat',
        ];

        $response = $this->post(route('guest-log.store'), $payload);

        $response->assertRedirect();
        $this->assertDatabaseHas('guest_logs', [
            'nama' => 'Siti Rahmawati',
            'kategori' => 'Umum',
            'identitas' => 'Jl. Kebon Sirih No. 12, Jakarta Pusat',
        ]);
    }

    public function test_guest_log_submission_validates_required_fields(): void
    {
        $response = $this->post(route('guest-log.store'), []);

        $response->assertSessionHasErrors(['nama', 'kategori', 'identitas']);
        $this->assertDatabaseCount('guest_logs', 0);
    }

    public function test_guest_log_validates_invalid_category(): void
    {
        $response = $this->post(route('guest-log.store'), [
            'nama' => 'Test User',
            'kategori' => 'Alien',
            'identitas' => '12345',
        ]);

        $response->assertSessionHasErrors(['kategori']);
        $this->assertDatabaseCount('guest_logs', 0);
    }

    public function test_guest_log_returns_json_on_ajax_request(): void
    {
        $payload = [
            'nama' => 'Indah Permata',
            'kategori' => 'Mahasiswa',
            'identitas' => '2023020088',
        ];

        $response = $this->postJson(route('guest-log.store'), $payload);

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
            'message' => 'Selamat datang, Indah Permata! Absensi berhasil dicatat.',
            'data' => [
                'nama' => 'Indah Permata',
                'kategori' => 'Mahasiswa',
                'identitas' => '2023020088',
            ],
        ]);

        $this->assertDatabaseHas('guest_logs', [
            'nama' => 'Indah Permata',
            'kategori' => 'Mahasiswa',
            'identitas' => '2023020088',
        ]);
    }
}
