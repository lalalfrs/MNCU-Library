<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BeritaAdminController extends Controller
{
    /**
     * Tampilkan daftar seluruh artikel dan berita perpustakaan.
     */
    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));
        $kategori = trim((string) $request->query('kategori', ''));

        $query = Berita::with('pengguna')->latest('tanggal_publikasi');

        if ($q !== '') {
            $query->where(function ($sub) use ($q): void {
                $sub->where('judul_berita', 'like', "%{$q}%")
                    ->orWhere('deskripsi_berita', 'like', "%{$q}%")
                    ->orWhere('isi_berita', 'like', "%{$q}%");
            });
        }

        if ($kategori !== '') {
            $query->where('kategori_berita', $kategori);
        }

        $news = $query->paginate(10)->withQueryString();
        $categories = Berita::distinct()->pluck('kategori_berita');

        return view('admin.berita.index', compact('news', 'categories', 'q', 'kategori'));
    }

    /**
     * Tampilkan formulir penulisan berita baru.
     */
    public function create(): View
    {
        return view('admin.berita.create');
    }

    /**
     * Simpan berita baru ke dalam database perpustakaan.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'judul_berita' => ['required', 'string', 'max:255'],
            'kategori_berita' => ['required', 'string', 'max:100'],
            'deskripsi_berita' => ['required', 'string', 'max:255'],
            'isi_berita' => ['required', 'string'],
            'gambar_berita' => ['nullable', 'string', 'max:255'],
            'tanggal_publikasi' => ['required', 'date'],
        ], [
            'judul_berita.required' => 'Judul berita wajib diisi.',
            'kategori_berita.required' => 'Kategori berita wajib dipilih.',
            'deskripsi_berita.required' => 'Ringkasan singkat berita wajib diisi.',
            'isi_berita.required' => 'Konten lengkap berita wajib diisi.',
            'tanggal_publikasi.required' => 'Tanggal publikasi wajib ditentukan.',
        ]);

        $validated['id_pengguna'] = Auth::id() ?? 'MNCU00001';
        $validated['slug'] = Str::slug($validated['judul_berita']).'-'.strtolower(Str::random(5));
        $validated['views_count'] = 0;

        $berita = Berita::create($validated);

        return redirect()->route('admin.berita.index')
            ->with('success', "Berita \"{$berita->judul_berita}\" berhasil diterbitkan!");
    }

    /**
     * Tampilkan formulir edit berita perpustakaan.
     */
    public function edit(string $id): View
    {
        $berita = Berita::findOrFail($id);

        return view('admin.berita.edit', compact('berita'));
    }

    /**
     * Perbarui konten berita perpustakaan.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $berita = Berita::findOrFail($id);

        $validated = $request->validate([
            'judul_berita' => ['required', 'string', 'max:255'],
            'kategori_berita' => ['required', 'string', 'max:100'],
            'deskripsi_berita' => ['required', 'string', 'max:255'],
            'isi_berita' => ['required', 'string'],
            'gambar_berita' => ['nullable', 'string', 'max:255'],
            'tanggal_publikasi' => ['required', 'date'],
        ], [
            'judul_berita.required' => 'Judul berita wajib diisi.',
            'kategori_berita.required' => 'Kategori berita wajib dipilih.',
            'deskripsi_berita.required' => 'Ringkasan singkat berita wajib diisi.',
            'isi_berita.required' => 'Konten lengkap berita wajib diisi.',
            'tanggal_publikasi.required' => 'Tanggal publikasi wajib ditentukan.',
        ]);

        $berita->update($validated);

        return redirect()->route('admin.berita.index')
            ->with('success', "Berita \"{$berita->judul_berita}\" berhasil diperbarui!");
    }

    /**
     * Hapus artikel berita dari database perpustakaan.
     */
    public function destroy(string $id): RedirectResponse
    {
        $berita = Berita::findOrFail($id);
        $judul = $berita->judul_berita;
        $berita->delete();

        return redirect()->route('admin.berita.index')
            ->with('success', "Berita \"{$judul}\" berhasil dihapus.");
    }
}
