@extends('layouts.app')

@section('title', 'Katalog OPAC')
@section('description', 'Katalog Online Perpustakaan MNC University (OPAC). Cari buku teks, monograf, buku referensi, dan skripsi fisik dengan informasi ketersediaan jumlah stok eksemplar dan nomor panggil rak secara real-time.')

@section('content')
    <div class="mx-auto flex w-full max-w-[1570px] flex-1 flex-col gap-6 px-3 py-6 lg:px-6 lg:py-8">
        {{-- Breadcrumb & Header --}}
        <div class="flex flex-col gap-3">
            <nav aria-label="Breadcrumb" class="flex items-center gap-2 text-xs text-muted sm:text-sm">
                <a href="{{ route('beranda') }}" class="hover:text-primary transition-colors">Beranda</a>
                <svg class="size-3.5 text-muted/60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
                <span class="font-medium text-ink">Katalog OPAC</span>
            </nav>
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-primary sm:text-3xl lg:text-4xl">
                        Katalog OPAC Perpustakaan
                    </h1>
                    <p class="mt-1 text-xs text-ink-secondary sm:text-sm lg:text-base">
                        Pencarian koleksi fisik, buku teks perkuliahan, dan referensi tercetak MNC University lengkap dengan status ketersediaan stok eksemplar.
                    </p>
                </div>

                {{-- Badges Ringkasan Inventaris --}}
                <div class="flex flex-wrap items-center gap-2 text-xs">
                    <div class="inline-flex items-center gap-1.5 rounded-xl border border-line bg-white px-3.5 py-2 shadow-xs">
                        <i class="fa-solid fa-book-bookmark text-primary text-xs"></i>
                        <span class="text-muted">Total Judul:</span>
                        <strong class="font-bold text-primary" id="badge-total-judul">{{ $totalCount }} judul</strong>
                    </div>
                    <div class="inline-flex items-center gap-1.5 rounded-xl border border-line bg-white px-3.5 py-2 shadow-xs">
                        <i class="fa-solid fa-boxes-stacked text-[#7C3AED] text-xs"></i>
                        <span class="text-muted">Total Fisik:</span>
                        <strong class="font-bold text-ink">{{ $totalEksemplarFisik }} eksemplar</strong>
                    </div>
                    <div class="inline-flex items-center gap-1.5 rounded-xl border border-emerald-200 bg-emerald-50/70 px-3.5 py-2 text-emerald-900 shadow-xs">
                        <span class="size-2 rounded-full bg-emerald-500"></span>
                        <span class="text-emerald-800">Siap Pinjam:</span>
                        <strong class="font-bold text-emerald-900">{{ $totalEksemplarTersedia }} eksemplar</strong>
                    </div>
                </div>
            </div>
        </div>

        {{-- Search & Filter Section (Berdasarkan Flowchart Pencarian Koleksi) --}}
        <section aria-labelledby="judul-filter-opac" class="rounded-2xl border border-line bg-white p-5 lg:p-6 shadow-sm">
            <h2 id="judul-filter-opac" class="sr-only">Form Pencarian dan Filter Katalog OPAC</h2>
            <form id="opac-search-form" action="{{ route('opac') }}" method="GET" class="flex flex-col gap-4">
                {{-- Search Bar Utama dengan Pemilihan Kriteria Koleksi --}}
                <div class="flex flex-col gap-2 md:flex-row md:items-center">
                    {{-- Dropdown Kriteria Koleksi --}}
                    <div class="w-full md:w-56 shrink-0">
                        <label for="opac-kriteria" class="sr-only">Kriteria Koleksi</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-muted">
                                <i class="fa-solid fa-filter text-xs"></i>
                            </span>
                            <select
                                id="opac-kriteria"
                                name="kriteria"
                                class="h-12 w-full appearance-none rounded-xl border border-line bg-subtle/70 pl-9 pr-8 text-xs font-semibold text-ink focus:border-primary focus:bg-white focus:outline-none focus:ring-1 focus:ring-primary sm:text-sm"
                            >
                                <option value="semua" @selected(($kriteria ?? 'semua') === 'semua')>Semua Kriteria</option>
                                <option value="judul" @selected(($kriteria ?? '') === 'judul')>Judul Buku</option>
                                <option value="pengarang" @selected(($kriteria ?? '') === 'pengarang')>Pengarang / Penulis</option>
                                <option value="penerbit" @selected(($kriteria ?? '') === 'penerbit')>Penerbit</option>
                                <option value="subjek" @selected(($kriteria ?? '') === 'subjek')>Subjek / Topik</option>
                                <option value="nomor_panggil" @selected(($kriteria ?? '') === 'nomor_panggil')>Nomor Panggil Rak</option>
                                <option value="isbn" @selected(($kriteria ?? '') === 'isbn')>Nomor ISBN</option>
                            </select>
                            <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-muted">
                                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
                            </span>
                        </div>
                    </div>

                    {{-- Input Kata Kunci & Tombol Submit --}}
                    <div class="relative flex flex-1 items-center">
                        <span class="pointer-events-none absolute left-4 text-muted">
                            <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"/>
                                <path d="m21 21-4.3-4.3"/>
                            </svg>
                        </span>
                        <input
                            type="text"
                            id="opac-search-input"
                            name="q"
                            value="{{ $query }}"
                            placeholder="Ketik kata kunci pencarian koleksi (judul, pengarang, ISBN, dsb)..."
                            autocomplete="off"
                            class="h-12 w-full rounded-xl border border-line bg-subtle/60 pl-12 pr-12 text-xs text-ink placeholder:text-muted focus:border-primary focus:bg-white focus:outline-none focus:ring-1 focus:ring-primary sm:text-sm"
                        >
                        {{-- Tombol Bersihkan Pencarian (X) --}}
                        <button
                            type="button"
                            id="btn-clear-search"
                            onclick="clearSearchInput()"
                            class="{{ empty($query) ? 'hidden' : '' }} absolute right-3 flex size-7 items-center justify-center rounded-lg text-muted hover:bg-slate-200 hover:text-ink transition-colors"
                            aria-label="Bersihkan kata kunci pencarian"
                        >
                            <i class="fa-solid fa-xmark text-xs"></i>
                        </button>
                    </div>

                    {{-- Tombol Cari Koleksi --}}
                    <button
                        type="submit"
                        class="inline-flex h-12 shrink-0 items-center justify-center gap-2 rounded-xl bg-primary px-6 text-xs font-semibold text-white shadow-xs transition-colors duration-150 hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary sm:text-sm"
                    >
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"/>
                            <path d="m21 21-4.3-4.3"/>
                        </svg>
                        <span>Cari Koleksi</span>
                    </button>
                </div>

                {{-- Baris Filter Tambahan: Kategori, Format/Jenis, Status Ketersediaan, dan Reset --}}
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-[1fr_1fr_1fr_auto] pt-1 border-t border-line/60">
                    <div>
                        <label for="filter-kategori" class="mb-1 block text-xs font-medium text-ink-secondary">
                            Kategori Koleksi
                        </label>
                        <select
                            id="filter-kategori"
                            name="kategori"
                            class="h-10 w-full rounded-xl border border-line bg-white px-3 text-xs font-medium text-ink focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary"
                        >
                            @foreach ($categories as $cat)
                                <option value="{{ $cat }}" @selected($category === $cat)>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="filter-jenis" class="mb-1 block text-xs font-medium text-ink-secondary">
                            Format / Jenis
                        </label>
                        <select
                            id="filter-jenis"
                            name="jenis"
                            class="h-10 w-full rounded-xl border border-line bg-white px-3 text-xs font-medium text-ink focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary"
                        >
                            @foreach ($types as $type)
                                <option value="{{ $type }}" @selected($jenisFilter === $type)>{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="filter-status" class="mb-1 block text-xs font-medium text-ink-secondary">
                            Status Ketersediaan Stok
                        </label>
                        <select
                            id="filter-status"
                            name="status"
                            class="h-10 w-full rounded-xl border border-line bg-white px-3 text-xs font-medium text-ink focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary"
                        >
                            @foreach ($statuses as $st)
                                <option value="{{ $st }}" @selected($statusFilter === $st)>{{ $st }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-end gap-2">
                        <button
                            type="submit"
                            class="inline-flex h-10 items-center justify-center gap-2 rounded-xl border border-line bg-subtle px-4 text-xs font-semibold text-ink-secondary hover:bg-slate-200 hover:text-ink transition-colors"
                        >
                            <i class="fa-solid fa-sliders text-xs"></i>
                            <span>Terapkan Filter</span>
                        </button>
                        @if ($query !== '' || ($kriteria ?? 'semua') !== 'semua' || $category !== 'Semua' || $prodi !== 'Semua' || $statusFilter !== 'Semua' || $jenisFilter !== 'Semua')
                            <a
                                href="{{ route('opac') }}"
                                class="inline-flex h-10 items-center justify-center rounded-xl border border-line bg-white px-3.5 text-xs font-medium text-rose-600 hover:bg-rose-50 transition-colors"
                                title="Reset Pencarian & Filter"
                            >
                                <i class="fa-solid fa-rotate-left mr-1.5 text-xs"></i>Reset
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Indikator Hasil Pencarian Dinamis --}}
                <div id="live-search-status" class="hidden items-center justify-between text-xs text-muted pt-2 border-t border-line/40">
                    <span id="live-search-text">Menampilkan hasil pencarian...</span>
                    <span class="text-[11px] text-muted/70">Tekan <kbd class="rounded border border-line bg-subtle px-1.5 py-0.5 font-mono text-[10px]">Enter</kbd> untuk pencarian menyeluruh</span>
                </div>
            </form>
        </section>

        {{-- Section Daftar Koleksi OPAC --}}
        <section aria-labelledby="daftar-koleksi-opac">
            <h2 id="daftar-koleksi-opac" class="sr-only">Daftar Buku Koleksi OPAC</h2>

            {{-- Grid Kartu Koleksi --}}
            <div id="catalog-grid" class="{{ count($items) > 0 ? 'grid' : 'hidden' }} gap-5 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-2">
                @foreach ($items as $item)
                    <article
                        class="opac-card group relative flex flex-col justify-between rounded-2xl border border-line bg-white p-5 shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:border-primary/40 hover:shadow-md sm:p-6 cursor-pointer focus-within:ring-2 focus-within:ring-primary"
                        tabindex="0"
                        role="button"
                        aria-label="Pilih buku: {{ $item['title'] }}"
                        data-id="{{ $item['id'] }}"
                        data-title="{{ strtolower($item['title']) }}"
                        data-author="{{ strtolower($item['author']) }}"
                        data-publisher="{{ strtolower($item['publisher']) }}"
                        data-isbn="{{ strtolower($item['isbn'] ?? '') }}"
                        data-call-number="{{ strtolower($item['call_number'] ?? '') }}"
                        data-subjects="{{ strtolower(implode(' ', $item['subjects'] ?? [])) }}"
                        data-category="{{ strtolower($item['category'] ?? '') }}"
                        data-format="{{ strtolower($item['format'] ?? '') }}"
                        data-available="{{ ($item['copies_available'] ?? 0) > 0 ? '1' : '0' }}"
                        onclick="handleCardClick(event, {{ json_encode($item) }})"
                        onkeydown="handleCardKeydown(event, {{ json_encode($item) }})"
                    >
                        <div>
                            {{-- Header Kartu: Kategori & Status Ketersediaan Stok --}}
                            <div class="flex flex-wrap items-start justify-between gap-2.5">
                                <div class="flex flex-wrap items-center gap-1.5">
                                    <span class="rounded-lg bg-primary/10 px-2.5 py-1 text-[11px] font-semibold text-primary">
                                        {{ $item['category'] }}
                                    </span>
                                    <span class="rounded-lg bg-subtle px-2.5 py-1 text-[11px] font-medium text-ink-secondary">
                                        {{ $item['format'] ?? 'Buku Cetak' }}
                                    </span>
                                </div>

                                {{-- Badge Stok Tersedia --}}
                                @php
                                    $available = $item['copies_available'] ?? 0;
                                    $total = $item['copies_total'] ?? 0;
                                @endphp
                                @if ($total === 0)
                                    <span class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-slate-50 px-2.5 py-0.5 text-xs font-medium text-slate-700">
                                        <i class="fa-solid fa-circle-nodes text-[10px] text-slate-500"></i>
                                        Koleksi Digital
                                    </span>
                                @elseif ($available > 0)
                                    <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-0.5 text-xs font-semibold text-emerald-800">
                                        <span class="size-1.5 rounded-full bg-emerald-500"></span>
                                        Tersedia: {{ $available }} / {{ $total }} Eksemplar
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 rounded-full border border-amber-200 bg-amber-50 px-2.5 py-0.5 text-xs font-semibold text-amber-800">
                                        <span class="size-1.5 rounded-full bg-amber-500"></span>
                                        Semua Dipinjam ({{ $total }} Eks)
                                    </span>
                                @endif
                            </div>

                            {{-- Layout Konten: Sampul Buku di Sisi Kiri, Detail Bibliografi di Kanan --}}
                            <div class="mt-4 flex flex-col gap-4 sm:flex-row sm:items-start">
                                {{-- Sampul Buku --}}
                                <div class="relative h-44 w-32 shrink-0 overflow-hidden rounded-xl border border-line bg-gradient-to-br from-subtle to-slate-100 shadow-xs mx-auto sm:mx-0">
                                    @if (!empty($item['cover_url']))
                                        <img
                                            src="{{ $item['cover_url'] }}"
                                            alt="{{ $item['title'] }}"
                                            class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
                                            loading="lazy"
                                        >
                                    @else
                                        <div class="flex h-full w-full flex-col items-center justify-center p-3 text-center text-muted">
                                            <div class="flex size-12 items-center justify-center rounded-xl bg-primary/10 text-primary">
                                                <i class="fa-solid fa-book text-xl"></i>
                                            </div>
                                            <span class="mt-2 text-[10px] font-semibold text-ink/70 uppercase tracking-wider">Perpustakaan</span>
                                            <span class="text-[9px] text-muted">MNC University</span>
                                        </div>
                                    @endif
                                </div>

                                {{-- Rincian Bibliografi --}}
                                <div class="min-w-0 flex-1">
                                    <h3 class="text-base font-bold text-ink leading-snug transition-colors group-hover:text-primary sm:text-lg">
                                        {{ $item['title'] }}
                                    </h3>

                                    <p class="mt-1.5 text-xs text-ink-secondary">
                                        <span class="font-medium text-ink">Pengarang:</span> {{ $item['author'] }}
                                    </p>

                                    <p class="mt-0.5 text-xs text-muted">
                                        <span class="font-medium text-ink-secondary">Penerbit:</span> {{ $item['publisher'] }} &bull; Tahun {{ $item['year'] }}
                                    </p>

                                    @if (!empty($item['isbn']) && $item['isbn'] !== '-')
                                        <p class="mt-0.5 font-mono text-[11px] text-muted">
                                            ISBN: {{ $item['isbn'] }}
                                        </p>
                                    @endif

                                    {{-- Cuplikan Deskripsi --}}
                                    @if (!empty($item['description']))
                                        <p class="mt-2 line-clamp-2 text-xs text-muted leading-relaxed">
                                            {{ $item['description'] }}
                                        </p>
                                    @endif
                                </div>
                            </div>

                            {{-- Rincian Rak, Nomor Panggil & Ringkasan Stok Eksemplar --}}
                            <div class="mt-4 grid grid-cols-1 gap-2.5 rounded-xl bg-subtle/80 p-3.5 text-xs sm:grid-cols-2">
                                <div class="flex items-center gap-2">
                                    <span class="text-muted shrink-0">Nomor Panggil:</span>
                                    <code class="rounded bg-white px-2 py-0.5 font-mono font-semibold text-primary border border-line text-[11px]">
                                        {{ $item['call_number'] }}
                                    </code>
                                </div>

                                <div class="flex items-center gap-2">
                                    <span class="text-muted shrink-0">Total Stok:</span>
                                    <span class="font-semibold text-ink">
                                        {{ $item['copies_total'] ?? 0 }} eksemplar
                                        @if (($item['copies_available'] ?? 0) > 0)
                                            <span class="text-emerald-700 font-medium">({{ $item['copies_available'] }} siap pinjam)</span>
                                        @endif
                                    </span>
                                </div>

                                <div class="sm:col-span-2 flex items-center gap-2 pt-1 border-t border-line/60">
                                    <svg class="size-4 text-primary shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/>
                                        <circle cx="12" cy="10" r="3"/>
                                    </svg>
                                    <span class="text-ink font-medium">{{ $item['location'] }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Card Footer & Tombol Aksi --}}
                        <div class="mt-5 flex flex-wrap items-center justify-between gap-3 border-t border-line pt-4">
                            <span class="font-mono text-[11px] text-muted">ID: #{{ $item['id'] }}</span>

                            <div class="flex items-center gap-2">
                                {{-- Tombol Lihat Rincian & Tabel Eksemplar (Memilih Buku) --}}
                                <button
                                    type="button"
                                    onclick="event.stopPropagation(); openBookDetailModal({{ json_encode($item) }})"
                                    class="inline-flex items-center gap-1.5 rounded-xl bg-primary px-3.5 py-1.5 text-xs font-semibold text-white shadow-xs transition-all hover:bg-primary-dark"
                                >
                                    <i class="fa-solid fa-list-check text-xs"></i>
                                    <span>Rincian &amp; Stok Eksemplar</span>
                                </button>

                                {{-- Tanya Pustakawan --}}
                                <a
                                    href="{{ route('chat') }}?q={{ urlencode('Tanya ketersediaan buku: ' . $item['title']) }}"
                                    onclick="event.stopPropagation();"
                                    class="inline-flex items-center gap-1.5 rounded-xl border border-line bg-white px-3 py-1.5 text-xs font-semibold text-ink-secondary transition-colors hover:border-primary/40 hover:bg-subtle hover:text-primary"
                                    title="Tanya ketersediaan atau reservasi peminjaman ke Pustakawan"
                                >
                                    <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                                    </svg>
                                    <span class="hidden sm:inline">Tanya Pustakawan</span>
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            {{-- State: Koleksi Tidak Ditemukan (Flowchart Cabang 'Tidak' -> Loop Balik ke Pencarian) --}}
            <div
                id="empty-state-container"
                class="{{ count($items) === 0 ? 'flex' : 'hidden' }} flex-col items-center justify-center rounded-2xl border border-line bg-subtle p-8 text-center sm:p-12"
            >
                <div class="flex size-16 items-center justify-center rounded-2xl bg-white text-primary border border-line shadow-xs">
                    <svg class="size-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"/>
                        <path d="m21 21-4.3-4.3"/>
                        <path d="m8 11 6 0" stroke-linecap="round"/>
                    </svg>
                </div>

                <h3 class="mt-4 text-lg font-bold text-ink">Koleksi Tidak Ditemukan</h3>

                <p id="empty-state-message" class="mt-1.5 max-w-lg text-xs text-muted sm:text-sm leading-relaxed">
                    @if (!empty($query))
                        Tidak ditemukan koleksi buku dengan kata kunci <strong class="text-ink font-semibold">"{{ $query }}"</strong>
                        @if (($kriteria ?? 'semua') !== 'semua')
                            pada kriteria <span class="text-primary font-medium">{{ ucwords(str_replace('_', ' ', $kriteria)) }}</span>.
                        @else
                            di katalog OPAC.
                        @endif
                    @else
                        Koleksi dengan filter yang Anda tentukan tidak ditemukan di sistem katalog perpustakaan.
                    @endif
                </p>

                {{-- Tips Pencarian Sesuai Alur Flowchart --}}
                <div class="mt-5 max-w-md rounded-xl border border-line bg-white p-4 text-left text-xs text-ink-secondary">
                    <p class="font-semibold text-ink flex items-center gap-1.5">
                        <i class="fa-solid fa-lightbulb text-amber-500"></i> Tips Pencarian Koleksi:
                    </p>
                    <ul class="mt-2 list-disc list-inside space-y-1 text-muted text-[11px] sm:text-xs">
                        <li>Periksa kembali ketepatan ejaan kata kunci.</li>
                        <li>Gunakan kata kunci yang lebih umum (misalnya: <em>"Python"</em> atau <em>"Manajemen"</em>).</li>
                        <li>Ganti pilihan kriteria pencarian ke <strong>"Semua Kriteria"</strong>.</li>
                        <li>Gunakan kata kunci pengarang atau nomor ISBN buku.</li>
                    </ul>
                </div>

                {{-- Action Buttons: Loop balik ke Search Bar (F --> B) atau Reset --}}
                <div class="mt-6 flex flex-wrap items-center justify-center gap-3">
                    <button
                        type="button"
                        onclick="focusToSearchBar()"
                        class="inline-flex h-10 items-center justify-center gap-2 rounded-xl bg-primary px-5 text-xs font-semibold text-white shadow-xs hover:bg-primary-dark transition-all"
                    >
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                        <span>Coba Kata Kunci Lain</span>
                    </button>

                    <a
                        href="{{ route('opac') }}"
                        class="inline-flex h-10 items-center justify-center gap-2 rounded-xl border border-line bg-white px-4 text-xs font-medium text-ink hover:bg-subtle transition-colors"
                    >
                        <i class="fa-solid fa-rotate-left text-xs text-muted"></i>
                        <span>Lihat Semua Koleksi</span>
                    </a>
                </div>
            </div>
        </section>
    </div>

    {{-- ========================================================================= --}}
    {{-- MODAL INTERAKTIF: RINCIAN BIBLIOGRAFI & TABEL INVENTARIS EKSEMPLAR       --}}
    {{-- (Sesuai Flowchart: User Memilih Buku -> Tampilkan Detail Informasi Buku)   --}}
    {{-- ========================================================================= --}}
    <div
        id="book-detail-modal"
        class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-xs transition-opacity duration-200"
        role="dialog"
        aria-modal="true"
        aria-labelledby="modal-book-title"
    >
        <div class="flex min-h-screen items-center justify-center p-3 sm:p-6">
            <div class="relative w-full max-w-3xl overflow-hidden rounded-2xl border border-line bg-white shadow-2xl transition-all">
                {{-- Modal Header --}}
                <div class="flex items-center justify-between border-b border-line bg-slate-50/80 px-5 py-4">
                    <div class="flex items-center gap-2.5">
                        <span class="flex size-8 items-center justify-center rounded-lg bg-primary/10 text-primary">
                            <i class="fa-solid fa-book-open text-sm"></i>
                        </span>
                        <div>
                            <h3 class="text-sm font-bold text-ink">Rincian Koleksi &amp; Status Eksemplar Fisik</h3>
                            <p class="text-[11px] text-muted">Informasi ketersediaan buku fisik di rak Perpustakaan MNC University</p>
                        </div>
                    </div>
                    <button
                        type="button"
                        onclick="closeBookDetailModal()"
                        class="inline-flex size-8 items-center justify-center rounded-lg text-muted hover:bg-slate-200 hover:text-ink transition-colors"
                        aria-label="Tutup dialog rincian buku"
                    >
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </button>
                </div>

                {{-- Modal Body --}}
                <div class="max-h-[78vh] overflow-y-auto p-5 sm:p-6 space-y-6">
                    {{-- Baris Info Bibliografi Utama --}}
                    <div class="flex flex-col gap-5 sm:flex-row sm:items-start">
                        {{-- Cover Image --}}
                        <div class="relative h-48 w-36 shrink-0 overflow-hidden rounded-xl border border-line bg-subtle mx-auto sm:mx-0 shadow-xs">
                            <img
                                id="modal-cover-img"
                                src=""
                                alt="Sampul Koleksi"
                                class="h-full w-full object-cover"
                            >
                            <div id="modal-cover-placeholder" class="hidden h-full w-full flex-col items-center justify-center p-3 text-center text-muted">
                                <i class="fa-solid fa-book text-2xl text-primary/60"></i>
                                <span class="mt-2 text-[10px] font-semibold text-ink/70">MNC University</span>
                            </div>
                        </div>

                        {{-- Metadata Koleksi --}}
                        <div class="min-w-0 flex-1 space-y-2">
                            <div class="flex flex-wrap items-center gap-1.5">
                                <span id="modal-category" class="rounded-lg bg-primary/10 px-2.5 py-0.5 text-xs font-semibold text-primary">
                                    Buku Teks
                                </span>
                                <span id="modal-format" class="rounded-lg bg-subtle px-2.5 py-0.5 text-xs font-medium text-ink-secondary">
                                    Buku Cetak
                                </span>
                                <span id="modal-id" class="font-mono text-xs text-muted">
                                    #MNCU00001
                                </span>
                            </div>

                            <h4 id="modal-book-title" class="text-lg font-bold text-ink leading-snug sm:text-xl">
                                [Judul Koleksi]
                            </h4>

                            <div class="grid grid-cols-1 gap-1 text-xs text-ink-secondary sm:grid-cols-2 pt-1">
                                <p><span class="font-medium text-ink">Pengarang:</span> <span id="modal-author">-</span></p>
                                <p><span class="font-medium text-ink">Penerbit:</span> <span id="modal-publisher">-</span></p>
                                <p><span class="font-medium text-ink">Tahun Terbit:</span> <span id="modal-year">-</span></p>
                                <p><span class="font-medium text-ink">Edisi / Halaman:</span> <span id="modal-pages">-</span></p>
                                <p class="sm:col-span-2 font-mono"><span class="font-medium text-ink font-sans">ISBN:</span> <span id="modal-isbn">-</span></p>
                            </div>
                        </div>
                    </div>

                    {{-- 3 Metrik Status Stok --}}
                    <div class="grid grid-cols-3 gap-3">
                        <div class="rounded-xl border border-line bg-subtle/50 p-3 text-center">
                            <span class="text-[11px] font-medium text-muted uppercase">Total Eksemplar</span>
                            <p id="modal-stat-total" class="mt-1 text-xl font-bold text-ink">0</p>
                            <span class="text-[10px] text-muted">Tercatat di sistem</span>
                        </div>
                        <div class="rounded-xl border border-emerald-200 bg-emerald-50/60 p-3 text-center">
                            <span class="text-[11px] font-medium text-emerald-800 uppercase">Tersedia di Rak</span>
                            <p id="modal-stat-available" class="mt-1 text-xl font-bold text-emerald-900">0</p>
                            <span class="text-[10px] text-emerald-700">Siap dipinjam</span>
                        </div>
                        <div class="rounded-xl border border-amber-200 bg-amber-50/60 p-3 text-center">
                            <span class="text-[11px] font-medium text-amber-800 uppercase">Sedang Dipinjam</span>
                            <p id="modal-stat-borrowed" class="mt-1 text-xl font-bold text-amber-900">0</p>
                            <span class="text-[10px] text-amber-700">Di tangan pemustaka</span>
                        </div>
                    </div>

                    {{-- Sinopsis / Deskripsi --}}
                    <div class="rounded-xl border border-line bg-white p-4">
                        <h5 class="text-xs font-bold uppercase tracking-wider text-muted">Sinopsis &amp; Deskripsi</h5>
                        <p id="modal-description" class="mt-2 text-xs text-ink-secondary leading-relaxed whitespace-pre-line">
                            -
                        </p>
                    </div>

                    {{-- Tabel Seluruh Eksemplar Fisik --}}
                    <div>
                        <div class="flex items-center justify-between mb-2.5">
                            <h5 class="text-xs font-bold uppercase tracking-wider text-primary">
                                <i class="fa-solid fa-boxes-stacked mr-1.5"></i>Daftar Eksemplar Fisik &amp; Nomor Panggil
                            </h5>
                            <span id="modal-copy-count" class="text-xs text-muted">0 eksemplar</span>
                        </div>

                        <div class="overflow-x-auto rounded-xl border border-line bg-white shadow-xs">
                            <table class="w-full text-left text-xs">
                                <thead class="border-b border-line bg-slate-50 text-[11px] font-semibold uppercase tracking-wider text-muted">
                                    <tr>
                                        <th class="px-3.5 py-2.5">No</th>
                                        <th class="px-3.5 py-2.5">Barcode / Inventaris</th>
                                        <th class="px-3.5 py-2.5">Nomor Panggil</th>
                                        <th class="px-3.5 py-2.5">Lokasi Rak</th>
                                        <th class="px-3.5 py-2.5 text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody id="modal-exemplar-tbody" class="divide-y divide-line text-ink">
                                    {{-- Diisi secara dinamis via JavaScript --}}
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Catatan Alur Peminjaman --}}
                    <div class="rounded-xl border border-blue-200 bg-blue-50/70 p-3.5 text-xs text-blue-900 flex items-start gap-2.5">
                        <i class="fa-solid fa-circle-info text-primary text-sm shrink-0 mt-0.5"></i>
                        <p class="leading-relaxed">
                            <strong>Tata Cara Peminjaman Buku:</strong> Catat nomor panggil dan lokasi rak buku di atas, lalu kunjungi meja sirkulasi Perpustakaan MNC University Lantai 2 dengan membawa Kartu Tanda Mahasiswa (KTM) aktif.
                        </p>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="flex items-center justify-between border-t border-line bg-slate-50/80 px-5 py-3.5">
                    <button
                        type="button"
                        onclick="closeBookDetailModal()"
                        class="inline-flex h-9 items-center justify-center rounded-xl border border-line bg-white px-4 text-xs font-medium text-ink hover:bg-subtle transition-colors"
                    >
                        Tutup
                    </button>

                    <a
                        id="modal-chat-link"
                        href="{{ route('chat') }}"
                        class="inline-flex h-9 items-center gap-1.5 rounded-xl bg-primary px-4 text-xs font-semibold text-white shadow-xs hover:bg-primary-dark transition-all"
                    >
                        <i class="fa-solid fa-headset text-xs"></i>
                        <span>Tanya Pustakawan</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Script Logika Interaktif OPAC (Flowchart SearchBar -> Filter -> Select Book -> Detail) --}}
    <script>
        // Data koleksi untuk live-search dan auto-open detail
        const allCatalogItems = @json($items);
        const selectedBookId = @json($selectedBookId);

        // Handler klik kartu (Memilih Buku)
        function handleCardClick(event, item) {
            // Hindari memicu modal jika user mengklik link atau tombol di dalam kartu
            if (event.target.closest('a') || event.target.closest('button')) {
                return;
            }
            openBookDetailModal(item);
        }

        // Handler keyboard (Enter / Space) untuk aksesibilitas pemilihan buku
        function handleCardKeydown(event, item) {
            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                openBookDetailModal(item);
            }
        }

        // Tampilkan Detail Buku di Modal (Flowchart: Tampilkan detail informasi buku)
        function openBookDetailModal(item) {
            const modal = document.getElementById('book-detail-modal');
            if (!modal || !item) return;

            document.getElementById('modal-book-title').textContent = item.title || '-';
            document.getElementById('modal-author').textContent = item.author || '-';
            document.getElementById('modal-publisher').textContent = (item.publisher || '-') + (item.publisher_city ? ' (' + item.publisher_city + ')' : '');
            document.getElementById('modal-year').textContent = item.year || '-';
            document.getElementById('modal-pages').textContent = (item.edition || '-') + ' / ' + (item.pages || '-');
            document.getElementById('modal-isbn').textContent = item.isbn || '-';
            document.getElementById('modal-category').textContent = item.category || 'Buku Teks';
            document.getElementById('modal-format').textContent = item.format || 'Buku Cetak';
            document.getElementById('modal-id').textContent = '#' + (item.id || '');
            document.getElementById('modal-description').textContent = item.description || 'Tidak ada deskripsi rinci.';

            // Stats
            const total = item.copies_total || 0;
            const available = item.copies_available || 0;
            const borrowed = item.copies_borrowed || 0;

            document.getElementById('modal-stat-total').textContent = total;
            document.getElementById('modal-stat-available').textContent = available;
            document.getElementById('modal-stat-borrowed').textContent = borrowed;
            document.getElementById('modal-copy-count').textContent = total + ' eksemplar terdaftar';

            // Cover Image
            const coverImg = document.getElementById('modal-cover-img');
            const placeholder = document.getElementById('modal-cover-placeholder');
            if (item.cover_url) {
                coverImg.src = item.cover_url;
                coverImg.classList.remove('hidden');
                placeholder.classList.add('hidden');
            } else {
                coverImg.classList.add('hidden');
                placeholder.classList.remove('hidden');
                placeholder.classList.add('flex');
            }

            // Chat link
            const chatLink = document.getElementById('modal-chat-link');
            if (chatLink) {
                chatLink.href = '{{ route("chat") }}?q=' + encodeURIComponent('Halo Pustakawan, saya ingin reservasi/cek buku: ' + item.title);
            }

            // Exemplar table
            const tbody = document.getElementById('modal-exemplar-tbody');
            tbody.innerHTML = '';

            const eksemplarList = item.eksemplar_list || [];
            if (eksemplarList.length === 0) {
                const tr = document.createElement('tr');
                tr.innerHTML = '<td colspan="5" class="px-4 py-6 text-center text-muted text-xs">Belum ada eksemplar fisik tercatat untuk koleksi ini.</td>';
                tbody.appendChild(tr);
            } else {
                eksemplarList.forEach(function (ek, idx) {
                    const tr = document.createElement('tr');
                    tr.className = 'hover:bg-slate-50 transition-colors';

                    let statusBadge = '<span class="inline-flex items-center rounded-full bg-emerald-50 px-2 py-0.5 text-[11px] font-semibold text-emerald-700 border border-emerald-200">Tersedia</span>';
                    if (ek.status === 'Dipinjam') {
                        statusBadge = '<span class="inline-flex items-center rounded-full bg-amber-50 px-2 py-0.5 text-[11px] font-semibold text-amber-700 border border-amber-200">Dipinjam</span>';
                    } else if (ek.status === 'Rusak' || ek.status === 'Perbaikan') {
                        statusBadge = '<span class="inline-flex items-center rounded-full bg-rose-50 px-2 py-0.5 text-[11px] font-semibold text-rose-700 border border-rose-200">Perbaikan</span>';
                    } else if (ek.status === 'Hilang') {
                        statusBadge = '<span class="inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-[11px] font-semibold text-slate-700 border border-slate-200">Hilang</span>';
                    }

                    tr.innerHTML = `
                        <td class="px-3.5 py-3 text-muted font-mono">${idx + 1}</td>
                        <td class="px-3.5 py-3 font-mono font-medium text-ink">${ek.kode_inventaris || '-'}</td>
                        <td class="px-3.5 py-3 font-mono text-primary">${ek.nomor_panggil || '-'}</td>
                        <td class="px-3.5 py-3 text-ink-secondary">${ek.lokasi_rak || '-'}</td>
                        <td class="px-3.5 py-3 text-center whitespace-nowrap">${statusBadge}</td>
                    `;
                    tbody.appendChild(tr);
                });
            }

            // Update URL hash/query secara aman tanpa reload halaman
            const url = new URL(window.location.href);
            url.searchParams.set('buku', item.id);
            window.history.replaceState({ bookId: item.id }, '', url.toString());

            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        // Tutup Modal Detail Buku
        function closeBookDetailModal() {
            const modal = document.getElementById('book-detail-modal');
            if (modal) {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');

                // Hapus parameter buku dari URL
                const url = new URL(window.location.href);
                if (url.searchParams.has('buku')) {
                    url.searchParams.delete('buku');
                    window.history.replaceState({}, '', url.toString());
                }
            }
        }

        // Fokus kembali ke Search Bar (Flowchart: F --> B jika buku tidak ditemukan)
        function focusToSearchBar() {
            const input = document.getElementById('opac-search-input');
            if (input) {
                input.scrollIntoView({ behavior: 'smooth', block: 'center' });
                input.focus();
                input.select();
            }
        }

        // Bersihkan input pencarian
        function clearSearchInput() {
            const input = document.getElementById('opac-search-input');
            const clearBtn = document.getElementById('btn-clear-search');
            if (input) {
                input.value = '';
                input.focus();
                if (clearBtn) clearBtn.classList.add('hidden');
                runLiveFilter();
            }
        }

        // Live Client-side Search & Filter dengan Debounce
        let searchDebounceTimer = null;
        function runLiveFilter() {
            const searchInput = document.getElementById('opac-search-input');
            const kriteriaSelect = document.getElementById('opac-kriteria');
            const clearBtn = document.getElementById('btn-clear-search');
            const liveStatus = document.getElementById('live-search-status');
            const liveText = document.getElementById('live-search-text');
            const grid = document.getElementById('catalog-grid');
            const emptyContainer = document.getElementById('empty-state-container');
            const emptyMsg = document.getElementById('empty-state-message');
            const totalBadge = document.getElementById('badge-total-judul');

            if (!searchInput || !grid) return;

            const query = searchInput.value.trim().toLowerCase();
            const kriteria = kriteriaSelect ? kriteriaSelect.value : 'semua';

            // Toggle tombol clear (X)
            if (clearBtn) {
                if (query.length > 0) {
                    clearBtn.classList.remove('hidden');
                } else {
                    clearBtn.classList.add('hidden');
                }
            }

            const cards = grid.querySelectorAll('.opac-card');
            let visibleCount = 0;

            cards.forEach(card => {
                let match = false;
                if (!query) {
                    match = true;
                } else {
                    const title = card.getAttribute('data-title') || '';
                    const author = card.getAttribute('data-author') || '';
                    const publisher = card.getAttribute('data-publisher') || '';
                    const isbn = card.getAttribute('data-isbn') || '';
                    const callNumber = card.getAttribute('data-call-number') || '';
                    const subjects = card.getAttribute('data-subjects') || '';

                    switch (kriteria) {
                        case 'judul':
                            match = title.includes(query);
                            break;
                        case 'pengarang':
                            match = author.includes(query);
                            break;
                        case 'penerbit':
                            match = publisher.includes(query);
                            break;
                        case 'isbn':
                            match = isbn.includes(query);
                            break;
                        case 'nomor_panggil':
                            match = callNumber.includes(query);
                            break;
                        case 'subjek':
                            match = subjects.includes(query);
                            break;
                        default:
                            match = title.includes(query) ||
                                    author.includes(query) ||
                                    publisher.includes(query) ||
                                    isbn.includes(query) ||
                                    callNumber.includes(query) ||
                                    subjects.includes(query);
                            break;
                    }
                }

                if (match) {
                    card.style.display = '';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            // Update status badge & empty state
            if (totalBadge) {
                totalBadge.textContent = visibleCount + ' judul';
            }

            if (visibleCount > 0) {
                grid.classList.remove('hidden');
                if (emptyContainer) emptyContainer.classList.add('hidden');

                if (query && liveStatus && liveText) {
                    liveStatus.classList.remove('hidden');
                    liveStatus.classList.add('flex');
                    liveText.textContent = `Menemukan ${visibleCount} koleksi untuk kata kunci "${searchInput.value.trim()}".`;
                } else if (liveStatus) {
                    liveStatus.classList.add('hidden');
                    liveStatus.classList.remove('flex');
                }
            } else {
                // Sesuai flowchart: Tampilkan pesan 'koleksi tidak ditemukan'
                grid.classList.add('hidden');
                if (emptyContainer) {
                    emptyContainer.classList.remove('hidden');
                    emptyContainer.classList.add('flex');
                }
                if (emptyMsg) {
                    emptyMsg.innerHTML = `Tidak ditemukan koleksi dengan kata kunci <strong class="text-ink font-semibold">"${searchInput.value.trim()}"</strong> pada kriteria <span class="text-primary font-medium">${kriteriaSelect ? kriteriaSelect.options[kriteriaSelect.selectedIndex].text : 'katalog'}</span>.`;
                }
                if (liveStatus) {
                    liveStatus.classList.add('hidden');
                    liveStatus.classList.remove('flex');
                }
            }
        }

        // Listener input real-time dengan debounce
        const searchInputEl = document.getElementById('opac-search-input');
        if (searchInputEl) {
            searchInputEl.addEventListener('input', function () {
                clearTimeout(searchDebounceTimer);
                searchDebounceTimer = setTimeout(runLiveFilter, 150);
            });
        }

        const kriteriaSelectEl = document.getElementById('opac-kriteria');
        if (kriteriaSelectEl) {
            kriteriaSelectEl.addEventListener('change', function () {
                runLiveFilter();
            });
        }

        // Tutup modal dengan tombol Escape
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeBookDetailModal();
            }
        });

        // Tutup modal jika klik di luar area dialog
        document.getElementById('book-detail-modal')?.addEventListener('click', function (e) {
            if (e.target === this) {
                closeBookDetailModal();
            }
        });

        // Buka otomatis detail buku jika ada parameter buku pada URL saat memuat halaman
        document.addEventListener('DOMContentLoaded', function () {
            const urlParams = new URLSearchParams(window.location.search);
            const targetId = selectedBookId || urlParams.get('buku');
            if (targetId && allCatalogItems && allCatalogItems.length > 0) {
                const foundItem = allCatalogItems.find(item => String(item.id) === String(targetId));
                if (foundItem) {
                    openBookDetailModal(foundItem);
                }
            }
        });
    </script>
@endsection
