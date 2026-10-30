@extends('layouts.app')

@section('title', 'E-Journal & Database Ilmiah')
@section('description', 'Akses basis data jurnal elektronik internasional dan nasional terakreditasi untuk sivitas akademika MNC University. Menemukan referensi riset terpercaya.')

@section('content')
    <div class="mx-auto flex w-full max-w-[1570px] flex-1 flex-col gap-8 px-3 py-6 sm:px-4 lg:px-6 xl:px-8 lg:py-10 responsive-desktop-container">
        {{-- Breadcrumb & Header --}}
        <div class="flex flex-col gap-2">
            <nav aria-label="Breadcrumb" class="flex items-center gap-2 text-xs text-muted sm:text-sm">
                <a href="{{ route('beranda') }}" class="hover:text-primary">Beranda</a>
                <svg class="size-3.5 text-muted/60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
                <span class="font-medium text-ink">E-Journal</span>
            </nav>
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-semibold text-primary sm:text-3xl lg:text-4xl">
                        Portal E-Journal & Database Riset
                    </h1>
                    <p class="mt-1 text-sm text-ink-secondary sm:text-base">
                        Koleksi database jurnal internasional dan nasional untuk mendukung riset serta penulisan karya ilmiah mahasiswa dan dosen.
                    </p>
                </div>
                <div class="rounded-xl border border-[#BFDBFE] bg-[#2563EB]/5 px-4 py-2 text-xs text-[#2563EB]">
                    Database Terintegrasi: <span class="font-bold">{{ count($databases) }} Sumber Daya</span>
                </div>
            </div>
        </div>

        {{-- Guide Banner --}}
        <div class="rounded-2xl border border-line bg-subtle p-5 lg:p-6">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div class="flex items-start gap-3.5">
                    <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-[#2563EB] text-white">
                        <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" x2="12" y1="8" y2="12"/>
                            <line x1="12" x2="12.01" y1="16" y2="16"/>
                        </svg>
                    </span>
                    <div>
                        <h2 class="text-sm font-semibold text-ink sm:text-base">Panduan Akses Jurnal dari Luar Kampus (Remote Access)</h2>
                        <p class="mt-1 text-xs text-ink-secondary leading-relaxed sm:text-sm">
                            Untuk database berbayar nasional seperti Perpusnas e-Resources, gunakan nomor anggota Perpustakaan Nasional Anda. Untuk bantuan aktivasi akun riset, hubungi meja layanan perpustakaan.
                        </p>
                    </div>
                </div>
                <a
                    href="{{ route('chat') }}?q=Bagaimana+akses+e-journal+dari+rumah"
                    class="inline-flex shrink-0 items-center gap-1.5 rounded-xl border border-line bg-white px-4 py-2.5 text-xs font-semibold text-primary shadow-sm transition-colors hover:border-primary/40 hover:bg-white"
                >
                    Tanya Akses Remote
                </a>
            </div>
        </div>

        {{-- Database Cards Grid --}}
        <section aria-labelledby="daftar-database-jurnal">
            <h2 id="daftar-database-jurnal" class="sr-only">Daftar Portal Jurnal Ilmiah</h2>

            <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 2xl:grid-cols-4 lg:gap-6">
                @foreach ($databases as $db)
                    <article class="flex flex-col justify-between rounded-2xl border border-line bg-white p-6 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:border-[#2563EB]/40 hover:shadow-raised">
                        <div>
                            <div class="flex items-center justify-between gap-2">
                                <span class="rounded-lg bg-[#2563EB]/10 px-2.5 py-1 text-xs font-medium text-[#2563EB]">
                                    {{ $db['badge'] }}
                                </span>
                                <span class="text-xs text-muted">Akses Terbuka / Institusi</span>
                            </div>

                            <h3 class="mt-4 text-lg font-semibold text-ink leading-snug">
                                {{ $db['name'] }}
                            </h3>

                            <p class="mt-2 text-xs text-ink-secondary leading-relaxed sm:text-sm">
                                {{ $db['description'] }}
                            </p>

                            <div class="mt-4 rounded-xl bg-subtle p-3 text-xs">
                                <span class="font-medium text-ink">Cakupan Disiplin:</span>
                                <p class="mt-0.5 text-muted leading-relaxed">{{ $db['coverage'] }}</p>
                            </div>
                        </div>

                        <div class="mt-6 border-t border-line pt-4">
                            <a
                                href="{{ $db['url'] }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#2563EB] px-4 py-2.5 text-xs font-semibold text-white shadow-sm transition-colors hover:bg-blue-700"
                            >
                                <span>Kunjungi Portal Jurnal</span>
                                <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
                                    <polyline points="15 3 21 3 21 9"/>
                                    <line x1="10" x2="21" y1="14" y2="3"/>
                                </svg>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    </div>
@endsection
