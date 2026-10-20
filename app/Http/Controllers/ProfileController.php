<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman lihat profil pengguna.
     */
    public function show(Request $request): View
    {
        $user = $request->user();
        $role = $user->role ?? 'mahasiswa';

        return view('profile.show', compact('user', 'role'));
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

        // Sinkronisasi atribut name jika menggunakan tabel users standar
        if (isset($validated['nama_lengkap']) && (in_array('name', $user->getFillable(), true) || array_key_exists('name', $user->getAttributes()))) {
            $validated['name'] = $validated['nama_lengkap'];
        }

        $user->update($validated);

        return redirect()->route('profile.edit')->with('success', 'Profil Anda berhasil diperbarui.');
    }
}
