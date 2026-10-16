<?php

namespace Tests\Feature;

use App\Mail\ResetPasswordMail;
use App\Models\Pengguna;
use App\Models\Prodi;
use Database\Seeders\PerpustakaanSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PerpustakaanSeeder::class);
    }

    /**
     * Pastikan halaman login dapat diakses dan menampilkan komponen UI sesuai spesifikasi.
     */
    public function test_login_page_renders_successfully(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Masuk ke Akun');
        $response->assertSee('Isi Otomatis Super Admin');
        $response->assertSee('Lupa kata sandi?');
        $response->assertSee('Dr. H. Muhammad Arifin, M.Hum.');
        $response->assertSee('Daftar Akun Baru');
        $response->assertDontSee('Masuk dengan SSO Mahasiswa');
    }

    /**
     * Pastikan Super Admin (Kepala Perpustakaan) dapat login dengan kata sandi ter-hash.
     */
    public function test_super_admin_can_authenticate_with_hashed_password(): void
    {
        $admin = Pengguna::where('email', 'arifin@mncu.ac.id')->first();
        $this->assertNotNull($admin, 'Akun Super Admin harus tersedia di database');

        $response = $this->post('/login', [
            'email' => 'arifin@mncu.ac.id',
            'kata_sandi' => 'password123',
        ]);

        $response->assertRedirect(route('beranda'));
        $this->assertAuthenticatedAs($admin);
    }

    /**
     * Pastikan otentikasi ditolak jika kata sandi salah.
     */
    public function test_login_fails_with_invalid_credentials(): void
    {
        $response = $this->from('/login')->post('/login', [
            'email' => 'arifin@mncu.ac.id',
            'kata_sandi' => 'password_salah_123',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /**
     * Pastikan halaman lupa kata sandi dapat diakses.
     */
    public function test_forgot_password_page_renders_successfully(): void
    {
        $response = $this->get('/lupa-password');

        $response->assertStatus(200);
        $response->assertSee('Lupa Kata Sandi?');
        $response->assertSee('Kirim Kata Sandi Baru ke Email');
    }

    /**
     * Pastikan alur lupa kata sandi membuat password baru, meng-hash ke database, dan mengirimkan email.
     */
    public function test_forgot_password_sends_email_and_updates_password(): void
    {
        Mail::fake();

        $user = Pengguna::where('email', 'fauzi.mhs@mncu.ac.id')->first();
        $this->assertNotNull($user);

        $response = $this->post('/lupa-password', [
            'email' => 'fauzi.mhs@mncu.ac.id',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('status');
        $response->assertSessionHas('demo_new_password');

        $newPassword = session('demo_new_password');
        $this->assertNotEmpty($newPassword);

        // Verifikasi email dikirimkan
        Mail::assertSent(ResetPasswordMail::class, function (ResetPasswordMail $mail) use ($user, $newPassword): bool {
            return $mail->hasTo($user->email) && $mail->newPassword === $newPassword;
        });

        // Verifikasi kata sandi di database telah diperbarui dengan hash baru
        $user->refresh();
        $this->assertTrue(Hash::check($newPassword, $user->kata_sandi));

        // Kembalikan ke password awal untuk konsistensi pengujian berulang
        $user->kata_sandi = Hash::make('password123');
        $user->save();
    }

    /**
     * Pastikan pengguna yang sedang login dapat keluar (logout) dengan aman dan token Sanctum terhapus.
     */
    public function test_authenticated_user_can_logout(): void
    {
        $admin = Pengguna::where('email', 'arifin@mncu.ac.id')->first();
        $this->actingAs($admin);

        // Buat token Sanctum untuk menguji penghapusan token saat logout
        $token = $admin->createToken('test-logout-token');
        $this->assertNotNull($token);
        $this->assertSame(1, $admin->tokens()->count());

        $response = $this->post('/logout');

        $response->assertRedirect(route('login'));
        $this->assertGuest();
        $this->assertSame(0, $admin->tokens()->count());
    }

    /**
     * Pastikan halaman registrasi pemustaka dapat diakses dan menampilkan formulir lengkap.
     */
    public function test_register_page_renders_successfully(): void
    {
        $response = $this->get('/daftar');

        $response->assertStatus(200);
        $response->assertSee('Daftar Akun Baru');
        $response->assertSee('Keanggotaan Pemustaka');
        $response->assertSee('Program Studi');
        $response->assertSee('Masuk Sekarang');
    }

    /**
     * Pastikan calon mahasiswa dapat mendaftar akun baru dan otomatis login dengan ID MNCUXXXXX.
     */
    public function test_user_can_register_new_account(): void
    {
        $prodi = Prodi::first();

        $response = $this->post('/daftar', [
            'nama_lengkap' => 'Budi Santoso',
            'email' => 'budi.santoso@mncu.ac.id',
            'nomor_identitas' => '202409999',
            'id_prodi' => $prodi?->id_prodi,
            'kata_sandi' => 'secret123',
            'kata_sandi_confirmation' => 'secret123',
            'terms' => '1',
        ]);

        $response->assertRedirect(route('beranda'));
        $response->assertSessionHas('success');

        $user = Pengguna::where('email', 'budi.santoso@mncu.ac.id')->first();
        $this->assertNotNull($user);
        $this->assertSame('Budi Santoso', $user->nama_lengkap);
        $this->assertMatchesRegularExpression('/^MNCU\d{5}$/', $user->id_pengguna);
        $this->assertSame('MNCU00003', $user->id_peran);
        $this->assertTrue(Hash::check('secret123', $user->kata_sandi));
        $this->assertAuthenticatedAs($user);
    }

    /**
     * Pastikan validasi pendaftaran menolak email duplikat atau password tidak cocok.
     */
    public function test_register_validation_fails_for_duplicate_email(): void
    {
        $response = $this->from('/daftar')->post('/daftar', [
            'nama_lengkap' => 'Duplikat User',
            'email' => 'arifin@mncu.ac.id', // Sudah ada
            'kata_sandi' => 'secret123',
            'kata_sandi_confirmation' => 'berbeda123',
            'terms' => '1',
        ]);

        $response->assertRedirect('/daftar');
        $response->assertSessionHasErrors(['email', 'kata_sandi']);
        $this->assertGuest();
    }
}
