<?php

namespace Database\Seeders;

use App\Models\Acara;
use Illuminate\Database\Seeder;

class AcaraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $events = [
            [
                'title' => 'Workshop Strategi Penelusuran Literatur Ilmiah & Mendeley Mastery',
                'slug' => 'workshop-penelusuran-literatur-ilmiah-mendeley-mastery',
                'category' => 'Literasi Informasi',
                'excerpt' => 'Pelajari teknik pencarian database ilmiah bereputasi, operator boolean, dan manajemen sitasi otomatis untuk tugas akhir.',
                'description' => "<p>Perpustakaan MNC University menyelenggarakan workshop intensif bagi mahasiswa dan dosen yang sedang mempersiapkan penulisan proposal, skripsi, dan artikel ilmiah.</p>\n\n<h3>Pokok Pembahasan:</h3>\n<ul>\n<li>Teknik Boolean Search (AND, OR, NOT) dan pemanfaatan wildcard di ScienceDirect & IEEE Xplore.</li>\n<li>Manajemen folder referensi, metadata deduplication, dan anotasi PDF di Mendeley.</li>\n<li>Integrasi sitasi otomatis gaya APA, Harvard, dan IEEE pada naskah dokumen.</li>\n</ul>\n\n<p><strong>Fasilitas:</strong> Sertifikat elektronik, modul materi, dan bimbingan langsung dari instruktur perpustakaan.</p>",
                'speaker' => 'Dr. Hendra Gunawan, M.Hum & Tim Pustakawan MNC University',
                'event_date' => now()->addDays(4)->toDateString(),
                'start_time' => '09:30',
                'end_time' => '12:00',
                'location' => 'Ruang Seminar Perpustakaan Lt. 2 / Hybrid Zoom',
                'registration_link' => 'https://forms.gle/mncu-workshop-literasi',
                'quota' => 80,
                'is_active' => true,
            ],
            [
                'title' => 'Bedah Buku: AI and The Future of Creative Economy bersama Praktisi MNC Group',
                'slug' => 'bedah-buku-ai-future-creative-economy',
                'category' => 'Bedah Buku',
                'excerpt' => 'Diskusi mendalam mengenai implementasi kecerdasan buatan dalam industri pertelevisian, konten digital, dan ekonomi kreatif.',
                'description' => "<p>Kecerdasan buatan telah meredefinisi lanskap industri kreatif global. Dalam acara bedah buku ini, kami menghadirkan praktisi teknologi dari ekosistem MNC Group untuk mengupas tuntas masa depan industri kreatif di era generative AI.</p>\n\n<h3>Agenda Kegiatan:</h3>\n<ul>\n<li>Pemaparan isi buku dan tren teknologi AI mutakhir di industri media.</li>\n<li>Sesi tanya jawab interaktif dan studi kasus produksi konten berbasis AI.</li>\n<li>Pembagian 5 eksemplar buku bertanda tangan penulis untuk penanya terbaik.</li>\n</ul>",
                'speaker' => 'Rian Pratama, M.Sc (Head of Digital Innovation MNC) & Penulis',
                'event_date' => now()->addDays(9)->toDateString(),
                'start_time' => '13:30',
                'end_time' => '16:00',
                'location' => 'Auditorium MNC University Lt. 5',
                'registration_link' => 'https://forms.gle/mncu-bedah-buku-ai',
                'quota' => 150,
                'is_active' => true,
            ],
            [
                'title' => 'Library Onboarding & Safari Literasi Digital Mahasiswa Baru 2026',
                'slug' => 'library-onboarding-safari-literasi-digital-2026',
                'category' => 'Onboarding',
                'excerpt' => 'Pengenalan fasilitas peminjaman mandiri RFID, aktivasi akun e-library mobile, dan ruang belajar multimedia.',
                'description' => "<p>Kegiatan pengenalan perpustakaan bagi mahasiswa baru MNC University untuk mengenal seluruh fasilitas, tata tertib peminjaman, dan akses koleksi digital sejak semester pertama.</p>\n\n<h3>Rangkaian Safari:</h3>\n<ul>\n<li>Tur keliling ruang baca, smart discussion room, dan pojok koleksi audio-visual.</li>\n<li>Simulasi mesin peminjaman dan pengembalian mandiri berbasis RFID.</li>\n<li>Aktivasi akun aplikasi baca e-book di smartphone mahasiswa.</li>\n</ul>",
                'speaker' => 'Pustakawan Layanan Pengguna MNC University',
                'event_date' => now()->addDays(15)->toDateString(),
                'start_time' => '10:00',
                'end_time' => '11:45',
                'location' => 'Lobby Utama Perpustakaan MNC University',
                'registration_link' => null,
                'quota' => 100,
                'is_active' => true,
            ],
            [
                'title' => 'Klinik Penulisan Artikel Ilmiah: Menembus Jurnal Terindeks SINTA 2 & Scopus',
                'slug' => 'klinik-penulisan-artikel-ilmiah-sinta-scopus',
                'category' => 'Klinik Publikasi',
                'excerpt' => 'Bimbingan intensif penyusunan struktur naskah IMRAD, pemilihan target jurnal yang tepat, dan respon terhadap reviewer.',
                'description' => "<p>Klinik penulisan ini dirancang khusus bagi dosen muda dan mahasiswa tingkat akhir yang ingin mempublikasikan naskah risetnya ke jurnal nasional terakreditasi SINTA 2 maupun jurnal internasional terindeks Scopus.</p>\n\n<p>Peserta dipersilakan membawa draf artikel masing-masing untuk direview langsung oleh narasumber reviewer jurnal nasional.</p>",
                'speaker' => 'Prof. Dr. Ir. Wahyudi, M.Kom (Editor-in-Chief Jurnal Terindeks SINTA)',
                'event_date' => now()->addDays(22)->toDateString(),
                'start_time' => '09:00',
                'end_time' => '15:00',
                'location' => 'Ruang Komputer Riset Lt. 3',
                'registration_link' => 'https://forms.gle/mncu-klinik-jurnal',
                'quota' => 40,
                'is_active' => true,
            ],
        ];

        foreach ($events as $data) {
            Acara::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );
        }
    }
}
