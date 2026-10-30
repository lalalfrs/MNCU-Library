<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\PeminjamanBuku;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman lihat profil pengguna beserta riwayat peminjamannya.
     */
    public function show(Request $request): View
    {
        $user = $request->user();
        $role = $user->role ?? 'mahasiswa';

        // Query riwayat peminjaman spesifik pengguna yang login
        $riwayatPeminjaman = PeminjamanBuku::with(['buku.kategoriBuku', 'eksemplar'])
            ->where('id_pengguna', $user->id_pengguna)
            ->orderByRaw("
                CASE 
                    WHEN status IN ('Menunggu Persetujuan', 'Dipinjam', 'Terlambat', 'Menunggu Verifikasi') THEN 1 
                    ELSE 2 
                END
            ")
            ->latest('tanggal_pinjam')
            ->latest('created_at')
            ->get();

        $totalPinjaman = $riwayatPeminjaman->count();
        $totalPinjamAktif = $riwayatPeminjaman->filter(function ($pinjam) {
            return empty($pinjam->tanggal_kembali) && in_array($pinjam->status, ['Dipinjam', 'Terlambat', 'Menunggu Verifikasi'], true);
        })->count();
        $totalPinjamSelesai = $riwayatPeminjaman->filter(function ($pinjam) {
            return in_array($pinjam->status, ['Kembali', 'Dikembalikan'], true) || ! empty($pinjam->tanggal_kembali);
        })->count();

        return view('profile.show', compact(
            'user',
            'role',
            'riwayatPeminjaman',
            'totalPinjaman',
            'totalPinjamAktif',
            'totalPinjamSelesai'
        ));
    }

    /**
     * Tampilkan halaman riwayat peminjaman spesifik pengguna yang sedang login.
     */
    public function riwayatPeminjaman(Request $request): View
    {
        $user = $request->user();
        $role = $user->role ?? 'mahasiswa';
        $statusFilter = strtolower((string) $request->query('status', 'semua'));

        $query = PeminjamanBuku::with(['buku.kategoriBuku', 'eksemplar'])
            ->where('id_pengguna', $user->id_pengguna);

        if ($statusFilter === 'aktif' || $statusFilter === 'berjalan' || $statusFilter === 'sedang_berjalan') {
            // Sedang berjalan: peminjaman aktif yang belum dikembalikan
            $query->whereNull('tanggal_kembali')
                ->whereIn('status', ['Dipinjam', 'Terlambat', 'Menunggu Verifikasi']);
        } elseif ($statusFilter === 'selesai' || $statusFilter === 'kembali' || $statusFilter === 'dikembalikan') {
            // Selesai dikembalikan: status 'Kembali', 'Dikembalikan', atau sudah memiliki tanggal_kembali
            $query->where(function ($q) {
                $q->whereIn('status', ['Kembali', 'Dikembalikan'])
                    ->orWhereNotNull('tanggal_kembali');
            });
        } elseif ($statusFilter === 'terlambat' || $statusFilter === 'keterlambatan') {
            // Keterlambatan: status Terlambat, lewat tanggal jatuh tempo belum kembali, atau ada hari_keterlambatan > 0
            $query->where(function ($q) {
                $q->where('status', 'Terlambat')
                    ->orWhere(function ($sub) {
                        $sub->whereNull('tanggal_kembali')
                            ->whereDate('tanggal_jatuh_tempo', '<', now()->toDateString());
                    })
                    ->orWhere('hari_keterlambatan', '>', 0);
            });
        } elseif ($statusFilter === 'menunggu' || $statusFilter === 'verifikasi' || $statusFilter === 'menunggu_verifikasi') {
            // Menunggu verifikasi pengembalian atau persetujuan pinjam
            $query->whereNull('tanggal_kembali')
                ->whereIn('status', ['Menunggu Verifikasi', 'Menunggu Persetujuan']);
        }

        $riwayatPeminjaman = $query->orderByRaw("
                CASE 
                    WHEN status IN ('Menunggu Persetujuan', 'Dipinjam', 'Terlambat', 'Menunggu Verifikasi') THEN 1 
                    ELSE 2 
                END
            ")
            ->latest('tanggal_pinjam')
            ->latest('created_at')
            ->paginate(10)
            ->withQueryString();

        $baseQuery = PeminjamanBuku::where('id_pengguna', $user->id_pengguna);

        $ringkasan = [
            'total' => (clone $baseQuery)->count(),
            'aktif' => (clone $baseQuery)
                ->whereNull('tanggal_kembali')
                ->whereIn('status', ['Dipinjam', 'Terlambat', 'Menunggu Verifikasi'])
                ->count(),
            'selesai' => (clone $baseQuery)
                ->where(function ($q) {
                    $q->whereIn('status', ['Kembali', 'Dikembalikan'])
                        ->orWhereNotNull('tanggal_kembali');
                })
                ->count(),
            'terlambat' => (clone $baseQuery)
                ->where(function ($q) {
                    $q->where('status', 'Terlambat')
                        ->orWhere(function ($sub) {
                            $sub->whereNull('tanggal_kembali')
                                ->whereDate('tanggal_jatuh_tempo', '<', now()->toDateString());
                        })
                        ->orWhere('hari_keterlambatan', '>', 0);
                })
                ->count(),
            'menunggu' => (clone $baseQuery)
                ->whereNull('tanggal_kembali')
                ->whereIn('status', ['Menunggu Verifikasi', 'Menunggu Persetujuan'])
                ->count(),
        ];

        return view('profile.riwayat', compact('user', 'role', 'riwayatPeminjaman', 'statusFilter', 'ringkasan'));
    }

    /**
     * Tampilkan formulir edit profil pengguna berdasarkan role.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $role = $user->role ?? 'mahasiswa';

        return view('profile.edit', compact('user', 'role'));
    }

    /**
     * Simpan pembaruan data profil dan kelola berkas foto profil di storage.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        // Logika unggah foto baru dan penghapusan foto lama dari folder storage
        if ($request->hasFile('foto_profil')) {
            if ($user->foto_profil && Storage::disk('public')->exists($user->foto_profil)) {
                Storage::disk('public')->delete($user->foto_profil);
            }

            $path = $request->file('foto_profil')->store('profile-photos', 'public');
            $validated['foto_profil'] = $path;
        }

        // Penyesuaian data khusus role
        $role = $user->role ?? 'mahasiswa';
        if ($role === 'admin') {
            $validated['nim'] = null;
            $validated['nidn'] = null;
        } elseif ($role === 'mahasiswa') {
            $validated['nidn'] = null;
        } elseif ($role === 'dosen') {
            $validated['nim'] = null;
        }

        // Penanganan ubah kata sandi opsional tanpa perlu password lama
        if (! empty($validated['kata_sandi'])) {
            $hashedPassword = Hash::make($validated['kata_sandi']);
            $validated['kata_sandi'] = $hashedPassword;
            if (in_array('password', $user->getFillable(), true) || array_key_exists('password', $user->getAttributes())) {
                $validated['password'] = $hashedPassword;
            }
        } else {
            unset($validated['kata_sandi']);
        }

        // Sinkronisasi atribut name jika menggunakan tabel users standar
        if (isset($validated['nama_lengkap']) && (in_array('name', $user->getFillable(), true) || array_key_exists('name', $user->getAttributes()))) {
            $validated['name'] = $validated['nama_lengkap'];
        }

        $user->update($validated);

        return redirect()->route('profile.edit')->with('success', 'Profil Anda berhasil diperbarui.');
    }

    /**
    /**
     * Reset / perbarui kata sandi mandiri pengguna langsung dari profil.
     * Mendukung field password_baru maupun kata_sandi (confirmed, min 6).
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        return $this->resetPassword($request);
    }

    /**
     * Reset kata sandi pengguna langsung dari profil masing-masing.
     */
    public function resetPassword(Request $request): RedirectResponse
    {
        $field = $request->has('kata_sandi') ? 'kata_sandi' : 'password_baru';

        $validated = $request->validate([
            $field => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            "{$field}.required" => 'Kata sandi baru wajib diisi.',
            "{$field}.min" => 'Kata sandi baru minimal 6 karakter.',
            "{$field}.confirmed" => 'Konfirmasi kata sandi baru tidak cocok. Pastikan kedua input kata sandi sama persis.',
        ]);

        $newPassword = $validated[$field];
        $user = $request->user();

        $hashed = Hash::make($newPassword);
        $user->kata_sandi = $hashed;

        if (isset($user->password) || in_array('password', $user->getFillable(), true) || array_key_exists('password', $user->getAttributes())) {
            $user->password = $hashed;
        }

        $user->save();

        return back()->with('success', 'Kata sandi akun Anda berhasil diperbarui! Silakan gunakan kata sandi baru untuk akses berikutnya.');
    }
}
