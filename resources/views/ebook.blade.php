@extends('layouts.app')

@section('title', 'Koleksi E-Book')
@section('description', 'Koleksi buku elektronik resmi Perpustakaan MNC University. Baca buku teks perkuliahan dan referensi digital dari berbagai perangkat kapan saja.')

@section('content')
    <div class="mx-auto flex w-full max-w-[1570px] flex-1 flex-col gap-8 px-3 py-6 sm:px-4 lg:px-6 xl:px-8 lg:py-10 responsive-desktop-container">
        {{-- Breadcrumb & Header --}}
        <div class="flex flex-col gap-2">
            <nav aria-label="Breadcrumb" class="flex items-center gap-2 text-xs text-muted sm:text-sm">
                <a href="{{ route('beranda') }}" class="hover:text-primary">Beranda</a>
                <svg class="size-3.5 text-muted/60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
                <span class="font-medium text-ink">E-Book</span>
            </nav>
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-semibold text-primary sm:text-3xl lg:text-4xl">
                        Koleksi E-Book Digital
                    </h1>
                    <p class="mt-1 text-sm text-ink-secondary sm:text-base">
                        Buku teks perkuliahan dan monograf dalam format elektronik yang dapat diakses secara fleksibel dari perangkat Anda.
                    </p>
                </div>
                <div class="rounded-xl border border-[#FECDD3] bg-[#E11D48]/5 px-4 py-2 text-xs text-[#E11D48]">
                    Koleksi Tersedia: <span class="font-bold">{{ $totalCount }} Judul E-Book</span>
                </div>
            </div>
        </div>

        {{-- Search & Filter Section --}}
        <section aria-labelledby="judul-filter-ebook" class="rounded-2xl border border-line bg-white p-5 lg:p-6 shadow-sm">
            <h2 id="judul-filter-ebook" class="sr-only">Form Pencarian E-Book</h2>
            <form action="{{ route('ebook') }}" method="GET" class="flex flex-col gap-4">
                <div class="relative flex flex-1 items-center">
                    <span class="pointer-events-none absolute left-4 text-muted">
                        <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"/>
                            <path d="m21 21-4.3-4.3"/>
                        </svg>
                    </span>
                    <input
                        type="text"
                        name="q"
                        value="{{ $query }}"
                        placeholder="Cari judul buku digital atau nama pengarang (misal: Rekayasa Perangkat Lunak, Jurnalisme)..."
                        class="h-13 w-full rounded-xl border border-line bg-subtle pl-12 pr-4 text-sm text-ink placeholder:text-muted focus:border-[#E11D48] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#E11D48]"
                    >
                </div>

                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-xs font-medium text-ink-secondary">Kategori:</span>
                        @foreach ($categories as $cat)
                            <a
                                href="{{ route('ebook', ['kategori' => $cat, 'q' => $query]) }}"
                                class="inline-flex h-9 items-center rounded-xl px-3.5 text-xs font-semibold transition-colors duration-150 {{ $kategori === $cat ? 'bg-[#E11D48] text-white shadow-sm' : 'border border-line bg-subtle text-ink hover:border-[#E11D48]/40 hover:bg-white' }}"
                            >
                                {{ $cat }}
                            </a>
                        @endforeach
                    </div>

                    <div class="flex items-center gap-2">
                        <button
                            type="submit"
                            class="inline-flex h-9 items-center gap-2 rounded-xl bg-[#E11D48] px-5 text-xs font-semibold text-white shadow-sm transition-colors duration-150 hover:bg-[#BE123C]"
                        >
                            <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"/>
                                <path d="m21 21-4.3-4.3"/>
                            </svg>
                            Cari E-Book
                        </button>
                        @if ($query !== '' || $kategori !== 'Semua')
                            <a
                                href="{{ route('ebook') }}"
                                class="inline-flex h-9 items-center rounded-xl border border-line bg-subtle px-3.5 text-xs font-medium text-ink-secondary hover:bg-line"
                            >
                                Reset
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </section>

        {{-- E-Book Collection Grid --}}
        <section aria-labelledby="daftar-koleksi-ebook">
            <h2 id="daftar-koleksi-ebook" class="sr-only">Daftar Buku Elektronik</h2>

            @if (count($books) > 0)
                <div class="grid gap-5 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 lg:gap-6">
                    @foreach ($books as $book)
                        <article class="group flex flex-col justify-between overflow-hidden rounded-2xl border border-line bg-white shadow-sm transition duration-200 hover:-translate-y-0.5 hover:border-[#FECDD3] hover:shadow-raised">
                            <div>
                                {{-- Cover Image & Badge Digital --}}
                                <div class="relative aspect-[3/4] sm:aspect-[4/5] lg:aspect-[3/4] w-full overflow-hidden bg-slate-100">
                                    <img
                                        src="{{ $book['cover_url'] }}"
                                        alt="{{ $book['title'] }}"
                                        loading="lazy"
                                        class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
                                    >
                                    <div class="absolute top-2.5 right-2.5 flex items-center gap-1.5 rounded-lg bg-[#E11D48]/90 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-white shadow-sm backdrop-blur-md">
                                        <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                                            <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                                        </svg>
                                        <span>{{ $book['format'] }}</span>
                                    </div>
                                    <div class="absolute bottom-2.5 left-2.5 rounded-md bg-black/60 px-2.5 py-0.5 text-[11px] font-medium text-white backdrop-blur-sm">
                                        {{ $book['category'] }}
                                    </div>
                                </div>

                                {{-- Info Buku --}}
                                <div class="p-5">
                                    <h3 class="line-clamp-2 text-base font-semibold text-ink transition-colors group-hover:text-[#E11D48] sm:text-lg leading-snug" title="{{ $book['title'] }}">
                                        {{ $book['title'] }}
                                    </h3>

                                    <div class="mt-1.5 text-xs text-ink-secondary">
                                        <span class="text-muted">Penulis:</span> <span class="font-medium text-ink">{{ $book['author'] }}</span>
                                    </div>

                                    <p class="mt-2.5 line-clamp-2 text-xs leading-relaxed text-muted">
                                        {{ $book['description'] ?? 'Koleksi buku teks dan referensi akademik digital resmi MNC University.' }}
                                    </p>

                                    <div class="mt-4 flex items-center justify-between rounded-xl bg-subtle p-2.5 text-xs text-muted">
                                        <span>Tahun Terbit: <strong class="text-ink">{{ $book['year'] }}</strong></span>
                                        <span>Jumlah: <strong class="text-ink">{{ $book['pages'] }}</strong></span>
                                    </div>
                                </div>
                            </div>

                            <div class="p-5 pt-0">
                                <button
                                    type="button"
                                    onclick="alert('Membuka reader e-book interaktif: {{ addslashes($book['title']) }}. Gunakan akun SSO MNC University untuk verifikasi hak baca.')"
                                    class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#E11D48] px-4 py-2.5 text-xs font-semibold text-white shadow-sm transition-colors hover:bg-[#BE123C]"
                                >
                                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                                        <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                                    </svg>
                                    <span>Baca E-Book</span>
                                </button>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="flex flex-col items-center justify-center rounded-2xl border border-line bg-subtle p-12 text-center">
                    <div class="flex size-14 items-center justify-center rounded-2xl bg-white text-muted shadow-sm">
                        <svg class="size-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"/>
                            <path d="m21 21-4.3-4.3"/>
                        </svg>
                    </div>
                    <h3 class="mt-4 text-base font-semibold text-ink">E-Book Tidak Ditemukan</h3>
                    <p class="mt-1 max-w-md text-xs text-muted">
                        Buku elektronik dengan kata kunci tersebut tidak tersedia. Coba gunakan kata kunci lain atau pilih kategori Semua.
                    </p>
                    <a
                        href="{{ route('ebook') }}"
                        class="mt-5 inline-flex h-10 items-center justify-center rounded-xl bg-[#E11D48] px-5 text-xs font-semibold text-white shadow-sm hover:bg-[#BE123C]"
                    >
                        Lihat Semua E-Book
                    </a>
                </div>
            @endif
        </section>
    </div>
@endsection
