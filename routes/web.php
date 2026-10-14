<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'beranda')->name('beranda');
Route::view('/tentang-kami', 'tentang-kami')->name('tentang-kami');
Route::view('/kontak', 'kontak')->name('kontak');
Route::view('/pustakawan', 'pustakawan')->name('pustakawan');
