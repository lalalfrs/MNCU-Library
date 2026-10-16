<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\ProfilPustakawan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
            'Jam buka perpustakaan?',
            'Cara pinjam buku di OPAC?',
            'Bagaimana akses e-journal dari rumah?',
            'Batas waktu peminjaman buku?',
            'Syarat bebas pustaka untuk wisuda?',
            'Kontak pustakawan MNC University?',
        ];

        return view('chat', compact('quickPrompts'));
    }

    /**
     * Tampilkan katalog OPAC dengan pencarian dan filter koleksi fisik.
     */
    public function opac(Request $request): View
    {
        $query = trim((string) $request->query('q', ''));
        $category = (string) $request->query('kategori', 'Semua');
        $prodi = (string) $request->query('prodi', 'Semua');

        $dbBooks = Buku::with(['penulis', 'jenisBuku', 'kategoriBuku', 'eksemplar'])->get();

        if ($dbBooks->isNotEmpty()) {
            $catalogItems = $dbBooks->map(function (Buku $buku): array {
                $authorName = $buku->penulis->pluck('nama_penulis')->join(', ') ?: 'Pustakawan MNC University';
                $copiesCount = $buku->eksemplar->count();
                $firstCopy = $buku->eksemplar->first();
                $hasAvailable = $buku->eksemplar->contains('status', 'Tersedia');

                return [
                    'id' => $buku->id_buku,
                    'title' => $buku->judul_buku,
                    'author' => $authorName,
                    'year' => $buku->tahun_terbit ?? 2024,
                    'category' => $buku->jenisBuku?->nama_jenis_buku ?? 'Buku Teks',
                    'prodi' => 'Informatika',
                    'call_number' => $firstCopy?->nomor_panggil ?? '005.1 MNC b',
                    'location' => $firstCopy?->lokasi_rak ?? 'Lantai 2, Rak Koleksi',
                    'copies' => $copiesCount > 0 ? "{$copiesCount} eksemplar" : '1 eksemplar',
                    'status' => $hasAvailable ? 'Tersedia' : 'Dipinjam',
                ];
            })->all();
        } else {
            $catalogItems = [
                [
                    'id' => 1,
                    'title' => 'Algoritma & Struktur Data dengan Python',
                    'author' => 'Dr. Rian Hidayat, M.Kom.',
                    'year' => 2024,
                    'category' => 'Buku Teks',
                    'prodi' => 'Informatika',
                    'call_number' => '005.1 HID a',
                    'location' => 'Lantai 2, Rak Komputer 03',
                    'copies' => '4 eksemplar',
                    'status' => 'Tersedia',
                ],
                [
                    'id' => 2,
                    'title' => 'Manajemen Pemasaran Strategis di Era Digital',
                    'author' => 'Prof. Dr. Hendra Gunawan, S.E., M.M.',
                    'year' => 2023,
                    'category' => 'Buku Teks',
                    'prodi' => 'Manajemen',
                    'call_number' => '658.8 GUN m',
                    'location' => 'Lantai 2, Rak Ekonomi 01',
                    'copies' => '3 eksemplar',
                    'status' => 'Tersedia',
                ],
                [
                    'id' => 3,
                    'title' => 'Prinsip Desain Komunikasi Visual & Tipografi',
                    'author' => 'Siti Nurhaliza, M.Ds.',
                    'year' => 2024,
                    'category' => 'Buku Teks',
                    'prodi' => 'Desain Komunikasi Visual',
                    'call_number' => '741.6 NUR p',
                    'location' => 'Lantai 2, Rak Desain 02',
                    'copies' => '2 eksemplar',
                    'status' => 'Dipinjam (Kembali 20 Okt)',
                ],
                [
                    'id' => 4,
                    'title' => 'Analisis Perancangan Sistem Informasi Enterprise',
                    'author' => 'Ahmad Fauzi, M.T.',
                    'year' => 2023,
                    'category' => 'Buku Teks',
                    'prodi' => 'Sistem Informasi',
                    'call_number' => '004.2 FAU a',
                    'location' => 'Lantai 2, Rak Komputer 04',
                    'copies' => '5 eksemplar',
                    'status' => 'Tersedia',
                ],
                [
                    'id' => 5,
                    'title' => 'Akuntansi Keuangan Menengah Berbasis IFRS',
                    'author' => 'Dewi Lestari, S.E., M.Ak., Ak.',
                    'year' => 2022,
                    'category' => 'Referensi',
                    'prodi' => 'Akuntansi',
                    'call_number' => '657.48 LES a',
                    'location' => 'Lantai 2, Rak Akuntansi 01',
                    'copies' => '3 eksemplar',
                    'status' => 'Tersedia',
                ],
                [
                    'id' => 6,
                    'title' => 'Komunikasi Massa & Dinamika Media Penyiaran',
                    'author' => 'Bambang Sudibyo, M.Si.',
                    'year' => 2023,
                    'category' => 'Buku Teks',
                    'prodi' => 'Sains Komunikasi',
                    'call_number' => '302.23 SUD k',
                    'location' => 'Lantai 2, Rak Komunikasi 02',
                    'copies' => '4 eksemplar',
                    'status' => 'Tersedia',
                ],
                [
                    'id' => 7,
                    'title' => 'Penerapan Machine Learning untuk Deteksi Pola Data Medis',
                    'author' => 'Muhammad Farhan (NIM: 20210801045)',
                    'year' => 2025,
                    'category' => 'Skripsi',
                    'prodi' => 'Informatika',
                    'call_number' => 'SKR-INF-2025-012',
                    'location' => 'Koleksi Khusus Karya Ilmiah',
                    'copies' => '1 eksemplar (Baca di Tempat)',
                    'status' => 'Tersedia',
                ],
                [
                    'id' => 8,
                    'title' => 'Ensiklopedia Teknologi Informasi & Komunikasi',
                    'author' => 'Tim Redaksi MNC Press',
                    'year' => 2024,
                    'category' => 'Referensi',
                    'prodi' => 'Informatika',
                    'call_number' => 'R 004.03 ENS t',
                    'location' => 'Lantai 1, Ruang Referensi',
                    'copies' => '2 eksemplar (Hanya Baca)',
                    'status' => 'Tersedia',
                ],
            ];
        }

        $filteredItems = array_filter($catalogItems, function (array $item) use ($query, $category, $prodi): bool {
            $matchesQuery = empty($query) || (
                stripos($item['title'], $query) !== false ||
                stripos($item['author'], $query) !== false ||
                stripos($item['call_number'], $query) !== false
            );
            $matchesCategory = $category === 'Semua' || $item['category'] === $category;
            $matchesProdi = $prodi === 'Semua' || $item['prodi'] === $prodi;

            return $matchesQuery && $matchesCategory && $matchesProdi;
        });

        $categories = ['Semua', 'Buku Teks', 'Referensi', 'Skripsi'];
        $prodis = ['Semua', 'Informatika', 'Sistem Informasi', 'Desain Komunikasi Visual', 'Manajemen', 'Akuntansi', 'Sains Komunikasi'];

        return view('opac', [
            'items' => array_values($filteredItems),
            'query' => $query,
            'category' => $category,
            'prodi' => $prodi,
            'categories' => $categories,
            'prodis' => $prodis,
            'totalCount' => count($filteredItems),
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
        $text = strtolower($input);

        if (empty($text)) {
            return 'Halo! Ada yang bisa saya bantu terkait layanan Perpustakaan MNC University? Kamu bisa menanyakan jadwal operasional, peminjaman buku, katalog OPAC, e-journal, atau repository skripsi.';
        }

        if (str_contains($text, 'jam') || str_contains($text, 'buka') || str_contains($text, 'operasional') || str_contains($text, 'tutup')) {
            return "Perpustakaan MNC University buka pada hari:\n\n• Senin sampai Jumat: 08.30 - 17.00 WIB\n• Sabtu: 09.00 - 14.00 WIB\n• Minggu & Hari Libur Nasional: Tutup.\n\nLayanan online (OPAC, Repository, E-Journal, dan E-Book) dapat diakses 24 jam.";
        }

        if (str_contains($text, 'pinjam') || str_contains($text, 'peminjaman') || str_contains($text, 'durasi') || str_contains($text, 'hari')) {
            return "Ketentuan peminjaman buku untuk mahasiswa aktif MNC University:\n\n• Maksimal: 3 buku sekaligus\n• Durasi peminjaman: 7 hari kalender\n• Perpanjangan: Dapat diperpanjang 1 kali (7 hari) selama buku tidak direservasi pemustaka lain\n• Syarat: Membawa Kartu Tanda Mahasiswa (KTM) aktif ke meja sirkulasi.";
        }

        if (str_contains($text, 'denda') || str_contains($text, 'terlambat')) {
            return 'Keterlambatan pengembalian buku dikenakan denda administrasi sebesar Rp1.000 per buku per hari keterlambatan. Denda dapat dibayarkan langsung di loket sirkulasi perpustakaan.';
        }

        if (str_contains($text, 'opac') || str_contains($text, 'katalog') || str_contains($text, 'cari buku')) {
            return "Katalog OPAC dapat diakses melalui menu 'Katalog OPAC'. Di sana kamu bisa mencari koleksi berdasarkan judul, pengarang, maupun subjek untuk mengetahui status ketersediaan dan lokasi rak buku secara langsung.";
        }

        if (str_contains($text, 'journal') || str_contains($text, 'jurnal') || str_contains($text, 'ejournal') || str_contains($text, 'scopus') || str_contains($text, 'doaj')) {
            return "Akses e-journal MNC University dapat dibuka pada menu 'E-Journal' dan 'OJS per Prodi'. Untuk mengakses jurnal internasional dari luar kampus, kamu bisa menggunakan akun email resmi @mncuniversity.ac.id atau akun e-Resources Perpusnas.";
        }

        if (str_contains($text, 'skripsi') || str_contains($text, 'repository') || str_contains($text, 'karya ilmiah') || str_contains($text, 'tesis')) {
            return "Seluruh karya ilmiah sivitas akademik (Skripsi, Tugas Akhir, dan Publikasi Dosen) diarsipkan secara digital di laman 'Repository'. Mahasiswa dapat membaca ringkasan dan abstrak langsung, atau mengunduh dokumen lengkap sesuai kebijakan prodi.";
        }

        if (str_contains($text, 'bebas pustaka') || str_contains($text, 'wisuda') || str_contains($text, 'yudisium')) {
            return "Syarat pengurusan Surat Keterangan Bebas Pustaka untuk yudisium/wisuda:\n\n1. Tidak memiliki tanggungan pinjaman buku atau denda\n2. Menyerahkan softcopy skripsi/tugas akhir format PDF ke repository\n3. Menyerahkan 1 eksemplar hardcopy naskah yang telah disahkan\n4. Mengisi formulir verifikasi bebas pustaka di ruang pelayanan.";
        }

        if (str_contains($text, 'kontak') || str_contains($text, 'hubungi') || str_contains($text, 'lokasi') || str_contains($text, 'alamat') || str_contains($text, 'email')) {
            return "Kontak Resmi Perpustakaan MNC University:\n\n• Lokasi: Kampus MNC University, Gedung Rektorat Lt. 2\n• Email: perpustakaan@mncuniversity.ac.id\n• Telepon/WhatsApp: (021) 5366-6555\n• Jam Pelayanan: Senin - Jumat (08.30 - 17.00 WIB).";
        }

        return 'Terima kasih atas pertanyaanmu! Kamu bisa menanyakan hal seputar peminjaman buku, katalog OPAC, akses jurnal, jam operasional, atau persyaratan bebas pustaka. Jika memerlukan bantuan khusus, pustakawan kami siap membantu di meja sirkulasi.';
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
