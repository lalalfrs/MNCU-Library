<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\BukuPenulis;
use App\Models\EksemplarBuku;
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
     * Tampilkan daftar seluruh koleksi buku perpustakaan dengan metrik stok inventaris.
     */
    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));
        $kategoriId = trim((string) $request->query('kategori', ''));
        $jenisId = trim((string) $request->query('jenis', ''));
        $statusStok = trim((string) $request->query('stok', ''));

        // Statistik Inventaris Global Perpustakaan
        $totalBooks = Buku::count();
        $totalCopies = EksemplarBuku::count();
        $availableCopies = EksemplarBuku::where('status', 'Tersedia')->count();
        $borrowedCopies = EksemplarBuku::where('status', 'Dipinjam')->count();

        $query = Buku::with(['jenisBuku', 'kategoriBuku', 'penerbit', 'penulis', 'eksemplar'])->latest();

        if ($q !== '') {
            $query->where(function ($sub) use ($q): void {
                $sub->where('judul_buku', 'like', "%{$q}%")
                    ->orWhere('isbn', 'like', "%{$q}%")
                    ->orWhere('deskripsi', 'like', "%{$q}%")
                    ->orWhereHas('penulis', function ($authorSub) use ($q): void {
                        $authorSub->where('nama_penulis', 'like', "%{$q}%");
                    })
                    ->orWhereHas('eksemplar', function ($ekSub) use ($q): void {
                        $ekSub->where('kode_inventaris', 'like', "%{$q}%")
                            ->orWhere('nomor_panggil', 'like', "%{$q}%");
                    });
            });
        }

        if ($kategoriId !== '') {
            $query->where('id_kategori_buku', $kategoriId);
        }

        if ($jenisId !== '') {
            $query->where('id_jenis_buku', $jenisId);
        }

        if ($statusStok === 'tersedia') {
            $query->whereHas('eksemplar', function ($sq): void {
                $sq->where('status', 'Tersedia');
            });
        } elseif ($statusStok === 'habis') {
            $query->has('eksemplar')->whereDoesntHave('eksemplar', function ($sq): void {
                $sq->where('status', 'Tersedia');
            });
        } elseif ($statusStok === 'kosong') {
            $query->doesntHave('eksemplar');
        }

        $books = $query->paginate(12)->withQueryString();
        $categories = KategoriBuku::orderBy('nama_kategori')->get();
        $types = JenisBuku::orderBy('nama_jenis_buku')->get();
        $viewMode = in_array($request->query('view'), ['grid', 'table'], true) ? $request->query('view') : 'table';

        return view('admin.books.index', compact(
            'books',
            'categories',
            'types',
            'q',
            'kategoriId',
            'jenisId',
            'statusStok',
            'viewMode',
            'totalBooks',
            'totalCopies',
            'availableCopies',
            'borrowedCopies'
        ));
    }

    /**
     * Tampilkan formulir penambahan buku baru ke katalog beserta konfigurasi stok awal.
     */
    public function create(): View
    {
        $categories = KategoriBuku::orderBy('nama_kategori')->get();
        $types = JenisBuku::orderBy('nama_jenis_buku')->get();
        $publishers = Penerbit::orderBy('nama_penerbit')->get();
        $authors = Penulis::orderBy('nama_penulis')->get();

        return view('admin.books.create', compact('categories', 'types', 'publishers', 'authors'));
    }

    /**
     * Simpan buku baru dan opsional cetak eksemplar fisik awal ke dalam database katalog.
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
            'nama_penulis_manual' => ['nullable', 'string', 'max:150'],
            'stok_awal' => ['nullable', 'integer', 'min:0', 'max:50'],
            'nomor_panggil_dasar' => ['nullable', 'string', 'max:50'],
            'lokasi_rak_dasar' => ['nullable', 'string', 'max:100'],
        ], [
            'judul_buku.required' => 'Judul buku wajib diisi.',
            'id_jenis_buku.required' => 'Jenis buku wajib dipilih.',
            'id_kategori_buku.required' => 'Kategori buku wajib dipilih.',
        ]);

        $book = Buku::create($validated);

        $this->syncBookAuthor($book, $request);

        // Jika pustakawan mengisi jumlah stok awal, buat eksemplar otomatis
        $stokAwal = (int) $request->input('stok_awal', 0);
        if ($stokAwal > 0) {
            $nomorPanggil = trim((string) $request->input('nomor_panggil_dasar', ''));
            $lokasiRak = trim((string) $request->input('lokasi_rak_dasar', 'Lantai 2, Rak Koleksi'));

            for ($i = 1; $i <= $stokAwal; $i++) {
                $kodeInventaris = 'INV-'.$book->id_buku.'-'.str_pad((string) $i, 2, '0', STR_PAD_LEFT);
                $nomorPanggilCopy = $nomorPanggil !== '' ? "{$nomorPanggil} c.{$i}" : null;

                EksemplarBuku::create([
                    'id_buku' => $book->id_buku,
                    'kode_inventaris' => $kodeInventaris,
                    'nomor_panggil' => $nomorPanggilCopy,
                    'lokasi_rak' => $lokasiRak !== '' ? $lokasiRak : 'Lantai 2, Rak Koleksi',
                    'status' => 'Tersedia',
                ]);
            }
        }

        $pesanStok = $stokAwal > 0 ? " dengan {$stokAwal} eksemplar fisik siap dipinjam" : '';

        return redirect()->route('admin.books.index')
            ->with('success', "Buku \"{$book->judul_buku}\" ({$book->id_buku}) berhasil ditambahkan ke katalog{$pesanStok}!");
    }

    /**
     * Tampilkan detail komprehensif buku beserta daftar lengkap dan status seluruh eksemplar fisik.
     */
    public function show(string $id): View
    {
        $book = Buku::with(['penulis', 'jenisBuku', 'kategoriBuku', 'penerbit', 'eksemplar', 'subjek'])->findOrFail($id);

        $totalCopies = $book->eksemplar->count();
        $availableCopies = $book->eksemplar->where('status', 'Tersedia')->count();
        $borrowedCopies = $book->eksemplar->where('status', 'Dipinjam')->count();
        $otherCopies = $book->eksemplar->whereNotIn('status', ['Tersedia', 'Dipinjam'])->count();

        return view('admin.books.show', compact('book', 'totalCopies', 'availableCopies', 'borrowedCopies', 'otherCopies'));
    }

    /**
     * Tampilkan formulir edit koleksi buku.
     */
    public function edit(string $id): View
    {
        $book = Buku::with(['penulis', 'penerbit', 'eksemplar'])->findOrFail($id);
        $categories = KategoriBuku::orderBy('nama_kategori')->get();
        $types = JenisBuku::orderBy('nama_jenis_buku')->get();
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
            'nama_penulis_manual' => ['nullable', 'string', 'max:150'],
        ], [
            'judul_buku.required' => 'Judul buku wajib diisi.',
            'id_jenis_buku.required' => 'Jenis buku wajib dipilih.',
            'id_kategori_buku.required' => 'Kategori buku wajib dipilih.',
        ]);

        $book->update($validated);

        $this->syncBookAuthor($book, $request);

        return redirect()->route('admin.books.index')
            ->with('success', "Katalog buku \"{$book->judul_buku}\" berhasil diperbarui!");
    }

    /**
     * Tambah eksemplar fisik baru untuk koleksi buku ini.
     */
    public function storeEksemplar(Request $request, string $id): RedirectResponse
    {
        $book = Buku::findOrFail($id);

        $validated = $request->validate([
            'kode_inventaris' => ['nullable', 'string', 'max:50', 'unique:eksemplar_buku,kode_inventaris'],
            'nomor_panggil' => ['nullable', 'string', 'max:50'],
            'lokasi_rak' => ['nullable', 'string', 'max:100'],
            'status' => ['required', 'string', 'in:Tersedia,Dipinjam,Rusak,Hilang,Perbaikan'],
        ], [
            'kode_inventaris.unique' => 'Kode inventaris / barcode ini sudah terdaftar di sistem.',
            'status.in' => 'Status eksemplar tidak valid.',
        ]);

        if (empty($validated['kode_inventaris'])) {
            $nextIndex = $book->eksemplar()->count() + 1;
            $candidate = 'INV-'.$book->id_buku.'-'.str_pad((string) $nextIndex, 2, '0', STR_PAD_LEFT);
            while (EksemplarBuku::where('kode_inventaris', $candidate)->exists()) {
                $nextIndex++;
                $candidate = 'INV-'.$book->id_buku.'-'.str_pad((string) $nextIndex, 2, '0', STR_PAD_LEFT);
            }
            $validated['kode_inventaris'] = $candidate;
        }

        $validated['id_buku'] = $book->id_buku;
        $validated['lokasi_rak'] = $validated['lokasi_rak'] ?: 'Lantai 2, Rak Koleksi';

        $eksemplar = EksemplarBuku::create($validated);

        return redirect()->route('admin.books.show', $book->id_buku)
            ->with('success', "Eksemplar baru ({$eksemplar->kode_inventaris}) berhasil ditambahkan ke inventaris!");
    }

    /**
     * Perbarui data atau status eksemplar fisik (misal saat dipinjam, dikembalikan, atau perbaikan).
     */
    public function updateEksemplar(Request $request, string $id, string $idEksemplar): RedirectResponse
    {
        $book = Buku::findOrFail($id);
        $eksemplar = EksemplarBuku::where('id_buku', $book->id_buku)->findOrFail($idEksemplar);

        $validated = $request->validate([
            'kode_inventaris' => ['required', 'string', 'max:50', 'unique:eksemplar_buku,kode_inventaris,'.$eksemplar->id_eksemplar.',id_eksemplar'],
            'nomor_panggil' => ['nullable', 'string', 'max:50'],
            'lokasi_rak' => ['nullable', 'string', 'max:100'],
            'status' => ['required', 'string', 'in:Tersedia,Dipinjam,Rusak,Hilang,Perbaikan'],
        ], [
            'kode_inventaris.required' => 'Kode inventaris tidak boleh kosong.',
            'kode_inventaris.unique' => 'Kode inventaris ini sudah digunakan oleh eksemplar lain.',
        ]);

        $eksemplar->update($validated);

        return redirect()->route('admin.books.show', $book->id_buku)
            ->with('success', "Data eksemplar \"{$eksemplar->kode_inventaris}\" berhasil diperbarui.");
    }

    /**
     * Hapus satu eksemplar fisik dari inventaris.
     */
    public function destroyEksemplar(string $id, string $idEksemplar): RedirectResponse
    {
        $book = Buku::findOrFail($id);
        $eksemplar = EksemplarBuku::where('id_buku', $book->id_buku)->findOrFail($idEksemplar);
        $kode = $eksemplar->kode_inventaris;

        $eksemplar->delete();

        return redirect()->route('admin.books.show', $book->id_buku)
            ->with('success', "Eksemplar \"{$kode}\" berhasil dihapus dari inventaris.");
    }

    /**
     * Sinkronisasi pengarang utama buku (baik dipilih dari daftar maupun diketik manual).
     */
    protected function syncBookAuthor(Buku $book, Request $request): void
    {
        $manualAuthor = trim((string) $request->input('nama_penulis_manual', ''));
        $authorId = $request->input('id_penulis');
        $targetAuthorId = null;

        if ($manualAuthor !== '') {
            $author = Penulis::whereRaw('LOWER(nama_penulis) = ?', [strtolower($manualAuthor)])->first();
            if (! $author) {
                $author = Penulis::create([
                    'nama_penulis' => $manualAuthor,
                    'deskripsi_penulis' => 'Penulis ditambahkan melalui entri katalog buku',
                ]);
            }
            $targetAuthorId = $author->id_penulis;
        } elseif (! empty($authorId)) {
            $targetAuthorId = $authorId;
        }

        if ($targetAuthorId) {
            BukuPenulis::where('id_buku', $book->id_buku)->delete();
            BukuPenulis::create([
                'id_buku' => $book->id_buku,
                'id_penulis' => $targetAuthorId,
            ]);
        }
    }

    /**
     * Hapus koleksi buku dari database.
     */
    public function destroy(string $id): RedirectResponse
    {
        $book = Buku::findOrFail($id);
        $judul = $book->judul_buku;

        // Lepas relasi pivot penulis dan subjek jika ada
        BukuPenulis::where('id_buku', $book->id_buku)->delete();
        $book->subjek()->detach();
        $book->eksemplar()->delete();

        $book->delete();

        return redirect()->route('admin.books.index')
            ->with('success', "Buku \"{$judul}\" berhasil dihapus dari katalog.");
    }
}
