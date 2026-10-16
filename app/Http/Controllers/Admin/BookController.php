<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\JenisBuku;
use App\Models\KategoriBuku;
use App\Models\Penerbit;
use App\Models\Penulis;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookController extends Controller
{
    /**
     * Tampilkan daftar seluruh koleksi buku perpustakaan.
     */
    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));
        $kategoriId = trim((string) $request->query('kategori', ''));
        $jenisId = trim((string) $request->query('jenis', ''));

        $query = Buku::with(['jenisBuku', 'kategoriBuku', 'penerbit', 'penulis', 'eksemplar'])->latest();

        if ($q !== '') {
            $query->where(function ($sub) use ($q): void {
                $sub->where('judul_buku', 'like', "%{$q}%")
                    ->orWhere('isbn', 'like', "%{$q}%")
                    ->orWhere('deskripsi', 'like', "%{$q}%");
            });
        }

        if ($kategoriId !== '') {
            $query->where('id_kategori_buku', $kategoriId);
        }

        if ($jenisId !== '') {
            $query->where('id_jenis_buku', $jenisId);
        }

        $books = $query->paginate(10)->withQueryString();
        $categories = KategoriBuku::orderBy('nama_kategori')->get();
        $types = JenisBuku::orderBy('nama_jenis')->get();

        return view('admin.books.index', compact('books', 'categories', 'types', 'q', 'kategoriId', 'jenisId'));
    }

    /**
     * Tampilkan formulir penambahan buku baru ke katalog.
     */
    public function create(): View
    {
        $categories = KategoriBuku::orderBy('nama_kategori')->get();
        $types = JenisBuku::orderBy('nama_jenis')->get();
        $publishers = Penerbit::orderBy('nama_penerbit')->get();
        $authors = Penulis::orderBy('nama_penulis')->get();

        return view('admin.books.create', compact('categories', 'types', 'publishers', 'authors'));
    }

    /**
     * Simpan buku baru ke dalam database katalog.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'judul_buku' => ['required', 'string', 'max:255'],
            'id_jenis_buku' => ['required', 'exists:jenis_buku,id_jenis_buku'],
            'id_kategori_buku' => ['required', 'exists:kategori_buku,id_kategori_buku'],
            'id_penerbit' => ['nullable', 'exists:penerbit,id_penerbit'],
            'isbn' => ['nullable', 'string', 'max:20'],
            'tahun_terbit' => ['nullable', 'integer', 'min:1900', 'max:'.(date('Y') + 1)],
            'edisi' => ['nullable', 'string', 'max:50'],
            'jumlah_halaman' => ['nullable', 'integer', 'min:1'],
            'sampul_buku' => ['nullable', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
        ], [
            'judul_buku.required' => 'Judul buku wajib diisi.',
            'id_jenis_buku.required' => 'Jenis buku wajib dipilih.',
            'id_kategori_buku.required' => 'Kategori buku wajib dipilih.',
        ]);

        $book = Buku::create($validated);

        if ($request->filled('id_penulis')) {
            $book->penulis()->sync([$request->input('id_penulis')]);
        }

        return redirect()->route('admin.books.index')
            ->with('success', "Buku \"{$book->judul_buku}\" ({$book->id_buku}) berhasil ditambahkan ke katalog!");
    }

    /**
     * Tampilkan formulir edit koleksi buku.
     */
    public function edit(string $id): View
    {
        $book = Buku::with(['penulis', 'penerbit'])->findOrFail($id);
        $categories = KategoriBuku::orderBy('nama_kategori')->get();
        $types = JenisBuku::orderBy('nama_jenis')->get();
        $publishers = Penerbit::orderBy('nama_penerbit')->get();
        $authors = Penulis::orderBy('nama_penulis')->get();

        return view('admin.books.edit', compact('book', 'categories', 'types', 'publishers', 'authors'));
    }

    /**
     * Perbarui data buku dalam katalog.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $book = Buku::findOrFail($id);

        $validated = $request->validate([
            'judul_buku' => ['required', 'string', 'max:255'],
            'id_jenis_buku' => ['required', 'exists:jenis_buku,id_jenis_buku'],
            'id_kategori_buku' => ['required', 'exists:kategori_buku,id_kategori_buku'],
            'id_penerbit' => ['nullable', 'exists:penerbit,id_penerbit'],
            'isbn' => ['nullable', 'string', 'max:20'],
            'tahun_terbit' => ['nullable', 'integer', 'min:1900', 'max:'.(date('Y') + 1)],
            'edisi' => ['nullable', 'string', 'max:50'],
            'jumlah_halaman' => ['nullable', 'integer', 'min:1'],
            'sampul_buku' => ['nullable', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
        ], [
            'judul_buku.required' => 'Judul buku wajib diisi.',
            'id_jenis_buku.required' => 'Jenis buku wajib dipilih.',
            'id_kategori_buku.required' => 'Kategori buku wajib dipilih.',
        ]);

        $book->update($validated);

        if ($request->has('id_penulis')) {
            $authorId = $request->input('id_penulis');
            $book->penulis()->sync($authorId ? [$authorId] : []);
        }

        return redirect()->route('admin.books.index')
            ->with('success', "Katalog buku \"{$book->judul_buku}\" berhasil diperbarui!");
    }

    /**
     * Hapus koleksi buku dari database.
     */
    public function destroy(string $id): RedirectResponse
    {
        $book = Buku::findOrFail($id);
        $judul = $book->judul_buku;

        // Lepas relasi pivot penulis dan subjek jika ada
        $book->penulis()->detach();
        $book->subjek()->detach();
        $book->eksemplar()->delete();

        $book->delete();

        return redirect()->route('admin.books.index')
            ->with('success', "Buku \"{$judul}\" berhasil dihapus dari katalog.");
    }
}
