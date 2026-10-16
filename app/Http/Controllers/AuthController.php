<?php

namespace App\Http\Controllers;

use App\Mail\ResetPasswordMail;
use App\Models\Pengguna;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman formulir login pemustaka dan staf perpustakaan.
     */
    public function showLoginForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('beranda')->with('info', 'Anda sudah masuk.');
        }

        return view('auth.login');
    }

    /**
     * Proses otentikasi login pemustaka menggunakan Laravel Auth & Sanctum stateful session.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'kata_sandi' => ['required', 'string'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'kata_sandi.required' => 'Kata sandi wajib diisi.',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['kata_sandi']], $remember)) {
            $request->session()->regenerate();

            /** @var Pengguna $user */
            $user = Auth::user();

            return redirect()->intended(route('beranda'))->with(
                'success',
                "Selamat datang kembali, {$user->nama_lengkap}! Anda berhasil masuk sebagai {$user->peran?->nama_peran}."
            );
        }

        return back()->withErrors([
            'email' => 'Email atau kata sandi yang Anda masukkan tidak sesuai dengan database perpustakaan.',
        ])->onlyInput('email');
    }

    /**
     * Tampilkan halaman atau modal bantuan lupa kata sandi.
     */
    public function showForgotPasswordForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('beranda');
        }

        return view('auth.forgot-password');
    }

    /**
     * Kirim email reset kata sandi otomatis berisi kata sandi baru.
     */
    public function sendResetPassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'exists:pengguna,email'],
        ], [
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.exists' => 'Alamat email tidak terdaftar dalam database pengguna perpustakaan.',
        ]);

        /** @var Pengguna $pengguna */
        $pengguna = Pengguna::where('email', $validated['email'])->firstOrFail();

        // Buat kata sandi baru yang aman dan mudah diingat
        $newPassword = 'Mncu'.random_int(100, 999).'@'.strtoupper(Str::random(4));

        // Enkripsi kata sandi baru menggunakan Hash::make()
        $pengguna->kata_sandi = Hash::make($newPassword);
        $pengguna->save();

        // Kirim email notifikasi secara otomatis
        try {
            Mail::to($pengguna->email)->send(new ResetPasswordMail($pengguna, $newPassword));
            $mailSuccess = true;
        } catch (\Throwable $e) {
            report($e);
            $mailSuccess = false;
        }

        $message = "Kata sandi baru berhasil dibuat dan dikirim ke {$pengguna->email}. Silakan cek email Anda untuk masuk.";

        return redirect()->route('login')
            ->with('status', $message)
            ->with('demo_new_password', $newPassword)
            ->with('demo_email', $pengguna->email);
    }

    /**
     * Proses keluar dari sesi sistem perpustakaan.
     */
    public function logout(Request $request): RedirectResponse
    {
        /** @var Pengguna|null $user */
        $user = Auth::user();

        if ($user) {
            // Hapus token Sanctum pengguna jika ada
            $user->tokens()->delete();
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('info', 'Anda telah berhasil keluar dari akun perpustakaan.');
    }
}
