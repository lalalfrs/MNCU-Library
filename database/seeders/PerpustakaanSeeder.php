<?php

namespace Database\Seeders;

use App\Models\Acara;
use App\Models\Berita;
use App\Models\Buku;
use App\Models\BukuPenulis;
use App\Models\BukuSubjek;
use App\Models\EksemplarBuku;
use App\Models\JenisBuku;
use App\Models\KategoriBuku;
use App\Models\Kontak;
use App\Models\KunjunganBuku;
use App\Models\PeminjamanBuku;
use App\Models\Penerbit;
use App\Models\Pengguna;
use App\Models\Penulis;
use App\Models\Peran;
use App\Models\Prodi;
use App\Models\ProfilPustakawan;
use App\Models\SubjekBuku;
use App\Models\VisitorLog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PerpustakaanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Table: peran
        $peranList = [
            [
                'id_peran' => 'MNCU00001',
                'uuid' => (string) Str::uuid(),
                'nama_peran' => 'Kepala Perpustakaan',
                'deskripsi_peran' => 'Pimpinan dan pengawas seluruh operasional dan kebijakan perpustakaan',
            ],
            [
                'id_peran' => 'MNCU00002',
                'uuid' => (string) Str::uuid(),
                'nama_peran' => 'Pustakawan',
                'deskripsi_peran' => 'Staf fungsional pengelola koleksi, sirkulasi, dan pelayanan pemustaka',
            ],
            [
                'id_peran' => 'MNCU00003',
                'uuid' => (string) Str::uuid(),
                'nama_peran' => 'Mahasiswa',
                'deskripsi_peran' => 'Pemustaka sivitas akademika jenjang sarjana',
            ],
            [
                'id_peran' => 'MNCU00004',
                'uuid' => (string) Str::uuid(),
                'nama_peran' => 'Dosen',
                'deskripsi_peran' => 'Tenaga pendidik dan peneliti MNC University',
            ],
        ];

        foreach ($peranList as $item) {
            Peran::updateOrCreate(['id_peran' => $item['id_peran']], $item);
        }

        // 2. Table: prodi
        $prodiList = [
            ['id_prodi' => 'MNCU00001', 'uuid' => (string) Str::uuid(), 'nama_prodi' => 'Sains Data', 'jenjang' => 'S1'],
            ['id_prodi' => 'MNCU00002', 'uuid' => (string) Str::uuid(), 'nama_prodi' => 'Informatika', 'jenjang' => 'S1'],
            ['id_prodi' => 'MNCU00003', 'uuid' => (string) Str::uuid(), 'nama_prodi' => 'Sistem Informasi', 'jenjang' => 'S1'],
            ['id_prodi' => 'MNCU00004', 'uuid' => (string) Str::uuid(), 'nama_prodi' => 'Desain Komunikasi Visual', 'jenjang' => 'S1'],
            ['id_prodi' => 'MNCU00005', 'uuid' => (string) Str::uuid(), 'nama_prodi' => 'Manajemen', 'jenjang' => 'S1'],
            ['id_prodi' => 'MNCU00006', 'uuid' => (string) Str::uuid(), 'nama_prodi' => 'Akuntansi', 'jenjang' => 'S1'],
            ['id_prodi' => 'MNCU00007', 'uuid' => (string) Str::uuid(), 'nama_prodi' => 'Pendidikan Bahasa Inggris', 'jenjang' => 'S1'],
        ];

        foreach ($prodiList as $item) {
            Prodi::updateOrCreate(['id_prodi' => $item['id_prodi']], $item);
        }

        // 3. Table: pengguna
        $penggunaList = [
            [
                'id_pengguna' => 'MNCU00001',
                'uuid' => (string) Str::uuid(),
                'id_peran' => 'MNCU00001',
                'id_prodi' => null,
                'nama_lengkap' => 'Dr. H. Muhammad Arifin, M.Hum.',
                'email' => 'arifin@mncu.ac.id',
                'kata_sandi' => Hash::make('password123'),
                'nomor_identitas' => 'PST-MNCU-001',
            ],
            [
                'id_pengguna' => 'MNCU00002',
                'uuid' => (string) Str::uuid(),
                'id_peran' => 'MNCU00002',
                'id_prodi' => null,
                'nama_lengkap' => 'Sarah Amalia, S.I.Pust.',
                'email' => 'sarah.amalia@mncu.ac.id',
                'kata_sandi' => Hash::make('password123'),
                'nomor_identitas' => 'PST-MNCU-002',
            ],
            [
                'id_pengguna' => 'MNCU00003',
                'uuid' => (string) Str::uuid(),
                'id_peran' => 'MNCU00002',
                'id_prodi' => null,
                'nama_lengkap' => 'Fajar Pratama, S.Kom.',
                'email' => 'fajar.pratama@mncu.ac.id',
                'kata_sandi' => Hash::make('password123'),
                'nomor_identitas' => 'PST-MNCU-003',
            ],
            [
                'id_pengguna' => 'MNCU00004',
                'uuid' => (string) Str::uuid(),
                'id_peran' => 'MNCU00003',
                'id_prodi' => 'MNCU00002',
                'nama_lengkap' => 'Ahmad Fauzi Rahman',
                'email' => 'fauzi.mhs@mncu.ac.id',
                'kata_sandi' => Hash::make('password123'),
                'nomor_identitas' => '2024010012',
            ],
            [
                'id_pengguna' => 'MNCU00005',
                'uuid' => (string) Str::uuid(),
                'id_peran' => 'MNCU00004',
                'id_prodi' => 'MNCU00005',
                'nama_lengkap' => 'Prof. Dr. Hendra Gunawan, S.E., M.M.',
                'email' => 'hendra.gunawan@mncu.ac.id',
                'kata_sandi' => Hash::make('password123'),
                'nomor_identitas' => 'NIDN-0312048501',
            ],
            [
                'id_pengguna' => 'MNCU00006',
                'uuid' => (string) Str::uuid(),
                'id_peran' => 'MNCU00003',
                'id_prodi' => 'MNCU00001',
                'nama_lengkap' => 'Nabila Putri Ramadhani',
                'email' => 'nabila.mhs@mncu.ac.id',
                'kata_sandi' => Hash::make('password123'),
                'nomor_identitas' => '2024010045',
            ],
        ];

        foreach ($penggunaList as $item) {
            Pengguna::updateOrCreate(['id_pengguna' => $item['id_pengguna']], $item);
        }

        // 4. Table: profil_pustakawan
        $profilPustakawanList = [
            [
                'id_profil_pustakawan' => 'MNCU00001',
                'uuid' => (string) Str::uuid(),
                'id_pengguna' => 'MNCU00001',
                'nama_pustakawan' => 'Dr. H. Muhammad Arifin, M.Hum.',
                'jabatan' => 'Kepala Perpustakaan MNC University',
                'foto_pustakawan' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=400&q=80',
                'deskripsi_pustakawan' => 'Bertanggung jawab atas pengelolaan seluruh layanan perpustakaan, pengembangan koleksi, dan koordinasi kegiatan literasi informasi.',
            ],
            [
                'id_profil_pustakawan' => 'MNCU00002',
                'uuid' => (string) Str::uuid(),
                'id_pengguna' => 'MNCU00002',
                'nama_pustakawan' => 'Sarah Amalia, S.I.Pust.',
                'jabatan' => 'Pustakawan Layanan Sirkulasi & Referensi',
                'foto_pustakawan' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=400&q=80',
                'deskripsi_pustakawan' => 'Melayani sirkulasi peminjaman, konsultasi literasi informasi, dan administrasi keanggotaan pemustaka.',
            ],
            [
                'id_profil_pustakawan' => 'MNCU00003',
                'uuid' => (string) Str::uuid(),
                'id_pengguna' => 'MNCU00003',
                'nama_pustakawan' => 'Fajar Pratama, S.Kom.',
                'jabatan' => 'Pustakawan Teknologi Informasi & E-Resources',
                'foto_pustakawan' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=400&q=80',
                'deskripsi_pustakawan' => 'Mengelola sistem katalog OPAC, repository karya ilmiah institusi, repositori jurnal, dan infrastruktur digital perpustakaan.',
            ],
        ];

        foreach ($profilPustakawanList as $item) {
            ProfilPustakawan::updateOrCreate(['id_profil_pustakawan' => $item['id_profil_pustakawan']], $item);
        }

        // 5. Table: jenis_buku
        $jenisList = [
            ['id_jenis_buku' => 'MNCU00001', 'uuid' => (string) Str::uuid(), 'nama_jenis_buku' => 'Buku Teks', 'deskripsi_jenis' => 'Buku materi perkuliahan utama'],
            ['id_jenis_buku' => 'MNCU00002', 'uuid' => (string) Str::uuid(), 'nama_jenis_buku' => 'Buku Referensi', 'deskripsi_jenis' => 'Kamus, ensiklopedia, dan buku pedoman acuan'],
            ['id_jenis_buku' => 'MNCU00003', 'uuid' => (string) Str::uuid(), 'nama_jenis_buku' => 'Karya Ilmiah / Skripsi', 'deskripsi_jenis' => 'Tugas akhir dan hasil riset sivitas akademika'],
            ['id_jenis_buku' => 'MNCU00004', 'uuid' => (string) Str::uuid(), 'nama_jenis_buku' => 'Jurnal & Majalah Ilmiah', 'deskripsi_jenis' => 'Publikasi berkala artikel penelitian'],
            ['id_jenis_buku' => 'MNCU00005', 'uuid' => (string) Str::uuid(), 'nama_jenis_buku' => 'Buku Fiksi & Sastra', 'deskripsi_jenis' => 'Koleksi novel, sastra, dan pengayaan umum'],
        ];

        foreach ($jenisList as $item) {
            JenisBuku::updateOrCreate(['id_jenis_buku' => $item['id_jenis_buku']], $item);
        }

        // 6. Table: kategori_buku
        $kategoriList = [
            ['id_kategori_buku' => 'MNCU00001', 'uuid' => (string) Str::uuid(), 'nama_kategori' => 'Teknologi & Ilmu Komputer', 'deskripsi_kategori' => 'Pemrograman, rekayasa software, AI, dan jaringan'],
            ['id_kategori_buku' => 'MNCU00002', 'uuid' => (string) Str::uuid(), 'nama_kategori' => 'Bisnis, Manajemen & Ekonomi', 'deskripsi_kategori' => 'Pemasaran, keuangan, akuntansi, dan wirausaha'],
            ['id_kategori_buku' => 'MNCU00003', 'uuid' => (string) Str::uuid(), 'nama_kategori' => 'Desain & Seni Visual', 'deskripsi_kategori' => 'DKV, tipografi, animasi, dan ilustrasi'],
            ['id_kategori_buku' => 'MNCU00004', 'uuid' => (string) Str::uuid(), 'nama_kategori' => 'Sains Data & Matematika', 'deskripsi_kategori' => 'Statistika, data analytics, dan kalkulus'],
            ['id_kategori_buku' => 'MNCU00005', 'uuid' => (string) Str::uuid(), 'nama_kategori' => 'Sastra, Humaniora & Umum', 'deskripsi_kategori' => 'Novel sastra, pengembangan diri, dan filsafat'],
        ];

        foreach ($kategoriList as $item) {
            KategoriBuku::updateOrCreate(['id_kategori_buku' => $item['id_kategori_buku']], $item);
        }

        // 7. Table: subjek_buku
        $subjekList = [
            ['id_subjek_buku' => 'MNCU00001', 'uuid' => (string) Str::uuid(), 'nama_subjek' => 'Algoritma & Struktur Data', 'deskripsi_subjek' => 'Konsep fundamental komputasi dan optimasi algoritma'],
            ['id_subjek_buku' => 'MNCU00002', 'uuid' => (string) Str::uuid(), 'nama_subjek' => 'Rekayasa Perangkat Lunak', 'deskripsi_subjek' => 'Arsitektur piranti lunak, clean code, dan pattern desain'],
            ['id_subjek_buku' => 'MNCU00003', 'uuid' => (string) Str::uuid(), 'nama_subjek' => 'Pemasaran Digital', 'deskripsi_subjek' => 'Strategi brand awareness dan pemasaran media internet'],
            ['id_subjek_buku' => 'MNCU00004', 'uuid' => (string) Str::uuid(), 'nama_subjek' => 'Desain Komunikasi Visual', 'deskripsi_subjek' => 'Prinsip tata letak, warna, dan komunikasi simbolis'],
            ['id_subjek_buku' => 'MNCU00005', 'uuid' => (string) Str::uuid(), 'nama_subjek' => 'Pengembangan Diri & Kebiasaan', 'deskripsi_subjek' => 'Membangun kebiasaan produktif dan mindset pertumbuhan'],
        ];

        foreach ($subjekList as $item) {
            SubjekBuku::updateOrCreate(['id_subjek_buku' => $item['id_subjek_buku']], $item);
        }

        // 8. Table: penulis
        $penulisList = [
            ['id_penulis' => 'MNCU00001', 'uuid' => (string) Str::uuid(), 'nama_penulis' => 'Dr. Rian Hidayat, M.Kom.', 'deskripsi_penulis' => 'Dosen dan pakar struktur data serta komputasi terapan'],
            ['id_penulis' => 'MNCU00002', 'uuid' => (string) Str::uuid(), 'nama_penulis' => 'Prof. Dr. Hendra Gunawan, S.E., M.M.', 'deskripsi_penulis' => 'Guru besar manajemen bisnis digital'],
            ['id_penulis' => 'MNCU00003', 'uuid' => (string) Str::uuid(), 'nama_penulis' => 'Siti Nurhaliza, M.Ds.', 'deskripsi_penulis' => 'Praktisi seni desain visual dan kurator tipografi'],
            ['id_penulis' => 'MNCU00004', 'uuid' => (string) Str::uuid(), 'nama_penulis' => 'Robert C. Martin (Uncle Bob)', 'deskripsi_penulis' => 'Insinyur software legendaris pencetus Clean Code & Architecture'],
            ['id_penulis' => 'MNCU00005', 'uuid' => (string) Str::uuid(), 'nama_penulis' => 'James Clear', 'deskripsi_penulis' => 'Penulis internasional tentang produktivitas dan kebiasaan hidup'],
            ['id_penulis' => 'MNCU00006', 'uuid' => (string) Str::uuid(), 'nama_penulis' => 'Pramoedya Ananta Toer', 'deskripsi_penulis' => 'Sastrawan besar Indonesia penulis Tetralogi Buru'],
        ];

        foreach ($penulisList as $item) {
            Penulis::updateOrCreate(['id_penulis' => $item['id_penulis']], $item);
        }

        // 9. Table: penerbit
        $penerbitList = [
            ['id_penerbit' => 'MNCU00001', 'uuid' => (string) Str::uuid(), 'nama_penerbit' => 'Informatika Bandung', 'deskripsi_penerbit' => 'Penerbit buku teknologi komputasi ternama Indonesia'],
            ['id_penerbit' => 'MNCU00002', 'uuid' => (string) Str::uuid(), 'nama_penerbit' => 'Penerbit Erlangga', 'deskripsi_penerbit' => 'Penerbit buku pendidikan tinggi dan referensi sains'],
            ['id_penerbit' => 'MNCU00003', 'uuid' => (string) Str::uuid(), 'nama_penerbit' => 'Gramedia Pustaka Utama', 'deskripsi_penerbit' => 'Grup penerbit terkemuka di Indonesia'],
            ['id_penerbit' => 'MNCU00004', 'uuid' => (string) Str::uuid(), 'nama_penerbit' => 'Prentice Hall', 'deskripsi_penerbit' => 'Penerbit internasional buku sains & teknik komputer'],
            ['id_penerbit' => 'MNCU00005', 'uuid' => (string) Str::uuid(), 'nama_penerbit' => 'Lentera Dipantara', 'deskripsi_penerbit' => 'Penerbit karya sastra klasik Indonesia'],
        ];

        foreach ($penerbitList as $item) {
            Penerbit::updateOrCreate(['id_penerbit' => $item['id_penerbit']], $item);
        }

        // 10. Table: buku
        $bukuList = [
            [
                'id_buku' => 'MNCU00001',
                'uuid' => (string) Str::uuid(),
                'id_jenis_buku' => 'MNCU00001',
                'id_kategori_buku' => 'MNCU00001',
                'id_penerbit' => 'MNCU00001',
                'judul_buku' => 'Algoritma & Struktur Data dengan Python',
                'isbn' => '978-623-7131-01-2',
                'tahun_terbit' => 2024,
                'edisi' => 'Edisi Kedua',
                'deskripsi' => 'Panduan komprehensif implementasi struktur data list, stack, queue, tree, dan graph menggunakan bahasa Python modern.',
                'sampul_buku' => 'https://images.unsplash.com/photo-1532012164546-f432f2e3edd4?auto=format&fit=crop&w=600&q=80',
                'jumlah_halaman' => 380,
                'penulis_ids' => ['MNCU00001'],
                'subjek_ids' => ['MNCU00001'],
                'copies' => [
                    ['kode' => 'INV-BK001-01', 'panggil' => '005.1 HID a c.1', 'rak' => 'Lantai 2, Rak Komputer 03', 'status' => 'Tersedia'],
                    ['kode' => 'INV-BK001-02', 'panggil' => '005.1 HID a c.2', 'rak' => 'Lantai 2, Rak Komputer 03', 'status' => 'Tersedia'],
                    ['kode' => 'INV-BK001-03', 'panggil' => '005.1 HID a c.3', 'rak' => 'Lantai 2, Rak Komputer 03', 'status' => 'Dipinjam'],
                ],
                'visits' => 128,
            ],
            [
                'id_buku' => 'MNCU00002',
                'uuid' => (string) Str::uuid(),
                'id_jenis_buku' => 'MNCU00001',
                'id_kategori_buku' => 'MNCU00002',
                'id_penerbit' => 'MNCU00002',
                'judul_buku' => 'Manajemen Pemasaran Strategis di Era Digital',
                'isbn' => '978-602-444-889-1',
                'tahun_terbit' => 2023,
                'edisi' => 'Edisi Pertama',
                'deskripsi' => 'Studi integrasi channel digital, analitik perilaku konsumen modern, dan perencanaan kampanye brand omni-channel.',
                'sampul_buku' => 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?auto=format&fit=crop&w=600&q=80',
                'jumlah_halaman' => 412,
                'penulis_ids' => ['MNCU00002'],
                'subjek_ids' => ['MNCU00003'],
                'copies' => [
                    ['kode' => 'INV-BK002-01', 'panggil' => '658.8 GUN m c.1', 'rak' => 'Lantai 2, Rak Ekonomi 01', 'status' => 'Tersedia'],
                    ['kode' => 'INV-BK002-02', 'panggil' => '658.8 GUN m c.2', 'rak' => 'Lantai 2, Rak Ekonomi 01', 'status' => 'Tersedia'],
                ],
                'visits' => 94,
            ],
            [
                'id_buku' => 'MNCU00003',
                'uuid' => (string) Str::uuid(),
                'id_jenis_buku' => 'MNCU00001',
                'id_kategori_buku' => 'MNCU00003',
                'id_penerbit' => 'MNCU00003',
                'judul_buku' => 'Prinsip Desain Komunikasi Visual & Tipografi',
                'isbn' => '978-602-03-9120-5',
                'tahun_terbit' => 2024,
                'edisi' => 'Edisi Revisi',
                'deskripsi' => 'Eksplorasi hierarki tipografi, grid system, teori warna, dan komposisi komunikasi visual kontemporer.',
                'sampul_buku' => 'https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=600&q=80',
                'jumlah_halaman' => 296,
                'penulis_ids' => ['MNCU00003'],
                'subjek_ids' => ['MNCU00004'],
                'copies' => [
                    ['kode' => 'INV-BK003-01', 'panggil' => '741.6 NUR p c.1', 'rak' => 'Lantai 2, Rak Desain 02', 'status' => 'Dipinjam'],
                    ['kode' => 'INV-BK003-02', 'panggil' => '741.6 NUR p c.2', 'rak' => 'Lantai 2, Rak Desain 02', 'status' => 'Tersedia'],
                ],
                'visits' => 87,
            ],
            [
                'id_buku' => 'MNCU00004',
                'uuid' => (string) Str::uuid(),
                'id_jenis_buku' => 'MNCU00002',
                'id_kategori_buku' => 'MNCU00001',
                'id_penerbit' => 'MNCU00004',
                'judul_buku' => 'Clean Architecture: A Craftsman\'s Guide to Software Structure and Design',
                'isbn' => '978-013-449-416-6',
                'tahun_terbit' => 2018,
                'edisi' => '1st Edition',
                'deskripsi' => 'Karya agung Robert C. Martin tentang prinsip universal arsitektur piranti lunak, batas komponen, dependency rule, dan domain-driven design.',
                'sampul_buku' => 'https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?auto=format&fit=crop&w=600&q=80',
                'jumlah_halaman' => 432,
                'penulis_ids' => ['MNCU00004'],
                'subjek_ids' => ['MNCU00002'],
                'copies' => [
                    ['kode' => 'INV-BK004-01', 'panggil' => '005.12 MAR c c.1', 'rak' => 'Lantai 2, Rak Komputer 04', 'status' => 'Tersedia'],
                    ['kode' => 'INV-BK004-02', 'panggil' => '005.12 MAR c c.2', 'rak' => 'Lantai 2, Rak Komputer 04', 'status' => 'Tersedia'],
                ],
                'visits' => 210,
            ],
            [
                'id_buku' => 'MNCU00005',
                'uuid' => (string) Str::uuid(),
                'id_jenis_buku' => 'MNCU00005',
                'id_kategori_buku' => 'MNCU00005',
                'id_penerbit' => 'MNCU00003',
                'judul_buku' => 'Atomic Habits: Perubahan Kecil yang Memberikan Hasil Luar Biasa',
                'isbn' => '978-602-06-3317-6',
                'tahun_terbit' => 2021,
                'edisi' => 'Cetakan ke-12',
                'deskripsi' => 'Metode praktis membentuk kebiasaan baik dan mengikis kebiasaan buruk dengan strategi perubahan 1% setiap hari.',
                'sampul_buku' => 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?auto=format&fit=crop&w=600&q=80',
                'jumlah_halaman' => 356,
                'penulis_ids' => ['MNCU00005'],
                'subjek_ids' => ['MNCU00005'],
                'copies' => [
                    ['kode' => 'INV-BK005-01', 'panggil' => '158.1 CLE a c.1', 'rak' => 'Lantai 1, Rak Umum 01', 'status' => 'Tersedia'],
                    ['kode' => 'INV-BK005-02', 'panggil' => '158.1 CLE a c.2', 'rak' => 'Lantai 1, Rak Umum 01', 'status' => 'Tersedia'],
                    ['kode' => 'INV-BK005-03', 'panggil' => '158.1 CLE a c.3', 'rak' => 'Lantai 1, Rak Umum 01', 'status' => 'Dipinjam'],
                ],
                'visits' => 342,
            ],
            [
                'id_buku' => 'MNCU00006',
                'uuid' => (string) Str::uuid(),
                'id_jenis_buku' => 'MNCU00005',
                'id_kategori_buku' => 'MNCU00005',
                'id_penerbit' => 'MNCU00005',
                'judul_buku' => 'Bumi Manusia',
                'isbn' => '978-979-97312-3-4',
                'tahun_terbit' => 2010,
                'edisi' => 'Edisi Khusus',
                'deskripsi' => 'Karya sastra klasik Indonesia berlatar masa kolonial awal abad ke-20 mengenai perjuangan Minke dan Annelies.',
                'sampul_buku' => 'https://images.unsplash.com/photo-1497633762265-9d179a990aa6?auto=format&fit=crop&w=600&q=80',
                'jumlah_halaman' => 535,
                'penulis_ids' => ['MNCU00006'],
                'subjek_ids' => ['MNCU00005'],
                'copies' => [
                    ['kode' => 'INV-BK006-01', 'panggil' => '899.221 TOE b c.1', 'rak' => 'Lantai 1, Rak Sastra 02', 'status' => 'Tersedia'],
                ],
                'visits' => 176,
            ],
        ];

        foreach ($bukuList as $bukuData) {
            $penulisIds = $bukuData['penulis_ids'];
            $subjekIds = $bukuData['subjek_ids'];
            $copies = $bukuData['copies'];
            $visits = $bukuData['visits'];

            unset($bukuData['penulis_ids'], $bukuData['subjek_ids'], $bukuData['copies'], $bukuData['visits']);

            $buku = Buku::updateOrCreate(['id_buku' => $bukuData['id_buku']], $bukuData);

            // Sync penulis
            foreach ($penulisIds as $penulisId) {
                $existing = BukuPenulis::where('id_buku', $buku->id_buku)->where('id_penulis', $penulisId)->first();
                if (! $existing) {
                    $nextBp = BukuPenulis::count() + 1;
                    BukuPenulis::create([
                        'id_buku_penulis' => sprintf('MNCU%05d', $nextBp),
                        'uuid' => (string) Str::uuid(),
                        'id_buku' => $buku->id_buku,
                        'id_penulis' => $penulisId,
                    ]);
                }
            }

            // Sync subjek
            foreach ($subjekIds as $subjekId) {
                $existing = BukuSubjek::where('id_buku', $buku->id_buku)->where('id_subjek_buku', $subjekId)->first();
                if (! $existing) {
                    $nextBs = BukuSubjek::count() + 1;
                    BukuSubjek::create([
                        'id_buku_subjek' => sprintf('MNCU%05d', $nextBs),
                        'uuid' => (string) Str::uuid(),
                        'id_buku' => $buku->id_buku,
                        'id_subjek_buku' => $subjekId,
                    ]);
                }
            }

            // Eksemplar
            foreach ($copies as $copy) {
                $nextEk = EksemplarBuku::count() + 1;
                EksemplarBuku::updateOrCreate(
                    ['kode_inventaris' => $copy['kode']],
                    [
                        'id_eksemplar' => sprintf('MNCU%05d', $nextEk),
                        'uuid' => (string) Str::uuid(),
                        'id_buku' => $buku->id_buku,
                        'kode_inventaris' => $copy['kode'],
                        'nomor_panggil' => $copy['panggil'],
                        'lokasi_rak' => $copy['rak'],
                        'status' => $copy['status'],
                    ]
                );
            }

            // Kunjungan buku
            $nextKjb = KunjunganBuku::count() + 1;
            KunjunganBuku::updateOrCreate(
                ['id_buku' => $buku->id_buku],
                [
                    'id_kunjungan_buku' => sprintf('MNCU%05d', $nextKjb),
                    'uuid' => (string) Str::uuid(),
                    'id_buku' => $buku->id_buku,
                    'jumlah_kunjungan' => $visits,
                ]
            );
        }

        // 11. Table: berita
        $beritaList = [
            [
                'id_berita' => 'MNCU00001',
                'uuid' => (string) Str::uuid(),
                'id_pengguna' => 'MNCU00001',
                'judul_berita' => 'Peluncuran Layanan Akses Digital MNCU Library Versi Baru',
                'slug' => 'peluncuran-layanan-akses-digital-mncu-library-versi-baru',
                'kategori_berita' => 'Pengumuman',
                'deskripsi_berita' => 'Perpustakaan MNC University meresmikan platform digital terintegrasi untuk katalog OPAC dan portal repository ilmiah.',
                'isi_berita' => 'Perpustakaan MNC University secara resmi meluncurkan platform digital terbaru yang mengintegrasikan layanan katalog OPAC, repository karya ilmiah, dan akses portal e-journal internasional. Platform ini dirancang untuk mempermudah sivitas akademika dalam menemukan referensi riset secara cepat dan akurat.',
                'gambar_berita' => 'images/berita/berita-digital-library.jpg',
                'tanggal_publikasi' => now()->subDays(2)->toDateString(),
                'views_count' => 142,
            ],
            [
                'id_berita' => 'MNCU00002',
                'uuid' => (string) Str::uuid(),
                'id_pengguna' => 'MNCU00002',
                'judul_berita' => 'Workshop Literasi Informasi & Pengutipan Sitasi Ilmiah dengan Zotero',
                'slug' => 'workshop-literasi-informasi-dan-pengutipan-sitasi-ilmiah-dengan-zotero',
                'kategori_berita' => 'Kegiatan',
                'deskripsi_berita' => 'Tingkatkan kualitas penulisan tugas akhir Anda dengan manajemen sitasi otomatis menggunakan Zotero dan Mendeley.',
                'isi_berita' => 'Workshop literasi informasi ini bertujuan membekali mahasiswa semester akhir dengan keahlian teknis manajemen sitasi ilmiah, pencegahan plagiarisme, dan strategi pencarian paper bereputasi di Scopus dan DOAJ.',
                'gambar_berita' => 'images/berita/berita-workshop-sitasi.jpg',
                'tanggal_publikasi' => now()->subDays(5)->toDateString(),
                'views_count' => 88,
            ],
            [
                'id_berita' => 'MNCU00003',
                'uuid' => (string) Str::uuid(),
                'id_pengguna' => 'MNCU00003',
                'judul_berita' => 'Penambahan 500+ Judul E-Book Terbaru Bidang Sains Data & AI',
                'slug' => 'penambahan-500-judul-e-book-terbaru-bidang-sains-data-dan-ai',
                'kategori_berita' => 'Koleksi',
                'deskripsi_berita' => 'Koleksi e-book terbaru telah ditambahkan ke portal digital, mencakup deep learning, cloud computing, dan computer vision.',
                'isi_berita' => 'Dalam rangka mendukung pembelajaran riset modern, Perpustakaan MNC University menambahkan lebih dari 500 koleksi e-book internasional berlisensi resmi dari penerbit dunia seperti Springer, Wiley, dan IEEE.',
                'gambar_berita' => 'images/berita/berita-ebook-ai.jpg',
                'tanggal_publikasi' => now()->subDays(8)->toDateString(),
                'views_count' => 205,
            ],
        ];

        foreach ($beritaList as $item) {
            Berita::updateOrCreate(['id_berita' => $item['id_berita']], $item);
        }

        // 12. Table: acara_perpustakaan
        $acaraList = [
            [
                'id_acara' => 'MNCU00001',
                'uuid' => (string) Str::uuid(),
                'id_pengguna' => 'MNCU00001',
                'nama_acara' => 'MNCU Library Book Fair & Tech Literacy Fest 2026',
                'slug' => 'mncu-library-book-fair-tech-literacy-fest-2026',
                'kategori_acara' => 'Festival',
                'ringkasan_acara' => 'Pameran buku terakbar tahun ini dengan diskon menarik, talkshow penulis, dan demo AI tools untuk riset akademik.',
                'deskripsi_acara' => 'Acara pameran buku tahunan MNC University menghadirkan puluhan penerbit terkemuka, pameran inovasi karya mahasiswa, dan seminar teknologi informasi untuk pendidikan.',
                'gambar_acara' => 'https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=800&q=80',
                'narasumber' => 'Dewi Lestari & Tim AI MNC Group',
                'tanggal_mulai' => now()->addDays(7)->setTime(9, 0),
                'tanggal_selesai' => now()->addDays(7)->setTime(16, 0),
                'lokasi_acara' => 'Auditorium Lantai 3, Gedung MNC University',
                'tautan_pendaftaran' => 'https://bit.ly/mncu-bookfair-2026',
                'kuota' => 250,
                'is_active' => true,
            ],
            [
                'id_acara' => 'MNCU00002',
                'uuid' => (string) Str::uuid(),
                'id_pengguna' => 'MNCU00002',
                'nama_acara' => 'Bedah Buku: Clean Architecture & Software Modern',
                'slug' => 'bedah-buku-clean-architecture-software-modern',
                'kategori_acara' => 'Bedah Buku',
                'ringkasan_acara' => 'Membahas tuntas prinsip perancangan piranti lunak enterprise berskala besar bersama praktisi industri.',
                'deskripsi_acara' => 'Sesi bedah buku Clean Architecture bersama Principal Engineer terkemuka. Terbuka bagi seluruh mahasiswa program studi Informatika dan Sistem Informasi.',
                'gambar_acara' => 'https://images.unsplash.com/photo-1517048676732-d65bc937f952?auto=format&fit=crop&w=800&q=80',
                'narasumber' => 'Rian Hidayat, M.Kom.',
                'tanggal_mulai' => now()->addDays(14)->setTime(13, 30),
                'tanggal_selesai' => now()->addDays(14)->setTime(16, 0),
                'lokasi_acara' => 'Ruang Multimedia Perpustakaan MNC University',
                'tautan_pendaftaran' => 'https://bit.ly/bedah-buku-mncu',
                'kuota' => 80,
                'is_active' => true,
            ],
        ];

        foreach ($acaraList as $item) {
            Acara::updateOrCreate(['id_acara' => $item['id_acara']], $item);
        }

        // 13. Table: kontak
        $kontakList = [
            [
                'id_kontak' => 'MNCU00001',
                'uuid' => (string) Str::uuid(),
                'id_pengguna' => 'MNCU00004',
                'nama' => 'Ahmad Fauzi Rahman',
                'email_kontak' => 'fauzi.mhs@mncu.ac.id',
                'nomor_telepon' => '081234567890',
                'isi_pesan' => 'Mohon informasi prosedur perpanjangan masa pinjam buku skripsi melalui portal online.',
                'waktu_kirim' => now()->subDay(),
            ],
        ];

        foreach ($kontakList as $item) {
            Kontak::updateOrCreate(['id_kontak' => $item['id_kontak']], $item);
        }

        // 14. Table: log_kunjungan
        for ($i = 0; $i < 15; $i++) {
            $logId = sprintf('MNCU%05d', $i + 1);
            VisitorLog::updateOrCreate(
                ['id_log_kunjungan' => $logId],
                [
                    'id_log_kunjungan' => $logId,
                    'uuid' => (string) Str::uuid(),
                    'id_pengguna' => $i % 3 === 0 ? 'MNCU00004' : null,
                    'waktu_kunjungan' => now()->subHours($i * 2),
                    'session_id' => 'seed-session-'.$i,
                    'ip_address' => '127.0.0.1',
                    'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Seeder',
                ]
            );
        }

        // 15. Table: peminjaman_buku (Simulasi sirkulasi & kalkulasi keterlambatan tanpa sanksi denda)
        PeminjamanBuku::updateOrCreate(
            ['id_peminjaman' => 'PJ00001'],
            [
                'id_peminjaman' => 'PJ00001',
                'uuid' => (string) Str::uuid(),
                'id_pengguna' => 'MNCU00004',
                'id_buku' => 'MNCU00001',
                'id_eksemplar' => 'MNCU00002',
                'tanggal_pinjam' => now()->subDays(19)->toDateString(),
                'tanggal_jatuh_tempo' => now()->subDays(5)->toDateString(),
                'tanggal_kembali' => null,
                'status' => 'Terlambat',
                'hari_keterlambatan' => 5,
                'catatan_keterlambatan' => 'Peminjaman melewati batas tempo 14 hari tanpa sanksi denda.',
                'notifikasi_peringatan_dikirim' => true,
                'tanggal_notifikasi' => now()->subDays(4),
            ]
        );
        EksemplarBuku::where('id_eksemplar', 'MNCU00002')->update(['status' => 'Dipinjam']);

        PeminjamanBuku::updateOrCreate(
            ['id_peminjaman' => 'PJ00002'],
            [
                'id_peminjaman' => 'PJ00002',
                'uuid' => (string) Str::uuid(),
                'id_pengguna' => 'MNCU00006',
                'id_buku' => 'MNCU00002',
                'id_eksemplar' => 'MNCU00004',
                'tanggal_pinjam' => now()->subDays(10)->toDateString(),
                'tanggal_jatuh_tempo' => now()->addDays(4)->toDateString(),
                'tanggal_kembali' => now()->subDays(1)->toDateString(),
                'status' => 'Kembali',
                'hari_keterlambatan' => 0,
                'catatan_keterlambatan' => 'Buku kembali dalam kondisi fisik baik dan rapi.',
                'notifikasi_peringatan_dikirim' => false,
                'tanggal_notifikasi' => null,
            ]
        );
    }
}
