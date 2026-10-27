@extends('layouts.app')

@section('title', 'Katalog OPAC')
@section('description', 'Katalog Online Perpustakaan MNC University (OPAC). Cari buku teks, monograf, buku referensi, dan skripsi fisik dengan informasi ketersediaan jumlah stok eksemplar dan nomor panggil rak secara real-time.')

@section('content')
    <div class="mx-auto flex w-full max-w-[1570px] flex-1 flex-col gap-6 px-3 py-6 sm:px-4 lg:px-6 xl:px-8 lg:py-8 responsive-desktop-container">
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
                    <div class="flex flex-wrap items-center gap-2">
                        <h1 class="text-2xl font-bold tracking-tight text-primary sm:text-3xl lg:text-4xl">
                            Katalog OPAC Perpustakaan
                        </h1>
                    </div>
                    <p class="mt-1 text-xs text-ink-secondary sm:text-sm lg:text-base leading-relaxed">
                        Pencarian koleksi fisik, buku teks perkuliahan, dan referensi tercetak MNC University lengkap dengan status ketersediaan stok eksemplar real-time.
                    </p>
                </div>

                @if (Auth::check() && (Auth::user()->isSuperAdmin() || Auth::user()->isPustakawan()))
                    {{-- Tombol Aksi Petugas Perpustakaan: Manajemen Member, Statistik, dan Report Sesuai Flowchart --}}
                    <div class="flex flex-wrap items-center gap-2 text-xs">
                        {{-- 1. Manajemen Data Member --}}
                        <a
                            href="{{ route('admin.users.index') }}"
                            class="btn-press btn-sheen inline-flex min-h-[40px] items-center gap-2 rounded-xl bg-emerald-600 px-3.5 sm:px-4 py-2 font-semibold text-white shadow-xs transition-all hover:bg-emerald-700 active:scale-95"
                            title="Buka Manajemen Data Member Perpustakaan"
                        >
                            <i class="fa-solid fa-id-card text-xs" aria-hidden="true"></i>
                            <span>Manajemen Data Member</span>
                        </a>

                        {{-- 2. Statistik --}}
                        <a
                            href="{{ route('admin.statistics.index') }}"
                            class="btn-press btn-sheen inline-flex min-h-[40px] items-center gap-2 rounded-xl bg-indigo-600 px-3.5 sm:px-4 py-2 font-semibold text-white shadow-xs transition-all hover:bg-indigo-700 active:scale-95"
                            title="Buka Statistik Perpustakaan (Sesuai Flowchart)"
                        >
                            <i class="fa-solid fa-chart-simple text-xs" aria-hidden="true"></i>
                            <span>Statistik</span>
                        </a>

                        {{-- 3. Report --}}
                        <a
                            href="{{ route('admin.reports.index') }}"
                            class="btn-press btn-sheen inline-flex min-h-[40px] items-center gap-2 rounded-xl bg-primary px-3.5 sm:px-4 py-2 font-semibold text-white shadow-xs transition-all hover:bg-primary-dark active:scale-95"
                            title="Buka Laporan & Rekapitulasi Perpustakaan (Sesuai Flowchart)"
                        >
                            <i class="fa-solid fa-file-lines text-xs" aria-hidden="true"></i>
                            <span>Report</span>
                        </a>
                    </div>
                @endif
            </div>
        </div>

        {{-- NOTIFIKASI PERINGATAN KETERLAMBATAN PENGEMBALIAN BUKU SESUAI FLOWCHART --}}
        @if (! empty($userOverdueLoans) && $userOverdueLoans->isNotEmpty())
            <div class="rounded-2xl border border-rose-300 bg-rose-50/90 p-4 text-xs text-rose-950 shadow-xs sm:px-6 animate-in fade-in duration-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div class="flex items-start gap-3">
                        <span class="flex size-9 shrink-0 items-center justify-center rounded-xl bg-rose-600 text-white shadow-xs">
                            <i class="fa-solid fa-triangle-exclamation text-sm"></i>
                        </span>
                        <div>
                            <div class="font-bold text-rose-900 text-sm flex items-center gap-2">
                                <span>Notifikasi Peringatan: Batas Waktu Pengembalian Telah Lewat!</span>
                                <span class="rounded bg-rose-200 px-2 py-0.5 text-[10px] font-bold text-rose-900">{{ $userOverdueLoans->count() }} Buku Terlambat</span>
                            </div>
                            <p class="text-[11px] text-rose-800 mt-0.5 leading-relaxed">
                                Peminjaman Anda telah melewati batas jadwal jatuh tempo pengembalian buku. Harap segera lakukan pengembalian buku mandiri via tombol kembalikan di bawah atau ke meja sirkulasi perpustakaan.
                            </p>
                            <div class="mt-2 flex flex-wrap gap-2 text-[10px]">
                                @foreach ($userOverdueLoans as $ov)
                                    <span class="inline-flex items-center gap-1.5 rounded-lg border border-rose-200 bg-white/95 px-2.5 py-1 font-semibold text-rose-900 shadow-2xs">
                                        <i class="fa-solid fa-book-bookmark text-rose-600 text-[9px]"></i>
                                        <span class="max-w-[220px] truncate">{{ $ov->buku->judul_buku }}</span>
                                        <span class="text-rose-700 font-mono font-bold">(Terlambat {{ $ov->hitungHariTerlambat() }} hari)</span>
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

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
                <input type="hidden" name="status_filter" id="hidden-filter-status" value="{{ $statusFilter }}">

                {{-- Baris Utama: Search Bar (Point 1) & Sort By (Point 2) & Tombol Box Filter --}}
                <div class="grid grid-cols-1 gap-3 md:grid-cols-12">
                    {{-- POINT 1: Search Bar Sederhana dengan Kriteria Tertentu --}}
                    <div class="md:col-span-12 lg:col-span-6 xl:col-span-7 flex flex-col sm:flex-row gap-2">
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
                                <option value="peminjam" @selected($kriteria === 'peminjam')>Nama Peminjam</option>
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
                    <div class="md:col-span-6 lg:col-span-3 xl:col-span-3">
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
                    <div class="md:col-span-6 lg:col-span-3 xl:col-span-2 flex items-center gap-2">
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

                        @if ($statusFilter !== 'Semua' && ! empty($statusFilter))
                            <span class="inline-flex items-center gap-1 rounded-lg bg-amber-100 px-2.5 py-1 text-[11px] font-semibold text-amber-900 border border-amber-200">
                                Status: {{ $statusFilter }}
                                <a href="{{ route('opac', array_merge(request()->except('status_filter', 'status'), ['fitur' => $fitur])) }}" class="ml-1 text-amber-700 hover:text-amber-950"><i class="fa-solid fa-xmark"></i></a>
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
                {{-- Container Katalog: Grid Responsif Kompak Sesuai Tampilan Admin --}}
                <div id="catalog-container" class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-6 2xl:grid-cols-6 opac-catalog-grid" data-view="grid">
                    @foreach ($items as $item)
                        @php
                            $available = $item['copies_available'] ?? 0;
                            $total = $item['copies_total'] ?? 0;
                        @endphp
                        <article
                            tabindex="0"
                            onclick="openBookDetailModal({{ json_encode($item) }})"
                            onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault();openBookDetailModal({{ json_encode($item) }});}"
                            class="catalog-book-card group relative flex flex-col justify-between rounded-xl border border-line bg-white shadow-xs transition-all duration-200 hover:-translate-y-1 hover:border-primary/50 hover:shadow-md cursor-pointer focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 overflow-hidden"
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
                                    <div class="card-cover-fallback {{ ! empty($item['cover_url']) ? 'hidden' : '' }} flex size-full flex-col justify-between bg-gradient-to-br from-[#0F2A6B] via-[#143A8F] to-[#0A1E4F] p-3 text-white">
                                        <div class="flex items-center justify-between opacity-80">
                                            <span class="text-[9px] font-bold uppercase tracking-wider text-blue-200">MNC University</span>
                                            <i class="fa-solid fa-book-bookmark text-xs text-blue-200" aria-hidden="true"></i>
                                        </div>
                                        <div class="my-auto py-1">
                                            <h4 class="text-xs font-bold leading-tight line-clamp-3 text-white">
                                                {{ $item['title'] }}
                                            </h4>
                                            <p class="mt-1 text-[10px] text-blue-200 line-clamp-1">
                                                {{ $item['author'] }}
                                            </p>
                                        </div>
                                        <div class="flex items-center justify-between border-t border-white/20 pt-1.5 text-[9px] text-blue-200">
                                            <span class="truncate max-w-[60%]">{{ $item['category'] ?? 'Buku Cetak' }}</span>
                                            <span>{{ $item['year'] ?? 2024 }}</span>
                                        </div>
                                    </div>

                                    {{-- Floating Badges pada Mode Grid: Persis Seperti Admin (Kategori & ID di atas, Stok & Call Number di bawah) --}}
                                    <div class="grid-only-badge pointer-events-none absolute inset-x-2 top-2 flex items-center justify-between gap-1">
                                        <span class="inline-flex items-center rounded bg-white/95 px-1.5 py-0.5 text-[9px] font-semibold text-primary shadow-xs backdrop-blur border border-line/60 truncate max-w-[65%]">
                                            {{ $item['category'] ?? 'Buku Cetak' }}
                                        </span>
                                        <span class="inline-flex items-center rounded bg-slate-900/80 px-1.5 py-0.5 font-mono text-[9px] font-medium text-white shadow-xs backdrop-blur shrink-0">
                                            #{{ $item['id'] }}
                                        </span>
                                    </div>

                                    <div class="grid-only-badge pointer-events-none absolute inset-x-2 bottom-2 flex items-center justify-between gap-1">
                                        @if ($total === 0)
                                            <span class="inline-flex items-center gap-1 rounded bg-slate-900/80 px-1.5 py-0.5 text-[9px] font-semibold text-slate-200 backdrop-blur-xs">
                                                Digital
                                            </span>
                                        @elseif ($available > 0)
                                            <span class="inline-flex items-center gap-1 rounded bg-emerald-700/90 px-1.5 py-0.5 text-[9px] font-bold text-white backdrop-blur-xs shadow-xs">
                                                <span class="size-1.5 rounded-full bg-emerald-300 animate-pulse"></span>
                                                <span>{{ $available }}/{{ $total }} Eks</span>
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 rounded bg-amber-700/90 px-1.5 py-0.5 text-[9px] font-bold text-white backdrop-blur-xs shadow-xs">
                                                <span>Dipinjam</span>
                                            </span>
                                        @endif

                                        @if (! empty($item['call_number']))
                                            <span class="inline-flex items-center gap-1 rounded bg-black/75 px-1.5 py-0.5 font-mono text-[9px] font-bold text-amber-300 backdrop-blur-xs shadow-xs truncate max-w-[50%]" title="Nomor Panggil: {{ $item['call_number'] }}">
                                                <i class="fa-solid fa-bookmark text-[8px]" aria-hidden="true"></i>
                                                <span class="truncate">{{ $item['call_number'] }}</span>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                {{-- 2. Wadah Rincian Informasi Buku (Sama Persis Format & Sizing dengan Admin) --}}
                                <div class="card-details-wrapper p-3 flex flex-col flex-1 justify-between">
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

                                        {{-- Kategori & Tahun --}}
                                        <div class="flex items-center justify-between text-[10px] text-muted">
                                            <span class="font-medium text-primary truncate max-w-[65%]">{{ $item['category'] ?? 'Umum' }}</span>
                                            <span>{{ $item['year'] ?? '-' }}</span>
                                        </div>

                                        {{-- Judul Buku --}}
                                        <h3 class="mt-1 text-xs sm:text-sm font-bold text-ink transition-colors group-hover:text-primary leading-snug line-clamp-2" title="{{ $item['title'] }}">
                                            {{ $item['title'] }}
                                        </h3>

                                        {{-- Pengarang, Penerbit & Lokasi Rak --}}
                                        <div class="mt-1.5 space-y-0.5 text-[10px] text-muted">
                                            <p class="truncate" title="Penulis: {{ $item['author'] }}">
                                                <i class="fa-solid fa-user-pen mr-1 text-[9px] text-primary/70"></i>
                                                <span>{{ $item['author'] ?: 'Tim Perpustakaan' }}</span>
                                            </p>
                                            <p class="truncate" title="Penerbit: {{ $item['publisher'] }}">
                                                <i class="fa-solid fa-building-columns mr-1 text-[9px] text-primary/70"></i>
                                                <span>{{ $item['publisher'] ?: 'Penerbit MNCU' }}</span>
                                            </p>
                                            @if (! empty($item['location']))
                                                <p class="truncate text-[10px] text-ink-secondary" title="Lokasi Rak: {{ $item['location'] }}">
                                                    <i class="fa-solid fa-location-dot mr-1 text-[9px] text-primary/70"></i>
                                                    <span>{{ $item['location'] }}</span>
                                                </p>
                                            @endif
                                        </div>

                                        {{-- Deskripsi Khusus Mode Row / Baris --}}
                                        <p class="row-only-desc hidden mt-2 text-xs text-muted line-clamp-2 leading-relaxed">
                                            {{ $item['description'] }}
                                        </p>

                                        {{-- Info Peminjam Khusus Mode Row / Baris --}}
                                        @if (! empty($item['is_borrowed']) && ! empty($item['borrower_names']))
                                            <div class="row-only-desc hidden mt-2 flex items-start gap-1.5 rounded-lg border border-amber-200 bg-amber-50/80 p-1.5 text-[10px] text-amber-900">
                                                <i class="fa-solid fa-user-clock text-amber-600 mt-0.5 text-[10px] shrink-0"></i>
                                                <div class="min-w-0">
                                                    <span class="font-semibold text-amber-950">Sedang Dipinjam:</span>
                                                    <span class="font-medium text-amber-900 line-clamp-1" title="{{ $item['borrower_names'] }}">{{ $item['borrower_names'] }}</span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Footer Kartu: ID & Tombol Aksi Kompak Sesuai Admin --}}
                                    <div class="mt-3 flex items-center justify-between border-t border-line pt-2.5 text-[10px] gap-1">
                                        <span class="font-mono text-[10px] text-muted truncate max-w-[50px]">ID: {{ $item['id'] }}</span>
                                        <div class="flex items-center gap-1 shrink-0">
                                            @if (! empty($item['is_borrowed_by_user']))
                                                @if (! empty($item['user_active_loan']['is_menunggu_verifikasi']))
                                                    <span class="inline-flex h-7 items-center gap-1 rounded-lg bg-amber-50 px-2 text-[10px] font-semibold text-amber-800 border border-amber-200" title="Pengajuan pengembalian buku sedang menunggu persetujuan admin">
                                                        <i class="fa-solid fa-clock-rotate-left text-[9px]"></i>
                                                        <span>Menunggu</span>
                                                    </span>
                                                @else
                                                    <button
                                                        type="button"
                                                        onclick="event.stopPropagation(); initiateReturnBook({{ json_encode($item) }})"
                                                        class="btn-press inline-flex h-7 items-center justify-center gap-1 rounded-lg bg-indigo-600 px-2 text-[10px] font-semibold text-white shadow-xs hover:bg-indigo-700 transition-colors"
                                                        title="Kembalikan buku yang sedang Anda pinjam"
                                                    >
                                                        <i class="fa-solid fa-arrow-rotate-left text-[9px]" aria-hidden="true"></i>
                                                        <span>Kembalikan</span>
                                                    </button>
                                                @endif
                                            @elseif ($total === 0)
                                                <span class="inline-flex h-7 items-center gap-1 rounded-lg bg-slate-100 px-2 text-[10px] font-semibold text-slate-500">
                                                    Digital
                                                </span>
                                            @elseif ($available > 0)
                                                <button
                                                    type="button"
                                                    onclick="event.stopPropagation(); initiateBorrowBook({{ json_encode($item) }})"
                                                    class="btn-press inline-flex h-7 items-center justify-center gap-1 rounded-lg bg-emerald-600 px-2 text-[10px] font-semibold text-white shadow-xs hover:bg-emerald-700 transition-colors"
                                                    title="Pinjam buku ini via website"
                                                >
                                                    <i class="fa-solid fa-hand-holding-hand text-[9px]" aria-hidden="true"></i>
                                                    <span>Pinjam</span>
                                                </button>
                                            @else
                                                <span class="inline-flex h-7 items-center gap-1 rounded-lg bg-amber-50 px-2 text-[10px] font-semibold text-amber-800 border border-amber-200" title="Buku sedang dipinjam pemustaka lain">
                                                    Dipinjam
                                                </span>
                                            @endif
                                            <button
                                                type="button"
                                                onclick="event.stopPropagation(); openBookDetailModal({{ json_encode($item) }})"
                                                class="inline-flex h-7 items-center justify-center gap-1 rounded-lg border border-line bg-white px-2 text-[10px] font-semibold text-ink shadow-xs hover:border-primary hover:text-primary transition-colors"
                                                title="Lihat rincian lengkap"
                                            >
                                                <i class="fa-solid fa-circle-info text-[9px]" aria-hidden="true"></i>
                                                <span class="hidden sm:inline">Detail</span>
                                            </button>
                                        </div>
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
        class="fixed inset-0 z-[99999] hidden overflow-y-auto bg-slate-900/60 backdrop-blur-xs transition-opacity duration-200"
        role="dialog"
        aria-modal="true"
        aria-labelledby="modal-filter-title"
    >
        <div class="flex min-h-full items-center justify-center p-3 sm:p-6 py-6 sm:py-10">
            <div class="relative w-full max-w-3xl overflow-hidden rounded-2xl border border-line bg-white shadow-2xl transition-all my-auto flex flex-col max-h-[90vh]">
                {{-- Header Box Filter: Icon Slider Ungu + Judul "Filter Pencarian" + Tombol X --}}
                <div class="sticky top-0 z-10 flex shrink-0 items-center justify-between border-b border-line bg-white px-6 py-4">
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

                        {{-- Field 9: Status Ketersediaan / Sedang Dipinjam --}}
                        <div class="sm:col-span-2">
                            <label for="modal-select-status" class="mb-1.5 block text-xs font-semibold text-ink">
                                Status Ketersediaan
                            </label>
                            <select
                                id="modal-select-status"
                                class="h-10 w-full rounded-xl border border-line bg-white px-3 text-xs text-ink focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary"
                            >
                                <option value="Semua">Semua Status</option>
                                <option value="Tersedia" @selected($statusFilter == 'Tersedia')>Tersedia di Rak (Siap Pinjam)</option>
                                <option value="Dipinjam" @selected($statusFilter == 'Dipinjam')>Sedang Dipinjam</option>
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
        class="fixed inset-0 z-[99999] hidden overflow-y-auto bg-slate-900/60 backdrop-blur-xs transition-opacity duration-200"
        role="dialog"
        aria-modal="true"
        aria-labelledby="modal-book-title"
    >
        <div class="flex min-h-full items-center justify-center p-3 sm:p-6 py-6 sm:py-10">
            <div class="relative w-full max-w-3xl overflow-hidden rounded-2xl border border-line bg-white shadow-2xl transition-all my-auto flex flex-col max-h-[88vh]">
                {{-- Header Detail Buku --}}
                <div class="sticky top-0 z-10 flex shrink-0 items-center justify-between border-b border-line bg-slate-50/95 backdrop-blur-xs px-5 py-4 sm:px-6">
                    <div class="flex items-center gap-2.5">
                        <span class="flex size-8 items-center justify-center rounded-lg bg-primary/10 text-primary">
                            <i class="fa-solid fa-book-open text-sm"></i>
                        </span>
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-sm font-bold text-ink">Detail Informasi Buku &amp; Ketersediaan Stok</h3>
                                @if (Auth::check() && (Auth::user()->isSuperAdmin() || Auth::user()->isPustakawan()))
                                    <span class="inline-flex items-center gap-1 rounded bg-purple-100 px-1.5 py-0.5 text-[10px] font-bold text-purple-700 border border-purple-200">
                                        <i class="fa-solid fa-shield-halved text-[9px]"></i> {{ Auth::user()->isSuperAdmin() ? 'Mode Super Admin' : 'Mode Pustakawan' }}
                                    </span>
                                @endif
                            </div>
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
                <div class="flex-1 overflow-y-auto p-5 sm:p-6 space-y-6">
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
                                        <th class="px-3.5 py-2.5">Peminjam Saat Ini</th>
                                    </tr>
                                </thead>
                                <tbody id="modal-exemplar-tbody" class="divide-y divide-line text-ink">
                                    {{-- Diisi secara dinamis via JavaScript --}}
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Banner Khusus Pemustaka yang Sedang Meminjam Buku Ini --}}
                    <div id="modal-user-loan-banner" class="hidden rounded-xl border p-4 text-xs">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-start gap-2.5">
                                <div id="modal-user-loan-icon-box" class="flex size-8 shrink-0 items-center justify-center rounded-lg">
                                    <i id="modal-user-loan-icon" class="fa-solid fa-book-bookmark text-sm"></i>
                                </div>
                                <div>
                                    <h4 id="modal-user-loan-title" class="font-bold text-sm"></h4>
                                    <p id="modal-user-loan-desc" class="mt-0.5 leading-relaxed"></p>
                                    <div id="modal-user-loan-meta" class="mt-2 flex flex-wrap items-center gap-2 text-[11px]"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Container Informasi Peminjam Aktif (Buku Sedang Dipinjam) --}}
                    <div id="modal-active-loans-box" class="hidden rounded-xl border border-amber-200 bg-amber-50/70 p-4">
                        <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-amber-900 mb-2.5">
                            <i class="fa-solid fa-users text-amber-600"></i>
                            <span>Daftar Pemustaka yang Sedang Meminjam Buku Ini</span>
                        </div>
                        <div id="modal-active-loans-list" class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                            {{-- Diisi secara dinamis via JavaScript --}}
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
                <div class="sticky bottom-0 z-10 flex shrink-0 flex-wrap items-center justify-between gap-2.5 border-t border-line bg-slate-50/95 backdrop-blur-xs px-5 py-3.5 sm:px-6">
                    <button
                        type="button"
                        onclick="closeBookDetailModal()"
                        class="inline-flex h-9 items-center justify-center rounded-xl border border-line bg-white px-4 text-xs font-semibold text-ink hover:bg-subtle transition-colors focus:outline-none focus:ring-2 focus:ring-primary"
                    >
                        Tutup
                    </button>

                    <div class="flex flex-wrap items-center gap-2">
                        @if (Auth::check() && (Auth::user()->isSuperAdmin() || Auth::user()->isPustakawan()))
                            {{-- Aksi Tambahan Khusus Administrator & Pustakawan --}}
                            <a
                                id="modal-admin-manage-link"
                                href="#"
                                class="inline-flex h-9 items-center gap-1.5 rounded-xl border border-line bg-white px-3.5 text-xs font-semibold text-ink hover:bg-subtle hover:text-primary transition-all focus:outline-none focus:ring-2 focus:ring-primary"
                                title="Kelola Eksemplar & Stok Fisik di Panel Admin"
                            >
                                <i class="fa-solid fa-boxes-stacked text-xs text-primary"></i>
                                <span>Kelola Stok</span>
                            </a>
                            <a
                                id="modal-admin-edit-link"
                                href="#"
                                class="inline-flex h-9 items-center gap-1.5 rounded-xl bg-amber-500 px-3.5 text-xs font-semibold text-white shadow-xs hover:bg-amber-600 transition-all focus:outline-none focus:ring-2 focus:ring-amber-400"
                                title="Ubah Data Buku di Panel Admin"
                            >
                                <i class="fa-regular fa-pen-to-square text-xs"></i>
                                <span>Ubah Buku</span>
                            </a>
                        @endif

                        {{-- Tombol Kembalikan Buku (Khusus Pemustaka yang Sedang Meminjam Buku Ini) --}}
                        <button
                            type="button"
                            id="modal-detail-return-btn"
                            onclick="initiateReturnBookFromDetail()"
                            class="hidden inline-flex h-9 items-center gap-1.5 rounded-xl bg-indigo-600 px-4 text-xs font-semibold text-white shadow-xs hover:bg-indigo-700 transition-all focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            title="Kembalikan buku yang sedang Anda pinjam"
                        >
                            <i class="fa-solid fa-arrow-rotate-left text-xs" aria-hidden="true"></i>
                            <span id="modal-detail-return-text">Kembalikan Buku</span>
                        </button>

                        {{-- Tombol Pinjam Buku (Untuk Pemustaka yang Tidak Sedang Meminjam) --}}
                        <button
                            type="button"
                            id="modal-detail-borrow-btn"
                            onclick="openBorrowModalFromDetail()"
                            class="inline-flex h-9 items-center gap-1.5 rounded-xl bg-emerald-600 px-4 text-xs font-semibold text-white shadow-xs hover:bg-emerald-700 transition-all focus:outline-none focus:ring-2 focus:ring-emerald-500"
                            title="Pinjam buku ini via website katalog OPAC"
                        >
                            <i class="fa-solid fa-hand-holding-hand text-xs" aria-hidden="true"></i>
                            <span>Pinjam Buku Ini</span>
                        </button>

                        <a
                            id="modal-chat-link"
                            href="{{ route('chat') }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex h-9 items-center gap-1.5 rounded-xl bg-primary px-4 text-xs font-semibold text-white shadow-xs hover:bg-primary-dark transition-all focus:outline-none focus:ring-2 focus:ring-primary"
                        >
                            <i class="fa-solid fa-headset text-xs"></i>
                            <span>Tanya Pustakawan</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- =========================================================================
         MODAL PEMINJAMAN BUKU MANDIRI (ALUR FLOWCHART SANCTUM & VALIDASI ELIGIBILITAS)
         ========================================================================= --}}
    <div
        id="borrow-modal"
        class="fixed inset-0 z-50 hidden flex items-center justify-center p-3 sm:p-4 bg-slate-950/60 backdrop-blur-sm overflow-y-auto"
        role="dialog"
        aria-modal="true"
        aria-labelledby="borrow-modal-title"
    >
        <div class="relative w-full max-w-xl overflow-hidden rounded-2xl border border-line bg-white shadow-2xl transition-all my-auto animate-in fade-in zoom-in-95 duration-200">
            {{-- Header Modal --}}
            <div class="flex items-center justify-between border-b border-line bg-slate-50/90 px-5 py-4 sm:px-6">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="flex size-10 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700 shrink-0">
                        <i class="fa-solid fa-hand-holding-hand text-base" aria-hidden="true"></i>
                    </div>
                    <div class="min-w-0">
                        <h3 id="borrow-modal-title" class="text-sm font-bold text-ink truncate">
                            Peminjaman Buku Mandiri (OPAC)
                        </h3>
                        <p class="text-[11px] text-muted truncate">
                            Validasi eligibilitas akun pemustaka & reservasi buku
                        </p>
                    </div>
                </div>
                <button
                    type="button"
                    onclick="closeBorrowModal()"
                    class="flex size-8 items-center justify-center rounded-lg text-muted hover:bg-slate-200/70 hover:text-ink transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-500 shrink-0"
                    aria-label="Tutup modal peminjaman"
                >
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            {{-- Body Modal --}}
            <div class="p-5 sm:p-6 max-h-[calc(85vh-120px)] overflow-y-auto space-y-4">
                {{-- State 1: Loading & Validasi Sanctum --}}
                <div id="borrow-state-loading" class="flex flex-col items-center justify-center py-10 text-center">
                    <div class="flex size-12 items-center justify-center rounded-full bg-emerald-50 text-emerald-600 mb-3 animate-spin">
                        <i class="fa-solid fa-circle-notch text-2xl"></i>
                    </div>
                    <h4 class="text-sm font-bold text-ink">Memeriksa Eligibilitas Pemustaka...</h4>
                    <p class="mt-1 max-w-sm text-xs text-muted leading-relaxed">
                        Sistem sedang memvalidasi keanggotaan dan memeriksa apakah ada buku aktif yang belum dikembalikan sesuai ketentuan perpustakaan.
                    </p>
                </div>

                {{-- State 2: Belum Login (Guest) --}}
                <div id="borrow-state-auth-required" class="hidden flex-col items-center justify-center py-6 text-center">
                    <div class="flex size-14 items-center justify-center rounded-2xl bg-amber-100 text-amber-700 text-2xl mb-3 shadow-xs">
                        <i class="fa-solid fa-user-lock"></i>
                    </div>
                    <h4 class="text-base font-bold text-ink">Silakan Masuk Terlebih Dahulu</h4>
                    <p class="mt-1.5 max-w-md text-xs text-muted leading-relaxed">
                        Untuk meminjam buku secara mandiri melalui website katalog OPAC, Anda harus masuk ke akun pemustaka (Mahasiswa / Dosen) MNC University yang telah terdaftar sebagai member resmi.
                    </p>
                    <div class="mt-5 flex flex-wrap items-center justify-center gap-2.5">
                        <button
                            type="button"
                            onclick="closeBorrowModal()"
                            class="inline-flex h-9 items-center justify-center rounded-xl border border-line bg-white px-4 text-xs font-medium text-ink hover:bg-subtle transition-colors"
                        >
                            Batal
                        </button>
                        <a
                            href="{{ route('login') }}"
                            class="inline-flex h-9 items-center justify-center gap-1.5 rounded-xl bg-primary px-4 text-xs font-semibold text-white shadow-xs hover:bg-primary-dark transition-colors"
                        >
                            <i class="fa-solid fa-arrow-right-to-bracket text-xs"></i>
                            <span>Masuk / Login Akun</span>
                        </a>
                    </div>
                </div>

                {{-- State 3: User Tidak Bisa Meminjam Buku (Blocked: Ada Buku Belum Dikembalikan atau Bukan Member) --}}
                <div id="borrow-state-blocked" class="hidden space-y-4">
                    <div class="rounded-xl border border-rose-200 bg-rose-50/90 p-4 text-xs text-rose-950 flex items-start gap-3">
                        <div class="flex size-9 items-center justify-center rounded-xl bg-rose-100 text-rose-700 shrink-0 mt-0.5">
                            <i class="fa-solid fa-circle-xmark text-lg"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <h4 class="text-sm font-bold text-rose-900">User Tidak Bisa Meminjam Buku!</h4>
                            <p id="borrow-blocked-message" class="mt-1 text-xs text-rose-800 leading-relaxed font-medium">
                                Anda masih memiliki buku pinjaman yang belum dikembalikan. Sesuai alur sirkulasi perpustakaan, Anda harus mengembalikan buku yang sedang dipinjam terlebih dahulu sebelum dapat meminjam koleksi baru.
                            </p>
                        </div>
                    </div>

                    {{-- Daftar Buku yang Belum Dikembalikan --}}
                    <div id="borrow-blocked-loans-container" class="rounded-xl border border-amber-200 bg-amber-50/70 p-3.5 text-xs">
                        <div class="flex items-center gap-1.5 text-xs font-bold text-amber-950 mb-2">
                            <i class="fa-solid fa-clock-rotate-left text-amber-700"></i>
                            <span>Buku yang Sedang Dipinjam & Belum Dikembalikan:</span>
                        </div>
                        <div id="borrow-blocked-loans-list" class="space-y-2">
                            {{-- Diisi secara dinamis via JavaScript --}}
                        </div>
                    </div>

                    <div class="rounded-xl border border-blue-200 bg-blue-50/70 p-3 text-xs text-blue-900 flex items-start gap-2.5">
                        <i class="fa-solid fa-circle-info text-primary text-sm shrink-0 mt-0.5"></i>
                        <p class="leading-relaxed">
                            <strong>Petunjuk:</strong> Silakan kembalikan buku di atas ke meja sirkulasi Perpustakaan MNC University Lantai 2 agar hak peminjaman Anda aktif kembali.
                        </p>
                    </div>

                    <div class="flex justify-end pt-2">
                        <button
                            type="button"
                            onclick="closeBorrowModal()"
                            class="inline-flex h-9 items-center justify-center rounded-xl bg-slate-800 px-4 text-xs font-semibold text-white hover:bg-slate-900 transition-colors"
                        >
                            Tutup
                        </button>
                    </div>
                </div>

                {{-- State 4: Form Input Data Peminjaman (Eligible / Memenuhi Syarat) --}}
                <div id="borrow-state-form" class="hidden space-y-4">
                    {{-- Status Verifikasi Eligible --}}
                    <div class="rounded-xl border border-emerald-200 bg-emerald-50/80 p-3 text-xs text-emerald-950 flex items-start gap-2.5">
                        <i class="fa-solid fa-circle-check text-emerald-600 text-sm shrink-0 mt-0.5"></i>
                        <div class="min-w-0">
                            <span class="font-bold text-emerald-900">Eligibilitas Terverifikasi:</span>
                            <span class="text-emerald-800">Anda tidak memiliki tanggungan buku pinjaman aktif dan memenuhi syarat untuk meminjam koleksi ini.</span>
                        </div>
                    </div>

                    {{-- Ringkasan Koleksi Buku yang Dipilih --}}
                    <div class="rounded-xl border border-line bg-slate-50/80 p-3 flex gap-3 items-center">
                        <div class="size-14 shrink-0 overflow-hidden rounded-lg border border-line bg-white shadow-2xs flex items-center justify-center">
                            <img id="borrow-form-cover-img" src="" alt="Sampul" class="h-full w-full object-cover hidden">
                            <div id="borrow-form-cover-placeholder" class="flex size-full items-center justify-center bg-slate-100 text-muted">
                                <i class="fa-solid fa-book text-lg"></i>
                            </div>
                        </div>
                        <div class="min-w-0 flex-1">
                            <h4 id="borrow-form-book-title" class="text-xs font-bold text-ink line-clamp-1">-</h4>
                            <p id="borrow-form-book-author" class="text-[11px] text-muted line-clamp-1 mt-0.5">-</p>
                            <p id="borrow-form-book-meta" class="text-[10px] text-primary font-mono mt-1">-</p>
                        </div>
                    </div>

                    {{-- Form Peminjaman --}}
                    <form id="form-borrow-book" onsubmit="submitBorrowLoan(event)" class="space-y-3.5">
                        <input type="hidden" id="borrow-input-id-buku" name="id_buku" value="">

                        {{-- Data Mahasiswa / Pemustaka & Tanggal Pengambilan --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[11px] font-semibold text-ink mb-1">
                                    Nama Pemustaka (Peminjam)
                                </label>
                                <input
                                    type="text"
                                    id="borrow-user-name"
                                    readonly
                                    class="w-full rounded-xl border border-line bg-slate-100 px-3 py-2 text-xs font-semibold text-ink cursor-not-allowed"
                                    value=""
                                >
                            </div>

                            <div>
                                <label class="block text-[11px] font-semibold text-ink mb-1">
                                    NIM / NIDN Pemustaka
                                </label>
                                <input
                                    type="text"
                                    id="borrow-user-nim"
                                    readonly
                                    class="w-full rounded-xl border border-line bg-slate-100 px-3 py-2 text-xs font-mono text-muted cursor-not-allowed"
                                    value=""
                                >
                            </div>
                        </div>

                        {{-- Tanggal Pengambilan & Tanggal Pengembalian --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[11px] font-semibold text-ink mb-1">
                                    Tanggal Pengambilan (Auto-set)
                                </label>
                                <div class="flex items-center justify-between rounded-xl border border-line bg-slate-50 px-3 py-2 text-xs">
                                    <span id="borrow-display-pickup-date" class="font-semibold text-ink">Hari Ini</span>
                                    <span class="rounded bg-emerald-100 px-1.5 py-0.5 text-[10px] font-bold text-emerald-800">
                                        Hari Ini
                                    </span>
                                </div>
                                <input type="hidden" id="borrow-input-tanggal-pinjam" name="tanggal_pinjam" value="">
                            </div>

                            <div>
                                <label for="borrow-input-return-date" class="block text-[11px] font-semibold text-ink mb-1">
                                    Tanggal Pengembalian <span class="text-rose-500">*</span>
                                </label>
                                <input
                                    type="date"
                                    id="borrow-input-return-date"
                                    name="tanggal_jatuh_tempo"
                                    required
                                    class="w-full rounded-xl border border-line bg-white px-3 py-1.5 text-xs text-ink shadow-2xs focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-500/20"
                                >
                                <p class="text-[10px] text-muted mt-0.5">Maksimal peminjaman 14 hari kerja.</p>
                            </div>
                        </div>

                        {{-- Pilihan Eksemplar Fisik yang Tersedia --}}
                        <div>
                            <label for="borrow-input-exemplar" class="block text-[11px] font-semibold text-ink mb-1">
                                Pilih Eksemplar Fisik Buku <span class="text-rose-500">*</span>
                            </label>
                            <select
                                id="borrow-input-exemplar"
                                name="id_eksemplar"
                                required
                                class="w-full rounded-xl border border-line bg-white px-3 py-2 text-xs text-ink shadow-2xs focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-500/20"
                            >
                                {{-- Diisi secara dinamis via JavaScript --}}
                            </select>
                        </div>

                        {{-- Catatan Tambahan --}}
                        <div>
                            <label for="borrow-input-catatan" class="block text-[11px] font-semibold text-ink mb-1">
                                Catatan Tambahan (Opsional)
                            </label>
                            <textarea
                                id="borrow-input-catatan"
                                name="catatan"
                                rows="2"
                                placeholder="Keperluan peminjaman (tugas akhir, riset, atau referensi perkuliahan)..."
                                class="w-full rounded-xl border border-line bg-white px-3 py-2 text-xs text-ink shadow-2xs focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-500/20"
                            ></textarea>
                        </div>

                        {{-- Pesan Error Validasi Form --}}
                        <div id="borrow-form-error" class="hidden rounded-xl border border-rose-200 bg-rose-50 p-3 text-xs text-rose-800 font-medium"></div>

                        {{-- Tombol Aksi Form --}}
                        <div class="flex items-center justify-end gap-2.5 pt-2 border-t border-line">
                            <button
                                type="button"
                                onclick="closeBorrowModal()"
                                class="inline-flex h-9 items-center justify-center rounded-xl border border-line bg-white px-4 text-xs font-semibold text-ink hover:bg-subtle transition-colors"
                            >
                                Batal
                            </button>
                            <button
                                type="submit"
                                id="borrow-submit-btn"
                                class="inline-flex h-9 items-center justify-center gap-1.5 rounded-xl bg-emerald-600 px-5 text-xs font-semibold text-white shadow-xs hover:bg-emerald-700 transition-all focus:outline-none focus:ring-2 focus:ring-emerald-500"
                            >
                                <i class="fa-solid fa-paper-plane text-xs"></i>
                                <span>Konfirmasi Pinjam Buku</span>
                            </button>
                        </div>
                    </form>
                </div>

                {{-- State 5: Peminjaman Berhasil Dicatat --}}
                <div id="borrow-state-success" class="hidden space-y-4 py-3">
                    <div class="flex flex-col items-center justify-center text-center">
                        <div class="flex size-14 items-center justify-center rounded-full bg-emerald-100 text-emerald-700 text-2xl mb-3 shadow-xs">
                            <i class="fa-solid fa-check"></i>
                        </div>
                        <h4 class="text-base font-bold text-ink">Peminjaman Berhasil Dicatat!</h4>
                        <p class="mt-1 max-w-md text-xs text-muted leading-relaxed">
                            Data peminjaman buku mandiri Anda telah sukses tersimpan di sistem perpustakaan.
                        </p>
                    </div>

                    {{-- Ringkasan Bukti Pinjam --}}
                    <div class="rounded-xl border border-line bg-slate-50/90 p-4 text-xs space-y-2">
                        <div class="flex items-center justify-between border-b border-line/60 pb-1.5">
                            <span class="text-muted">ID Peminjaman:</span>
                            <span id="borrow-success-id" class="font-mono font-bold text-primary">-</span>
                        </div>
                        <div class="flex items-center justify-between border-b border-line/60 pb-1.5">
                            <span class="text-muted">Judul Buku:</span>
                            <span id="borrow-success-title" class="font-semibold text-ink text-right line-clamp-1 max-w-[240px]">-</span>
                        </div>
                        <div class="flex items-center justify-between border-b border-line/60 pb-1.5">
                            <span class="text-muted">Eksemplar Fisik:</span>
                            <span id="borrow-success-exemplar" class="font-mono font-medium text-ink">-</span>
                        </div>
                        <div class="flex items-center justify-between border-b border-line/60 pb-1.5">
                            <span class="text-muted">Peminjam:</span>
                            <span id="borrow-success-borrower" class="font-semibold text-ink">-</span>
                        </div>
                        <div class="flex items-center justify-between border-b border-line/60 pb-1.5">
                            <span class="text-muted">Tanggal Pinjam:</span>
                            <span id="borrow-success-pickup" class="font-medium text-ink">-</span>
                        </div>
                        <div class="flex items-center justify-between pt-0.5">
                            <span class="font-semibold text-amber-900">Batas Pengembalian:</span>
                            <span id="borrow-success-due" class="font-bold text-amber-900">-</span>
                        </div>
                    </div>

                    {{-- Notice Update Real-Time --}}
                    <div class="rounded-xl border border-emerald-200 bg-emerald-50/80 p-3 text-xs text-emerald-950 flex items-start gap-2.5">
                        <i class="fa-solid fa-eye text-emerald-600 text-sm shrink-0 mt-0.5"></i>
                        <p class="leading-relaxed">
                            Status buku telah otomatis berkurang menjadi <strong>Dipinjam</strong>. Admin sirkulasi serta pemustaka lain kini dapat melihat bahwa buku ini sedang dipinjam atas nama Anda.
                        </p>
                    </div>

                    <div class="flex justify-center pt-2">
                        <button
                            type="button"
                            onclick="window.location.reload()"
                            class="inline-flex h-9 items-center justify-center gap-1.5 rounded-xl bg-primary px-5 text-xs font-semibold text-white shadow-xs hover:bg-primary-dark transition-colors"
                        >
                            <i class="fa-solid fa-rotate text-xs"></i>
                            <span>Selesai & Segarkan Katalog</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- =========================================================================
         MODAL PENGEMBALIAN BUKU MANDIRI (SESUAI FLOWCHART VALIDASI PENGEMBALIAN)
         ========================================================================= --}}
    <div
        id="return-book-modal"
        class="fixed inset-0 z-50 hidden flex items-center justify-center p-3 sm:p-4 bg-slate-950/60 backdrop-blur-sm overflow-y-auto"
        role="dialog"
        aria-modal="true"
        aria-labelledby="return-modal-title"
    >
        <div class="relative w-full max-w-lg overflow-hidden rounded-2xl border border-line bg-white shadow-2xl transition-all my-auto animate-in fade-in zoom-in-95 duration-200">
            {{-- Header Modal --}}
            <div class="flex items-center justify-between border-b border-line bg-slate-50/90 px-5 py-4 sm:px-6">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="flex size-10 items-center justify-center rounded-xl bg-indigo-100 text-indigo-700 shrink-0">
                        <i class="fa-solid fa-arrow-rotate-left text-base" aria-hidden="true"></i>
                    </div>
                    <div class="min-w-0">
                        <h3 id="return-modal-title" class="text-sm font-bold text-ink truncate">
                            Pengembalian Buku oleh Pemustaka
                        </h3>
                        <p class="text-[11px] text-muted truncate">
                            Validasi pengembalian buku & penambahan stok otomatis
                        </p>
                    </div>
                </div>
                <button
                    type="button"
                    onclick="closeReturnBookModal()"
                    class="inline-flex size-8 items-center justify-center rounded-lg text-muted hover:bg-slate-200/60 hover:text-ink transition-colors focus:outline-none focus:ring-2 focus:ring-primary"
                    aria-label="Tutup modal"
                >
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            {{-- Body Modal --}}
            <div class="p-5 sm:p-6 space-y-4">
                {{-- Detail Buku & Eksemplar --}}
                <div class="flex gap-3.5 rounded-xl border border-line bg-slate-50/70 p-3 sm:p-4">
                    <div class="relative h-24 w-16 shrink-0 overflow-hidden rounded-lg border border-line bg-subtle shadow-xs">
                        <img
                            id="return-form-cover-img"
                            src=""
                            alt=""
                            class="h-full w-full object-cover hidden"
                        >
                        <div id="return-form-cover-placeholder" class="flex h-full w-full items-center justify-center bg-subtle text-muted">
                            <i class="fa-solid fa-book text-xl"></i>
                        </div>
                    </div>
                    <div class="min-w-0 flex-1 text-xs space-y-1">
                        <h4 id="return-form-book-title" class="font-bold text-sm text-ink line-clamp-2 leading-snug"></h4>
                        <p id="return-form-book-author" class="text-ink-secondary text-[11px] line-clamp-1"></p>
                        <div class="pt-1 text-[11px] space-y-0.5 text-muted">
                            <div>No. Induk: <code id="return-form-kode-inv" class="font-mono font-bold text-ink">-</code></div>
                            <div>No. Panggil: <span id="return-form-call-number" class="font-mono text-primary">-</span></div>
                            <div>Lokasi Rak: <span id="return-form-location" class="text-ink-secondary">-</span></div>
                        </div>
                    </div>
                </div>

                {{-- Status Batas Waktu / Keterlambatan --}}
                <div id="return-status-alert-box" class="rounded-xl border p-3.5 text-xs">
                    {{-- Diisi secara dinamis via JS --}}
                </div>

                {{-- Informasi Sesuai Flowchart --}}
                <div class="rounded-xl border border-primary/20 bg-primary/5 p-3.5 text-xs text-ink flex items-start gap-2.5">
                    <i class="fa-solid fa-circle-info text-primary text-sm shrink-0 mt-0.5"></i>
                    <p class="leading-relaxed">
                        Setelah Anda melakukan konfirmasi pengembalian, sistem akan memvalidasi data peminjaman, mencatat pengembalian, dan <strong>status stok buku akan kembali bertambah</strong> menjadi Tersedia.
                    </p>
                </div>

                {{-- Form Pengembalian --}}
                <form id="form-pengajuan-kembali" onsubmit="submitReturnBook(event)" class="space-y-3.5">
                    <input type="hidden" id="return-input-id-peminjaman" value="">

                    {{-- Error message jika ada --}}
                    <div id="return-form-error" class="hidden rounded-xl border border-rose-200 bg-rose-50 p-3 text-xs text-rose-700 font-medium"></div>

                    {{-- Catatan Pengembalian (Opsional) --}}
                    <div>
                        <label for="return-input-catatan" class="block text-xs font-semibold text-ink mb-1">
                            Catatan Pengembalian <span class="text-muted font-normal">(Opsional)</span>
                        </label>
                        <textarea
                            id="return-input-catatan"
                            rows="2"
                            placeholder="Contoh: Buku dikembalikan dalam kondisi baik dan lengkap..."
                            class="w-full rounded-xl border border-line bg-slate-50/50 p-2.5 text-xs text-ink placeholder:text-muted focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20"
                        ></textarea>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="flex items-center justify-end gap-2 pt-2 border-t border-line">
                        <button
                            type="button"
                            onclick="closeReturnBookModal()"
                            class="inline-flex h-9 items-center justify-center rounded-xl border border-line bg-white px-4 text-xs font-semibold text-ink hover:bg-subtle transition-colors"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            id="return-submit-btn"
                            class="inline-flex h-9 items-center justify-center gap-1.5 rounded-xl bg-indigo-600 px-5 text-xs font-semibold text-white shadow-xs hover:bg-indigo-700 transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        >
                            <i class="fa-solid fa-arrow-rotate-left text-xs"></i>
                            <span id="return-submit-label">Ya, Kembalikan Buku</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- SCRIPT INTERAKTIVITAS: BOX FILTER PENCARIAN & MODAL DETAIL BUKU & PEMINJAMAN MANDIRI --}}
    <script>
        window.MNCU_AUTH = {
            isLoggedIn: {{ Auth::check() ? 'true' : 'false' }},
            userId: '{{ Auth::check() ? Auth::user()->id_pengguna : '' }}',
            userName: '{{ Auth::check() ? addslashes(Auth::user()->nama_lengkap) : '' }}',
            userNim: '{{ Auth::check() ? (Auth::user()->nim ?: (Auth::user()->nidn ?: (Auth::user()->nomor_identitas ?: '-'))) : '' }}',
            userRole: '{{ Auth::check() ? (Auth::user()->peran?->nama_peran ?: 'Mahasiswa') : '' }}',
            isMember: {{ Auth::check() && Auth::user()->isMember() ? 'true' : 'false' }},
            routes: {
                cekEligibilitas: '{{ route("opac.cek-eligibilitas") }}',
                pinjam: '{{ route("opac.pinjam") }}',
                kembalikan: '{{ route("opac.kembalikan") }}',
                ajukanPengembalian: '{{ route("opac.ajukan-pengembalian") }}',
                login: '{{ route("login") }}'
            },
            dates: {
                todayYmd: '{{ now()->toDateString() }}',
                todayFormatted: '{{ now()->translatedFormat("l, d F Y") }}',
                minReturnYmd: '{{ now()->toDateString() }}',
                maxReturnYmd: '{{ now()->addDays(14)->toDateString() }}',
                defaultReturnYmd: '{{ now()->addDays(14)->toDateString() }}'
            }
        };

        // Pindahkan modal langsung ke <body> agar terbebas dari pembatas layout CSS/transform
        function ensureModalsInBody() {
            const detailModal = document.getElementById('book-detail-modal');
            if (detailModal && detailModal.parentElement !== document.body) {
                document.body.appendChild(detailModal);
            }
            const filterModal = document.getElementById('search-filter-modal');
            if (filterModal && filterModal.parentElement !== document.body) {
                document.body.appendChild(filterModal);
            }
            const borrowModal = document.getElementById('borrow-modal');
            if (borrowModal && borrowModal.parentElement !== document.body) {
                document.body.appendChild(borrowModal);
            }
            const returnModal = document.getElementById('return-book-modal');
            if (returnModal && returnModal.parentElement !== document.body) {
                document.body.appendChild(returnModal);
            }
        }

        // =========================================================================
        // 1. KONTROL BOX FILTER PENCARIAN (MODAL SEPERTI GAMBAR 2)
        // =========================================================================
        function openFilterModal() {
            ensureModalsInBody();
            const modal = document.getElementById('search-filter-modal');
            if (modal) {
                modal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden', 'modal-open');
                const mainHeader = document.getElementById('main-header') || document.querySelector('header');
                if (mainHeader) {
                    mainHeader.classList.add('opacity-0', 'pointer-events-none', '-translate-y-full', 'invisible');
                }
                modal.scrollTop = 0;
                document.getElementById('modal-input-pengarang')?.focus();
            }
        }

        function closeFilterModal() {
            const modal = document.getElementById('search-filter-modal');
            if (modal) {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden', 'modal-open');
                const mainHeader = document.getElementById('main-header') || document.querySelector('header');
                if (mainHeader) {
                    mainHeader.classList.remove('opacity-0', 'pointer-events-none', '-translate-y-full', 'invisible');
                }
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
            if (document.getElementById('modal-select-status')) {
                document.getElementById('hidden-filter-status').value = document.getElementById('modal-select-status').value;
            }

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
            if (document.getElementById('modal-select-status')) {
                document.getElementById('modal-select-status').value = 'Semua';
            }

            document.getElementById('hidden-filter-pengarang').value = '';
            document.getElementById('hidden-filter-isbn').value = '';
            document.getElementById('hidden-filter-ddc').value = '';
            document.getElementById('hidden-filter-nomor-induk').value = '';
            document.getElementById('hidden-filter-tahun').value = 'Semua';
            document.getElementById('hidden-filter-lokasi').value = 'Semua';
            document.getElementById('hidden-filter-jenis').value = 'Semua';
            document.getElementById('hidden-filter-prodi').value = 'Semua';
            document.getElementById('hidden-filter-status').value = 'Semua';

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
            ensureModalsInBody();
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

            window.currentSelectedBookItem = item;

            // Cek apakah pemustaka yang sedang login sedang meminjam eksemplar dari buku ini
            const userLoan = item.user_active_loan || (function () {
                if (!window.MNCU_AUTH || !window.MNCU_AUTH.isLoggedIn || !window.MNCU_AUTH.userId) return null;
                const activeLoans = item.active_loans || [];
                return activeLoans.find(function (l) {
                    return l.id_pengguna === window.MNCU_AUTH.userId;
                }) || null;
            })();

            const userLoanBanner = document.getElementById('modal-user-loan-banner');
            const detailBorrowBtn = document.getElementById('modal-detail-borrow-btn');
            const detailReturnBtn = document.getElementById('modal-detail-return-btn');

            if (userLoan) {
                // Alur: Jika user sedang meminjam buku ini -> Sembunyikan tombol Pinjam dan tampilkan tombol Kembalikan
                if (detailBorrowBtn) detailBorrowBtn.classList.add('hidden');

                if (detailReturnBtn) {
                    detailReturnBtn.classList.remove('hidden');
                    if (userLoan.is_menunggu_verifikasi || userLoan.status === 'Menunggu Verifikasi') {
                        detailReturnBtn.disabled = true;
                        detailReturnBtn.className = 'inline-flex h-9 items-center gap-1.5 rounded-xl border border-amber-300 bg-amber-50 px-4 text-xs font-semibold text-amber-800 shadow-2xs cursor-not-allowed';
                        detailReturnBtn.innerHTML = '<i class="fa-solid fa-clock-rotate-left text-xs" aria-hidden="true"></i><span>Menunggu Persetujuan Admin</span>';
                        detailReturnBtn.title = 'Pengajuan pengembalian buku sedang menunggu verifikasi dan persetujuan admin';
                    } else {
                        detailReturnBtn.disabled = false;
                        detailReturnBtn.className = 'inline-flex h-9 items-center gap-1.5 rounded-xl bg-indigo-600 px-4 text-xs font-semibold text-white shadow-xs hover:bg-indigo-700 transition-all focus:outline-none focus:ring-2 focus:ring-indigo-500';
                        const returnLabel = userLoan.is_terlambat ? 'Kembalikan Buku (Terlambat)' : 'Kembalikan Buku';
                        detailReturnBtn.innerHTML = '<i class="fa-solid fa-arrow-rotate-left text-xs" aria-hidden="true"></i><span>' + returnLabel + '</span>';
                        detailReturnBtn.title = 'Ajukan pengembalian buku ini ke meja sirkulasi perpustakaan';
                    }
                }

                // Tampilkan banner khusus status peminjaman user
                if (userLoanBanner) {
                    userLoanBanner.classList.remove('hidden');
                    const titleEl = document.getElementById('modal-user-loan-title');
                    const descEl = document.getElementById('modal-user-loan-desc');
                    const metaEl = document.getElementById('modal-user-loan-meta');
                    const iconBox = document.getElementById('modal-user-loan-icon-box');
                    const iconEl = document.getElementById('modal-user-loan-icon');

                    if (userLoan.is_menunggu_verifikasi || userLoan.status === 'Menunggu Verifikasi') {
                        userLoanBanner.className = 'rounded-xl border border-amber-200 bg-amber-50 p-4 text-xs text-amber-950';
                        iconBox.className = 'flex size-8 shrink-0 items-center justify-center rounded-lg bg-amber-100 text-amber-800';
                        iconEl.className = 'fa-solid fa-clock-rotate-left text-sm';
                        titleEl.textContent = 'Pengajuan Pengembalian Sedang Menunggu Verifikasi Admin';
                        descEl.textContent = 'Anda telah mengajukan pengembalian buku ini via website. Silakan serahkan fisik buku ke meja sirkulasi Perpustakaan MNC University Lantai 2 agar admin dapat memeriksa kondisi buku dan menyetujui transaksi pengembalian.';
                        metaEl.innerHTML = `
                            <span class="rounded bg-amber-200/80 px-2 py-0.5 font-semibold text-amber-900">Menunggu Approval</span>
                            <span class="text-amber-800 font-medium">No. Induk: <code class="font-mono font-bold">${userLoan.kode_inventaris || '-'}</code></span>
                            <span class="text-amber-800 font-medium">Jatuh Tempo: <strong>${userLoan.tanggal_jatuh_tempo || '-'}</strong></span>
                        `;
                    } else if (userLoan.is_terlambat) {
                        const lateDays = userLoan.hari_keterlambatan || 1;
                        userLoanBanner.className = 'rounded-xl border border-rose-200 bg-rose-50 p-4 text-xs text-rose-950';
                        iconBox.className = 'flex size-8 shrink-0 items-center justify-center rounded-lg bg-rose-100 text-rose-800';
                        iconEl.className = 'fa-solid fa-triangle-exclamation text-sm';
                        titleEl.textContent = 'Buku Ini Sedang Anda Pinjam (Lewat Batas Jatuh Tempo)';
                        descEl.textContent = 'Peminjaman buku ini telah melewati batas waktu pengembalian sebanyak ' + lateDays + ' hari. Anda tetap dapat mengembalikan buku ini melalui sistem dan menyerahkan fisiknya ke meja sirkulasi perpustakaan.';
                        metaEl.innerHTML = `
                            <span class="rounded bg-rose-200 px-2 py-0.5 font-bold text-rose-900">Terlambat ${lateDays} Hari</span>
                            <span class="text-rose-900 font-medium">No. Induk: <code class="font-mono font-bold">${userLoan.kode_inventaris || '-'}</code></span>
                            <span class="text-rose-900 font-medium">Batas Tempo: <strong>${userLoan.tanggal_jatuh_tempo || '-'}</strong></span>
                        `;
                    } else {
                        userLoanBanner.className = 'rounded-xl border border-indigo-200 bg-indigo-50/80 p-4 text-xs text-indigo-950';
                        iconBox.className = 'flex size-8 shrink-0 items-center justify-center rounded-lg bg-indigo-100 text-indigo-800';
                        iconEl.className = 'fa-solid fa-book-bookmark text-sm';
                        titleEl.textContent = 'Buku Ini Sedang Anda Pinjam';
                        descEl.textContent = 'Buku ini tercatat aktif dalam peminjaman akun Anda. Anda dapat mengajukan pengembalian kapan saja sebelum atau pada saat jatuh tempo.';
                        metaEl.innerHTML = `
                            <span class="rounded bg-indigo-200/70 px-2 py-0.5 font-semibold text-indigo-900">Status: Dipinjam</span>
                            <span class="text-indigo-900 font-medium">No. Induk: <code class="font-mono font-bold">${userLoan.kode_inventaris || '-'}</code></span>
                            <span class="text-indigo-900 font-medium">Batas Tempo: <strong>${userLoan.tanggal_jatuh_tempo || '-'}</strong></span>
                        `;
                    }
                }
            } else {
                // User TIDAK sedang meminjam buku ini -> sembunyikan banner & tombol kembalikan
                if (userLoanBanner) userLoanBanner.classList.add('hidden');
                if (detailReturnBtn) detailReturnBtn.classList.add('hidden');

                if (detailBorrowBtn) {
                    if (available > 0) {
                        detailBorrowBtn.disabled = false;
                        detailBorrowBtn.classList.remove('hidden', 'bg-slate-200', 'text-slate-500', 'cursor-not-allowed');
                        detailBorrowBtn.classList.add('bg-emerald-600', 'text-white', 'hover:bg-emerald-700');
                        detailBorrowBtn.innerHTML = '<i class="fa-solid fa-hand-holding-hand text-xs" aria-hidden="true"></i><span>Pinjam Buku Ini</span>';
                        detailBorrowBtn.title = 'Pinjam buku ini via website katalog OPAC';
                    } else {
                        detailBorrowBtn.disabled = true;
                        detailBorrowBtn.classList.remove('hidden', 'bg-emerald-600', 'text-white', 'hover:bg-emerald-700');
                        detailBorrowBtn.classList.add('bg-slate-200', 'text-slate-500', 'cursor-not-allowed');
                        detailBorrowBtn.innerHTML = '<i class="fa-solid fa-clock text-xs" aria-hidden="true"></i><span>Buku Sedang Dipinjam</span>';
                        detailBorrowBtn.title = 'Semua eksemplar fisik buku ini sedang dipinjam';
                    }
                }
            }

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

            // Chat link (buka di tab baru sesuai preferensi pemustaka)
            const chatLink = document.getElementById('modal-chat-link');
            if (chatLink) {
                chatLink.href = '{{ route("chat") }}?q=' + encodeURIComponent('Halo Pustakawan, saya ingin reservasi/cek buku: ' + item.title);
                chatLink.target = '_blank';
                chatLink.rel = 'noopener noreferrer';
            }

            // Tombol khusus admin jika ada di DOM
            const adminManageLink = document.getElementById('modal-admin-manage-link');
            if (adminManageLink && item.id) {
                adminManageLink.href = '{{ url("/admin/buku") }}/' + encodeURIComponent(item.id);
            }
            const adminEditLink = document.getElementById('modal-admin-edit-link');
            if (adminEditLink && item.id) {
                adminEditLink.href = '{{ url("/admin/buku") }}/' + encodeURIComponent(item.id) + '/edit';
            }

            // Exemplar table (Nomor Induk, Call Number, Lokasi, Status, Peminjam)
            const tbody = document.getElementById('modal-exemplar-tbody');
            tbody.innerHTML = '';

            const eksemplarList = item.eksemplar_list || [];
            if (eksemplarList.length === 0) {
                const tr = document.createElement('tr');
                tr.innerHTML = '<td colspan="6" class="px-4 py-6 text-center text-muted text-xs">Belum ada eksemplar fisik tercatat untuk koleksi ini.</td>';
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

                    let peminjamInfo = '<span class="text-muted text-[11px]">-</span>';
                    if (ek.peminjam) {
                        peminjamInfo = `
                            <div class="text-[11px] leading-tight">
                                <strong class="font-semibold text-ink">${ek.peminjam.nama || 'Pemustaka'}</strong>
                                <div class="text-[10px] text-muted">${ek.peminjam.nim || ''} (${ek.peminjam.role || 'Mahasiswa'})</div>
                                <div class="text-[10px] text-amber-700 font-medium">Batas: ${ek.peminjam.tanggal_jatuh_tempo || '-'}</div>
                            </div>
                        `;
                    }

                    tr.innerHTML = `
                        <td class="px-3.5 py-3 text-muted font-mono">${idx + 1}</td>
                        <td class="px-3.5 py-3 font-mono font-medium text-ink">${ek.kode_inventaris || '-'}</td>
                        <td class="px-3.5 py-3 font-mono text-primary">${ek.nomor_panggil || '-'}</td>
                        <td class="px-3.5 py-3 text-ink-secondary">${ek.lokasi_rak || '-'}</td>
                        <td class="px-3.5 py-3 text-center whitespace-nowrap">${statusBadge}</td>
                        <td class="px-3.5 py-3">${peminjamInfo}</td>
                    `;
                    tbody.appendChild(tr);
                });
            }

            // Box Informasi Peminjam Aktif
            const activeLoansBox = document.getElementById('modal-active-loans-box');
            const activeLoansList = document.getElementById('modal-active-loans-list');
            if (activeLoansBox && activeLoansList) {
                activeLoansList.innerHTML = '';
                const loans = item.active_loans || [];
                if (loans.length > 0) {
                    activeLoansBox.classList.remove('hidden');
                    loans.forEach(function (loan) {
                        const card = document.createElement('div');
                        card.className = 'rounded-lg border border-amber-200/90 bg-white p-2.5 shadow-xs text-xs';
                        card.innerHTML = `
                            <div class="flex items-center justify-between mb-1">
                                <strong class="font-bold text-ink">${loan.peminjam_nama}</strong>
                                <span class="rounded bg-amber-100 px-1.5 py-0.5 text-[10px] font-semibold text-amber-800">${loan.status}</span>
                            </div>
                            <div class="text-[11px] text-muted">${loan.peminjam_nim} &bull; ${loan.peminjam_role}</div>
                            <div class="mt-1 flex items-center justify-between text-[10px] border-t border-line/60 pt-1 text-ink-secondary">
                                <span>No. Induk: <code class="font-mono">${loan.kode_inventaris}</code></span>
                                <span class="text-amber-800 font-medium">Jatuh Tempo: ${loan.tanggal_jatuh_tempo}</span>
                            </div>
                        `;
                        activeLoansList.appendChild(card);
                    });
                } else {
                    activeLoansBox.classList.add('hidden');
                }
            }

            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden', 'modal-open');
            const mainHeader = document.getElementById('main-header') || document.querySelector('header');
            if (mainHeader) {
                mainHeader.classList.add('opacity-0', 'pointer-events-none', '-translate-y-full', 'invisible');
            }

            // Reset scroll modal ke atas agar judul & sampul selalu terlihat pertama kali
            modal.scrollTop = 0;
            const scrollArea = modal.querySelector('.overflow-y-auto');
            if (scrollArea) {
                scrollArea.scrollTop = 0;
            }

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
                const borrowModal = document.getElementById('borrow-modal');
                if (!borrowModal || borrowModal.classList.contains('hidden')) {
                    document.body.classList.remove('overflow-hidden', 'modal-open');
                    const mainHeader = document.getElementById('main-header') || document.querySelector('header');
                    if (mainHeader) {
                        mainHeader.classList.remove('opacity-0', 'pointer-events-none', '-translate-y-full', 'invisible');
                    }
                }
            }
        }

        // =========================================================================
        // 3. KONTROL ALUR PEMINJAMAN BUKU MANDIRI (SANCTUM & VALIDASI ELIGIBILITAS)
        // =========================================================================
        function openBorrowModalFromDetail() {
            closeBookDetailModal();
            if (window.currentSelectedBookItem) {
                initiateBorrowBook(window.currentSelectedBookItem);
            }
        }

        function closeBorrowModal() {
            const modal = document.getElementById('borrow-modal');
            if (modal) {
                modal.classList.add('hidden');
                const detailModal = document.getElementById('book-detail-modal');
                if (!detailModal || detailModal.classList.contains('hidden')) {
                    document.body.classList.remove('overflow-hidden', 'modal-open');
                    const mainHeader = document.getElementById('main-header') || document.querySelector('header');
                    if (mainHeader) {
                        mainHeader.classList.remove('opacity-0', 'pointer-events-none', '-translate-y-full', 'invisible');
                    }
                }
            }
        }

        function initiateBorrowBook(item) {
            ensureModalsInBody();
            window.currentSelectedBookItem = item;
            const modal = document.getElementById('borrow-modal');
            if (!modal || !item) return;

            // Reset semua kontainer state modal
            document.getElementById('borrow-state-loading').classList.remove('hidden');
            document.getElementById('borrow-state-auth-required').classList.add('hidden');
            document.getElementById('borrow-state-blocked').classList.add('hidden');
            document.getElementById('borrow-state-form').classList.add('hidden');
            document.getElementById('borrow-state-success').classList.add('hidden');
            document.getElementById('borrow-form-error').classList.add('hidden');
            document.getElementById('borrow-form-error').textContent = '';

            // Buka modal
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden', 'modal-open');
            const mainHeader = document.getElementById('main-header') || document.querySelector('header');
            if (mainHeader) {
                mainHeader.classList.add('opacity-0', 'pointer-events-none', '-translate-y-full', 'invisible');
            }

            // Validasi status login pemustaka
            if (!window.MNCU_AUTH || !window.MNCU_AUTH.isLoggedIn) {
                document.getElementById('borrow-state-loading').classList.add('hidden');
                document.getElementById('borrow-state-auth-required').classList.remove('hidden');
                return;
            }

            // Ambil eligibilitas pemustaka via request terotentikasi (Sanctum / Web Session)
            fetch(window.MNCU_AUTH.routes.cekEligibilitas, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => {
                if (res.status === 401) {
                    document.getElementById('borrow-state-loading').classList.add('hidden');
                    document.getElementById('borrow-state-auth-required').classList.remove('hidden');
                    return null;
                }
                return res.json();
            })
            .then(res => {
                if (!res) return;
                document.getElementById('borrow-state-loading').classList.add('hidden');

                const eligData = res.data || {};
                if (eligData.eligible) {
                    renderBorrowForm(item, eligData.user);
                } else {
                    renderBorrowBlocked(eligData);
                }
            })
            .catch(err => {
                document.getElementById('borrow-state-loading').classList.add('hidden');
                renderBorrowBlocked({
                    message: 'Gagal memverifikasi eligibilitas akun: ' + (err.message || 'Terjadi kesalahan jaringan.'),
                    active_loans: []
                });
            });
        }

        function renderBorrowBlocked(eligData) {
            document.getElementById('borrow-state-loading').classList.add('hidden');
            document.getElementById('borrow-state-form').classList.add('hidden');
            document.getElementById('borrow-state-blocked').classList.remove('hidden');

            const msgEl = document.getElementById('borrow-blocked-message');
            if (msgEl) {
                msgEl.textContent = eligData.message || 'User tidak bisa meminjam buku karena masih memiliki tanggungan peminjaman aktif.';
            }

            const loansContainer = document.getElementById('borrow-blocked-loans-container');
            const loansList = document.getElementById('borrow-blocked-loans-list');
            const activeLoans = eligData.active_loans || [];

            if (loansContainer && loansList) {
                if (activeLoans.length > 0) {
                    loansContainer.classList.remove('hidden');
                    loansList.innerHTML = '';
                    activeLoans.forEach(loan => {
                        const itemCard = document.createElement('div');
                        itemCard.className = 'rounded-lg border border-amber-200/80 bg-white p-2.5 shadow-2xs text-xs';
                        const bukuJudul = (loan.buku && loan.buku.judul_buku) ? loan.buku.judul_buku : 'Koleksi Perpustakaan';
                        const kodeInv = (loan.eksemplar && loan.eksemplar.kode_inventaris) ? loan.eksemplar.kode_inventaris : '-';
                        const tglPinjam = loan.tanggal_pinjam || '-';
                        const tglJatuhTempo = loan.tanggal_jatuh_tempo || '-';
                        const statusLoan = loan.status || 'Dipinjam';

                        itemCard.innerHTML = `
                            <div class="flex items-center justify-between gap-2 mb-1">
                                <strong class="font-bold text-ink line-clamp-1">${bukuJudul}</strong>
                                <span class="rounded bg-amber-100 px-1.5 py-0.5 text-[10px] font-semibold text-amber-800 shrink-0">${statusLoan}</span>
                            </div>
                            <div class="flex flex-wrap items-center justify-between text-[11px] text-muted border-t border-line/60 pt-1">
                                <span>No. Induk: <code class="font-mono text-ink">${kodeInv}</code></span>
                                <span>Pinjam: <strong class="text-ink">${tglPinjam}</strong></span>
                                <span class="text-rose-700 font-semibold">Batas: ${tglJatuhTempo}</span>
                            </div>
                        `;
                        loansList.appendChild(itemCard);
                    });
                } else {
                    loansContainer.classList.add('hidden');
                }
            }
        }

        function renderBorrowForm(item, user) {
            document.getElementById('borrow-state-loading').classList.add('hidden');
            document.getElementById('borrow-state-blocked').classList.add('hidden');
            document.getElementById('borrow-state-form').classList.remove('hidden');
            document.getElementById('borrow-form-error').classList.add('hidden');
            document.getElementById('borrow-form-error').textContent = '';

            // Data buku terpilih
            document.getElementById('borrow-input-id-buku').value = item.id || '';
            document.getElementById('borrow-form-book-title').textContent = item.title || '-';
            document.getElementById('borrow-form-book-author').textContent = (item.author || '-') + (item.year ? ' (' + item.year + ')' : '');
            document.getElementById('borrow-form-book-meta').textContent = 'No. Panggil: ' + (item.call_number || '-') + ' | Lokasi: ' + (item.location || 'Lantai 2');

            const coverImg = document.getElementById('borrow-form-cover-img');
            const placeholder = document.getElementById('borrow-form-cover-placeholder');
            if (item.cover_url) {
                coverImg.src = item.cover_url;
                coverImg.classList.remove('hidden');
                placeholder.classList.add('hidden');
            } else {
                coverImg.classList.add('hidden');
                placeholder.classList.remove('hidden');
            }

            // Data pemustaka
            document.getElementById('borrow-user-name').value = (user && user.nama_lengkap) ? user.nama_lengkap : window.MNCU_AUTH.userName;
            const nimVal = (user && user.nim) ? user.nim : window.MNCU_AUTH.userNim;
            const roleVal = (user && user.role) ? user.role : window.MNCU_AUTH.userRole;
            document.getElementById('borrow-user-nim').value = nimVal + ' (' + roleVal + ')';

            // Tanggal pengambilan & pengembalian
            document.getElementById('borrow-display-pickup-date').textContent = window.MNCU_AUTH.dates.todayFormatted;
            document.getElementById('borrow-input-tanggal-pinjam').value = window.MNCU_AUTH.dates.todayYmd;

            const returnInput = document.getElementById('borrow-input-return-date');
            returnInput.min = window.MNCU_AUTH.dates.minReturnYmd;
            returnInput.max = window.MNCU_AUTH.dates.maxReturnYmd;
            returnInput.value = window.MNCU_AUTH.dates.defaultReturnYmd;

            // Eksemplar buku fisik tersedia
            const exemplarSelect = document.getElementById('borrow-input-exemplar');
            exemplarSelect.innerHTML = '';

            const availableExemplars = (item.eksemplar_list || []).filter(ek => ek.status === 'Tersedia');
            if (availableExemplars.length === 0) {
                const opt = document.createElement('option');
                opt.value = '';
                opt.textContent = 'Tidak ada eksemplar fisik berstatus Tersedia saat ini.';
                exemplarSelect.appendChild(opt);
                exemplarSelect.disabled = true;
            } else {
                exemplarSelect.disabled = false;
                availableExemplars.forEach((ek, idx) => {
                    const opt = document.createElement('option');
                    opt.value = ek.id_eksemplar;
                    opt.textContent = 'No. Induk: ' + ek.kode_inventaris + ' - Rak: ' + (ek.lokasi_rak || 'Lantai 2') + ' (' + (ek.nomor_panggil || 'Koleksi') + ')';
                    if (idx === 0) opt.selected = true;
                    exemplarSelect.appendChild(opt);
                });
            }

            // Catatan
            document.getElementById('borrow-input-catatan').value = '';

            // Reset submit button
            const submitBtn = document.getElementById('borrow-submit-btn');
            submitBtn.disabled = availableExemplars.length === 0;
            submitBtn.innerHTML = '<i class="fa-solid fa-paper-plane text-xs"></i><span>Konfirmasi Pinjam Buku</span>';
        }

        async function submitBorrowLoan(event) {
            event.preventDefault();
            const submitBtn = document.getElementById('borrow-submit-btn');
            const errBox = document.getElementById('borrow-form-error');
            errBox.classList.add('hidden');
            errBox.textContent = '';

            const idBuku = document.getElementById('borrow-input-id-buku').value;
            const idEksemplar = document.getElementById('borrow-input-exemplar').value;
            const returnDate = document.getElementById('borrow-input-return-date').value;
            const catatan = document.getElementById('borrow-input-catatan').value;

            if (!idBuku) {
                errBox.textContent = 'Data buku tidak valid.';
                errBox.classList.remove('hidden');
                return;
            }
            if (!idEksemplar) {
                errBox.textContent = 'Pilih salah satu eksemplar fisik yang tersedia.';
                errBox.classList.remove('hidden');
                return;
            }
            if (!returnDate) {
                errBox.textContent = 'Tentukan batas tanggal pengembalian buku.';
                errBox.classList.remove('hidden');
                return;
            }

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin text-xs"></i><span>Memproses Peminjaman...</span>';

            try {
                const response = await fetch(window.MNCU_AUTH.routes.pinjam, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        id_buku: idBuku,
                        id_eksemplar: idEksemplar,
                        tanggal_jatuh_tempo: returnDate,
                        catatan: catatan
                    })
                });

                const result = await response.json();

                if (!response.ok || !result.success) {
                    let errorMsg = result.message || 'Gagal memproses peminjaman buku.';
                    if (result.errors) {
                        const detailedErrors = Object.values(result.errors).flat().join(' ');
                        if (detailedErrors) errorMsg += ' (' + detailedErrors + ')';
                    }
                    throw new Error(errorMsg);
                }

                // Tampilkan State 5: Berhasil
                document.getElementById('borrow-state-form').classList.add('hidden');
                document.getElementById('borrow-state-success').classList.remove('hidden');

                const d = result.data || {};
                document.getElementById('borrow-success-id').textContent = d.id_peminjaman || '-';
                document.getElementById('borrow-success-title').textContent = d.judul_buku || '-';
                document.getElementById('borrow-success-exemplar').textContent = (d.kode_inventaris || '-') + (d.lokasi_rak ? ' (Rak: ' + d.lokasi_rak + ')' : '');
                document.getElementById('borrow-success-borrower').textContent = d.nama_peminjam || '-';
                document.getElementById('borrow-success-pickup').textContent = d.tanggal_pinjam || '-';
                document.getElementById('borrow-success-due').textContent = d.tanggal_jatuh_tempo || '-';
            } catch (err) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fa-solid fa-paper-plane text-xs"></i><span>Konfirmasi Pinjam Buku</span>';
                errBox.textContent = err.message || 'Terjadi kesalahan saat memproses data.';
                errBox.classList.remove('hidden');
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
        // 4. KONTROL ALUR PENGEMBALIAN BUKU MANDIRI (VERIFIKASI & APPROVAL ADMIN)
        // =========================================================================
        function initiateReturnBookFromDetail() {
            if (window.currentSelectedBookItem) {
                initiateReturnBook(window.currentSelectedBookItem);
            }
        }

        function initiateReturnBook(item) {
            ensureModalsInBody();
            if (!item) return;

            // Validasi status login
            if (!window.MNCU_AUTH || !window.MNCU_AUTH.isLoggedIn) {
                alert('Silakan masuk (login) terlebih dahulu untuk mengembalikan buku.');
                window.location.href = window.MNCU_AUTH.routes.login;
                return;
            }

            const userLoan = item.user_active_loan || (function () {
                const activeLoans = item.active_loans || [];
                return activeLoans.find(function (l) {
                    return l.id_pengguna === window.MNCU_AUTH.userId;
                }) || null;
            })();

            if (!userLoan) {
                alert('Tidak ditemukan data peminjaman aktif Anda untuk koleksi buku ini.');
                return;
            }

            if (userLoan.is_menunggu_verifikasi || userLoan.status === 'Menunggu Verifikasi') {
                alert('Pengajuan pengembalian buku ini sudah dikirim sebelumnya dan sedang menunggu verifikasi/approval admin di meja sirkulasi.');
                return;
            }

            // Tutup detail modal jika terbuka
            closeBookDetailModal();

            const modal = document.getElementById('return-book-modal');
            if (!modal) return;

            // Pre-fill data
            document.getElementById('return-input-id-peminjaman').value = userLoan.id_peminjaman;
            document.getElementById('return-form-book-title').textContent = item.title || '-';
            document.getElementById('return-form-book-author').textContent = (item.author || '-') + (item.year ? ' (' + item.year + ')' : '');
            document.getElementById('return-form-kode-inv').textContent = userLoan.kode_inventaris || '-';
            document.getElementById('return-form-call-number').textContent = userLoan.nomor_panggil || item.call_number || '-';
            document.getElementById('return-form-location').textContent = userLoan.lokasi_rak || item.location || 'Lantai 2, Rak Koleksi';
            document.getElementById('return-input-catatan').value = '';

            const errorBox = document.getElementById('return-form-error');
            if (errorBox) {
                errorBox.classList.add('hidden');
                errorBox.textContent = '';
            }

            // Cover Image
            const coverImg = document.getElementById('return-form-cover-img');
            const placeholder = document.getElementById('return-form-cover-placeholder');
            if (item.cover_url) {
                coverImg.src = item.cover_url;
                coverImg.classList.remove('hidden');
                placeholder.classList.add('hidden');
            } else {
                coverImg.classList.add('hidden');
                placeholder.classList.remove('hidden');
            }

            // Status Batas Waktu / Keterlambatan
            const statusAlertBox = document.getElementById('return-status-alert-box');
            if (statusAlertBox) {
                if (userLoan.is_terlambat) {
                    const lateDays = userLoan.hari_keterlambatan || 1;
                    statusAlertBox.className = 'rounded-xl border border-rose-300 bg-rose-50 p-3.5 text-xs text-rose-950 animate-in fade-in duration-150';
                    statusAlertBox.innerHTML = `
                        <div class="flex items-start gap-2.5">
                            <i class="fa-solid fa-triangle-exclamation text-rose-600 text-base shrink-0 mt-0.5"></i>
                            <div>
                                <strong class="font-bold text-rose-900 text-xs">Notifikasi Peringatan: Waktu Telah Lewat Batas Pengembalian!</strong>
                                <p class="mt-0.5 leading-relaxed text-[11px] text-rose-800">
                                    Batas waktu pengembalian buku ini adalah <strong>${userLoan.tanggal_jatuh_tempo || '-'}</strong> (terlambat <strong>${lateDays} hari</strong>). Notifikasi peringatan telah dikirimkan ke sistem Anda.
                                </p>
                                <p class="mt-1.5 font-bold text-[11px] text-rose-950">
                                    User melakukan pengembalian buku? Silakan klik tombol di bawah untuk menyelesaikan pengembalian buku dan menambah kembali stok buku di sistem perpustakaan.
                                </p>
                            </div>
                        </div>
                    `;
                    const submitLabel = document.getElementById('return-submit-label');
                    if (submitLabel) submitLabel.textContent = 'Ya, Kembalikan Buku Sekarang';
                } else {
                    statusAlertBox.className = 'rounded-xl border border-emerald-300 bg-emerald-50 p-3.5 text-xs text-emerald-950 animate-in fade-in duration-150';
                    statusAlertBox.innerHTML = `
                        <div class="flex items-start gap-2.5">
                            <i class="fa-solid fa-circle-check text-emerald-600 text-base shrink-0 mt-0.5"></i>
                            <div>
                                <strong class="font-bold text-emerald-900 text-xs">Validasi Pengembalian: Peminjaman Valid & Tepat Waktu</strong>
                                <p class="mt-0.5 leading-relaxed text-[11px] text-emerald-800">
                                    Batas jadwal pengembalian buku ini adalah <strong>${userLoan.tanggal_jatuh_tempo || '-'}</strong>. Status stok buku akan kembali bertambah setelah pengembalian diselesaikan.
                                </p>
                            </div>
                        </div>
                    `;
                    const submitLabel = document.getElementById('return-submit-label');
                    if (submitLabel) submitLabel.textContent = 'Kembalikan Buku Sekarang';
                }
            }

            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden', 'modal-open');
            const mainHeader = document.getElementById('main-header') || document.querySelector('header');
            if (mainHeader) {
                mainHeader.classList.add('opacity-0', 'pointer-events-none', '-translate-y-full', 'invisible');
            }
        }

        function closeReturnBookModal() {
            const modal = document.getElementById('return-book-modal');
            if (modal) {
                modal.classList.add('hidden');
                const detailModal = document.getElementById('book-detail-modal');
                const borrowModal = document.getElementById('borrow-modal');
                const anyModalOpen = (detailModal && !detailModal.classList.contains('hidden')) || (borrowModal && !borrowModal.classList.contains('hidden'));
                if (!anyModalOpen) {
                    document.body.classList.remove('overflow-hidden', 'modal-open');
                    const mainHeader = document.getElementById('main-header') || document.querySelector('header');
                    if (mainHeader) {
                        mainHeader.classList.remove('opacity-0', 'pointer-events-none', '-translate-y-full', 'invisible');
                    }
                }
            }
        }

        function submitReturnBook(event) {
            event.preventDefault();
            const idPeminjaman = document.getElementById('return-input-id-peminjaman').value;
            const catatan = document.getElementById('return-input-catatan').value.trim();
            const submitBtn = document.getElementById('return-submit-btn');
            const errorBox = document.getElementById('return-form-error');

            if (!idPeminjaman) {
                alert('Pengembalian buku gagal: ID Peminjaman tidak valid.');
                return;
            }

            if (errorBox) {
                errorBox.classList.add('hidden');
                errorBox.textContent = '';
            }

            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-xs"></i><span>Memvalidasi & Memproses Pengembalian...</span>';
            }

            fetch(window.MNCU_AUTH.routes.kembalikan, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    id_peminjaman: idPeminjaman,
                    catatan: catatan
                })
            })
            .then(function (res) {
                return res.json().then(function (data) {
                    return { ok: res.ok, status: res.status, data: data };
                });
            })
            .then(function (result) {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fa-solid fa-arrow-rotate-left text-xs"></i><span>Ya, Kembalikan Buku</span>';
                }

                if (!result.ok || !result.data.success) {
                    const errMsg = (result.data && result.data.message) ? result.data.message : 'Pengembalian buku gagal: Terjadi kesalahan saat memproses data transaksi.';
                    if (errorBox) {
                        errorBox.textContent = errMsg;
                        errorBox.classList.remove('hidden');
                    } else {
                        alert(errMsg);
                    }
                    return;
                }

                // Sukses sesuai flowchart: Buku berhasil dikembalikan oleh user & Status buku akan kembali bertambah
                if (window.currentSelectedBookItem) {
                    window.currentSelectedBookItem.user_active_loan = null;
                    window.currentSelectedBookItem.is_borrowed_by_user = false;
                    window.currentSelectedBookItem.copies_available = (window.currentSelectedBookItem.copies_available || 0) + 1;
                    window.currentSelectedBookItem.copies_borrowed = Math.max(0, (window.currentSelectedBookItem.copies_borrowed || 1) - 1);
                }

                closeReturnBookModal();

                alert(result.data.message || 'Buku berhasil dikembalikan oleh user! Status buku telah kembali bertambah menjadi Tersedia di rak perpustakaan.');

                // Segarkan katalog untuk memperbarui tampilan kartu buku dan stok
                window.location.reload();
            })
            .catch(function (err) {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fa-solid fa-arrow-rotate-left text-xs"></i><span>Ya, Kembalikan Buku</span>';
                }
                const msg = err.message || 'Pengembalian buku gagal: Terjadi gangguan koneksi jaringan.';
                if (errorBox) {
                    errorBox.textContent = msg;
                    errorBox.classList.remove('hidden');
                } else {
                    alert(msg);
                }
            });
        }

        // =========================================================================
        // 5. EVENT LISTENER KEYBOARD (ESCAPE), CLICK OUTSIDE, & AUTO-OPEN MODAL
        // =========================================================================
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeFilterModal();
                closeBookDetailModal();
                closeBorrowModal();
                closeReturnBookModal();
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

        document.getElementById('borrow-modal')?.addEventListener('click', function (e) {
            if (e.target === this) {
                closeBorrowModal();
            }
        });

        document.getElementById('return-book-modal')?.addEventListener('click', function (e) {
            if (e.target === this) {
                closeReturnBookModal();
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
                container.className = 'grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-6 2xl:grid-cols-6 opac-catalog-grid';

                document.querySelectorAll('.card-inner-layout').forEach(el => {
                    el.className = 'card-inner-layout flex flex-col h-full';
                });
                document.querySelectorAll('.card-cover-wrapper').forEach(el => {
                    el.className = 'card-cover-wrapper relative w-full aspect-[3/4] shrink-0 overflow-hidden bg-slate-100 border-b border-line/60';
                });
                document.querySelectorAll('.card-details-wrapper').forEach(el => {
                    el.className = 'card-details-wrapper p-3 flex flex-col flex-1 justify-between';
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
            // Pastikan elemen modal berada di root body
            ensureModalsInBody();

            // Inisialisasi tampilan katalog dengan default 'grid'
            const savedView = localStorage.getItem('mncu_opac_view') || 'grid';
            setCatalogView(savedView);

            // Buka modal secara otomatis jika ada buku terpilih dari controller atau query parameter
            @if (!empty($selectedBookItem))
                openBookDetailModal(@json($selectedBookItem));
            @else
                const urlParams = new URLSearchParams(window.location.search);
                const targetId = '{{ $selectedBookId ?? '' }}' || urlParams.get('buku');
                if (targetId && Array.isArray(allCatalogItems) && allCatalogItems.length > 0) {
                    const foundItem = allCatalogItems.find(item => String(item.id) === String(targetId));
                    if (foundItem) {
                        openBookDetailModal(foundItem);
                    }
                }
            @endif
        });
    </script>
@endsection
