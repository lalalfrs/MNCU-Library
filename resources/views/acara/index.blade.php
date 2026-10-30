@extends('layouts.app')

@section('title', 'Acara & Agenda Perpustakaan')
@section('description', 'Agenda kegiatan, workshop literasi, bedah buku, dan seminar akademik Perpustakaan MNC University.')

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
                    <li class="font-medium text-ink" aria-current="page">Acara Perpustakaan</li>
                </ol>
            </nav>

            <div class="flex flex-col justify-between gap-4 md:flex-row md:items-end">
                <div>
                    <h1 class="text-2xl font-semibold text-primary sm:text-3xl lg:text-4xl">
                        Acara & Agenda Perpustakaan
                    </h1>
                    <p class="mt-2 text-sm text-ink-secondary sm:text-base lg:text-lg">
                        Tingkatkan kecakapan akademik lewat kelas literasi, bedah buku, dan workshop riset gratis untuk sivitas MNC University.
                    </p>
                </div>
            </div>
        </div>

        {{-- Search & Filter Section --}}
        <section aria-label="Filter Acara" class="flex flex-col gap-4 rounded-2xl border border-line bg-white p-4 shadow-card lg:p-6">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                {{-- Status Tabs: Mendatang / Selesai / Semua --}}
                <div class="flex items-center gap-1.5 border-b border-line pb-3 sm:border-b-0 sm:pb-0">
                    <a
                        href="{{ route('acara.index', array_filter(['waktu' => 'mendatang', 'q' => $q, 'kategori' => $kategori])) }}"
                        class="rounded-xl px-4 py-2 text-xs font-semibold sm:text-sm transition-colors {{ $waktu === 'mendatang' ? 'bg-primary text-white shadow-xs' : 'text-ink-secondary hover:bg-subtle' }}"
                    >
                        Acara Mendatang
                    </a>
                    <a
                        href="{{ route('acara.index', array_filter(['waktu' => 'selesai', 'q' => $q, 'kategori' => $kategori])) }}"
                        class="rounded-xl px-4 py-2 text-xs font-semibold sm:text-sm transition-colors {{ $waktu === 'selesai' ? 'bg-primary text-white shadow-xs' : 'text-ink-secondary hover:bg-subtle' }}"
                    >
                        Telah Selesai
                    </a>
                    <a
                        href="{{ route('acara.index', array_filter(['waktu' => 'semua', 'q' => $q, 'kategori' => $kategori])) }}"
                        class="rounded-xl px-4 py-2 text-xs font-semibold sm:text-sm transition-colors {{ $waktu === 'semua' ? 'bg-primary text-white shadow-xs' : 'text-ink-secondary hover:bg-subtle' }}"
                    >
                        Semua Acara
                    </a>
                </div>

                {{-- Search Bar --}}
                <form method="GET" action="{{ route('acara.index') }}" class="relative w-full md:w-80">
                    <input type="hidden" name="waktu" value="{{ $waktu }}">
                    @if (!empty($kategori))
                        <input type="hidden" name="kategori" value="{{ $kategori }}">
                    @endif
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted">
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </span>
                    <input
                        type="search"
                        name="q"
                        value="{{ $q ?? '' }}"
                        placeholder="Cari nama acara, narasumber..."
                        class="w-full rounded-xl border border-line bg-subtle py-2 pr-4 pl-9 text-sm text-ink placeholder:text-muted focus:border-primary focus:bg-white focus:outline-hidden"
                    >
                </form>
            </div>

            {{-- Categories Pills --}}
            @if ($categories->isNotEmpty())
                <div class="flex flex-wrap items-center gap-2 pt-2 border-t border-line">
                    <span class="text-xs font-medium text-muted mr-1">Kategori:</span>
                    <a
                        href="{{ route('acara.index', array_filter(['waktu' => $waktu, 'q' => $q])) }}"
                        class="rounded-lg px-3 py-1 text-xs font-medium transition-colors {{ empty($kategori) ? 'bg-ink text-white' : 'border border-line bg-white text-ink hover:bg-subtle' }}"
                    >
                        Semua Kategori
                    </a>
                    @foreach ($categories as $cat)
                        <a
                            href="{{ route('acara.index', array_filter(['waktu' => $waktu, 'q' => $q, 'kategori' => $cat])) }}"
                            class="rounded-lg px-3 py-1 text-xs font-medium transition-colors {{ $kategori === $cat ? 'bg-ink text-white' : 'border border-line bg-white text-ink hover:bg-subtle' }}"
                        >
                            {{ $cat }}
                        </a>
                    @endforeach
                </div>
            @endif
        </section>

        {{-- Events Cards Grid --}}
        <section aria-labelledby="daftar-acara-heading">
            <h2 id="daftar-acara-heading" class="sr-only">Daftar Kegiatan Perpustakaan</h2>

            @if ($acaras->isNotEmpty())
                <div class="grid gap-6 md:grid-cols-2 lg:gap-8">
                    @foreach ($acaras as $item)
                        @php
                            $isPast = $item->event_date->isPast() && !$item->event_date->isToday();
                        @endphp
                        <article class="group flex flex-col justify-between overflow-hidden rounded-2xl border border-line bg-white shadow-card transition-all duration-200 hover:-translate-y-1 hover:border-primary/40 hover:shadow-raised">
                            @if ($item->image)
                                <div class="relative h-48 w-full overflow-hidden bg-subtle border-b border-line">
                                    <img
                                        src="{{ $item->image }}"
                                        alt="{{ $item->title }}"
                                        class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
                                        loading="lazy"
                                        onerror="this.onerror=null; this.src='{{ asset('images/acara/acara-01.jpg') }}';"
                                    >
                                    <div class="absolute top-3 left-3 flex size-14 flex-col items-center justify-center rounded-xl {{ $isPast ? 'bg-slate-900/80 text-white backdrop-blur-xs' : 'bg-primary text-white shadow-md' }}">
                                        <span class="text-lg font-bold leading-none">{{ $item->event_date->format('d') }}</span>
                                        <span class="mt-0.5 text-[10px] font-medium uppercase">{{ $item->event_date->translatedFormat('M') }}</span>
                                    </div>
                                    <div class="absolute top-3 right-3">
                                        @if ($isPast)
                                            <span class="rounded-full bg-slate-900/80 backdrop-blur-xs px-2.5 py-1 text-xs font-medium text-white">
                                                Selesai
                                            </span>
                                        @else
                                            <span class="rounded-full bg-emerald-600 px-2.5 py-1 text-xs font-semibold text-white shadow-xs">
                                                Terbuka
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endif
                            <div class="p-6">
                                <div class="flex items-start justify-between gap-4">
                                    @if (!$item->image)
                                        {{-- Calendar Badge when no poster image --}}
                                        <div class="flex size-16 shrink-0 flex-col items-center justify-center rounded-xl {{ $isPast ? 'bg-muted/20 text-muted' : 'bg-primary text-white shadow-xs' }}">
                                            <span class="text-xl font-bold leading-none">{{ $item->event_date->format('d') }}</span>
                                            <span class="mt-1 text-xs font-medium uppercase">{{ $item->event_date->translatedFormat('M Y') }}</span>
                                        </div>
                                    @endif

                                    <div class="flex flex-1 flex-col">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="rounded-full bg-subtle px-2.5 py-0.5 text-xs font-medium text-ink-secondary border border-line">
                                                {{ $item->category }}
                                            </span>
                                            @if (!$item->image)
                                                @if ($isPast)
                                                    <span class="rounded-full bg-muted/15 px-2.5 py-0.5 text-xs font-medium text-muted">
                                                        Selesai
                                                    </span>
                                                @else
                                                    <span class="rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-semibold text-emerald-700 border border-emerald-200">
                                                        Terbuka
                                                    </span>
                                                @endif
                                            @endif
                                        </div>

                                        <h3 class="mt-2 text-lg font-bold text-ink transition-colors group-hover:text-primary lg:text-xl">
                                            <a href="{{ route('acara.show', $item->slug) }}" class="focus:outline-hidden">
                                                {{ $item->title }}
                                            </a>
                                        </h3>
                                    </div>
                                </div>

                                <p class="mt-4 line-clamp-2 text-sm leading-relaxed text-ink-secondary">
                                    {{ $item->excerpt }}
                                </p>

                                {{-- Event Details Meta --}}
                                <div class="mt-4 space-y-2 rounded-xl bg-subtle p-3.5 text-xs text-ink-secondary">
                                    <div class="flex items-center gap-2">
                                        <svg class="size-4 shrink-0 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                        <span>{{ $item->start_time }} &ndash; {{ $item->end_time }} WIB</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg class="size-4 shrink-0 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                                        <span class="truncate">{{ $item->location }}</span>
                                    </div>
                                    @if ($item->speaker)
                                        <div class="flex items-center gap-2">
                                            <svg class="size-4 shrink-0 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                            <span class="truncate font-medium text-ink">{{ $item->speaker }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="mt-6 flex items-center justify-between border-t border-line pt-4">
                                <span class="text-xs text-muted">
                                    @if ($item->quota)
                                        Kuota: {{ $item->quota }} Peserta
                                    @else
                                        Terbuka untuk Umum
                                    @endif
                                </span>
                                
                                <a href="{{ route('acara.show', $item->slug) }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-primary group-hover:underline">
                                    Lihat Detail Acara
                                    <svg class="size-4 transition-transform duration-200 group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 6 6 6-6 6"/></svg>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-8 flex justify-center">
                    {{ $acaras->links() }}
                </div>
            @else
                <div class="rounded-2xl border border-line bg-subtle p-12 text-center">
                    <div class="mx-auto flex size-14 items-center justify-center rounded-full bg-primary/10 text-primary">
                        <svg class="size-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect width="18" height="18" x="3" y="4" rx="2" ry="2"/>
                            <line x1="16" x2="16" y1="2" y2="6"/>
                            <line x1="8" x2="8" y1="2" y2="6"/>
                            <line x1="3" x2="21" y1="10" y2="10"/>
                        </svg>
                    </div>
                    <h3 class="mt-4 text-lg font-medium text-ink">Tidak ada acara yang sesuai</h3>
                    <p class="mt-1 text-sm text-muted">
                        Jadwal kegiatan atau kata kunci yang dicari belum tersedia.
                    </p>
                    <div class="mt-5">
                        <a href="{{ route('acara.index') }}" class="inline-flex items-center rounded-xl bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-primary-dark transition-colors">
                            Lihat Semua Agenda
                        </a>
                    </div>
                </div>
            @endif
        </section>
    </div>
@endsection
