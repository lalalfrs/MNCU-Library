<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\EksemplarBuku;
use App\Models\JenisBuku;
use App\Models\KunjunganBuku;
use App\Models\Prodi;
use App\Models\ProfilPustakawan;
use App\Models\SubjekBuku;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class LibraryController extends Controller
{
    /**
     * Tampilkan halaman Chatbot / QnA Bantuan Pemustaka atau proses pesan chat.
     */
    public function chat(Request $request): View|JsonResponse
    {
        if ($request->isMethod('post') || $request->wantsJson()) {
            $message = trim((string) $request->input('message', ''));

            return response()->json([
                'reply' => $this->resolveChatbotReply($message),
                'time' => now()->format('H:i'),
            ]);
        }

        $quickPrompts = [
            [
                'question' => 'Jam operasional perpustakaan?',
                'category' => 'Layanan',
                'icon' => 'fa-clock',
            ],
            [
                'question' => 'Batas durasi & perpanjangan buku?',
                'category' => 'Sirkulasi',
                'icon' => 'fa-book-bookmark',
            ],
            [
                'question' => 'Berapa denda keterlambatan buku?',
                'category' => 'Sirkulasi',
                'icon' => 'fa-money-bill-wave',
            ],
            [
                'question' => 'Bagaimana cara cek Turnitin untuk skripsi?',
                'category' => 'Turnitin & Riset',
                'icon' => 'fa-magnifying-glass-chart',
            ],
            [
                'question' => 'Bagaimana akses e-journal dari luar kampus?',
                'category' => 'Digital',
                'icon' => 'fa-globe',
            ],
            [
                'question' => 'Cara reservasi buku yang sedang dipinjam?',
                'category' => 'Sirkulasi',
                'icon' => 'fa-calendar-check',
            ],
            [
                'question' => 'Syarat bebas pustaka untuk wisuda?',
                'category' => 'Turnitin & Riset',
                'icon' => 'fa-graduation-cap',
            ],
            [
                'question' => 'Bagaimana prosedur jika buku hilang atau rusak?',
                'category' => 'Sirkulasi',
                'icon' => 'fa-triangle-exclamation',
            ],
            [
                'question' => 'Cara peminjaman ruang diskusi kelompok?',
                'category' => 'Fasilitas',
                'icon' => 'fa-users',
            ],
            [
                'question' => 'Kontak & lokasi Perpustakaan MNCU?',
                'category' => 'Layanan',
                'icon' => 'fa-location-dot',
            ],
        ];

        return view('chat', compact('quickPrompts'));
    }

    /**
     * Tampilkan katalog OPAC dengan perlindungan otentikasi, alur flowchart fitur,
     * pencarian kriteria, penyortiran kriteria, box filter lanjutan, dan ketersediaan stok eksemplar.
     */
    public function opac(Request $request): View|RedirectResponse
    {
        // 1. Proteksi Otentikasi: Tamu/guest diarahkan ke halaman login atau pendaftaran terlebih dahulu
        if (! Auth::check()) {
            return redirect()->guest(route('login'))->with(
                'info',
                'Silakan masuk atau daftar akun terlebih dahulu untuk mengakses katalog OPAC Perpustakaan MNC University.'
            );
        }

        $category = (string) $request->query('kategori', 'Semua');
        $prodi = (string) $request->query('prodi', 'Semua');
        $jenisFilter = (string) $request->query('jenis', 'Semua');
        $selectedBookId = $request->query('buku');

        // 2. Alur Flowchart Fitur OPAC (Kategori Subjek, Koleksi Populer, Koleksi Baru & Diperbarui)
        $fitur = (string) $request->query('fitur', 'semua');
        $subjekId = trim((string) $request->query('subjek_id', ''));

        // 3. Point 1: Search Bar Sederhana dengan Kriteria Tertentu
        $query = trim((string) $request->query('q', ''));
        $kriteria = (string) $request->query('kriteria', 'semua');

        // 4. Point 2: Sort By Kriteria Tertentu
        $sort = (string) $request->query('sort', '');
        if ($sort === '') {
            if ($fitur === 'populer') {
                $sort = 'populer';
            } elseif ($fitur === 'terbaru') {
                $sort = 'terbaru';
            } else {
                $sort = 'terbaru';
            }
        }

        // 5. Box Filter Pencarian Lanjutan (Sesuai Referensi Gambar Desain)
        $filterPengarang = trim((string) $request->query('filter_pengarang', ''));
        $filterIsbn = trim((string) $request->query('filter_isbn', ''));
        $filterDdc = trim((string) $request->query('filter_ddc', ''));
        $filterNomorInduk = trim((string) $request->query('filter_nomor_induk', ''));
        $filterTahun = (string) $request->query('filter_tahun', 'Semua');
        $filterLokasi = (string) $request->query('filter_lokasi', 'Semua');
        $filterJenis = (string) $request->query('filter_jenis', 'Semua');
        $filterProdi = (string) $request->query('filter_prodi', 'Semua');
        $statusFilter = (string) $request->query('status', 'Semua');

        // Hitung berapa banyak filter dari Box Filter yang sedang aktif
        $activeFilterCount = 0;
        if (! empty($filterPengarang)) {
            $activeFilterCount++;
        }
        if (! empty($filterIsbn)) {
            $activeFilterCount++;
        }
        if (! empty($filterDdc)) {
            $activeFilterCount++;
        }
        if (! empty($filterNomorInduk)) {
            $activeFilterCount++;
        }
        if ($filterTahun !== 'Semua' && ! empty($filterTahun)) {
            $activeFilterCount++;
        }
        if ($filterLokasi !== 'Semua' && ! empty($filterLokasi)) {
            $activeFilterCount++;
        }
        if ($filterJenis !== 'Semua' && ! empty($filterJenis)) {
            $activeFilterCount++;
        }
        if ($filterProdi !== 'Semua' && ! empty($filterProdi)) {
            $activeFilterCount++;
        }
        if ($statusFilter !== 'Semua' && ! empty($statusFilter)) {
            $activeFilterCount++;
        }

        // Ambil data buku dengan relasi lengkap
        $dbBooks = Buku::with([
            'penulis',
            'jenisBuku',
            'kategoriBuku',
            'penerbit',
            'eksemplar',
            'subjek',
            'kunjungan',
        ])->get();

        if ($dbBooks->isNotEmpty()) {
            $catalogItems = $dbBooks->map(function (Buku $buku): array {
                $authorName = $buku->penulis->pluck('nama_penulis')->join(', ') ?: 'Pustakawan MNC University';
                $copiesCount = $buku->eksemplar->count();
                $availableCount = $buku->eksemplar->where('status', 'Tersedia')->count();
                $borrowedCount = $buku->eksemplar->where('status', 'Dipinjam')->count();
                $otherCount = $buku->eksemplar->whereNotIn('status', ['Tersedia', 'Dipinjam'])->count();
                $firstCopy = $buku->eksemplar->first();
                $hasAvailable = $availableCount > 0;

                if ($copiesCount === 0) {
                    $statusLabel = 'Koleksi Digital / Tanpa Eksemplar Fisik';
                } elseif ($hasAvailable) {
                    $statusLabel = "Tersedia ({$availableCount} dari {$copiesCount} eksemplar)";
                } else {
                    $statusLabel = "Semua Dipinjam ({$copiesCount} eksemplar)";
                }

                $kategoriName = $buku->kategoriBuku?->nama_kategori ?? 'Umum';
                $prodiName = match ($kategoriName) {
                    'Teknologi Informasi', 'Teknologi & Ilmu Komputer' => 'Informatika',
                    'Ekonomi & Bisnis', 'Bisnis, Manajemen & Ekonomi' => 'Manajemen',
                    'Desain & Seni Kreatif', 'Desain & Seni Visual' => 'Desain Komunikasi Visual',
                    'Ilmu Komunikasi' => 'Sains Komunikasi',
                    'Sains Data & Matematika' => 'Sistem Informasi',
                    default => 'Umum',
                };

                return [
                    'id' => $buku->id_buku,
                    'uuid' => $buku->uuid,
                    'title' => $buku->judul_buku,
                    'author' => $authorName,
                    'publisher' => $buku->penerbit?->nama_penerbit ?? 'Penerbit MNC University',
                    'publisher_city' => $buku->penerbit?->kota ?? 'Jakarta',
                    'year' => $buku->tahun_terbit ?? 2024,
                    'category' => $buku->jenisBuku?->nama_jenis_buku ?? 'Buku Teks',
                    'category_name' => $kategoriName,
                    'format' => $buku->jenisBuku?->nama_jenis_buku ?? 'Buku Cetak',
                    'prodi' => $prodiName,
                    'isbn' => $buku->isbn ?? '-',
                    'edition' => $buku->edisi ?? 'Edisi Pertama',
                    'pages' => $buku->jumlah_halaman ? "{$buku->jumlah_halaman} hlm" : '-',
                    'cover_url' => $buku->sampul_buku,
                    'description' => $buku->deskripsi ?: 'Buku referensi akademik koleksi Perpustakaan MNC University.',
                    'subjects' => $buku->subjek->pluck('nama_subjek')->all(),
                    'subject_ids' => $buku->subjek->pluck('id_subjek_buku')->all(),
                    'call_number' => $firstCopy?->nomor_panggil ?? '000.00 MNC b',
                    'location' => $firstCopy?->lokasi_rak ?? 'Lantai 2, Rak Koleksi',
                    'copies' => $copiesCount > 0 ? "{$copiesCount} eksemplar" : '0 eksemplar',
                    'copies_total' => $copiesCount,
                    'copies_available' => $availableCount,
                    'copies_borrowed' => $borrowedCount,
                    'copies_other' => $otherCount,
                    'status' => $hasAvailable ? 'Tersedia' : ($copiesCount > 0 ? 'Dipinjam' : 'Koleksi Digital'),
                    'status_label' => $statusLabel,
                    'visits' => $buku->kunjungan?->jumlah_kunjungan ?? 0,
                    'created_at' => $buku->created_at ? $buku->created_at->toISOString() : '',
                    'updated_at' => $buku->updated_at ? $buku->updated_at->toISOString() : '',
                    'eksemplar_list' => $buku->eksemplar->map(fn ($ek, $idx) => [
                        'no' => $idx + 1,
                        'id_eksemplar' => $ek->id_eksemplar,
                        'kode_inventaris' => $ek->kode_inventaris,
                        'nomor_panggil' => $ek->nomor_panggil ?? '-',
                        'lokasi_rak' => $ek->lokasi_rak ?? 'Lantai 2, Rak Koleksi',
                        'status' => $ek->status ?? 'Tersedia',
                    ])->values()->all(),
                ];
            })->all();
        } else {
            $catalogItems = [];
        }

        // Terapkan Logika Penyaringan (Filtering)
        $filteredItems = array_filter($catalogItems, function (array $item) use (
            $query,
            $kriteria,
            $fitur,
            $subjekId,
            $filterPengarang,
            $filterIsbn,
            $filterDdc,
            $filterNomorInduk,
            $filterTahun,
            $filterLokasi,
            $filterJenis,
            $filterProdi,
            $statusFilter,
            $category

        ): bool {
            // A. Search Bar Sederhana dengan Kriteria Tertentu
            $matchesQuery = true;
            if (! empty($query)) {
                $subjectsText = implode(' ', $item['subjects'] ?? []);
                $matchesQuery = match ($kriteria) {
                    'judul' => stripos($item['title'], $query) !== false,
                    'pengarang' => stripos($item['author'], $query) !== false,
                    'penerbit' => stripos($item['publisher'], $query) !== false || stripos($item['publisher_city'] ?? '', $query) !== false,
                    'subjek' => stripos($subjectsText, $query) !== false || stripos($item['category_name'] ?? '', $query) !== false,
                    'nomor_panggil', 'ddc' => (
                        stripos($item['call_number'] ?? '', $query) !== false ||
                        collect($item['eksemplar_list'])->contains(fn ($ek) => stripos($ek['nomor_panggil'] ?? '', $query) !== false)
                    ),
                    'isbn' => stripos($item['isbn'] ?? '', $query) !== false,
                    default => (
                        stripos($item['title'], $query) !== false ||
                        stripos($item['author'], $query) !== false ||
                        stripos($item['call_number'] ?? '', $query) !== false ||
                        stripos($item['isbn'] ?? '', $query) !== false ||
                        stripos($item['publisher'] ?? '', $query) !== false ||
                        stripos($item['description'] ?? '', $query) !== false ||
                        stripos($subjectsText, $query) !== false ||
                        collect($item['eksemplar_list'])->contains(fn ($ek) => stripos($ek['nomor_panggil'] ?? '', $query) !== false)
                    ),
                };
            }

            $matchesCategory = $category === 'Semua' || $item['category'] === $category || ($item['category_name'] ?? '') === $category;

            // B. Filter Kategori Subjek (Flowchart Cabang 1)
            $matchesSubjek = true;
            if ($fitur === 'subjek' && ! empty($subjekId) && $subjekId !== 'Semua') {
                $matchesSubjek = in_array($subjekId, $item['subject_ids'] ?? [], true) ||
                    collect($item['subjects'] ?? [])->contains(fn ($s) => strcasecmp($s, $subjekId) === 0);
            }

            // C. Box Filter Pencarian Lanjutan (Referensi Desain Gambar 2)
            $matchesPengarang = empty($filterPengarang) || stripos($item['author'], $filterPengarang) !== false;
            $matchesIsbn = empty($filterIsbn) || stripos($item['isbn'] ?? '', $filterIsbn) !== false;
            $matchesDdc = empty($filterDdc) || (
                stripos($item['call_number'] ?? '', $filterDdc) !== false ||
                collect($item['eksemplar_list'])->contains(fn ($ek) => stripos($ek['nomor_panggil'] ?? '', $filterDdc) !== false)
            );
            $matchesNomorInduk = empty($filterNomorInduk) || (
                collect($item['eksemplar_list'])->contains(fn ($ek) => stripos($ek['kode_inventaris'] ?? '', $filterNomorInduk) !== false)
            );
            $matchesTahun = empty($filterTahun) || $filterTahun === 'Semua' || (string) $item['year'] === (string) $filterTahun;
            $matchesLokasi = empty($filterLokasi) || $filterLokasi === 'Semua' || (
                stripos($item['location'] ?? '', $filterLokasi) !== false ||
                collect($item['eksemplar_list'])->contains(fn ($ek) => stripos($ek['lokasi_rak'] ?? '', $filterLokasi) !== false)
            );
            $matchesJenis = empty($filterJenis) || $filterJenis === 'Semua' || (
                $item['category'] === $filterJenis || ($item['format'] ?? '') === $filterJenis
            );
            $matchesProdi = empty($filterProdi) || $filterProdi === 'Semua' || (
                $item['prodi'] === $filterProdi
            );

            // D. Status Ketersediaan
            $matchesStatus = true;
            if ($statusFilter === 'Tersedia' || $statusFilter === 'Hanya Tersedia') {
                $matchesStatus = ($item['copies_available'] ?? 0) > 0;
            } elseif ($statusFilter === 'Dipinjam' || $statusFilter === 'Sedang Dipinjam') {
                $matchesStatus = ($item['copies_borrowed'] ?? 0) > 0 || ($item['copies_available'] ?? 0) === 0;
            }

            return $matchesQuery &&
                $matchesSubjek &&
                $matchesPengarang &&
                $matchesIsbn &&
                $matchesDdc &&
                $matchesNomorInduk &&
                $matchesTahun &&
                $matchesLokasi &&
                $matchesJenis &&
                $matchesProdi &&
                $matchesCategory &&
                $matchesStatus;
        });

        // Re-index array
        $filteredItems = array_values($filteredItems);

        // 6. Point 2: Penyortiran (Sorting) Koleksi
        usort($filteredItems, function (array $a, array $b) use ($sort): int {
            if ($sort === 'populer') {
                $comp = ($b['visits'] ?? 0) <=> ($a['visits'] ?? 0);
                if ($comp !== 0) {
                    return $comp;
                }

                return ($b['copies_borrowed'] ?? 0) <=> ($a['copies_borrowed'] ?? 0);
            }

            if ($sort === 'terbaru') {
                return strcmp((string) ($b['created_at'] ?? ''), (string) ($a['created_at'] ?? ''));
            }

            if ($sort === 'judul_asc') {
                return strcasecmp($a['title'] ?? '', $b['title'] ?? '');
            }

            if ($sort === 'judul_desc') {
                return strcasecmp($b['title'] ?? '', $a['title'] ?? '');
            }

            if ($sort === 'tahun_desc') {
                return ($b['year'] ?? 0) <=> ($a['year'] ?? 0);
            }

            if ($sort === 'tahun_asc') {
                return ($a['year'] ?? 0) <=> ($b['year'] ?? 0);
            }

            return 0;
        });

        // Hitung statistik inventaris
        $totalKoleksi = count($catalogItems);
        $totalEksemplarFisik = array_sum(array_column($catalogItems, 'copies_total'));
        $totalEksemplarTersedia = array_sum(array_column($catalogItems, 'copies_available'));

        // Data Pilihan Dropdown & Filter Lanjutan
        $subjekList = SubjekBuku::withCount('buku')->orderBy('nama_subjek')->get();
        $selectedSubjek = null;
        if (! empty($subjekId)) {
            $selectedSubjek = SubjekBuku::where('id_subjek_buku', $subjekId)->orWhere('nama_subjek', $subjekId)->first();
        }

        $tahunList = Buku::whereNotNull('tahun_terbit')->distinct()->orderByDesc('tahun_terbit')->pluck('tahun_terbit')->all();
        if (empty($tahunList)) {
            $tahunList = [2026, 2025, 2024, 2023, 2022, 2021, 2020];
        }

        $lokasiList = EksemplarBuku::whereNotNull('lokasi_rak')->distinct()->orderBy('lokasi_rak')->pluck('lokasi_rak')->all();
        if (empty($lokasiList)) {
            $lokasiList = ['Lantai 2, Rak Komputer 03', 'Lantai 2, Rak Komputer 04', 'Lantai 2, Rak Ekonomi 01', 'Lantai 2, Rak Desain 02', 'Lantai 1, Rak Umum 01', 'Lantai 1, Rak Sastra 02'];
        }

        $dbTypes = JenisBuku::orderBy('nama_jenis_buku')->pluck('nama_jenis_buku')->all();
        $types = $dbTypes ?: ['Buku Teks', 'Buku Referensi', 'Karya Ilmiah / Skripsi', 'Jurnal & Majalah Ilmiah', 'Buku Fiksi & Sastra'];

        $prodiList = Prodi::orderBy('nama_prodi')->get();

        $selectedBookItem = null;
        if (! empty($selectedBookId)) {
            $selectedBookItem = collect($catalogItems)->first(fn ($it) => (string) ($it['id'] ?? '') === (string) $selectedBookId);
        }

        return view('opac', [
            'items' => $filteredItems,
            'fitur' => $fitur,
            'subjekId' => $subjekId,
            'selectedSubjek' => $selectedSubjek,
            'subjekList' => $subjekList,
            'query' => $query,
            'kriteria' => $kriteria,
            'sort' => $sort,
            'filterPengarang' => $filterPengarang,
            'filterIsbn' => $filterIsbn,
            'filterDdc' => $filterDdc,
            'filterNomorInduk' => $filterNomorInduk,
            'filterTahun' => $filterTahun,
            'filterLokasi' => $filterLokasi,
            'filterJenis' => $filterJenis,
            'filterProdi' => $filterProdi,
            'category' => $category,
            'prodi' => $prodi,
            'jenisFilter' => $jenisFilter,
            'selectedBookId' => $selectedBookId,
            'selectedBookItem' => $selectedBookItem,
            'statusFilter' => $statusFilter,
            'activeFilterCount' => $activeFilterCount,
            'tahunList' => $tahunList,
            'lokasiList' => $lokasiList,
            'types' => $types,
            'prodiList' => $prodiList,
            'totalCount' => count($filteredItems),
            'totalKoleksi' => $totalKoleksi,
            'totalEksemplarFisik' => $totalEksemplarFisik,
            'totalEksemplarTersedia' => $totalEksemplarTersedia,
        ]);
    }

    /**
     * Catat kunjungan penelusuran buku saat user membuka rincian informasi buku.
     */
    public function recordKunjungan(Request $request, Buku $buku): JsonResponse
    {
        if (! Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $kunjungan = KunjunganBuku::firstOrCreate(
            ['id_buku' => $buku->id_buku],
            [
                'id_kunjungan_buku' => sprintf('MNCU%05d', KunjunganBuku::count() + 1),
                'uuid' => (string) Str::uuid(),
                'jumlah_kunjungan' => 0,
            ]
        );

        $kunjungan->increment('jumlah_kunjungan');

        return response()->json([
            'success' => true,
            'id_buku' => $buku->id_buku,
            'jumlah_kunjungan' => $kunjungan->fresh()->jumlah_kunjungan,
        ]);
    }

    /**
     * Tampilkan halaman Repository MNC University.
     */
    public function repository(Request $request): View
    {
        $query = trim((string) $request->query('q', ''));
        $tipe = (string) $request->query('tipe', 'Semua');

        $repoItems = [
            [
                'title' => 'Rancang Bangun Sistem Informasi Manajemen Perpustakaan Berbasis Web pada MNC University',
                'author' => 'Hilal Alfaris, Jovin, Azka',
                'prodi' => 'S1 Informatika',
                'year' => 2026,
                'type' => 'Tugas Akhir / Skripsi',
                'downloads' => 142,
            ],
            [
                'title' => 'Analisis Pengaruh Kampanye Digital Media MNC Group terhadap Minat Belajar Mahasiswa',
                'author' => 'Clarissa Putri',
                'prodi' => 'S1 Sains Komunikasi',
                'year' => 2025,
                'type' => 'Tugas Akhir / Skripsi',
                'downloads' => 98,
            ],
            [
                'title' => 'Implementasi UI/UX Desain Aplikasi Mobile Banking dengan Pendekatan Design Thinking',
                'author' => 'Reza Pratama',
                'prodi' => 'S1 Desain Komunikasi Visual',
                'year' => 2025,
                'type' => 'Tugas Akhir / Skripsi',
                'downloads' => 210,
            ],
            [
                'title' => 'Evaluasi Kinerja Keuangan Perusahaan Media Menggunakan Metode Economic Value Added (EVA)',
                'author' => 'Anita Rahmawati',
                'prodi' => 'S1 Akuntansi',
                'year' => 2024,
                'type' => 'Tugas Akhir / Skripsi',
                'downloads' => 176,
            ],
            [
                'title' => 'Pengembangan Algoritma Klasifikasi Teks Berita Menggunakan Transformer Model',
                'author' => 'Dr. Rian Hidayat, M.Kom. & Tim Dosen',
                'prodi' => 'S1 Informatika',
                'year' => 2025,
                'type' => 'Publikasi Dosen',
                'downloads' => 315,
            ],
        ];

        $filteredRepo = array_filter($repoItems, function (array $item) use ($query, $tipe): bool {
            $matchesQuery = empty($query) || (
                stripos($item['title'], $query) !== false ||
                stripos($item['author'], $query) !== false ||
                stripos($item['prodi'], $query) !== false
            );
            $matchesType = $tipe === 'Semua' || $item['type'] === $tipe;

            return $matchesQuery && $matchesType;
        });

        $types = ['Semua', 'Tugas Akhir / Skripsi', 'Publikasi Dosen'];

        return view('repository', [
            'items' => array_values($filteredRepo),
            'query' => $query,
            'tipe' => $tipe,
            'types' => $types,
            'totalCount' => count($filteredRepo),
        ]);
    }

    /**
     * Tampilkan portal E-Journal & Database Ilmiah.
     */
    public function ejournal(): View
    {
        $databases = [
            [
                'name' => 'DOAJ (Directory of Open Access Journals)',
                'description' => 'Akses ribuan jurnal ilmiah berkualitas tinggi dan terindeks secara global tanpa biaya.',
                'coverage' => 'Multidisiplin ilmu, sains, teknologi, dan sosial humaniora',
                'url' => 'https://doaj.org',
                'badge' => 'Open Access',
            ],
            [
                'name' => 'Garuda (Garba Rujukan Digital Kemdikbud)',
                'description' => 'Portal rujukan jurnal dan publikasi ilmiah nasional terakreditasi di Indonesia.',
                'coverage' => 'Semua jurnal ilmiah nasional terakreditasi SINTA',
                'url' => 'https://garuda.kemdikbud.go.id',
                'badge' => 'Nasional',
            ],
            [
                'name' => 'e-Resources Perpustakaan Nasional RI',
                'description' => 'Layanan database jurnal internasional langganan Perpusnas untuk seluruh warga negara Indonesia.',
                'coverage' => 'ProQuest, EBSCO, Taylor & Francis, SpringerLink',
                'url' => 'https://e-resources.perpusnas.go.id',
                'badge' => 'Langganan Nasional',
            ],
            [
                'name' => 'IEEE Xplore Digital Library',
                'description' => 'Publikasi terkemuka di bidang teknik elektro, ilmu komputer, dan elektronika.',
                'coverage' => 'Computer Science, Artificial Intelligence, Telecommunications',
                'url' => 'https://ieeexplore.ieee.org',
                'badge' => 'Sains & Teknologi',
            ],
            [
                'name' => 'ScienceDirect / Elsevier',
                'description' => 'Koleksi jurnal penelitian sains, kesehatan, dan manajemen bisnis internasional.',
                'coverage' => 'Jurnal peer-reviewed terkemuka',
                'url' => 'https://www.sciencedirect.com',
                'badge' => 'Internasional',
            ],
        ];

        return view('ejournal', compact('databases'));
    }

    /**
     * Tampilkan halaman pemilihan portal OJS per Program Studi.
     */
    public function ojs(): View
    {
        $journals = [
            [
                'prodi' => 'S1 Informatika',
                'name' => 'MNC Journal of Computer Science & Intelligent Systems (MJCSIS)',
                'issn' => 'e-ISSN: 2985-112X',
                'frequency' => '2 kali setahun (Juni & Desember)',
                'focus' => 'Machine Learning, Web Technology, Computer Vision, Cloud Computing',
                'url' => 'https://mncu.ac.id',
            ],
            [
                'prodi' => 'S1 Sistem Informasi',
                'name' => 'Journal of Information Systems & Enterprise Architecture (JISEA)',
                'issn' => 'e-ISSN: 2985-2234',
                'frequency' => '2 kali setahun (Mei & November)',
                'focus' => 'Enterprise Architecture, Big Data, IT Governance, E-Commerce',
                'url' => 'https://mncu.ac.id',
            ],
            [
                'prodi' => 'S1 Desain Komunikasi Visual',
                'name' => 'Creative & Visual Art Studies (CREAVIS)',
                'issn' => 'e-ISSN: 2985-3345',
                'frequency' => '2 kali setahun (April & Oktober)',
                'focus' => 'Animasi, Branding, Desain Grafis, UI/UX, Sinematografi',
                'url' => 'https://mncu.ac.id',
            ],
            [
                'prodi' => 'S1 Manajemen',
                'name' => 'MNC Business & Management Review (MBMR)',
                'issn' => 'e-ISSN: 2985-4456',
                'frequency' => '2 kali setahun (Maret & September)',
                'focus' => 'Pemasaran Digital, Kewirausahaan, Manajemen Strategis, SDM',
                'url' => 'https://mncu.ac.id',
            ],
            [
                'prodi' => 'S1 Akuntansi',
                'name' => 'Journal of Accounting, Taxation & Auditing (JATA)',
                'issn' => 'e-ISSN: 2985-5567',
                'frequency' => '2 kali setahun (Juni & Desember)',
                'focus' => 'Akuntansi Keuangan, Perpajakan, Audit, Sistem Informasi Akuntansi',
                'url' => 'https://mncu.ac.id',
            ],
            [
                'prodi' => 'S1 Sains Komunikasi',
                'name' => 'MNC Communication & Media Studies (MCMS)',
                'issn' => 'e-ISSN: 2985-6678',
                'frequency' => '2 kali setahun (Januari & Juli)',
                'focus' => 'Jurnalisme Penyiaran, Komunikasi Korporat, Media Digital, Humas',
                'url' => 'https://mncu.ac.id',
            ],
        ];

        return view('ojs', compact('journals'));
    }

    /**
     * Tampilkan koleksi E-Book MNC University.
     */
    public function ebook(Request $request): View
    {
        $query = trim((string) $request->query('q', ''));
        $kategori = (string) $request->query('kategori', 'Semua');

        $books = [
            [
                'title' => 'Pengantar Rekayasa Perangkat Lunak Modern',
                'author' => 'Ian Sommerville & Tim Adaptasi MNCU',
                'category' => 'Informatika',
                'year' => 2024,
                'pages' => '420 halaman',
                'format' => 'PDF Digital',
                'cover_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBFGNT3XheiIYPlqxxSefMAz_14hOzFOGeEvZmIwpfN4tZ9H6dkBFmKMPqAwTEYpm97YTdnoV9FJ7ZVP4hpUfwH8zqJh6C2NXm6x08q0H-3ur5-NQO8BzexbmgTo_aABfX3ijppIgO_GoWZd_HCZszRMlGCyTtXw8h704MJHq6OX0r4djSjSj0lBmgwQBD9xhct1bdW0_95kJOv9h9YF3dkME8kjK0dxTSGiZoeokYQIdb_xMV0lNls',
                'description' => 'Pedoman lengkap rekayasa perangkat lunak, arsitektur sistem modular, dan metodologi pengembangan agile modern.',
            ],
            [
                'title' => 'Prinsip Dasar Jurnalisme Televisi & Media Baru',
                'author' => 'Ahmad Santoso, M.I.Kom.',
                'category' => 'Komunikasi',
                'year' => 2023,
                'pages' => '280 halaman',
                'format' => 'PDF Digital',
                'cover_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBithebMUdUulLyhN5WrNVtvuyJ9pU7uDrz_Sdu0qeZvV4eFB-SRng3nMsx04M82pJz-rCXuAvwRc5lKaKIDjqHxWBOVWxbCk38sEo9Y6BSp6VBOXP7FtO26yIxzW1mqNwNYFQTEYPgDey2lQ7Vp4zCKmU-IgUBIm5Ctlh0j61D1_83bhZl3wS0TFiqa8pqhBJPj5HFG_i3BTQEsUyE7DJtWSa1oHyV0O4kMPtX8TrcINU2AMf98WLv',
                'description' => 'Kaidah jurnalistik penyiaran, konvergensi media digital, etika peliputan berita televisi, dan produksi konten multimedia.',
            ],
            [
                'title' => 'Dasar-Dasar Desain Grafis & Komposisi Visual',
                'author' => 'Maya Anggraini, M.Sn.',
                'category' => 'Desain',
                'year' => 2024,
                'pages' => '310 halaman',
                'format' => 'PDF Digital',
                'cover_url' => 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?auto=format&fit=crop&q=80&w=600',
                'description' => 'Eksplorasi teori warna, hierarki tipografi, grid system, dan perancangan identitas visual untuk media digital dan cetak.',
            ],
            [
                'title' => 'Manajemen Keuangan Perusahaan: Teori & Kasus',
                'author' => 'Drs. Herman Wijaya, M.M.',
                'category' => 'Bisnis',
                'year' => 2023,
                'pages' => '390 halaman',
                'format' => 'PDF Digital',
                'cover_url' => 'https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?auto=format&fit=crop&q=80&w=600',
                'description' => 'Analisis rasio keuangan, manajemen modal kerja, penganggaran investasi modal, dan studi kasus korporasi Indonesia.',
            ],
            [
                'title' => 'Sistem Basis Data Relasional & NoSQL',
                'author' => 'Budi Prasetyo, M.Kom.',
                'category' => 'Informatika',
                'year' => 2024,
                'pages' => '350 halaman',
                'format' => 'PDF Digital',
                'cover_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAxr25631MOraMM8EMu_28k2aE-tBREKbSeKlIUQ_eyQCKe2DTdZ-W2ArJ6C88Cb0hpxz83NVHq8F_ZC2yTxH0xp_5R3B7UH_Eoo5pH33k4zOyYUlNt-pmtwnMiI7sA7GJD8amOOvcnSV7KjewEx-wxvjnMFlnu4BabdZvksKY0rJ4E9JmwDjH7VgDENOUscnRpxVPaIsmnTjWsEGlhsX29kO9TErb2ZFdS4dtOW3wtfq7nhkc4VL-9',
                'description' => 'Konsep perancangan skema basis data relasional, normalisasi data, optimasi kueri SQL, serta arsitektur database NoSQL.',
            ],
            [
                'title' => 'Etika Profesi & Hukum Media di Era Digital',
                'author' => 'Prof. Dr. Irwan Susanto, S.H., M.H.',
                'category' => 'Hukum & Sosial',
                'year' => 2023,
                'pages' => '240 halaman',
                'format' => 'PDF Digital',
                'cover_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAylDzhS69C6c_wFZDHdgwm1Ypy31UFEHMxhTitS9DFZODJtje34dJmVbrMw7HQ53upC5KYfWXtzGBmv3uIQ7Kz51DKUA6sd6VnZ3LRN3e5C0gE44u-iMNjF5Z60NzFgg_9aMqZpZdpiz3fenEhI02YN71v2W-7CRI3lOKWL08iA_Zs2mM41ecOs7oKRwq9ftnFZF7yDF2zpidL9TtiRcioUFMJqQVs257UHMoQr1fe5nkgLziOjVCd',
                'description' => 'Kajian komprehensif undang-undang pers, perlindungan data privasi, regulasi ITE, dan tanggung jawab etis media penyiaran.',
            ],
        ];

        $filteredBooks = array_filter($books, function (array $book) use ($query, $kategori): bool {
            $matchesQuery = empty($query) || (
                stripos($book['title'], $query) !== false ||
                stripos($book['author'], $query) !== false
            );
            $matchesCat = $kategori === 'Semua' || $book['category'] === $kategori;

            return $matchesQuery && $matchesCat;
        });

        $categories = ['Semua', 'Informatika', 'Komunikasi', 'Desain', 'Bisnis', 'Hukum & Sosial'];

        return view('ebook', [
            'books' => array_values($filteredBooks),
            'query' => $query,
            'kategori' => $kategori,
            'categories' => $categories,
            'totalCount' => count($filteredBooks),
        ]);
    }

    /**
     * Resolusi respon chatbot cerdas untuk pemustaka MNC University.
     */
    private function resolveChatbotReply(string $input): string
    {
        $text = strtolower(trim($input));

        if (empty($text)) {
            return "Halo! Ada yang bisa saya bantu terkait layanan Perpustakaan MNC University?\n\nAnda dapat menanyakan informasi seputar jam operasional, peminjaman dan perpanjangan buku, denda, cek plagiarisme Turnitin, akses e-journal luar kampus, repository skripsi, hingga pengurusan bebas pustaka wisuda.";
        }

        // 1. Perpanjangan Peminjaman Buku
        if (str_contains($text, 'perpanjang') || str_contains($text, 'renew') || str_contains($text, 'tambah waktu') || str_contains($text, 'tambah hari')) {
            return "Ketentuan & Prosedur Perpanjangan Peminjaman Buku:\n\n"
                ."• Perpanjangan diperbolehkan 1 kali selama 7 hari kalender tambahan.\n"
                ."• Pengajuan perpanjangan dapat dilakukan langsung di loket sirkulasi perpustakaan atau melalui akun pemustaka sebelum tanggal jatuh tempo.\n"
                ."• Syarat: Buku tidak dalam status terlambat/denda dan tidak sedang direservasi (dipesan) oleh pemustaka lain.\n"
                .'• Jika buku telah melewati tanggal jatuh tempo, pemustaka wajib mengembalikan buku terlebih dahulu dan menyelesaikan denda administrasi sebelum dapat meminjam kembali.';
        }

        // 2. Buku Hilang atau Rusak
        if (str_contains($text, 'hilang') || str_contains($text, 'rusak') || str_contains($text, 'ganti buku') || str_contains($text, 'kehilangan')) {
            return "Prosedur Penggantian Buku Hilang atau Rusak:\n\n"
                ."1. Segera lapor ke loket sirkulasi agar status peminjaman dibekukan dan denda harian tidak terus berjalan.\n"
                ."2. Mengganti dengan buku fisik yang identik (judul, pengarang, dan ISBN sama) atau edisi revisi terbaru yang disetujui pustakawan.\n"
                ."3. Jika buku sudah tidak beredar di pasaran (out of print), pemustaka mengganti dengan buku subjek serumpun yang nilainya setara berdasarkan rekomendasi pustakawan.\n"
                .'4. Membayar biaya administrasi pengolahan koleksi baru (label & barcode) sebesar Rp10.000 per buku.';
        }

        // 3. Denda Keterlambatan
        if (str_contains($text, 'denda') || str_contains($text, 'terlambat') || str_contains($text, 'telat')) {
            return "Ketentuan Denda Keterlambatan Pengembalian Buku:\n\n"
                ."• Tarif denda: Rp1.000 per buku per hari keterlambatan (termasuk hari Sabtu dan Minggu selama masa pinjam terlewati).\n"
                ."• Pembayaran denda dapat dilakukan secara tunai maupun nontunai (QRIS) di meja sirkulasi perpustakaan.\n"
                .'• Pemustaka yang masih memiliki tanggungan denda belum dapat meminjam koleksi baru atau memproses surat bebas pustaka.';
        }

        // 4. Reservasi / Booking Buku
        if (str_contains($text, 'reservasi') || str_contains($text, 'booking') || str_contains($text, 'pesan buku') || str_contains($text, 'antre buku')) {
            return "Layanan Reservasi Buku Fisik:\n\n"
                ."• Anda dapat memesan buku yang status eksemplarnya sedang dipinjam oleh pemustaka lain.\n"
                ."• Reservasi dapat diajukan melalui petugas sirkulasi dengan menyebutkan Judul Buku dan Kode Call Number.\n"
                ."• Saat buku dikembalikan oleh peminjam sebelumnya, sistem akan mengirim notifikasi ketersediaan ke kontak/email Anda.\n"
                .'• Buku reservasi disimpan di meja layanan sirkulasi selama 2 x 24 jam. Jika tidak diambil, buku akan dialihkan ke pemustaka berikutnya.';
        }

        // 5. Uji Kemiripan / Cek Plagiarisme Turnitin
        if (str_contains($text, 'turnitin') || str_contains($text, 'plagiat') || str_contains($text, 'plagiarisme') || str_contains($text, 'kemiripan') || str_contains($text, 'similarity')) {
            return "Layanan Uji Kemiripan Naskah (Turnitin) MNC University:\n\n"
                ."• Diperuntukkan bagi mahasiswa yang sedang menyusun Tugas Akhir/Skripsi/Tesis serta publikasi karya ilmiah sivitas akademika.\n"
                ."• Batas toleransi kemiripan (similarity index): Umumnya maksimal 20% - 25% (mengacu pada pedoman akademik fakultas/prodi masing-masing).\n"
                ."• Format naskah: Dokumen naskah (.docx atau .pdf) tanpa halaman cover, kata pengantar, dan daftar pustaka.\n"
                ."• Cara pengajuan: Kirim naskah ke email perpustakaan@mncuniversity.ac.id dengan subjek 'Uji Turnitin - [NIM] - [Nama]' atau datang ke ruang konsultasi pustakawan.\n"
                .'• Hasil uji berupa laporan digital resmi (PDF) akan dikirimkan dalam 1 x 24 jam hari kerja.';
        }

        // 6. Bebas Pustaka untuk Wisuda / Yudisium
        if (str_contains($text, 'bebas pustaka') || str_contains($text, 'wisuda') || str_contains($text, 'yudisium') || str_contains($text, 'kelulusan')) {
            return "Syarat & Prosedur Surat Keterangan Bebas Pustaka:\n\n"
                ."1. Tidak memiliki pinjaman buku aktif dan tidak ada tunggakan denda.\n"
                ."2. Telah lolos uji Turnitin dengan lembar persetujuan persentase kemiripan.\n"
                ."3. Mengunggah file softcopy lengkap skripsi/tugas akhir (format PDF) ke portal Repository MNC University.\n"
                ."4. Menyerahkan 1 eksemplar hardcopy jilid skripsi yang telah ditandatangani lengkap oleh dosen pembimbing dan penguji.\n"
                ."5. Menyerahkan donasi buku referensi akademik alumni (sesuai ketentuan prodi).\n\n"
                .'Surat keterangan bebas pustaka digital/cetak diterbitkan dalam 1 hari kerja setelah verifikasi berkas selesai.';
        }

        // 7. Skripsi & Repository Institusi
        if (str_contains($text, 'skripsi') || str_contains($text, 'repository') || str_contains($text, 'karya ilmiah') || str_contains($text, 'tesis') || str_contains($text, 'tugas akhir') || str_contains($text, 'laporan magang')) {
            return "Repository Karya Ilmiah Perpustakaan MNC University:\n\n"
                ."• Menyimpan arsip digital Tugas Akhir, Skripsi S1, Laporan Magang, dan Publikasi Ilmiah Dosen.\n"
                ."• Akses publik terbuka untuk abstrak, bab pendahuluan, dan daftar pustaka.\n"
                ."• Naskah lengkap (full-text) dapat diakses oleh sivitas akademika aktif melalui login Single Sign-On (SSO) akun kampus MNC University.\n"
                ."• Mahasiswa yang telah menyelesaikan sidang dapat melakukan serah simpan digital secara mandiri melalui menu 'Repository'.";
        }

        // 8. E-Journal & Pangkalan Data Jurnal Internasional
        if (str_contains($text, 'journal') || str_contains($text, 'jurnal') || str_contains($text, 'ejournal') || str_contains($text, 'scopus') || str_contains($text, 'doaj') || str_contains($text, 'ieee') || str_contains($text, 'sciencedirect') || str_contains($text, 'springer') || str_contains($text, 'remote') || str_contains($text, 'luar kampus')) {
            return "Panduan Akses E-Journal & Database Ilmiah:\n\n"
                ."• Pangkalan data jurnal dapat dilihat pada menu 'E-Journal' (ScienceDirect, IEEE Xplore, SpringerLink, DOAJ, Emerald, ResearchGate).\n"
                ."• Akses dari Luar Kampus: Gunakan Single Sign-On (SSO) email resmi institusi @mncuniversity.ac.id atau akses melalui portal e-Resources Perpustakaan Nasional RI (PNRI).\n"
                .'• Untuk panduan registrasi akun e-Resources Perpusnas atau bantuan penelusuran artikel ilmiah berbayar, Anda dapat berkonsultasi langsung dengan pustakawan referensi.';
        }

        // 9. OJS (Open Journal Systems) Kampus
        if (str_contains($text, 'ojs') || str_contains($text, 'jurnal prodi') || str_contains($text, 'submit artikel') || str_contains($text, 'publikasi internal')) {
            return "Portal Open Journal Systems (OJS) MNC University:\n\n"
                ."• Menampung jurnal ilmiah berkala yang dikelola oleh program studi di lingkungan MNC University (Informatika, Komunikasi, Desain, Bisnis).\n"
                ."• Seluruh artikel jurnal dapat diunduh secara gratis (Open Access) melalui menu 'OJS per Prodi'.\n"
                .'• Penulis (dosen/mahasiswa) dapat melakukan registrasi dan submisi manuskrip artikel secara daring melalui masing-masing laman jurnal OJS prodi terkait.';
        }

        // 10. Fasilitas Ruang Diskusi, Wi-Fi, dan Komputer
        if (str_contains($text, 'ruang diskusi') || str_contains($text, 'diskusi') || str_contains($text, 'multimedia') || str_contains($text, 'fasilitas') || str_contains($text, 'wifi') || str_contains($text, 'komputer') || str_contains($text, 'colokan') || str_contains($text, 'charging')) {
            return "Fasilitas Perpustakaan MNC University:\n\n"
                ."• Ruang Diskusi Kelompok: Tersedia 2 ruang diskusi berkapasitas 4-8 orang yang dilengkapi papan tulis dan layar monitor. Pemesanan dilakukan di loket sirkulasi dengan durasi maksimal 2 jam per sesi.\n"
                ."• PC Workstation & OPAC: Perangkat komputer untuk pencarian katalog buku dan akses jurnal digital di area lantai 2.\n"
                ."• Area Baca Mandiri: Meja baca tenang dilengkapi stopkontak untuk laptop dan perangkat belajar.\n"
                ."• Akses Wi-Fi: Terhubung ke hotspot 'MNCU-Student' dengan akun portal mahasiswa aktif.";
        }

        // 11. Keanggotaan & Kartu Tanda Mahasiswa (KTM)
        if (str_contains($text, 'anggota') || str_contains($text, 'keanggotaan') || str_contains($text, 'ktm') || str_contains($text, 'kartu')) {
            return "Informasi Keanggotaan Perpustakaan:\n\n"
                ."• Seluruh mahasiswa, dosen, dan staf aktif MNC University secara otomatis terdaftar sebagai anggota perpustakaan.\n"
                ."• Kartu anggota utama menggunakan Kartu Tanda Mahasiswa (KTM) fisik maupun kartu identitas digital pada portal akademik.\n"
                .'• Jika KTM hilang atau belum dicetak, pemustaka dapat menggunakan Kartu Mahasiswa Sementara dari BAAK untuk transaksi peminjaman buku di loket sirkulasi.';
        }

        // 12. Usulan Pengadaan Buku Baru
        if (str_contains($text, 'usul') || str_contains($text, 'pengadaan') || str_contains($text, 'request buku') || str_contains($text, 'beli buku') || str_contains($text, 'rekomendasi buku')) {
            return "Layanan Usulan Koleksi Buku Baru:\n\n"
                ."• Mahasiswa dan dosen dapat mengusulkan judul buku referensi atau buku teks baru yang belum tersedia di koleksi perpustakaan.\n"
                ."• Formulir usulan dapat diisi di meja layanan atau diajukan via email ke perpustakaan@mncuniversity.ac.id dengan menyertakan: Judul Buku, Nama Pengarang, Penerbit, Tahun Terbit, ISBN, serta Mata Kuliah terkait.\n"
                .'• Usulan akan diseleksi dan ditindaklanjuti oleh Tim Pengembangan Koleksi pada setiap periode pengadaan buku.';
        }

        // 13. Tata Tertib & Etika Kunjungan
        if (str_contains($text, 'tata tertib') || str_contains($text, 'aturan') || str_contains($text, 'peraturan') || str_contains($text, 'etika') || str_contains($text, 'loker') || str_contains($text, 'pakaian') || str_contains($text, 'makan') || str_contains($text, 'merokok')) {
            return "Tata Tertib Perpustakaan MNC University:\n\n"
                ."1. Berpakaian rapi, sopan, dan mengenakan sepatu (tidak diperkenankan menggunakan kaos oblong tanpa kerah atau sandal jepit).\n"
                ."2. Menitipkan tas ransel, jaket, dan wadah besar di loker penitipan sebelum memasuki area koleksi.\n"
                ."3. Dilarang membawa makanan dan minuman tanpa wadah tertutup rapat ke dalam ruang baca.\n"
                ."4. Menjaga ketenangan dan menyetel nada dering ponsel pada mode getar/senyap (silent).\n"
                .'5. Dilarang keras merokok atau menggunakan vape di seluruh area perpustakaan.';
        }

        // 14. OPAC & Ketersediaan Stok Buku
        if (str_contains($text, 'opac') || str_contains($text, 'katalog') || str_contains($text, 'cari buku') || str_contains($text, 'stok') || str_contains($text, 'call number') || str_contains($text, 'nomor panggil') || str_contains($text, 'rak') || str_contains($text, 'ketersediaan')) {
            return "Panduan Penelusuran Katalog OPAC & Ketersediaan Stok:\n\n"
                ."• Buka menu 'Katalog OPAC' pada navigasi situs.\n"
                ."• Anda dapat melihat total eksemplar fisik, jumlah buku yang sedang tersedia di rak, lokasi rak, dan kode nomor panggil (Call Number).\n"
                ."• Catat kode Call Number (contoh: 005.1 IND a) untuk memudahkan menemukan buku langsung di rak koleksi Lantai 2.\n"
                .'• Jika semua eksemplar sedang dipinjam, Anda dapat memanfaatkan fitur Reservasi Buku melalui petugas sirkulasi.';
        }

        // 15. Jam Operasional
        if (str_contains($text, 'jam') || str_contains($text, 'buka') || str_contains($text, 'operasional') || str_contains($text, 'tutup') || str_contains($text, 'jadwal')) {
            return "Jadwal Operasional Perpustakaan MNC University:\n\n"
                ."• Senin - Jumat: 08.30 - 17.00 WIB\n"
                ."• Sabtu: 09.00 - 14.00 WIB\n"
                ."• Minggu & Hari Libur Nasional: Tutup.\n\n"
                .'Layanan daring (OPAC, Repository, E-Journal, dan Chatbot) dapat diakses 24 jam setiap hari.';
        }

        // 16. Peminjaman Buku (Umum)
        if (str_contains($text, 'pinjam') || str_contains($text, 'peminjaman') || str_contains($text, 'durasi') || str_contains($text, 'hari')) {
            return "Ketentuan Peminjaman Buku Fisik MNC University:\n\n"
                ."• Kuota peminjaman: Maksimal 3 eksemplar buku sekaligus untuk mahasiswa aktif.\n"
                ."• Durasi pinjam: 7 hari kalender.\n"
                ."• Perpanjangan: Dapat diperpanjang 1 kali (tambahan 7 hari) jika buku tidak sedang direservasi orang lain.\n"
                .'• Syarat: Membawa KTM aktif dan melakukan pemindaian barcode buku di meja sirkulasi Lantai 2.';
        }

        // 17. Kontak & Lokasi
        if (str_contains($text, 'kontak') || str_contains($text, 'hubungi') || str_contains($text, 'lokasi') || str_contains($text, 'alamat') || str_contains($text, 'email') || str_contains($text, 'telepon') || str_contains($text, 'wa') || str_contains($text, 'whatsapp') || str_contains($text, 'pustakawan')) {
            return "Kontak & Lokasi Perpustakaan MNC University:\n\n"
                ."• Lokasi: Gedung Rektorat MNC University, Lantai 2, Jl. Arteri Kedoya No. 2, Kebon Jeruk, Jakarta Barat\n"
                ."• Email Layanan: perpustakaan@mncuniversity.ac.id\n"
                ."• Telepon: (021) 5366-6555\n"
                .'• Jam Layanan Petugas: Senin - Jumat (08.30 - 17.00 WIB) & Sabtu (09.00 - 14.00 WIB).';
        }

        // 18. Sapaan / Salam
        if (str_contains($text, 'halo') || str_contains($text, 'hai') || str_contains($text, 'hello') || str_contains($text, 'pagi') || str_contains($text, 'siang') || str_contains($text, 'sore') || str_contains($text, 'malam') || str_contains($text, 'assalam')) {
            return "Halo! Selamat datang di Layanan Bantuan Perpustakaan MNC University.\n\nAda yang bisa kami bantu hari ini? Anda dapat menanyakan tentang:\n• Jam operasional & lokasi perpustakaan\n• Peminjaman, perpanjangan, dan denda buku\n• Uji Turnitin & syarat bebas pustaka wisuda\n• Akses e-journal & repository skripsi\n• Peminjaman ruang diskusi & fasilitas lainnya.";
        }

        return "Terima kasih atas pertanyaan Anda. Untuk topik tersebut, Anda dapat menanyakan seputar peminjaman buku, perpanjangan, denda keterlambatan, cek Turnitin skripsi, akses e-journal luar kampus, maupun surat bebas pustaka.\n\nBila membutuhkan informasi lebih rinci, pustakawan kami siap membantu di meja sirkulasi Lantai 2 atau via email perpustakaan@mncuniversity.ac.id.";
    }

    /**
     * Tampilkan profil pustakawan dari database.
     */
    public function pustakawan(): View
    {
        $kepala = ProfilPustakawan::with('pengguna')
            ->whereHas('pengguna.peran', function ($q) {
                $q->where('nama_peran', 'like', '%Kepala%');
            })
            ->first();

        $staf = ProfilPustakawan::with('pengguna')
            ->when($kepala, fn ($q) => $q->where('id_profil_pustakawan', '!=', $kepala->id_profil_pustakawan))
            ->get();

        return view('pustakawan', compact('kepala', 'staf'));
    }
}
