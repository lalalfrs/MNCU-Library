<?php

namespace Database\Seeders;

use App\Models\Berita;
use Illuminate\Database\Seeder;

class BeritaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $articles = [
            [
                'title' => 'Sosialisasi Akses Database Jurnal Internasional ScienceDirect & IEEE Xplore untuk Sivitas Akademika',
                'slug' => 'sosialisasi-akses-jurnal-internasional-sciencedirect-ieee',
                'category' => 'Layanan Digital',
                'excerpt' => 'Perpustakaan MNC University menyelenggarakan sosialisasi pemanfaatan database jurnal ilmiah internasional guna mendukung riset dan tugas akhir.',
                'content' => "<p>Perpustakaan MNC University terus berkomitmen untuk memperluas akses referensi ilmiah mutakhir bagi seluruh sivitas akademika. Melalui kerja sama terbaru, seluruh dosen dan mahasiswa aktif kini dapat mengakses ribuan artikel riset terkini dari database terkemuka seperti <strong>ScienceDirect</strong>, <strong>IEEE Xplore</strong>, dan <strong>SpringerLink</strong>.</p>\n\n<h3>Mengapa Akses Ini Penting?</h3>\n<p>Dalam era pembelajaran berbasis riset, validitas dan kebaruan literatur menjadi fondasi utama kelulusan riset skripsi, tesis, dan publikasi jurnal terindeks Scopus / SINTA. Sivitas akademika kini tidak perlu lagi terkendala paywall saat mengunduh paper bereputasi internasional.</p>\n\n<h3>Cara Akses Dari Luar Kampus (Off-Campus Access)</h3>\n<ol>\n<li>Buka portal e-resources melalui menu <strong>E-Journal</strong> di Beranda MNC Library.</li>\n<li>Gunakan Single Sign-On (SSO) email resmi institusi (@mncuniversity.ac.id).</li>\n<li>Lakukan pencarian berdasarkan kata kunci topik atau Digital Object Identifier (DOI).</li>\n</ol>\n\n<p>Bila mahasiswa mengalami kendala otentikasi atau membutuhkan bantuan penelusuran literatur spesifik, Tim Pustakawan Referensi siap membantu di Ruang Layanan Informasi Lt. 2 atau melalui email pustaka@mncuniversity.ac.id.</p>",
                'author' => 'Tim Layanan Referensi MNC University',
                'published_at' => now()->subDays(2),
                'views_count' => 342,
            ],
            [
                'title' => 'Pembaruan Jam Operasional & Fasilitas Coworking Space Selama Periode Ujian Tengah Semester',
                'slug' => 'pembaruan-jam-operasional-periode-uts',
                'category' => 'Pengumuman',
                'excerpt' => 'Merespons kebutuhan ruang belajar intensif menjelang UTS, Perpustakaan MNC University memperpanjang jam layanan hingga pukul 20.00 WIB.',
                'content' => "<p>Menyambut pekan Ujian Tengah Semester (UTS) Semester Ganjil Tahun Akademik 2026/2027, Unit Pelaksana Teknis Perpustakaan MNC University mengumumkan penyesuaian jam operasional serta penyediaan fasilitas belajar bersama.</p>\n\n<h3>Jadwal Layanan Periode UTS</h3>\n<ul>\n<li><strong>Senin – Jumat:</strong> 08.00 – 20.00 WIB (sebelumnya tutup pukul 17.00 WIB)</li>\n<li><strong>Sabtu:</strong> 09.00 – 16.00 WIB</li>\n<li><strong>Minggu & Hari Libur Nasional:</strong> Tutup</li>\n</ul>\n\n<h3>Pemanfaatan Smart Discussion Room & Wi-Fi Berkecepatan Tinggi</h3>\n<p>Lantai 2 dan 3 perpustakaan telah dilengkapi dengan peredam suara, stopkontak meja mandiri, serta koneksi Wi-Fi khusus literatur tanpa kuota. Mahasiswa diharapkan tetap menjaga ketertiban, menjaga kebersihan, dan tidak membawa makanan berat ke dalam ruang baca demi kenyamanan bersama.</p>",
                'author' => 'Bagian Tata Usaha Perpustakaan',
                'published_at' => now()->subDays(5),
                'views_count' => 520,
            ],
            [
                'title' => 'Koleksi 150+ Judul E-Book Terbaru Bidang AI, Data Science, dan Bisnis Digital Resmi Tersedia',
                'slug' => 'koleksi-ebook-terbaru-ai-data-science-bisnis-digital',
                'category' => 'Koleksi Baru',
                'excerpt' => 'Perpustakaan menambah koleksi pustaka digital edisi terkini dari penerbit ternama dunia yang dapat diakses penuh melalui perangkat apa pun.',
                'content' => "<p>Sebagai universitas yang berfokus pada inovasi teknologi dan industri kreatif, Perpustakaan MNC University melengkapi khazanah literatur digital dengan menambahkan lebih dari 150 judul e-book edisi 2025 dan 2026.</p>\n\n<h3>Fokus Topik Koleksi Terbaru:</h3>\n<ul>\n<li><strong>Artificial Intelligence & Machine Learning:</strong> Rekayasa prompt, LLM orchestration, etika AI, dan neural network frameworks.</li>\n<li><strong>Data Science & Big Data Analytics:</strong> Analisis prediktif, visualisasi data bisnis, dan komputasi awan.</li>\n<li><strong>Digital Business & Financial Technology:</strong> Manajemen produk digital, strategi e-commerce, dan keamanan siber perbankan.</li>\n</ul>\n\n<p>E-book dapat dipinjam secara digital dan dibaca secara luring (offline) hingga 14 hari melalui aplikasi pembaca perpustakaan yang kompatibel dengan laptop, tablet, dan smartphone.</p>",
                'author' => 'Divisi Pengembangan Koleksi',
                'published_at' => now()->subDays(9),
                'views_count' => 280,
            ],
            [
                'title' => 'Panduan Teknis Bebas Pustaka dan Penyerahan Mandiri Skripsi Mahasiswa Tingkat Akhir',
                'slug' => 'panduan-bebas-pustaka-dan-penyerahan-skripsi',
                'category' => 'Layanan',
                'excerpt' => 'Simak alur pengurusan surat keterangan bebas perpustakaan serta verifikasi format berkas naskah publikasi repository.',
                'content' => "<p>Bagi mahasiswa tingkat akhir yang tengah mempersiapkan wisuda periode Semester Ganjil 2026/2027, Surat Keterangan Bebas Pustaka (SKBP) menjadi syarat mutlak pendaftaran yudisium. Proses pengajuan kini dilakukan 100% daring melalui sistem informasi perpustakaan.</p>\n\n<h3>Tahapan Pengurusan SKBP:</h3>\n<ol>\n<li>Pastikan tidak memiliki tanggungan peminjaman buku fisik atau denda keterlambatan.</li>\n<li>Unggah file tugas akhir lengkap dalam format PDF sesuai panduan penamaan berkas di portal Repository MNC University.</li>\n<li>Isi formulir persetujuan publikasi karya ilmiah (open access atau restricted).</li>\n<li>Tunggu proses validasi pustakawan (maksimal 2x24 jam kerja).</li>\n</ol>\n\n<p>Surat keterangan resmi bertanda tangan digital dan kode QR verifikasi akan otomatis dikirimkan ke email terdaftar mahasiswa.</p>",
                'author' => 'Layanan Sirkulasi & Repository',
                'published_at' => now()->subDays(14),
                'views_count' => 895,
            ],
            [
                'title' => 'Pelatihan Mendeley Reference Manager & Cek Similaritas Turnitin untuk Karya Ilmiah Berkualitas',
                'slug' => 'pelatihan-mendeley-dan-turnitin-mahasiswa-tingkat-akhir',
                'category' => 'Literasi Informasi',
                'excerpt' => 'Tingkatkan integritas akademik skripsi dengan sitasi otomatis dan pemahaman batas toleransi cek plagiarisme.',
                'content' => "<p>Kerapian daftar pustaka dan orisinalitas ide merupakan tolak ukur integritas sebuah karya ilmiah. Dalam rangka mempersiapkan mahasiswa menulis proposal dan skripsi bebas plagiarisme, Perpustakaan MNC University menyelenggarakan kelas berkala literasi informasi.</p>\n\n<p>Materi yang disampaikan mencakup teknik sitasi standar APA 7th Edition dan IEEE, integrasi plugin Mendeley ke Microsoft Word, serta tips melakukan parafrasa kalimat ilmiah agar lolos uji Turnitin dengan nilai similaritas di bawah batas maksimum 20%.</p>",
                'author' => 'Koordinator Literasi Informasi',
                'published_at' => now()->subDays(20),
                'views_count' => 610,
            ],
            [
                'title' => 'Program Hibah Buku dan Donasi Koleksi Ilmiah Alumni MNC University Tahun Akademik 2026',
                'slug' => 'program-hibah-buku-alumni-mnc-university-2026',
                'category' => 'Pengumuman',
                'excerpt' => 'Perpustakaan membuka kesempatan bagi alumni dan mitra industri untuk mendonasikan literatur akademik demi penguatan khazanah perpustakaan.',
                'content' => "<p>Perpustakaan MNC University menerima sumbangan dan hibah buku teks, prosiding, maupun buku referensi dari para alumni dan praktisi industri. Donasi buku ini akan dikurasi dan dicatat ke dalam katalog OPAC dengan label khusus penghargaan donatur alumni.</p>\n\n<p>Pemberian donasi dapat diserahkan langsung ke meja sirkulasi atau dikirimkan melalui ekspedisi dengan konfirmasi sebelumnya kepada staf perpustakaan.</p>",
                'author' => 'Humas & Kemitraan Perpustakaan',
                'published_at' => now()->subDays(28),
                'views_count' => 175,
            ],
        ];

        foreach ($articles as $data) {
            Berita::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );
        }
    }
}
