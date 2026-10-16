<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengguna;
use App\Models\Peran;
use App\Models\Prodi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Tampilkan daftar seluruh pengguna perpustakaan dengan pencarian dan filter peran.
     */
    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));
        $peranId = trim((string) $request->query('peran', ''));

        $query = Pengguna::with(['peran', 'prodi'])->latest();

        if ($q !== '') {
            $query->where(function ($sub) use ($q): void {
                $sub->where('nama_lengkap', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('nomor_identitas', 'like', "%{$q}%");
            });
        }

        if ($peranId !== '') {
            $query->where('id_peran', $peranId);
        }

        $users = $query->paginate(10)->withQueryString();
        $roles = Peran::orderBy('nama_peran')->get();

        return view('admin.users.index', compact('users', 'roles', 'q', 'peranId'));
    }

    /**
     * Tampilkan formulir penambahan pengguna baru.
     */
    public function create(): View
    {
        $roles = Peran::orderBy('nama_peran')->get();
        $prodis = Prodi::orderBy('nama_prodi')->get();

        return view('admin.users.create', compact('roles', 'prodis'));
    }

    /**
     * Simpan pengguna baru ke dalam database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100', 'unique:pengguna,email'],
            'kata_sandi' => ['required', 'string', 'min:6'],
            'id_peran' => ['required', 'exists:peran,id_peran'],
            'id_prodi' => ['nullable', 'exists:prodi,id_prodi'],
            'nomor_identitas' => ['nullable', 'string', 'max:30', 'unique:pengguna,nomor_identitas'],
        ], [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah terdaftar.',
            'kata_sandi.required' => 'Kata sandi wajib diisi.',
            'kata_sandi.min' => 'Kata sandi minimal 6 karakter.',
            'id_peran.required' => 'Peran pengguna wajib dipilih.',
            'nomor_identitas.unique' => 'Nomor identitas sudah digunakan.',
        ]);

        $validated['kata_sandi'] = Hash::make($validated['kata_sandi']);

        $user = Pengguna::create($validated);

        return redirect()->route('admin.users.index')
            ->with('success', "Pengguna {$user->nama_lengkap} ({$user->id_pengguna}) berhasil ditambahkan!");
    }

    /**
     * Tampilkan formulir edit data pengguna.
     */
    public function edit(string $id): View
    {
        $user = Pengguna::with(['peran', 'prodi'])->findOrFail($id);
        $roles = Peran::orderBy('nama_peran')->get();
        $prodis = Prodi::orderBy('nama_prodi')->get();

        return view('admin.users.edit', compact('user', 'roles', 'prodis'));
    }

    /**
     * Perbarui data pengguna dalam database.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $user = Pengguna::findOrFail($id);

        $validated = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100', Rule::unique('pengguna', 'email')->ignore($user->id_pengguna, 'id_pengguna')],
            'kata_sandi' => ['nullable', 'string', 'min:6'],
            'id_peran' => ['required', 'exists:peran,id_peran'],
            'id_prodi' => ['nullable', 'exists:prodi,id_prodi'],
            'nomor_identitas' => ['nullable', 'string', 'max:30', Rule::unique('pengguna', 'nomor_identitas')->ignore($user->id_pengguna, 'id_pengguna')],
        ], [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah terdaftar untuk pengguna lain.',
            'id_peran.required' => 'Peran pengguna wajib dipilih.',
        ]);

        if (! empty($validated['kata_sandi'])) {
            $validated['kata_sandi'] = Hash::make($validated['kata_sandi']);
        } else {
            unset($validated['kata_sandi']);
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', "Data pengguna {$user->nama_lengkap} berhasil diperbarui!");
    }

    /**
     * Hapus akun pengguna dari database.
     */
    public function destroy(string $id): RedirectResponse
    {
        if (Auth::id() === $id) {
            return back()->withErrors(['error' => 'Anda tidak dapat menghapus akun Anda sendiri saat sedang login.']);
        }

        $user = Pengguna::findOrFail($id);
        $nama = $user->nama_lengkap;
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "Pengguna {$nama} berhasil dihapus dari sistem.");
    }
}
