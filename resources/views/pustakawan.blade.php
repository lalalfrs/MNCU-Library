@extends('layouts.app')

@section('title', 'Pustakawan')
@section('description', 'Daftar pustakawan dan staf Perpustakaan MNC University. Temukan kontak dan bidang keahlian setiap pustakawan.')

@section('content')
    <div class="mx-auto flex max-w-[1570px] flex-col gap-10 px-3 pt-4 sm:px-4 lg:gap-14 lg:px-6 xl:px-8 lg:pt-6 responsive-desktop-container">

        {{-- Page header --}}
        <section aria-labelledby="judul-pustakawan" class="px-1 lg:px-[26px]">
            <div class="flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
                <div>
                    <h1 id="judul-pustakawan" class="text-[28px] leading-tight font-semibold text-primary sm:text-3xl lg:text-4xl">
                        Pustakawan
                    </h1>
                    <p class="mt-2 text-sm leading-relaxed text-ink-secondary lg:text-base">
                        Tim pustakawan Perpustakaan MNC University yang siap membantu kebutuhan informasi Anda.
                    </p>
                </div>
            </div>
            <div class="mt-6 h-0.5 bg-primary"></div>
        </section>

        {{-- Kepala Perpustakaan --}}
        <section aria-labelledby="judul-kepala" class="rounded-2xl border border-line bg-white p-6 lg:p-8">
            <h2 id="judul-kepala" class="text-lg font-medium text-ink lg:text-2xl">Kepala Perpustakaan</h2>
            <p class="mt-1 text-sm text-muted lg:text-base">Pimpinan unit perpustakaan MNC University.</p>

            <div class="mt-6 flex flex-col items-center gap-6 sm:flex-row sm:items-start">
                <div class="flex size-28 shrink-0 items-center justify-center overflow-hidden rounded-2xl bg-primary/8 text-primary lg:size-32">
                    @if (isset($kepala) && !empty($kepala->foto_pustakawan))
                        <img src="{{ $kepala->foto_pustakawan }}" alt="{{ $kepala->nama_pustakawan }}" class="size-full object-cover">
                    @else
                        <svg class="size-14 lg:size-16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="12" cy="8" r="4" />
                            <path d="M20 21a8 8 0 0 0-16 0" />
                        </svg>
                    @endif
                </div>
                <div class="text-center sm:text-left">
                    <p class="text-xl font-medium text-ink lg:text-2xl">{{ $kepala->nama_pustakawan ?? 'Dr. H. Muhammad Arifin, M.Hum.' }}</p>
                    <p class="mt-1 text-sm text-muted lg:text-base">{{ $kepala->jabatan ?? 'Kepala Perpustakaan MNC University' }}</p>
                    <p class="mt-3 text-sm leading-relaxed text-ink-secondary lg:text-base">
                        {{ $kepala->deskripsi_pustakawan ?? 'Bertanggung jawab atas pengelolaan seluruh layanan perpustakaan, pengembangan koleksi, dan koordinasi kegiatan literasi informasi di lingkungan kampus.' }}
                    </p>
                    <div class="mt-4 flex flex-wrap justify-center gap-3 sm:justify-start">
                        <span class="inline-flex items-center gap-1.5 rounded-lg border border-line bg-subtle px-3 py-1.5 text-xs text-ink-secondary lg:text-sm">
                            <svg class="size-4 text-[#2563EB]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z M22 6l-10 7L2 6" />
                            </svg>
                            {{ $kepala->pengguna?->email ?? 'perpustakaan@mncu.ac.id' }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="mt-6 h-1 rounded-full bg-linear-to-r from-[#0EA5E9] to-[#2563EB]"></div>
        </section>

        {{-- Daftar pustakawan --}}
        <section aria-labelledby="judul-tim" class="rounded-2xl border border-line bg-white p-6 lg:p-8">
            <h2 id="judul-tim" class="text-lg font-medium text-ink lg:text-2xl">Tim Pustakawan</h2>
            <p class="mt-1 text-sm text-muted lg:text-base">Pustakawan yang melayani di berbagai bidang perpustakaan.</p>

            @php
                if (isset($staf) && $staf->isNotEmpty()) {
                    $accents = [
                        ['accentLine' => 'from-[#8B5CF6] to-[#D946EF]', 'iconBox' => 'bg-[#7C3AED]/8 text-[#7C3AED]'],
                        ['accentLine' => 'from-[#0EA5E9] to-[#2563EB]', 'iconBox' => 'bg-[#2563EB]/8 text-[#2563EB]'],
                        ['accentLine' => 'from-[#10B981] to-[#14B8A6]', 'iconBox' => 'bg-[#059669]/8 text-[#047857]'],
                        ['accentLine' => 'from-[#F43F5E] to-[#EC4899]', 'iconBox' => 'bg-[#E11D48]/8 text-[#E11D48]'],
                    ];
                    $librarians = $staf->map(function ($item, $idx) use ($accents) {
                        $style = $accents[$idx % count($accents)];
                        return [
                            'name' => $item->nama_pustakawan,
                            'role' => $item->jabatan ?? 'Pustakawan Layanan',
                            'description' => $item->deskripsi_pustakawan ?? 'Melayani pemustaka di Perpustakaan MNC University.',
                            'email' => $item->pengguna?->email ?? 'pustakawan@mncu.ac.id',
                            'accentLine' => $style['accentLine'],
                            'iconBox' => $style['iconBox'],
                        ];
                    })->all();
                } else {
                    $librarians = [
                        [
                            'name' => 'Sarah Amalia, S.I.Pust.',
                            'role' => 'Pustakawan Bidang Sirkulasi',
                            'description' => 'Menangani layanan peminjaman, pengembalian, dan perpanjangan buku.',
                            'email' => 'sarah.amalia@mncu.ac.id',
                            'accentLine' => 'from-[#8B5CF6] to-[#D946EF]',
                            'iconBox' => 'bg-[#7C3AED]/8 text-[#7C3AED]',
                        ],
                        [
                            'name' => 'Fajar Pratama, S.Kom.',
                            'role' => 'Pustakawan Bidang Teknologi Informasi',
                            'description' => 'Mengelola repository institusi, e-journal, e-book, dan sistem OPAC.',
                            'email' => 'fajar.pratama@mncu.ac.id',
                            'accentLine' => 'from-[#0EA5E9] to-[#2563EB]',
                            'iconBox' => 'bg-[#2563EB]/8 text-[#2563EB]',
                        ],
                        [
                            'name' => 'Dewi Sartika, S.Hum.',
                            'role' => 'Pustakawan Bidang Referensi & Literasi',
                            'description' => 'Membantu pencarian literatur, panduan sitasi, dan konsultasi penelitian.',
                            'email' => 'dewi.sartika@mncu.ac.id',
                            'accentLine' => 'from-[#10B981] to-[#14B8A6]',
                            'iconBox' => 'bg-[#059669]/8 text-[#047857]',
                        ],
                        [
                            'name' => 'Budi Santoso, S.Sos.',
                            'role' => 'Pustakawan Bidang Pengolahan & Preservasi',
                            'description' => 'Mengolah dan mengklasifikasikan koleksi baru, serta mengelola metadata katalog.',
                            'email' => 'budi.santoso@mncu.ac.id',
                            'accentLine' => 'from-[#F43F5E] to-[#EC4899]',
                            'iconBox' => 'bg-[#E11D48]/8 text-[#E11D48]',
                        ],
                    ];
                }
            @endphp

            <ul class="mt-5 grid gap-5 sm:grid-cols-2 lg:mt-6 lg:grid-cols-2 xl:grid-cols-4 lg:gap-5 xl:gap-6">
                @foreach ($librarians as $librarian)
                    <li class="flex flex-col rounded-xl border border-line bg-white p-5 transition duration-200 hover:shadow-raised motion-reduce:transition-none">
                        <div class="flex gap-4">
                            <span class="flex size-14 shrink-0 items-center justify-center rounded-xl {{ $librarian['iconBox'] }}">
                                <svg class="size-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <circle cx="12" cy="8" r="4" />
                                    <path d="M20 21a8 8 0 0 0-16 0" />
                                </svg>
                            </span>
                            <div class="min-w-0">
                                <span class="block text-base font-medium text-ink">{{ $librarian['name'] }}</span>
                                <span class="mt-0.5 block text-sm text-muted">{{ $librarian['role'] }}</span>
                            </div>
                        </div>
                        <p class="mt-3 flex-1 text-sm leading-relaxed text-ink-secondary">{{ $librarian['description'] }}</p>
                        <div class="mt-4 flex min-w-0 items-center gap-1.5 text-xs text-ink-secondary lg:text-sm">
                            <svg class="size-4 shrink-0 text-muted" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z M22 6l-10 7L2 6" />
                            </svg>
                            <span class="truncate">{{ $librarian['email'] }}</span>
                        </div>
                        <div class="mt-4 h-1 rounded-full bg-linear-to-r {{ $librarian['accentLine'] }}"></div>
                    </li>
                @endforeach
            </ul>

            <div class="mt-6 h-1 rounded-full bg-track"></div>
        </section>

        {{-- Layanan konsultasi --}}
        <section aria-labelledby="judul-konsultasi" class="rounded-2xl border border-line bg-white p-6 lg:p-8">
            <div class="flex flex-wrap items-end justify-between gap-3">
                <div>
                    <h2 id="judul-konsultasi" class="text-lg font-medium text-ink lg:text-2xl">Layanan Konsultasi</h2>
                    <p class="mt-1 text-sm text-muted lg:text-base">Jadwalkan sesi konsultasi dengan pustakawan.</p>
                </div>
            </div>

            <div class="mt-5 grid gap-5 sm:grid-cols-3 lg:mt-6 lg:gap-6">
                @php
                    $consultations = [
                        [
                            'title' => 'Pencarian Literatur',
                            'badge' => 'Riset & Skripsi',
                            'badgeStyle' => 'bg-[#7C3AED]/10 text-[#7C3AED]',
                            'description' => 'Bantuan penelusuran referensi ilmiah, jurnal terindeks, dan buku penunjang tugas akhir atau riset.',
                            'icon' => 'M11 17.25a6.25 6.25 0 1 1 0-12.5 6.25 6.25 0 0 1 0 12.5z M16 16l4.5 4.5',
                            'iconBox' => 'bg-[#7C3AED]/8 text-[#7C3AED]',
                        ],
                        [
                            'title' => 'Panduan Sitasi & Referensi',
                            'badge' => 'Format & Sitasi',
                            'badgeStyle' => 'bg-[#2563EB]/10 text-[#2563EB]',
                            'description' => 'Konsultasi gaya sitasi standar (APA, IEEE, Chicago) serta penggunaan Mendeley dan Zotero.',
                            'icon' => 'M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z M14 2v6h6 M16 13H8 M16 17H8 M10 9H8',
                            'iconBox' => 'bg-[#2563EB]/8 text-[#2563EB]',
                        ],
                        [
                            'title' => 'Uji Similaritas (Turnitin)',
                            'badge' => 'Integritas Ilmiah',
                            'badgeStyle' => 'bg-[#059669]/10 text-[#047857]',
                            'description' => 'Pemeriksaan skor kemiripan naskah skripsi/jurnal via Turnitin serta panduan parafrasa naskah.',
                            'icon' => 'M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z M9 12l2 2 4-4',
                            'iconBox' => 'bg-[#059669]/8 text-[#047857]',
                        ],
                    ];
                @endphp

                @foreach ($consultations as $consultation)
                    <div class="flex flex-col items-center rounded-xl border border-line bg-white p-5 text-center transition duration-200 hover:-translate-y-0.5 hover:shadow-raised motion-reduce:transition-none motion-reduce:hover:translate-y-0">
                        <div class="flex w-full items-center justify-between">
                            <span class="rounded-md px-2 py-0.5 text-[11px] font-medium {{ $consultation['badgeStyle'] }}">
                                {{ $consultation['badge'] }}
                            </span>
                            <span class="text-xs text-muted">Daring / Luring</span>
                        </div>

                        <span class="mt-3 flex size-14 items-center justify-center rounded-xl {{ $consultation['iconBox'] }}">
                            <svg class="size-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="{{ $consultation['icon'] }}" />
                            </svg>
                        </span>
                        <span class="mt-3 block text-base font-medium text-ink lg:text-lg">{{ $consultation['title'] }}</span>
                        <span class="mt-1.5 block text-sm leading-relaxed text-muted">{{ $consultation['description'] }}</span>

                        <div class="mt-auto pt-4">
                            <a href="{{ route('kontak') }}" class="inline-flex items-center gap-1 text-xs font-semibold text-primary transition-colors hover:text-primary-dark hover:underline">
                                Jadwalkan Sesi
                                <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M5 12h14 M12 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6 rounded-xl border border-line bg-subtle p-5">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h3 class="text-sm font-semibold text-ink lg:text-base">Butuh Sesi Konsultasi Khusus?</h3>
                        <p class="mt-0.5 text-xs text-muted lg:text-sm">Konsultasi dapat dilakukan secara luring di Ruang Referensi Perpustakaan maupun daring melalui Zoom / Google Meet.</p>
                    </div>
                    <a href="{{ route('kontak') }}" class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-primary px-4 py-2.5 text-xs font-semibold text-white transition-colors duration-150 hover:bg-primary-dark lg:text-sm">
                        <span>Hubungi Pustakawan</span>
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M5 12h14 M12 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>

            <div class="mt-6 h-1 rounded-full bg-linear-to-r from-[#8B5CF6] to-[#14B8A6]"></div>
        </section>
    </div>
@endsection
