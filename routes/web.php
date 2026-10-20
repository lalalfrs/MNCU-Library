<?php

use App\Http\Controllers\AcaraController;
use App\Http\Controllers\Admin\AcaraAdminController;
use App\Http\Controllers\Admin\BeritaAdminController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Otentikasi Pemustaka & Pustakawan (Sanctum / Laravel Auth)
Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/daftar', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/daftar', [AuthController::class, 'register'])->name('register.post');
    Route::get('/lupa-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/lupa-password', [AuthController::class, 'sendResetPassword'])->name('password.email');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Profil Pengguna (Khusus Pengguna Terautentikasi)
Route::middleware('auth')->group(function (): void {
    Route::get('/profil', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profil/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');
});

// Beranda Utama
Route::get('/', [BerandaController::class, 'index'])->name('beranda');

// Profil & Informasi Perpustakaan
Route::view('/tentang-kami', 'tentang-kami')->name('tentang-kami');
Route::view('/kontak', 'kontak')->name('kontak');
Route::get('/pustakawan', [LibraryController::class, 'pustakawan'])->name('pustakawan');

// Berita Perpustakaan
Route::get('/berita', [BeritaController::class, 'index'])->name('berita.index');
Route::get('/berita/{slug}', [BeritaController::class, 'show'])->name('berita.show');

// Acara & Kegiatan Perpustakaan
Route::get('/acara', [AcaraController::class, 'index'])->name('acara.index');
Route::get('/acara/{slug}', [AcaraController::class, 'show'])->name('acara.show');

// Chatbot & Bantuan Pemustaka
Route::match(['get', 'post'], '/chat', [LibraryController::class, 'chat'])->name('chat');

// 5 Layanan Perpustakaan Utama
Route::get('/opac', [LibraryController::class, 'opac'])->name('opac');
Route::post('/opac/kunjungan/{buku}', [LibraryController::class, 'recordKunjungan'])->name('opac.kunjungan');
Route::get('/repository', [LibraryController::class, 'repository'])->name('repository');
Route::get('/e-journal', [LibraryController::class, 'ejournal'])->name('ejournal');
Route::get('/ojs-prodi', [LibraryController::class, 'ojs'])->name('ojs');
Route::get('/e-book', [LibraryController::class, 'ebook'])->name('ebook');

// Menu Manajemen Perpustakaan (CRUD - Khusus Super Admin / Kepala Perpustakaan)
Route::middleware(['auth', 'superadmin'])->prefix('admin')->as('admin.')->group(function (): void {
    // 1. Manajemen User
    Route::resource('users', UserController::class);

    // 2. Manajemen Buku & Stok Eksemplar
    Route::resource('buku', BookController::class)->names('books');
    Route::post('buku/{buku}/eksemplar', [BookController::class, 'storeEksemplar'])->name('books.eksemplar.store');
    Route::put('buku/{buku}/eksemplar/{eksemplar}', [BookController::class, 'updateEksemplar'])->name('books.eksemplar.update');
    Route::delete('buku/{buku}/eksemplar/{eksemplar}', [BookController::class, 'destroyEksemplar'])->name('books.eksemplar.destroy');

    // 3. Manajemen Acara
    Route::resource('acara', AcaraAdminController::class)->names('acara');

    // 4. Manajemen Berita
    Route::resource('berita', BeritaAdminController::class)->names('berita');
});
