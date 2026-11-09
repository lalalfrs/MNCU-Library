<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use App\Models\VisitorLog;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
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
     * Cari identitas pengguna (NIM/NIP) untuk otomatis mengisi nama pemustaka terdaftar.
     */
    public function lookup(Request $request): JsonResponse
    {
        $identitas = trim((string) $request->query('identitas', ''));
        $kategori = trim((string) $request->query('kategori', ''));

        if (empty($identitas) || strlen($identitas) < 3) {
            return response()->json([
                'found' => false,
                'message' => 'Masukkan minimal 3 karakter identitas.',
            ]);
        }

        // Cari berdasarkan nomor identitas, id_pengguna, nim, atau nidn
        $query = Pengguna::query()
            ->with(['prodi', 'peran'])
            ->where(function ($q) use ($identitas) {
                $q->where('nomor_identitas', $identitas)
                    ->orWhere('id_pengguna', $identitas)
                    ->orWhere('nim', $identitas)
                    ->orWhere('nidn', $identitas);
            });

        // Filter kategori jika diberikan
        if ($kategori === 'Mahasiswa') {
            $query->whereHas('peran', function ($q) {
                $q->where('nama_peran', 'Mahasiswa');
            });
        } elseif (in_array($kategori, ['Dosen / Tendik', 'Dosen', 'Tendik'], true)) {
            $query->whereHas('peran', function ($q) {
                $q->whereIn('nama_peran', ['Dosen', 'Tendik', 'Pustakawan', 'Kepala Perpustakaan', 'Super Admin']);
            });
        }

        $user = $query->first();

        // Fallback jika tidak ditemukan dengan filter kategori, cari tanpa batasan peran
        if (! $user) {
            $user = Pengguna::query()
                ->with(['prodi', 'peran'])
                ->where(function ($q) use ($identitas) {
                    $q->where('nomor_identitas', $identitas)
                        ->orWhere('id_pengguna', $identitas)
                        ->orWhere('nim', $identitas)
                        ->orWhere('nidn', $identitas);
                })
                ->first();
        }

        if ($user) {
            $userKategori = 'Mahasiswa';
            if ($user->isDosen() || $user->isTendik() || $user->isPustakawan() || $user->isSuperAdmin()) {
                $userKategori = 'Dosen / Tendik';
            }

            return response()->json([
                'found' => true,
                'nama_lengkap' => $user->nama_lengkap,
                'nomor_identitas' => $user->nomor_identitas ?? $user->id_pengguna,
                'kategori' => $userKategori,
                'peran' => $user->peran?->nama_peran ?? 'Pemustaka',
                'prodi' => $user->prodi?->nama_prodi,
            ]);
        }

        return response()->json([
            'found' => false,
            'message' => 'NIM / NIP tidak terdaftar dalam database pemustaka.',
        ]);
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

        $userId = auth()->id();
        if (! $userId && ! empty($validated['identitas_pengunjung'])) {
            $matchedUser = Pengguna::query()
                ->where('nomor_identitas', trim($validated['identitas_pengunjung']))
                ->orWhere('id_pengguna', trim($validated['identitas_pengunjung']))
                ->orWhere('nim', trim($validated['identitas_pengunjung']))
                ->orWhere('nidn', trim($validated['identitas_pengunjung']))
                ->first();

            $userId = $matchedUser?->id_pengguna;
        }

        $kategoriPengunjung = $validated['kategori_pengunjung'] ?? 'Umum';
        if ($kategoriPengunjung !== 'Umum' && isset($matchedUser) && $matchedUser) {
            $kategoriPengunjung = $matchedUser->peran?->nama_peran ?? $kategoriPengunjung;
        }

        VisitorLog::create([
            'id_pengguna' => $userId,
            'nama_pengunjung' => trim($validated['nama_pengunjung']),
            'kategori_pengunjung' => $kategoriPengunjung,
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
