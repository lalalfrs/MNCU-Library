@extends('layouts.app')

@section('title', 'Beranda')
@section('description', 'Beranda Perpustakaan MNC University. Akses katalog OPAC, repository, e-journal, OJS per prodi, dan e-book dari satu halaman.')

@section('content')
    @php
        /**
         * Layanan di bawah kartu utama (Katalog OPAC). Warna aksen mengikuti tabel §2.1 spesifikasi.
         *
         * @var array<int, array{title: string, description: string, href: string, isExternal: bool, faIcon: string, iconBox: string, linkColor: string, hoverBorder: string, accentLine: string, typeBadge: string}> $digitalServices
         */
        $digitalServices = [
            [
                'title' => 'Repository',
                'description' => 'Skripsi, tesis, dan karya ilmiah sivitas MNC University.',
                'href' => route('repository'),
                'isExternal' => false,
                'faIcon' => 'fa-solid fa-graduation-cap',
                'cardBg' => 'bg-white',
                'cardBorder' => 'border-purple-200 hover:border-purple-400',
                'cardShadow' => 'hover:shadow-lg hover:shadow-purple-500/10',
                'iconBox' => 'bg-purple-100 text-purple-700 border border-purple-200',
                'iconStyle' => 'background-color: #f3e8ff; color: #7c3aed;',
                'topBarStyle' => 'background: linear-gradient(to right, #7c3aed, #d946ef);',
                'tagBadge' => 'bg-purple-100 text-purple-800 border border-purple-200',
                'titleColor' => 'group-hover:text-purple-700',
                'linkColor' => 'text-purple-700',
                'typeBadge' => 'Karya Ilmiah',
            ],
            [
                'title' => 'E-Journal',
                'description' => 'Jurnal elektronik yang dilanggan perpustakaan.',
                'href' => route('ejournal'),
                'isExternal' => false,
                'faIcon' => 'fa-solid fa-newspaper',
                'cardBg' => 'bg-white',
                'cardBorder' => 'border-blue-200 hover:border-blue-400',
                'cardShadow' => 'hover:shadow-lg hover:shadow-blue-500/10',
                'iconBox' => 'bg-blue-100 text-blue-700 border border-blue-200',
                'iconStyle' => 'background-color: #dbeafe; color: #2563eb;',
                'topBarStyle' => 'background: linear-gradient(to right, #0ea5e9, #2563eb);',
                'tagBadge' => 'bg-blue-100 text-blue-800 border border-blue-200',
                'titleColor' => 'group-hover:text-blue-700',
                'linkColor' => 'text-blue-700',
                'typeBadge' => 'Jurnal Online',
            ],
            [
                'title' => 'OJS per Prodi',
                'description' => 'Pilih program studi untuk membuka jurnal OJS-nya.',
                'href' => route('ojs'),
                'isExternal' => false,
                'faIcon' => 'fa-solid fa-layer-group',
                'cardBg' => 'bg-white',
                'cardBorder' => 'border-emerald-200 hover:border-emerald-400',
                'cardShadow' => 'hover:shadow-lg hover:shadow-emerald-500/10',
                'iconBox' => 'bg-emerald-100 text-emerald-700 border border-emerald-200',
                'iconStyle' => 'background-color: #d1fae5; color: #047857;',
                'topBarStyle' => 'background: linear-gradient(to right, #10b981, #14b8a6);',
                'tagBadge' => 'bg-emerald-100 text-emerald-800 border border-emerald-200',
                'titleColor' => 'group-hover:text-emerald-700',
                'linkColor' => 'text-emerald-700',
                'typeBadge' => 'Portal OJS',
            ],
            [
                'title' => 'E-Book',
                'description' => 'Buku elektronik yang bisa dibaca dari perangkat apa pun.',
                'href' => route('ebook'),
                'isExternal' => false,
                'faIcon' => 'fa-solid fa-tablet-screen-button',
                'cardBg' => 'bg-white',
                'cardBorder' => 'border-rose-200 hover:border-rose-400',
                'cardShadow' => 'hover:shadow-lg hover:shadow-rose-500/10',
                'iconBox' => 'bg-rose-100 text-rose-700 border border-rose-200',
                'iconStyle' => 'background-color: #ffe4e6; color: #e11d48;',
                'topBarStyle' => 'background: linear-gradient(to right, #f43f5e, #ec4899);',
                'tagBadge' => 'bg-rose-100 text-rose-800 border border-rose-200',
                'titleColor' => 'group-hover:text-rose-700',
                'linkColor' => 'text-rose-700',
                'typeBadge' => 'Buku Digital',
            ],
        ];

        /**
         * Koleksi berita & agenda acara dari database. Jika kosong, blok @empty yang tampil.
         *
         * @var array<int, array{title: string, excerpt: string, date: string}> $latestNews
         */
        $latestNews = $latestNews ?? [];
        $upcomingEvents = $upcomingEvents ?? [];

        $visitorStats = $visitorStats ?? [
            'today' => 0,
            'month' => 0,
            'total' => 0,
        ];

        $heroPromoSlides = [];
        foreach (array_slice($latestNews, 0, 2) as $newsItem) {
            $rawNewsImg = $newsItem['image'] ?? '';
            $heroPromoSlides[] = [
                'badge' => 'Berita Perpustakaan',
                'date' => $newsItem['date'] ?? now()->translatedFormat('d M Y'),
                'title' => $newsItem['title'] ?? 'Berita Perpustakaan MNC University',
                'excerpt' => $newsItem['excerpt'] ?? 'Informasi terbaru seputar layanan dan koleksi Perpustakaan MNC University.',
                'image' => !empty($rawNewsImg) ? (filter_var($rawNewsImg, FILTER_VALIDATE_URL) ? $rawNewsImg : asset($rawNewsImg)) : asset('images/berita/berita-digital-library.jpg'),
                'url' => !empty($newsItem['slug']) ? route('berita.show', $newsItem['slug']) : '#berita-acara',
                'cta' => 'Baca Selengkapnya',
            ];
        }
        foreach (array_slice($upcomingEvents, 0, 2) as $eventItem) {
            $rawEventImg = $eventItem['image'] ?? ($eventItem['poster_url'] ?? '');
            $heroPromoSlides[] = [
                'badge' => 'Agenda Perpustakaan',
                'date' => $eventItem['date'] ?? now()->translatedFormat('d M Y'),
                'title' => $eventItem['title'] ?? 'Agenda Kegiatan Perpustakaan',
                'excerpt' => !empty($eventItem['excerpt'])
                    ? $eventItem['excerpt']
                    : 'Ikuti kegiatan literasi dan akademik di ' . ($eventItem['place'] ?? 'Perpustakaan MNC University') . (!empty($eventItem['time']) ? ' pada pukul ' . $eventItem['time'] . '.' : '.'),
                'image' => !empty($rawEventImg) ? (filter_var($rawEventImg, FILTER_VALIDATE_URL) ? $rawEventImg : asset($rawEventImg)) : asset('images/acara/acara-bedah-buku-ai.jpg'),
                'url' => !empty($eventItem['slug']) ? route('acara.show', $eventItem['slug']) : '#berita-acara',
                'cta' => 'Baca Selengkapnya',
            ];
        }
        if (count($heroPromoSlides) < 4) {
            foreach (array_slice($latestNews, 2) as $extraNews) {
                if (count($heroPromoSlides) >= 4) {
                    break;
                }
                $rawExtraImg = $extraNews['image'] ?? '';
                $heroPromoSlides[] = [
                    'badge' => 'Berita Perpustakaan',
                    'date' => $extraNews['date'] ?? now()->translatedFormat('d M Y'),
                    'title' => $extraNews['title'] ?? 'Berita Perpustakaan',
                    'excerpt' => $extraNews['excerpt'] ?? 'Informasi terbaru Perpustakaan MNC University.',
                    'image' => !empty($rawExtraImg) ? (filter_var($rawExtraImg, FILTER_VALIDATE_URL) ? $rawExtraImg : asset($rawExtraImg)) : asset('images/berita/berita-workshop-sitasi.jpg'),
                    'url' => !empty($extraNews['slug']) ? route('berita.show', $extraNews['slug']) : '#berita-acara',
                    'cta' => 'Baca Selengkapnya',
                ];
            }
        }
        $fallbackHeroSlides = [
            [
                'badge' => 'Berita Perpustakaan',
                'date' => '24 Okt 2026',
                'title' => 'TRANSFORMASI LAYANAN PERPUSTAKAAN DIGITAL MNC UNIVERSITY DI ERA KECERDASAN BUATAN',
                'excerpt' => 'JAKARTA – Perpustakaan MNC University terus memperkuat ekosistem literasi digital melalui integrasi katalog OPAC terpadu, akses e-resource global, dan pendampingan riset akademik.',
                'image' => asset('images/berita/berita-digital-library.jpg'),
                'url' => '#berita-acara',
                'cta' => 'Baca Selengkapnya',
            ],
            [
                'badge' => 'Berita Perpustakaan',
                'date' => '18 Okt 2026',
                'title' => 'WORKSHOP SITASI ILMIAH & MANAJEMEN REFERENSI MENDELEY BAGI MAHASISWA TUGAS AKHIR',
                'excerpt' => 'JAKARTA – Tingkatkan kualitas publikasi dan penulisan skripsi bersama tim pustakawan melalui sesi klinik sitasi ilmiah, penelusuran jurnal terindeks, dan pencegahan plagiarisme.',
                'image' => asset('images/berita/berita-workshop-sitasi.jpg'),
                'url' => '#berita-acara',
                'cta' => 'Baca Selengkapnya',
            ],
            [
                'badge' => 'Agenda Perpustakaan',
                'date' => '05 Nov 2026',
                'title' => 'BEDAH BUKU & DISKUSI LITERASI: ARTIFICIAL INTELLIGENCE UNTUK RISET AKADEMIK MODERN',
                'excerpt' => 'Mari bergabung dalam forum diskusi interaktif membedah pemanfaatan teknologi kecerdasan buatan secara etis dan produktif dalam menyusun kajian pustaka.',
                'image' => asset('images/acara/acara-bedah-buku-ai.jpg'),
                'url' => '#berita-acara',
                'cta' => 'Baca Selengkapnya',
            ],
            [
                'badge' => 'Berita Perpustakaan',
                'date' => '12 Nov 2026',
                'title' => 'PENAMBAHAN KOLEKSI E-BOOK INTERNASIONAL & LITERATUR KECERDASAN BUATAN TERBARU',
                'excerpt' => 'Kini tersedia ratusan judul buku elektronik terbaru lintas program studi yang dapat diakses langsung oleh seluruh sivitas akademika MNC University.',
                'image' => asset('images/berita/berita-ebook-ai.jpg'),
                'url' => '#berita-acara',
                'cta' => 'Baca Selengkapnya',
            ],
        ];
        foreach ($fallbackHeroSlides as $fallbackSlide) {
            if (count($heroPromoSlides) >= 4) {
                break;
            }
            $heroPromoSlides[] = $fallbackSlide;
        }
        $totalHeroSlides = 1 + count($heroPromoSlides);
        $lastPromoSlide = $heroPromoSlides[count($heroPromoSlides) - 1];
    @endphp

    <div class="mx-auto flex w-full min-w-0 max-w-[1570px] flex-col gap-8 sm:gap-10 lg:gap-12 px-3 sm:px-4 lg:px-6 xl:px-8 pt-4 sm:pt-6 lg:pt-8 responsive-desktop-container">

        {{-- Page header / Hero Carousel (Widyatama Peeking Card Style) --}}
        <section aria-labelledby="judul-beranda" class="w-full min-w-0">
            <div id="hero-widyatama-carousel" class="relative w-full overflow-hidden py-2 select-none" aria-roledescription="carousel" aria-label="Sorotan Utama Perpustakaan MNC University">

                {{-- Tombol Navigasi Kiri (<) tepat di batas kartu putih utama --}}
                <button
                    type="button"
                    id="hero-widyatama-prev"
                    aria-label="Slide sebelumnya"
                    class="btn-press absolute left-1.5 sm:left-3 lg:left-[4%] lg:-translate-x-1/2 top-1/2 -translate-y-1/2 z-30 flex size-10 sm:size-12 items-center justify-center rounded-full border border-slate-200/90 bg-white text-slate-700 shadow-[0_6px_20px_rgba(15,23,42,0.12)] transition-all duration-200 hover:scale-105 hover:border-primary/40 hover:text-primary focus:outline-none focus:ring-2 focus:ring-primary/30 cursor-pointer"
                >
                    <i class="fa-solid fa-chevron-left text-xs sm:text-sm" aria-hidden="true"></i>
                </button>

                {{-- Tombol Navigasi Kanan (>) tepat di batas kartu putih utama --}}
                <button
                    type="button"
                    id="hero-widyatama-next"
                    aria-label="Slide berikutnya"
                    class="btn-press absolute right-1.5 sm:right-3 lg:right-[4%] lg:translate-x-1/2 top-1/2 -translate-y-1/2 z-30 flex size-10 sm:size-12 items-center justify-center rounded-full border border-slate-200/90 bg-white text-slate-700 shadow-[0_6px_20px_rgba(15,23,42,0.12)] transition-all duration-200 hover:scale-105 hover:border-primary/40 hover:text-primary focus:outline-none focus:ring-2 focus:ring-primary/30 cursor-pointer"
                >
                    <i class="fa-solid fa-chevron-right text-xs sm:text-sm" aria-hidden="true"></i>
                </button>

                {{-- Track Slide (Clone Last + Slide 0..N + Clone First agar kartu kiri & kanan selalu mengintip) --}}
                <div id="hero-widyatama-track" class="flex items-stretch gap-3.5 sm:gap-5 lg:gap-6 transition-transform duration-500 ease-out will-change-transform">

                    {{-- CLONE DARI SLIDE TERAKHIR (Untuk efek mengintip di sisi kiri saat berada di Slide 1) --}}
                    <article data-hero-clone="last" aria-hidden="true" class="hero-widyatama-card w-[90%] sm:w-[91%] lg:w-[92%] shrink-0 rounded-[26px] sm:rounded-[30px] border border-slate-200/80 bg-white p-5 sm:p-8 lg:px-12 lg:py-10 shadow-[0_8px_30px_rgba(15,23,42,0.05)] opacity-60 transition-opacity duration-300">
                        <div class="grid h-full grid-cols-1 items-center gap-6 lg:grid-cols-12 lg:gap-10">
                            <div class="flex flex-col justify-center lg:col-span-7">
                                <div class="mb-4 flex flex-wrap items-center gap-3">
                                    <span class="inline-flex items-center gap-2 rounded-full bg-[#f1f5f9] px-3.5 py-1.5 text-xs font-bold text-[#0f2a6b]">
                                        <span class="size-2 rounded-full bg-[#f59e0b]"></span>
                                        {{ $lastPromoSlide['badge'] }}
                                    </span>
                                    <span class="inline-flex items-center gap-1.5 text-xs font-medium text-slate-400">
                                        <i class="fa-regular fa-calendar text-[11px]" aria-hidden="true"></i>
                                        {{ $lastPromoSlide['date'] }}
                                    </span>
                                </div>
                                <h2 class="text-xl sm:text-2xl lg:text-[28px] xl:text-[32px] font-extrabold uppercase tracking-tight text-[#0b1f3a] leading-[1.22] line-clamp-3">
                                    {{ $lastPromoSlide['title'] }}
                                </h2>
                                <p class="mt-3.5 text-xs sm:text-sm lg:text-[15px] leading-[1.7] text-slate-600 line-clamp-3">
                                    {{ $lastPromoSlide['excerpt'] }}
                                </p>
                                <div class="mt-6 sm:mt-7">
                                    <span class="inline-flex items-center gap-2.5 rounded-full bg-[#0b2a5b] px-6 py-3 text-xs sm:text-sm font-bold text-white shadow-sm">
                                        <span>{{ $lastPromoSlide['cta'] }}</span>
                                        <i class="fa-solid fa-arrow-right text-xs text-[#f59e0b]" aria-hidden="true"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="flex items-center justify-center lg:col-span-5 lg:justify-end">
                                <div class="w-full max-w-[290px] sm:max-w-[330px] lg:max-w-[360px] aspect-square rounded-[22px] overflow-hidden bg-slate-100 border border-slate-200/80 shadow-sm">
                                    <img
                                        src="{{ $lastPromoSlide['image'] }}"
                                        alt=""
                                        class="h-full w-full object-cover"
                                        loading="lazy"
                                        onerror="this.onerror=null; this.src='{{ asset('images/berita/berita-digital-library.jpg') }}';"
                                    >
                                </div>
                            </div>
                        </div>
                    </article>

                    {{-- SLIDE 1 (INDEX 0): SELAMAT DATANG & REKAP PENGUNJUNG --}}
                    <article data-hero-slide="0" class="hero-widyatama-card relative overflow-hidden w-[90%] sm:w-[91%] lg:w-[92%] shrink-0 rounded-[26px] sm:rounded-[30px] border border-slate-200/80 bg-white p-5 sm:p-8 lg:px-12 lg:py-10 shadow-[0_8px_30px_rgba(15,23,42,0.05)] transition-opacity duration-300">
                        {{-- Siluet Watermark Logo MNC University di Belakang Kartu --}}
                        <div class="pointer-events-none select-none absolute -left-8 -bottom-10 sm:left-6 sm:-bottom-12 lg:left-[26%] lg:top-1/2 lg:-translate-y-1/2 z-0" aria-hidden="true">
                            <img
                                src="{{ asset('images/LogoTXT.png') }}"
                                alt=""
                                class="w-[260px] sm:w-[340px] lg:w-[420px] max-w-none object-contain opacity-[0.045] grayscale contrast-125 -rotate-6"
                            >
                        </div>

                        <div class="relative z-10 grid h-full grid-cols-1 items-center gap-6 lg:grid-cols-12 lg:gap-10">
                            {{-- Kolom Kiri: Sambutan Sesuai Peran User --}}
                            <div class="flex flex-col justify-center lg:col-span-7">
                                <div class="mb-3.5 flex flex-wrap items-center gap-3">
                                    <span class="inline-flex items-center gap-2 rounded-full bg-[#f1f5f9] px-3.5 py-1.5 text-xs font-bold text-[#0f2a6b]">
                                        <span class="size-2 rounded-full bg-[#f59e0b]"></span>
                                        Sambutan Perpustakaan
                                    </span>
                                    <span class="inline-flex items-center gap-1.5 text-xs font-medium text-slate-400">
                                        <i class="fa-regular fa-calendar text-[11px]" aria-hidden="true"></i>
                                        {{ now()->translatedFormat('d M Y') }}
                                    </span>
                                </div>

                                @auth
                                    @if (Auth::user()->isSuperAdmin() || Auth::user()->isPustakawan())
                                        <h1 id="judul-beranda" class="text-xl sm:text-2xl lg:text-[28px] xl:text-[32px] font-extrabold uppercase tracking-tight text-[#0b1f3a] leading-[1.22]">
                                            Selamat Bertugas, {{ Auth::user()->nama_lengkap }}!
                                        </h1>
                                        <p class="mt-3.5 text-xs sm:text-sm lg:text-[15px] leading-[1.7] text-slate-600">
                                            Kelola operasional dan sirkulasi Perpustakaan MNC University. Pantau statistik kunjungan harian pemustaka, perbarui katalog koleksi buku, publikasikan agenda acara, dan kelola rilis berita kampus.
                                        </p>
                                        <div class="mt-6 sm:mt-7 flex flex-col items-start gap-2.5 sm:gap-3">
                                            <div class="flex flex-wrap items-center gap-2.5 sm:gap-3">
                                                <a href="{{ route('admin.books.index') }}" class="btn-press inline-flex items-center gap-2.5 rounded-full bg-[#0b2a5b] hover:bg-primary-dark px-6 py-3 text-xs sm:text-sm font-bold text-white shadow-sm transition-all">
                                                    <span>Kelola Buku (OPAC)</span>
                                                    <i class="fa-solid fa-arrow-right text-xs text-[#f59e0b]" aria-hidden="true"></i>
                                                </a>
                                                <a href="{{ route('admin.acara.index') }}" class="btn-press inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2.5 text-xs sm:text-sm font-semibold text-slate-700 transition-all hover:border-primary/40 hover:text-primary">
                                                    <i class="fa-solid fa-calendar-plus text-primary text-xs" aria-hidden="true"></i>
                                                    <span>Kelola Acara &amp; Poster</span>
                                                </a>
                                                <a href="{{ route('admin.users.index') }}" class="btn-press inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2.5 text-xs sm:text-sm font-semibold text-slate-700 transition-all hover:border-primary/40 hover:text-primary">
                                                    <i class="fa-solid fa-users-gear text-primary text-xs" aria-hidden="true"></i>
                                                    <span>Data Pemustaka</span>
                                                </a>
                                            </div>
                                            <div class="flex flex-wrap items-center gap-2.5 sm:gap-3">
                                                <a href="{{ route('kehadiran.index') }}" class="btn-press inline-flex items-center gap-2.5 rounded-full bg-[#0b2a5b] hover:bg-primary-dark px-6 py-3 text-xs sm:text-sm font-bold text-white shadow-sm transition-all">
                                                    <i class="fa-solid fa-clipboard-user text-xs text-[#f59e0b]" aria-hidden="true"></i>
                                                    <span>Isi Kehadiran Pengunjung</span>
                                                    <i class="fa-solid fa-arrow-right text-xs text-[#f59e0b]" aria-hidden="true"></i>
                                                </a>
                                            </div>
                                        </div>
                                    @elseif (Auth::user()->isDosen())
                                        <h1 id="judul-beranda" class="text-xl sm:text-2xl lg:text-[28px] xl:text-[32px] font-extrabold uppercase tracking-tight text-[#0b1f3a] leading-[1.22]">
                                            Selamat Datang, {{ Auth::user()->nama_lengkap }}!
                                        </h1>
                                        <p class="mt-3.5 text-xs sm:text-sm lg:text-[15px] leading-[1.7] text-slate-600">
                                            Akses seluruh fasilitas referensi riset, jurnal internasional terakreditasi Scopus &amp; SINTA, portal publikasi ilmiah OJS MNC University, serta ajukan usulan pengadaan buku ajar silabus perkuliahan.
                                        </p>
                                        <div class="mt-6 sm:mt-7 flex flex-col items-start gap-2.5 sm:gap-3">
                                            <div class="flex flex-wrap items-center gap-2.5 sm:gap-3">
                                                <a href="{{ route('ejournal') }}" class="btn-press inline-flex items-center gap-2.5 rounded-full bg-[#0b2a5b] hover:bg-primary-dark px-6 py-3 text-xs sm:text-sm font-bold text-white shadow-sm transition-all">
                                                    <span>E-Journal Internasional</span>
                                                    <i class="fa-solid fa-arrow-right text-xs text-[#f59e0b]" aria-hidden="true"></i>
                                                </a>
                                                <a href="{{ route('ojs') }}" class="btn-press inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2.5 text-xs sm:text-sm font-semibold text-slate-700 transition-all hover:border-primary/40 hover:text-primary">
                                                    <i class="fa-solid fa-layer-group text-primary text-xs" aria-hidden="true"></i>
                                                    <span>Portal OJS Prodi</span>
                                                </a>
                                                <a href="{{ route('repository') }}" class="btn-press inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2.5 text-xs sm:text-sm font-semibold text-slate-700 transition-all hover:border-primary/40 hover:text-primary">
                                                    <i class="fa-solid fa-book-atlas text-primary text-xs" aria-hidden="true"></i>
                                                    <span>Repository Riset</span>
                                                </a>
                                            </div>
                                            <div class="flex flex-wrap items-center gap-2.5 sm:gap-3">
                                                <a href="{{ route('kehadiran.index') }}" class="btn-press inline-flex items-center gap-2.5 rounded-full bg-[#0b2a5b] hover:bg-primary-dark px-6 py-3 text-xs sm:text-sm font-bold text-white shadow-sm transition-all">
                                                    <i class="fa-solid fa-clipboard-user text-xs text-[#f59e0b]" aria-hidden="true"></i>
                                                    <span>Isi Kehadiran Pengunjung</span>
                                                    <i class="fa-solid fa-arrow-right text-xs text-[#f59e0b]" aria-hidden="true"></i>
                                                </a>
                                            </div>
                                        </div>
                                    @else
                                        <h1 id="judul-beranda" class="text-xl sm:text-2xl lg:text-[28px] xl:text-[32px] font-extrabold uppercase tracking-tight text-[#0b1f3a] leading-[1.22]">
                                            Selamat Datang, {{ Auth::user()->nama_lengkap }}!
                                        </h1>
                                        <p class="mt-3.5 text-xs sm:text-sm lg:text-[15px] leading-[1.7] text-slate-600">
                                            Temukan referensi tugas akhir, buku teks perkuliahan, dan e-book pembelajaran untuk mendukung studimu di MNC University. Jelajahi katalog buku fisik OPAC dan baca koleksi digital kapan saja.
                                        </p>
                                        <div class="mt-6 sm:mt-7 flex flex-col items-start gap-2.5 sm:gap-3">
                                            <div class="flex flex-wrap items-center gap-2.5 sm:gap-3">
                                                <a href="{{ route('opac') }}" class="btn-press inline-flex items-center gap-2.5 rounded-full bg-[#0b2a5b] hover:bg-primary-dark px-6 py-3 text-xs sm:text-sm font-bold text-white shadow-sm transition-all">
                                                    <span>Cari Buku di OPAC</span>
                                                    <i class="fa-solid fa-arrow-right text-xs text-[#f59e0b]" aria-hidden="true"></i>
                                                </a>
                                                <a href="{{ route('repository') }}" class="btn-press inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2.5 text-xs sm:text-sm font-semibold text-slate-700 transition-all hover:border-primary/40 hover:text-primary">
                                                    <i class="fa-solid fa-graduation-cap text-primary text-xs" aria-hidden="true"></i>
                                                    <span>Repository Skripsi</span>
                                                </a>
                                                <a href="{{ route('ebook') }}" class="btn-press inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2.5 text-xs sm:text-sm font-semibold text-slate-700 transition-all hover:border-primary/40 hover:text-primary">
                                                    <i class="fa-solid fa-book-open-reader text-primary text-xs" aria-hidden="true"></i>
                                                    <span>E-Book Reader</span>
                                                </a>
                                            </div>
                                            <div class="flex flex-wrap items-center gap-2.5 sm:gap-3">
                                                <a href="{{ route('kehadiran.index') }}" class="btn-press inline-flex items-center gap-2.5 rounded-full bg-[#0b2a5b] hover:bg-primary-dark px-6 py-3 text-xs sm:text-sm font-bold text-white shadow-sm transition-all">
                                                    <i class="fa-solid fa-clipboard-user text-xs text-[#f59e0b]" aria-hidden="true"></i>
                                                    <span>Isi Kehadiran Pengunjung</span>
                                                    <i class="fa-solid fa-arrow-right text-xs text-[#f59e0b]" aria-hidden="true"></i>
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                @else
                                    <h1 id="judul-beranda" class="text-xl sm:text-2xl lg:text-[28px] xl:text-[32px] font-extrabold uppercase tracking-tight text-[#0b1f3a] leading-[1.22]">
                                        Perpustakaan MNC University
                                    </h1>
                                    <p class="mt-3.5 text-xs sm:text-sm lg:text-[15px] leading-[1.7] text-slate-600">
                                        Pusat sumber belajar dan referensi akademik sivitas MNC University. Jelajahi katalog buku, repository skripsi dan riset, jurnal internasional terindeks, serta koleksi e-book resmi.
                                    </p>
                                    <div class="mt-6 sm:mt-7 flex flex-col items-start gap-2.5 sm:gap-3">
                                        <div class="flex flex-wrap items-center gap-2.5 sm:gap-3">
                                            <a href="#layanan" class="btn-press inline-flex items-center gap-2.5 rounded-full bg-[#0b2a5b] hover:bg-primary-dark px-6 py-3 text-xs sm:text-sm font-bold text-white shadow-sm transition-all">
                                                <span>Pilih Layanan Perpustakaan</span>
                                                <i class="fa-solid fa-arrow-right text-xs text-[#f59e0b]" aria-hidden="true"></i>
                                            </a>
                                            <a href="{{ route('register') }}" class="btn-press inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2.5 text-xs sm:text-sm font-semibold text-primary transition-all hover:bg-primary/5">
                                                <i class="fa-solid fa-user-plus text-xs"></i>
                                                <span>Daftar Anggota</span>
                                            </a>
                                        </div>
                                        <div class="flex flex-wrap items-center gap-2.5 sm:gap-3">
                                            <a href="{{ route('kehadiran.index') }}" class="btn-press inline-flex items-center gap-2.5 rounded-full bg-[#0b2a5b] hover:bg-primary-dark px-6 py-3 text-xs sm:text-sm font-bold text-white shadow-sm transition-all">
                                                <i class="fa-solid fa-clipboard-user text-xs text-[#f59e0b]" aria-hidden="true"></i>
                                                <span>Isi Kehadiran Pengunjung</span>
                                                <i class="fa-solid fa-arrow-right text-xs text-[#f59e0b]" aria-hidden="true"></i>
                                            </a>
                                            <a href="https://maps.google.com/maps?q=MNC%20University" target="_blank" rel="noopener" class="btn-press inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2.5 text-xs sm:text-sm font-semibold text-slate-700 transition-all hover:border-primary/40 hover:text-primary">
                                                <i class="fa-solid fa-location-dot text-primary text-xs" aria-hidden="true"></i>
                                                <span>Lokasi Kampus</span>
                                            </a>
                                        </div>
                                    </div>
                                @endauth
                            </div>

                            {{-- Kolom Kanan: Frame Kotak Rounded berisi Data Rekap Pengunjung --}}
                            <div class="flex items-center justify-center lg:col-span-5 lg:justify-end">
                                <div class="w-full max-w-[360px] lg:max-w-[380px] rounded-[22px] border border-slate-200/90 bg-slate-50/70 p-4 sm:p-5 shadow-xs">
                                    <div class="flex items-center justify-between border-b border-slate-200/80 pb-3">
                                        <div class="flex items-center gap-2.5">
                                            <span class="flex size-9 shrink-0 items-center justify-center rounded-xl bg-[#0b2a5b]/10 text-[#0b2a5b]">
                                                <i class="fa-solid fa-chart-simple text-sm" aria-hidden="true"></i>
                                            </span>
                                            <div>
                                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Aktivitas Pemustaka</span>
                                                <h2 class="text-sm sm:text-base font-bold text-[#0b1f3a]">Statistik Kunjungan</h2>
                                            </div>
                                        </div>
                                        <span class="inline-flex items-center gap-1 rounded-full bg-white px-2.5 py-1 text-[11px] font-semibold text-slate-500 border border-slate-200/70">
                                            <i class="fa-solid fa-clock-rotate-left text-[10px] text-[#f59e0b]"></i>
                                            Terkini
                                        </span>
                                    </div>

                                    <dl class="mt-3 flex flex-col gap-2.5">
                                        <div class="flex items-center justify-between rounded-xl border border-blue-200/80 bg-white px-3.5 py-2.5 shadow-2xs">
                                            <div class="flex items-center gap-2.5">
                                                <span class="flex size-8 sm:size-9 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-600">
                                                    <i class="fa-solid fa-user-clock text-xs" aria-hidden="true"></i>
                                                </span>
                                                <div>
                                                    <dt class="text-xs font-bold text-slate-800">Hari ini</dt>
                                                    <span class="text-[10px] text-slate-500">Kunjungan unik hari ini</span>
                                                </div>
                                            </div>
                                            <dd class="text-lg sm:text-xl font-extrabold text-blue-700">{{ number_format($visitorStats['today'] ?? 0) }}</dd>
                                        </div>

                                        <div class="flex items-center justify-between rounded-xl border border-purple-200/80 bg-white px-3.5 py-2.5 shadow-2xs">
                                            <div class="flex items-center gap-2.5">
                                                <span class="flex size-8 sm:size-9 shrink-0 items-center justify-center rounded-xl bg-purple-100 text-[#7C3AED]">
                                                    <i class="fa-solid fa-calendar-check text-xs" aria-hidden="true"></i>
                                                </span>
                                                <div>
                                                    <dt class="text-xs font-bold text-slate-800">Bulan ini</dt>
                                                    <span class="text-[10px] text-slate-500">{{ now()->translatedFormat('F Y') }}</span>
                                                </div>
                                            </div>
                                            <dd class="text-lg sm:text-xl font-extrabold text-[#7C3AED]">{{ number_format($visitorStats['month'] ?? 0) }}</dd>
                                        </div>

                                        <div class="flex items-center justify-between rounded-xl border border-emerald-200/80 bg-white px-3.5 py-2.5 shadow-2xs">
                                            <div class="flex items-center gap-2.5">
                                                <span class="flex size-8 sm:size-9 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600">
                                                    <i class="fa-solid fa-users text-xs" aria-hidden="true"></i>
                                                </span>
                                                <div>
                                                    <dt class="text-xs font-bold text-slate-800">Total Kunjungan</dt>
                                                    <span class="text-[10px] text-slate-500">Akumulasi pengunjung</span>
                                                </div>
                                            </div>
                                            <dd class="text-lg sm:text-xl font-extrabold text-emerald-700">{{ number_format($visitorStats['total'] ?? 0) }}</dd>
                                        </div>
                                    </dl>

                                    <div class="mt-3 flex flex-wrap items-center justify-between gap-2 border-t border-slate-200/80 pt-2.5 text-[11px] text-slate-500">
                                        @if (isset($isDbConnected) && ! $isDbConnected)
                                            <span class="flex items-center gap-1.5 text-amber-600 font-medium">
                                                <i class="fa-solid fa-circle-exclamation text-amber-500 text-xs" aria-hidden="true"></i>
                                                Database offline
                                            </span>
                                        @else
                                            <span class="flex items-center gap-1.5">
                                                <i class="fa-solid fa-circle-check text-emerald-500 text-xs" aria-hidden="true"></i>
                                                Pembaruan otomatis
                                            </span>
                                        @endif
                                        <span class="font-semibold text-[#0b2a5b] flex items-center gap-1">
                                            <i class="fa-solid fa-building-columns text-xs" aria-hidden="true"></i>
                                            MNCU Library
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>

                    {{-- SLIDE 2..5 (INDEX 1..4): SOROTAN BERITA & AGENDA ACARA PERSIS UI WIDYATAMA --}}
                    @foreach ($heroPromoSlides as $sIdx => $slide)
                        <article data-hero-slide="{{ $sIdx + 1 }}" class="hero-widyatama-card w-[90%] sm:w-[91%] lg:w-[92%] shrink-0 rounded-[26px] sm:rounded-[30px] border border-slate-200/80 bg-white p-5 sm:p-8 lg:px-12 lg:py-10 shadow-[0_8px_30px_rgba(15,23,42,0.05)] opacity-60 transition-opacity duration-300">
                            <div class="grid h-full grid-cols-1 items-center gap-6 lg:grid-cols-12 lg:gap-10">
                                {{-- Kolom Kiri: Pill Kategori + Tanggal, Judul Kapital Bold Navy, Kutipan, Tombol Pill Navy --}}
                                <div class="flex flex-col justify-center lg:col-span-7">
                                    <div class="mb-4 flex flex-wrap items-center gap-3">
                                        <span class="inline-flex items-center gap-2 rounded-full bg-[#f1f5f9] px-3.5 py-1.5 text-xs font-bold text-[#0f2a6b]">
                                            <span class="size-2 rounded-full bg-[#f59e0b]"></span>
                                            {{ $slide['badge'] }}
                                        </span>
                                        <span class="inline-flex items-center gap-1.5 text-xs font-medium text-slate-400">
                                            <i class="fa-regular fa-calendar text-[11px]" aria-hidden="true"></i>
                                            {{ $slide['date'] }}
                                        </span>
                                    </div>

                                    <h2 class="text-xl sm:text-2xl lg:text-[28px] xl:text-[32px] font-extrabold uppercase tracking-tight text-[#0b1f3a] leading-[1.22] line-clamp-3">
                                        {{ $slide['title'] }}
                                    </h2>

                                    <p class="mt-3.5 text-xs sm:text-sm lg:text-[15px] leading-[1.7] text-slate-600 line-clamp-3">
                                        {{ $slide['excerpt'] }}
                                    </p>

                                    <div class="mt-6 sm:mt-7">
                                        <a href="{{ $slide['url'] }}" class="btn-press inline-flex items-center gap-2.5 rounded-full bg-[#0b2a5b] hover:bg-primary-dark px-6 py-3 text-xs sm:text-sm font-bold text-white shadow-sm transition-all">
                                            <span>{{ $slide['cta'] }}</span>
                                            <i class="fa-solid fa-arrow-right text-xs text-[#f59e0b]" aria-hidden="true"></i>
                                        </a>
                                    </div>
                                </div>

                                {{-- Kolom Kanan: Gambar Kotak / Square Rounded di Dalam Kartu --}}
                                <div class="flex items-center justify-center lg:col-span-5 lg:justify-end">
                                    <div class="w-full max-w-[290px] sm:max-w-[330px] lg:max-w-[360px] aspect-square rounded-[22px] overflow-hidden bg-slate-100 border border-slate-200/80 shadow-sm">
                                        <img
                                            src="{{ $slide['image'] }}"
                                            alt="{{ $slide['title'] }}"
                                            class="h-full w-full object-cover transition-transform duration-500 hover:scale-105"
                                            loading="lazy"
                                            onerror="this.onerror=null; this.src='{{ asset('images/berita/berita-digital-library.jpg') }}';"
                                        >
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach

                    {{-- CLONE DARI SLIDE PERTAMA (Untuk efek mengintip di sisi kanan saat berada di Slide Terakhir) --}}
                    <article data-hero-clone="first" aria-hidden="true" class="hero-widyatama-card relative overflow-hidden w-[90%] sm:w-[91%] lg:w-[92%] shrink-0 rounded-[26px] sm:rounded-[30px] border border-slate-200/80 bg-white p-5 sm:p-8 lg:px-12 lg:py-10 shadow-[0_8px_30px_rgba(15,23,42,0.05)] opacity-60 transition-opacity duration-300">
                        <div class="pointer-events-none select-none absolute -left-8 -bottom-10 sm:left-6 sm:-bottom-12 lg:left-[26%] lg:top-1/2 lg:-translate-y-1/2 z-0" aria-hidden="true">
                            <img
                                src="{{ asset('images/LogoTXT.png') }}"
                                alt=""
                                class="w-[260px] sm:w-[340px] lg:w-[420px] max-w-none object-contain opacity-[0.045] grayscale contrast-125 -rotate-6"
                            >
                        </div>
                        <div class="relative z-10 grid h-full grid-cols-1 items-center gap-6 lg:grid-cols-12 lg:gap-10">
                            <div class="flex flex-col justify-center lg:col-span-7">
                                <div class="mb-3.5 flex flex-wrap items-center gap-3">
                                    <span class="inline-flex items-center gap-2 rounded-full bg-[#f1f5f9] px-3.5 py-1.5 text-xs font-bold text-[#0f2a6b]">
                                        <span class="size-2 rounded-full bg-[#f59e0b]"></span>
                                        Sambutan Perpustakaan
                                    </span>
                                    <span class="inline-flex items-center gap-1.5 text-xs font-medium text-slate-400">
                                        <i class="fa-regular fa-calendar text-[11px]" aria-hidden="true"></i>
                                        {{ now()->translatedFormat('d M Y') }}
                                    </span>
                                </div>
                                <h2 class="text-xl sm:text-2xl lg:text-[28px] xl:text-[32px] font-extrabold uppercase tracking-tight text-[#0b1f3a] leading-[1.22]">
                                    Perpustakaan MNC University
                                </h2>
                                <p class="mt-3.5 text-xs sm:text-sm lg:text-[15px] leading-[1.7] text-slate-600">
                                    Pusat sumber belajar dan referensi akademik sivitas MNC University. Jelajahi katalog buku, repository skripsi dan riset, jurnal internasional terindeks, serta koleksi e-book resmi.
                                </p>
                            </div>
                            <div class="flex items-center justify-center lg:col-span-5 lg:justify-end">
                                <div class="w-full max-w-[360px] lg:max-w-[380px] rounded-[22px] border border-slate-200/90 bg-slate-50/70 p-4 sm:p-5 shadow-xs">
                                    <div class="text-sm font-bold text-[#0b1f3a]">Statistik Kunjungan</div>
                                    <div class="mt-2 text-2xl font-extrabold text-emerald-700">{{ number_format($visitorStats['total'] ?? 0) }}</div>
                                </div>
                            </div>
                        </div>
                    </article>
                </div>

                {{-- Indikator Titik (Dots) di Bawah Kartu --}}
                <div id="hero-widyatama-dots" class="mt-4 flex items-center justify-center gap-2" role="tablist" aria-label="Pilih slide sorotan beranda">
                    @for ($dotIdx = 0; $dotIdx < $totalHeroSlides; $dotIdx++)
                        <button
                            type="button"
                            data-hero-dot="{{ $dotIdx }}"
                            role="tab"
                            aria-label="Tampilkan slide {{ $dotIdx + 1 }}"
                            aria-selected="{{ $dotIdx === 0 ? 'true' : 'false' }}"
                            class="h-2 rounded-full transition-all duration-300 cursor-pointer {{ $dotIdx === 0 ? 'w-7 bg-[#0b2a5b]' : 'w-2 bg-slate-300 hover:bg-slate-400' }}"
                        ></button>
                    @endfor
                </div>
            </div>

            <script>
                (function () {
                    const root = document.getElementById('hero-widyatama-carousel');
                    const track = document.getElementById('hero-widyatama-track');
                    const prevBtn = document.getElementById('hero-widyatama-prev');
                    const nextBtn = document.getElementById('hero-widyatama-next');
                    const dots = Array.from(document.querySelectorAll('[data-hero-dot]'));
                    if (!root || !track) return;

                    const cards = Array.from(track.querySelectorAll('.hero-widyatama-card'));
                    const realCount = {{ $totalHeroSlides }};
                    let domIndex = 1; // index 0 = clone last, 1..realCount = real slides, realCount+1 = clone first
                    let isAnimating = false;
                    let autoTimer = null;

                    function computeOffset(targetDomIdx) {
                        const card = cards[targetDomIdx];
                        if (!card) return 0;
                        const rootWidth = root.clientWidth;
                        const cardWidth = card.offsetWidth;
                        const cardLeft = card.offsetLeft;
                        return -(cardLeft - (rootWidth - cardWidth) / 2);
                    }

                    function updateActiveState() {
                        let realIdx = domIndex - 1;
                        if (realIdx < 0) realIdx = realCount - 1;
                        if (realIdx >= realCount) realIdx = 0;

                        cards.forEach((card, idx) => {
                            if (idx === domIndex) {
                                card.classList.remove('opacity-60');
                                card.classList.add('opacity-100');
                            } else {
                                card.classList.remove('opacity-100');
                                card.classList.add('opacity-60');
                            }
                        });

                        dots.forEach((dot, idx) => {
                            const active = idx === realIdx;
                            dot.setAttribute('aria-selected', active ? 'true' : 'false');
                            dot.className = active
                                ? 'h-2 w-7 rounded-full bg-[#0b2a5b] transition-all duration-300 cursor-pointer'
                                : 'h-2 w-2 rounded-full bg-slate-300 hover:bg-slate-400 transition-all duration-300 cursor-pointer';
                        });
                    }

                    function applyTransform(animate = true) {
                        if (animate) {
                            track.style.transition = 'transform 500ms cubic-bezier(0.22, 1, 0.36, 1)';
                        } else {
                            track.style.transition = 'none';
                        }
                        const offset = computeOffset(domIndex);
                        track.style.transform = `translate3d(${offset}px, 0, 0)`;
                        updateActiveState();
                    }

                    function goToDomIndex(nextDomIdx) {
                        if (isAnimating) return;
                        isAnimating = true;
                        domIndex = nextDomIdx;
                        applyTransform(true);
                    }

                    track.addEventListener('transitionend', function (e) {
                        if (e.target !== track) return;
                        isAnimating = false;
                        if (domIndex === 0) {
                            domIndex = realCount;
                            applyTransform(false);
                        } else if (domIndex === realCount + 1) {
                            domIndex = 1;
                            applyTransform(false);
                        }
                    });

                    function nextSlide() {
                        if (isAnimating) return;
                        goToDomIndex(domIndex + 1);
                    }

                    function prevSlide() {
                        if (isAnimating) return;
                        goToDomIndex(domIndex - 1);
                    }

                    function startAuto() {
                        stopAuto();
                        autoTimer = setInterval(nextSlide, 5500);
                    }

                    function stopAuto() {
                        if (autoTimer) {
                            clearInterval(autoTimer);
                            autoTimer = null;
                        }
                    }

                    if (prevBtn) {
                        prevBtn.addEventListener('click', function () {
                            prevSlide();
                            startAuto();
                        });
                    }

                    if (nextBtn) {
                        nextBtn.addEventListener('click', function () {
                            nextSlide();
                            startAuto();
                        });
                    }

                    dots.forEach((dot, idx) => {
                        dot.addEventListener('click', function () {
                            if (isAnimating) return;
                            goToDomIndex(idx + 1);
                            startAuto();
                        });
                    });

                    // Drag / Swipe support
                    let startX = 0;
                    let isDragging = false;

                    root.addEventListener('pointerdown', function (e) {
                        if (e.target.closest('a, button')) return;
                        isDragging = true;
                        startX = e.clientX;
                        stopAuto();
                    });

                    window.addEventListener('pointerup', function (e) {
                        if (!isDragging) return;
                        isDragging = false;
                        const diff = e.clientX - startX;
                        if (Math.abs(diff) > 45) {
                            if (diff < 0) nextSlide();
                            else prevSlide();
                        }
                        startAuto();
                    });

                    root.addEventListener('mouseenter', stopAuto);
                    root.addEventListener('mouseleave', startAuto);

                    window.addEventListener('resize', function () {
                        isAnimating = false;
                        applyTransform(false);
                    });

                    requestAnimationFrame(function () {
                        applyTransform(false);
                        startAuto();
                    });
                })();
            </script>

            {{-- Garis biru di bawah hero --}}
            <div class="mt-3 pt-3 pb-2 sm:mt-4 sm:pt-4 sm:pb-3">
                <div class="h-0.5 w-full bg-primary/80"></div>
            </div>
        </section>

        {{-- Komponen card: layanan --}}
        <section id="layanan" aria-labelledby="judul-layanan" class="scroll-mt-28 rounded-2xl border border-line bg-white p-6 lg:p-8">
            <div class="flex flex-wrap items-end justify-between gap-2">
                <div>
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-primary/10 text-primary mb-2">
                        <i class="fa-solid fa-sparkles text-[10px]"></i>
                        Koleksi & Portal Digital
                    </div>
                    <h2 id="judul-layanan" class="text-xl font-bold text-ink lg:text-3xl">Layanan Perpustakaan</h2>
                    <p class="mt-1 text-sm text-slate-600 lg:text-base">Pilih layanan perpustakaan sesuai koleksi atau kebutuhan riset yang ingin kamu akses.</p>
                </div>
            </div>

            {{-- Kartu utama: satu-satunya layanan yang berupa halaman di situs ini, jadi diberi porsi lebih besar. --}}
            <a href="{{ route('opac') }}" class="group relative mt-6 flex flex-col gap-5 overflow-hidden rounded-2xl border-2 border-primary/20 bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-primary hover:shadow-xl hover:shadow-primary/10 motion-reduce:transition-none motion-reduce:hover:translate-y-0 md:flex-row md:items-center md:gap-7 lg:p-8">
                <span class="absolute inset-x-0 top-0 h-1.5" style="background: linear-gradient(to right, #0f2a6b, #2563eb, #38bdf8);"></span>
                <span class="flex size-16 shrink-0 items-center justify-center rounded-2xl bg-primary text-white shadow-lg shadow-primary/20 transition-transform duration-300 group-hover:scale-105 lg:size-20" style="background-color: #0f2a6b; color: #ffffff;">
                    <i class="fa-solid fa-book-open-reader text-3xl lg:text-4xl text-white" aria-hidden="true"></i>
                </span>
                <span class="flex-1">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-primary/10 px-3 py-0.5 text-xs font-bold text-primary mb-2">
                        <span class="size-1.5 rounded-full bg-primary animate-pulse"></span>
                        Koleksi & Sirkulasi Utama
                    </span>
                    <span class="block text-xl font-bold text-ink lg:text-2xl group-hover:text-primary transition-colors">Katalog OPAC</span>
                    <span class="mt-1.5 block text-sm leading-relaxed text-slate-600 lg:text-base">Cari buku teks, monograf, dan referensi fisik. Cek ketersediaan eksemplar dan lokasi nomor panggil rak secara real-time.</span>
                </span>
                <span class="inline-flex items-center gap-2 rounded-xl bg-primary px-6 py-3 text-sm font-bold text-white shadow-md shadow-primary/20 transition-all duration-200 group-hover:shadow-lg group-hover:shadow-primary/30 group-hover:scale-105 lg:text-base" style="background-color: #0f2a6b; color: #ffffff;">
                    Buka Katalog
                    <i class="fa-solid fa-arrow-right text-base transition-transform duration-200 group-hover:translate-x-1.5 motion-reduce:transition-none" aria-hidden="true"></i>
                </span>
            </a>

            <ul class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-2 xl:grid-cols-4 lg:mt-6 lg:gap-5 xl:gap-6">
                @foreach ($digitalServices as $digitalService)
                    <li>
                        <a
                            href="{{ $digitalService['href'] }}"
                            @if ($digitalService['isExternal']) target="_blank" rel="noopener" @endif
                            class="group relative flex h-full flex-col overflow-hidden rounded-2xl border-2 {{ $digitalService['cardBorder'] }} {{ $digitalService['cardBg'] }} {{ $digitalService['cardShadow'] }} p-5 transition-all duration-300 hover:-translate-y-1 motion-reduce:transition-none motion-reduce:hover:translate-y-0"
                        >
                            <span class="absolute inset-x-0 top-0 h-1.5" style="{{ $digitalService['topBarStyle'] }}"></span>

                            <span class="flex items-start gap-3.5 xl:gap-4 pt-1">
                                <span class="flex size-12 xl:size-14 2xl:size-[60px] shrink-0 items-center justify-center rounded-2xl {{ $digitalService['iconBox'] }} transition-transform duration-300 group-hover:scale-110" style="{{ $digitalService['iconStyle'] ?? '' }}">
                                    <i class="{{ $digitalService['faIcon'] }} text-xl xl:text-2xl 2xl:text-3xl" aria-hidden="true"></i>
                                </span>
                                <span class="min-w-0">
                                    <span class="block text-base font-bold text-ink lg:text-base xl:text-lg transition-colors {{ $digitalService['titleColor'] }} truncate">{{ $digitalService['title'] }}</span>
                                    <span class="mt-1 block text-xs leading-relaxed text-slate-600 xl:text-sm line-clamp-2">{{ $digitalService['description'] }}</span>
                                </span>
                            </span>

                            <span class="mt-auto block pt-5">
                                <span class="block h-px bg-slate-200/70"></span>
                                <span class="mt-4 flex items-center justify-between text-xs sm:text-sm">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $digitalService['tagBadge'] }}">
                                        {{ $digitalService['typeBadge'] ?? ($digitalService['isExternal'] ? 'Situs eksternal' : 'Layanan digital') }}
                                    </span>
                                    <span class="inline-flex shrink-0 items-center gap-1.5 font-bold {{ $digitalService['linkColor'] }}">
                                        Buka
                                        @if ($digitalService['isExternal'])
                                            <i class="fa-solid fa-arrow-up-right-from-square text-xs" aria-hidden="true"></i>
                                            <span class="sr-only">(tab baru)</span>
                                        @else
                                            <i class="fa-solid fa-chevron-right text-xs transition-transform duration-200 group-hover:translate-x-1 motion-reduce:transition-none" aria-hidden="true"></i>
                                        @endif
                                    </span>
                                </span>
                            </span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </section>

        {{-- Card berita --}}
        <section aria-labelledby="judul-berita" class="rounded-2xl border border-line bg-white p-5 lg:p-[30px]">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h2 id="judul-berita" class="text-base font-semibold text-ink lg:text-xl">Berita Perpustakaan</h2>
                    <p class="mt-1 text-[13px] text-muted lg:text-base">Kabar terbaru dari perpustakaan MNC University.</p>
                </div>
                <div class="flex items-center gap-2 sm:gap-3">
                    <a href="{{ route('berita.index') }}" class="inline-flex min-h-[44px] items-center gap-1 rounded-lg px-2 text-sm font-medium text-primary hover:underline lg:text-base">
                        Semua berita
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 6 6 6-6 6" /></svg>
                    </a>

                    {{-- Navigasi Tombol Geser Carousel Berita --}}
                    @if (!empty($latestNews) && count($latestNews) > 0)
                        <div class="inline-flex items-center gap-1.5 border-l border-line pl-2 sm:pl-3">
                            <button
                                type="button"
                                id="btn-berita-prev"
                                class="inline-flex min-h-[44px] min-w-[44px] size-11 items-center justify-center rounded-xl border border-line bg-white text-ink transition hover:border-primary/40 hover:bg-subtle hover:text-primary disabled:opacity-30 disabled:cursor-not-allowed focus-visible:outline-2 focus-visible:outline-primary"
                                aria-label="Geser ke berita sebelumnya"
                                disabled
                            >
                                <i class="fa-solid fa-chevron-left text-sm" aria-hidden="true"></i>
                            </button>
                            <button
                                type="button"
                                id="btn-berita-next"
                                class="inline-flex min-h-[44px] min-w-[44px] size-11 items-center justify-center rounded-xl border border-line bg-white text-ink transition hover:border-primary/40 hover:bg-subtle hover:text-primary disabled:opacity-30 disabled:cursor-not-allowed focus-visible:outline-2 focus-visible:outline-primary"
                                aria-label="Geser ke berita berikutnya"
                            >
                                <i class="fa-solid fa-chevron-right text-sm" aria-hidden="true"></i>
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            @if (!empty($latestNews) && count($latestNews) > 0)
                <div class="relative mt-5">
                    <ul
                        id="carousel-berita"
                        class="carousel-track flex gap-5 overflow-x-auto pb-4 pt-1 cursor-grab select-none focus:outline-none"
                        tabindex="0"
                        role="region"
                        aria-label="Daftar Berita Perpustakaan yang dapat digeser"
                    >
                        @foreach ($latestNews as $newsItem)
                            <li class="carousel-item-3 shrink-0 flex flex-col">
                                <a
                                    href="{{ !empty($newsItem['slug']) && $newsItem['slug'] !== '#' ? route('berita.show', $newsItem['slug']) : route('berita.index') }}"
                                    class="carousel-card group flex h-full flex-col overflow-hidden rounded-2xl border border-line bg-white shadow-card transition-all duration-200 hover:-translate-y-1 hover:border-primary/40 hover:shadow-raised motion-reduce:transition-none motion-reduce:hover:translate-y-0"
                                    draggable="false"
                                >
                                    <div class="relative aspect-video overflow-hidden bg-gradient-to-br from-primary/10 via-track to-primary/5 select-none pointer-events-none">
                                        @if (!empty($newsItem['image']))
                                            <img
                                                src="{{ filter_var($newsItem['image'], FILTER_VALIDATE_URL) ? $newsItem['image'] : asset($newsItem['image']) }}"
                                                alt="{{ $newsItem['title'] }}"
                                                class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
                                                loading="lazy"
                                                draggable="false"
                                                onerror="this.onerror=null; this.src='{{ asset('images/berita/berita-digital-library.jpg') }}';"
                                            >
                                        @endif
                                        <span class="absolute top-3 left-3 inline-flex items-center gap-1.5 rounded-full bg-white/95 px-3 py-1 text-xs font-semibold text-primary shadow-xs backdrop-blur-xs">
                                            <span class="size-1.5 rounded-full bg-primary" aria-hidden="true"></span>
                                            {{ $newsItem['category'] ?? 'Berita Kampus' }}
                                        </span>
                                    </div>
                                    <span class="flex flex-1 flex-col p-5 bg-white">
                                        <span class="flex items-center gap-1.5 text-xs font-semibold text-primary">
                                            <i class="fa-regular fa-calendar text-[11px]"></i>
                                            <span>{{ $newsItem['date'] }}</span>
                                        </span>
                                        <span class="mt-2 line-clamp-2 text-base font-bold text-ink group-hover:text-primary transition-colors lg:text-lg">{{ $newsItem['title'] }}</span>
                                        <span class="mt-2 line-clamp-2 text-sm text-slate-600">{{ $newsItem['excerpt'] }}</span>
                                        <span class="mt-auto pt-4 flex items-center justify-between border-t border-slate-100 text-xs sm:text-sm font-bold text-primary">
                                            <span>Baca berita</span>
                                            <span class="flex size-7 items-center justify-center rounded-full bg-primary/10 text-primary transition-all duration-200 group-hover:translate-x-1 group-hover:bg-primary group-hover:text-white shadow-xs">
                                                <i class="fa-solid fa-arrow-right text-xs" aria-hidden="true"></i>
                                            </span>
                                        </span>
                                    </span>
                                </a>
                            </li>
                        @endforeach
                    </ul>

                    {{-- Navigasi visual & Indikator Dots Carousel Berita --}}
                    <div class="mt-4 flex flex-wrap items-center justify-between gap-3 border-t border-line/60 pt-3 text-xs text-muted">
                        <div class="flex items-center gap-3">
                            <span class="flex items-center gap-1.5 font-medium text-ink-secondary">
                                <i class="fa-solid fa-arrows-left-right text-[12px] text-primary" aria-hidden="true"></i>
                                <span>Geser untuk berita lainnya</span>
                            </span>
                            {{-- Pagination Dots --}}
                            <div id="dots-berita" class="flex items-center gap-1.5" role="tablist" aria-label="Indikator slide berita"></div>
                        </div>
                        <span class="font-medium text-ink-secondary" id="counter-berita">
                            Menampilkan 1 - 3 dari {{ count($latestNews) }} berita
                        </span>
                    </div>
                </div>
            @else
                <div class="mt-5 rounded-2xl border border-dashed border-line bg-subtle/70 p-8 text-center">
                    <div class="mx-auto flex size-12 items-center justify-center rounded-2xl bg-primary/10 text-primary">
                        <i class="fa-solid fa-newspaper text-xl"></i>
                    </div>
                    <h3 class="mt-3 text-sm font-semibold text-ink sm:text-base">Belum Ada Berita yang Diterbitkan</h3>
                    <p class="mt-1 text-xs text-muted sm:text-sm">Rilis informasi dan kabar terbaru perpustakaan akan tampil di sini begitu dipublikasikan.</p>
                </div>
            @endif

            <div class="mt-5 h-1 rounded-full bg-track"></div>
        </section>

        {{-- Acara / event --}}
        <section aria-labelledby="judul-acara" class="rounded-2xl border border-line bg-white p-5 lg:p-[30px]">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h2 id="judul-acara" class="text-base font-semibold text-ink lg:text-xl">Acara Mendatang</h2>
                    <p class="mt-1 text-[13px] text-muted lg:text-base">Kelas literasi, bedah buku, dan kegiatan perpustakaan lainnya.</p>
                </div>
                <div class="flex items-center gap-2 sm:gap-3">
                    <a href="{{ route('acara.index') }}" class="inline-flex min-h-[44px] items-center gap-1 rounded-lg px-2 text-sm font-medium text-primary hover:underline lg:text-base">
                        Semua acara
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 6 6 6-6 6" /></svg>
                    </a>

                    {{-- Navigasi Tombol Geser Carousel Acara --}}
                    @if (!empty($upcomingEvents) && count($upcomingEvents) > 0)
                        <div class="inline-flex items-center gap-1.5 border-l border-line pl-2 sm:pl-3">
                            <button
                                type="button"
                                id="btn-acara-prev"
                                class="inline-flex min-h-[44px] min-w-[44px] size-11 items-center justify-center rounded-xl border border-line bg-white text-ink transition hover:border-primary/40 hover:bg-subtle hover:text-primary disabled:opacity-30 disabled:cursor-not-allowed focus-visible:outline-2 focus-visible:outline-primary"
                                aria-label="Geser ke acara sebelumnya"
                                disabled
                            >
                                <i class="fa-solid fa-chevron-left text-sm" aria-hidden="true"></i>
                            </button>
                            <button
                                type="button"
                                id="btn-acara-next"
                                class="inline-flex min-h-[44px] min-w-[44px] size-11 items-center justify-center rounded-xl border border-line bg-white text-ink transition hover:border-primary/40 hover:bg-subtle hover:text-primary disabled:opacity-30 disabled:cursor-not-allowed focus-visible:outline-2 focus-visible:outline-primary"
                                aria-label="Geser ke acara berikutnya"
                            >
                                <i class="fa-solid fa-chevron-right text-sm" aria-hidden="true"></i>
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            @if (!empty($upcomingEvents) && count($upcomingEvents) > 0)
                <div class="relative mt-5">
                    <ul
                        id="carousel-acara"
                        class="carousel-track flex gap-5 overflow-x-auto pb-4 pt-1 cursor-grab select-none focus:outline-none"
                        tabindex="0"
                        role="region"
                        aria-label="Daftar Acara Perpustakaan yang dapat digeser"
                    >
                        @foreach ($upcomingEvents as $eventItem)
                            <li class="carousel-item-3 shrink-0 flex flex-col">
                                <a
                                    href="{{ !empty($eventItem['slug']) && $eventItem['slug'] !== '#' ? route('acara.show', $eventItem['slug']) : route('acara.index') }}"
                                    class="carousel-card group flex h-full flex-col overflow-hidden rounded-2xl border border-line bg-white shadow-card transition-all duration-200 hover:-translate-y-1 hover:border-primary/40 hover:shadow-raised motion-reduce:transition-none motion-reduce:hover:translate-y-0"
                                    draggable="false"
                                >
                                    <div class="relative aspect-video overflow-hidden bg-gradient-to-br from-primary/10 via-track to-primary/5 select-none pointer-events-none">
                                        @if (!empty($eventItem['image']))
                                            <img
                                                src="{{ filter_var($eventItem['image'], FILTER_VALIDATE_URL) ? $eventItem['image'] : asset($eventItem['image']) }}"
                                                alt="{{ $eventItem['title'] }}"
                                                class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
                                                loading="lazy"
                                                draggable="false"
                                                onerror="this.onerror=null; this.src='{{ asset('images/acara/acara-01.jpg') }}';"
                                            >
                                        @else
                                            <div class="flex h-full w-full items-center justify-center p-6 text-muted">
                                                <svg class="size-12 text-primary/30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                    <rect width="18" height="18" x="3" y="4" rx="2" ry="2"/>
                                                    <line x1="16" x2="16" y1="2" y2="6"/>
                                                    <line x1="8" x2="8" y1="2" y2="6"/>
                                                    <line x1="3" x2="21" y1="10" y2="10"/>
                                                </svg>
                                            </div>
                                        @endif
                                        <span class="absolute top-3 left-3 inline-flex items-center gap-1.5 rounded-full bg-white/95 px-3 py-1 text-xs font-semibold text-primary shadow-xs backdrop-blur-xs">
                                            <span class="size-1.5 rounded-full bg-primary" aria-hidden="true"></span>
                                            {{ $eventItem['category'] ?? 'Acara Kampus' }}
                                        </span>
                                        @if (!empty($eventItem['day']) && $eventItem['day'] !== '--')
                                            <span class="absolute top-3 right-3 inline-flex items-center gap-1.5 rounded-full bg-white/95 px-2.5 py-1 text-xs font-semibold text-ink shadow-xs backdrop-blur-xs border border-line">
                                                <svg class="size-3 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                    <circle cx="12" cy="12" r="10"/>
                                                    <polyline points="12 6 12 12 16 14"/>
                                                </svg>
                                                {{ $eventItem['day'] }} {{ $eventItem['month'] }}
                                            </span>
                                        @endif
                                    </div>
                                    <span class="flex flex-1 flex-col p-5 bg-white">
                                        <span class="flex items-center gap-1.5 text-xs font-semibold text-primary">
                                            <i class="fa-regular fa-clock text-[11px]"></i>
                                            <span>{{ !empty($eventItem['date']) ? $eventItem['date'] : ($eventItem['day'] . ' ' . $eventItem['month']) }}</span>
                                            @if (!empty($eventItem['time']))
                                                &middot; <span>{{ $eventItem['time'] }}</span>
                                            @endif
                                        </span>
                                        <span class="mt-2 line-clamp-2 text-base font-bold text-ink group-hover:text-primary transition-colors lg:text-lg">{{ $eventItem['title'] }}</span>
                                        <span class="mt-2 line-clamp-2 text-sm text-slate-600">{{ !empty($eventItem['excerpt']) && $eventItem['excerpt'] !== '[Ringkasan acara, maksimal dua baris]' ? $eventItem['excerpt'] : (!empty($eventItem['place']) ? 'Diselenggarakan di ' . $eventItem['place'] . '.' : 'Agenda kegiatan perpustakaan.') }}</span>
                                        @if (!empty($eventItem['place']))
                                            <span class="mt-2.5 flex items-center gap-1.5 text-xs font-medium text-slate-600 truncate">
                                                <i class="fa-solid fa-location-dot text-rose-500 text-[11px]"></i>
                                                <span class="truncate">{{ $eventItem['place'] }}</span>
                                            </span>
                                        @endif
                                        <span class="mt-auto pt-4 flex items-center justify-between border-t border-slate-100 text-xs sm:text-sm font-bold text-primary">
                                            <span>Lihat detail acara</span>
                                            <span class="flex size-7 items-center justify-center rounded-full bg-primary/10 text-primary transition-all duration-200 group-hover:translate-x-1 group-hover:bg-primary group-hover:text-white shadow-xs">
                                                <i class="fa-solid fa-arrow-right text-xs" aria-hidden="true"></i>
                                            </span>
                                        </span>
                                    </span>
                                </a>
                            </li>
                        @endforeach
                    </ul>

                    {{-- Navigasi visual & Indikator Dots Carousel Acara --}}
                    <div class="mt-4 flex flex-wrap items-center justify-between gap-3 border-t border-line/60 pt-3 text-xs text-muted">
                        <div class="flex items-center gap-3">
                            <span class="flex items-center gap-1.5 font-medium text-ink-secondary">
                                <i class="fa-solid fa-arrows-left-right text-[12px] text-primary" aria-hidden="true"></i>
                                <span>Geser untuk kegiatan lainnya</span>
                            </span>
                            {{-- Pagination Dots --}}
                            <div id="dots-acara" class="flex items-center gap-1.5" role="tablist" aria-label="Indikator slide acara"></div>
                        </div>
                        <span class="font-medium text-ink-secondary" id="counter-acara">
                            Menampilkan 1 - 3 dari {{ count($upcomingEvents) }} acara
                        </span>
                    </div>
                </div>
            @else
                <div class="mt-5 rounded-2xl border border-dashed border-line bg-subtle/70 p-8 text-center">
                    <div class="mx-auto flex size-12 items-center justify-center rounded-2xl bg-primary/10 text-primary">
                        <i class="fa-solid fa-calendar-days text-xl"></i>
                    </div>
                    <h3 class="mt-3 text-sm font-semibold text-ink sm:text-base">Belum Ada Agenda Acara Mendatang</h3>
                    <p class="mt-1 text-xs text-muted sm:text-sm">Jadwal webinar, workshop, dan kegiatan literasi perpustakaan akan diumumkan di sini.</p>
                </div>
            @endif

            <div class="mt-5 h-1 rounded-full bg-track"></div>
        </section>

        {{-- Map --}}
        <section aria-labelledby="judul-lokasi" class="rounded-2xl border border-line bg-white p-5 lg:p-[30px]">
            <div class="flex flex-wrap items-end justify-between gap-3">
                <div>
                    <h2 id="judul-lokasi" class="text-base font-medium text-ink lg:text-xl">Lokasi Perpustakaan</h2>
                    <p class="mt-1 text-[13px] text-muted lg:text-base">Gedung Perpustakaan MNC University, Jl. Haji Ten No. 34, RT.7/RW.2, Kebon Sirih, Menteng, Jakarta Pusat, DKI Jakarta 10340</p>
                </div>
                <a href="https://www.google.com/maps/search/?api=1&query=MNC+University" target="_blank" rel="noopener" class="inline-flex min-h-11 items-center gap-1 rounded-lg text-sm font-medium text-primary hover:underline lg:text-base">
                    Buka di Google Maps
                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 17 17 7M9 7h8v8" /></svg>
                    <span class="sr-only">(tab baru)</span>
                </a>
            </div>

            <div class="mt-5 overflow-hidden rounded-xl border border-line bg-track">
                <iframe
                    title="Peta lokasi Perpustakaan MNC University"
                    src="https://maps.google.com/maps?q=MNC%20University&z=16&output=embed"
                    class="block h-72 w-full lg:h-[420px]"
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                ></iframe>
            </div>
        </section>
    </div>

    {{-- Script Interaktif Carousel Geser (Berita & Acara) --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const setupDragCarousel = (carouselId, prevBtnId, nextBtnId, dotsId, counterId, itemLabel) => {
                const track = document.getElementById(carouselId);
                const prevBtn = document.getElementById(prevBtnId);
                const nextBtn = document.getElementById(nextBtnId);
                const dotsContainer = document.getElementById(dotsId);
                const counterEl = document.getElementById(counterId);

                if (!track) return;

                const items = Array.from(track.querySelectorAll('li'));
                const totalItems = items.length;
                if (totalItems === 0) return;

                let isDown = false;
                let startX = 0;
                let scrollLeft = 0;
                let hasDragged = false;
                const dragThreshold = 6;
                const dots = [];

                const getItemStep = () => {
                    const firstItem = items[0];
                    if (firstItem) {
                        return firstItem.offsetWidth + 20; // 20px corresponds to gap-5 (1.25rem)
                    }
                    return track.clientWidth * 0.8;
                };

                const getVisibleCount = () => {
                    const w = window.innerWidth;
                    if (w >= 1024) return 3;
                    if (w >= 640) return 2;
                    return 1;
                };

                const scrollToIndex = (index) => {
                    const targetItem = items[index];
                    if (!targetItem) return;
                    const targetLeft = targetItem.offsetLeft - track.offsetLeft;
                    track.scrollTo({ left: targetLeft, behavior: 'smooth' });
                };

                // Generate pagination indicator dots
                if (dotsContainer) {
                    dotsContainer.innerHTML = '';
                    items.forEach((_, idx) => {
                        const dot = document.createElement('button');
                        dot.type = 'button';
                        dot.className = `carousel-dot h-2 rounded-full transition-all duration-200 focus-visible:outline-2 focus-visible:outline-primary ${idx === 0 ? 'is-active w-6 bg-primary' : 'w-2 bg-slate-300 hover:bg-slate-400'}`;
                        dot.setAttribute('aria-label', `Lihat ${itemLabel} ke-${idx + 1}`);
                        dot.setAttribute('role', 'tab');
                        dot.setAttribute('aria-selected', idx === 0 ? 'true' : 'false');
                        dot.addEventListener('click', (e) => {
                            e.preventDefault();
                            scrollToIndex(idx);
                        });
                        dotsContainer.appendChild(dot);
                        dots.push(dot);
                    });
                }

                const updateState = () => {
                    const maxScrollLeft = Math.max(0, track.scrollWidth - track.clientWidth);
                    const currentScroll = track.scrollLeft;
                    const atStart = currentScroll <= 5;
                    const atEnd = currentScroll >= maxScrollLeft - 5;

                    if (prevBtn) {
                        prevBtn.disabled = atStart;
                    }
                    if (nextBtn) {
                        nextBtn.disabled = atEnd;
                    }

                    // Calculate currently active card index based on scroll position
                    const step = getItemStep();
                    const activeIndex = Math.min(
                        Math.max(0, Math.round(currentScroll / step)),
                        totalItems - 1
                    );

                    // Update pagination dots
                    if (dots.length > 0) {
                        dots.forEach((dot, idx) => {
                            const isActive = idx === activeIndex;
                            dot.setAttribute('aria-selected', isActive ? 'true' : 'false');
                            if (isActive) {
                                dot.className = 'carousel-dot is-active h-2 w-6 rounded-full bg-primary transition-all duration-200 focus-visible:outline-2 focus-visible:outline-primary';
                            } else {
                                dot.className = 'carousel-dot h-2 w-2 rounded-full bg-slate-300 hover:bg-slate-400 transition-all duration-200 focus-visible:outline-2 focus-visible:outline-primary';
                            }
                        });
                    }

                    // Update preview counter text
                    if (counterEl) {
                        const visibleCount = getVisibleCount();
                        const startNum = activeIndex + 1;
                        const endNum = Math.min(activeIndex + visibleCount, totalItems);
                        counterEl.textContent = `Menampilkan ${startNum} - ${endNum} dari ${totalItems} ${itemLabel}`;
                    }
                };

                // Previous & Next Button Controls
                if (prevBtn) {
                    prevBtn.addEventListener('click', (e) => {
                        e.preventDefault();
                        const step = getItemStep();
                        track.scrollBy({ left: -step, behavior: 'smooth' });
                    });
                }

                if (nextBtn) {
                    nextBtn.addEventListener('click', (e) => {
                        e.preventDefault();
                        const step = getItemStep();
                        track.scrollBy({ left: step, behavior: 'smooth' });
                    });
                }

                // Mouse Drag-to-Scroll
                track.addEventListener('mousedown', (e) => {
                    if (e.button !== 0) return;
                    isDown = true;
                    hasDragged = false;
                    startX = e.pageX - track.offsetLeft;
                    scrollLeft = track.scrollLeft;
                    track.style.scrollBehavior = 'auto';
                    track.style.scrollSnapType = 'none';
                });

                window.addEventListener('mousemove', (e) => {
                    if (!isDown) return;
                    const x = e.pageX - track.offsetLeft;
                    const walk = (x - startX);

                    if (Math.abs(walk) > dragThreshold) {
                        if (!hasDragged) {
                            hasDragged = true;
                            track.classList.add('is-dragging');
                        }
                        track.scrollLeft = scrollLeft - walk;
                    }
                });

                const endDrag = () => {
                    if (isDown) {
                        isDown = false;
                        track.classList.remove('is-dragging');
                        track.style.scrollBehavior = '';
                        track.style.scrollSnapType = '';
                        setTimeout(() => {
                            hasDragged = false;
                            updateState();
                        }, 50);
                    }
                };

                window.addEventListener('mouseup', endDrag);

                // Prevent opening link when user drags
                track.addEventListener('click', (e) => {
                    if (hasDragged) {
                        e.preventDefault();
                        e.stopPropagation();
                    }
                }, true);

                // Keyboard Accessibility: Arrow Left / Right
                track.addEventListener('keydown', (e) => {
                    if (e.key === 'ArrowLeft') {
                        e.preventDefault();
                        track.scrollBy({ left: -getItemStep(), behavior: 'smooth' });
                    } else if (e.key === 'ArrowRight') {
                        e.preventDefault();
                        track.scrollBy({ left: getItemStep(), behavior: 'smooth' });
                    }
                });

                let scrollTimeout;
                track.addEventListener('scroll', () => {
                    clearTimeout(scrollTimeout);
                    scrollTimeout = setTimeout(updateState, 50);
                    updateState();
                }, { passive: true });

                window.addEventListener('resize', updateState, { passive: true });

                // Initial state update
                updateState();
            };

            setupDragCarousel('carousel-berita', 'btn-berita-prev', 'btn-berita-next', 'dots-berita', 'counter-berita', 'berita');
            setupDragCarousel('carousel-acara', 'btn-acara-prev', 'btn-acara-next', 'dots-acara', 'counter-acara', 'acara');
        });
    </script>
@endsection
