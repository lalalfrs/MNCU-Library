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
     * Tampilkan daftar seluruh pengguna perpustakaan dengan pencarian, filter peran, dan filter status member.
     */
    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));
        $peranId = trim((string) $request->query('peran', ''));
        $statusMember = trim((string) $request->query('status_member', ''));

        // Statistik Pengguna & Member
        $totalUsers = Pengguna::count();
        $totalMembers = Pengguna::where('is_member', true)->count();
        $totalNonMembers = Pengguna::where('is_member', false)->count();

        $query = Pengguna::with(['peran', 'prodi'])->latest();

        if ($q !== '') {
            $query->where(function ($sub) use ($q): void {
                $sub->where('nama_lengkap', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('nomor_identitas', 'like', "%{$q}%")
                    ->orWhere('nomor_anggota', 'like', "%{$q}%");
            });
        }

        if ($peranId !== '') {
            $query->where('id_peran', $peranId);
        }

        if ($statusMember === 'member') {
            $query->where('is_member', true);
        } elseif ($statusMember === 'non_member') {
            $query->where('is_member', false);
        }

        $users = $query->paginate(10)->withQueryString();
        $roles = Peran::orderBy('nama_peran')->get();

        // Daftar pengguna yang belum menjadi member untuk opsi modal "Tambah Data Member"
        $candidateUsers = Pengguna::with('peran')
            ->where('is_member', false)
            ->orderBy('nama_lengkap')
            ->get();

        return view('admin.users.index', compact(
            'users',
            'roles',
            'q',
            'peranId',
            'statusMember',
            'totalUsers',
            'totalMembers',
            'totalNonMembers',
            'candidateUsers'
        ));
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
     * Promote pengguna (mahasiswa / dosen) menjadi member perpustakaan (sesuai flowchart).
     */
    public function promoteMember(Request $request, string $id): RedirectResponse
    {
        $user = Pengguna::findOrFail($id);

        if ($user->isMember()) {
            return back()->with('info', "Pengguna {$user->nama_lengkap} sudah berstatus sebagai member aktif.");
        }

        $validated = $request->validate([
            'nomor_anggota' => ['nullable', 'string', 'max:30', 'unique:pengguna,nomor_anggota'],
        ], [
            'nomor_anggota.unique' => 'Nomor anggota tersebut sudah digunakan oleh member lain.',
        ]);

        $nomorAnggota = ! empty($validated['nomor_anggota']) ? trim($validated['nomor_anggota']) : null;
        $user->promoteToMember($nomorAnggota);

        return redirect()->route('admin.users.index')
            ->with('success', "User {$user->nama_lengkap} berhasil menjadi member perpustakaan dengan Nomor Anggota {$user->nomor_anggota}!");
    }

    /**
     * Hapus data member / cabut status keanggotaan (sesuai flowchart).
     * Validasi: Apakah member masih ada buku yang dipinjam?
     * - Jika Ya: Jangan hapus, berikan pesan error.
     * - Jika Tidak: Data berhasil dihapus (status member dicabut).
     */
    public function demoteMember(Request $request, string $id): RedirectResponse
    {
        $user = Pengguna::with('peminjaman')->findOrFail($id);

        if (! $user->isMember()) {
            return back()->with('info', "Pengguna {$user->nama_lengkap} bukan merupakan member aktif.");
        }

        // Validasi apakah member masih ada buku yang dipinjam
        if ($user->memilikiTanggunganPinjaman()) {
            $count = $user->peminjaman()
                ->whereNull('tanggal_kembali')
                ->whereIn('status', ['Dipinjam', 'Terlambat', 'Menunggu Verifikasi'])
                ->count();

            return back()->withErrors([
                'error' => "Gagal menghapus data member: {$user->nama_lengkap} masih memiliki {$count} buku yang sedang dipinjam dan belum dikembalikan. Sesuai ketentuan, proses penghapusan data member ditolak sampai seluruh buku tersebut dikembalikan ke perpustakaan.",
            ]);
        }

        $user->demoteFromMember();

        return redirect()->route('admin.users.index')
            ->with('success', "Data member {$user->nama_lengkap} berhasil dihapus. Status pengguna kini kembali menjadi non-member.");
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

        if ($user->memilikiTanggunganPinjaman()) {
            $count = $user->peminjaman()
                ->whereNull('tanggal_kembali')
                ->whereIn('status', ['Dipinjam', 'Terlambat', 'Menunggu Verifikasi'])
                ->count();

            return back()->withErrors(['error' => "Gagal menghapus pengguna: {$user->nama_lengkap} masih memiliki {$count} buku yang sedang dipinjam dan belum dikembalikan. Proses penghapusan ditolak sampai seluruh buku tersebut dikembalikan ke perpustakaan."]);
        }

        $nama = $user->nama_lengkap;
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "Pengguna {$nama} berhasil dihapus dari sistem.");
    }
}
