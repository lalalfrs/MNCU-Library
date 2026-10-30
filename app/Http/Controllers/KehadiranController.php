<?php

namespace App\Http\Controllers;

use App\Models\VisitorLog;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class KehadiranController extends Controller
{
    /**
     * Tampilkan halaman Buku Tamu & Presensi Kehadiran Pengunjung Perpustakaan.
     */
    public function index(): View
    {
        $visitorStats = VisitorLog::getStatistics();

        $recentAttendees = VisitorLog::query()
            ->whereNotNull('nama_pengunjung')
            ->latest('waktu_kunjungan')
            ->limit(10)
            ->get();

        return view('kehadiran', compact('visitorStats', 'recentAttendees'));
    }

    /**
     * Simpan data kehadiran pengunjung dan tambahkan ke statistik kunjungan perpustakaan.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_pengunjung' => ['required', 'string', 'max:150'],
            'kategori_pengunjung' => ['nullable', 'string', 'max:50'],
            'identitas_pengunjung' => ['required', 'string', 'max:255'],
            'keperluan' => ['nullable', 'string', 'max:255'],
        ], [
            'nama_pengunjung.required' => 'Nama lengkap pengunjung wajib diisi.',
            'identitas_pengunjung.required' => 'NIM / NIP / Alamat (untuk Umum) wajib diisi.',
        ]);

        VisitorLog::create([
            'id_pengguna' => auth()->id(),
            'nama_pengunjung' => trim($validated['nama_pengunjung']),
            'kategori_pengunjung' => $validated['kategori_pengunjung'] ?? 'Umum',
            'identitas_pengunjung' => trim($validated['identitas_pengunjung']),
            'keperluan' => ! empty($validated['keperluan']) ? trim($validated['keperluan']) : 'Kunjungan Perpustakaan',
            'waktu_kunjungan' => now(),
            'session_id' => 'kehadiran-'.uniqid(),
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 500),
        ]);

        return redirect()
            ->route('kehadiran.index')
            ->with('success', 'Terima kasih, kehadiran '.trim($validated['nama_pengunjung']).' berhasil dicatat dan ditambahkan ke Statistik Kunjungan Perpustakaan!');
    }
}
