@extends('layouts.app')

@section('title', 'Katalog OPAC')
@section('description', 'Katalog Online Perpustakaan MNC University (OPAC). Cari buku teks, monograf, buku referensi, dan skripsi fisik dengan informasi ketersediaan jumlah stok eksemplar dan nomor panggil rak secara real-time.')

@section('content')
    <div class="mx-auto flex w-full max-w-[1570px] flex-1 flex-col gap-6 px-3 py-6 lg:px-6 lg:py-8">
        {{-- Breadcrumb & Header --}}
        <div class="flex flex-col gap-3">
            <nav aria-label="Breadcrumb" class="flex items-center gap-2 text-xs text-muted sm:text-sm">
                <a href="{{ route('beranda') }}" class="hover:text-primary transition-colors">Beranda</a>
                <svg class="size-3.5 text-muted/60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
                <span class="font-medium text-ink">Layanan Perpustakaan</span>
                <svg class="size-3.5 text-muted/60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
                <span class="font-medium text-primary">Katalog OPAC</span>
            </nav>

            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-primary sm:text-3xl lg:text-4xl">
                        Katalog OPAC Perpustakaan
                    </h1>
                    <p class="mt-1 text-xs text-ink-secondary sm:text-sm lg:text-base leading-relaxed">
                        Pencarian koleksi fisik, buku teks perkuliahan, dan referensi tercetak MNC University lengkap dengan status ketersediaan stok eksemplar real-time.
                    </p>
                </div>

                {{-- Badges Ringkasan Inventaris --}}
                <div class="flex flex-wrap items-center gap-2 text-xs">
                    <div class="inline-flex items-center gap-1.5 rounded-xl border border-line bg-white px-3.5 py-2 shadow-xs">
                        <i class="fa-solid fa-book-bookmark text-primary text-xs"></i>
                        <span class="text-muted">Total Judul:</span>
                        <strong class="font-bold text-primary">{{ $totalCount }} judul</strong>
                    </div>
                    <div class="inline-flex items-center gap-1.5 rounded-xl border border-line bg-white px-3.5 py-2 shadow-xs">
                        <i class="fa-solid fa-boxes-stacked text-[#7C3AED] text-xs"></i>
                        <span class="text-muted">Total Fisik:</span>
                        <strong class="font-bold text-ink">{{ $totalEksemplarFisik }} eksemplar</strong>
                    </div>
                    <div class="inline-flex items-center gap-1.5 rounded-xl border border-emerald-200 bg-emerald-50/70 px-3.5 py-2 text-emerald-900 shadow-xs">
                        <span class="size-2 rounded-full bg-emerald-500"></span>
                        <span class="text-emerald-800">Siap Pinjam:</span>
                        <strong class="font-bold text-emerald-900">{{ $totalEksemplarTersedia }} eksemplar</strong>
                    </div>
                </div>
            </div>
        </div>

        {{-- ========================================================================= --}}
        {{-- ALUR FLOWCHART: FITUR NAVIGASI OPAC (KATEGORI SUBJEK, POPULER, BARU)     --}}
        {{-- ========================================================================= --}}
        <section aria-label="Pilihan Fitur Penelusuran OPAC" class="rounded-2xl border border-line bg-white p-2.5 shadow-xs">
            <div class="grid grid-cols-2 gap-2 sm:grid-cols-4">
                {{-- Fitur 1: Semua Koleksi --}}
                <a
                    href="{{ route('opac', ['fitur' => 'semua']) }}"
                    class="flex items-center justify-center gap-2 rounded-xl px-3 py-2.5 text-xs font-semibold transition-all duration-150 {{ $fitur === 'semua' && empty($subjekId) ? 'bg-primary text-white shadow-xs' : 'bg-subtle/70 text-ink-secondary hover:bg-subtle hover:text-primary' }}"
                >
                    <i class="fa-solid fa-layer-group text-xs"></i>
                    <span>Semua Koleksi</span>
                </a>

                {{-- Fitur 2: Kategori Subjek (Sesuai Flowchart Cabang 1) --}}
                <a
                    href="{{ route('opac', ['fitur' => 'subjek']) }}"
                    class="flex items-center justify-center gap-2 rounded-xl px-3 py-2.5 text-xs font-semibold transition-all duration-150 {{ $fitur === 'subjek' ? 'bg-primary text-white shadow-xs' : 'bg-subtle/70 text-ink-secondary hover:bg-subtle hover:text-primary' }}"
                >
                    <i class="fa-solid fa-tags text-xs"></i>
                    <span>Kategori Subjek</span>
                    @if ($subjekList->isNotEmpty())
                        <span class="rounded-full px-1.5 py-0.2 text-[10px] {{ $fitur === 'subjek' ? 'bg-white/20 text-white' : 'bg-primary/10 text-primary' }}">
                            {{ $subjekList->count() }}
                        </span>
                    @endif
                </a>

                {{-- Fitur 3: Koleksi Populer (Sesuai Flowchart Cabang 2) --}}
                <a
                    href="{{ route('opac', ['fitur' => 'populer', 'sort' => 'populer']) }}"
                    class="flex items-center justify-center gap-2 rounded-xl px-3 py-2.5 text-xs font-semibold transition-all duration-150 {{ $fitur === 'populer' ? 'bg-primary text-white shadow-xs' : 'bg-subtle/70 text-ink-secondary hover:bg-subtle hover:text-primary' }}"
                >
                    <i class="fa-solid fa-fire text-xs {{ $fitur === 'populer' ? 'text-amber-300' : 'text-amber-600' }}"></i>
                    <span>Koleksi Populer</span>
                </a>

                {{-- Fitur 4: Koleksi Baru & Diperbarui (Sesuai Flowchart Cabang 3) --}}
                <a
                    href="{{ route('opac', ['fitur' => 'terbaru', 'sort' => 'terbaru']) }}"
                    class="flex items-center justify-center gap-2 rounded-xl px-3 py-2.5 text-xs font-semibold transition-all duration-150 {{ $fitur === 'terbaru' ? 'bg-primary text-white shadow-xs' : 'bg-subtle/70 text-ink-secondary hover:bg-subtle hover:text-primary' }}"
                >
                    <i class="fa-solid fa-sparkles text-xs {{ $fitur === 'terbaru' ? 'text-yellow-300' : 'text-amber-500' }}"></i>
                    <span>Koleksi Baru</span>
                </a>
            </div>
        </section>

        {{-- TAMPILAN CABANG FLOWCHART: DAFTAR KATEGORI SUBJEK UNTUK DIPILIH USER --}}
        @if ($fitur === 'subjek' && empty($subjekId))
            <section aria-labelledby="judul-pilih-subjek" class="rounded-2xl border border-line bg-white p-5 lg:p-6 shadow-sm">
                <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between border-b border-line pb-4">
                    <div>
                        <h2 id="judul-pilih-subjek" class="text-base font-bold text-ink sm:text-lg">
                            <i class="fa-solid fa-tags mr-2 text-primary"></i>Pilih Kategori Subjek Koleksi
                        </h2>
                        <p class="text-xs text-muted">
                            Silakan pilih salah satu kategori subjek keilmuan di bawah ini untuk melihat seluruh koleksi buku terkait:
                        </p>
                    </div>
                    <span class="text-xs font-medium text-ink-secondary">
                        Tersedia {{ $subjekList->count() }} Subjek Akademik
                    </span>
                </div>

                <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5">
                    @forelse ($subjekList as $subj)
                        <a
                            href="{{ route('opac', ['fitur' => 'subjek', 'subjek_id' => $subj->id_subjek_buku]) }}"
                            class="group flex flex-col justify-between rounded-xl border border-line bg-subtle/40 p-4 transition-all duration-150 hover:-translate-y-0.5 hover:border-primary hover:bg-white hover:shadow-sm"
                        >
                            <div class="flex items-start justify-between gap-2">
                                <span class="flex size-9 items-center justify-center rounded-lg bg-primary/10 text-primary group-hover:bg-primary group-hover:text-white transition-colors">
                                    <i class="fa-solid fa-book-bookmark text-xs"></i>
                                </span>
                                <span class="rounded-full bg-white px-2 py-0.5 text-[11px] font-semibold text-primary border border-line group-hover:border-primary/30">
                                    {{ $subj->buku_count ?? 0 }} buku
                                </span>
                            </div>
                            <div class="mt-3">
                                <h3 class="text-xs font-bold text-ink group-hover:text-primary transition-colors line-clamp-2">
                                    {{ $subj->nama_subjek }}
                                </h3>
                                <p class="mt-1 text-[11px] text-muted line-clamp-1">
                                    {{ $subj->deskripsi_subjek ?: 'Koleksi akademik Perpustakaan MNC University.' }}
                                </p>
                            </div>
                            <div class="mt-3 flex items-center gap-1 text-[11px] font-semibold text-primary group-hover:translate-x-1 transition-transform">
                                <span>Lihat Buku</span>
                                <i class="fa-solid fa-arrow-right text-[10px]"></i>
                            </div>
                        </a>
                    @empty
                        <div class="col-span-full py-8 text-center text-xs text-muted">
                            Belum ada kategori subjek tercatat di sistem.
                        </div>
                    @endforelse
                </div>
            </section>
        @endif

        {{-- BANNER SUBJEK TERPILIH (JIKA USER SUDAH MEMILIH SUBJEK TERTENTU) --}}
        @if ($fitur === 'subjek' && ! empty($subjekId))
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between rounded-2xl border border-primary/20 bg-primary/5 p-4 text-xs text-ink sm:px-6">
                <div class="flex items-center gap-3">
                    <span class="flex size-10 items-center justify-center rounded-xl bg-primary text-white shadow-xs">
                        <i class="fa-solid fa-bookmark text-sm"></i>
                    </span>
                    <div>
                        <div class="text-[11px] font-semibold uppercase tracking-wider text-primary">Kategori Subjek Terpilih</div>
                        <h2 class="text-sm font-bold text-ink sm:text-base">
                            {{ $selectedSubjek?->nama_subjek ?? $subjekId }}
                        </h2>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <a
                        href="{{ route('opac', ['fitur' => 'subjek']) }}"
                        class="inline-flex items-center gap-1.5 rounded-xl border border-primary/30 bg-white px-3 py-1.5 text-xs font-semibold text-primary hover:bg-primary hover:text-white transition-colors"
                    >
                        <i class="fa-solid fa-rotate text-[11px]"></i>
                        <span>Ganti Subjek</span>
                    </a>
                    <a
                        href="{{ route('opac', ['fitur' => 'semua']) }}"
                        class="inline-flex items-center gap-1.5 rounded-xl border border-line bg-white px-3 py-1.5 text-xs font-medium text-muted hover:text-ink transition-colors"
                    >
                        <i class="fa-solid fa-xmark text-[11px]"></i>
                        <span>Lihat Semua</span>
                    </a>
                </div>
            </div>
        @endif

        {{-- BANNER KOLEKSI POPULER / TERBARU --}}
        @if ($fitur === 'populer')
            <div class="flex items-center justify-between rounded-2xl border border-amber-200 bg-amber-50/70 p-4 text-xs text-amber-950 sm:px-6">
                <div class="flex items-center gap-3">
                    <span class="flex size-9 items-center justify-center rounded-xl bg-amber-500 text-white shadow-xs">
                        <i class="fa-solid fa-fire text-sm"></i>
                    </span>
                    <div>
                        <span class="font-bold text-amber-900">Koleksi Terpopuler Perpustakaan</span>
                        <p class="text-[11px] text-amber-800">Menampilkan buku-buku yang paling sering dikunjungi, dipelajari, dan dipinjam oleh mahasiswa &amp; dosen.</p>
                    </div>
                </div>
            </div>
        @elseif ($fitur === 'terbaru')
            <div class="flex items-center justify-between rounded-2xl border border-blue-200 bg-blue-50/70 p-4 text-xs text-blue-950 sm:px-6">
                <div class="flex items-center gap-3">
                    <span class="flex size-9 items-center justify-center rounded-xl bg-primary text-white shadow-xs">
                        <i class="fa-solid fa-sparkles text-sm"></i>
                    </span>
                    <div>
                        <span class="font-bold text-primary">Koleksi Baru dan Diperbarui</span>
                        <p class="text-[11px] text-blue-800">Menampilkan buku referensi dan teks terbaru yang baru saja didaftarkan ke dalam katalog perpustakaan.</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- ========================================================================= --}}
        {{-- SECTION UTAMA: 2 POINT PENCARIAN & PENYORTIRAN (SESUAI REQUEST USER)       --}}
        {{-- ========================================================================= --}}
        <section aria-labelledby="judul-filter-opac" class="rounded-2xl border border-line bg-white p-5 lg:p-6 shadow-sm">
            <h2 id="judul-filter-opac" class="sr-only">Form Pencarian dan Filter Katalog OPAC</h2>

            <form action="{{ route('opac') }}" method="GET" class="flex flex-col gap-4" id="main-opac-form">
                {{-- Parameter Tersembunyi untuk mempertahankan Fitur & Subjek --}}
                <input type="hidden" name="fitur" value="{{ $fitur }}">
                @if (! empty($subjekId))
                    <input type="hidden" name="subjek_id" value="{{ $subjekId }}">
                @endif

                {{-- Hidden input untuk menyimpan nilai dari Modal Box Filter --}}
                <input type="hidden" name="filter_pengarang" id="hidden-filter-pengarang" value="{{ $filterPengarang }}">
                <input type="hidden" name="filter_isbn" id="hidden-filter-isbn" value="{{ $filterIsbn }}">
                <input type="hidden" name="filter_ddc" id="hidden-filter-ddc" value="{{ $filterDdc }}">
                <input type="hidden" name="filter_nomor_induk" id="hidden-filter-nomor-induk" value="{{ $filterNomorInduk }}">
                <input type="hidden" name="filter_tahun" id="hidden-filter-tahun" value="{{ $filterTahun }}">
                <input type="hidden" name="filter_lokasi" id="hidden-filter-lokasi" value="{{ $filterLokasi }}">
                <input type="hidden" name="filter_jenis" id="hidden-filter-jenis" value="{{ $filterJenis }}">
                <input type="hidden" name="filter_prodi" id="hidden-filter-prodi" value="{{ $filterProdi }}">

                {{-- Baris Utama: Search Bar (Point 1) & Sort By (Point 2) & Tombol Box Filter --}}
                <div class="grid grid-cols-1 gap-3 md:grid-cols-12">
                    {{-- POINT 1: Search Bar Sederhana dengan Kriteria Tertentu --}}
                    <div class="md:col-span-7 flex flex-col sm:flex-row gap-2">
                        {{-- Dropdown Kriteria Pencarian --}}
                        <div class="sm:w-44 shrink-0">
                            <label for="opac-kriteria" class="sr-only">Kriteria Pencarian</label>
                            <select
                                id="opac-kriteria"
                                name="kriteria"
                                class="h-11 w-full rounded-xl border border-line bg-subtle/50 px-3 text-xs font-semibold text-ink focus:border-primary focus:bg-white focus:outline-none focus:ring-1 focus:ring-primary"
                            >
                                <option value="semua" @selected($kriteria === 'semua')>Semua Kriteria</option>
                                <option value="judul" @selected($kriteria === 'judul')>Judul Buku</option>
                                <option value="pengarang" @selected($kriteria === 'pengarang')>Pengarang</option>
                                <option value="penerbit" @selected($kriteria === 'penerbit')>Penerbit</option>
                                <option value="subjek" @selected($kriteria === 'subjek')>Subjek</option>
                                <option value="nomor_panggil" @selected($kriteria === 'nomor_panggil')>Nomor Panggil</option>
                                <option value="ddc" @selected($kriteria === 'ddc')>Nomor Panggil (DDC)</option>
                                <option value="isbn" @selected($kriteria === 'isbn')>Nomor ISBN</option>
                            </select>
                        </div>

                        {{-- Input Kata Kunci --}}
                        <div class="relative flex-1">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted">
                                <i class="fa-solid fa-magnifying-glass text-xs"></i>
                            </span>
                            <input
                                type="text"
                                id="opac-search-input"
                                name="q"
                                value="{{ $query }}"
                                placeholder="Ketik kata kunci pencarian buku..."
                                class="h-11 w-full rounded-xl border border-line bg-subtle/50 pr-10 pl-10 text-xs text-ink placeholder:text-muted focus:border-primary focus:bg-white focus:outline-none focus:ring-1 focus:ring-primary"
                            >
                            @if (! empty($query))
                                <a
                                    href="{{ route('opac', array_merge(request()->except('q'), ['fitur' => $fitur])) }}"
                                    id="btn-clear-search"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 flex size-6 items-center justify-center rounded-md text-muted hover:bg-slate-200 hover:text-ink transition-colors"
                                    title="Bersihkan Pencarian"
                                >
                                    <i class="fa-solid fa-xmark text-xs"></i>
                                </a>
                            @endif
                        </div>
                    </div>

                    {{-- POINT 2: Sort By Kriteria Tertentu --}}
                    <div class="md:col-span-3">
                        <label for="opac-sort" class="sr-only">Urutkan Berdasarkan</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-muted">
                                <i class="fa-solid fa-arrow-down-short-wide text-xs"></i>
                            </span>
                            <select
                                id="opac-sort"
                                name="sort"
                                onchange="this.form.submit()"
                                class="h-11 w-full rounded-xl border border-line bg-subtle/50 pr-8 pl-8 text-xs font-semibold text-ink focus:border-primary focus:bg-white focus:outline-none focus:ring-1 focus:ring-primary"
                            >
                                <option value="populer" @selected($sort === 'populer')>Sort: Paling Populer</option>
                                <option value="terbaru" @selected($sort === 'terbaru')>Sort: Koleksi Terbaru</option>
                                <option value="judul_asc" @selected($sort === 'judul_asc')>Sort: Judul (A - Z)</option>
                                <option value="judul_desc" @selected($sort === 'judul_desc')>Sort: Judul (Z - A)</option>
                                <option value="tahun_desc" @selected($sort === 'tahun_desc')>Sort: Tahun Terbit (Terbaru)</option>
                                <option value="tahun_asc" @selected($sort === 'tahun_asc')>Sort: Tahun Terbit (Terlama)</option>
                            </select>
                        </div>
                    </div>

                    {{-- Tombol Cari & Tombol Box Filter Lanjutan --}}
                    <div class="md:col-span-2 flex items-center gap-2">
                        {{-- Tombol Buka Modal Box Filter (Sesuai Referensi Gambar 2) --}}
                        <button
                            type="button"
                            onclick="openFilterModal()"
                            class="relative inline-flex h-11 flex-1 items-center justify-center gap-1.5 rounded-xl border border-line bg-white px-3 text-xs font-semibold text-ink hover:border-primary hover:text-primary transition-colors focus:outline-none focus:ring-2 focus:ring-primary"
                            title="Buka Box Filter Pencarian Lanjutan"
                        >
                            <i class="fa-solid fa-sliders text-xs text-primary"></i>
                            <span>Filter</span>
                            @if ($activeFilterCount > 0)
                                <span class="flex size-4 items-center justify-center rounded-full bg-primary text-[10px] font-bold text-white">
                                    {{ $activeFilterCount }}
                                </span>
                            @endif
                        </button>

                        {{-- Tombol Submit Search --}}
                        <button
                            type="submit"
                            class="inline-flex h-11 items-center justify-center gap-1.5 rounded-xl bg-primary px-4 text-xs font-semibold text-white shadow-xs transition-colors hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary shrink-0"
                            title="Terapkan Pencarian"
                        >
                            <span>Cari</span>
                        </button>
                    </div>
                </div>

                {{-- TAG FILTER AKTIF: Memudahkan user melihat dan menghapus filter yang sedang berjalan --}}
                @if ($activeFilterCount > 0 || ! empty($query) || ($fitur === 'subjek' && ! empty($subjekId)))
                    <div class="flex flex-wrap items-center gap-2 border-t border-line/70 pt-3 text-xs">
                        <span class="text-muted font-medium text-[11px]">Filter Aktif:</span>

                        @if (! empty($query))
                            <span class="inline-flex items-center gap-1 rounded-lg bg-primary/10 px-2.5 py-1 text-[11px] font-semibold text-primary">
                                Kata Kunci: "{{ $query }}"
                                <a href="{{ route('opac', array_merge(request()->except('q'), ['fitur' => $fitur])) }}" class="ml-1 text-primary/70 hover:text-primary"><i class="fa-solid fa-xmark"></i></a>
                            </span>
                        @endif

                        @if (! empty($filterPengarang))
                            <span class="inline-flex items-center gap-1 rounded-lg bg-slate-100 px-2.5 py-1 text-[11px] font-medium text-ink">
                                Pengarang: {{ $filterPengarang }}
                                <a href="{{ route('opac', array_merge(request()->except('filter_pengarang'), ['fitur' => $fitur])) }}" class="ml-1 text-muted hover:text-red-500"><i class="fa-solid fa-xmark"></i></a>
                            </span>
                        @endif

                        @if (! empty($filterIsbn))
                            <span class="inline-flex items-center gap-1 rounded-lg bg-slate-100 px-2.5 py-1 text-[11px] font-medium text-ink">
                                ISBN: {{ $filterIsbn }}
                                <a href="{{ route('opac', array_merge(request()->except('filter_isbn'), ['fitur' => $fitur])) }}" class="ml-1 text-muted hover:text-red-500"><i class="fa-solid fa-xmark"></i></a>
                            </span>
                        @endif

                        @if (! empty($filterDdc))
                            <span class="inline-flex items-center gap-1 rounded-lg bg-slate-100 px-2.5 py-1 text-[11px] font-medium text-ink">
                                DDC: {{ $filterDdc }}
                                <a href="{{ route('opac', array_merge(request()->except('filter_ddc'), ['fitur' => $fitur])) }}" class="ml-1 text-muted hover:text-red-500"><i class="fa-solid fa-xmark"></i></a>
                            </span>
                        @endif

                        @if (! empty($filterNomorInduk))
                            <span class="inline-flex items-center gap-1 rounded-lg bg-slate-100 px-2.5 py-1 text-[11px] font-medium text-ink">
                                No. Induk: {{ $filterNomorInduk }}
                                <a href="{{ route('opac', array_merge(request()->except('filter_nomor_induk'), ['fitur' => $fitur])) }}" class="ml-1 text-muted hover:text-red-500"><i class="fa-solid fa-xmark"></i></a>
                            </span>
                        @endif

                        @if ($filterTahun !== 'Semua' && ! empty($filterTahun))
                            <span class="inline-flex items-center gap-1 rounded-lg bg-slate-100 px-2.5 py-1 text-[11px] font-medium text-ink">
                                Tahun: {{ $filterTahun }}
                                <a href="{{ route('opac', array_merge(request()->except('filter_tahun'), ['fitur' => $fitur])) }}" class="ml-1 text-muted hover:text-red-500"><i class="fa-solid fa-xmark"></i></a>
                            </span>
                        @endif

                        @if ($filterLokasi !== 'Semua' && ! empty($filterLokasi))
                            <span class="inline-flex items-center gap-1 rounded-lg bg-slate-100 px-2.5 py-1 text-[11px] font-medium text-ink">
                                Lokasi: {{ $filterLokasi }}
                                <a href="{{ route('opac', array_merge(request()->except('filter_lokasi'), ['fitur' => $fitur])) }}" class="ml-1 text-muted hover:text-red-500"><i class="fa-solid fa-xmark"></i></a>
                            </span>
                        @endif

                        @if ($filterJenis !== 'Semua' && ! empty($filterJenis))
                            <span class="inline-flex items-center gap-1 rounded-lg bg-slate-100 px-2.5 py-1 text-[11px] font-medium text-ink">
                                Jenis: {{ $filterJenis }}
                                <a href="{{ route('opac', array_merge(request()->except('filter_jenis'), ['fitur' => $fitur])) }}" class="ml-1 text-muted hover:text-red-500"><i class="fa-solid fa-xmark"></i></a>
                            </span>
                        @endif

                        @if ($filterProdi !== 'Semua' && ! empty($filterProdi))
                            <span class="inline-flex items-center gap-1 rounded-lg bg-slate-100 px-2.5 py-1 text-[11px] font-medium text-ink">
                                Jurusan: {{ $filterProdi }}
                                <a href="{{ route('opac', array_merge(request()->except('filter_prodi'), ['fitur' => $fitur])) }}" class="ml-1 text-muted hover:text-red-500"><i class="fa-solid fa-xmark"></i></a>
                            </span>
                        @endif

                        {{-- Tombol Reset Semua Filter --}}
                        <a
                            href="{{ route('opac', ['fitur' => $fitur]) }}"
                            class="inline-flex items-center gap-1 text-[11px] font-semibold text-rose-600 hover:text-rose-700 hover:underline transition-colors ml-auto"
                        >
                            <i class="fa-solid fa-rotate-left text-[10px]"></i>
                            <span>Reset Semua Filter</span>
                        </a>
                    </div>
                @endif
            </form>
        </section>

        {{-- ========================================================================= --}}
        {{-- DAFTAR KARTU BUKU (CARDS MENIRU REFERENSI DESAIN GAMBAR 2)                --}}
        {{-- ========================================================================= --}}
        <section aria-labelledby="daftar-koleksi-opac">
            <h2 id="daftar-koleksi-opac" class="sr-only">Daftar Buku Koleksi OPAC</h2>

            {{-- Baris Kontrol: Jumlah Hasil, Pilihan Urutan & Switcher Tampilan (Grid / Baris) --}}
            <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between border-b border-line pb-3">
                <div class="flex items-center gap-3 text-xs text-muted">
                    <span>Menampilkan <strong>{{ count($items) }}</strong> judul buku</span>
                    <span class="hidden sm:inline text-line" aria-hidden="true">|</span>
                    <span class="font-medium text-ink-secondary">
                        Urutan:
                        @if ($sort === 'populer')
                            Paling Populer
                        @elseif ($sort === 'terbaru')
                            Koleksi Terbaru
                        @elseif ($sort === 'judul_asc')
                            Judul (A-Z)
                        @elseif ($sort === 'judul_desc')
                            Judul (Z-A)
                        @elseif ($sort === 'tahun_desc')
                            Tahun Terbit (Terbaru)
                        @else
                            Tahun Terbit (Terlama)
                        @endif
                    </span>
                </div>

                {{-- Layout View Switcher: Grid (Default) vs Baris (Row) --}}
                <div class="flex items-center gap-2 self-end sm:self-auto">
                    <span class="text-xs text-muted hidden md:inline">Tampilan:</span>
                    <div class="inline-flex items-center rounded-xl border border-line bg-white p-1 shadow-xs" role="group" aria-label="Pilihan Tata Letak Katalog">
                        <button
                            type="button"
                            id="btn-view-grid"
                            onclick="setCatalogView('grid')"
                            title="Tampilan Grid (Kotak)"
                            aria-label="Tampilan Grid"
                            class="inline-flex min-h-[34px] items-center gap-1.5 rounded-lg px-3 py-1 text-xs font-semibold bg-primary text-white shadow-xs transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-primary/20"
                        >
                            <i class="fa-solid fa-table-cells-large text-xs" aria-hidden="true"></i>
                            <span>Grid</span>
                        </button>
                        <button
                            type="button"
                            id="btn-view-row"
                            onclick="setCatalogView('row')"
                            title="Tampilan Baris (List)"
                            aria-label="Tampilan Baris"
                            class="inline-flex min-h-[34px] items-center gap-1.5 rounded-lg px-3 py-1 text-xs font-medium text-muted hover:text-ink hover:bg-slate-50 transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-primary/20"
                        >
                            <i class="fa-solid fa-list text-xs" aria-hidden="true"></i>
                            <span>Baris</span>
                        </button>
                    </div>
                </div>
            </div>

            @if (count($items) > 0)
                {{-- Container Katalog: Default Grid Responsif --}}
                <div id="catalog-container" class="grid grid-cols-1 gap-5 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4" data-view="grid">
                    @foreach ($items as $item)
                        @php
                            $available = $item['copies_available'] ?? 0;
                            $total = $item['copies_total'] ?? 0;
                        @endphp
                        <article
                            tabindex="0"
                            onclick="openBookDetailModal({{ json_encode($item) }})"
                            onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault();openBookDetailModal({{ json_encode($item) }});}"
                            class="catalog-book-card group relative flex flex-col justify-between rounded-2xl border border-line bg-white shadow-xs transition-all duration-200 hover:-translate-y-1 hover:border-primary/50 hover:shadow-md cursor-pointer focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 overflow-hidden"
                            aria-label="Lihat detail buku {{ $item['title'] }}"
                        >
                            <div class="card-inner-layout flex flex-col h-full">
                                {{-- 1. Wadah Sampul Buku (Cover Image) --}}
                                <div class="card-cover-wrapper relative w-full aspect-[3/4] shrink-0 overflow-hidden bg-slate-100 border-b border-line/60">
                                    @if (! empty($item['cover_url']))
                                        <img
                                            src="{{ $item['cover_url'] }}"
                                            alt="Sampul buku {{ $item['title'] }}"
                                            class="size-full object-cover transition-transform duration-300 group-hover:scale-105"
                                            loading="lazy"
                                            onerror="this.classList.add('hidden'); this.nextElementSibling?.classList.remove('hidden');"
                                        >
                                    @endif

                                    {{-- Fallback Sampul Buku jika URL Kosong atau Gagal Dimuat --}}
                                    <div class="card-cover-fallback {{ ! empty($item['cover_url']) ? 'hidden' : '' }} flex size-full flex-col justify-between bg-gradient-to-br from-[#0F2A6B] via-[#143A8F] to-[#0A1E4F] p-4 text-white">
                                        <div class="flex items-center justify-between opacity-80">
                                            <span class="text-[10px] font-bold uppercase tracking-wider text-blue-200">MNC University</span>
                                            <i class="fa-solid fa-book-bookmark text-sm text-blue-200" aria-hidden="true"></i>
                                        </div>
                                        <div class="my-auto py-2">
                                            <h4 class="text-xs font-bold leading-tight line-clamp-3 text-white sm:text-sm">
                                                {{ $item['title'] }}
                                            </h4>
                                            <p class="mt-1 text-[11px] text-blue-200 line-clamp-1">
                                                {{ $item['author'] }}
                                            </p>
                                        </div>
                                        <div class="flex items-center justify-between border-t border-white/20 pt-2 text-[10px] text-blue-200">
                                            <span>{{ $item['category'] ?? 'Buku Cetak' }}</span>
                                            <span>{{ $item['year'] ?? 2024 }}</span>
                                        </div>
                                    </div>

                                    {{-- Floating Badges pada Mode Grid --}}
                                    <div class="grid-only-badge pointer-events-none absolute inset-x-0 top-0 flex items-center justify-between p-2.5">
                                        <span class="inline-flex items-center gap-1 rounded-md bg-black/75 px-2 py-0.5 font-mono text-[10px] font-bold text-amber-300 backdrop-blur-xs shadow-xs">
                                            <i class="fa-solid fa-bookmark text-[9px]" aria-hidden="true"></i>
                                            <span>{{ $item['call_number'] ?? '000.00 MNC' }}</span>
                                        </span>


                                        @if ($total === 0)
                                            <span class="inline-flex items-center rounded-md bg-slate-900/80 px-2 py-0.5 text-[10px] font-semibold text-slate-200 backdrop-blur-xs">
                                                Digital
                                            </span>
                                        @elseif ($available > 0)
                                            <span class="inline-flex items-center gap-1 rounded-md bg-emerald-700/90 px-2 py-0.5 text-[10px] font-bold text-white backdrop-blur-xs shadow-xs">
                                                <span class="size-1.5 rounded-full bg-emerald-300 animate-pulse"></span>
                                                <span>{{ $available }}/{{ $total }} Eks</span>
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 rounded-md bg-amber-700/90 px-2 py-0.5 text-[10px] font-bold text-white backdrop-blur-xs shadow-xs">
                                                <span>Dipinjam</span>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                {{-- 2. Wadah Rincian Informasi Buku --}}
                                <div class="card-details-wrapper p-4 flex flex-col flex-1 justify-between gap-3">
                                    <div>
                                        {{-- Baris Header Khusus Tampilan Row / Baris --}}
                                        <div class="row-only-header hidden items-center justify-between gap-2 border-b border-line/60 pb-2 mb-2">
                                            <div class="flex items-center gap-1.5 font-mono text-xs font-bold text-amber-700">
                                                <i class="fa-solid fa-bookmark text-[11px] text-amber-600" aria-hidden="true"></i>
                                                <span>{{ $item['call_number'] ?? '000.00 MNC' }}</span>
                                            </div>

                                            @if ($total === 0)
                                                <span class="inline-flex items-center gap-1 rounded-full border border-slate-200 bg-slate-50 px-2.5 py-0.5 text-[11px] font-medium text-slate-700">
                                                    Koleksi Digital
                                                </span>
                                            @elseif ($available > 0)
                                                <span class="inline-flex items-center gap-1 rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-0.5 text-[11px] font-semibold text-emerald-800">
                                                    <span class="size-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                                    <span>Tersedia: {{ $available }} / {{ $total }} Eks</span>
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 rounded-full border border-amber-200 bg-amber-50 px-2.5 py-0.5 text-[11px] font-semibold text-amber-800">
                                                    <span class="size-1.5 rounded-full bg-amber-500"></span>
                                                    <span>Dipinjam ({{ $total }} Eks)</span>
                                                </span>
                                            @endif
                                        </div>

                                        {{-- Judul Buku --}}
                                        <h3 class="text-sm sm:text-base font-bold text-ink transition-colors group-hover:text-primary leading-snug line-clamp-2">
                                            {{ $item['title'] }}
                                        </h3>

                                        {{-- Penulis & Penerbit --}}
                                        <p class="mt-1.5 text-xs text-ink-secondary flex items-start gap-1.5 leading-relaxed">
                                            <i class="fa-regular fa-user text-muted shrink-0 mt-0.5 text-[11px]" aria-hidden="true"></i>
                                            <span class="truncate">
                                                oleh <strong class="font-semibold text-ink">{{ $item['author'] }}</strong>
                                            </span>
                                        </p>

                                        <p class="mt-0.5 text-[11px] text-muted truncate">
                                            {{ $item['publisher'] }} &bull; {{ $item['year'] }}
                                        </p>

                                        {{-- Deskripsi Khusus Mode Row / Baris --}}
                                        <p class="row-only-desc hidden mt-2 text-xs text-muted line-clamp-2 leading-relaxed">
                                            {{ $item['description'] }}
                                        </p>

                                        {{-- Tag Lokasi & Kategori --}}
                                        <div class="mt-2.5 flex flex-wrap items-center gap-1.5 text-[11px]">
                                            <span class="inline-flex items-center gap-1 rounded-lg bg-subtle px-2 py-0.5 text-muted">
                                                <i class="fa-solid fa-location-dot text-primary text-[10px]" aria-hidden="true"></i>
                                                <span>{{ $item['location'] }}</span>
                                            </span>

                                            @if (! empty($item['category']))
                                                <span class="rounded-lg bg-primary/10 px-2 py-0.5 font-semibold text-primary">
                                                    {{ $item['category'] }}
                                                </span>
                                            @endif

                                            @if (($item['visits'] ?? 0) > 0)
                                                <span class="inline-flex items-center gap-1 rounded-lg bg-amber-50 px-2 py-0.5 text-amber-800 border border-amber-200">
                                                    <i class="fa-solid fa-eye text-[10px]" aria-hidden="true"></i>
                                                    <span>{{ $item['visits'] }}x</span>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Footer Kartu: ID & Tombol Aksi --}}
                                    <div class="mt-3 flex items-center justify-between border-t border-line/70 pt-2.5 text-xs">
                                        <span class="font-mono text-[10px] sm:text-[11px] text-muted">ID: {{ $item['id'] }}</span>
                                        <button
                                            type="button"
                                            class="inline-flex items-center gap-1.5 rounded-xl bg-primary px-3 py-1.5 text-xs font-semibold text-white shadow-xs group-hover:bg-primary-dark transition-colors"
                                        >
                                            <i class="fa-solid fa-circle-info text-[11px]" aria-hidden="true"></i>
                                            <span>Detail Buku</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="flex flex-col items-center justify-center rounded-2xl border border-line bg-subtle/50 p-12 text-center">
                    <div class="flex size-14 items-center justify-center rounded-2xl bg-white text-muted shadow-sm">
                        <i class="fa-solid fa-book-open text-2xl text-muted/60"></i>
                    </div>
                    <h3 class="mt-4 text-base font-bold text-ink">Koleksi Tidak Ditemukan</h3>
                    <p class="mt-1 max-w-md text-xs text-muted leading-relaxed">
                        Tidak ada koleksi buku yang cocok dengan kriteria kata kunci atau kombinasi box filter yang Anda tentukan. Silakan gunakan kata kunci umum atau reset filter pencarian Anda.
                    </p>
                    <div class="mt-5 flex flex-wrap items-center justify-center gap-2.5">
                        <button
                            type="button"
                            onclick="focusToSearchBar()"
                            class="inline-flex h-10 items-center justify-center gap-2 rounded-xl bg-primary px-4 text-xs font-semibold text-white shadow-sm hover:bg-primary-dark transition-colors"
                        >
                            <i class="fa-solid fa-magnifying-glass text-xs"></i>
                            <span>Coba Kata Kunci Lain</span>
                        </button>
                        <a
                            href="{{ route('opac', ['fitur' => 'semua']) }}"
                            class="inline-flex h-10 items-center justify-center gap-2 rounded-xl border border-line bg-white px-4 text-xs font-medium text-ink hover:bg-subtle transition-colors"
                        >
                            <i class="fa-solid fa-rotate-left text-xs"></i>
                            <span>Lihat Semua Koleksi</span>
                        </a>
                    </div>
                </div>
            @endif
        </section>
    </div>

    {{-- ========================================================================= --}}
    {{-- MODAL BOX FILTER PENCARIAN (PERSIS REFERENSI GAMBAR 2)                     --}}
    {{-- ========================================================================= --}}
    <div
        id="search-filter-modal"
        class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-xs transition-opacity duration-200"
        role="dialog"
        aria-modal="true"
        aria-labelledby="modal-filter-title"
    >
        <div class="flex min-h-screen items-center justify-center p-3 sm:p-6">
            <div class="relative w-full max-w-3xl overflow-hidden rounded-2xl border border-line bg-white shadow-2xl transition-all">
                {{-- Header Box Filter: Icon Slider Ungu + Judul "Filter Pencarian" + Tombol X --}}
                <div class="flex items-center justify-between border-b border-line bg-white px-6 py-4">
                    <div class="flex items-center gap-2.5">
                        <span class="flex size-8 items-center justify-center rounded-lg bg-primary/10 text-primary">
                            <i class="fa-solid fa-sliders text-sm"></i>
                        </span>
                        <h3 id="modal-filter-title" class="text-base font-bold text-ink">
                            Filter Pencarian
                        </h3>
                    </div>
                    <button
                        type="button"
                        onclick="closeFilterModal()"
                        class="inline-flex size-8 items-center justify-center rounded-lg text-muted hover:bg-slate-100 hover:text-ink transition-colors focus:outline-none focus:ring-2 focus:ring-primary"
                        aria-label="Tutup dialog filter pencarian"
                    >
                        <i class="fa-solid fa-xmark text-base"></i>
                    </button>
                </div>

                {{-- Body Box Filter: Grid 2 Baris x 4 Kolom Sesuai Desain Gambar 2 --}}
                <form id="filter-modal-form" onsubmit="applyModalFilters(event)" class="p-6 space-y-4">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                        {{-- Field 1: Pengarang (Placeholder: elliot) --}}
                        <div>
                            <label for="modal-input-pengarang" class="mb-1.5 block text-xs font-semibold text-ink">
                                Pengarang
                            </label>
                            <input
                                type="text"
                                id="modal-input-pengarang"
                                value="{{ $filterPengarang }}"
                                placeholder="elliot"
                                class="h-10 w-full rounded-xl border border-line bg-white px-3 text-xs text-ink placeholder:text-muted focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary"
                            >
                        </div>

                        {{-- Field 2: ISBN (Placeholder: 978-xxx...) --}}
                        <div>
                            <label for="modal-input-isbn" class="mb-1.5 block text-xs font-semibold text-ink">
                                ISBN
                            </label>
                            <input
                                type="text"
                                id="modal-input-isbn"
                                value="{{ $filterIsbn }}"
                                placeholder="978-xxx..."
                                class="h-10 w-full rounded-xl border border-line bg-white px-3 text-xs text-ink placeholder:text-muted focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary font-mono"
                            >
                        </div>

                        {{-- Field 3: DDC (Placeholder: 000.00...) --}}
                        <div>
                            <label for="modal-input-ddc" class="mb-1.5 block text-xs font-semibold text-ink">
                                DDC
                            </label>
                            <input
                                type="text"
                                id="modal-input-ddc"
                                value="{{ $filterDdc }}"
                                placeholder="000.00..."
                                class="h-10 w-full rounded-xl border border-line bg-white px-3 text-xs text-ink placeholder:text-muted focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary font-mono"
                            >
                        </div>

                        {{-- Field 4: Nomor Induk (Placeholder: Nomor induk...) --}}
                        <div>
                            <label for="modal-input-nomor-induk" class="mb-1.5 block text-xs font-semibold text-ink">
                                Nomor Induk
                            </label>
                            <input
                                type="text"
                                id="modal-input-nomor-induk"
                                value="{{ $filterNomorInduk }}"
                                placeholder="Nomor induk..."
                                class="h-10 w-full rounded-xl border border-line bg-white px-3 text-xs text-ink placeholder:text-muted focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary font-mono"
                            >
                        </div>

                        {{-- Field 5: Tahun Terbit (Dropdown: Semua tahun) --}}
                        <div>
                            <label for="modal-select-tahun" class="mb-1.5 block text-xs font-semibold text-ink">
                                Tahun Terbit
                            </label>
                            <select
                                id="modal-select-tahun"
                                class="h-10 w-full rounded-xl border border-line bg-white px-3 text-xs text-ink focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary"
                            >
                                <option value="Semua">Semua tahun</option>
                                @foreach ($tahunList as $thn)
                                    <option value="{{ $thn }}" @selected($filterTahun == $thn)>{{ $thn }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Field 6: Lokasi (Dropdown: Semua lokasi) --}}
                        <div>
                            <label for="modal-select-lokasi" class="mb-1.5 block text-xs font-semibold text-ink">
                                Lokasi
                            </label>
                            <select
                                id="modal-select-lokasi"
                                class="h-10 w-full rounded-xl border border-line bg-white px-3 text-xs text-ink focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary"
                            >
                                <option value="Semua">Semua lokasi</option>
                                @foreach ($lokasiList as $lok)
                                    <option value="{{ $lok }}" @selected($filterLokasi == $lok)>{{ $lok }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Field 7: Jenis Koleksi (Dropdown: Semua jenis) --}}
                        <div>
                            <label for="modal-select-jenis" class="mb-1.5 block text-xs font-semibold text-ink">
                                Jenis Koleksi
                            </label>
                            <select
                                id="modal-select-jenis"
                                class="h-10 w-full rounded-xl border border-line bg-white px-3 text-xs text-ink focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary"
                            >
                                <option value="Semua">Semua jenis</option>
                                @foreach ($types as $tp)
                                    <option value="{{ $tp }}" @selected($filterJenis == $tp)>{{ $tp }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Field 8: Fakultas / Jurusan (Dropdown: Semua Fakultas) --}}
                        <div>
                            <label for="modal-select-prodi" class="mb-1.5 block text-xs font-semibold text-ink">
                                Fakultas / Jurusan
                            </label>
                            <select
                                id="modal-select-prodi"
                                class="h-10 w-full rounded-xl border border-line bg-white px-3 text-xs text-ink focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary"
                            >
                                <option value="Semua">Semua Fakultas</option>
                                @foreach ($prodiList as $prd)
                                    <option value="{{ $prd->nama_prodi }}" @selected($filterProdi == $prd->nama_prodi)>{{ $prd->nama_prodi }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Footer Box Filter: Tombol Terapkan & Reset --}}
                    <div class="flex items-center justify-end gap-2.5 pt-4 border-t border-line">
                        <button
                            type="button"
                            onclick="resetModalFilters()"
                            class="inline-flex h-10 items-center justify-center rounded-xl border border-line bg-white px-4 text-xs font-semibold text-ink-secondary hover:bg-slate-50 transition-colors"
                        >
                            Reset Filter
                        </button>
                        <button
                            type="submit"
                            class="inline-flex h-10 items-center justify-center gap-1.5 rounded-xl bg-primary px-5 text-xs font-semibold text-white shadow-xs hover:bg-primary-dark transition-colors"
                        >
                            <i class="fa-solid fa-check text-xs"></i>
                            <span>Terapkan Filter</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ========================================================================= --}}
    {{-- MODAL DETAIL INFORMASI BUKU LENGKAP & STOK EKSEMPLAR FISIK              --}}
    {{-- ========================================================================= --}}
    <div
        id="book-detail-modal"
        class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-xs transition-opacity duration-200"
        role="dialog"
        aria-modal="true"
        aria-labelledby="modal-book-title"
    >
        <div class="flex min-h-screen items-center justify-center p-3 sm:p-6">
            <div class="relative w-full max-w-3xl overflow-hidden rounded-2xl border border-line bg-white shadow-2xl transition-all">
                {{-- Header Detail Buku --}}
                <div class="flex items-center justify-between border-b border-line bg-slate-50/80 px-6 py-4">
                    <div class="flex items-center gap-2.5">
                        <span class="flex size-8 items-center justify-center rounded-lg bg-primary/10 text-primary">
                            <i class="fa-solid fa-book-open text-sm"></i>
                        </span>
                        <div>
                            <h3 class="text-sm font-bold text-ink">Detail Informasi Buku &amp; Ketersediaan Stok</h3>
                            <p class="text-[11px] text-muted">Informasi bibliografi dan nomor panggil rak Perpustakaan MNC University</p>
                        </div>
                    </div>
                    <button
                        type="button"
                        onclick="closeBookDetailModal()"
                        class="inline-flex size-8 items-center justify-center rounded-lg text-muted hover:bg-slate-200 hover:text-ink transition-colors focus:outline-none focus:ring-2 focus:ring-primary"
                        aria-label="Tutup dialog rincian buku"
                    >
                        <i class="fa-solid fa-xmark text-base"></i>
                    </button>
                </div>

                {{-- Body Detail Buku --}}
                <div class="max-h-[78vh] overflow-y-auto p-6 space-y-6">
                    {{-- Baris Metadata Bibliografi --}}
                    <div class="flex flex-col gap-5 sm:flex-row sm:items-start">
                        {{-- Cover Image --}}
                        <div class="relative h-48 w-36 shrink-0 overflow-hidden rounded-xl border border-line bg-subtle mx-auto sm:mx-0 shadow-xs">
                            <img
                                id="modal-cover-img"
                                src=""
                                alt="Sampul Koleksi"
                                class="h-full w-full object-cover"
                            >
                            <div id="modal-cover-placeholder" class="hidden h-full w-full flex-col items-center justify-center p-3 text-center text-muted">
                                <i class="fa-solid fa-book text-2xl text-primary/60"></i>
                                <span class="mt-2 text-[10px] font-semibold text-ink/70">MNC University</span>
                            </div>
                        </div>

                        {{-- Metadata Koleksi --}}
                        <div class="min-w-0 flex-1 space-y-2">
                            <div class="flex flex-wrap items-center gap-1.5">
                                <span id="modal-category" class="rounded-lg bg-primary/10 px-2.5 py-0.5 text-xs font-semibold text-primary">
                                    Buku Teks
                                </span>
                                <span id="modal-format" class="rounded-lg bg-subtle px-2.5 py-0.5 text-xs font-medium text-ink-secondary">
                                    Buku Cetak
                                </span>
                                <span id="modal-id" class="font-mono text-xs text-muted">
                                    #MNCU00001
                                </span>
                            </div>

                            <h4 id="modal-book-title" class="text-lg font-bold text-ink leading-snug sm:text-xl">
                                [Judul Koleksi]
                            </h4>

                            <div class="grid grid-cols-1 gap-1 text-xs text-ink-secondary sm:grid-cols-2 pt-1">
                                <p><span class="font-medium text-ink">Pengarang:</span> <span id="modal-author">-</span></p>
                                <p><span class="font-medium text-ink">Penerbit:</span> <span id="modal-publisher">-</span></p>
                                <p><span class="font-medium text-ink">Tahun Terbit:</span> <span id="modal-year">-</span></p>
                                <p><span class="font-medium text-ink">Edisi / Halaman:</span> <span id="modal-pages">-</span></p>
                                <p class="sm:col-span-2 font-mono"><span class="font-medium text-ink font-sans">ISBN:</span> <span id="modal-isbn">-</span></p>
                                <p class="sm:col-span-2"><span class="font-medium text-ink">Nomor Panggil (DDC):</span> <code id="modal-call-number" class="font-mono text-primary font-bold bg-subtle px-1.5 py-0.5 rounded">-</code></p>
                            </div>

                            {{-- Subjek Terkait --}}
                            <div class="pt-1">
                                <span class="text-[11px] font-semibold text-muted block mb-1">Subjek Keilmuan:</span>
                                <div id="modal-subjects-container" class="flex flex-wrap gap-1">
                                    {{-- Diisi secara dinamis --}}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 3 Metrik Status Stok Eksemplar --}}
                    <div class="grid grid-cols-3 gap-3">
                        <div class="rounded-xl border border-line bg-subtle/50 p-3 text-center">
                            <span class="text-[11px] font-medium text-muted uppercase">Total Eksemplar</span>
                            <p id="modal-stat-total" class="mt-1 text-xl font-bold text-ink">0</p>
                            <span class="text-[10px] text-muted">Tercatat di sistem</span>
                        </div>
                        <div class="rounded-xl border border-emerald-200 bg-emerald-50/60 p-3 text-center">
                            <span class="text-[11px] font-medium text-emerald-800 uppercase">Tersedia di Rak</span>
                            <p id="modal-stat-available" class="mt-1 text-xl font-bold text-emerald-900">0</p>
                            <span class="text-[10px] text-emerald-700">Siap dipinjam</span>
                        </div>
                        <div class="rounded-xl border border-amber-200 bg-amber-50/60 p-3 text-center">
                            <span class="text-[11px] font-medium text-amber-800 uppercase">Sedang Dipinjam</span>
                            <p id="modal-stat-borrowed" class="mt-1 text-xl font-bold text-amber-900">0</p>
                            <span class="text-[10px] text-amber-700">Di tangan pemustaka</span>
                        </div>
                    </div>

                    {{-- Sinopsis / Deskripsi --}}
                    <div class="rounded-xl border border-line bg-white p-4">
                        <h5 class="text-xs font-bold uppercase tracking-wider text-muted">Sinopsis &amp; Deskripsi Buku</h5>
                        <p id="modal-description" class="mt-2 text-xs text-ink-secondary leading-relaxed whitespace-pre-line">
                            -
                        </p>
                    </div>

                    {{-- Tabel Seluruh Eksemplar Fisik & Nomor Induk Inventaris --}}
                    <div>
                        <div class="flex items-center justify-between mb-2.5">
                            <h5 class="text-xs font-bold uppercase tracking-wider text-primary">
                                <i class="fa-solid fa-boxes-stacked mr-1.5"></i>Daftar Eksemplar Fisik &amp; Nomor Induk
                            </h5>
                            <span id="modal-copy-count" class="text-xs text-muted">0 eksemplar</span>
                        </div>

                        <div class="overflow-x-auto rounded-xl border border-line bg-white shadow-xs">
                            <table class="w-full text-left text-xs">
                                <thead class="border-b border-line bg-slate-50 text-[11px] font-semibold uppercase tracking-wider text-muted">
                                    <tr>
                                        <th class="px-3.5 py-2.5">No</th>
                                        <th class="px-3.5 py-2.5">Nomor Induk / Barcode</th>
                                        <th class="px-3.5 py-2.5">Nomor Panggil (DDC)</th>
                                        <th class="px-3.5 py-2.5">Lokasi Rak</th>
                                        <th class="px-3.5 py-2.5 text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody id="modal-exemplar-tbody" class="divide-y divide-line text-ink">
                                    {{-- Diisi secara dinamis via JavaScript --}}
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Catatan Alur Peminjaman --}}
                    <div class="rounded-xl border border-blue-200 bg-blue-50/70 p-3.5 text-xs text-blue-900 flex items-start gap-2.5">
                        <i class="fa-solid fa-circle-info text-primary text-sm shrink-0 mt-0.5"></i>
                        <p class="leading-relaxed">
                            <strong>Tata Cara Peminjaman Buku:</strong> Catat nomor panggil dan lokasi rak buku di atas, lalu kunjungi meja sirkulasi Perpustakaan MNC University Lantai 2 dengan membawa Kartu Tanda Mahasiswa (KTM) aktif.
                        </p>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="flex items-center justify-between border-t border-line bg-slate-50/80 px-6 py-3.5">
                    <button
                        type="button"
                        onclick="closeBookDetailModal()"
                        class="inline-flex h-9 items-center justify-center rounded-xl border border-line bg-white px-4 text-xs font-semibold text-ink hover:bg-subtle transition-colors focus:outline-none focus:ring-2 focus:ring-primary"
                    >
                        Tutup
                    </button>

                    <a
                        id="modal-chat-link"
                        href="{{ route('chat') }}"
                        class="inline-flex h-9 items-center gap-1.5 rounded-xl bg-primary px-4 text-xs font-semibold text-white shadow-xs hover:bg-primary-dark transition-all focus:outline-none focus:ring-2 focus:ring-primary"
                    >
                        <i class="fa-solid fa-headset text-xs"></i>
                        <span>Tanya Pustakawan</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPT INTERAKTIVITAS: BOX FILTER PENCARIAN & MODAL DETAIL BUKU --}}
    <script>
        // =========================================================================
        // 1. KONTROL BOX FILTER PENCARIAN (MODAL SEPERTI GAMBAR 2)
        // =========================================================================
        function openFilterModal() {
            const modal = document.getElementById('search-filter-modal');
            if (modal) {
                modal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
                document.getElementById('modal-input-pengarang')?.focus();
            }
        }

        function closeFilterModal() {
            const modal = document.getElementById('search-filter-modal');
            if (modal) {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        }

        function applyModalFilters(event) {
            event.preventDefault();
            const mainForm = document.getElementById('main-opac-form');
            if (!mainForm) return;

            document.getElementById('hidden-filter-pengarang').value = document.getElementById('modal-input-pengarang').value.trim();
            document.getElementById('hidden-filter-isbn').value = document.getElementById('modal-input-isbn').value.trim();
            document.getElementById('hidden-filter-ddc').value = document.getElementById('modal-input-ddc').value.trim();
            document.getElementById('hidden-filter-nomor-induk').value = document.getElementById('modal-input-nomor-induk').value.trim();
            document.getElementById('hidden-filter-tahun').value = document.getElementById('modal-select-tahun').value;
            document.getElementById('hidden-filter-lokasi').value = document.getElementById('modal-select-lokasi').value;
            document.getElementById('hidden-filter-jenis').value = document.getElementById('modal-select-jenis').value;
            document.getElementById('hidden-filter-prodi').value = document.getElementById('modal-select-prodi').value;

            closeFilterModal();
            mainForm.submit();
        }

        function resetModalFilters() {
            document.getElementById('modal-input-pengarang').value = '';
            document.getElementById('modal-input-isbn').value = '';
            document.getElementById('modal-input-ddc').value = '';
            document.getElementById('modal-input-nomor-induk').value = '';
            document.getElementById('modal-select-tahun').value = 'Semua';
            document.getElementById('modal-select-lokasi').value = 'Semua';
            document.getElementById('modal-select-jenis').value = 'Semua';
            document.getElementById('modal-select-prodi').value = 'Semua';

            document.getElementById('hidden-filter-pengarang').value = '';
            document.getElementById('hidden-filter-isbn').value = '';
            document.getElementById('hidden-filter-ddc').value = '';
            document.getElementById('hidden-filter-nomor-induk').value = '';
            document.getElementById('hidden-filter-tahun').value = 'Semua';
            document.getElementById('hidden-filter-lokasi').value = 'Semua';
            document.getElementById('hidden-filter-jenis').value = 'Semua';
            document.getElementById('hidden-filter-prodi').value = 'Semua';

            const mainForm = document.getElementById('main-opac-form');
            if (mainForm) {
                closeFilterModal();
                mainForm.submit();
            }
        }

        // =========================================================================
        // 2. KONTROL MODAL DETAIL BUKU & PENCATATAN KUNJUNGAN REAL-TIME
        // =========================================================================
        function openBookDetailModal(item) {
            const modal = document.getElementById('book-detail-modal');
            if (!modal || !item) return;

            document.getElementById('modal-book-title').textContent = item.title || '-';
            document.getElementById('modal-author').textContent = item.author || '-';
            document.getElementById('modal-publisher').textContent = (item.publisher || '-') + (item.publisher_city ? ' (' + item.publisher_city + ')' : '');
            document.getElementById('modal-year').textContent = item.year || '-';
            document.getElementById('modal-pages').textContent = (item.edition || '-') + ' / ' + (item.pages || '-');
            document.getElementById('modal-isbn').textContent = item.isbn || '-';
            document.getElementById('modal-call-number').textContent = item.call_number || '-';
            document.getElementById('modal-category').textContent = item.category || 'Buku Teks';
            document.getElementById('modal-format').textContent = item.format || 'Buku Cetak';
            document.getElementById('modal-id').textContent = '#' + (item.id || '');
            document.getElementById('modal-description').textContent = item.description || 'Tidak ada deskripsi rinci.';

            // Stats
            const total = item.copies_total || 0;
            const available = item.copies_available || 0;
            const borrowed = item.copies_borrowed || 0;

            document.getElementById('modal-stat-total').textContent = total;
            document.getElementById('modal-stat-available').textContent = available;
            document.getElementById('modal-stat-borrowed').textContent = borrowed;
            document.getElementById('modal-copy-count').textContent = total + ' eksemplar terdaftar';

            // Subjek tags
            const subContainer = document.getElementById('modal-subjects-container');
            subContainer.innerHTML = '';
            const subjects = item.subjects || [];
            if (subjects.length > 0) {
                subjects.forEach(function (s) {
                    const tag = document.createElement('a');
                    tag.href = '{{ route("opac") }}?fitur=subjek&subjek_id=' + encodeURIComponent(s);
                    tag.className = 'rounded-md bg-subtle px-2 py-0.5 text-[11px] font-medium text-ink-secondary hover:bg-primary/10 hover:text-primary transition-colors';
                    tag.textContent = s;
                    subContainer.appendChild(tag);
                });
            } else {
                subContainer.innerHTML = '<span class="text-xs text-muted">Umum</span>';
            }

            // Cover Image
            const coverImg = document.getElementById('modal-cover-img');
            const placeholder = document.getElementById('modal-cover-placeholder');
            if (item.cover_url) {
                coverImg.src = item.cover_url;
                coverImg.classList.remove('hidden');
                placeholder.classList.add('hidden');
            } else {
                coverImg.classList.add('hidden');
                placeholder.classList.remove('hidden');
                placeholder.classList.add('flex');
            }

            // Chat link
            const chatLink = document.getElementById('modal-chat-link');
            if (chatLink) {
                chatLink.href = '{{ route("chat") }}?q=' + encodeURIComponent('Halo Pustakawan, saya ingin reservasi/cek buku: ' + item.title);
            }

            // Exemplar table (Nomor Induk, Call Number, Lokasi, Status)
            const tbody = document.getElementById('modal-exemplar-tbody');
            tbody.innerHTML = '';

            const eksemplarList = item.eksemplar_list || [];
            if (eksemplarList.length === 0) {
                const tr = document.createElement('tr');
                tr.innerHTML = '<td colspan="5" class="px-4 py-6 text-center text-muted text-xs">Belum ada eksemplar fisik tercatat untuk koleksi ini.</td>';
                tbody.appendChild(tr);
            } else {
                eksemplarList.forEach(function (ek, idx) {
                    const tr = document.createElement('tr');
                    tr.className = 'hover:bg-slate-50 transition-colors';

                    let statusBadge = '<span class="inline-flex items-center rounded-full bg-emerald-50 px-2 py-0.5 text-[11px] font-semibold text-emerald-700 border border-emerald-200">Tersedia</span>';
                    if (ek.status === 'Dipinjam') {
                        statusBadge = '<span class="inline-flex items-center rounded-full bg-amber-50 px-2 py-0.5 text-[11px] font-semibold text-amber-700 border border-amber-200">Dipinjam</span>';
                    } else if (ek.status === 'Rusak' || ek.status === 'Perbaikan') {
                        statusBadge = '<span class="inline-flex items-center rounded-full bg-rose-50 px-2 py-0.5 text-[11px] font-semibold text-rose-700 border border-rose-200">Perbaikan</span>';
                    } else if (ek.status === 'Hilang') {
                        statusBadge = '<span class="inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-[11px] font-semibold text-slate-700 border border-slate-200">Hilang</span>';
                    }

                    tr.innerHTML = `
                        <td class="px-3.5 py-3 text-muted font-mono">${idx + 1}</td>
                        <td class="px-3.5 py-3 font-mono font-medium text-ink">${ek.kode_inventaris || '-'}</td>
                        <td class="px-3.5 py-3 font-mono text-primary">${ek.nomor_panggil || '-'}</td>
                        <td class="px-3.5 py-3 text-ink-secondary">${ek.lokasi_rak || '-'}</td>
                        <td class="px-3.5 py-3 text-center whitespace-nowrap">${statusBadge}</td>
                    `;
                    tbody.appendChild(tr);
                });
            }

            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');

            // Catat kunjungan penelusuran buku secara otomatis via AJAX
            if (item.id) {
                fetch('/opac/kunjungan/' + encodeURIComponent(item.id), {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                }).catch(() => {});
            }
        }

        function closeBookDetailModal() {
            const modal = document.getElementById('book-detail-modal');
            if (modal) {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        }

        const allCatalogItems = @json($items);

        function focusToSearchBar() {
            const input = document.getElementById('opac-search-input');
            if (input) {
                input.scrollIntoView({ behavior: 'smooth', block: 'center' });
                input.focus();
                input.select();
            }
        }

        function clearSearchInput() {
            const input = document.getElementById('opac-search-input');
            if (input) {
                input.value = '';
                input.focus();
            }
        }
        // =========================================================================
        // 3. EVENT LISTENER KEYBOARD (ESCAPE), CLICK OUTSIDE, & AUTO-OPEN MODAL
        // =========================================================================
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeFilterModal();
                closeBookDetailModal();
            }
        });

        document.getElementById('search-filter-modal')?.addEventListener('click', function (e) {
            if (e.target === this) {
                closeFilterModal();
            }
        });

        document.getElementById('book-detail-modal')?.addEventListener('click', function (e) {
            if (e.target === this) {
                closeBookDetailModal();
            }
        });

        // =========================================================================
        // 4. SWITCHER TAMPILAN KATALOG (GRID / ROW) DENGAN DEFAULT GRID
        // =========================================================================
        function setCatalogView(view) {
            const container = document.getElementById('catalog-container');
            const btnGrid = document.getElementById('btn-view-grid');
            const btnRow = document.getElementById('btn-view-row');
            if (!container || !btnGrid || !btnRow) return;

            const isRow = view === 'row';
            container.setAttribute('data-view', isRow ? 'row' : 'grid');

            if (isRow) {
                container.className = 'flex flex-col gap-4';

                document.querySelectorAll('.card-inner-layout').forEach(el => {
                    el.className = 'card-inner-layout flex flex-col sm:flex-row gap-5 p-5';
                });
                document.querySelectorAll('.card-cover-wrapper').forEach(el => {
                    el.className = 'card-cover-wrapper relative w-28 sm:w-36 aspect-[3/4] shrink-0 overflow-hidden rounded-xl border border-line bg-slate-100 shadow-xs mx-auto sm:mx-0';
                });
                document.querySelectorAll('.card-details-wrapper').forEach(el => {
                    el.className = 'card-details-wrapper p-0 flex flex-col flex-1 justify-between';
                });
                document.querySelectorAll('.row-only-header').forEach(el => el.classList.remove('hidden'));
                document.querySelectorAll('.row-only-desc').forEach(el => el.classList.remove('hidden'));
                document.querySelectorAll('.grid-only-badge').forEach(el => el.classList.add('hidden'));

                btnRow.className = 'inline-flex min-h-[34px] items-center gap-1.5 rounded-lg px-3 py-1 text-xs font-semibold bg-primary text-white shadow-xs transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-primary/20';
                btnGrid.className = 'inline-flex min-h-[34px] items-center gap-1.5 rounded-lg px-3 py-1 text-xs font-medium text-muted hover:text-ink hover:bg-slate-50 transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-primary/20';

                localStorage.setItem('mncu_opac_view', 'row');
            } else {
                container.className = 'grid grid-cols-1 gap-5 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4';

                document.querySelectorAll('.card-inner-layout').forEach(el => {
                    el.className = 'card-inner-layout flex flex-col h-full';
                });
                document.querySelectorAll('.card-cover-wrapper').forEach(el => {
                    el.className = 'card-cover-wrapper relative w-full aspect-[3/4] shrink-0 overflow-hidden bg-slate-100 border-b border-line/60';
                });
                document.querySelectorAll('.card-details-wrapper').forEach(el => {
                    el.className = 'card-details-wrapper p-4 flex flex-col flex-1 justify-between gap-3';
                });
                document.querySelectorAll('.row-only-header').forEach(el => el.classList.add('hidden'));
                document.querySelectorAll('.row-only-desc').forEach(el => el.classList.add('hidden'));
                document.querySelectorAll('.grid-only-badge').forEach(el => el.classList.remove('hidden'));

                btnGrid.className = 'inline-flex min-h-[34px] items-center gap-1.5 rounded-lg px-3 py-1 text-xs font-semibold bg-primary text-white shadow-xs transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-primary/20';
                btnRow.className = 'inline-flex min-h-[34px] items-center gap-1.5 rounded-lg px-3 py-1 text-xs font-medium text-muted hover:text-ink hover:bg-slate-50 transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-primary/20';

                localStorage.setItem('mncu_opac_view', 'grid');
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Inisialisasi tampilan katalog dengan default 'grid'
            const savedView = localStorage.getItem('mncu_opac_view') || 'grid';
            setCatalogView(savedView);

            const urlParams = new URLSearchParams(window.location.search);
            const targetId = '{{ $selectedBookId ?? '' }}' || urlParams.get('buku');
            if (targetId && Array.isArray(allCatalogItems) && allCatalogItems.length > 0) {
                const foundItem = allCatalogItems.find(item => String(item.id) === String(targetId));
                if (foundItem) {
                    openBookDetailModal(foundItem);
                }
            }
        });
    </script>
@endsection
