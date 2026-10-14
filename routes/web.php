<?php

use App\Http\Controllers\LibraryController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'beranda')->name('beranda');
// Halaman Informasi Perpustakaan
Route::view('/tentang-kami', 'tentang-kami')->name('tentang-kami');
Route::view('/kontak', 'kontak')->name('kontak');
Route::view('/pustakawan', 'pustakawan')->name('pustakawan');

// Chatbot & Bantuan Pemustaka
Route::match(['get', 'post'], '/chat', [LibraryController::class, 'chat'])->name('chat');

// 5 Layanan Perpustakaan Utama
Route::get('/opac', [LibraryController::class, 'opac'])->name('opac');
Route::get('/repository', [LibraryController::class, 'repository'])->name('repository');
Route::get('/e-journal', [LibraryController::class, 'ejournal'])->name('ejournal');
Route::get('/ojs-prodi', [LibraryController::class, 'ojs'])->name('ojs');
Route::get('/e-book', [LibraryController::class, 'ebook'])->name('ebook');
