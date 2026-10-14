@extends('layouts.app')

@section('title', 'Katalog OPAC')
@section('description', 'Katalog Online Perpustakaan MNC University (OPAC). Cari buku teks, monograf, buku referensi, dan skripsi fisik dengan informasi ketersediaan dan nomor panggil rak secara real-time.')

@section('content')
    <div class="mx-auto flex w-full max-w-[1570px] flex-1 flex-col gap-8 px-3 py-6 lg:px-6 lg:py-10">
        {{-- Breadcrumb & Header --}}
        <div class="flex flex-col gap-2">
            <nav aria-label="Breadcrumb" class="flex items-center gap-2 text-xs text-muted sm:text-sm">
                <a href="{{ route('beranda') }}" class="hover:text-primary">Beranda</a>
                <svg class="size-3.5 text-muted/60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
                <span class="font-medium text-ink">Katalog OPAC</span>
            </nav>
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-semibold text-primary sm:text-3xl lg:text-4xl">
                        Katalog OPAC Perpustakaan
                    </h1>
                    <p class="mt-1 text-sm text-ink-secondary sm:text-base">
                        Pencarian koleksi fisik, buku teks perkuliahan, dan referensi tercetak MNC University.
                    </p>
                </div>
                <div class="rounded-xl border border-line bg-subtle px-4 py-2 text-xs text-ink-secondary">
                    Total Koleksi Ditemukan: <span class="font-bold text-primary">{{ $totalCount }} judul</span>
                </div>
            </div>
        </div>

        {{-- Search & Filter Section --}}
        <section aria-labelledby="judul-filter-opac" class="rounded-2xl border border-line bg-white p-5 lg:p-6 shadow-sm">
            <h2 id="judul-filter-opac" class="sr-only">Form Pencarian Katalog OPAC</h2>
            <form action="{{ route('opac') }}" method="GET" class="flex flex-col gap-4">
                {{-- Search Bar --}}
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
                        placeholder="Cari judul buku, penulis, kata kunci, atau nomor panggil (misal: Python, Manajemen, 005.1)..."
                        class="h-13 w-full rounded-xl border border-line bg-subtle pl-12 pr-4 text-sm text-ink placeholder:text-muted focus:border-primary focus:bg-white focus:outline-none focus:ring-1 focus:ring-primary"
                    >
                </div>

                {{-- Filter Options --}}
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-[1fr_1fr_auto]">
                    <div>
                        <label for="filter-kategori" class="mb-1 block text-xs font-medium text-ink-secondary">Kategori Koleksi</label>
                        <select
                            id="filter-kategori"
                            name="kategori"
                            class="h-11 w-full rounded-xl border border-line bg-white px-3 text-xs font-medium text-ink focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary"
                        >
                            @foreach ($categories as $cat)
                                <option value="{{ $cat }}" @selected($category === $cat)>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="filter-prodi" class="mb-1 block text-xs font-medium text-ink-secondary">Program Studi</label>
                        <select
                            id="filter-prodi"
                            name="prodi"
                            class="h-11 w-full rounded-xl border border-line bg-white px-3 text-xs font-medium text-ink focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary"
                        >
                            @foreach ($prodis as $p)
                                <option value="{{ $p }}" @selected($prodi === $p)>{{ $p }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-end gap-2">
                        <button
                            type="submit"
                            class="inline-flex h-11 items-center justify-center gap-2 rounded-xl bg-primary px-6 text-xs font-semibold text-white shadow-sm transition-colors duration-150 hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary"
                        >
                            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"/>
                                <path d="m21 21-4.3-4.3"/>
                            </svg>
                            Terapkan Filter
                        </button>
                        @if ($query !== '' || $category !== 'Semua' || $prodi !== 'Semua')
                            <a
                                href="{{ route('opac') }}"
                                class="inline-flex h-11 items-center justify-center rounded-xl border border-line bg-subtle px-4 text-xs font-medium text-ink-secondary hover:bg-line"
                            >
                                Reset
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </section>

        {{-- Catalog List Grid --}}
        <section aria-labelledby="daftar-koleksi-opac">
            <h2 id="daftar-koleksi-opac" class="sr-only">Daftar Buku Koleksi</h2>

            @if (count($items) > 0)
                <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-2">
                    @foreach ($items as $item)
                        <article class="flex flex-col justify-between rounded-2xl border border-line bg-white p-6 shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:border-primary/40 hover:shadow-raised">
                            <div>
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="rounded-lg bg-primary/10 px-2.5 py-1 text-xs font-medium text-primary">
                                            {{ $item['category'] }}
                                        </span>
                                        <span class="rounded-lg bg-subtle px-2.5 py-1 text-xs font-medium text-ink-secondary">
                                            Prodi {{ $item['prodi'] }}
                                        </span>
                                    </div>
                                    <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium {{ str_contains($item['status'], 'Tersedia') ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-amber-50 text-amber-800 border border-amber-200' }}">
                                        <span class="size-1.5 rounded-full {{ str_contains($item['status'], 'Tersedia') ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                                        {{ $item['status'] }}
                                    </span>
                                </div>

                                <h3 class="mt-4 text-lg font-semibold text-ink leading-snug">
                                    {{ $item['title'] }}
                                </h3>

                                <div class="mt-2 text-xs text-ink-secondary">
                                    <span class="font-medium text-ink">Penulis:</span> {{ $item['author'] }} (Tahun {{ $item['year'] }})
                                </div>

                                {{-- Details Grid --}}
                                <div class="mt-4 grid grid-cols-1 gap-2 rounded-xl bg-subtle/70 p-3.5 text-xs sm:grid-cols-2">
                                    <div class="flex items-center gap-2">
                                        <span class="text-muted">Nomor Panggil:</span>
                                        <code class="rounded bg-white px-1.5 py-0.5 font-mono font-semibold text-primary border border-line">
                                            {{ $item['call_number'] }}
                                        </code>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-muted">Eksemplar:</span>
                                        <span class="font-medium text-ink">{{ $item['copies'] }}</span>
                                    </div>
                                    <div class="sm:col-span-2 flex items-center gap-2 pt-1 border-t border-line/50">
                                        <svg class="size-4 text-primary shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/>
                                            <circle cx="12" cy="10" r="3"/>
                                        </svg>
                                        <span class="text-ink font-medium">{{ $item['location'] }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-5 flex items-center justify-between border-t border-line pt-4">
                                <span class="text-xs text-muted">ID: #MNCU-LIB-{{ str_pad($item['id'], 4, '0', STR_PAD_LEFT) }}</span>
                                <div class="flex items-center gap-2">
                                    <a
                                        href="{{ route('chat') }}?q={{ urlencode('Buku ' . $item['title']) }}"
                                        class="inline-flex items-center gap-1.5 rounded-xl border border-line bg-white px-3 py-1.5 text-xs font-semibold text-primary transition-colors hover:border-primary/40 hover:bg-primary/5"
                                    >
                                        <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                                        </svg>
                                        Tanya Pustakawan
                                    </a>
                                </div>
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
                    <h3 class="mt-4 text-base font-semibold text-ink">Koleksi Tidak Ditemukan</h3>
                    <p class="mt-1 max-w-md text-xs text-muted">
                        Koleksi dengan kata kunci atau filter yang Anda pilih tidak ditemukan di sistem OPAC. Coba gunakan kata kunci umum atau reset filter pencarian.
                    </p>
                    <a
                        href="{{ route('opac') }}"
                        class="mt-5 inline-flex h-10 items-center justify-center rounded-xl bg-primary px-5 text-xs font-semibold text-white shadow-sm hover:bg-primary-dark"
                    >
                        Lihat Semua Koleksi
                    </a>
                </div>
            @endif
        </section>
    </div>
@endsection
