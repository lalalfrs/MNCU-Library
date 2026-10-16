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
                'iconBox' => 'bg-[#7C3AED]/10 text-[#7C3AED]',
                'linkColor' => 'text-[#7C3AED]',
                'hoverBorder' => 'hover:border-[#DDD6FE]',
                'accentLine' => 'from-[#8B5CF6] to-[#D946EF]',
                'typeBadge' => 'Karya Ilmiah',
            ],
            [
                'title' => 'E-Journal',
                'description' => 'Jurnal elektronik yang dilanggan perpustakaan.',
                'href' => route('ejournal'),
                'isExternal' => false,
                'faIcon' => 'fa-solid fa-newspaper',
                'iconBox' => 'bg-[#2563EB]/10 text-[#2563EB]',
                'linkColor' => 'text-[#2563EB]',
                'hoverBorder' => 'hover:border-[#BFDBFE]',
                'accentLine' => 'from-[#0EA5E9] to-[#2563EB]',
                'typeBadge' => 'Jurnal Online',
            ],
            [
                'title' => 'OJS per Prodi',
                'description' => 'Pilih program studi untuk membuka jurnal OJS-nya.',
                'href' => route('ojs'),
                'isExternal' => false,
                'faIcon' => 'fa-solid fa-layer-group',
                'iconBox' => 'bg-[#059669]/10 text-[#047857]',
                'linkColor' => 'text-[#047857]',
                'hoverBorder' => 'hover:border-[#A7F3D0]',
                'accentLine' => 'from-[#10B981] to-[#14B8A6]',
                'typeBadge' => 'Portal OJS',
            ],
            [
                'title' => 'E-Book',
                'description' => 'Buku elektronik yang bisa dibaca dari perangkat apa pun.',
                'href' => route('ebook'),
                'isExternal' => false,
                'faIcon' => 'fa-solid fa-tablet-screen-button',
                'iconBox' => 'bg-[#E11D48]/10 text-[#E11D48]',
                'linkColor' => 'text-[#E11D48]',
                'hoverBorder' => 'hover:border-[#FECDD3]',
                'accentLine' => 'from-[#F43F5E] to-[#EC4899]',
                'typeBadge' => 'Buku Digital',
            ],
        ];

        /**
         * Placeholder tampilan. TODO (integrasi): ganti dengan data berita dari controller.
         * Jika kosong, blok @empty di bawah yang tampil.
         *
         * @var array<int, array{title: string, excerpt: string, date: string}> $latestNews
         */
        $latestNews = $latestNews ?? array_fill(0, 3, [
            'title' => '[Judul berita]',
            'excerpt' => '[Ringkasan berita, maksimal dua baris]',
            'date' => '[Tanggal]',
            'slug' => '#',
        ]);

        $upcomingEvents = $upcomingEvents ?? array_fill(0, 3, [
            'title' => '[Nama acara]',
            'day' => '--',
            'month' => 'Bln',
            'time' => '[Waktu]',
            'place' => '[Tempat]',
            'slug' => '#',
        ]);

        $visitorStats = $visitorStats ?? [
            'today' => 0,
            'month' => 0,
            'total' => 0,
        ];
    @endphp

    <div class="mx-auto flex max-w-[1570px] flex-col gap-12 px-3 pt-4 lg:gap-16 lg:px-6 lg:pt-6">

        {{-- Page header / Hero --}}
        <section aria-labelledby="judul-beranda" class="flex flex-col justify-between px-1 lg:min-h-[calc(100vh-8.5rem)] lg:px-[26px]">
            <div class="my-auto grid items-center gap-8 py-6 md:grid-cols-[1fr_auto] lg:gap-14 lg:py-12">
                <div class="max-w-3xl">
                    {{-- Logo MNC University di atas Header dan subtext --}}
                    <div class="mb-5 inline-flex items-center">
                        <img src="{{ asset('images/LogoTXT.png') }}" alt="Logo MNC University" class="h-16 w-auto sm:h-20 lg:h-24">
                    </div>

                    <h1 id="judul-beranda" class="text-[32px] leading-tight font-semibold text-primary sm:text-4xl lg:text-[48px]">
                        Perpustakaan MNC University
                    </h1>
                    <p class="mt-4 text-[16px] leading-relaxed text-ink-secondary sm:text-lg lg:text-xl">
                        Pusat sumber belajar dan referensi akademik sivitas MNC University. Jelajahi katalog buku, repository skripsi dan riset, jurnal internasional terindeks, serta koleksi e-book resmi.
                    </p>
                    <div class="mt-8 flex flex-wrap items-center gap-4">
                        <a href="#layanan" class="inline-flex h-12 items-center gap-2 rounded-xl bg-primary px-6 text-base font-semibold text-white shadow-sm transition-colors duration-150 hover:bg-primary-dark">
                            <i class="fa-solid fa-book-bookmark text-sm" aria-hidden="true"></i>
                            Pilih Layanan Perpustakaan
                        </a>
                        <a href="https://maps.google.com/maps?q=MNC%20University" target="_blank" rel="noopener" class="inline-flex h-12 items-center gap-2 rounded-xl border border-line bg-white px-5 text-base font-medium text-ink transition-colors duration-150 hover:border-primary/40 hover:bg-subtle">
                            <i class="fa-solid fa-location-dot text-primary text-base" aria-hidden="true"></i>
                            Lokasi Kampus
                        </a>
                    </div>
                </div>

                {{-- Statistik Pengunjung menggantikan posisi logo di Hero --}}
                <div class="w-full md:w-[360px] lg:w-[420px] rounded-2xl border border-line bg-white p-6 shadow-sm lg:p-7">
                    <div class="flex items-center justify-between border-b border-line pb-4">
                        <div class="flex items-center gap-3">
                            <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary">
                                <i class="fa-solid fa-chart-simple text-base" aria-hidden="true"></i>
                            </span>
                            <div>
                                <span class="text-xs font-semibold uppercase tracking-wider text-primary">Live Monitoring</span>
                                <h2 class="text-lg font-semibold text-ink sm:text-xl">Statistik Pengunjung</h2>
                            </div>
                        </div>
                        <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-700">
                            <i class="fa-solid fa-circle text-[8px] text-emerald-500 animate-pulse" aria-hidden="true"></i>
                            Aktif
                        </span>
                    </div>

                    <dl class="mt-5 flex flex-col gap-3">
                        <div class="flex items-center justify-between rounded-xl border border-line bg-subtle/50 px-4 py-3.5 transition-colors hover:bg-subtle">
                            <div class="flex items-center gap-3">
                                <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                                    <i class="fa-solid fa-user-clock text-sm" aria-hidden="true"></i>
                                </span>
                                <div>
                                    <dt class="text-xs text-muted">Hari ini</dt>
                                    <span class="text-[11px] text-ink-secondary">Kunjungan unik hari ini</span>
                                </div>
                            </div>
                            <dd class="text-2xl font-bold text-primary sm:text-3xl">{{ number_format($visitorStats['today'] ?? 0) }}</dd>
                        </div>

                        <div class="flex items-center justify-between rounded-xl border border-line bg-subtle/50 px-4 py-3.5 transition-colors hover:bg-subtle">
                            <div class="flex items-center gap-3">
                                <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-purple-50 text-[#7C3AED]">
                                    <i class="fa-solid fa-calendar-check text-sm" aria-hidden="true"></i>
                                </span>
                                <div>
                                    <dt class="text-xs text-muted">Bulan ini</dt>
                                    <span class="text-[11px] text-ink-secondary">{{ now()->translatedFormat('F Y') }}</span>
                                </div>
                            </div>
                            <dd class="text-2xl font-bold text-primary sm:text-3xl">{{ number_format($visitorStats['month'] ?? 0) }}</dd>
                        </div>

                        <div class="flex items-center justify-between rounded-xl border border-line bg-subtle/50 px-4 py-3.5 transition-colors hover:bg-subtle">
                            <div class="flex items-center gap-3">
                                <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                                    <i class="fa-solid fa-users text-sm" aria-hidden="true"></i>
                                </span>
                                <div>
                                    <dt class="text-xs text-muted">Total Kunjungan</dt>
                                    <span class="text-[11px] text-ink-secondary">Akumulasi pengunjung</span>
                                </div>
                            </div>
                            <dd class="text-2xl font-bold text-primary sm:text-3xl">{{ number_format($visitorStats['total'] ?? 0) }}</dd>
                        </div>
                    </dl>

                    <div class="mt-4 flex items-center justify-between border-t border-line pt-3 text-[11px] text-muted">
                        <span class="flex items-center gap-1.5">
                            <i class="fa-solid fa-circle-check text-emerald-500 text-xs" aria-hidden="true"></i>
                            Pembaruan otomatis
                        </span>
                        <span class="font-medium text-primary flex items-center gap-1">
                            <i class="fa-solid fa-building-columns text-xs" aria-hidden="true"></i>
                            MNC University Library
                        </span>
                    </div>
                </div>
            </div>

            {{-- Garis biru di bawah hero dengan ruang kosong / gap yang lega sebelum layanan --}}
            <div class="mt-auto pt-10 pb-4 lg:pt-16 lg:pb-6">
                <div class="h-0.5 bg-primary"></div>
            </div>
        </section>

        {{-- Panel Pintasan Manajemen CRUD (Khusus Super Admin) / Status Pemustaka (Mahasiswa) --}}
        @if (Auth::check() && Auth::user()->isSuperAdmin())
            <section aria-labelledby="judul-manajemen" class="rounded-2xl border border-primary/20 bg-gradient-to-br from-primary/5 via-white to-blue-50/30 p-6 lg:p-8 shadow-card">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between border-b border-line pb-5">
                    <div>
                        <div class="inline-flex items-center gap-2 rounded-full border border-primary/20 bg-primary/10 px-3 py-1 text-xs font-semibold text-primary">
                            <span class="size-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            Mode Administrator Aktif
                        </div>
                        <h2 id="judul-manajemen" class="mt-2 text-xl font-bold tracking-tight text-primary sm:text-2xl">
                            Panel Kontrol & Manajemen Perpustakaan
                        </h2>
                        <p class="text-xs text-ink-secondary sm:text-sm">
                            Kelola seluruh modul perpustakaan dengan hak akses akun {{ Auth::user()->peran?->nama_peran }}.
                        </p>
                    </div>

                    <button
                        type="button"
                        onclick="document.getElementById('sidebar-open').click()"
                        class="inline-flex items-center gap-2 rounded-xl bg-white border border-line px-4 py-2.5 text-xs font-semibold text-primary shadow-sm hover:border-primary transition-all"
                    >
                        <i class="fa-solid fa-bars-staggered"></i>
                        <span>Buka Menu Sidebar</span>
                    </button>
                </div>

                {{-- Grid 4 Kartu CRUD --}}
                <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    {{-- 1. Manajemen User --}}
                    <a
                        href="{{ route('admin.users.index') }}"
                        class="group flex flex-col justify-between rounded-2xl border border-line bg-white p-5 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:border-[#7C3AED]/40 hover:shadow-raised"
                    >
                        <div class="flex items-start justify-between">
                            <div class="flex size-12 items-center justify-center rounded-xl bg-[#7C3AED]/10 text-[#7C3AED] transition-transform duration-200 group-hover:scale-110">
                                <i class="fa-solid fa-users-gear text-xl"></i>
                            </div>
                            <span class="rounded-full bg-[#7C3AED]/10 px-2 py-0.5 text-[10px] font-bold text-[#7C3AED] uppercase">
                                CRUD
                            </span>
                        </div>
                        <div class="mt-4">
                            <h3 class="text-base font-semibold text-ink group-hover:text-[#7C3AED] transition-colors">
                                Manajemen User
                            </h3>
                            <p class="mt-1 text-xs text-muted leading-relaxed">
                                Kelola pustakawan, mahasiswa, dosen, dan hak akses otentikasi.
                            </p>
                        </div>
                        <div class="mt-4 flex items-center gap-1.5 text-xs font-semibold text-[#7C3AED]">
                            <span>Buka Modul</span>
                            <i class="fa-solid fa-arrow-right text-[10px] transition-transform group-hover:translate-x-1"></i>
                        </div>
                    </a>

                    {{-- 2. Manajemen Buku --}}
                    <a
                        href="{{ route('admin.books.index') }}"
                        class="group flex flex-col justify-between rounded-2xl border border-line bg-white p-5 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:border-[#2563EB]/40 hover:shadow-raised"
                    >
                        <div class="flex items-start justify-between">
                            <div class="flex size-12 items-center justify-center rounded-xl bg-[#2563EB]/10 text-[#2563EB] transition-transform duration-200 group-hover:scale-110">
                                <i class="fa-solid fa-book-bookmark text-xl"></i>
                            </div>
                            <span class="rounded-full bg-[#2563EB]/10 px-2 py-0.5 text-[10px] font-bold text-[#2563EB] uppercase">
                                CRUD
                            </span>
                        </div>
                        <div class="mt-4">
                            <h3 class="text-base font-semibold text-ink group-hover:text-[#2563EB] transition-colors">
                                Manajemen Buku
                            </h3>
                            <p class="mt-1 text-xs text-muted leading-relaxed">
                                Input dan perbarui katalog buku cetak, e-book, dan eksemplar rak.
                            </p>
                        </div>
                        <div class="mt-4 flex items-center gap-1.5 text-xs font-semibold text-[#2563EB]">
                            <span>Buka Modul</span>
                            <i class="fa-solid fa-arrow-right text-[10px] transition-transform group-hover:translate-x-1"></i>
                        </div>
                    </a>

                    {{-- 3. Manajemen Acara --}}
                    <a
                        href="{{ route('admin.acara.index') }}"
                        class="group flex flex-col justify-between rounded-2xl border border-line bg-white p-5 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:border-[#EA580C]/40 hover:shadow-raised"
                    >
                        <div class="flex items-start justify-between">
                            <div class="flex size-12 items-center justify-center rounded-xl bg-[#EA580C]/10 text-[#EA580C] transition-transform duration-200 group-hover:scale-110">
                                <i class="fa-solid fa-calendar-days text-xl"></i>
                            </div>
                            <span class="rounded-full bg-[#EA580C]/10 px-2 py-0.5 text-[10px] font-bold text-[#EA580C] uppercase">
                                CRUD
                            </span>
                        </div>
                        <div class="mt-4">
                            <h3 class="text-base font-semibold text-ink group-hover:text-[#EA580C] transition-colors">
                                Manajemen Acara
                            </h3>
                            <p class="mt-1 text-xs text-muted leading-relaxed">
                                Publikasikan webinar, workshop literasi, bedah buku, dan kuota.
                            </p>
                        </div>
                        <div class="mt-4 flex items-center gap-1.5 text-xs font-semibold text-[#EA580C]">
                            <span>Buka Modul</span>
                            <i class="fa-solid fa-arrow-right text-[10px] transition-transform group-hover:translate-x-1"></i>
                        </div>
                    </a>

                    {{-- 4. Manajemen Berita --}}
                    <a
                        href="{{ route('admin.berita.index') }}"
                        class="group flex flex-col justify-between rounded-2xl border border-line bg-white p-5 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:border-[#059669]/40 hover:shadow-raised"
                    >
                        <div class="flex items-start justify-between">
                            <div class="flex size-12 items-center justify-center rounded-xl bg-[#059669]/10 text-[#059669] transition-transform duration-200 group-hover:scale-110">
                                <i class="fa-solid fa-newspaper text-xl"></i>
                            </div>
                            <span class="rounded-full bg-[#059669]/10 px-2 py-0.5 text-[10px] font-bold text-[#059669] uppercase">
                                CRUD
                            </span>
                        </div>
                        <div class="mt-4">
                            <h3 class="text-base font-semibold text-ink group-hover:text-[#059669] transition-colors">
                                Manajemen Berita
                            </h3>
                            <p class="mt-1 text-xs text-muted leading-relaxed">
                                Rilis artikel, pengumuman jadwal operasional, dan info perpustakaan.
                            </p>
                        </div>
                        <div class="mt-4 flex items-center gap-1.5 text-xs font-semibold text-[#059669]">
                            <span>Buka Modul</span>
                            <i class="fa-solid fa-arrow-right text-[10px] transition-transform group-hover:translate-x-1"></i>
                        </div>
                    </a>
                </div>
            </section>
        @elseif (Auth::check())
            {{-- Kartu Status Pemustaka Mahasiswa / Sivitas --}}
            <section aria-labelledby="judul-pemustaka" class="rounded-2xl border border-line bg-gradient-to-br from-primary/5 via-white to-amber-50/20 p-6 lg:p-8 shadow-card">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center gap-4">
                        <div class="flex size-14 shrink-0 items-center justify-center rounded-2xl bg-primary text-xl font-bold text-white shadow-md">
                            {{ strtoupper(substr(Auth::user()->nama_lengkap, 0, 1)) }}
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-primary/10 px-2.5 py-0.5 text-xs font-semibold text-primary">
                                    <i class="fa-solid fa-graduation-cap text-[11px]"></i>
                                    {{ Auth::user()->peran?->nama_peran }}
                                </span>
                                @if (Auth::user()->id_pengguna)
                                    <span class="rounded bg-slate-100 px-2 py-0.5 font-mono text-[11px] font-semibold text-slate-700">
                                        ID: {{ Auth::user()->id_pengguna }}
                                    </span>
                                @endif
                            </div>
                            <h2 id="judul-pemustaka" class="mt-1 text-lg font-bold text-ink sm:text-xl">
                                Halo, {{ Auth::user()->nama_lengkap }}!
                            </h2>
                            <p class="text-xs text-ink-secondary sm:text-sm">
                                Status keanggotaan aktif. Akses penuh pencarian katalog OPAC, peminjaman buku, repository skripsi, dan koleksi e-journal digital MNC University.
                            </p>
                        </div>
                    </div>

                    <div class="flex shrink-0 items-center gap-2">
                        <a
                            href="{{ route('opac') }}"
                            class="inline-flex items-center gap-2 rounded-xl bg-primary px-4 py-2.5 text-xs font-semibold text-white shadow-sm hover:bg-primary-dark transition-all"
                        >
                            <i class="fa-solid fa-magnifying-glass text-xs"></i>
                            <span>Cari Buku OPAC</span>
                        </a>
                        <a
                            href="{{ route('ejournal') }}"
                            class="inline-flex items-center gap-2 rounded-xl border border-line bg-white px-4 py-2.5 text-xs font-semibold text-ink shadow-sm hover:bg-subtle transition-all"
                        >
                            <i class="fa-solid fa-newspaper text-xs text-primary"></i>
                            <span>E-Journal</span>
                        </a>
                    </div>
                </div>
            </section>
        @endif

        {{-- Komponen card: layanan --}}
        <section id="layanan" aria-labelledby="judul-layanan" class="scroll-mt-28 rounded-2xl border border-line bg-white p-6 lg:p-8">
            <div class="flex flex-wrap items-end justify-between gap-2">
                <div>
                    <h2 id="judul-layanan" class="text-lg font-medium text-ink lg:text-2xl">Layanan Perpustakaan</h2>
                    <p class="mt-1 text-sm text-muted lg:text-base">Pilih layanan sesuai koleksi yang ingin kamu cari.</p>
                </div>
            </div>

            {{-- Kartu utama: satu-satunya layanan yang berupa halaman di situs ini, jadi diberi porsi lebih besar. --}}
            <a href="{{ route('opac') }}" class="group mt-6 flex flex-col gap-5 rounded-2xl border border-line bg-white p-6 transition duration-200 hover:-translate-y-0.5 hover:border-primary/40 hover:shadow-raised motion-reduce:transition-none motion-reduce:hover:translate-y-0 md:flex-row md:items-center md:gap-7 lg:p-8">
                <span class="flex size-16 shrink-0 items-center justify-center rounded-2xl bg-primary/10 text-primary transition-transform duration-200 group-hover:scale-105 lg:size-20">
                    <i class="fa-solid fa-book-open-reader text-3xl lg:text-4xl text-primary" aria-hidden="true"></i>
                </span>
                <span class="flex-1">
                    <span class="block text-xl font-medium text-ink lg:text-2xl">Katalog OPAC</span>
                    <span class="mt-1.5 block text-sm leading-relaxed text-ink-secondary lg:text-base">Cari buku teks, monograf, dan referensi fisik. Cek ketersediaan eksemplar dan lokasi nomor panggil rak secara real-time.</span>
                </span>
                <span class="inline-flex items-center gap-2 text-base font-semibold text-primary lg:text-lg">
                    Buka Katalog
                    <i class="fa-solid fa-arrow-right text-base transition-transform duration-200 group-hover:translate-x-1 motion-reduce:transition-none" aria-hidden="true"></i>
                </span>
            </a>

            <ul class="mt-5 grid gap-5 sm:grid-cols-2 lg:mt-6 lg:grid-cols-4 lg:gap-6">
                @foreach ($digitalServices as $digitalService)
                    <li>
                        <a
                            href="{{ $digitalService['href'] }}"
                            @if ($digitalService['isExternal']) target="_blank" rel="noopener" @endif
                            class="group flex h-full flex-col rounded-xl border border-line bg-white p-5 transition duration-200 hover:-translate-y-0.5 hover:shadow-raised motion-reduce:transition-none motion-reduce:hover:translate-y-0 {{ $digitalService['hoverBorder'] }}"
                        >
                            <span class="flex items-start gap-4">
                                <span class="flex size-[60px] shrink-0 items-center justify-center rounded-xl {{ $digitalService['iconBox'] }} transition-transform duration-200 group-hover:scale-105">
                                    <i class="{{ $digitalService['faIcon'] }} text-2xl lg:text-3xl" aria-hidden="true"></i>
                                </span>
                                <span>
                                    <span class="block text-base font-medium text-ink lg:text-lg">{{ $digitalService['title'] }}</span>
                                    <span class="mt-1 block text-xs leading-relaxed text-muted lg:text-sm">{{ $digitalService['description'] }}</span>
                                </span>
                            </span>

                            <span class="mt-auto block pt-4">
                                <span class="block h-px bg-line"></span>
                                <span class="mt-4 flex items-center justify-between text-sm">
                                    <span class="text-ink">{{ $digitalService['typeBadge'] ?? ($digitalService['isExternal'] ? 'Situs eksternal' : 'Layanan digital') }}</span>
                                    <span class="inline-flex items-center gap-1.5 font-medium {{ $digitalService['linkColor'] }}">
                                        Buka
                                        @if ($digitalService['isExternal'])
                                            <i class="fa-solid fa-arrow-up-right-from-square text-xs" aria-hidden="true"></i>
                                            <span class="sr-only">(tab baru)</span>
                                        @else
                                            <i class="fa-solid fa-chevron-right text-xs transition-transform duration-200 group-hover:translate-x-0.5 motion-reduce:transition-none" aria-hidden="true"></i>
                                        @endif
                                    </span>
                                </span>
                                <span class="mt-3 block h-1 rounded-full bg-linear-to-r {{ $digitalService['accentLine'] }}"></span>
                            </span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </section>

        {{-- Card berita --}}
        <section aria-labelledby="judul-berita" class="rounded-2xl border border-line bg-white p-5 lg:p-[30px]">
            <div class="flex flex-wrap items-end justify-between gap-3">
                <div>
                    <h2 id="judul-berita" class="text-base font-medium text-ink lg:text-xl">Berita Perpustakaan</h2>
                    <p class="mt-1 text-[13px] text-muted lg:text-base">Kabar terbaru dari perpustakaan MNC University.</p>
                </div>
                {{-- TODO (integrasi): arahkan ke halaman berita lengkap. --}}
                <a href="{{ route('berita.index') }}" class="inline-flex min-h-11 items-center gap-1 rounded-lg text-sm font-medium text-primary hover:underline lg:text-base">
                    Semua berita
                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 6 6 6-6 6" /></svg>
                </a>
            </div>

            @forelse ($latestNews as $newsItem)
                @if ($loop->first)
                    <ul class="mt-5 grid gap-5 md:grid-cols-2 lg:mt-6 lg:grid-cols-3 lg:gap-[30px]">
                @endif
                        <li>
                            {{-- TODO (integrasi): arahkan ke detail berita. --}}
                            <a href="{{ !empty($newsItem['slug']) && $newsItem['slug'] !== '#' ? route('berita.show', $newsItem['slug']) : route('berita.index') }}" class="group flex h-full flex-col overflow-hidden rounded-xl border border-line bg-white transition duration-200 hover:-translate-y-0.5 hover:shadow-raised motion-reduce:transition-none motion-reduce:hover:translate-y-0">
                                <div class="relative aspect-video overflow-hidden bg-gradient-to-br from-primary/10 via-track to-primary/5">
                                    @if (!empty($newsItem['image']))
                                        <img
                                            src="{{ filter_var($newsItem['image'], FILTER_VALIDATE_URL) ? $newsItem['image'] : asset($newsItem['image']) }}"
                                            alt="{{ $newsItem['title'] }}"
                                            class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
                                            loading="lazy"
                                        >
                                    @endif
                                    <span class="absolute top-3 left-3 inline-flex items-center gap-1.5 rounded-full bg-white/95 px-3 py-1 text-xs font-medium text-primary shadow-xs backdrop-blur-xs">
                                        <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/><path d="M18 14h-8"/><path d="M15 18h-5"/><path d="M10 6h8v4h-8V6Z"/></svg>
                                        {{ $newsItem['category'] ?? 'Berita Kampus' }}
                                    </span>
                                </div>
                                <span class="flex flex-1 flex-col p-5">
                                    <span class="text-[13px] text-muted">{{ $newsItem['date'] }}</span>
                                    <span class="mt-2 line-clamp-2 text-base font-medium text-ink group-hover:text-primary lg:text-lg">{{ $newsItem['title'] }}</span>
                                    <span class="mt-2 line-clamp-2 text-sm text-ink-secondary">{{ $newsItem['excerpt'] }}</span>
                                    <span class="mt-auto pt-4 text-sm font-medium text-primary">Baca berita &rarr;</span>
                                </span>
                            </a>
                        </li>
                @if ($loop->last)
                    </ul>
                @endif
            @empty
                <div class="mt-5 rounded-lg border border-line bg-subtle p-6 text-center">
                    <p class="text-base text-ink">Belum ada berita yang diterbitkan.</p>
                    <p class="mt-1 text-[13px] text-muted">Berita baru dari perpustakaan akan muncul di sini.</p>
                </div>
            @endforelse

            <div class="mt-5 h-1 rounded-full bg-track"></div>
        </section>

        {{-- Acara / event --}}
        <section aria-labelledby="judul-acara" class="rounded-2xl border border-line bg-white p-5 lg:p-[30px]">
            <div class="flex flex-wrap items-end justify-between gap-3">
                <div>
                    <h2 id="judul-acara" class="text-base font-medium text-ink lg:text-xl">Acara Mendatang</h2>
                    <p class="mt-1 text-[13px] text-muted lg:text-base">Kelas literasi, bedah buku, dan kegiatan perpustakaan lainnya.</p>
                </div>
                {{-- TODO (integrasi): arahkan ke halaman event lengkap. --}}
                <a href="{{ route('acara.index') }}" class="inline-flex min-h-11 items-center gap-1 rounded-lg text-sm font-medium text-primary hover:underline lg:text-base">
                    Semua acara
                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 6 6 6-6 6" /></svg>
                </a>
            </div>

            @forelse ($upcomingEvents as $eventItem)
                @if ($loop->first)
                    <ul class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @endif
                        <li>
                            {{-- TODO (integrasi): arahkan ke detail event. --}}
                            <a href="{{ !empty($eventItem['slug']) && $eventItem['slug'] !== '#' ? route('acara.show', $eventItem['slug']) : route('acara.index') }}" class="group flex items-center gap-4 rounded-xl border border-line bg-white p-4 transition-colors duration-200 hover:border-primary/30 hover:bg-subtle">
                                <span class="flex size-14 shrink-0 flex-col items-center justify-center rounded-lg bg-primary text-white">
                                    <span class="text-lg leading-none font-semibold">{{ $eventItem['day'] }}</span>
                                    <span class="mt-1 text-[11px]">{{ $eventItem['month'] }}</span>
                                </span>
                                <span class="min-w-0 flex-1">
                                    <span class="block truncate text-[15px] font-medium text-ink group-hover:text-primary lg:text-base">{{ $eventItem['title'] }}</span>
                                    <span class="mt-0.5 block truncate text-[13px] text-muted">{{ $eventItem['time'] }} &middot; {{ $eventItem['place'] }}</span>
                                </span>
                                <span class="hidden shrink-0 text-sm font-medium text-primary sm:inline">Lihat &rarr;</span>
                            </a>
                        </li>
                @if ($loop->last)
                    </ul>
                @endif
            @empty
                <div class="mt-5 rounded-lg border border-line bg-subtle p-6 text-center">
                    <p class="text-base text-ink">Belum ada acara terjadwal.</p>
                    <p class="mt-1 text-[13px] text-muted">Jadwal kegiatan perpustakaan akan diumumkan di sini.</p>
                </div>
            @endforelse
        </section>

        {{-- Map --}}
        <section aria-labelledby="judul-lokasi" class="rounded-2xl border border-line bg-white p-5 lg:p-[30px]">
            <div class="flex flex-wrap items-end justify-between gap-3">
                <div>
                    <h2 id="judul-lokasi" class="text-base font-medium text-ink lg:text-xl">Lokasi Perpustakaan</h2>
                    <p class="mt-1 text-[13px] text-muted lg:text-base">[Alamat lengkap perpustakaan]</p>
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
@endsection
