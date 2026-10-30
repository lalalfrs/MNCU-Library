@extends('layouts.app')

@section('title', 'Repository Karya Ilmiah')
@section('description', 'Repository Institusi Perpustakaan MNC University: arsip digital skripsi, tugas akhir mahasiswa, dan publikasi penelitian dosen sivitas akademika.')

@section('content')
    <div class="mx-auto flex w-full max-w-[1570px] flex-1 flex-col gap-8 px-3 py-6 sm:px-4 lg:px-6 xl:px-8 lg:py-10 responsive-desktop-container">
        {{-- Breadcrumb & Header --}}
        <div class="flex flex-col gap-2">
            <nav aria-label="Breadcrumb" class="flex items-center gap-2 text-xs text-muted sm:text-sm">
                <a href="{{ route('beranda') }}" class="hover:text-primary">Beranda</a>
                <svg class="size-3.5 text-muted/60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
                <span class="font-medium text-ink">Repository</span>
            </nav>
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-semibold text-primary sm:text-3xl lg:text-4xl">
                        Repository Karya Ilmiah MNC University
                    </h1>
                    <p class="mt-1 text-sm text-ink-secondary sm:text-base">
                        Pusat arsip digital skripsi, tugas akhir sarjana, dan publikasi penelitian sivitas akademika MNC University.
                    </p>
                </div>
                <div class="rounded-xl border border-[#DDD6FE] bg-[#7C3AED]/5 px-4 py-2 text-xs text-[#7C3AED]">
                    Total Karya Ilmiah: <span class="font-bold">{{ $totalCount }} dokumen</span>
                </div>
            </div>
        </div>

        {{-- Search & Filter Section --}}
        <section aria-labelledby="judul-filter-repo" class="rounded-2xl border border-line bg-white p-5 lg:p-6 shadow-sm">
            <h2 id="judul-filter-repo" class="sr-only">Form Pencarian Repository</h2>
            <form action="{{ route('repository') }}" method="GET" class="flex flex-col gap-4">
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
                        placeholder="Cari judul skripsi, nama penulis, program studi, atau topik penelitian..."
                        class="h-13 w-full rounded-xl border border-line bg-subtle pl-12 pr-4 text-sm text-ink placeholder:text-muted focus:border-[#7C3AED] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#7C3AED]"
                    >
                </div>

                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-xs font-medium text-ink-secondary">Jenis Dokumen:</span>
                        @foreach ($types as $t)
                            <a
                                href="{{ route('repository', ['tipe' => $t, 'q' => $query]) }}"
                                class="inline-flex h-9 items-center rounded-xl px-3.5 text-xs font-semibold transition-colors duration-150 {{ $tipe === $t ? 'bg-[#7C3AED] text-white shadow-sm' : 'border border-line bg-subtle text-ink hover:border-[#7C3AED]/40 hover:bg-white' }}"
                            >
                                {{ $t }}
                            </a>
                        @endforeach
                    </div>

                    <div class="flex items-center gap-2">
                        <button
                            type="submit"
                            class="inline-flex h-9 items-center gap-2 rounded-xl bg-[#7C3AED] px-5 text-xs font-semibold text-white shadow-sm transition-colors duration-150 hover:bg-[#6D28D9]"
                        >
                            <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"/>
                                <path d="m21 21-4.3-4.3"/>
                            </svg>
                            Cari
                        </button>
                        @if ($query !== '' || $tipe !== 'Semua')
                            <a
                                href="{{ route('repository') }}"
                                class="inline-flex h-9 items-center rounded-xl border border-line bg-subtle px-3.5 text-xs font-medium text-ink-secondary hover:bg-line"
                            >
                                Reset
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </section>

        {{-- Repository Document List --}}
        <section aria-labelledby="daftar-dokumen-repo">
            <h2 id="daftar-dokumen-repo" class="sr-only">Daftar Dokumen Repository</h2>

            @if (count($items) > 0)
                <div class="flex flex-col gap-4">
                    @foreach ($items as $item)
                        <article class="flex flex-col justify-between gap-4 rounded-2xl border border-line bg-white p-6 shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:border-[#7C3AED]/40 hover:shadow-raised md:flex-row md:items-center">
                            <div class="flex-1">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="rounded-lg bg-[#7C3AED]/10 px-2.5 py-1 text-xs font-medium text-[#7C3AED]">
                                        {{ $item['type'] }}
                                    </span>
                                    <span class="rounded-lg bg-subtle px-2.5 py-1 text-xs font-medium text-ink-secondary">
                                        {{ $item['prodi'] }}
                                    </span>
                                    <span class="text-xs text-muted">
                                        Tahun {{ $item['year'] }}
                                    </span>
                                </div>

                                <h3 class="mt-3 text-base font-semibold text-ink sm:text-lg leading-snug">
                                    {{ $item['title'] }}
                                </h3>

                                <div class="mt-2 flex flex-wrap items-center gap-4 text-xs text-ink-secondary">
                                    <div>
                                        <span class="text-muted">Penulis:</span>
                                        <span class="font-medium text-ink">{{ $item['author'] }}</span>
                                    </div>
                                    <div class="flex items-center gap-1 text-muted">
                                        <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                            <polyline points="7 10 12 15 17 10"/>
                                            <line x1="12" x2="12" y1="15" y2="3"/>
                                        </svg>
                                        <span>Diunduh {{ $item['downloads'] }} kali</span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex shrink-0 items-center gap-3 border-t border-line pt-3 md:border-t-0 md:pt-0">
                                <button
                                    type="button"
                                    onclick="alert('Dokumen digital sedang disiapkan untuk pengunduhan')"
                                    class="inline-flex h-10 items-center gap-2 rounded-xl bg-[#7C3AED] px-4 text-xs font-semibold text-white shadow-sm transition-colors hover:bg-[#6D28D9]"
                                >
                                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                        <polyline points="7 10 12 15 17 10"/>
                                        <line x1="12" x2="12" y1="15" y2="3"/>
                                    </svg>
                                    Unduh Naskah
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
                    <h3 class="mt-4 text-base font-semibold text-ink">Dokumen Repository Tidak Ditemukan</h3>
                    <p class="mt-1 max-w-md text-xs text-muted">
                        Karya ilmiah dengan kata kunci atau filter tersebut tidak ditemukan. Silakan ganti kata kunci atau pilih jenis dokumen lain.
                    </p>
                    <a
                        href="{{ route('repository') }}"
                        class="mt-5 inline-flex h-10 items-center justify-center rounded-xl bg-[#7C3AED] px-5 text-xs font-semibold text-white shadow-sm hover:bg-[#6D28D9]"
                    >
                        Tampilkan Semua Dokumen
                    </a>
                </div>
            @endif
        </section>
    </div>
@endsection
