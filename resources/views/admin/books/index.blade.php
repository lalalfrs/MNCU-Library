@extends('layouts.app')

@section('title', 'Manajemen Buku & Katalog')
@section('description', 'Kelola koleksi buku fisik, e-book, kategori, penerbit, nomor ISBN, dan ketersediaan stok eksemplar perpustakaan MNC University.')

@section('content')
    <div class="mx-auto flex w-full max-w-[1570px] flex-1 flex-col gap-6 px-3 sm:px-4 lg:px-6 xl:px-8 py-4 sm:py-6 lg:py-8 responsive-desktop-container">
        {{-- Breadcrumb & Header (Style Visual Sebelumnya) --}}
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <nav aria-label="Breadcrumb" class="flex items-center gap-2 text-xs text-muted sm:text-sm">
                    <a href="{{ route('beranda') }}" class="hover:text-primary">Beranda</a>
                    <svg class="size-3.5 text-muted/60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
                    <span class="text-ink-secondary">Panel Manajemen</span>
                    <svg class="size-3.5 text-muted/60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
                    <span class="font-medium text-ink">Manajemen Buku</span>
                </nav>
                <h1 class="mt-1 text-2xl font-bold tracking-tight text-primary sm:text-3xl">
                    Manajemen Katalog Koleksi Buku
                </h1>
                <p class="mt-0.5 text-xs text-ink-secondary sm:text-sm">
                    Kelola koleksi buku cetak, e-book, data bibliografi, serta visualisasi stok eksemplar fisik secara real-time.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2.5">
                {{-- Switcher Mode Tampilan: Row (Tabel) vs Grid (Kartu) --}}
                <div class="flex items-center rounded-xl border border-line bg-subtle/80 p-1 shadow-inner">
                    <button
                        type="button"
                        id="btn-view-table"
                        onclick="switchBookView('table')"
                        class="inline-flex h-9 items-center gap-1.5 rounded-lg px-3 text-xs font-semibold transition-all duration-150 {{ $viewMode === 'table' ? 'bg-white text-primary shadow-sm' : 'text-muted hover:text-ink' }}"
                        title="Tampilan Tabel (Row)"
                    >
                        <i class="fa-solid fa-table-list text-xs"></i>
                        <span class="hidden sm:inline">Tabel</span>
                    </button>
                    <button
                        type="button"
                        id="btn-view-grid"
                        onclick="switchBookView('grid')"
                        class="inline-flex h-9 items-center gap-1.5 rounded-lg px-3 text-xs font-semibold transition-all duration-150 {{ $viewMode === 'grid' ? 'bg-white text-primary shadow-sm' : 'text-muted hover:text-ink' }}"
                        title="Tampilan Kartu (Grid)"
                    >
                        <i class="fa-solid fa-border-all text-xs"></i>
                        <span class="hidden sm:inline">Grid</span>
                    </button>
                </div>

                {{-- Tombol Lihat di OPAC --}}
                <a
                    href="{{ route('opac') }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="inline-flex h-10 items-center gap-2 rounded-xl border border-line bg-white px-3.5 text-xs font-semibold text-ink shadow-xs transition-all hover:bg-subtle hover:text-primary sm:h-11 sm:text-sm"
                    title="Buka Katalog OPAC Publik di Tab Baru"
                >
                    <i class="fa-solid fa-book-open-reader text-xs text-primary"></i>
                    <span class="hidden sm:inline">Katalog OPAC</span>
                </a>

                {{-- Tombol Kotak Sampah (Recycle Bin) --}}
                <a
                    href="{{ route('admin.books.trash') }}"
                    class="inline-flex h-10 items-center gap-2 rounded-xl border border-line bg-white px-3.5 text-xs font-semibold text-ink shadow-xs transition-all hover:bg-rose-50 hover:border-rose-200 hover:text-rose-700 sm:h-11 sm:text-sm"
                    title="Buka Kotak Sampah Koleksi Buku (Recycle Bin)"
                >
                    <i class="fa-solid fa-trash-can text-xs text-rose-500"></i>
                    <span class="hidden sm:inline">Kotak Sampah</span>
                    @if (($trashedCount ?? 0) > 0)
                        <span class="flex size-5 items-center justify-center rounded-full bg-rose-500 text-[10px] font-bold text-white shadow-xs">
                            {{ $trashedCount }}
                        </span>
                    @endif
                </a>

                {{-- Tombol Tambah Buku --}}
                <a
                    href="{{ route('admin.books.create') }}"
                    class="inline-flex h-10 items-center gap-2 rounded-xl bg-primary px-4 text-xs font-semibold text-white shadow-sm transition-all hover:bg-primary-dark active:scale-[0.98] sm:h-11 sm:text-sm"
                >
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>Tambah Koleksi Buku</span>
                </a>
            </div>
        </div>

        {{-- 4 Kartu Metrik Inventaris Stok Global (Klik untuk Filter Stok Koleksi) --}}
        @php
            $isFilterSemua = ($statusStok ?? '') === '';
            $isFilterEksemplar = ($statusStok ?? '') === 'eksemplar';
            $isFilterTersedia = ($statusStok ?? '') === 'tersedia';
            $isFilterDipinjam = ($statusStok ?? '') === 'dipinjam';

            $urlSemua = route('admin.books.index', array_merge(request()->except(['stok', 'page']), ['view' => $viewMode]));
            $urlEksemplar = route('admin.books.index', array_merge(request()->except(['stok', 'page']), ['stok' => $isFilterEksemplar ? '' : 'eksemplar', 'view' => $viewMode]));
            $urlTersedia = route('admin.books.index', array_merge(request()->except(['stok', 'page']), ['stok' => $isFilterTersedia ? '' : 'tersedia', 'view' => $viewMode]));
            $urlDipinjam = route('admin.books.index', array_merge(request()->except(['stok', 'page']), ['stok' => $isFilterDipinjam ? '' : 'dipinjam', 'view' => $viewMode]));
        @endphp

        <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
            {{-- Kartu 1: Total Judul --}}
            <div class="group relative flex flex-col justify-between rounded-2xl border p-4 shadow-card transition-all duration-200 {{ $isFilterSemua ? 'border-primary bg-primary/[0.03] ring-2 ring-primary/20 shadow-md' : 'border-line bg-white hover:border-primary/50 hover:shadow-md hover:-translate-y-0.5' }}">
                <a href="{{ $urlSemua }}" class="absolute inset-0 z-10 rounded-2xl focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary" aria-label="Tampilkan semua judul buku terdaftar"></a>
                <div>
                    <div class="flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2.5">
                            <span class="flex size-9 items-center justify-center rounded-xl bg-primary/10 text-primary transition-transform group-hover:scale-105">
                                <i class="fa-solid fa-book-bookmark text-sm"></i>
                            </span>
                            <span class="text-[11px] font-semibold uppercase tracking-wider text-muted group-hover:text-primary transition-colors">Total Judul</span>
                        </div>
                        @if ($isFilterSemua)
                            <span class="inline-flex items-center gap-1 rounded-full bg-primary/10 px-2 py-0.5 text-[10px] font-bold text-primary">
                                <i class="fa-solid fa-check text-[9px]"></i> Semua
                            </span>
                        @else
                            <span class="text-xs text-muted/60 group-hover:text-primary transition-colors">
                                <i class="fa-solid fa-arrow-right text-[11px]"></i>
                            </span>
                        @endif
                    </div>
                    <div class="mt-2 text-2xl font-bold text-primary">{{ number_format($totalBooks ?? 0) }}</div>
                </div>
                <div class="mt-1 flex items-center justify-between text-[11px] text-muted">
                    <span>Judul buku terdaftar</span>
                    <span class="font-medium text-primary group-hover:underline">Lihat Semua</span>
                </div>
            </div>

            {{-- Kartu 2: Total Eksemplar --}}
            <div class="group relative flex flex-col justify-between rounded-2xl border p-4 shadow-card transition-all duration-200 {{ $isFilterEksemplar ? 'border-[#7C3AED] bg-[#7C3AED]/[0.03] ring-2 ring-[#7C3AED]/20 shadow-md' : 'border-line bg-white hover:border-[#7C3AED]/50 hover:shadow-md hover:-translate-y-0.5' }}">
                <a href="{{ $urlEksemplar }}" class="absolute inset-0 z-10 rounded-2xl focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#7C3AED]" aria-label="Filter koleksi buku yang memiliki eksemplar fisik"></a>
                <div>
                    <div class="flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2.5">
                            <span class="flex size-9 items-center justify-center rounded-xl bg-[#7C3AED]/10 text-[#7C3AED] transition-transform group-hover:scale-105">
                                <i class="fa-solid fa-boxes-stacked text-sm"></i>
                            </span>
                            <span class="text-[11px] font-semibold uppercase tracking-wider text-muted group-hover:text-[#7C3AED] transition-colors">Total Eksemplar</span>
                        </div>
                        @if ($isFilterEksemplar)
                            <span class="inline-flex items-center gap-1 rounded-full bg-[#7C3AED]/10 px-2 py-0.5 text-[10px] font-bold text-[#7C3AED]">
                                <i class="fa-solid fa-check text-[9px]"></i> Aktif
                            </span>
                        @else
                            <span class="text-xs text-muted/60 group-hover:text-[#7C3AED] transition-colors">
                                <i class="fa-solid fa-arrow-right text-[11px]"></i>
                            </span>
                        @endif
                    </div>
                    <div class="mt-2 text-2xl font-bold text-ink">{{ number_format($totalCopies ?? 0) }}</div>
                </div>
                <div class="mt-1 flex items-center justify-between text-[11px] text-muted">
                    <span>Buku fisik di perpustakaan</span>
                    <span class="font-medium text-[#7C3AED] group-hover:underline">{{ $isFilterEksemplar ? 'Reset' : 'Filter Fisik' }}</span>
                </div>
            </div>

            {{-- Kartu 3: Stok Siap Pinjam --}}
            <div class="group relative flex flex-col justify-between rounded-2xl border p-4 shadow-card transition-all duration-200 {{ $isFilterTersedia ? 'border-emerald-500 bg-emerald-50/80 ring-2 ring-emerald-500/25 shadow-md' : 'border-emerald-200 bg-emerald-50/40 hover:border-emerald-400 hover:shadow-md hover:-translate-y-0.5' }}">
                <a href="{{ $urlTersedia }}" class="absolute inset-0 z-10 rounded-2xl focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-600" aria-label="Filter buku dengan stok siap pinjam di rak"></a>
                <div>
                    <div class="flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2.5">
                            <span class="flex size-9 items-center justify-center rounded-xl bg-emerald-100 text-emerald-800 transition-transform group-hover:scale-105">
                                <i class="fa-solid fa-circle-check text-sm"></i>
                            </span>
                            <span class="text-[11px] font-semibold uppercase tracking-wider text-emerald-800">Stok Siap Pinjam</span>
                        </div>
                        @if ($isFilterTersedia)
                            <span class="inline-flex items-center gap-1 rounded-full bg-emerald-200/90 px-2 py-0.5 text-[10px] font-bold text-emerald-900">
                                <i class="fa-solid fa-check text-[9px]"></i> Aktif
                            </span>
                        @else
                            <span class="text-xs text-emerald-700/60 group-hover:text-emerald-800 transition-colors">
                                <i class="fa-solid fa-arrow-right text-[11px]"></i>
                            </span>
                        @endif
                    </div>
                    <div class="mt-2 text-2xl font-bold text-emerald-900">{{ number_format($availableCopies ?? 0) }}</div>
                </div>
                <div class="mt-1 flex items-center justify-between text-[11px] text-emerald-700">
                    <span>Tersedia di rak</span>
                    <span class="font-medium text-emerald-800 group-hover:underline">{{ $isFilterTersedia ? 'Reset' : 'Filter Siap Pinjam' }}</span>
                </div>
            </div>

            {{-- Kartu 4: Sedang Dipinjam --}}
            <div class="group relative flex flex-col justify-between rounded-2xl border p-4 shadow-card transition-all duration-200 {{ $isFilterDipinjam ? 'border-amber-500 bg-amber-50/80 ring-2 ring-amber-500/25 shadow-md' : 'border-amber-200 bg-amber-50/40 hover:border-amber-400 hover:shadow-md hover:-translate-y-0.5' }}">
                <a href="{{ $urlDipinjam }}" class="absolute inset-0 z-10 rounded-2xl focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-amber-600" aria-label="Filter buku yang sedang dipinjam pemustaka"></a>
                <div>
                    <div class="flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2.5">
                            <span class="flex size-9 items-center justify-center rounded-xl bg-amber-100 text-amber-800 transition-transform group-hover:scale-105">
                                <i class="fa-solid fa-clock-rotate-left text-sm"></i>
                            </span>
                            <span class="text-[11px] font-semibold uppercase tracking-wider text-amber-800">Sedang Dipinjam</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <a
                                href="{{ route('admin.peminjaman.index', ['status' => 'dipinjam']) }}"
                                class="relative z-20 inline-flex items-center gap-1 rounded-lg border border-amber-300/80 bg-amber-100/90 px-2 py-0.5 text-[10px] font-semibold text-amber-800 hover:bg-amber-200 hover:text-amber-950 transition-colors shadow-2xs"
                                title="Buka modul sirkulasi peminjaman di tab baru"
                            >
                                <span>Sirkulasi</span>
                                <i class="fa-solid fa-arrow-up-right-from-square text-[8px]"></i>
                            </a>
                            @if ($isFilterDipinjam)
                                <span class="inline-flex items-center gap-1 rounded-full bg-amber-200/90 px-2 py-0.5 text-[10px] font-bold text-amber-900">
                                    <i class="fa-solid fa-check text-[9px]"></i> Aktif
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="mt-2 text-2xl font-bold text-amber-900">{{ number_format($borrowedCopies ?? 0) }}</div>
                </div>
                <div class="mt-1 flex items-center justify-between text-[11px] text-amber-700">
                    <span>Di tangan pemustaka</span>
                    <span class="font-medium text-amber-800 group-hover:underline">{{ $isFilterDipinjam ? 'Reset' : 'Filter Dipinjam' }}</span>
                </div>
            </div>
        </div>

        {{-- Simplified Search Bar & Pencarian Spesifik (Dengan Style Kartu Sebelumnya) --}}
        @php
            $hasAdminActiveFilter = $q !== '' || ! empty($kategoriList) || ! empty($jenisList) || ! empty($lokasiFilterList) || ($statusStok ?? '') !== '' || ($tahunDari ?? '') !== '' || ($tahunSampai ?? '') !== '' || ($filterJudul ?? '') !== '' || ($filterPengarang ?? '') !== '' || ($filterIsbn ?? '') !== '';
        @endphp
        <div class="rounded-2xl border border-line bg-white p-4 shadow-card">
            <form method="GET" action="{{ route('admin.books.index') }}" id="filter-form" class="flex flex-col gap-3">
                <input type="hidden" name="view" id="filter-view-input" value="{{ $viewMode }}">
                <input type="hidden" name="sort" id="filter-sort-input" value="{{ $sort ?? 'terbaru' }}">
                @if (($statusStok ?? '') !== '')
                    <input type="hidden" name="stok" value="{{ $statusStok }}">
                @endif

                <div class="flex flex-col gap-2.5 sm:flex-row sm:items-center">
                    <div class="relative flex-1">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted">
                            <i class="fa-solid fa-magnifying-glass text-xs"></i>
                        </span>
                        <input
                            type="text"
                            name="q"
                            value="{{ $q }}"
                            placeholder="Cari judul buku, ISBN, pengarang, nomor barcode, atau nomor panggil..."
                            class="h-10 w-full rounded-xl border border-line bg-subtle/50 pr-9 pl-10 text-xs text-ink placeholder:text-muted focus:border-primary focus:bg-white focus:outline-none sm:text-sm"
                        >
                        @if ($q !== '')
                            <a
                                href="{{ route('admin.books.index', array_merge(request()->except('q'), ['view' => $viewMode])) }}"
                                class="absolute right-2.5 top-1/2 -translate-y-1/2 flex size-6 items-center justify-center rounded-md text-muted hover:bg-slate-200 hover:text-ink transition-colors"
                                title="Bersihkan Kata Kunci"
                            >
                                <i class="fa-solid fa-xmark text-xs"></i>
                            </a>
                        @endif
                    </div>

                    <div class="flex items-center gap-2 shrink-0">
                        <button
                            type="submit"
                            class="inline-flex h-10 items-center justify-center gap-2 rounded-xl bg-primary px-4 text-xs font-semibold text-white shadow-xs transition-colors hover:bg-primary-dark sm:text-sm"
                        >
                            <i class="fa-solid fa-magnifying-glass text-xs"></i>
                            <span>Cari</span>
                        </button>

                        <button
                            type="button"
                            onclick="toggleAdminAdvancedSearch()"
                            class="inline-flex h-10 items-center justify-center gap-1.5 rounded-xl border border-line bg-white px-3.5 text-xs font-semibold text-ink transition-colors hover:border-primary hover:text-primary sm:text-sm"
                            title="Buka Pencarian Spesifik"
                        >
                            <i class="fa-solid fa-sliders text-xs text-primary"></i>
                            <span>Filter</span>
                        </button>

                        @if ($hasAdminActiveFilter)
                            <a
                                href="{{ route('admin.books.index', ['view' => $viewMode]) }}"
                                class="inline-flex h-10 items-center justify-center rounded-xl border border-line px-3 text-xs font-medium text-muted transition-colors hover:bg-subtle hover:text-ink sm:text-sm"
                                title="Reset Filter"
                            >
                                <i class="fa-solid fa-rotate-left"></i>
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Panel Expandable: Pencarian Spesifik Admin --}}
                <div id="admin-advanced-search-panel" class="hidden rounded-xl border border-line bg-subtle/40 p-4 text-left text-ink">
                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                        <div>
                            <label class="mb-1 block text-[11px] font-semibold text-ink-secondary">Judul Buku</label>
                            <input
                                type="text"
                                name="filter_judul"
                                value="{{ $filterJudul ?? '' }}"
                                placeholder="Judul spesifik..."
                                class="h-9 w-full rounded-xl border border-line bg-white px-3 text-xs text-ink focus:border-primary focus:outline-none"
                            >
                        </div>
                        <div>
                            <label class="mb-1 block text-[11px] font-semibold text-ink-secondary">Pengarang</label>
                            <input
                                type="text"
                                name="filter_pengarang"
                                value="{{ $filterPengarang ?? '' }}"
                                placeholder="Nama penulis..."
                                class="h-9 w-full rounded-xl border border-line bg-white px-3 text-xs text-ink focus:border-primary focus:outline-none"
                            >
                        </div>
                        <div>
                            <label class="mb-1 block text-[11px] font-semibold text-ink-secondary">ISBN</label>
                            <input
                                type="text"
                                name="filter_isbn"
                                value="{{ $filterIsbn ?? '' }}"
                                placeholder="978-..."
                                class="h-9 w-full rounded-xl border border-line bg-white px-3 text-xs font-mono text-ink focus:border-primary focus:outline-none"
                            >
                        </div>
                    </div>
                </div>
            </form>
        </div>

        {{-- Tata Letak 2 Kolom (Sidebar Filter Kiri 3 Kolom + Daftar Katalog Kanan 9 Kolom dengan Style Visual Sebelumnya) --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-12 lg:gap-8 items-start">
            {{-- Kolom Kiri: Filter Pencarian Katalog (Style Kartu Sebelumnya dengan Header Biru Tua Gradasi MNCU) --}}
            <aside aria-label="Filter Katalog Buku" class="lg:col-span-3 order-2 lg:order-1">
                <div class="lg:sticky lg:top-24">
                    <div class="overflow-hidden rounded-2xl border border-line bg-white shadow-card">
                        <div class="flex items-center justify-between gap-2 bg-gradient-to-r from-[#0F2A6B] to-[#143A8F] px-5 py-3.5 text-white">
                            <div class="flex items-center gap-2.5">
                                <i class="fa-solid fa-sliders text-sm" aria-hidden="true"></i>
                                <h2 class="text-sm sm:text-base font-semibold">Filter Katalog</h2>
                            </div>
                            @if ($hasAdminActiveFilter)
                                <a
                                    href="{{ route('admin.books.index', ['view' => $viewMode]) }}"
                                    class="inline-flex items-center gap-1 rounded-lg bg-white/15 px-2.5 py-1 text-[11px] font-semibold text-white hover:bg-white/25 transition-colors"
                                    title="Reset Filter"
                                >
                                    <i class="fa-solid fa-rotate-left text-[10px]"></i>
                                    <span>Reset</span>
                                </a>
                            @endif
                        </div>

                        <form method="GET" action="{{ route('admin.books.index') }}" id="admin-sidebar-filter-form" class="p-4 divide-y divide-dashed divide-line">
                            <input type="hidden" name="view" id="sidebar-view-input" value="{{ $viewMode }}">
                            <input type="hidden" name="q" value="{{ $q }}">
                            <input type="hidden" name="sort" value="{{ $sort ?? 'terbaru' }}">

                            {{-- 1. Tahun Penerbitan --}}
                            <div class="py-3 first:pt-0">
                                <button
                                    type="button"
                                    onclick="toggleAdminSidebarSection('admin-collapse-year', this)"
                                    class="flex w-full items-center justify-between text-left font-semibold text-ink text-xs sm:text-sm hover:text-primary transition-colors"
                                    aria-expanded="true"
                                >
                                    <span>Tahun Penerbitan</span>
                                    <i class="fa-solid fa-chevron-down text-xs text-muted transition-transform duration-200 rotate-180" data-chevron></i>
                                </button>
                                <div id="admin-collapse-year" class="mt-2.5">
                                    <div class="flex items-center gap-2">
                                        <input
                                            type="number"
                                            name="tahun_dari"
                                            value="{{ $tahunDari ?? '' }}"
                                            placeholder="Dari (2018)"
                                            class="h-9 w-full rounded-xl border border-line bg-subtle/60 px-2.5 text-center text-xs text-ink focus:border-primary focus:bg-white focus:outline-none"
                                        >
                                        <span class="text-muted font-semibold">-</span>
                                        <input
                                            type="number"
                                            name="tahun_sampai"
                                            value="{{ $tahunSampai ?? '' }}"
                                            placeholder="Sampai (2026)"
                                            class="h-9 w-full rounded-xl border border-line bg-subtle/60 px-2.5 text-center text-xs text-ink focus:border-primary focus:bg-white focus:outline-none"
                                        >
                                    </div>
                                </div>
                            </div>

                            {{-- 2. Ketersediaan Stok --}}
                            <div class="py-3">
                                <button
                                    type="button"
                                    onclick="toggleAdminSidebarSection('admin-collapse-stok', this)"
                                    class="flex w-full items-center justify-between text-left font-semibold text-ink text-xs sm:text-sm hover:text-primary transition-colors"
                                    aria-expanded="true"
                                >
                                    <span>Ketersediaan Stok</span>
                                    <i class="fa-solid fa-chevron-down text-xs text-muted transition-transform duration-200 rotate-180" data-chevron></i>
                                </button>
                                <div id="admin-collapse-stok" class="mt-2.5 space-y-2">
                                    <label class="flex items-center gap-2.5 cursor-pointer group">
                                        <input type="radio" name="stok" value="" @checked(($statusStok ?? '') === '') class="size-4 border-line text-primary focus:ring-primary/20">
                                        <span class="text-xs text-ink-secondary group-hover:text-primary transition-colors">Semua Status Stok</span>
                                    </label>
                                    <label class="flex items-center gap-2.5 cursor-pointer group">
                                        <input type="radio" name="stok" value="tersedia" @checked(($statusStok ?? '') === 'tersedia') class="size-4 border-line text-primary focus:ring-primary/20">
                                        <span class="text-xs text-ink-secondary group-hover:text-primary transition-colors">Stok Siap Pinjam ({{ $availableCopies ?? 0 }})</span>
                                    </label>
                                    <label class="flex items-center gap-2.5 cursor-pointer group">
                                        <input type="radio" name="stok" value="dipinjam" @checked(($statusStok ?? '') === 'dipinjam') class="size-4 border-line text-primary focus:ring-primary/20">
                                        <span class="text-xs text-ink-secondary group-hover:text-primary transition-colors">Sedang Dipinjam ({{ $borrowedCopies ?? 0 }})</span>
                                    </label>
                                    <label class="flex items-center gap-2.5 cursor-pointer group">
                                        <input type="radio" name="stok" value="eksemplar" @checked(($statusStok ?? '') === 'eksemplar') class="size-4 border-line text-primary focus:ring-primary/20">
                                        <span class="text-xs text-ink-secondary group-hover:text-primary transition-colors">Memiliki Eksemplar Fisik</span>
                                    </label>
                                    <label class="flex items-center gap-2.5 cursor-pointer group">
                                        <input type="radio" name="stok" value="habis" @checked(($statusStok ?? '') === 'habis') class="size-4 border-line text-primary focus:ring-primary/20">
                                        <span class="text-xs text-ink-secondary group-hover:text-primary transition-colors">Semua Dipinjam / Habis</span>
                                    </label>
                                    <label class="flex items-center gap-2.5 cursor-pointer group">
                                        <input type="radio" name="stok" value="kosong" @checked(($statusStok ?? '') === 'kosong') class="size-4 border-line text-primary focus:ring-primary/20">
                                        <span class="text-xs text-ink-secondary group-hover:text-primary transition-colors">Belum Ada Eksemplar</span>
                                    </label>
                                </div>
                            </div>

                            {{-- 3. Format / Jenis Buku --}}
                            <div class="py-3">
                                <button
                                    type="button"
                                    onclick="toggleAdminSidebarSection('admin-collapse-jenis', this)"
                                    class="flex w-full items-center justify-between text-left font-semibold text-ink text-xs sm:text-sm hover:text-primary transition-colors"
                                    aria-expanded="true"
                                >
                                    <span>Jenis Koleksi</span>
                                    <i class="fa-solid fa-chevron-down text-xs text-muted transition-transform duration-200 rotate-180" data-chevron></i>
                                </button>
                                <div id="admin-collapse-jenis" class="mt-2.5 space-y-2">
                                    @foreach ($types as $type)
                                        <label class="flex items-center justify-between gap-2 cursor-pointer group">
                                            <span class="flex items-center gap-2.5 min-w-0">
                                                <input
                                                    type="checkbox"
                                                    name="jenis[]"
                                                    value="{{ $type->id_jenis_buku }}"
                                                    @checked(in_array($type->id_jenis_buku, $jenisList ?? [], true))
                                                    class="size-4 rounded border-line text-primary focus:ring-primary/20 shrink-0"
                                                >
                                                <span class="text-xs text-ink-secondary group-hover:text-primary transition-colors truncate">{{ $type->nama_jenis_buku }}</span>
                                            </span>
                                            <span class="rounded-full bg-subtle border border-line px-2 py-0.5 text-[10px] font-semibold text-ink-secondary shrink-0">{{ $type->buku_count ?? 0 }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            {{-- 4. Kategori Koleksi --}}
                            <div class="py-3">
                                <button
                                    type="button"
                                    onclick="toggleAdminSidebarSection('admin-collapse-kategori', this)"
                                    class="flex w-full items-center justify-between text-left font-semibold text-ink text-xs sm:text-sm hover:text-primary transition-colors"
                                    aria-expanded="true"
                                >
                                    <span>Kategori Keilmuan</span>
                                    <i class="fa-solid fa-chevron-down text-xs text-muted transition-transform duration-200 rotate-180" data-chevron></i>
                                </button>
                                <div id="admin-collapse-kategori" class="mt-2.5 space-y-2">
                                    @php
                                        $primaryCats = $categories->take(5);
                                        $extraCats = $categories->slice(5);
                                    @endphp
                                    @foreach ($primaryCats as $cat)
                                        <label class="flex items-center justify-between gap-2 cursor-pointer group">
                                            <span class="flex items-center gap-2.5 min-w-0">
                                                <input
                                                    type="checkbox"
                                                    name="kategori[]"
                                                    value="{{ $cat->id_kategori_buku }}"
                                                    @checked(in_array($cat->id_kategori_buku, $kategoriList ?? [], true))
                                                    class="size-4 rounded border-line text-primary focus:ring-primary/20 shrink-0"
                                                >
                                                <span class="text-xs text-ink-secondary group-hover:text-primary transition-colors truncate">{{ $cat->nama_kategori }}</span>
                                            </span>
                                            <span class="rounded-full bg-subtle border border-line px-2 py-0.5 text-[10px] font-semibold text-ink-secondary shrink-0">{{ $cat->buku_count ?? 0 }}</span>
                                        </label>
                                    @endforeach

                                    @if ($extraCats->isNotEmpty())
                                        <div id="admin-more-categories" class="hidden space-y-2 pt-1">
                                            @foreach ($extraCats as $cat)
                                                <label class="flex items-center justify-between gap-2 cursor-pointer group">
                                                    <span class="flex items-center gap-2.5 min-w-0">
                                                        <input
                                                            type="checkbox"
                                                            name="kategori[]"
                                                            value="{{ $cat->id_kategori_buku }}"
                                                            @checked(in_array($cat->id_kategori_buku, $kategoriList ?? [], true))
                                                            class="size-4 rounded border-line text-primary focus:ring-primary/20 shrink-0"
                                                        >
                                                        <span class="text-xs text-ink-secondary group-hover:text-primary transition-colors truncate">{{ $cat->nama_kategori }}</span>
                                                    </span>
                                                    <span class="rounded-full bg-subtle border border-line px-2 py-0.5 text-[10px] font-semibold text-ink-secondary shrink-0">{{ $cat->buku_count ?? 0 }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                        <button
                                            type="button"
                                            onclick="toggleAdminMoreCategories('admin-more-categories', this)"
                                            class="mt-1 inline-flex items-center gap-1 text-[11px] font-semibold text-primary hover:underline"
                                        >
                                            <span>Lihat Lebih Banyak</span>
                                            <i class="fa-solid fa-plus text-[9px]"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>

                            {{-- 5. Lokasi Rak --}}
                            @if (! empty($lokasiList))
                                <div class="py-3">
                                    <button
                                        type="button"
                                        onclick="toggleAdminSidebarSection('admin-collapse-lokasi', this)"
                                        class="flex w-full items-center justify-between text-left font-semibold text-ink text-xs sm:text-sm hover:text-primary transition-colors"
                                        aria-expanded="false"
                                    >
                                        <span>Lokasi Rak</span>
                                        <i class="fa-solid fa-chevron-down text-xs text-muted transition-transform duration-200 {{ ! empty($lokasiFilterList) ? 'rotate-180' : '' }}" data-chevron></i>
                                    </button>
                                    <div id="admin-collapse-lokasi" class="mt-2.5 space-y-2 {{ empty($lokasiFilterList) ? 'hidden' : '' }}">
                                        @foreach ($lokasiList as $lok)
                                            <label class="flex items-center gap-2.5 cursor-pointer group">
                                                <input
                                                    type="checkbox"
                                                    name="lokasi[]"
                                                    value="{{ $lok }}"
                                                    @checked(in_array($lok, $lokasiFilterList ?? [], true))
                                                    class="size-4 rounded border-line text-primary focus:ring-primary/20"
                                                >
                                                <span class="text-xs text-ink-secondary group-hover:text-primary transition-colors leading-snug">{{ $lok }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            {{-- Tombol Terapkan Filter --}}
                            <div class="pt-3.5">
                                <button
                                    type="submit"
                                    class="btn-press btn-sheen flex h-10 w-full items-center justify-center gap-2 rounded-xl bg-primary px-4 text-xs font-semibold text-white shadow-xs transition-colors hover:bg-primary-dark"
                                >
                                    <i class="fa-solid fa-check text-xs"></i>
                                    <span>Terapkan Filter</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </aside>

            {{-- Kolom Kanan: Tabel / Grid Koleksi Buku (Style Visual Sebelumnya) --}}
            <div class="lg:col-span-9 order-1 lg:order-2 min-w-0 flex flex-col gap-5">
                {{-- Header Urutan & Info Jumlah Koleksi (Style Sebelumnya) --}}
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between border-b border-line pb-3">
                    <div>
                        <h2 class="text-base sm:text-lg font-bold text-ink flex items-center gap-2">
                            <i class="fa-solid fa-bars-staggered text-primary"></i>
                            <span>Daftar Katalog Koleksi</span>
                        </h2>
                        <p class="mt-0.5 text-xs text-muted">
                            Menampilkan <strong>{{ $books->count() }}</strong> dari <strong>{{ $books->total() }}</strong> judul buku
                        </p>
                    </div>

                    <div class="relative self-start sm:self-auto">
                        <label for="admin-book-sort" class="sr-only">Urutkan Berdasarkan</label>
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-muted">
                            <i class="fa-solid fa-arrow-down-short-wide text-xs"></i>
                        </span>
                        <select
                            id="admin-book-sort"
                            onchange="const s = document.getElementById('filter-sort-input'); if (s) { s.value = this.value; } document.getElementById('filter-form')?.submit();"
                            class="h-10 rounded-xl border border-line bg-white pr-8 pl-8 text-xs font-semibold text-ink shadow-xs focus:border-primary focus:outline-none"
                        >
                            <option value="terbaru" @selected(($sort ?? 'terbaru') === 'terbaru')>Urutkan: Koleksi Terbaru</option>
                            <option value="judul_asc" @selected(($sort ?? '') === 'judul_asc')>Urutkan: Judul (A - Z)</option>
                            <option value="judul_desc" @selected(($sort ?? '') === 'judul_desc')>Urutkan: Judul (Z - A)</option>
                            <option value="tahun_desc" @selected(($sort ?? '') === 'tahun_desc')>Urutkan: Tahun (Terbaru)</option>
                            <option value="tahun_asc" @selected(($sort ?? '') === 'tahun_asc')>Urutkan: Tahun (Terlama)</option>
                        </select>
                    </div>
                </div>

                {{-- Banner Indikator Filter Stok Aktif --}}
                @if (($statusStok ?? '') !== '')
                    <div class="flex flex-wrap items-center justify-between gap-2.5 rounded-xl border border-primary/20 bg-primary/[0.03] p-3 text-xs sm:px-4 shadow-2xs">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-ink-secondary">Menyaring stok:</span>
                            <span class="inline-flex items-center gap-1.5 rounded-lg border border-line bg-white px-2.5 py-1 font-semibold text-ink shadow-xs">
                                @if ($statusStok === 'tersedia')
                                    <span class="size-2 rounded-full bg-emerald-500"></span>
                                    <span class="text-emerald-800">Stok Siap Pinjam</span>
                                @elseif ($statusStok === 'dipinjam')
                                    <span class="size-2 rounded-full bg-amber-500"></span>
                                    <span class="text-amber-800">Sedang Dipinjam</span>
                                @elseif ($statusStok === 'eksemplar')
                                    <span class="size-2 rounded-full bg-[#7C3AED]"></span>
                                    <span class="text-[#7C3AED]">Memiliki Eksemplar Fisik</span>
                                @elseif ($statusStok === 'habis')
                                    <span class="size-2 rounded-full bg-rose-500"></span>
                                    <span class="text-rose-700">Semua Dipinjam / Habis</span>
                                @elseif ($statusStok === 'kosong')
                                    <span class="size-2 rounded-full bg-slate-400"></span>
                                    <span class="text-slate-700">Belum Ada Eksemplar</span>
                                @endif
                                <a
                                    href="{{ route('admin.books.index', array_merge(request()->except(['stok', 'page']), ['view' => $viewMode])) }}"
                                    class="ml-1 text-muted hover:text-rose-600 transition-colors"
                                    title="Hapus penyaringan stok ini"
                                    aria-label="Hapus penyaringan stok"
                                >
                                    <i class="fa-solid fa-xmark text-xs"></i>
                                </a>
                            </span>
                        </div>
                        @if ($statusStok === 'dipinjam')
                            <a
                                href="{{ route('admin.peminjaman.index', ['status' => 'dipinjam']) }}"
                                class="inline-flex items-center gap-1.5 font-semibold text-amber-800 hover:text-amber-950 transition-colors"
                            >
                                <span>Lihat detail peminjam di Modul Sirkulasi</span>
                                <i class="fa-solid fa-arrow-right text-[11px]"></i>
                            </a>
                        @endif
                    </div>
                @endif

        {{-- 1. TAMPILAN TABEL (ROW VIEW) DILENGKAPI KOLOM STOK --}}
        <div id="books-table-view" class="{{ $viewMode === 'grid' ? 'hidden' : '' }} overflow-hidden rounded-2xl border border-line bg-white shadow-card">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm">
                    <thead class="border-b border-line bg-[#F8FAFC] text-[11px] font-semibold uppercase tracking-wider text-muted">
                        <tr>
                            <th scope="col" class="px-5 py-3.5">Koleksi Buku</th>
                            <th scope="col" class="px-5 py-3.5">Format &amp; Kategori</th>
                            <th scope="col" class="px-5 py-3.5">Ketersediaan Stok</th>
                            <th scope="col" class="px-5 py-3.5">Penerbit &amp; Tahun</th>
                            <th scope="col" class="px-5 py-3.5">ISBN &amp; Barcode</th>
                            <th scope="col" class="px-5 py-3.5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line text-ink">
                        @forelse ($books as $book)
                            @php
                                $totalEk = $book->eksemplar->count();
                                $readyEk = $book->eksemplar->where('status', 'Tersedia')->count();
                                $borrowedEk = $book->eksemplar->where('status', 'Dipinjam')->count();
                                $firstCopy = $book->eksemplar->first();
                            @endphp
                            <tr class="transition-colors hover:bg-[#F9FAFB]">
                                {{-- Judul & Cover --}}
                                <td class="px-5 py-4">
                                    <div class="flex items-start gap-3.5">
                                        <div class="relative h-16 w-11 shrink-0 overflow-hidden rounded-md border border-line bg-subtle shadow-xs">
                                            <img src="{{ $book->sampul_url }}" alt="{{ $book->judul_buku }}" class="h-full w-full object-cover" onerror="this.onerror=null; this.src='{{ asset('images/buku/default-cover.jpg') }}';">
                                        </div>
                                        <div class="min-w-0">
                                            <a href="{{ route('admin.books.show', $book->id_buku) }}" class="font-semibold text-ink leading-snug hover:text-primary transition-colors">
                                                {{ $book->judul_buku }}
                                            </a>
                                            <p class="mt-0.5 text-xs text-muted truncate">
                                                Penulis: {{ $book->penulis->pluck('nama_penulis')->join(', ') ?: 'Tim Perpustakaan' }}
                                            </p>
                                            <div class="mt-1 flex flex-wrap items-center gap-1.5">
                                                <span class="font-mono text-[10px] text-muted">{{ $book->id_buku }}</span>
                                                @if (! empty($book->kata_kunci_list))
                                                    @foreach (array_slice($book->kata_kunci_list, 0, 3) as $kw)
                                                        <span class="inline-flex items-center rounded bg-primary/5 px-1.5 py-0.5 text-[9px] font-medium text-primary border border-primary/20">
                                                            #{{ $kw }}
                                                        </span>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Format & Kategori --}}
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <div class="flex flex-col gap-1">
                                        <span class="inline-flex w-fit items-center rounded-full border border-blue-200 bg-blue-50 px-2.5 py-0.5 text-[11px] font-medium text-blue-800">
                                            {{ $book->jenisBuku?->nama_jenis_buku ?? 'Buku Cetak' }}
                                        </span>
                                        <span class="text-xs text-muted">{{ $book->kategoriBuku?->nama_kategori ?? '-' }}</span>
                                    </div>
                                </td>

                                {{-- Stok & Eksemplar --}}
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <div class="flex flex-col gap-1">
                                        @if ($totalEk === 0)
                                            <span class="inline-flex w-fit items-center gap-1 rounded-full border border-slate-200 bg-slate-50 px-2.5 py-0.5 text-[11px] font-medium text-slate-600">
                                                <i class="fa-solid fa-circle-minus text-[9px]"></i> 0 Eksemplar
                                            </span>
                                            <span class="text-[11px] text-muted">Belum ada fisik</span>
                                        @elseif ($readyEk > 0)
                                            <span class="inline-flex w-fit items-center gap-1.5 rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-0.5 text-[11px] font-semibold text-emerald-800">
                                                <span class="size-1.5 rounded-full bg-emerald-500"></span>
                                                {{ $readyEk }} / {{ $totalEk }} Tersedia
                                            </span>
                                            <span class="text-[11px] text-muted">
                                                {{ $borrowedEk > 0 ? "({$borrowedEk} dipinjam)" : 'Semua di rak' }}
                                            </span>
                                        @else
                                            <span class="inline-flex w-fit items-center gap-1.5 rounded-full border border-amber-200 bg-amber-50 px-2.5 py-0.5 text-[11px] font-semibold text-amber-800">
                                                <span class="size-1.5 rounded-full bg-amber-500"></span>
                                                Habis ({{ $totalEk }} dipinjam)
                                            </span>
                                            <span class="text-[11px] text-muted">Semua sedang dipinjam</span>
                                        @endif

                                        @if ($firstCopy?->lokasi_rak)
                                            <span class="text-[11px] text-ink-secondary truncate max-w-[170px]" title="{{ $firstCopy->lokasi_rak }}">
                                                <i class="fa-solid fa-location-dot text-[10px] text-primary/70 mr-1"></i>{{ $firstCopy->lokasi_rak }}
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                {{-- Penerbit & Tahun --}}
                                <td class="px-5 py-4 whitespace-nowrap text-ink-secondary">
                                    <div class="text-xs">
                                        <p class="font-medium text-ink">{{ $book->penerbit?->nama_penerbit ?? 'Penerbit MNCU' }}</p>
                                        <p class="text-muted">{{ $book->tahun_terbit ?? '-' }} {{ $book->edisi ? "• {$book->edisi}" : '' }}</p>
                                    </div>
                                </td>

                                {{-- ISBN & Barcode --}}
                                <td class="px-5 py-4 whitespace-nowrap text-xs">
                                    <div class="font-mono text-muted text-xs">{{ $book->isbn ?? '-' }}</div>
                                    @if ($firstCopy?->nomor_barcode)
                                        <div class="mt-1 inline-flex items-center gap-1 font-mono text-[11px] font-semibold text-primary">
                                            <i class="fa-solid fa-barcode text-[10px]"></i>
                                            <span>{{ $firstCopy->nomor_barcode }}</span>
                                            @if ($totalEk > 1)
                                                <span class="text-[10px] text-muted font-normal">(+{{ $totalEk - 1 }})</span>
                                            @endif
                                        </div>
                                    @endif
                                </td>

                                {{-- Aksi --}}
                                <td class="px-5 py-4 whitespace-nowrap text-center">
                                    <div class="inline-flex items-center gap-1.5">
                                        {{-- Tombol Detail & Stok --}}
                                        <a
                                            href="{{ route('admin.books.show', $book->id_buku) }}"
                                            class="inline-flex size-8 items-center justify-center rounded-lg border border-line text-primary transition-colors hover:bg-primary hover:text-white"
                                            title="Lihat Detail & Kelola Stok Eksemplar"
                                        >
                                            <i class="fa-solid fa-boxes-stacked text-xs"></i>
                                        </a>

                                        {{-- Tombol Lihat di OPAC --}}
                                        <a
                                            href="{{ route('opac', ['buku' => $book->id_buku]) }}"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="inline-flex size-8 items-center justify-center rounded-lg border border-line text-emerald-600 transition-colors hover:border-emerald-500 hover:bg-emerald-50 hover:text-emerald-700"
                                            title="Lihat Detail Buku di Katalog OPAC (Tab Baru)"
                                        >
                                            <i class="fa-solid fa-magnifying-glass text-xs"></i>
                                        </a>

                                        {{-- Ubah --}}
                                        <a
                                            href="{{ route('admin.books.edit', $book->id_buku) }}"
                                            class="inline-flex size-8 items-center justify-center rounded-lg border border-line text-muted transition-colors hover:border-primary hover:bg-primary/5 hover:text-primary"
                                            title="Ubah Katalog Buku"
                                        >
                                            <i class="fa-regular fa-pen-to-square text-xs"></i>
                                        </a>

                                        {{-- Hapus (Pindahkan ke Kotak Sampah) --}}
                                        @if ($borrowedEk > 0)
                                            <button
                                                type="button"
                                                class="inline-flex size-8 items-center justify-center rounded-lg border border-slate-200 bg-slate-100 text-slate-400 cursor-not-allowed transition-colors"
                                                title="Buku tidak dapat dihapus karena masih ada {{ $borrowedEk }} eksemplar yang sedang dipinjam"
                                                onclick="alert('Koleksi buku \'{{ addslashes($book->judul_buku) }}\' tidak dapat dihapus karena masih ada {{ $borrowedEk }} eksemplar yang sedang dipinjam pemustaka.')"
                                            >
                                                <i class="fa-regular fa-trash-can text-xs"></i>
                                            </button>
                                        @else
                                            <form
                                                action="{{ route('admin.books.destroy', $book->id_buku) }}"
                                                method="POST"
                                                onsubmit="return confirm('Pindahkan buku \'{{ addslashes($book->judul_buku) }}\' ke Kotak Sampah (Recycle Bin)?')"
                                                class="inline-block"
                                            >
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    type="submit"
                                                    class="inline-flex size-8 items-center justify-center rounded-lg border border-red-200 text-red-600 transition-colors hover:bg-red-50"
                                                    title="Pindahkan ke Kotak Sampah"
                                                >
                                                    <i class="fa-regular fa-trash-can text-xs"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        <i class="fa-solid fa-book-open text-3xl text-muted/40"></i>
                                        <p class="font-medium text-ink">Tidak ada koleksi buku ditemukan.</p>
                                        <p class="text-xs text-muted">Coba ubah kata kunci pencarian atau filter status stok.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- 2. TAMPILAN GRID (CARD VIEW) DENGAN STRUKTUR LAYOUT 2-KOLOM HORISONTAL (SESUAI OPAC) --}}
        <div id="books-grid-view" class="{{ $viewMode === 'table' ? 'hidden' : '' }}">
            @if ($books->isEmpty())
                <div class="rounded-2xl border border-line bg-white p-12 text-center shadow-card">
                    <div class="flex flex-col items-center justify-center gap-2">
                        <i class="fa-solid fa-book-open text-3xl text-muted/40" aria-hidden="true"></i>
                        <p class="font-medium text-ink">Tidak ada koleksi buku ditemukan.</p>
                        <p class="text-xs text-muted">Coba ubah kata kunci pencarian atau filter kategori.</p>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    @foreach ($books as $book)
                        @php
                            $totalEk = $book->eksemplar->count();
                            $readyEk = $book->eksemplar->where('status', 'Tersedia')->count();
                            $borrowedEk = $book->eksemplar->where('status', 'Dipinjam')->count();
                            $firstCopy = $book->eksemplar->first();
                            $callNumber = $firstCopy?->nomor_panggil ?: ($book->nomor_dewey ? $book->nomor_dewey . ' MNC' : '000.00 MNC');
                            $primaryCategory = $book->kategoriBuku?->nama_kategori ?? 'Umum';
                            $formatName = $book->jenisBuku?->nama_jenis_buku ?? 'Buku Cetak';
                            $penulisStr = $book->penulis->pluck('nama_penulis')->join(', ') ?: 'Tim Perpustakaan';
                            $penerbitStr = $book->penerbit?->nama_penerbit ?? 'Penerbit MNC University';
                        @endphp
                        <article class="group relative flex flex-col justify-between overflow-hidden rounded-2xl border border-line bg-white p-4 shadow-card transition-all duration-200 hover:-translate-y-0.5 hover:border-primary/50 hover:shadow-raised">
                            <div class="grid grid-cols-12 gap-3.5 sm:gap-4 h-full">
                                {{-- Sisi Kiri (4 Kolom): Sampul Buku & Badge Status Stok --}}
                                <div class="col-span-4 relative overflow-hidden rounded-xl border border-line/70 bg-slate-100 shadow-xs min-h-[165px] sm:min-h-[185px]">
                                    <img
                                        src="{{ $book->sampul_url }}"
                                        alt="{{ $book->judul_buku }}"
                                        class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
                                        loading="lazy"
                                        onerror="this.onerror=null; this.src='{{ asset('images/buku/default-cover.jpg') }}';"
                                    >

                                    {{-- Badge Format Melayang di Atas --}}
                                    <div class="absolute inset-x-2 top-2 flex items-center justify-between gap-1 pointer-events-none">
                                        <span class="inline-flex items-center rounded-md bg-white/95 px-1.5 py-0.5 text-[9px] font-semibold text-primary shadow-xs backdrop-blur-xs border border-line/60 truncate max-w-[70%]">
                                            {{ $formatName }}
                                        </span>
                                        <span class="inline-flex items-center rounded-md bg-slate-900/80 px-1.5 py-0.5 font-mono text-[9px] font-medium text-white shadow-xs backdrop-blur-xs shrink-0">
                                            #{{ $book->id_buku }}
                                        </span>
                                    </div>

                                    {{-- Badge Stok Melayang di Bawah Gambar --}}
                                    <div class="absolute inset-x-2 bottom-2 pointer-events-none">
                                        @if ($totalEk === 0)
                                            <span class="inline-flex items-center gap-1 rounded-md bg-slate-900/85 px-2 py-0.5 text-[10px] font-bold text-white shadow-xs">
                                                Digital
                                            </span>
                                        @elseif ($readyEk > 0)
                                            <span class="inline-flex items-center gap-1.5 rounded-md bg-emerald-600/95 px-2 py-0.5 text-[10px] font-bold text-white shadow-xs">
                                                <span class="size-1.5 rounded-full bg-emerald-300 animate-pulse"></span>
                                                <span>{{ $readyEk }}/{{ $totalEk }} Tersedia</span>
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 rounded-md bg-amber-600/95 px-2 py-0.5 text-[10px] font-bold text-white shadow-xs">
                                                <span>Dipinjam ({{ $borrowedEk }})</span>
                                            </span>
                                        @endif
                                    </div>

                                    {{-- Overlay Hover "Lihat" --}}
                                    <a
                                        href="{{ route('admin.books.show', $book->id_buku) }}"
                                        class="absolute inset-0 flex items-center justify-center bg-[#0F2A6B]/80 opacity-0 transition-opacity duration-200 group-hover:opacity-100"
                                        title="Buka detail buku di panel admin"
                                    >
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-white px-3.5 py-1.5 text-xs font-bold text-primary shadow-md">
                                            <i class="fa-solid fa-eye text-[11px]" aria-hidden="true"></i>
                                            <span>Lihat</span>
                                        </span>
                                    </a>
                                </div>

                                {{-- Sisi Kanan (8 Kolom): Judul, Metadata Bibliografi & Action Bar Admin --}}
                                <div class="col-span-8 flex flex-col justify-between min-w-0">
                                    <div>
                                        {{-- Baris Nomor Panggil & ID Buku --}}
                                        <div class="flex flex-wrap items-center justify-between gap-1.5 text-[11px] mb-1">
                                            <span class="inline-flex items-center gap-1 font-mono font-bold text-primary truncate max-w-[70%]" title="Nomor Panggil: {{ $callNumber }}">
                                                <i class="fa-solid fa-bookmark text-[10px] text-amber-600" aria-hidden="true"></i>
                                                <span class="truncate">{{ $callNumber }}</span>
                                            </span>
                                            <span class="font-mono text-[10px] font-semibold text-muted shrink-0">#{{ $book->id_buku }}</span>
                                        </div>

                                        {{-- Judul Buku --}}
                                        <a href="{{ route('admin.books.show', $book->id_buku) }}">
                                            <h3 class="text-sm sm:text-base font-bold text-ink transition-colors group-hover:text-primary leading-snug line-clamp-2" title="{{ $book->judul_buku }}">
                                                {{ $book->judul_buku }}
                                            </h3>
                                        </a>

                                        {{-- Metadata Pengarang, Penerbit, Tahun & Lokasi Rak --}}
                                        <div class="mt-2 space-y-1 text-xs text-ink-secondary">
                                            <div class="flex items-center gap-2 truncate" title="Pengarang: {{ $penulisStr }}">
                                                <i class="fa-solid fa-user-pen w-4 text-center text-[11px] text-muted shrink-0" aria-hidden="true"></i>
                                                <span class="truncate">{{ $penulisStr }}</span>
                                            </div>
                                            <div class="flex items-center gap-2 truncate" title="Penerbit: {{ $penerbitStr }}">
                                                <i class="fa-solid fa-building w-4 text-center text-[11px] text-muted shrink-0" aria-hidden="true"></i>
                                                <span class="truncate">{{ $penerbitStr }}</span>
                                            </div>
                                            <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-muted">
                                                <span class="inline-flex items-center gap-1.5">
                                                    <i class="fa-solid fa-calendar-days w-4 text-center text-[11px] text-muted" aria-hidden="true"></i>
                                                    <span>{{ $book->tahun_terbit ?? '-' }}</span>
                                                </span>
                                                @if ($firstCopy?->lokasi_rak)
                                                    <span class="inline-flex items-center gap-1 truncate max-w-[180px]" title="Lokasi Rak: {{ $firstCopy->lokasi_rak }}">
                                                        <i class="fa-solid fa-location-dot text-[11px] text-rose-500" aria-hidden="true"></i>
                                                        <span class="truncate">{{ $firstCopy->lokasi_rak }}</span>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- Tag Kata Kunci --}}
                                        @if (! empty($book->kata_kunci_list))
                                            <div class="mt-2 flex flex-wrap items-center gap-1">
                                                @foreach (array_slice($book->kata_kunci_list, 0, 3) as $kw)
                                                    <span class="inline-flex items-center gap-0.5 rounded-md border border-line/80 bg-subtle px-1.5 py-0.5 text-[10px] font-medium text-ink-secondary">
                                                        <i class="fa-solid fa-hashtag text-[8px] opacity-70" aria-hidden="true"></i>
                                                        <span>{{ $kw }}</span>
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Footer Kartu: Kategori/Subjek (Kiri) & Tombol Aksi Admin Lengkap (Kanan) --}}
                                    <div class="mt-3 flex flex-wrap items-center justify-between gap-2 border-t border-line pt-2.5">
                                        <span
                                            class="inline-block truncate max-w-[40%] rounded-lg border border-line bg-subtle px-2.5 py-1 text-[11px] font-medium text-ink-secondary"
                                            title="Kategori: {{ $primaryCategory }}"
                                        >
                                            {{ $primaryCategory }}
                                        </span>

                                        <div class="flex items-center gap-1.5 shrink-0">
                                            {{-- Tombol Detail & Kelola Stok --}}
                                            <a
                                                href="{{ route('admin.books.show', $book->id_buku) }}"
                                                class="btn-press inline-flex h-8 items-center justify-center gap-1 rounded-lg bg-primary/10 px-2.5 text-[11px] font-semibold text-primary transition-colors hover:bg-primary hover:text-white"
                                                title="Detail & Kelola Stok Eksemplar"
                                            >
                                                <i class="fa-solid fa-boxes-stacked text-[10px]" aria-hidden="true"></i>
                                                <span>Stok</span>
                                            </a>

                                            {{-- Tombol Lihat di OPAC --}}
                                            <a
                                                href="{{ route('opac', ['buku' => $book->id_buku]) }}"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                class="inline-flex size-8 items-center justify-center rounded-lg border border-line bg-subtle/50 text-emerald-600 transition-colors hover:border-emerald-500 hover:bg-emerald-50 hover:text-emerald-700"
                                                title="Lihat Detail Buku di Katalog OPAC (Tab Baru)"
                                            >
                                                <i class="fa-solid fa-magnifying-glass text-xs" aria-hidden="true"></i>
                                            </a>

                                            {{-- Tombol Ubah Buku --}}
                                            <a
                                                href="{{ route('admin.books.edit', $book->id_buku) }}"
                                                class="inline-flex size-8 items-center justify-center rounded-lg border border-line bg-subtle/50 text-muted transition-colors hover:border-primary hover:bg-primary/5 hover:text-primary"
                                                title="Ubah Katalog Buku"
                                            >
                                                <i class="fa-regular fa-pen-to-square text-xs" aria-hidden="true"></i>
                                            </a>

                                            {{-- Tombol Hapus Buku (Pindahkan ke Kotak Sampah) --}}
                                            @if ($borrowedEk > 0)
                                                <button
                                                    type="button"
                                                    class="inline-flex size-8 items-center justify-center rounded-lg border border-slate-200 bg-slate-100 text-slate-400 cursor-not-allowed transition-colors"
                                                    title="Buku tidak dapat dihapus karena masih ada {{ $borrowedEk }} eksemplar yang sedang dipinjam"
                                                    onclick="alert('Koleksi buku \'{{ addslashes($book->judul_buku) }}\' tidak dapat dihapus karena masih ada {{ $borrowedEk }} eksemplar yang sedang dipinjam pemustaka.')"
                                                >
                                                    <i class="fa-regular fa-trash-can text-xs" aria-hidden="true"></i>
                                                </button>
                                            @else
                                                <form
                                                    action="{{ route('admin.books.destroy', $book->id_buku) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Pindahkan buku \'{{ addslashes($book->judul_buku) }}\' ke Kotak Sampah (Recycle Bin)?')"
                                                    class="inline-block"
                                                >
                                                    @csrf
                                                    @method('DELETE')
                                                    <button
                                                        type="submit"
                                                        class="inline-flex size-8 items-center justify-center rounded-lg border border-red-200 text-red-600 transition-colors hover:bg-red-50"
                                                        title="Pindahkan ke Kotak Sampah"
                                                    >
                                                        <i class="fa-regular fa-trash-can text-xs" aria-hidden="true"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </div>

                {{-- Pagination (Berlaku untuk Tabel & Grid) --}}
                @if ($books->hasPages())
                    <div class="rounded-2xl border border-line bg-white p-4 shadow-card">
                        {{ $books->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Script Switcher Row (Tabel) <-> Grid (Kartu) & Accordion Sidebar --}}
    <script>
        function toggleAdminAdvancedSearch() {
            const panel = document.getElementById('admin-advanced-search-panel');
            if (!panel) return;
            panel.classList.toggle('hidden');
        }

        function toggleAdminSidebarSection(targetId, btn) {
            const target = document.getElementById(targetId);
            if (!target) return;
            const isHidden = target.classList.toggle('hidden');
            if (btn) {
                btn.setAttribute('aria-expanded', isHidden ? 'false' : 'true');
                const chevron = btn.querySelector('[data-chevron]');
                if (chevron) {
                    chevron.classList.toggle('rotate-180', !isHidden);
                }
            }
        }

        function toggleAdminMoreCategories(targetId, btn) {
            const target = document.getElementById(targetId);
            if (!target) return;
            const isHidden = target.classList.toggle('hidden');
            if (btn) {
                btn.innerHTML = isHidden
                    ? '<span>Lihat Lebih Banyak</span> <i class="fa-solid fa-plus text-[9px]"></i>'
                    : '<span>Sembunyikan</span> <i class="fa-solid fa-minus text-[9px]"></i>';
            }
        }

        function switchBookView(mode) {
            const tableView = document.getElementById('books-table-view');
            const gridView = document.getElementById('books-grid-view');
            const btnTable = document.getElementById('btn-view-table');
            const btnGrid = document.getElementById('btn-view-grid');
            const filterInput = document.getElementById('filter-view-input');
            const sidebarInput = document.getElementById('sidebar-view-input');

            if (mode === 'grid') {
                tableView.classList.add('hidden');
                gridView.classList.remove('hidden');

                btnGrid.className = 'inline-flex h-9 items-center gap-1.5 rounded-lg px-3 text-xs font-semibold transition-all duration-150 bg-white text-primary shadow-sm';
                btnTable.className = 'inline-flex h-9 items-center gap-1.5 rounded-lg px-3 text-xs font-semibold transition-all duration-150 text-muted hover:text-ink';
            } else {
                gridView.classList.add('hidden');
                tableView.classList.remove('hidden');

                btnTable.className = 'inline-flex h-9 items-center gap-1.5 rounded-lg px-3 text-xs font-semibold transition-all duration-150 bg-white text-primary shadow-sm';
                btnGrid.className = 'inline-flex h-9 items-center gap-1.5 rounded-lg px-3 text-xs font-semibold transition-all duration-150 text-muted hover:text-ink';
            }

            if (filterInput) {
                filterInput.value = mode;
            }
            if (sidebarInput) {
                sidebarInput.value = mode;
            }

            try {
                localStorage.setItem('mncu_books_view_preference', mode);
            } catch (e) {}

            if (window.history && window.history.replaceState) {
                const url = new URL(window.location.href);
                url.searchParams.set('view', mode);
                window.history.replaceState(null, '', url.toString());
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const urlParams = new URLSearchParams(window.location.search);
            const viewParam = urlParams.get('view');

            if (!viewParam) {
                const savedPreference = localStorage.getItem('mncu_books_view_preference');
                if (savedPreference === 'grid' || savedPreference === 'table') {
                    switchBookView(savedPreference);
                }
            }
        });
    </script>
@endsection
