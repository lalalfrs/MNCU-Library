<?php

use App\Http\Controllers\AcaraController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\LibraryController;
use Illuminate\Support\Facades\Route;

// Beranda Utama
Route::get('/', [BerandaController::class, 'index'])->name('beranda');

// Profil & Informasi Perpustakaan
Route::view('/tentang-kami', 'tentang-kami')->name('tentang-kami');
Route::view('/kontak', 'kontak')->name('kontak');
Route::view('/pustakawan', 'pustakawan')->name('pustakawan');

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
Route::get('/repository', [LibraryController::class, 'repository'])->name('repository');
Route::get('/e-journal', [LibraryController::class, 'ejournal'])->name('ejournal');
Route::get('/ojs-prodi', [LibraryController::class, 'ojs'])->name('ojs');
Route::get('/e-book', [LibraryController::class, 'ebook'])->name('ebook');
