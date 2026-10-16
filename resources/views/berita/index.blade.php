@extends('layouts.app')

@section('title', 'Berita & Pengumuman Perpustakaan')
@section('description', 'Kumpulan berita, pengumuman, dan artikel informasi terbaru dari Perpustakaan MNC University.')

@section('content')
    <div class="mx-auto flex max-w-[1570px] flex-col gap-8 px-3 py-6 lg:gap-12 lg:px-6 lg:py-10">
        
        {{-- Breadcrumbs & Header --}}
        <div class="flex flex-col gap-4">
            <nav aria-label="Breadcrumb" class="flex items-center text-sm text-muted">
                <ol class="flex items-center gap-2">
                    <li>
                        <a href="{{ route('beranda') }}" class="hover:text-primary transition-colors">Beranda</a>
                    </li>
                    <li aria-hidden="true" class="text-muted/60">&rsaquo;</li>
                    <li class="font-medium text-ink" aria-current="page">Berita Perpustakaan</li>
                </ol>
            </nav>

            <div class="flex flex-col justify-between gap-4 md:flex-row md:items-end">
                <div>
                    <h1 class="text-2xl font-semibold text-primary sm:text-3xl lg:text-4xl">
                        Berita & Pengumuman
                    </h1>
                    <p class="mt-2 text-sm text-ink-secondary sm:text-base lg:text-lg">
                        Informasi terkini seputar layanan, koleksi baru, dan kegiatan akademik Perpustakaan MNC University.
                    </p>
                </div>
            </div>
        </div>

        {{-- Search & Filter Section --}}
        <section aria-label="Pencarian dan Filter Berita" class="rounded-2xl border border-line bg-white p-4 shadow-card lg:p-6">
            <form method="GET" action="{{ route('berita.index') }}" class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                {{-- Search Input --}}
                <div class="relative flex-1">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted">
                        <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </span>
                    <input
                        type="search"
                        name="q"
                        value="{{ $q ?? '' }}"
                        placeholder="Cari judul berita, topik, atau kata kunci..."
                        class="w-full rounded-xl border border-line bg-subtle py-2.5 pr-4 pl-10 text-sm text-ink placeholder:text-muted focus:border-primary focus:bg-white focus:outline-hidden"
                    >
                </div>

                {{-- Category Filter --}}
                <div class="flex flex-wrap items-center gap-2">
                    <a
                        href="{{ route('berita.index', array_filter(['q' => $q])) }}"
                        class="rounded-lg px-3.5 py-2 text-xs font-medium transition-colors sm:text-sm {{ empty($kategori) ? 'bg-primary text-white' : 'border border-line bg-white text-ink hover:bg-subtle' }}"
                    >
                        Semua
                    </a>
                    @foreach ($categories as $cat)
                        <a
                            href="{{ route('berita.index', array_filter(['q' => $q, 'kategori' => $cat])) }}"
                            class="rounded-lg px-3.5 py-2 text-xs font-medium transition-colors sm:text-sm {{ $kategori === $cat ? 'bg-primary text-white' : 'border border-line bg-white text-ink hover:bg-subtle' }}"
                        >
                            {{ $cat }}
                        </a>
                    @endforeach
                </div>
            </form>
        </section>

        {{-- News Cards Grid --}}
        <section aria-labelledby="daftar-berita-heading">
            <h2 id="daftar-berita-heading" class="sr-only">Daftar Berita Perpustakaan</h2>

            @if ($beritas->isNotEmpty())
                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3 lg:gap-8">
                    @foreach ($beritas as $item)
                        <article class="group flex h-full flex-col overflow-hidden rounded-2xl border border-line bg-white shadow-card transition-all duration-200 hover:-translate-y-1 hover:border-primary/40 hover:shadow-raised">
                            <div class="relative aspect-video overflow-hidden bg-gradient-to-br from-primary/10 via-track to-primary/5">
                                @if ($item->image)
                                    <img
                                        src="{{ filter_var($item->image, FILTER_VALIDATE_URL) ? $item->image : asset($item->image) }}"
                                        alt="{{ $item->title }}"
                                        class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
                                        loading="lazy"
                                    >
                                @else
                                    <div class="flex h-full w-full items-center justify-center p-6 text-muted">
                                        <svg class="size-12 text-primary/30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/>
                                            <path d="M6 6h10"/>
                                            <path d="M6 10h10"/>
                                            <path d="M6 14h6"/>
                                        </svg>
                                    </div>
                                @endif
                                <span class="absolute top-4 left-4 inline-flex items-center gap-1.5 rounded-full bg-white/95 px-3 py-1 text-xs font-semibold text-primary shadow-xs backdrop-blur-xs">
                                    <span class="size-1.5 rounded-full bg-primary" aria-hidden="true"></span>
                                    {{ $item->category }}
                                </span>
                            </div>

                            <div class="flex flex-1 flex-col p-6">
                                <div class="flex items-center gap-3 text-xs text-muted">
                                    <time datetime="{{ $item->published_at->toDateString() }}">
                                        {{ $item->published_at->translatedFormat('d F Y') }}
                                    </time>
                                    <span>&bull;</span>
                                    <span>{{ $item->views_count }} kali dibaca</span>
                                </div>

                                <h3 class="mt-3 line-clamp-2 text-lg font-semibold text-ink transition-colors group-hover:text-primary lg:text-xl">
                                    <a href="{{ route('berita.show', $item->slug) }}" class="focus:outline-hidden">
                                        {{ $item->title }}
                                    </a>
                                </h3>

                                <p class="mt-2.5 line-clamp-3 text-sm leading-relaxed text-ink-secondary">
                                    {{ $item->excerpt }}
                                </p>

                                <div class="mt-auto pt-6 flex items-center justify-between border-t border-line text-sm font-semibold text-primary">
                                    <span>Baca Berita</span>
                                    <svg class="size-4 transition-transform duration-200 group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M5 12h14"></path>
                                        <path d="m12 5 7 7-7 7"></path>
                                    </svg>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-8 flex justify-center">
                    {{ $beritas->links() }}
                </div>
            @else
                <div class="rounded-2xl border border-line bg-subtle p-12 text-center">
                    <div class="mx-auto flex size-14 items-center justify-center rounded-full bg-primary/10 text-primary">
                        <svg class="size-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </div>
                    <h3 class="mt-4 text-lg font-medium text-ink">Tidak ada berita yang ditemukan</h3>
                    <p class="mt-1 text-sm text-muted">
                        @if (!empty($q) || !empty($kategori))
                            Coba ubah kata kunci pencarian atau bersihkan filter kategori Anda.
                        @else
                            Belum ada berita yang dipublikasikan saat ini.
                        @endif
                    </p>
                    @if (!empty($q) || !empty($kategori))
                        <div class="mt-5">
                            <a href="{{ route('berita.index') }}" class="inline-flex items-center rounded-xl bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-primary-dark transition-colors">
                                Reset Pencarian
                            </a>
                        </div>
                    @endif
                </div>
            @endif
        </section>
    </div>
@endsection
