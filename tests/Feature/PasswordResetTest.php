<?php

namespace Tests\Feature;

use App\Models\Pengguna;
use Database\Seeders\PerpustakaanSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PerpustakaanSeeder::class);
    }

    /**
     * Skenario 1: Tamu (guest) yang belum login diarahkan ke login saat mengakses halaman ubah password.
     */
    public function test_guest_is_redirected_to_login_when_accessing_change_password(): void
    {
        $response = $this->get(route('password.edit'));

        $response->assertRedirect(route('login'));
    }

    /**
     * Skenario 1: Pengguna yang sudah login (Mahasiswa/Dosen) dapat membuka formulir ubah kata sandi.
     */
    public function test_authenticated_user_can_view_change_password_form(): void
    {
        $user = Pengguna::where('email', 'jovian@mncu.ac.id')->first()
            ?? Pengguna::whereHas('peran', fn ($q) => $q->where('nama_peran', 'Mahasiswa'))->first();

        $this->assertNotNull($user);

        $response = $this->actingAs($user)->get(route('password.edit'));

        $response->assertStatus(200);
        $response->assertSee('Ubah Kata Sandi');
        $response->assertSee('Kata Sandi Saat Ini');
        $response->assertSee('Kata Sandi Baru');
        $response->assertSee('Konfirmasi Kata Sandi Baru');
        $response->assertSee($user->nama_lengkap);
    }

    /**
     * Skenario 1: Gagal mengubah password jika kata sandi lama yang dimasukkan salah.
     */
    public function test_user_cannot_change_password_with_incorrect_old_password(): void
    {
        $user = Pengguna::where('email', 'jovian@mncu.ac.id')->first()
            ?? Pengguna::whereHas('peran', fn ($q) => $q->where('nama_peran', 'Mahasiswa'))->first();

        $response = $this->actingAs($user)->put(route('password.update'), [
            'password_lama' => 'PasswordSalah123!',
            'password_baru' => 'PasswordBaru2026!',
            'password_baru_confirmation' => 'PasswordBaru2026!',
        ]);

        $response->assertSessionHasErrors(['password_lama']);
        $this->assertTrue(Hash::check('password123', $user->fresh()->kata_sandi));
    }

    /**
     * Skenario 1: Gagal mengubah password jika konfirmasi kata sandi baru tidak cocok.
     */
    public function test_user_cannot_change_password_with_mismatched_confirmation(): void
    {
        $user = Pengguna::where('email', 'jovian@mncu.ac.id')->first()
            ?? Pengguna::whereHas('peran', fn ($q) => $q->where('nama_peran', 'Mahasiswa'))->first();

        $response = $this->actingAs($user)->put(route('password.update'), [
            'password_lama' => 'password123',
            'password_baru' => 'PasswordBaru2026!',
            'password_baru_confirmation' => 'PasswordBeda999!',
        ]);

        $response->assertSessionHasErrors(['password_baru']);
    }

    /**
     * Skenario 1: Gagal mengubah password jika kata sandi baru sama dengan kata sandi lama.
     */
    public function test_user_cannot_reuse_identical_current_password(): void
    {
        $user = Pengguna::where('email', 'jovian@mncu.ac.id')->first()
            ?? Pengguna::whereHas('peran', fn ($q) => $q->where('nama_peran', 'Mahasiswa'))->first();

        $response = $this->actingAs($user)->put(route('password.update'), [
            'password_lama' => 'password123',
            'password_baru' => 'password123',
            'password_baru_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['password_baru']);
    }

    /**
     * Skenario 1: Sukses mengubah password saat kata sandi lama cocok dan konfirmasi valid.
     */
    public function test_user_can_successfully_update_password_and_login_with_new_password(): void
    {
        $user = Pengguna::where('email', 'jovian@mncu.ac.id')->first()
            ?? Pengguna::whereHas('peran', fn ($q) => $q->where('nama_peran', 'Mahasiswa'))->first();

        $response = $this->actingAs($user)->put(route('password.update'), [
            'password_lama' => 'password123',
            'password_baru' => 'KatalaluanBaru2026!',
            'password_baru_confirmation' => 'KatalaluanBaru2026!',
        ]);

        $response->assertRedirect(route('password.edit'));
        $response->assertSessionHas('success');

        // Verifikasi hash di database telah berubah
        $this->assertTrue(Hash::check('KatalaluanBaru2026!', $user->fresh()->kata_sandi));

        // Verifikasi user dapat melakukan login dengan password baru
        auth()->logout();

        $loginResponse = $this->post('/login', [
            'email' => $user->email,
            'kata_sandi' => 'KatalaluanBaru2026!',
        ]);

        $loginResponse->assertRedirect(route('beranda'));
        $this->assertAuthenticatedAs($user);
    }

    /**
     * Skenario 2: User biasa (Mahasiswa/Dosen) dilarang mengakses endpoint reset password milik admin (403).
     */
    public function test_regular_user_cannot_access_admin_reset_password(): void
    {
        $regularUser = Pengguna::where('email', 'jovian@mncu.ac.id')->first()
            ?? Pengguna::whereHas('peran', fn ($q) => $q->where('nama_peran', 'Mahasiswa'))->first();

        $targetUser = Pengguna::whereHas('peran', fn ($q) => $q->where('nama_peran', 'Dosen'))->first()
            ?? Pengguna::where('email', '!=', $regularUser->email)->first();

        $response = $this->actingAs($regularUser)->post(
            route('admin.users.reset-password', $targetUser->id_pengguna),
            [
                'password_baru' => 'AdminReset123!',
                'password_baru_confirmation' => 'AdminReset123!',
            ]
        );

        $response->assertStatus(403);
    }

    /**
     * Skenario 2: Super Admin dapat mereset password akun user lain secara manual tanpa perlu password lama.
     */
    public function test_super_admin_can_reset_another_user_password_manually_without_old_password(): void
    {
        $admin = Pengguna::where('email', 'arifin@mncu.ac.id')->first();
        $this->assertNotNull($admin);

        $targetUser = Pengguna::where('email', 'jovian@mncu.ac.id')->first()
            ?? Pengguna::whereHas('peran', fn ($q) => $q->where('nama_peran', 'Mahasiswa'))->first();

        $response = $this->actingAs($admin)->post(
            route('admin.users.reset-password', $targetUser->id_pengguna),
            [
                'password_baru' => 'AdminReset2026#',
                'password_baru_confirmation' => 'AdminReset2026#',
            ]
        );

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Pastikan password user di database berubah ke password baru
        $this->assertTrue(Hash::check('AdminReset2026#', $targetUser->fresh()->kata_sandi));

        // Pastikan target user dapat login menggunakan password yang diatur oleh Admin
        auth()->logout();

        $loginResponse = $this->post('/login', [
            'email' => $targetUser->email,
            'kata_sandi' => 'AdminReset2026#',
        ]);

        $loginResponse->assertRedirect(route('beranda'));
        $this->assertAuthenticatedAs($targetUser);
    }

    /**
     * Skenario 2: Super Admin dapat mereset password akun user lain ke password default secara instan.
     */
    public function test_super_admin_can_reset_user_password_to_default(): void
    {
        $admin = Pengguna::where('email', 'arifin@mncu.ac.id')->first();
        $this->assertNotNull($admin);

        $targetUser = Pengguna::where('email', 'jovian@mncu.ac.id')->first()
            ?? Pengguna::whereHas('peran', fn ($q) => $q->where('nama_peran', 'Mahasiswa'))->first();

        $expectedDefault = 'Mncu#'.($targetUser->nomor_identitas ?: substr($targetUser->id_pengguna, -5));

        $response = $this->actingAs($admin)->post(
            route('admin.users.reset-password', $targetUser->id_pengguna),
            [
                'reset_to_default' => '1',
            ]
        );

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $response->assertSessionHas('reset_password_preview', $expectedDefault);

        // Pastikan password di database sesuai dengan default
        $this->assertTrue(Hash::check($expectedDefault, $targetUser->fresh()->kata_sandi));

        // Pastikan user bisa login dengan password default tersebut
        auth()->logout();

        $loginResponse = $this->post('/login', [
            'email' => $targetUser->email,
            'kata_sandi' => $expectedDefault,
        ]);

        $loginResponse->assertRedirect(route('beranda'));
        $this->assertAuthenticatedAs($targetUser);
    }
}
