@extends('layouts.app')

@section('title', 'Acara & Agenda Perpustakaan')
@section('description', 'Agenda kegiatan, workshop literasi, bedah buku, dan seminar akademik Perpustakaan MNC University.')

@section('content')
    <div class="mx-auto flex max-w-[1570px] flex-col gap-6 px-4 py-6 sm:px-6 lg:gap-8 lg:px-8 lg:py-10">
        
        {{-- Breadcrumbs & Header --}}
        <div class="flex flex-col gap-3">
            <nav aria-label="Breadcrumb" class="flex items-center text-xs sm:text-sm text-muted">
                <ol class="flex items-center gap-2">
                    <li>
                        <a href="{{ route('beranda') }}" class="hover:text-primary transition-colors">Beranda</a>
                    </li>
                    <li aria-hidden="true" class="text-muted/60">&rsaquo;</li>
                    <li class="font-medium text-ink" aria-current="page">Acara Perpustakaan</li>
                </ol>
            </nav>

            <div class="flex flex-col justify-between gap-2 md:flex-row md:items-end">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-primary sm:text-3xl lg:text-4xl">
                        Acara & Agenda Perpustakaan
                    </h1>
                    <p class="mt-1.5 text-sm text-ink-secondary sm:text-base max-w-3xl leading-relaxed">
                        Tingkatkan kecakapan akademik lewat kelas literasi, bedah buku, dan workshop riset gratis untuk sivitas MNC University.
                    </p>
                </div>
            </div>
        </div>

        {{-- Search & Filter Section --}}
        <section aria-label="Filter Acara" class="flex flex-col gap-4 rounded-2xl border border-line bg-white p-4 sm:p-5 shadow-card">
            {{-- Status Tabs & Search Bar --}}
            <div class="flex flex-col gap-3 sm:gap-4 md:flex-row md:items-center md:justify-between">
                {{-- Status Tabs: Mendatang / Selesai / Semua --}}
                <div class="inline-flex rounded-xl bg-subtle p-1 border border-line/60">
                    <a
                        href="{{ route('acara.index', array_filter(['waktu' => 'mendatang', 'q' => $q, 'kategori' => $kategori])) }}"
                        class="rounded-lg px-3.5 py-1.5 text-xs font-semibold sm:text-sm transition-all {{ $waktu === 'mendatang' ? 'bg-primary text-white shadow-xs' : 'text-ink-secondary hover:text-ink hover:bg-white/80' }}"
                    >
                        Acara Mendatang
                    </a>
                    <a
                        href="{{ route('acara.index', array_filter(['waktu' => 'selesai', 'q' => $q, 'kategori' => $kategori])) }}"
                        class="rounded-lg px-3.5 py-1.5 text-xs font-semibold sm:text-sm transition-all {{ $waktu === 'selesai' ? 'bg-primary text-white shadow-xs' : 'text-ink-secondary hover:text-ink hover:bg-white/80' }}"
                    >
                        Telah Selesai
                    </a>
                    <a
                        href="{{ route('acara.index', array_filter(['waktu' => 'semua', 'q' => $q, 'kategori' => $kategori])) }}"
                        class="rounded-lg px-3.5 py-1.5 text-xs font-semibold sm:text-sm transition-all {{ $waktu === 'semua' ? 'bg-primary text-white shadow-xs' : 'text-ink-secondary hover:text-ink hover:bg-white/80' }}"
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
                        class="w-full rounded-xl border border-line bg-subtle py-2 pr-9 pl-9 text-xs sm:text-sm text-ink placeholder:text-muted focus:border-primary focus:bg-white focus:outline-hidden transition-all"
                    >
                    @if (!empty($q))
                        <a
                            href="{{ route('acara.index', array_filter(['waktu' => $waktu, 'kategori' => $kategori])) }}"
                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-muted hover:text-ink"
                            title="Hapus pencarian"
                        >
                            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </a>
                    @endif
                </form>
            </div>

            {{-- Categories Pills & Counter --}}
            <div class="flex flex-col gap-3 pt-3 border-t border-line/70 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex flex-wrap items-center gap-1.5">
                    <span class="text-xs font-medium text-muted mr-1">Kategori:</span>
                    <a
                        href="{{ route('acara.index', array_filter(['waktu' => $waktu, 'q' => $q])) }}"
                        class="rounded-lg px-3 py-1 text-xs font-semibold transition-all {{ empty($kategori) ? 'bg-primary text-white shadow-xs' : 'border border-line bg-white text-ink-secondary hover:bg-subtle hover:text-ink' }}"
                    >
                        Semua Kategori
                    </a>
                    @foreach ($categories as $cat)
                        <a
                            href="{{ route('acara.index', array_filter(['waktu' => $waktu, 'q' => $q, 'kategori' => $cat])) }}"
                            class="rounded-lg px-3 py-1 text-xs font-semibold transition-all {{ $kategori === $cat ? 'bg-primary text-white shadow-xs' : 'border border-line bg-white text-ink-secondary hover:bg-subtle hover:text-ink' }}"
                        >
                            {{ $cat }}
                        </a>
                    @endforeach
                </div>

                <div class="text-xs font-medium text-muted shrink-0">
                    Menampilkan <span class="font-semibold text-ink">{{ $acaras->total() }}</span> kegiatan
                </div>
            </div>
        </section>

        {{-- Events Cards Grid --}}
        <section aria-labelledby="daftar-acara-heading">
            <h2 id="daftar-acara-heading" class="sr-only">Daftar Kegiatan Perpustakaan</h2>

            @if ($acaras->isNotEmpty())
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($acaras as $item)
                        @php
                            $isPast = $item->event_date->isPast() && !$item->event_date->isToday();
                        @endphp
                        <article class="group flex h-full flex-col overflow-hidden rounded-2xl border border-slate-200/90 bg-gradient-to-br from-white via-slate-50/80 to-blue-50/40 shadow-card transition-all duration-300 hover:-translate-y-1 hover:border-primary/50 hover:shadow-raised">
                            {{-- Image Cover Container --}}
                            <div class="relative aspect-video w-full overflow-hidden bg-gradient-to-br from-primary/10 via-track to-primary/5 border-b border-line/60">
                                @if ($item->image)
                                    <img
                                        src="{{ $item->image }}"
                                        alt="{{ $item->title }}"
                                        class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
                                        loading="lazy"
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

                                {{-- Category Badge Top-Left --}}
                                <span class="absolute top-3 left-3 inline-flex items-center gap-1.5 rounded-full bg-white/95 px-3 py-1 text-xs font-semibold text-primary shadow-xs backdrop-blur-xs border border-line/40">
                                    <span class="size-1.5 rounded-full bg-primary" aria-hidden="true"></span>
                                    {{ $item->category }}
                                </span>

                                {{-- Status Badge Top-Right --}}
                                <span class="absolute top-3 right-3 inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold shadow-xs backdrop-blur-xs {{ $isPast ? 'bg-slate-900/85 text-white border border-white/10' : 'bg-emerald-600/95 text-white border border-white/20' }}">
                                    <span class="size-1.5 rounded-full {{ $isPast ? 'bg-slate-400' : 'bg-emerald-200' }}" aria-hidden="true"></span>
                                    {{ $isPast ? 'Selesai' : 'Terbuka' }}
                                </span>
                            </div>

                            {{-- Card Body --}}
                            <div class="flex flex-1 flex-col p-5 bg-transparent">
                                {{-- Date & Time Line --}}
                                <div class="flex flex-wrap items-center gap-1.5 text-xs font-semibold {{ $isPast ? 'text-slate-500' : 'text-primary' }}">
                                    <i class="fa-regular fa-clock text-[11px]" aria-hidden="true"></i>
                                    <span>{{ $item->event_date->translatedFormat('d F Y') }}</span>
                                    <span class="text-slate-300">&middot;</span>
                                    <span>{{ $item->start_time }} &ndash; {{ $item->end_time }} WIB</span>
                                </div>

                                {{-- Title --}}
                                <h3 class="mt-2 line-clamp-2 text-base font-bold text-ink transition-colors group-hover:text-primary lg:text-lg leading-snug">
                                    <a href="{{ route('acara.show', $item->slug) }}" class="focus:outline-hidden hover:underline">
                                        {{ $item->title }}
                                    </a>
                                </h3>

                                {{-- Excerpt --}}
                                <p class="mt-2 line-clamp-2 text-xs sm:text-sm text-slate-600 leading-relaxed">
                                    {{ $item->excerpt }}
                                </p>

                                {{-- Event Details Meta (Location & Speaker) --}}
                                <div class="mt-3.5 space-y-1.5 border-t border-slate-100 pt-3 text-xs text-slate-600">
                                    <div class="flex items-center gap-2 truncate">
                                        <i class="fa-solid fa-location-dot text-[11px] text-rose-500 shrink-0" aria-hidden="true"></i>
                                        <span class="truncate">{{ $item->location }}</span>
                                    </div>
                                    @if ($item->speaker)
                                        <div class="flex items-center gap-2 truncate">
                                            <i class="fa-solid fa-user-tie text-[11px] text-primary shrink-0" aria-hidden="true"></i>
                                            <span class="truncate font-medium text-ink">{{ $item->speaker }}</span>
                                        </div>
                                    @endif
                                </div>

                                {{-- Card Footer --}}
                                <div class="mt-auto pt-4 flex items-center justify-between border-t border-slate-100 text-xs">
                                    <span class="inline-flex items-center gap-1.5 text-[11px] font-medium text-slate-500">
                                        <i class="fa-solid fa-users text-[10px] text-muted" aria-hidden="true"></i>
                                        {{ $item->quota ? 'Kuota: ' . $item->quota . ' Peserta' : 'Terbuka Umum' }}
                                    </span>

                                    <a href="{{ route('acara.show', $item->slug) }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-primary group-hover:underline">
                                        <span>Lihat detail acara</span>
                                        <span class="flex size-6 items-center justify-center rounded-full bg-primary/10 text-primary transition-all duration-200 group-hover:translate-x-1 group-hover:bg-primary group-hover:text-white shadow-xs">
                                            <i class="fa-solid fa-arrow-right text-[10px]" aria-hidden="true"></i>
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if ($acaras->hasPages())
                    <div class="mt-8 flex justify-center">
                        {{ $acaras->links() }}
                    </div>
                @endif
            @else
                <div class="rounded-2xl border border-line bg-subtle p-10 sm:p-14 text-center max-w-xl mx-auto my-6">
                    <div class="mx-auto flex size-14 items-center justify-center rounded-2xl bg-primary/10 text-primary">
                        <i class="fa-solid fa-calendar-xmark text-2xl" aria-hidden="true"></i>
                    </div>
                    <h3 class="mt-4 text-base sm:text-lg font-bold text-ink">
                        @if ($waktu === 'mendatang')
                            Belum Ada Acara Mendatang
                        @elseif ($waktu === 'selesai')
                            Belum Ada Acara yang Selesai
                        @else
                            Tidak Ada Acara yang Sesuai
                        @endif
                    </h3>
                    <p class="mt-1.5 text-xs sm:text-sm text-muted leading-relaxed">
                        @if (!empty($q) || !empty($kategori))
                            Tidak ditemukan kegiatan dengan kata kunci atau kategori yang Anda pilih. Silakan coba atur ulang filter pencarian.
                        @elseif ($waktu === 'mendatang')
                            Jadwal webinar, workshop, dan agenda literasi mendatang akan diumumkan di sini. Anda juga dapat melihat agenda yang telah selesai.
                        @else
                            Jadwal kegiatan atau kata kunci yang dicari belum tersedia saat ini.
                        @endif
                    </p>
                    <div class="mt-6 flex flex-wrap items-center justify-center gap-3">
                        @if (!empty($q) || !empty($kategori))
                            <a href="{{ route('acara.index', ['waktu' => $waktu]) }}" class="inline-flex items-center rounded-xl border border-line bg-white px-4 py-2 text-xs sm:text-sm font-semibold text-ink hover:bg-subtle transition-colors shadow-xs">
                                Reset Filter
                            </a>
                        @endif
                        <a href="{{ route('acara.index', ['waktu' => 'semua']) }}" class="inline-flex items-center rounded-xl bg-primary px-4 py-2 text-xs sm:text-sm font-semibold text-white hover:bg-primary-dark transition-colors shadow-xs">
                            Lihat Semua Agenda
                        </a>
                    </div>
                </div>
            @endif
        </section>
    </div>
@endsection
