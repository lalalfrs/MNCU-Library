@extends('layouts.app')

@section('title', 'OJS per Program Studi')
@section('description', 'Portal Open Journal Systems (OJS) per program studi MNC University: publikasi jurnal ilmiah berkala untuk bidang informatika, sistem informasi, desain, manajemen, akuntansi, dan komunikasi.')

@section('content')
    <div class="mx-auto flex w-full max-w-[1570px] flex-1 flex-col gap-8 px-3 py-6 sm:px-4 lg:px-6 xl:px-8 lg:py-10 responsive-desktop-container">
        {{-- Breadcrumb & Header --}}
        <div class="flex flex-col gap-2">
            <nav aria-label="Breadcrumb" class="flex items-center gap-2 text-xs text-muted sm:text-sm">
                <a href="{{ route('beranda') }}" class="hover:text-primary">Beranda</a>
                <svg class="size-3.5 text-muted/60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
                <span class="font-medium text-ink">OJS per Prodi</span>
            </nav>
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-semibold text-primary sm:text-3xl lg:text-4xl">
                        Portal Jurnal OJS per Program Studi
                    </h1>
                    <p class="mt-1 text-sm text-ink-secondary sm:text-base">
                        Sistem pengelolaan dan penerbitan jurnal elektronik berkala ilmiah (Open Journal Systems) MNC University.
                    </p>
                </div>
                <div class="rounded-xl border border-[#A7F3D0] bg-[#059669]/5 px-4 py-2 text-xs text-[#047857]">
                    Total Jurnal Aktif: <span class="font-bold">{{ count($journals) }} Program Studi</span>
                </div>
            </div>
        </div>

        {{-- Info Banner --}}
        <div class="rounded-2xl border border-line bg-subtle p-5 lg:p-6">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-sm font-semibold text-ink sm:text-base">Kirim Naskah Artikel Ilmiah (Call for Papers)</h2>
                    <p class="mt-1 text-xs text-ink-secondary leading-relaxed sm:text-sm">
                        Setiap jurnal menerima naskah hasil penelitian orisinal mahasiswa, dosen, dan peneliti luar. Silakan pilih portal program studi terkait untuk membaca Author Guidelines dan melakukan registrasi naskah secara online.
                    </p>
                </div>
                <div class="shrink-0">
                    <span class="inline-flex items-center gap-2 rounded-xl border border-line bg-white px-3.5 py-2 text-xs font-medium text-ink">
                        <svg class="size-4 text-[#047857]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        Peer-Reviewed & Open Access
                    </span>
                </div>
            </div>
        </div>

        {{-- Journals Grid --}}
        <section aria-labelledby="daftar-jurnal-ojs">
            <h2 id="daftar-jurnal-ojs" class="sr-only">Daftar Jurnal Program Studi</h2>

            <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 2xl:grid-cols-4 lg:gap-6">
                @foreach ($journals as $journal)
                    <article class="flex flex-col justify-between rounded-2xl border border-line bg-white p-6 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:border-[#A7F3D0] hover:shadow-raised">
                        <div>
                            <div class="flex items-center justify-between gap-2">
                                <span class="rounded-lg bg-[#059669]/10 px-2.5 py-1 text-xs font-semibold text-[#047857]">
                                    {{ $journal['prodi'] }}
                                </span>
                                <span class="font-mono text-xs text-muted">{{ $journal['issn'] }}</span>
                            </div>

                            <h3 class="mt-4 text-base font-semibold text-ink sm:text-lg leading-snug">
                                {{ $journal['name'] }}
                            </h3>

                            <div class="mt-3 space-y-2 text-xs">
                                <div class="flex items-center gap-2 text-muted">
                                    <svg class="size-4 text-[#047857] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect width="18" height="18" x="3" y="4" rx="2" ry="2"/>
                                        <line x1="16" x2="16" y1="2" y2="6"/>
                                        <line x1="8" x2="8" y1="2" y2="6"/>
                                        <line x1="3" x2="21" y1="10" y2="10"/>
                                    </svg>
                                    <span>Frekuensi Terbit: <strong class="text-ink">{{ $journal['frequency'] }}</strong></span>
                                </div>
                                <div class="rounded-xl bg-subtle p-3 text-xs">
                                    <span class="font-medium text-ink">Fokus & Ruang Lingkup:</span>
                                    <p class="mt-1 text-muted leading-relaxed">{{ $journal['focus'] }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 border-t border-line pt-4">
                            <a
                                href="{{ $journal['url'] }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#047857] px-4 py-2.5 text-xs font-semibold text-white shadow-sm transition-colors hover:bg-emerald-800"
                            >
                                <span>Masuk ke Laman Jurnal OJS</span>
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
