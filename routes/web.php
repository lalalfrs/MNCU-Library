<?php

use App\Http\Controllers\AcaraController;
use App\Http\Controllers\Admin\AcaraAdminController;
use App\Http\Controllers\Admin\BeritaAdminController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\PeminjamanAdminController;
use App\Http\Controllers\Admin\ReportAdminController;
use App\Http\Controllers\Admin\StatisticAdminController;
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

// Skenario 1: Reset / Ubah Password oleh Pengguna yang Sedang Login (Dosen & Mahasiswa)
Route::middleware('auth')->group(function (): void {
    Route::get('/ubah-password', [AuthController::class, 'showChangePasswordForm'])->name('password.edit');
    Route::put('/ubah-password', [AuthController::class, 'updatePassword'])->name('password.update');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Profil Pengguna (Khusus Pengguna Terautentikasi)
Route::middleware('auth')->group(function (): void {
    Route::get('/profil', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profil/riwayat-peminjaman', [ProfileController::class, 'riwayatPeminjaman'])->name('profile.riwayat');
    Route::get('/riwayat-peminjaman', [ProfileController::class, 'riwayatPeminjaman'])->name('peminjaman.riwayat');
    Route::get('/profil/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profil/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::put('/profil/reset-password', [ProfileController::class, 'resetPassword']);
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

// Layanan Peminjaman & Pengembalian Buku Mandiri via Website OPAC (Sanctum / Web Auth)
Route::middleware(['auth'])->group(function (): void {
    Route::get('/opac/cek-eligibilitas', [LibraryController::class, 'cekEligibilitasPeminjaman'])->name('opac.cek-eligibilitas');
    Route::post('/opac/pinjam', [LibraryController::class, 'pinjamBuku'])->name('opac.pinjam');
    Route::post('/opac/kembalikan', [LibraryController::class, 'kembalikanBukuMandiri'])->name('opac.kembalikan');
    Route::post('/opac/ajukan-pengembalian', [LibraryController::class, 'ajukanPengembalianBuku'])->name('opac.ajukan-pengembalian');
});

Route::get('/repository', [LibraryController::class, 'repository'])->name('repository');
Route::get('/e-journal', [LibraryController::class, 'ejournal'])->name('ejournal');
Route::get('/ojs-prodi', [LibraryController::class, 'ojs'])->name('ojs');
Route::get('/e-book', [LibraryController::class, 'ebook'])->name('ebook');

// Menu Manajemen Perpustakaan (CRUD - Khusus Super Admin / Kepala Perpustakaan)
Route::middleware(['auth', 'superadmin'])->prefix('admin')->as('admin.')->group(function (): void {
    // 1. Manajemen User & Keanggotaan Member Perpustakaan
    Route::post('users/{user}/promote-member', [UserController::class, 'promoteMember'])->name('users.promote-member');
    Route::post('users/{user}/demote-member', [UserController::class, 'demoteMember'])->name('users.demote-member');
    Route::resource('users', UserController::class);
    Route::post('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');

    // 2. Manajemen Buku & Stok Eksemplar
    Route::resource('buku', BookController::class)->names('books');
    Route::post('buku/{buku}/kata-kunci', [BookController::class, 'updateKataKunci'])->name('books.kata-kunci.update');
    Route::post('buku/{buku}/eksemplar', [BookController::class, 'storeEksemplar'])->name('books.eksemplar.store');
    Route::put('buku/{buku}/eksemplar/{eksemplar}', [BookController::class, 'updateEksemplar'])->name('books.eksemplar.update');
    Route::delete('buku/{buku}/eksemplar/{eksemplar}', [BookController::class, 'destroyEksemplar'])->name('books.eksemplar.destroy');

    // 3. Manajemen Acara
    Route::resource('acara', AcaraAdminController::class)->names('acara');

    // 4. Manajemen Berita
    Route::resource('berita', BeritaAdminController::class)->names('berita');

    // 5. Manajemen Peminjaman & Keterlambatan Sirkulasi Buku
    Route::get('inbox', [PeminjamanAdminController::class, 'inbox'])->name('inbox');
    Route::get('peminjaman/cek-eligibilitas/{id_pengguna}', [PeminjamanAdminController::class, 'cekEligibilitasUser'])->name('peminjaman.cek-eligibilitas');
    Route::resource('peminjaman', PeminjamanAdminController::class)->except(['edit', 'update', 'destroy']);
    Route::post('peminjaman/{peminjaman}/kembalikan', [PeminjamanAdminController::class, 'kembalikan'])->name('peminjaman.kembalikan');
    Route::post('peminjaman/{peminjaman}/tolak-pengembalian', [PeminjamanAdminController::class, 'tolakPengembalian'])->name('peminjaman.tolak-pengembalian');
    Route::post('peminjaman/{peminjaman}/setujui-pinjam', [PeminjamanAdminController::class, 'setujuiPeminjaman'])->name('peminjaman.setujui-pinjam');
    Route::post('peminjaman/{peminjaman}/tolak-pinjam', [PeminjamanAdminController::class, 'tolakPeminjaman'])->name('peminjaman.tolak-pinjam');
    Route::post('peminjaman/{peminjaman}/kirim-peringatan', [PeminjamanAdminController::class, 'kirimPeringatan'])->name('peminjaman.kirim-peringatan');
    Route::post('peminjaman/{peminjaman}/input-keterlambatan', [PeminjamanAdminController::class, 'inputKeterlambatan'])->name('peminjaman.input-keterlambatan');

    // 6. Statistik Perpustakaan Sesuai Flowchart
    Route::get('statistik', [StatisticAdminController::class, 'index'])->name('statistics.index');

    // 7. Laporan & Rekapitulasi Perpustakaan Sesuai Flowchart
    Route::get('laporan', [ReportAdminController::class, 'index'])->name('reports.index');
    Route::get('laporan/export-csv', [ReportAdminController::class, 'exportCsv'])->name('reports.export-csv');
});
