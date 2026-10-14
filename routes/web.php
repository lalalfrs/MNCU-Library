<?php

use App\Http\Controllers\AcaraController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\BeritaController;
use Illuminate\Support\Facades\Route;

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
