<?php

namespace App\Http\Controllers;

use App\Models\GuestLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GuestLogController extends Controller
{
    /**
     * Store a newly created guest log attendance record.
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'min:2', 'max:255'],
            'kategori' => ['required', 'string', Rule::in(['Mahasiswa', 'Dosen / Staf', 'Dosen/Staf', 'Umum'])],
            'identitas' => ['required', 'string', 'min:2', 'max:500'],
        ], [
            'nama.required' => 'Nama lengkap wajib diisi.',
            'nama.min' => 'Nama lengkap minimal terdiri dari 2 karakter.',
            'kategori.required' => 'Kategori pengunjung wajib dipilih.',
            'kategori.in' => 'Kategori pengunjung tidak valid.',
            'identitas.required' => 'Identitas (NIM / NIP / Alamat) wajib diisi.',
            'identitas.min' => 'Identitas minimal terdiri dari 2 karakter.',
        ]);

        if ($validated['kategori'] === 'Dosen/Staf') {
            $validated['kategori'] = 'Dosen / Staf';
        }

        $guestLog = GuestLog::create([
            'nama' => $validated['nama'],
            'kategori' => $validated['kategori'],
            'identitas' => $validated['identitas'],
            'waktu_masuk' => now(),
        ]);

        $message = "Selamat datang, {$guestLog->nama}! Absensi berhasil dicatat.";

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => [
                    'id' => $guestLog->id,
                    'nama' => $guestLog->nama,
                    'kategori' => $guestLog->kategori,
                    'identitas' => $guestLog->identitas,
                    'waktu_masuk' => $guestLog->waktu_masuk?->toIso8601String(),
                ],
            ], 201);
        }

        return redirect()->back()->with('success', $message);
    }
}
