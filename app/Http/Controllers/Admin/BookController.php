<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\BukuPenulis;
use App\Models\EksemplarBuku;
use App\Models\JenisBuku;
use App\Models\KategoriBuku;
use App\Models\PeminjamanBuku;
use App\Models\Penerbit;
use App\Models\Pengguna;
use App\Models\Penulis;
use App\Services\BarcodeService;
use App\Services\MediaUploadService;
use App\Services\PeminjamanService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class BookController extends Controller
{
    public function __construct(
        protected MediaUploadService $mediaUploadService
    ) {}

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
                    ->orWhere('kata_kunci', 'like', "%{$q}%")
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
            'nama_kategori' => ['nullable', 'string', 'max:100'],
            'id_kategori_buku' => ['nullable', 'string', 'max:10'],
            'nama_jenis_buku' => ['nullable', 'string', 'max:100'],
            'id_jenis_buku' => ['nullable', 'string', 'max:10'],
            'nama_penerbit' => ['nullable', 'string', 'max:150'],
            'id_penerbit' => ['nullable', 'string', 'max:10'],
            'nama_penulis' => ['nullable', 'string', 'max:150'],
            'nama_penulis_manual' => ['nullable', 'string', 'max:150'],
            'dimensi' => ['nullable', 'string', 'max:100'],
            'isbn' => ['nullable', 'string', 'max:20'],
            'tahun_terbit' => ['nullable', 'integer', 'min:1900', 'max:'.(date('Y') + 1)],
            'edisi' => ['nullable', 'string', 'max:50'],
            'jumlah_halaman' => ['nullable', 'integer', 'min:1'],
            'sampul_buku' => ['nullable', 'string', 'max:255'],
            'sampul_buku_file' => ['nullable', 'file', 'mimes:png,jpg,jpeg,webp', 'max:200'],
            'deskripsi' => ['nullable', 'string'],
            'kata_kunci' => ['nullable', 'string', 'max:500'],
            'stok_awal' => ['nullable', 'integer', 'min:0', 'max:50'],
            'kode_barcode' => ['nullable', 'string', 'max:50'],
            'kode_inventaris' => ['nullable', 'string', 'max:50'],
            'nomor_panggil_dasar' => ['nullable', 'string', 'max:50'],
            'lokasi_rak_dasar' => ['nullable', 'string', 'max:100'],
        ], [
            'judul_buku.required' => 'Judul buku wajib diisi.',
            'sampul_buku_file.mimes' => 'Format file sampul buku harus berupa PNG, JPG, JPEG, atau WEBP.',
            'sampul_buku_file.max' => 'Ukuran file sampul buku maksimal 200 KB.',
        ]);

        $idKategori = $this->resolveKategoriId($request);
        $idJenis = $this->resolveJenisId($request);
        $idPenerbit = $this->resolvePenerbitId($request);

        if (empty($idKategori)) {
            throw ValidationException::withMessages(['nama_kategori' => 'Kategori buku wajib diisi.']);
        }
        if (empty($idJenis)) {
            throw ValidationException::withMessages(['nama_jenis_buku' => 'Format / jenis buku wajib diisi.']);
        }

        if ($request->hasFile('sampul_buku_file')) {
            $sampulBuku = $this->mediaUploadService->uploadImage(
                $request->file('sampul_buku_file'),
                'buku'
            );
        } else {
            $sampulBuku = $validated['sampul_buku'] ?? null;
        }

        $book = Buku::create([
            'judul_buku' => $validated['judul_buku'],
            'id_kategori_buku' => $idKategori,
            'id_jenis_buku' => $idJenis,
            'id_penerbit' => $idPenerbit,
            'dimensi' => $validated['dimensi'] ?? null,
            'isbn' => $validated['isbn'] ?? null,
            'tahun_terbit' => $validated['tahun_terbit'] ?? null,
            'edisi' => $validated['edisi'] ?? null,
            'jumlah_halaman' => $validated['jumlah_halaman'] ?? null,
            'sampul_buku' => $sampulBuku,
            'deskripsi' => $validated['deskripsi'] ?? null,
            'kata_kunci' => $validated['kata_kunci'] ?? null,
        ]);

        $this->syncBookAuthor($book, $request);

        // Jika pustakawan mengisi jumlah stok awal atau kode barcode, daftarkan eksemplar fisik
        $inputBarcode = trim((string) ($request->input('kode_barcode') ?: $request->input('kode_inventaris', '')));
        $stokAwal = (int) $request->input('stok_awal', $inputBarcode !== '' ? 1 : 0);
        $firstEksemplar = null;

        if ($stokAwal > 0) {
            $nomorPanggil = trim((string) $request->input('nomor_panggil_dasar', ''));
            $lokasiRak = trim((string) $request->input('lokasi_rak_dasar', 'Lantai 2, Rak Koleksi'));

            for ($i = 1; $i <= $stokAwal; $i++) {
                if ($i === 1 && $inputBarcode !== '') {
                    $kodeInventaris = $inputBarcode;
                } else {
                    $kodeInventaris = 'INV-'.$book->id_buku.'-'.str_pad((string) $i, 2, '0', STR_PAD_LEFT);
                }

                $uniqueCode = $kodeInventaris;
                $counter = 1;
                while (EksemplarBuku::where('kode_inventaris', $uniqueCode)->exists()) {
                    $uniqueCode = $kodeInventaris.'-'.$counter;
                    $counter++;
                }

                $nomorPanggilCopy = $nomorPanggil !== '' ? "{$nomorPanggil} c.{$i}" : null;

                $ek = EksemplarBuku::create([
                    'id_buku' => $book->id_buku,
                    'kode_inventaris' => $uniqueCode,
                    'nomor_panggil' => $nomorPanggilCopy,
                    'lokasi_rak' => $lokasiRak !== '' ? $lokasiRak : 'Lantai 2, Rak Koleksi',
                    'status' => 'Tersedia',
                ]);

                if (! $firstEksemplar) {
                    $firstEksemplar = $ek;
                }
            }
        }

        $pesanStok = $stokAwal > 0 ? " dengan {$stokAwal} eksemplar fisik siap dipinjam" : '';
        $cetakUrl = route('admin.books.cetak-label', [
            'buku' => $book->id_buku,
            'eksemplar' => $firstEksemplar?->id_eksemplar,
        ]);

        return redirect()->route('admin.books.index')
            ->with('success', "Buku \"{$book->judul_buku}\" ({$book->id_buku}) berhasil ditambahkan ke katalog{$pesanStok}!")
            ->with('cetak_label_url', $cetakUrl);
    }

    /**
     * Tampilkan detail komprehensif buku beserta daftar lengkap dan status seluruh eksemplar fisik.
     */
    public function show(string $id): View
    {
        $book = Buku::with([
            'penulis',
            'jenisBuku',
            'kategoriBuku',
            'penerbit',
            'eksemplar.peminjamanAktif.pengguna.peran',
            'subjek',
        ])->findOrFail($id);

        $totalCopies = $book->eksemplar->count();
        $availableCopies = $book->eksemplar->where('status', 'Tersedia')->count();
        $borrowedCopies = $book->eksemplar->where('status', 'Dipinjam')->count();
        $otherCopies = $book->eksemplar->whereNotIn('status', ['Tersedia', 'Dipinjam'])->count();

        // Ambil daftar seluruh mahasiswa & pemustaka untuk modal peminjaman eksemplar
        $users = Pengguna::with('peran')
            ->orderBy('nama_lengkap')
            ->get();

        return view('admin.books.show', compact('book', 'totalCopies', 'availableCopies', 'borrowedCopies', 'otherCopies', 'users'));
    }

    /**
     * Tampilkan pratinjau cetak stiker label buku/eksemplar (format 8cm x 5cm) dengan barcode SVG.
     */
    public function cetakLabel(Request $request, string $id, ?string $idEksemplar = null, ?BarcodeService $barcodeService = null): View
    {
        $barcodeService = $barcodeService ?? app(BarcodeService::class);
        $book = Buku::with(['penulis', 'jenisBuku', 'kategoriBuku', 'penerbit', 'eksemplar'])->findOrFail($id);

        $eksemplar = null;
        if ($idEksemplar) {
            $eksemplar = $book->eksemplar->firstWhere('id_eksemplar', $idEksemplar);
        }

        if (! $eksemplar) {
            $eksemplar = $book->eksemplar->first();
        }

        $barcodeText = $eksemplar ? $eksemplar->kode_inventaris : ($book->isbn ?: $book->id_buku);
        $barcodeSvg = $barcodeService->generateSvg($barcodeText, 30, false);
        $nomorPanggil = $eksemplar?->nomor_panggil ?: ($book->isbn ?: $book->id_buku);

        return view('admin.books.print-label', compact(
            'book',
            'eksemplar',
            'barcodeText',
            'barcodeSvg',
            'nomorPanggil'
        ));
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
            'nama_kategori' => ['nullable', 'string', 'max:100'],
            'id_kategori_buku' => ['nullable', 'string', 'max:10'],
            'nama_jenis_buku' => ['nullable', 'string', 'max:100'],
            'id_jenis_buku' => ['nullable', 'string', 'max:10'],
            'nama_penerbit' => ['nullable', 'string', 'max:150'],
            'id_penerbit' => ['nullable', 'string', 'max:10'],
            'nama_penulis' => ['nullable', 'string', 'max:150'],
            'nama_penulis_manual' => ['nullable', 'string', 'max:150'],
            'dimensi' => ['nullable', 'string', 'max:100'],
            'isbn' => ['nullable', 'string', 'max:20'],
            'tahun_terbit' => ['nullable', 'integer', 'min:1900', 'max:'.(date('Y') + 1)],
            'edisi' => ['nullable', 'string', 'max:50'],
            'jumlah_halaman' => ['nullable', 'integer', 'min:1'],
            'sampul_buku' => ['nullable', 'string', 'max:255'],
            'sampul_buku_file' => ['nullable', 'file', 'mimes:png,jpg,jpeg,webp', 'max:200'],
            'hapus_sampul' => ['nullable', 'boolean'],
            'deskripsi' => ['nullable', 'string'],
            'kata_kunci' => ['nullable', 'string', 'max:500'],
        ], [
            'judul_buku.required' => 'Judul buku wajib diisi.',
            'sampul_buku_file.mimes' => 'Format file sampul buku harus berupa PNG, JPG, JPEG, atau WEBP.',
            'sampul_buku_file.max' => 'Ukuran file sampul buku maksimal 200 KB.',
        ]);

        $idKategori = $this->resolveKategoriId($request);
        $idJenis = $this->resolveJenisId($request);
        $idPenerbit = $this->resolvePenerbitId($request);

        if ($request->boolean('hapus_sampul')) {
            $this->mediaUploadService->deleteImage($book->sampul_buku);
            $sampulBuku = null;
        } elseif ($request->hasFile('sampul_buku_file')) {
            $sampulBuku = $this->mediaUploadService->uploadImage(
                $request->file('sampul_buku_file'),
                'buku',
                $book->sampul_buku
            );
        } else {
            $sampulBuku = $request->filled('sampul_buku') ? $request->input('sampul_buku') : $book->sampul_buku;
        }

        $book->update([
            'judul_buku' => $validated['judul_buku'],
            'id_kategori_buku' => $idKategori ?: $book->id_kategori_buku,
            'id_jenis_buku' => $idJenis ?: $book->id_jenis_buku,
            'id_penerbit' => $idPenerbit,
            'dimensi' => $validated['dimensi'] ?? null,
            'isbn' => $validated['isbn'] ?? null,
            'tahun_terbit' => $validated['tahun_terbit'] ?? null,
            'edisi' => $validated['edisi'] ?? null,
            'jumlah_halaman' => $validated['jumlah_halaman'] ?? null,
            'sampul_buku' => $sampulBuku,
            'deskripsi' => $validated['deskripsi'] ?? null,
            'kata_kunci' => $validated['kata_kunci'] ?? null,
        ]);

        $this->syncBookAuthor($book, $request);

        return redirect()->route('admin.books.index')
            ->with('success', "Katalog buku \"{$book->judul_buku}\" berhasil diperbarui!");
    }

    /**
     * Perbarui kata kunci pencarian OPAC untuk buku tertentu langsung dari halaman detail buku admin.
     */
    public function updateKataKunci(Request $request, string $id): RedirectResponse
    {
        $book = Buku::findOrFail($id);

        $validated = $request->validate([
            'kata_kunci' => ['nullable', 'string', 'max:500'],
        ]);

        $book->update([
            'kata_kunci' => $validated['kata_kunci'] ?? null,
        ]);

        return redirect()->route('admin.books.show', $book->id_buku)
            ->with('success', "Kata kunci pencarian OPAC untuk buku \"{$book->judul_buku}\" berhasil diperbarui!");
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

        $targetStatus = $request->input('status');
        $oldStatus = $eksemplar->status;

        // Alur 1: Jika admin mengubah status menjadi 'Dipinjam'
        if ($targetStatus === 'Dipinjam' && $oldStatus !== 'Dipinjam') {
            $validatedLoan = $request->validate([
                'id_pengguna' => ['required', 'string', 'exists:pengguna,id_pengguna'],
                'tanggal_jatuh_tempo' => ['required', 'date', 'after_or_equal:today'],
                'tanggal_pinjam' => ['nullable', 'date'],
                'catatan' => ['nullable', 'string', 'max:500'],
            ], [
                'id_pengguna.required' => 'Pilih mahasiswa atau user yang meminjam buku.',
                'id_pengguna.exists' => 'Data mahasiswa peminjam tidak valid.',
                'tanggal_jatuh_tempo.required' => 'Tanggal batas pengembalian (jatuh tempo) wajib ditentukan.',
                'tanggal_jatuh_tempo.after_or_equal' => 'Tanggal pengembalian tidak boleh sebelum hari ini.',
            ]);

            // Update metadata eksemplar jika diubah
            $eksemplar->update([
                'kode_inventaris' => $request->input('kode_inventaris', $eksemplar->kode_inventaris),
                'nomor_panggil' => $request->input('nomor_panggil', $eksemplar->nomor_panggil),
                'lokasi_rak' => $request->input('lokasi_rak', $eksemplar->lokasi_rak),
            ]);

            $peminjamanService = app(PeminjamanService::class);
            $tanggalPinjam = $validatedLoan['tanggal_pinjam'] ?? now()->toDateString();
            $tanggalJatuhTempo = $validatedLoan['tanggal_jatuh_tempo'];

            try {
                $peminjaman = $peminjamanService->buatPeminjaman(
                    $validatedLoan['id_pengguna'],
                    $eksemplar->id_eksemplar,
                    $tanggalPinjam,
                    $tanggalJatuhTempo,
                    $validatedLoan['catatan'] ?? 'Peminjaman dicatat oleh admin melalui menu eksemplar'
                );

                return redirect()->route('admin.books.show', $book->id_buku)
                    ->with('success', "Eksemplar \"{$eksemplar->kode_inventaris}\" berhasil dipinjamkan kepada {$peminjaman->pengguna->nama_lengkap}. Status diubah menjadi Dipinjam.");
            } catch (ValidationException $e) {
                return redirect()->route('admin.books.show', $book->id_buku)
                    ->withErrors($e->errors())
                    ->with('error', $e->getMessage());
            }
        }

        // Alur 2: Jika admin mengubah status dari 'Dipinjam' menjadi 'Tersedia' (Pengembalian Buku)
        if ($oldStatus === 'Dipinjam' && $targetStatus === 'Tersedia') {
            $activeLoan = PeminjamanBuku::where('id_eksemplar', $eksemplar->id_eksemplar)
                ->whereNull('tanggal_kembali')
                ->latest('id_peminjaman')
                ->first();

            if ($activeLoan) {
                $peminjamanService = app(PeminjamanService::class);
                $peminjamanService->prosesPengembalian(
                    $activeLoan->id_peminjaman,
                    'Buku dikembalikan melalui pembaruan status eksemplar di admin panel'
                );

                // Update metadata eksemplar jika diubah
                $eksemplar->update([
                    'kode_inventaris' => $request->input('kode_inventaris', $eksemplar->kode_inventaris),
                    'nomor_panggil' => $request->input('nomor_panggil', $eksemplar->nomor_panggil),
                    'lokasi_rak' => $request->input('lokasi_rak', $eksemplar->lokasi_rak),
                ]);

                return redirect()->route('admin.books.show', $book->id_buku)
                    ->with('success', "Buku berhasil dikembalikan oleh {$activeLoan->pengguna->nama_lengkap}. Status eksemplar \"{$eksemplar->kode_inventaris}\" kembali Tersedia di rak.");
            }
        }

        // Perubahan status umum selain meminjam baru
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

        try {
            DB::transaction(function () use ($eksemplar): void {
                // Bersihkan riwayat peminjaman terkait eksemplar ini agar tidak melanggar foreign key restrict
                PeminjamanBuku::where('id_eksemplar', $eksemplar->id_eksemplar)->delete();
                $eksemplar->delete();
            });

            return redirect()->route('admin.books.show', $book->id_buku)
                ->with('success', "Eksemplar \"{$kode}\" berhasil dihapus dari inventaris.");
        } catch (\Throwable $e) {
            return redirect()->route('admin.books.show', $book->id_buku)
                ->with('error', "Gagal menghapus eksemplar \"{$kode}\": {$e->getMessage()}");
        }
    }

    /**
     * Sinkronisasi pengarang utama buku (baik diketik manual via keyboard maupun dipilih).
     */
    protected function syncBookAuthor(Buku $book, Request $request): void
    {
        $authorName = trim((string) ($request->input('nama_penulis') ?: $request->input('nama_penulis_manual', '')));
        $authorId = $request->input('id_penulis');
        $targetAuthorId = null;

        if ($authorName !== '') {
            $author = Penulis::whereRaw('LOWER(nama_penulis) = ?', [strtolower($authorName)])->first();
            if (! $author) {
                $author = Penulis::create([
                    'nama_penulis' => $authorName,
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
     * Cari atau daftarkan kategori buku baru berdasarkan nama ketikan keyboard atau ID.
     */
    protected function resolveKategoriId(Request $request): string
    {
        $nama = trim((string) $request->input('nama_kategori', ''));
        if ($nama !== '') {
            $cat = KategoriBuku::whereRaw('LOWER(nama_kategori) = ?', [strtolower($nama)])->first();
            if (! $cat) {
                $cat = KategoriBuku::create([
                    'nama_kategori' => $nama,
                    'deskripsi_kategori' => 'Kategori ditambahkan otomatis melalui keyboard',
                ]);
            }

            return $cat->id_kategori_buku;
        }

        $id = trim((string) $request->input('id_kategori_buku', ''));
        if ($id !== '' && KategoriBuku::where('id_kategori_buku', $id)->exists()) {
            return $id;
        }

        return KategoriBuku::value('id_kategori_buku') ?? '';
    }

    /**
     * Cari atau daftarkan jenis/format buku baru berdasarkan nama ketikan keyboard atau ID.
     */
    protected function resolveJenisId(Request $request): string
    {
        $nama = trim((string) $request->input('nama_jenis_buku', ''));
        if ($nama !== '') {
            $jenis = JenisBuku::whereRaw('LOWER(nama_jenis_buku) = ?', [strtolower($nama)])->first();
            if (! $jenis) {
                $jenis = JenisBuku::create([
                    'nama_jenis_buku' => $nama,
                    'deskripsi_jenis' => 'Format koleksi ditambahkan otomatis melalui keyboard',
                ]);
            }

            return $jenis->id_jenis_buku;
        }

        $id = trim((string) $request->input('id_jenis_buku', ''));
        if ($id !== '' && JenisBuku::where('id_jenis_buku', $id)->exists()) {
            return $id;
        }

        return JenisBuku::value('id_jenis_buku') ?? '';
    }

    /**
     * Cari atau daftarkan penerbit buku baru berdasarkan nama ketikan keyboard atau ID.
     */
    protected function resolvePenerbitId(Request $request): ?string
    {
        $nama = trim((string) $request->input('nama_penerbit', ''));
        if ($nama !== '') {
            $pub = Penerbit::whereRaw('LOWER(nama_penerbit) = ?', [strtolower($nama)])->first();
            if (! $pub) {
                $pub = Penerbit::create([
                    'nama_penerbit' => $nama,
                    'deskripsi_penerbit' => 'Penerbit ditambahkan otomatis melalui keyboard',
                ]);
            }

            return $pub->id_penerbit;
        }

        $id = trim((string) $request->input('id_penerbit', ''));
        if ($id !== '' && Penerbit::where('id_penerbit', $id)->exists()) {
            return $id;
        }

        return null;
    }

    /**
     * Hapus koleksi buku dari database beserta seluruh eksemplar dan riwayat peminjamannya.
     */
    public function destroy(string $id): RedirectResponse
    {
        $book = Buku::with('eksemplar')->findOrFail($id);
        $judul = $book->judul_buku;

        try {
            DB::transaction(function () use ($book): void {
                $eksemplarIds = $book->eksemplar->pluck('id_eksemplar')->filter()->values()->all();

                // 1. Bersihkan seluruh transaksi/riwayat peminjaman terkait buku atau eksemplarnya
                PeminjamanBuku::where('id_buku', $book->id_buku)
                    ->when(! empty($eksemplarIds), function ($query) use ($eksemplarIds): void {
                        $query->orWhereIn('id_eksemplar', $eksemplarIds);
                    })
                    ->delete();

                // 2. Hapus log kunjungan buku jika ada
                $book->kunjungan()->delete();

                // 3. Lepas relasi pivot penulis dan subjek
                BukuPenulis::where('id_buku', $book->id_buku)->delete();
                $book->subjek()->detach();

                // 4. Hapus seluruh eksemplar fisik buku
                $book->eksemplar()->delete();

                // 5. Hapus file sampul jika diupload kustom lokal
                if ($book->sampul_buku) {
                    $this->mediaUploadService->deleteImage($book->sampul_buku);
                    if (str_starts_with($book->sampul_buku, 'images/buku/uploads/') && file_exists(public_path($book->sampul_buku))) {
                        @unlink(public_path($book->sampul_buku));
                    }
                }

                // 6. Hapus katalog buku
                $book->delete();
            });

            return redirect()->route('admin.books.index')
                ->with('success', "Buku \"{$judul}\" beserta seluruh eksemplar dan riwayat sirkulasinya berhasil dihapus.");
        } catch (\Throwable $e) {
            return redirect()->route('admin.books.index')
                ->with('error', "Gagal menghapus buku \"{$judul}\": {$e->getMessage()}");
        }
    }
}
