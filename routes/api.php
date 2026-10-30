<?php

use App\Http\Controllers\Api\PeminjamanApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Endpoint Otentikasi Token Sanctum (untuk pengujian API & integrasi)
Route::post('/sanctum/token', [PeminjamanApiController::class, 'generateToken'])->name('api.token');

// Endpoint Layanan Sirkulasi & Validasi Peminjaman Berbasis Sanctum
Route::middleware('auth:sanctum')->group(function (): void {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // 1. Validasi eligibilitas mahasiswa (cek apakah ada buku yang belum dikembalikan)
    Route::get('/peminjaman/cek-eligibilitas/{id_pengguna}', [PeminjamanApiController::class, 'cekEligibilitas'])->name('api.peminjaman.cek-eligibilitas');

    // 2. Daftar eksemplar tersedia untuk buku tertentu
    Route::get('/peminjaman/eksemplar-tersedia/{id_buku}', [PeminjamanApiController::class, 'eksemplarTersedia'])->name('api.peminjaman.eksemplar-tersedia');

    // 3. Validasi transaksi pengembalian buku & kalkulasi hari keterlambatan
    Route::post('/peminjaman/validasi-pengembalian', [PeminjamanApiController::class, 'validasiPengembalian'])->name('api.peminjaman.validasi-pengembalian');

    // 4. Catat transaksi peminjaman baru (validasi eligibilitas & kurangi status eksemplar)
    Route::post('/peminjaman', [PeminjamanApiController::class, 'store'])->name('api.peminjaman.store');

    // 5. Proses pengembalian buku (tambah kembali status eksemplar menjadi Tersedia)
    Route::post('/peminjaman/{id}/kembalikan', [PeminjamanApiController::class, 'kembalikan'])->name('api.peminjaman.kembalikan');

    // 6. Kirim peringatan keterlambatan ke mahasiswa yang melewati batas waktu
    Route::post('/peminjaman/{id}/kirim-peringatan', [PeminjamanApiController::class, 'kirimPeringatan'])->name('api.peminjaman.kirim-peringatan');
});
