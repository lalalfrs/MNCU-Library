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
    @endphp

    <div class="mx-auto flex max-w-[1570px] flex-col gap-12 px-3 pt-8 lg:gap-16 lg:px-6 lg:pt-12">

        {{-- Page header / Hero --}}
        <section aria-labelledby="judul-beranda" class="flex flex-col justify-between px-1 lg:px-[26px]">
            <div class="grid items-start gap-8 pt-1 pb-6 md:grid-cols-[1fr_auto] lg:gap-14 lg:pt-2 lg:pb-8">
                <div class="max-w-3xl pt-0.5">
                    {{-- Logo MNC University di atas Header dan subtext --}}
                    <div class="mb-3 inline-flex items-center sm:mb-4">
                        <img src="{{ asset('images/LogoTXT.png') }}" alt="Logo MNC University" class="h-12 max-w-[200px] w-auto object-contain sm:h-16 sm:max-w-[240px] lg:h-20" style="max-height: 80px; max-width: 240px;" width="240" height="80">
                    </div>

                    @auth
                        @if (Auth::user()->isSuperAdmin() || Auth::user()->isPustakawan())
                            {{-- Role: Super Admin / Pustakawan --}}
                            <h1 id="judul-beranda" class="text-[32px] leading-tight font-semibold text-primary sm:text-4xl lg:text-[46px]">
                                Selamat Bertugas, {{ Auth::user()->nama_lengkap }}!
                            </h1>
                            <p class="mt-4 text-[16px] leading-relaxed text-ink-secondary sm:text-lg lg:text-xl">
                                Kelola operasional dan sirkulasi Perpustakaan MNC University. Pantau statistik kunjungan harian pemustaka, perbarui katalog koleksi buku, publikasikan agenda acara, dan kelola rilis berita kampus.
                            </p>
                            <div class="mt-8 flex flex-wrap items-center gap-4">
                                <a href="{{ route('admin.books.index') }}" class="btn-press btn-sheen inline-flex h-12 items-center gap-2 rounded-xl bg-primary px-6 text-base font-semibold text-white shadow-sm transition-all duration-150 hover:bg-primary-dark">
                                    <i class="fa-solid fa-book-bookmark text-sm" aria-hidden="true"></i>
                                    Kelola Buku (OPAC)
                                </a>
                                <a href="{{ route('admin.acara.index') }}" class="btn-press inline-flex h-12 items-center gap-2 rounded-xl border border-line bg-white px-5 text-base font-medium text-ink transition-all duration-150 hover:border-primary/40 hover:bg-subtle">
                                    <i class="fa-solid fa-calendar-plus text-primary text-base" aria-hidden="true"></i>
                                    Kelola Acara & Poster
                                </a>
                                <a href="{{ route('admin.users.index') }}" class="btn-press inline-flex h-12 items-center gap-2 rounded-xl border border-line bg-white px-5 text-base font-medium text-ink transition-all duration-150 hover:border-primary/40 hover:bg-subtle">
                                    <i class="fa-solid fa-users-gear text-primary text-base" aria-hidden="true"></i>
                                    Data Pemustaka
                                </a>
                            </div>
                        @elseif (Auth::user()->isDosen())
                            {{-- Role: Dosen / Peneliti --}}
                            <h1 id="judul-beranda" class="text-[32px] leading-tight font-semibold text-primary sm:text-4xl lg:text-[46px]">
                                Selamat Datang, {{ Auth::user()->nama_lengkap }}!
                            </h1>
                            <p class="mt-4 text-[16px] leading-relaxed text-ink-secondary sm:text-lg lg:text-xl">
                                Akses seluruh fasilitas referensi riset, jurnal internasional terakreditasi Scopus & SINTA, portal publikasi ilmiah OJS MNC University, serta ajukan usulan pengadaan buku ajar silabus perkuliahan.
                            </p>
                            <div class="mt-8 flex flex-wrap items-center gap-4">
                                <a href="{{ route('ejournal') }}" class="btn-press btn-sheen inline-flex h-12 items-center gap-2 rounded-xl bg-primary px-6 text-base font-semibold text-white shadow-sm transition-all duration-150 hover:bg-primary-dark">
                                    <i class="fa-solid fa-newspaper text-sm" aria-hidden="true"></i>
                                    E-Journal Internasional
                                </a>
                                <a href="{{ route('ojs') }}" class="btn-press inline-flex h-12 items-center gap-2 rounded-xl border border-line bg-white px-5 text-base font-medium text-ink transition-all duration-150 hover:border-primary/40 hover:bg-subtle">
                                    <i class="fa-solid fa-layer-group text-primary text-base" aria-hidden="true"></i>
                                    Portal OJS Prodi
                                </a>
                                <a href="{{ route('repository') }}" class="btn-press inline-flex h-12 items-center gap-2 rounded-xl border border-line bg-white px-5 text-base font-medium text-ink transition-all duration-150 hover:border-primary/40 hover:bg-subtle">
                                    <i class="fa-solid fa-book-atlas text-primary text-base" aria-hidden="true"></i>
                                    Repository Riset
                                </a>
                            </div>
                        @else
                            {{-- Role: Mahasiswa (e.g. Jovin) --}}
                            <h1 id="judul-beranda" class="text-[32px] leading-tight font-semibold text-primary sm:text-4xl lg:text-[46px]">
                                Selamat Datang, {{ Auth::user()->nama_lengkap }}!
                            </h1>
                            <p class="mt-4 text-[16px] leading-relaxed text-ink-secondary sm:text-lg lg:text-xl">
                                Temukan referensi tugas akhir, buku teks perkuliahan, dan e-book pembelajaran untuk mendukung studimu di MNC University. Jelajahi katalog buku fisik OPAC dan baca koleksi digital kapan saja.
                            </p>
                            <div class="mt-8 flex flex-wrap items-center gap-4">
                                <a href="{{ route('opac') }}" class="btn-press btn-sheen inline-flex h-12 items-center gap-2 rounded-xl bg-primary px-6 text-base font-semibold text-white shadow-sm transition-all duration-150 hover:bg-primary-dark">
                                    <i class="fa-solid fa-magnifying-glass text-sm" aria-hidden="true"></i>
                                    Cari Buku di OPAC
                                </a>
                                <a href="{{ route('repository') }}" class="btn-press inline-flex h-12 items-center gap-2 rounded-xl border border-line bg-white px-5 text-base font-medium text-ink transition-all duration-150 hover:border-primary/40 hover:bg-subtle">
                                    <i class="fa-solid fa-graduation-cap text-primary text-base" aria-hidden="true"></i>
                                    Repository Skripsi
                                </a>
                                <a href="{{ route('ebook') }}" class="btn-press inline-flex h-12 items-center gap-2 rounded-xl border border-line bg-white px-5 text-base font-medium text-ink transition-all duration-150 hover:border-primary/40 hover:bg-subtle">
                                    <i class="fa-solid fa-book-open-reader text-primary text-base" aria-hidden="true"></i>
                                    E-Book Reader
                                </a>
                            </div>
                        @endif
                    @else
                        {{-- Guest / Belum Login --}}
                        <h1 id="judul-beranda" class="text-[32px] leading-tight font-semibold text-primary sm:text-4xl lg:text-[48px]">
                            Perpustakaan MNC University
                        </h1>
                        <p class="mt-4 text-[16px] leading-relaxed text-ink-secondary sm:text-lg lg:text-xl">
                            Pusat sumber belajar dan referensi akademik sivitas MNC University. Jelajahi katalog buku, repository skripsi dan riset, jurnal internasional terindeks, serta koleksi e-book resmi.
                        </p>
                        <div class="mt-8 flex flex-wrap items-center gap-4">
                            <a href="#layanan" class="btn-press btn-sheen inline-flex h-12 items-center gap-2 rounded-xl bg-primary px-6 text-base font-semibold text-white shadow-sm transition-all duration-150 hover:bg-primary-dark">
                                <i class="fa-solid fa-book-bookmark text-sm" aria-hidden="true"></i>
                                Pilih Layanan Perpustakaan
                            </a>
                            <a href="{{ route('register') }}" class="btn-press inline-flex h-12 items-center gap-2 rounded-xl border border-primary text-primary bg-white px-5 text-base font-semibold transition-all duration-150 hover:bg-primary/5">
                                <i class="fa-solid fa-user-plus text-sm"></i>
                                Daftar Anggota
                            </a>
                            <a href="https://maps.google.com/maps?q=MNC%20University" target="_blank" rel="noopener" class="btn-press inline-flex h-12 items-center gap-2 rounded-xl border border-line bg-white px-5 text-base font-medium text-ink transition-all duration-150 hover:border-primary/40 hover:bg-subtle">
                                <i class="fa-solid fa-location-dot text-primary text-base" aria-hidden="true"></i>
                                Lokasi Kampus
                            </a>
                        </div>
                    @endauth
                </div>

                {{-- Statistik Pengunjung menggantikan posisi logo di Hero --}}
                <div class="w-full md:w-[360px] lg:w-[420px] rounded-2xl border border-line bg-white p-6 shadow-sm lg:p-7">
                    <div class="flex items-center justify-between border-b border-line pb-4">
                        <div class="flex items-center gap-3">
                            <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary">
                                <i class="fa-solid fa-chart-simple text-base" aria-hidden="true"></i>
                            </span>
                            <div>
                                <span class="text-xs font-semibold uppercase tracking-wider text-muted">Aktivitas Pemustaka</span>
                                <h2 class="text-lg font-semibold text-ink sm:text-xl">Statistik Kunjungan</h2>
                            </div>
                        </div>
                        <span class="text-xs text-muted flex items-center gap-1.5">
                            <i class="fa-solid fa-clock-rotate-left text-[11px] text-primary"></i>
                            Terkini
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
            <div class="mt-6 pt-10 pb-4 lg:mt-8 lg:pt-16 lg:pb-6">
                <div class="h-0.5 bg-primary"></div>
            </div>
        </section>

        {{-- ========================================================================= --}}
        {{-- KONTEN LANDING PAGE BERDASARKAN STATUS & ROLE PENGGUNA                     --}}
        {{-- ========================================================================= --}}

        {{-- 1. TAMPILAN SUPER ADMIN & PUSTAKAWAN (Tata Kelola & Administrasi) --}}
        @if (Auth::check() && (Auth::user()->isSuperAdmin() || Auth::user()->isPustakawan()))
            <section aria-labelledby="judul-admin-workspace" class="rounded-2xl border border-primary/20 bg-gradient-to-br from-primary/5 via-white to-blue-50/20 p-6 lg:p-8 shadow-card">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between border-b border-line pb-5">
                    <div>
                        <h2 id="judul-admin-workspace" class="text-xl font-bold tracking-tight text-primary sm:text-2xl">
                            Panel Kontrol & Manajemen Perpustakaan
                        </h2>
                        <p class="text-xs text-ink-secondary sm:text-sm">
                            Selamat bertugas, <span class="font-semibold text-ink">{{ Auth::user()->nama_lengkap }}</span> ({{ Auth::user()->peran?->nama_peran }}). Kelola katalog, agenda, dan pantau operasional pemustaka.
                        </p>
                    </div>

                    <div class="flex shrink-0 items-center gap-2">
                        <button
                            type="button"
                            onclick="document.getElementById('sidebar-open').click()"
                            class="inline-flex items-center gap-2 rounded-xl bg-white border border-line px-4 py-2.5 text-xs font-semibold text-primary shadow-sm hover:border-primary transition-all"
                        >
                            <i class="fa-solid fa-bars-staggered"></i>
                            <span>Buka Menu Sidebar</span>
                        </button>
                    </div>
                </div>

                {{-- Ringkasan Metrik Koleksi & Pemustaka --}}
                <div class="mt-5 grid grid-cols-2 gap-3 sm:grid-cols-4">
                    <div class="rounded-xl border border-line bg-white p-4 shadow-xs">
                        <span class="text-[11px] font-medium text-muted uppercase">Koleksi Buku</span>
                        <div class="mt-1 text-2xl font-bold text-primary">{{ number_format($counts['total_books'] ?? 0) }}</div>
                        <span class="text-[11px] text-ink-secondary">Judul di katalog</span>
                    </div>
                    <div class="rounded-xl border border-line bg-white p-4 shadow-xs">
                        <span class="text-[11px] font-medium text-muted uppercase">Acara & Agenda</span>
                        <div class="mt-1 text-2xl font-bold text-primary">{{ number_format($counts['total_events'] ?? 0) }}</div>
                        <span class="text-[11px] text-ink-secondary">Kegiatan aktif</span>
                    </div>
                    <div class="rounded-xl border border-line bg-white p-4 shadow-xs">
                        <span class="text-[11px] font-medium text-muted uppercase">Pemustaka Terdaftar</span>
                        <div class="mt-1 text-2xl font-bold text-primary">{{ number_format($counts['total_users'] ?? 0) }}</div>
                        <span class="text-[11px] text-ink-secondary">Akun anggota</span>
                    </div>
                    <div class="rounded-xl border border-line bg-white p-4 shadow-xs">
                        <span class="text-[11px] font-medium text-muted uppercase">Berita & Rilis</span>
                        <div class="mt-1 text-2xl font-bold text-primary">{{ number_format($counts['total_news'] ?? 0) }}</div>
                        <span class="text-[11px] text-ink-secondary">Artikel diterbitkan</span>
                    </div>
                </div>

                {{-- Tombol Aksi Cepat (Quick Actions) --}}
                <div class="mt-5 flex flex-wrap items-center gap-2 border-t border-line/60 pt-4">
                    <span class="text-xs font-semibold text-muted mr-1">Aksi Cepat:</span>
                    <a href="{{ route('admin.books.create') }}" class="inline-flex items-center gap-1.5 rounded-lg bg-primary px-3 py-1.5 text-xs font-semibold text-white shadow-xs hover:bg-primary-dark transition-all">
                        <i class="fa-solid fa-plus text-[10px]"></i>
                        <span>Tambah Buku</span>
                    </a>
                    <a href="{{ route('admin.acara.create') }}" class="inline-flex items-center gap-1.5 rounded-lg border border-line bg-white px-3 py-1.5 text-xs font-semibold text-ink shadow-xs hover:border-primary hover:text-primary transition-all">
                        <i class="fa-solid fa-calendar-plus text-[10px] text-primary"></i>
                        <span>Terbitkan Acara</span>
                    </a>
                    <a href="{{ route('admin.berita.create') }}" class="inline-flex items-center gap-1.5 rounded-lg border border-line bg-white px-3 py-1.5 text-xs font-semibold text-ink shadow-xs hover:border-primary hover:text-primary transition-all">
                        <i class="fa-solid fa-newspaper text-[10px] text-primary"></i>
                        <span>Tulis Berita</span>
                    </a>
                    <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-1.5 rounded-lg border border-line bg-white px-3 py-1.5 text-xs font-semibold text-ink shadow-xs hover:border-primary hover:text-primary transition-all">
                        <i class="fa-solid fa-user-plus text-[10px] text-primary"></i>
                        <span>Tambah User</span>
                    </a>
                </div>

                {{-- Grid 4 Modul CRUD --}}
                <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    {{-- 1. Manajemen User --}}
                    <a href="{{ route('admin.users.index') }}" class="group flex flex-col justify-between rounded-2xl border border-line bg-white p-5 shadow-xs transition-all duration-200 hover:-translate-y-1 hover:border-[#7C3AED]/40 hover:shadow-raised">
                        <div class="flex items-start justify-between">
                            <div class="flex size-12 items-center justify-center rounded-xl bg-[#7C3AED]/10 text-[#7C3AED] transition-transform duration-200 group-hover:scale-110">
                                <i class="fa-solid fa-users-gear text-xl"></i>
                            </div>
                            <span class="rounded-full bg-[#7C3AED]/10 px-2 py-0.5 text-[10px] font-bold text-[#7C3AED] uppercase">MODUL</span>
                        </div>
                        <div class="mt-4">
                            <h3 class="text-base font-semibold text-ink group-hover:text-[#7C3AED] transition-colors">Manajemen User</h3>
                            <p class="mt-1 text-xs text-muted leading-relaxed">Kelola data pemustaka mahasiswa, dosen, pustakawan, dan status akun.</p>
                        </div>
                        <div class="mt-4 flex items-center gap-1.5 text-xs font-semibold text-[#7C3AED]">
                            <span>Buka Modul</span>
                            <i class="fa-solid fa-arrow-right text-[10px] transition-transform group-hover:translate-x-1"></i>
                        </div>
                    </a>

                    {{-- 2. Manajemen Buku --}}
                    <a href="{{ route('admin.books.index') }}" class="group flex flex-col justify-between rounded-2xl border border-line bg-white p-5 shadow-xs transition-all duration-200 hover:-translate-y-1 hover:border-[#2563EB]/40 hover:shadow-raised">
                        <div class="flex items-start justify-between">
                            <div class="flex size-12 items-center justify-center rounded-xl bg-[#2563EB]/10 text-[#2563EB] transition-transform duration-200 group-hover:scale-110">
                                <i class="fa-solid fa-book-bookmark text-xl"></i>
                            </div>
                            <span class="rounded-full bg-[#2563EB]/10 px-2 py-0.5 text-[10px] font-bold text-[#2563EB] uppercase">MODUL</span>
                        </div>
                        <div class="mt-4">
                            <h3 class="text-base font-semibold text-ink group-hover:text-[#2563EB] transition-colors">Manajemen Buku</h3>
                            <p class="mt-1 text-xs text-muted leading-relaxed">Input dan perbarui data katalog buku cetak, e-book, dan eksemplar rak.</p>
                        </div>
                        <div class="mt-4 flex items-center gap-1.5 text-xs font-semibold text-[#2563EB]">
                            <span>Buka Modul</span>
                            <i class="fa-solid fa-arrow-right text-[10px] transition-transform group-hover:translate-x-1"></i>
                        </div>
                    </a>

                    {{-- 3. Manajemen Acara --}}
                    <a href="{{ route('admin.acara.index') }}" class="group flex flex-col justify-between rounded-2xl border border-line bg-white p-5 shadow-xs transition-all duration-200 hover:-translate-y-1 hover:border-[#EA580C]/40 hover:shadow-raised">
                        <div class="flex items-start justify-between">
                            <div class="flex size-12 items-center justify-center rounded-xl bg-[#EA580C]/10 text-[#EA580C] transition-transform duration-200 group-hover:scale-110">
                                <i class="fa-solid fa-calendar-days text-xl"></i>
                            </div>
                            <span class="rounded-full bg-[#EA580C]/10 px-2 py-0.5 text-[10px] font-bold text-[#EA580C] uppercase">MODUL</span>
                        </div>
                        <div class="mt-4">
                            <h3 class="text-base font-semibold text-ink group-hover:text-[#EA580C] transition-colors">Manajemen Acara</h3>
                            <p class="mt-1 text-xs text-muted leading-relaxed">Publikasikan webinar, poster acara (PNG/JPG/WEBP), dan kuota pendaftaran.</p>
                        </div>
                        <div class="mt-4 flex items-center gap-1.5 text-xs font-semibold text-[#EA580C]">
                            <span>Buka Modul</span>
                            <i class="fa-solid fa-arrow-right text-[10px] transition-transform group-hover:translate-x-1"></i>
                        </div>
                    </a>

                    {{-- 4. Manajemen Berita --}}
                    <a href="{{ route('admin.berita.index') }}" class="group flex flex-col justify-between rounded-2xl border border-line bg-white p-5 shadow-xs transition-all duration-200 hover:-translate-y-1 hover:border-[#059669]/40 hover:shadow-raised">
                        <div class="flex items-start justify-between">
                            <div class="flex size-12 items-center justify-center rounded-xl bg-[#059669]/10 text-[#059669] transition-transform duration-200 group-hover:scale-110">
                                <i class="fa-solid fa-newspaper text-xl"></i>
                            </div>
                            <span class="rounded-full bg-[#059669]/10 px-2 py-0.5 text-[10px] font-bold text-[#059669] uppercase">MODUL</span>
                        </div>
                        <div class="mt-4">
                            <h3 class="text-base font-semibold text-ink group-hover:text-[#059669] transition-colors">Manajemen Berita</h3>
                            <p class="mt-1 text-xs text-muted leading-relaxed">Rilis pengumuman resmi, artikel literasi informasi, dan jadwal layanan.</p>
                        </div>
                        <div class="mt-4 flex items-center gap-1.5 text-xs font-semibold text-[#059669]">
                            <span>Buka Modul</span>
                            <i class="fa-solid fa-arrow-right text-[10px] transition-transform group-hover:translate-x-1"></i>
                        </div>
                    </a>
                </div>
            </section>

        {{-- 2. TAMPILAN MAHASISWA (Academic Learning & Resource Hub) --}}
        @elseif (Auth::check() && Auth::user()->isMahasiswa())
            <section aria-labelledby="judul-mahasiswa-hub" class="rounded-2xl border border-primary/20 bg-gradient-to-br from-blue-50/40 via-white to-primary/5 p-6 lg:p-8 shadow-card">
                <div class="flex flex-col gap-5 md:flex-row md:items-center md:justify-between border-b border-line pb-6">
                    <div class="flex items-center gap-4">
                        <div class="flex size-14 shrink-0 items-center justify-center rounded-2xl bg-primary text-xl font-bold text-white shadow-md">
                            {{ strtoupper(substr(Auth::user()->nama_lengkap, 0, 1)) }}
                        </div>
                        <div>
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-primary/10 px-2.5 py-0.5 text-xs font-semibold text-primary">
                                    <i class="fa-solid fa-graduation-cap text-[11px]"></i>
                                    Mahasiswa
                                </span>
                                <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-0.5 text-xs font-medium text-emerald-800">
                                    <span class="size-1.5 rounded-full bg-emerald-500"></span>
                                    Status keanggotaan aktif
                                </span>
                                @if (Auth::user()->prodi)
                                    <span class="rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-700">
                                        {{ Auth::user()->prodi->nama_prodi }}
                                    </span>
                                @endif
                                @if (Auth::user()->nomor_identitas)
                                    <span class="rounded bg-slate-100 px-2 py-0.5 font-mono text-[11px] font-semibold text-slate-700">
                                        NIM: {{ Auth::user()->nomor_identitas }}
                                    </span>
                                @endif
                            </div>
                            <h2 id="judul-mahasiswa-hub" class="mt-1.5 text-xl font-bold text-ink sm:text-2xl">
                                Halo, {{ Auth::user()->nama_lengkap }}!
                            </h2>
                            <p class="mt-0.5 text-xs text-ink-secondary sm:text-sm">
                                Portal belajar mandiri: temukan buku pegangan kuliah, referensi skripsi tugas akhir, dan akses e-book resmi.
                            </p>
                        </div>
                    </div>

                    {{-- Form Pencarian Cepat OPAC untuk Mahasiswa --}}
                    <form action="{{ route('opac') }}" method="GET" class="relative w-full md:w-80 lg:w-96">
                        <input
                            type="search"
                            name="q"
                            placeholder="Cari buku kuliah, skripsi, modul..."
                            class="h-11 w-full rounded-xl border border-line bg-white pl-10 pr-4 text-xs text-ink shadow-xs placeholder:text-muted focus:border-primary focus:outline-none sm:text-sm"
                        >
                        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-xs text-muted"></i>
                    </form>
                </div>

                {{-- 4 Pintasan Kebutuhan Mahasiswa --}}
                <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <a href="{{ route('opac') }}" class="group flex flex-col justify-between rounded-xl border border-line bg-white p-4 shadow-xs transition-all hover:border-primary/40 hover:shadow-sm">
                        <div class="flex items-center gap-3">
                            <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-primary">
                                <i class="fa-solid fa-book-bookmark text-base"></i>
                            </span>
                            <div>
                                <h3 class="text-sm font-semibold text-ink group-hover:text-primary transition-colors">Cari Buku OPAC</h3>
                                <p class="text-[11px] text-muted">Cek stok & letak rak buku fisik</p>
                            </div>
                        </div>
                        <span class="mt-3 flex items-center gap-1 text-[11px] font-semibold text-primary">
                            <span>Buka Katalog</span>
                            <i class="fa-solid fa-arrow-right text-[9px] transition-transform group-hover:translate-x-1"></i>
                        </span>
                    </a>

                    <a href="{{ route('repository') }}" class="group flex flex-col justify-between rounded-xl border border-line bg-white p-4 shadow-xs transition-all hover:border-[#7C3AED]/40 hover:shadow-sm">
                        <div class="flex items-center gap-3">
                            <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-[#7C3AED]/10 text-[#7C3AED]">
                                <i class="fa-solid fa-graduation-cap text-base"></i>
                            </span>
                            <div>
                                <h3 class="text-sm font-semibold text-ink group-hover:text-[#7C3AED] transition-colors">Repository Skripsi</h3>
                                <p class="text-[11px] text-muted">Referensi skripsi & tugas akhir alumni</p>
                            </div>
                        </div>
                        <span class="mt-3 flex items-center gap-1 text-[11px] font-semibold text-[#7C3AED]">
                            <span>Buka Skripsi</span>
                            <i class="fa-solid fa-arrow-right text-[9px] transition-transform group-hover:translate-x-1"></i>
                        </span>
                    </a>

                    <a href="{{ route('ebook') }}" class="group flex flex-col justify-between rounded-xl border border-line bg-white p-4 shadow-xs transition-all hover:border-[#E11D48]/40 hover:shadow-sm">
                        <div class="flex items-center gap-3">
                            <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-[#E11D48]/10 text-[#E11D48]">
                                <i class="fa-solid fa-tablet-screen-button text-base"></i>
                            </span>
                            <div>
                                <h3 class="text-sm font-semibold text-ink group-hover:text-[#E11D48] transition-colors">E-Book Reader</h3>
                                <p class="text-[11px] text-muted">Akses baca buku digital tanpa antre</p>
                            </div>
                        </div>
                        <span class="mt-3 flex items-center gap-1 text-[11px] font-semibold text-[#E11D48]">
                            <span>Buka E-Book</span>
                            <i class="fa-solid fa-arrow-right text-[9px] transition-transform group-hover:translate-x-1"></i>
                        </span>
                    </a>

                    <a href="{{ route('chat') }}" class="group flex flex-col justify-between rounded-xl border border-line bg-white p-4 shadow-xs transition-all hover:border-[#059669]/40 hover:shadow-sm">
                        <div class="flex items-center gap-3">
                            <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-[#059669]/10 text-[#059669]">
                                <i class="fa-solid fa-headset text-base"></i>
                            </span>
                            <div>
                                <h3 class="text-sm font-semibold text-ink group-hover:text-[#059669] transition-colors">Tanya Pustakawan</h3>
                                <p class="text-[11px] text-muted">Bantuan referensi & bebas pustaka</p>
                            </div>
                        </div>
                        <span class="mt-3 flex items-center gap-1 text-[11px] font-semibold text-[#059669]">
                            <span>Mulai Tanya</span>
                            <i class="fa-solid fa-arrow-right text-[9px] transition-transform group-hover:translate-x-1"></i>
                        </span>
                    </a>
                </div>

                <div class="mt-4 flex items-center gap-2 rounded-xl bg-white/80 border border-line px-4 py-2.5 text-xs text-ink-secondary">
                    <i class="fa-solid fa-circle-info text-primary text-sm shrink-0"></i>
                    <span><strong class="font-semibold text-ink">Ketentuan Peminjaman Buku:</strong> Mahasiswa aktif MNC University dapat meminjam hingga 3 eksemplar buku cetak sekaligus dengan jangka waktu 14 hari kerja.</span>
                </div>
            </section>

        {{-- 3. TAMPILAN DOSEN & PENELITI (Faculty, Research & Academic Publication) --}}
        @elseif (Auth::check() && Auth::user()->isDosen())
            <section aria-labelledby="judul-dosen-hub" class="rounded-2xl border border-primary/20 bg-gradient-to-br from-indigo-50/30 via-white to-primary/5 p-6 lg:p-8 shadow-card">
                <div class="flex flex-col gap-5 md:flex-row md:items-center md:justify-between border-b border-line pb-6">
                    <div class="flex items-center gap-4">
                        <div class="flex size-14 shrink-0 items-center justify-center rounded-2xl bg-[#00205B] text-xl font-bold text-white shadow-md">
                            {{ strtoupper(substr(Auth::user()->nama_lengkap, 0, 1)) }}
                        </div>
                        <div>
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-primary/10 px-2.5 py-0.5 text-xs font-semibold text-primary">
                                    <i class="fa-solid fa-chalkboard-user text-[11px]"></i>
                                    Dosen / Peneliti
                                </span>
                                @if (Auth::user()->prodi)
                                    <span class="rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-700">
                                        Homebase: {{ Auth::user()->prodi->nama_prodi }}
                                    </span>
                                @endif
                                @if (Auth::user()->nomor_identitas)
                                    <span class="rounded bg-slate-100 px-2 py-0.5 font-mono text-[11px] font-semibold text-slate-700">
                                        NIDN: {{ Auth::user()->nomor_identitas }}
                                    </span>
                                @endif
                            </div>
                            <h2 id="judul-dosen-hub" class="mt-1.5 text-xl font-bold text-ink sm:text-2xl">
                                Selamat Datang, {{ Auth::user()->nama_lengkap }}!
                            </h2>
                            <p class="mt-0.5 text-xs text-ink-secondary sm:text-sm">
                                Portal riset dan publikasi ilmiah sivitas tenaga pendidik MNC University. Akses database jurnal bereputasi, OJS, dan repository riset institusi.
                            </p>
                        </div>
                    </div>

                    {{-- Form Pencarian Riset Dosen --}}
                    <form action="{{ route('ejournal') }}" method="GET" class="relative w-full md:w-80 lg:w-96">
                        <input
                            type="search"
                            name="q"
                            placeholder="Cari jurnal internasional, prosiding, riset..."
                            class="h-11 w-full rounded-xl border border-line bg-white pl-10 pr-4 text-xs text-ink shadow-xs placeholder:text-muted focus:border-primary focus:outline-none sm:text-sm"
                        >
                        <i class="fa-solid fa-microscope absolute left-3.5 top-1/2 -translate-y-1/2 text-xs text-muted"></i>
                    </form>
                </div>

                {{-- 4 Pintasan Kebutuhan Dosen --}}
                <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <a href="{{ route('ejournal') }}" class="group flex flex-col justify-between rounded-xl border border-line bg-white p-4 shadow-xs transition-all hover:border-[#2563EB]/40 hover:shadow-sm">
                        <div class="flex items-center gap-3">
                            <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-[#2563EB]/10 text-[#2563EB]">
                                <i class="fa-solid fa-newspaper text-base"></i>
                            </span>
                            <div>
                                <h3 class="text-sm font-semibold text-ink group-hover:text-[#2563EB] transition-colors">E-Journal Internasional</h3>
                                <p class="text-[11px] text-muted">Database jurnal Scopus, ScienceDirect & SINTA</p>
                            </div>
                        </div>
                        <span class="mt-3 flex items-center gap-1 text-[11px] font-semibold text-[#2563EB]">
                            <span>Akses Jurnal</span>
                            <i class="fa-solid fa-arrow-right text-[9px] transition-transform group-hover:translate-x-1"></i>
                        </span>
                    </a>

                    <a href="{{ route('ojs') }}" class="group flex flex-col justify-between rounded-xl border border-line bg-white p-4 shadow-xs transition-all hover:border-[#047857]/40 hover:shadow-sm">
                        <div class="flex items-center gap-3">
                            <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-[#047857]/10 text-[#047857]">
                                <i class="fa-solid fa-layer-group text-base"></i>
                            </span>
                            <div>
                                <h3 class="text-sm font-semibold text-ink group-hover:text-[#047857] transition-colors">Portal OJS Prodi</h3>
                                <p class="text-[11px] text-muted">Submit naskah & kelola jurnal prodi</p>
                            </div>
                        </div>
                        <span class="mt-3 flex items-center gap-1 text-[11px] font-semibold text-[#047857]">
                            <span>Portal OJS</span>
                            <i class="fa-solid fa-arrow-right text-[9px] transition-transform group-hover:translate-x-1"></i>
                        </span>
                    </a>

                    <a href="{{ route('repository') }}" class="group flex flex-col justify-between rounded-xl border border-line bg-white p-4 shadow-xs transition-all hover:border-[#7C3AED]/40 hover:shadow-sm">
                        <div class="flex items-center gap-3">
                            <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-[#7C3AED]/10 text-[#7C3AED]">
                                <i class="fa-solid fa-book-atlas text-base"></i>
                            </span>
                            <div>
                                <h3 class="text-sm font-semibold text-ink group-hover:text-[#7C3AED] transition-colors">Repository Riset Dosen</h3>
                                <p class="text-[11px] text-muted">Publikasi riset & pengabdian masyarakat (PKM)</p>
                            </div>
                        </div>
                        <span class="mt-3 flex items-center gap-1 text-[11px] font-semibold text-[#7C3AED]">
                            <span>Buka Repository</span>
                            <i class="fa-solid fa-arrow-right text-[9px] transition-transform group-hover:translate-x-1"></i>
                        </span>
                    </a>

                    <a href="{{ route('kontak') }}" class="group flex flex-col justify-between rounded-xl border border-line bg-white p-4 shadow-xs transition-all hover:border-primary/40 hover:shadow-sm">
                        <div class="flex items-center gap-3">
                            <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary">
                                <i class="fa-solid fa-book-medical text-base"></i>
                            </span>
                            <div>
                                <h3 class="text-sm font-semibold text-ink group-hover:text-primary transition-colors">Usulan Buku Ajar</h3>
                                <p class="text-[11px] text-muted">Ajukan referensi silabus mata kuliah</p>
                            </div>
                        </div>
                        <span class="mt-3 flex items-center gap-1 text-[11px] font-semibold text-primary">
                            <span>Ajukan Usulan</span>
                            <i class="fa-solid fa-arrow-right text-[9px] transition-transform group-hover:translate-x-1"></i>
                        </span>
                    </a>
                </div>

                <div class="mt-4 flex items-center gap-2 rounded-xl bg-white/80 border border-line px-4 py-2.5 text-xs text-ink-secondary">
                    <i class="fa-solid fa-award text-primary text-sm shrink-0"></i>
                    <span><strong class="font-semibold text-ink">Dukungan Riset Dosen:</strong> Perpustakaan MNC University memfasilitasi akses remote jurnal internasional, asistensi cek kemiripan karya ilmiah/Turnitin, serta pelatihan penulisan sitasi bereputasi.</span>
                </div>
            </section>

        {{-- 4. TAMPILAN GUEST (Pengunjung Umum Belum Login) --}}
        @else
            <section aria-labelledby="judul-guest-welcome" class="rounded-2xl border border-line bg-gradient-to-br from-slate-50 via-white to-blue-50/20 p-6 lg:p-8 shadow-card">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between border-b border-line pb-6">
                    <div class="max-w-2xl">
                        <h2 id="judul-guest-welcome" class="text-xl font-bold tracking-tight text-ink sm:text-2xl lg:text-3xl">
                            Bergabung Bersama Komunitas Belajar MNC University
                        </h2>
                        <p class="mt-2 text-xs leading-relaxed text-ink-secondary sm:text-sm">
                            Nikmati kemudahan peminjaman koleksi buku fisik, akses e-book interaktif, referensi karya ilmiah skripsi, dan jurnal internasional terakreditasi dengan mendaftarkan akun keanggotaan Anda.
                        </p>
                    </div>

                    <div class="flex shrink-0 flex-wrap items-center gap-3">
                        <a
                            href="{{ route('register') }}"
                            class="inline-flex h-11 items-center gap-2 rounded-xl bg-primary px-5 text-xs font-semibold text-white shadow-sm hover:bg-primary-dark transition-all sm:text-sm"
                        >
                            <i class="fa-solid fa-user-plus text-xs"></i>
                            <span>Daftar Anggota</span>
                        </a>
                        <a
                            href="{{ route('login') }}"
                            class="inline-flex h-11 items-center gap-2 rounded-xl border border-line bg-white px-5 text-xs font-semibold text-ink shadow-sm hover:bg-subtle transition-all sm:text-sm"
                        >
                            <i class="fa-solid fa-arrow-right-to-bracket text-xs text-primary"></i>
                            <span>Masuk Akun</span>
                        </a>
                    </div>
                </div>

                {{-- 3 Kolom Info Pelayanan Perpustakaan --}}
                <div class="mt-6 grid gap-4 sm:grid-cols-3">
                    <div class="flex items-start gap-3 rounded-xl border border-line bg-white p-4">
                        <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-primary">
                            <i class="fa-solid fa-clock text-base"></i>
                        </span>
                        <div>
                            <h3 class="text-xs font-bold text-ink sm:text-sm">Jam Pelayanan Perpustakaan</h3>
                            <p class="mt-1 text-[11px] text-ink-secondary leading-snug">Senin &ndash; Jumat: 08:30 &ndash; 17:00 WIB<br><span class="text-muted">(Sabtu, Minggu & Libur Nasional Tutup)</span></p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3 rounded-xl border border-line bg-white p-4">
                        <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-purple-50 text-[#7C3AED]">
                            <i class="fa-solid fa-location-dot text-base"></i>
                        </span>
                        <div>
                            <h3 class="text-xs font-bold text-ink sm:text-sm">Lokasi Ruang Baca</h3>
                            <p class="mt-1 text-[11px] text-ink-secondary leading-snug">Kampus MNC University Lt. 3<br><span class="text-muted">Ruang Baca Tenang & Koleksi Referensi</span></p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3 rounded-xl border border-line bg-white p-4">
                        <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                            <i class="fa-solid fa-wifi text-base"></i>
                        </span>
                        <div>
                            <h3 class="text-xs font-bold text-ink sm:text-sm">Fasilitas Pemustaka</h3>
                            <p class="mt-1 text-[11px] text-ink-secondary leading-snug">Wi-Fi Cepat, Komputer OPAC, Ruang Diskusi, dan Koleksi Digital 24/7</p>
                        </div>
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
                <div class="mt-5 rounded-2xl border border-dashed border-line bg-subtle/70 p-8 text-center">
                    <div class="mx-auto flex size-12 items-center justify-center rounded-2xl bg-primary/10 text-primary">
                        <i class="fa-solid fa-newspaper text-xl"></i>
                    </div>
                    <h3 class="mt-3 text-sm font-semibold text-ink sm:text-base">Belum Ada Berita yang Diterbitkan</h3>
                    <p class="mt-1 text-xs text-muted sm:text-sm">Rilis informasi dan kabar terbaru perpustakaan akan tampil di sini begitu dipublikasikan.</p>
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
                    <ul class="mt-5 grid gap-5 md:grid-cols-2 lg:mt-6 lg:grid-cols-3 lg:gap-[30px]">
                @endif
                        <li>
                            <a href="{{ !empty($eventItem['slug']) && $eventItem['slug'] !== '#' ? route('acara.show', $eventItem['slug']) : route('acara.index') }}" class="group flex h-full flex-col overflow-hidden rounded-xl border border-line bg-white transition duration-200 hover:-translate-y-0.5 hover:shadow-raised motion-reduce:transition-none motion-reduce:hover:translate-y-0">
                                <div class="relative aspect-video overflow-hidden bg-gradient-to-br from-primary/10 via-track to-primary/5">
                                    @if (!empty($eventItem['image']))
                                        <img
                                            src="{{ filter_var($eventItem['image'], FILTER_VALIDATE_URL) ? $eventItem['image'] : asset($eventItem['image']) }}"
                                            alt="{{ $eventItem['title'] }}"
                                            class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
                                            loading="lazy"
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
                                    <span class="absolute top-3 left-3 inline-flex items-center gap-1.5 rounded-full bg-white/95 px-3 py-1 text-xs font-medium text-primary shadow-xs backdrop-blur-xs">
                                        <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <rect width="18" height="18" x="3" y="4" rx="2" ry="2"/>
                                            <line x1="16" x2="16" y1="2" y2="6"/>
                                            <line x1="8" x2="8" y1="2" y2="6"/>
                                            <line x1="3" x2="21" y1="10" y2="10"/>
                                        </svg>
                                        {{ $eventItem['category'] ?? 'Acara Kampus' }}
                                    </span>
                                    @if (!empty($eventItem['day']) && $eventItem['day'] !== '--')
                                        <span class="absolute top-3 right-3 inline-flex items-center gap-1 rounded-full bg-white/95 px-2.5 py-1 text-xs font-medium text-ink shadow-xs backdrop-blur-xs">
                                            <svg class="size-3 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                <circle cx="12" cy="12" r="10"/>
                                                <polyline points="12 6 12 12 16 14"/>
                                            </svg>
                                            {{ $eventItem['day'] }} {{ $eventItem['month'] }}
                                        </span>
                                    @endif
                                </div>
                                <span class="flex flex-1 flex-col p-5">
                                    <span class="text-[13px] text-muted">
                                        {{ !empty($eventItem['date']) ? $eventItem['date'] : ($eventItem['day'] . ' ' . $eventItem['month']) }}
                                        @if (!empty($eventItem['time']))
                                            &middot; {{ $eventItem['time'] }}
                                        @endif
                                    </span>
                                    <span class="mt-2 line-clamp-2 text-base font-medium text-ink group-hover:text-primary lg:text-lg">{{ $eventItem['title'] }}</span>
                                    <span class="mt-2 line-clamp-2 text-sm text-ink-secondary">{{ !empty($eventItem['excerpt']) && $eventItem['excerpt'] !== '[Ringkasan acara, maksimal dua baris]' ? $eventItem['excerpt'] : (!empty($eventItem['place']) ? 'Diselenggarakan di ' . $eventItem['place'] . '.' : 'Agenda kegiatan perpustakaan.') }}</span>
                                    @if (!empty($eventItem['place']))
                                        <span class="mt-2.5 flex items-center gap-1.5 text-xs text-muted truncate">
                                            <svg class="size-3.5 shrink-0 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/>
                                                <circle cx="12" cy="10" r="3"/>
                                            </svg>
                                            <span class="truncate">{{ $eventItem['place'] }}</span>
                                        </span>
                                    @endif
                                    <span class="mt-auto pt-4 text-sm font-medium text-primary">Lihat detail acara &rarr;</span>
                                </span>
                            </a>
                        </li>
                @if ($loop->last)
                    </ul>
                @endif
            @empty
                <div class="mt-5 rounded-2xl border border-dashed border-line bg-subtle/70 p-8 text-center">
                    <div class="mx-auto flex size-12 items-center justify-center rounded-2xl bg-primary/10 text-primary">
                        <i class="fa-solid fa-calendar-days text-xl"></i>
                    </div>
                    <h3 class="mt-3 text-sm font-semibold text-ink sm:text-base">Belum Ada Agenda Acara Mendatang</h3>
                    <p class="mt-1 text-xs text-muted sm:text-sm">Jadwal webinar, workshop, dan kegiatan literasi perpustakaan akan diumumkan di sini.</p>
                </div>
            @endforelse

            <div class="mt-5 h-1 rounded-full bg-track"></div>
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
