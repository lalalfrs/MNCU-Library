@extends('layouts.app')

@section('title', $acara->title)
@section('description', $acara->excerpt)

@section('content')
    <div class="mx-auto flex max-w-[1570px] flex-col gap-8 px-3 py-6 lg:gap-12 lg:px-6 lg:py-10">
        
        {{-- Breadcrumbs --}}
        <nav aria-label="Breadcrumb" class="flex items-center text-sm text-muted">
            <ol class="flex flex-wrap items-center gap-2">
                <li>
                    <a href="{{ route('beranda') }}" class="hover:text-primary transition-colors">Beranda</a>
                </li>
                <li aria-hidden="true" class="text-muted/60">&rsaquo;</li>
                <li>
                    <a href="{{ route('acara.index') }}" class="hover:text-primary transition-colors">Acara & Agenda</a>
                </li>
                <li aria-hidden="true" class="text-muted/60">&rsaquo;</li>
                <li class="font-medium text-ink truncate max-w-[240px] sm:max-w-md" aria-current="page">{{ $acara->title }}</li>
            </ol>
        </nav>

        {{-- Main Event Grid --}}
        <div class="grid gap-8 lg:grid-cols-[1fr_380px] lg:gap-12">
            
            {{-- Left Column: Event Article --}}
            <div class="flex flex-col gap-8">
                <article class="rounded-2xl border border-line bg-white p-6 shadow-card lg:p-10">
                    <header class="border-b border-line pb-6">
                        <div class="flex flex-wrap items-center gap-2.5">
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-primary/10 px-3 py-1 text-xs font-semibold text-primary">
                                {{ $acara->category }}
                            </span>
                            @if ($acara->event_date->isPast() && !$acara->event_date->isToday())
                                <span class="rounded-full bg-muted/20 px-3 py-1 text-xs font-semibold text-muted">
                                    Acara Telah Selesai
                                </span>
                            @else
                                <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 border border-emerald-200">
                                    Pendaftaran Dibuka
                                </span>
                            @endif
                        </div>

                        <h1 class="mt-4 text-2xl font-bold leading-tight text-ink sm:text-3xl lg:text-4xl">
                            {{ $acara->title }}
                        </h1>

                        <p class="mt-3 text-base leading-relaxed text-ink-secondary sm:text-lg">
                            {{ $acara->excerpt }}
                        </p>
                    </header>

                    {{-- Poster / Foto Acara --}}
                    @if ($acara->image)
                        <div class="my-6 overflow-hidden rounded-2xl border border-line bg-subtle shadow-xs">
                            <img
                                src="{{ $acara->image }}"
                                alt="Poster {{ $acara->title }}"
                                class="w-full max-h-[520px] object-contain sm:object-cover mx-auto"
                                loading="lazy"
                            >
                        </div>
                    @endif

                    {{-- Speaker Highlight Card --}}
                    @if ($acara->speaker)
                        <div class="my-6 flex items-center gap-4 rounded-xl border border-line bg-subtle p-5">
                            <div class="flex size-14 shrink-0 items-center justify-center rounded-full bg-primary text-lg font-bold text-white shadow-xs">
                                <svg class="size-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            </div>
                            <div>
                                <span class="text-xs font-semibold uppercase tracking-wider text-muted">Narasumber / Instruktur</span>
                                <p class="text-base font-bold text-ink sm:text-lg">{{ $acara->speaker }}</p>
                            </div>
                        </div>
                    @endif

                    {{-- Event Full Description --}}
                    <div class="space-y-4 text-base leading-relaxed text-ink-secondary sm:text-lg [&_h3]:mt-6 [&_h3]:text-xl [&_h3]:font-semibold [&_h3]:text-ink [&_p]:leading-relaxed [&_ul]:list-disc [&_ul]:pl-6 [&_ul]:space-y-1 [&_ol]:list-decimal [&_ol]:pl-6 [&_ol]:space-y-1 [&_strong]:text-ink">
                        {!! $acara->description !!}
                    </div>

                    {{-- Share Section --}}
                    <footer class="mt-10 border-t border-line pt-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-semibold uppercase tracking-wider text-muted">Bagikan Acara:</span>
                            <a
                                href="https://api.whatsapp.com/send?text={{ urlencode($acara->title . ' - ' . request()->url()) }}"
                                target="_blank"
                                rel="noopener"
                                class="inline-flex size-9 items-center justify-center rounded-lg border border-line bg-white text-ink transition-colors hover:border-emerald-500 hover:text-emerald-600"
                                aria-label="Bagikan ke WhatsApp"
                            >
                                <svg class="size-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.007c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86.173.086.275.072.376-.044.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.043.072.043.419-.101.824z"/></svg>
                            </a>
                            <button
                                type="button"
                                onclick="navigator.clipboard.writeText(window.location.href); alert('Tautan acara berhasil disalin!');"
                                class="inline-flex size-9 items-center justify-center rounded-lg border border-line bg-white text-ink transition-colors hover:border-primary hover:text-primary"
                                aria-label="Salin Tautan Acara"
                            >
                                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                            </button>
                        </div>

                        <a href="{{ route('acara.index') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-primary hover:underline">
                            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                            Lihat Acara Lainnya
                        </a>
                    </footer>
                </article>
            </div>

            {{-- Right Column: Event Details Card (Sticky) --}}
            <aside aria-label="Informasi Pelaksanaan" class="flex flex-col gap-6">
                <div class="sticky top-24 rounded-2xl border border-line bg-white p-6 shadow-card">
                    <h2 class="text-lg font-bold text-ink">Informasi Jadwal</h2>
                    
                    <div class="mt-6 flex items-center gap-4 rounded-xl bg-subtle p-4 border border-line">
                        <div class="flex size-14 shrink-0 flex-col items-center justify-center rounded-lg bg-primary text-white">
                            <span class="text-xl font-bold leading-none">{{ $acara->event_date->format('d') }}</span>
                            <span class="mt-1 text-[11px] font-semibold uppercase">{{ $acara->event_date->translatedFormat('M Y') }}</span>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-ink">{{ $acara->event_date->translatedFormat('l, d F Y') }}</p>
                            <p class="text-xs text-muted">{{ $acara->start_time }} &ndash; {{ $acara->end_time }} WIB</p>
                        </div>
                    </div>

                    <div class="mt-6 space-y-4 text-sm">
                        <div class="flex items-start gap-3">
                            <div class="flex size-8 shrink-0 items-center justify-center rounded-lg bg-primary/10 text-primary">
                                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                            </div>
                            <div>
                                <span class="block text-xs text-muted">Lokasi Pelaksanaan</span>
                                <span class="font-medium text-ink">{{ $acara->location }}</span>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="flex size-8 shrink-0 items-center justify-center rounded-lg bg-primary/10 text-primary">
                                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                            </div>
                            <div>
                                <span class="block text-xs text-muted">Kapasitas Kursi</span>
                                <span class="font-medium text-ink">
                                    {{ $acara->quota ? $acara->quota . ' Peserta' : 'Terbuka untuk Sivitas MNC' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- CTA Register --}}
                    <div class="mt-8">
                        @if ($acara->event_date->isPast() && !$acara->event_date->isToday())
                            <button disabled class="w-full cursor-not-allowed rounded-xl bg-muted/20 py-3.5 text-center text-sm font-semibold text-muted">
                                Pendaftaran Ditutup (Selesai)
                            </button>
                        @elseif ($acara->registration_link)
                            <a
                                href="{{ $acara->registration_link }}"
                                target="_blank"
                                rel="noopener"
                                class="block w-full rounded-xl bg-primary py-3.5 text-center text-sm font-semibold text-white shadow-sm transition-colors hover:bg-primary-dark"
                            >
                                Daftar Sekarang &rarr;
                            </a>
                        @else
                            <button
                                type="button"
                                onclick="alert('Pendaftaran langsung di tempat (Walk-in) di lokasi acara.');"
                                class="block w-full rounded-xl bg-primary py-3.5 text-center text-sm font-semibold text-white shadow-sm transition-colors hover:bg-primary-dark"
                            >
                                Registrasi di Lokasi
                            </button>
                        @endif
                        <p class="mt-2 text-center text-[11px] text-muted">
                            Gratis untuk seluruh mahasiswa dan dosen MNC University.
                        </p>
                    </div>
                </div>

                {{-- Other upcoming events mini-list --}}
                @if ($otherEvents->isNotEmpty())
                    <div class="rounded-2xl border border-line bg-white p-6 shadow-card">
                        <h3 class="font-bold text-ink">Acara Mendatang Lainnya</h3>
                        <div class="mt-4 flex flex-col divide-y divide-line">
                            @foreach ($otherEvents as $item)
                                <article class="py-3 first:pt-0 last:pb-0">
                                    <span class="text-xs text-muted">{{ $item->event_date->translatedFormat('d M Y') }}</span>
                                    <h4 class="mt-1 font-medium text-ink hover:text-primary transition-colors">
                                        <a href="{{ route('acara.show', $item->slug) }}">
                                            {{ $item->title }}
                                        </a>
                                    </h4>
                                </article>
                            @endforeach
                        </div>
                    </div>
                @endif
            </aside>
        </div>
    </div>
@endsection
