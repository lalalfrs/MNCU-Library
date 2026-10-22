@extends('layouts.app')

@section('title', 'Manajemen Buku & Katalog')
@section('description', 'Kelola koleksi buku fisik, e-book, kategori, penerbit, nomor ISBN, dan ketersediaan stok eksemplar perpustakaan MNC University.')

@section('content')
    <div class="mx-auto flex w-full max-w-[1570px] flex-1 flex-col gap-6 px-3 py-6 lg:px-6 lg:py-8">
        {{-- Breadcrumb & Header --}}
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

        {{-- 4 Kartu Metrik Inventaris Stok Global --}}
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
            <div class="rounded-2xl border border-line bg-white p-4 shadow-card">
                <div class="flex items-center gap-2.5">
                    <span class="flex size-9 items-center justify-center rounded-xl bg-primary/10 text-primary">
                        <i class="fa-solid fa-book-bookmark text-sm"></i>
                    </span>
                    <span class="text-[11px] font-semibold uppercase tracking-wider text-muted">Total Judul</span>
                </div>
                <div class="mt-2 text-2xl font-bold text-primary">{{ number_format($totalBooks ?? 0) }}</div>
                <span class="text-[11px] text-muted">Judul buku terdaftar</span>
            </div>

            <div class="rounded-2xl border border-line bg-white p-4 shadow-card">
                <div class="flex items-center gap-2.5">
                    <span class="flex size-9 items-center justify-center rounded-xl bg-[#7C3AED]/10 text-[#7C3AED]">
                        <i class="fa-solid fa-boxes-stacked text-sm"></i>
                    </span>
                    <span class="text-[11px] font-semibold uppercase tracking-wider text-muted">Total Eksemplar</span>
                </div>
                <div class="mt-2 text-2xl font-bold text-ink">{{ number_format($totalCopies ?? 0) }}</div>
                <span class="text-[11px] text-muted">Buku fisik di perpustakaan</span>
            </div>

            <div class="rounded-2xl border border-emerald-200 bg-emerald-50/40 p-4 shadow-card">
                <div class="flex items-center gap-2.5">
                    <span class="flex size-9 items-center justify-center rounded-xl bg-emerald-100 text-emerald-800">
                        <i class="fa-solid fa-circle-check text-sm"></i>
                    </span>
                    <span class="text-[11px] font-semibold uppercase tracking-wider text-emerald-800">Stok Siap Pinjam</span>
                </div>
                <div class="mt-2 text-2xl font-bold text-emerald-900">{{ number_format($availableCopies ?? 0) }}</div>
                <span class="text-[11px] text-emerald-700">Tersedia di rak</span>
            </div>

            <div class="rounded-2xl border border-amber-200 bg-amber-50/40 p-4 shadow-card">
                <div class="flex items-center gap-2.5">
                    <span class="flex size-9 items-center justify-center rounded-xl bg-amber-100 text-amber-800">
                        <i class="fa-solid fa-clock-rotate-left text-sm"></i>
                    </span>
                    <span class="text-[11px] font-semibold uppercase tracking-wider text-amber-800">Sedang Dipinjam</span>
                </div>
                <div class="mt-2 text-2xl font-bold text-amber-900">{{ number_format($borrowedCopies ?? 0) }}</div>
                <span class="text-[11px] text-amber-700">Di tangan pemustaka</span>
            </div>
        </div>

        {{-- Filter & Pencarian Bar --}}
        <div class="rounded-2xl border border-line bg-white p-4 shadow-card">
            <form method="GET" action="{{ route('admin.books.index') }}" id="filter-form" class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <input type="hidden" name="view" id="filter-view-input" value="{{ $viewMode }}">

                <div class="relative flex-1">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </span>
                    <input
                        type="text"
                        name="q"
                        value="{{ $q }}"
                        placeholder="Cari judul buku, ISBN, pengarang, nomor barcode, atau nomor panggil..."
                        class="h-10 w-full rounded-xl border border-line bg-subtle/50 pr-4 pl-10 text-xs text-ink placeholder:text-muted focus:border-primary focus:bg-white focus:outline-none sm:text-sm"
                    >
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <select
                        name="kategori"
                        onchange="this.form.submit()"
                        class="h-10 rounded-xl border border-line bg-white px-3 text-xs text-ink focus:border-primary focus:outline-none sm:text-sm"
                    >
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id_kategori_buku }}" {{ $kategoriId === $cat->id_kategori_buku ? 'selected' : '' }}>
                                {{ $cat->nama_kategori }}
                            </option>
                        @endforeach
                    </select>

                    <select
                        name="jenis"
                        onchange="this.form.submit()"
                        class="h-10 rounded-xl border border-line bg-white px-3 text-xs text-ink focus:border-primary focus:outline-none sm:text-sm"
                    >
                        <option value="">Semua Format</option>
                        @foreach ($types as $type)
                            <option value="{{ $type->id_jenis_buku }}" {{ $jenisId === $type->id_jenis_buku ? 'selected' : '' }}>
                                {{ $type->nama_jenis_buku }}
                            </option>
                        @endforeach
                    </select>

                    <select
                        name="stok"
                        onchange="this.form.submit()"
                        class="h-10 rounded-xl border border-line bg-white px-3 text-xs text-ink focus:border-primary focus:outline-none sm:text-sm"
                    >
                        <option value="">Semua Status Stok</option>
                        <option value="tersedia" {{ ($statusStok ?? '') === 'tersedia' ? 'selected' : '' }}>Stok Siap Pinjam</option>
                        <option value="habis" {{ ($statusStok ?? '') === 'habis' ? 'selected' : '' }}>Semua Dipinjam / Habis</option>
                        <option value="kosong" {{ ($statusStok ?? '') === 'kosong' ? 'selected' : '' }}>Belum Ada Eksemplar</option>
                    </select>

                    <button
                        type="submit"
                        class="inline-flex h-10 items-center justify-center rounded-xl bg-primary/10 px-4 text-xs font-semibold text-primary transition-colors hover:bg-primary hover:text-white sm:text-sm"
                    >
                        Filter
                    </button>

                    @if ($q !== '' || $kategoriId !== '' || $jenisId !== '' || ($statusStok ?? '') !== '')
                        <a
                            href="{{ route('admin.books.index', ['view' => $viewMode]) }}"
                            class="inline-flex h-10 items-center justify-center rounded-xl border border-line px-3 text-xs font-medium text-muted transition-colors hover:bg-subtle hover:text-ink sm:text-sm"
                            title="Reset Filter"
                        >
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>

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
                            <th scope="col" class="px-5 py-3.5">ISBN</th>
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
                                            @if ($book->sampul_buku)
                                                <img src="{{ $book->sampul_buku }}" alt="{{ $book->judul_buku }}" class="h-full w-full object-cover">
                                            @else
                                                <div class="flex h-full w-full items-center justify-center bg-primary/10 text-primary">
                                                    <i class="fa-solid fa-book text-sm"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <a href="{{ route('admin.books.show', $book->id_buku) }}" class="font-semibold text-ink leading-snug hover:text-primary transition-colors">
                                                {{ $book->judul_buku }}
                                            </a>
                                            <p class="mt-0.5 text-xs text-muted truncate">
                                                Penulis: {{ $book->penulis->pluck('nama_penulis')->join(', ') ?: 'Tim Perpustakaan' }}
                                            </p>
                                            <span class="font-mono text-[10px] text-muted">{{ $book->id_buku }}</span>
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

                                {{-- ISBN --}}
                                <td class="px-5 py-4 whitespace-nowrap font-mono text-xs text-muted">
                                    {{ $book->isbn ?? '-' }}
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

                                        {{-- Hapus --}}
                                        <form
                                            action="{{ route('admin.books.destroy', $book->id_buku) }}"
                                            method="POST"
                                            onsubmit="return confirm('Hapus buku {{ $book->judul_buku }} beserta seluruh riwayat eksemplarnya?')"
                                            class="inline-block"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                class="inline-flex size-8 items-center justify-center rounded-lg border border-red-200 text-red-600 transition-colors hover:bg-red-50"
                                                title="Hapus Buku"
                                            >
                                                <i class="fa-regular fa-trash-can text-xs"></i>
                                            </button>
                                        </form>
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

        {{-- 2. TAMPILAN GRID (CARD VIEW) DILENGKAPI CHIP STOK --}}
        <div id="books-grid-view" class="{{ $viewMode === 'table' ? 'hidden' : '' }}">
            @if ($books->isEmpty())
                <div class="rounded-2xl border border-line bg-white p-12 text-center shadow-card">
                    <div class="flex flex-col items-center justify-center gap-2">
                        <i class="fa-solid fa-book-open text-3xl text-muted/40"></i>
                        <p class="font-medium text-ink">Tidak ada koleksi buku ditemukan.</p>
                        <p class="text-xs text-muted">Coba ubah kata kunci pencarian atau filter kategori.</p>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                    @foreach ($books as $book)
                        @php
                            $totalEk = $book->eksemplar->count();
                            $readyEk = $book->eksemplar->where('status', 'Tersedia')->count();
                            $firstCopy = $book->eksemplar->first();
                        @endphp
                        <div class="group relative flex flex-col overflow-hidden rounded-2xl border border-line bg-white shadow-card transition-all duration-200 hover:-translate-y-1 hover:shadow-lg">
                            {{-- Sampul Buku --}}
                            <div class="relative aspect-[3/4] w-full overflow-hidden bg-gradient-to-br from-subtle to-slate-100">
                                @if ($book->sampul_buku)
                                    <img
                                        src="{{ $book->sampul_buku }}"
                                        alt="{{ $book->judul_buku }}"
                                        class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
                                        loading="lazy"
                                    >
                                @else
                                    <div class="flex h-full w-full flex-col items-center justify-center p-4 text-center text-muted">
                                        <div class="flex size-14 items-center justify-center rounded-2xl bg-primary/10 text-primary">
                                            <i class="fa-solid fa-book text-2xl"></i>
                                        </div>
                                        <span class="mt-2 text-xs font-medium text-ink/70">Tanpa Sampul</span>
                                    </div>
                                @endif

                                {{-- Badges Melayang di Atas Gambar --}}
                                <div class="absolute inset-x-2.5 top-2.5 flex items-center justify-between gap-1.5">
                                    <span class="inline-flex items-center rounded-md border border-line/60 bg-white/95 px-2 py-0.5 text-[10px] font-semibold text-primary shadow-xs backdrop-blur">
                                        {{ $book->jenisBuku?->nama_jenis_buku ?? 'Buku Cetak' }}
                                    </span>
                                    <span class="inline-flex items-center rounded-md bg-slate-900/80 px-2 py-0.5 font-mono text-[10px] font-medium text-white shadow-xs backdrop-blur">
                                        {{ $book->id_buku }}
                                    </span>
                                </div>

                                {{-- Badge Stok di Pojok Bawah Gambar --}}
                                <div class="absolute inset-x-2.5 bottom-2.5">
                                    @if ($totalEk === 0)
                                        <span class="inline-flex items-center gap-1 rounded-md bg-slate-900/80 px-2 py-0.5 text-[10px] font-medium text-slate-200 backdrop-blur">
                                            0 Eksemplar
                                        </span>
                                    @elseif ($readyEk > 0)
                                        <span class="inline-flex items-center gap-1.5 rounded-md bg-emerald-900/90 px-2 py-0.5 text-[10px] font-semibold text-emerald-200 backdrop-blur">
                                            <span class="size-1.5 rounded-full bg-emerald-400"></span>
                                            Stok: {{ $readyEk }} / {{ $totalEk }} Tersedia
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 rounded-md bg-amber-900/90 px-2 py-0.5 text-[10px] font-semibold text-amber-200 backdrop-blur">
                                            <span class="size-1.5 rounded-full bg-amber-400"></span>
                                            Dipinjam Semua ({{ $totalEk }})
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Isi Rincian Kartu --}}
                            <div class="flex flex-1 flex-col p-4">
                                {{-- Kategori --}}
                                <div class="flex items-center justify-between text-[11px] text-muted">
                                    <span class="font-medium text-primary">{{ $book->kategoriBuku?->nama_kategori ?? 'Umum' }}</span>
                                    <span>{{ $book->tahun_terbit ?? '-' }}</span>
                                </div>

                                {{-- Judul Buku --}}
                                <a href="{{ route('admin.books.show', $book->id_buku) }}">
                                    <h3 class="mt-1.5 line-clamp-2 text-sm font-bold text-ink transition-colors group-hover:text-primary">
                                        {{ $book->judul_buku }}
                                    </h3>
                                </a>

                                {{-- Pengarang & Penerbit --}}
                                <div class="mt-2 space-y-1 text-xs text-muted">
                                    <p class="truncate" title="Penulis">
                                        <i class="fa-solid fa-user-pen mr-1.5 text-[10px] text-primary/70"></i>
                                        {{ $book->penulis->pluck('nama_penulis')->join(', ') ?: 'Tim Perpustakaan' }}
                                    </p>
                                    <p class="truncate" title="Penerbit">
                                        <i class="fa-solid fa-building-columns mr-1.5 text-[10px] text-primary/70"></i>
                                        {{ $book->penerbit?->nama_penerbit ?? 'Penerbit MNCU' }}
                                    </p>
                                    @if ($firstCopy?->lokasi_rak)
                                        <p class="truncate text-[11px] text-ink-secondary" title="Lokasi Rak">
                                            <i class="fa-solid fa-location-dot mr-1.5 text-[10px] text-primary/70"></i>
                                            {{ $firstCopy->lokasi_rak }}
                                        </p>
                                    @endif
                                </div>

                                {{-- Action Bar di Bagian Bawah Kartu --}}
                                <div class="mt-auto flex items-center gap-1.5 pt-4 border-t border-line">
                                    <a
                                        href="{{ route('admin.books.show', $book->id_buku) }}"
                                        class="inline-flex h-9 flex-1 items-center justify-center gap-1 rounded-xl bg-primary/10 text-xs font-semibold text-primary transition-colors hover:bg-primary hover:text-white"
                                        title="Detail & Kelola Eksemplar"
                                    >
                                        <i class="fa-solid fa-boxes-stacked text-[11px]"></i>
                                        <span>Stok</span>
                                    </a>

                                    <a
                                        href="{{ route('opac', ['buku' => $book->id_buku]) }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="inline-flex size-9 items-center justify-center rounded-xl border border-line bg-subtle/40 text-xs font-semibold text-emerald-600 transition-colors hover:border-emerald-500 hover:bg-emerald-50 hover:text-emerald-700"
                                        title="Lihat Detail Buku di Katalog OPAC (Tab Baru)"
                                    >
                                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                                    </a>

                                    <a
                                        href="{{ route('admin.books.edit', $book->id_buku) }}"
                                        class="inline-flex size-9 items-center justify-center rounded-xl border border-line bg-subtle/40 text-xs font-semibold text-ink transition-colors hover:border-primary hover:bg-primary/5 hover:text-primary"
                                        title="Ubah Buku"
                                    >
                                        <i class="fa-regular fa-pen-to-square text-xs"></i>
                                    </a>

                                    <form
                                        action="{{ route('admin.books.destroy', $book->id_buku) }}"
                                        method="POST"
                                        onsubmit="return confirm('Hapus buku {{ $book->judul_buku }} beserta seluruh eksemplarnya?')"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            type="submit"
                                            class="inline-flex size-9 items-center justify-center rounded-xl border border-red-200 text-red-600 transition-colors hover:bg-red-50"
                                            title="Hapus Buku"
                                        >
                                            <i class="fa-regular fa-trash-can text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
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

    {{-- Script Switcher Row (Tabel) <-> Grid (Kartu) --}}
    <script>
        function switchBookView(mode) {
            const tableView = document.getElementById('books-table-view');
            const gridView = document.getElementById('books-grid-view');
            const btnTable = document.getElementById('btn-view-table');
            const btnGrid = document.getElementById('btn-view-grid');
            const filterInput = document.getElementById('filter-view-input');

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
