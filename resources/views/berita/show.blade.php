@extends('layouts.app')

@section('title', $berita->title)
@section('description', $berita->excerpt)

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
                    <a href="{{ route('berita.index') }}" class="hover:text-primary transition-colors">Berita</a>
                </li>
                <li aria-hidden="true" class="text-muted/60">&rsaquo;</li>
                <li class="font-medium text-ink truncate max-w-[240px] sm:max-w-md" aria-current="page">{{ $berita->title }}</li>
            </ol>
        </nav>

        {{-- Main Article Container --}}
        <div class="grid gap-8 lg:grid-cols-[1fr_360px] lg:gap-12">
            
            {{-- Article Content --}}
            <article class="flex flex-col rounded-2xl border border-line bg-white p-6 shadow-card lg:p-10">
                <header class="border-b border-line pb-6">
                    <div class="flex flex-wrap items-center gap-2.5">
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-primary/10 px-3 py-1 text-xs font-semibold text-primary">
                            <span class="size-1.5 rounded-full bg-primary" aria-hidden="true"></span>
                            {{ $berita->category }}
                        </span>
                        <time datetime="{{ $berita->published_at->toDateString() }}" class="text-xs text-muted sm:text-sm">
                            {{ $berita->published_at->translatedFormat('l, d F Y') }}
                        </time>
                        <span class="text-muted">&bull;</span>
                        <span class="text-xs text-muted sm:text-sm">{{ $berita->views_count }} kali dilihat</span>
                    </div>

                    <h1 class="mt-4 text-2xl font-bold leading-tight text-ink sm:text-3xl lg:text-4xl">
                        {{ $berita->title }}
                    </h1>

                    <div class="mt-4 flex items-center gap-3 text-sm text-ink-secondary">
                        <div class="flex size-9 items-center justify-center rounded-full bg-primary text-xs font-semibold text-white">
                            PU
                        </div>
                        <div>
                            <p class="font-medium text-ink">{{ $berita->author }}</p>
                            <p class="text-xs text-muted">Perpustakaan MNC University</p>
                        </div>
                    </div>
                </header>

                {{-- Featured Visual Banner --}}
                @if ($berita->image)
                    <div class="my-8 aspect-video sm:aspect-21/9 w-full overflow-hidden rounded-2xl border border-line shadow-sm">
                        <img
                            src="{{ filter_var($berita->image, FILTER_VALIDATE_URL) ? $berita->image : asset($berita->image) }}"
                            alt="{{ $berita->title }}"
                            class="h-full w-full object-cover"
                        >
                    </div>
                @else
                    <div class="my-8 flex aspect-video sm:aspect-21/9 w-full items-center justify-center rounded-2xl bg-gradient-to-br from-primary/15 via-track to-primary/5 p-8 text-primary">
                        <div class="flex flex-col items-center gap-2 text-center">
                            <svg class="size-16 text-primary/40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/>
                                <path d="M6 6h10"/>
                                <path d="M6 10h10"/>
                                <path d="M6 14h6"/>
                            </svg>
                            <span class="text-sm font-medium text-muted">Publikasi Resmi Perpustakaan MNC University</span>
                        </div>
                    </div>
                @endif

                {{-- Excerpt Callout --}}
                <div class="mb-8 rounded-xl border-l-4 border-primary bg-subtle p-4 text-base italic text-ink-secondary sm:text-lg">
                    &ldquo;{{ $berita->excerpt }}&rdquo;
                </div>

                {{-- Article Body --}}
                <div class="space-y-4 text-base leading-relaxed text-ink-secondary sm:text-lg [&_h3]:mt-6 [&_h3]:text-xl [&_h3]:font-semibold [&_h3]:text-ink [&_p]:leading-relaxed [&_ul]:list-disc [&_ul]:pl-6 [&_ul]:space-y-1 [&_ol]:list-decimal [&_ol]:pl-6 [&_ol]:space-y-1 [&_strong]:text-ink">
                    {!! $berita->content !!}
                </div>

                {{-- Article Footer: Share & Tags --}}
                <footer class="mt-10 border-t border-line pt-6">
                    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-semibold uppercase tracking-wider text-muted">Bagikan:</span>
                            <a
                                href="https://api.whatsapp.com/send?text={{ urlencode($berita->title . ' - ' . request()->url()) }}"
                                target="_blank"
                                rel="noopener"
                                class="inline-flex size-9 items-center justify-center rounded-lg border border-line bg-white text-ink transition-colors hover:border-emerald-500 hover:text-emerald-600"
                                aria-label="Bagikan ke WhatsApp"
                            >
                                <svg class="size-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.007c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86.173.086.275.072.376-.044.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.043.072.043.419-.101.824z"/></svg>
                            </a>
                            <button
                                type="button"
                                onclick="navigator.clipboard.writeText(window.location.href); alert('Tautan berita berhasil disalin!');"
                                class="inline-flex size-9 items-center justify-center rounded-lg border border-line bg-white text-ink transition-colors hover:border-primary hover:text-primary"
                                aria-label="Salin Tautan"
                            >
                                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                            </button>
                        </div>

                        <a href="{{ route('berita.index') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-primary hover:underline">
                            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                            Kembali ke Daftar Berita
                        </a>
                    </div>
                </footer>
            </article>

            {{-- Sidebar Berita Terkait --}}
            <aside aria-label="Berita Lainnya" class="flex flex-col gap-6">
                <div class="rounded-2xl border border-line bg-white p-6 shadow-card">
                    <h2 class="text-lg font-semibold text-ink">Berita Lainnya</h2>
                    <p class="mt-1 text-xs text-muted">Kabar terbaru dari perpustakaan.</p>

                    <div class="mt-5 flex flex-col divide-y divide-line">
                        @foreach ($relatedNews as $item)
                            <article class="py-4 first:pt-0 last:pb-0">
                                <span class="text-xs text-muted">{{ $item->published_at->translatedFormat('d M Y') }}</span>
                                <h3 class="mt-1 font-medium text-ink transition-colors hover:text-primary">
                                    <a href="{{ route('berita.show', $item->slug) }}">
                                        {{ $item->title }}
                                    </a>
                                </h3>
                            </article>
                        @endforeach
                    </div>

                    <a href="{{ route('berita.index') }}" class="mt-5 block w-full rounded-xl border border-line bg-subtle py-2.5 text-center text-xs font-semibold text-primary hover:bg-primary/5 transition-colors">
                        Lihat Semua Berita
                    </a>
                </div>

                {{-- Help Card --}}
                <div class="rounded-2xl border border-line bg-primary/5 p-6 text-ink">
                    <h3 class="font-semibold text-primary">Butuh Informasi Tambahan?</h3>
                    <p class="mt-2 text-xs leading-relaxed text-ink-secondary">
                        Hubungi tim pustakawan MNC University bila memiliki pertanyaan seputar artikel atau layanan yang diterbitkan.
                    </p>
                    <a href="mailto:pustaka@mncuniversity.ac.id" class="mt-4 inline-flex items-center gap-1 text-xs font-semibold text-primary hover:underline">
                        Hubungi via Email &rarr;
                    </a>
                </div>
            </aside>
        </div>
    </div>
@endsection
