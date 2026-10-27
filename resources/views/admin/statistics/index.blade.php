@extends('layouts.app')

@section('title', 'Statistik Perpustakaan | MNC University')
@section('description', 'Statistik operasional perpustakaan MNC University meliputi keanggotaan member, sirkulasi peminjaman, pengembalian buku, dan penambahan koleksi buku.')

@section('content')
    <div class="mx-auto flex w-full max-w-[1570px] flex-1 flex-col gap-6 px-3 sm:px-4 lg:px-6 xl:px-8 py-4 sm:py-6 lg:py-8 responsive-desktop-container">
        {{-- Breadcrumb & Header --}}
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <nav aria-label="Breadcrumb" class="flex items-center gap-2 text-xs text-muted sm:text-sm">
                    <a href="{{ route('beranda') }}" class="hover:text-primary transition-colors">Beranda</a>
                    <svg class="size-3.5 text-muted/60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
                    <a href="{{ route('opac') }}" class="hover:text-primary transition-colors">Katalog OPAC</a>
                    <svg class="size-3.5 text-muted/60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
                    <span class="font-medium text-ink">Statistik Perpustakaan</span>
                </nav>
                <div class="mt-1 flex flex-wrap items-center gap-2.5">
                    <h1 class="text-2xl font-bold tracking-tight text-primary sm:text-3xl">
                        Statistik Perpustakaan MNC University
                    </h1>
                    <span class="inline-flex items-center gap-1.5 rounded-full border border-indigo-200 bg-indigo-50 px-3 py-0.5 text-xs font-semibold text-indigo-700 shadow-xs">
                        <i class="fa-solid fa-chart-pie text-[11px]"></i>
                        <span>{{ $periodeLabel }}</span>
                    </span>
                </div>
                <p class="mt-0.5 text-xs text-ink-secondary sm:text-sm">
                    Visualisasi data operasional perpustakaan: keanggotaan member, buku yang dipinjam &amp; dikembalikan, serta penambahan koleksi.
                </p>
            </div>

            {{-- Action Buttons Navigasi --}}
            <div class="flex flex-wrap items-center gap-2.5">
                <a
                    href="{{ route('opac') }}"
                    class="btn-press inline-flex min-h-[42px] items-center gap-2 rounded-xl border border-line bg-white px-3.5 text-xs font-semibold text-ink shadow-xs transition-all hover:bg-subtle hover:text-primary active:scale-95 sm:text-sm"
                    title="Kembali ke Katalog OPAC"
                >
                    <i class="fa-solid fa-arrow-left text-xs text-muted" aria-hidden="true"></i>
                    <span>Katalog OPAC</span>
                </a>
                <a
                    href="{{ route('admin.users.index') }}"
                    class="btn-press inline-flex min-h-[42px] items-center gap-2 rounded-xl bg-emerald-600 px-3.5 text-xs font-semibold text-white shadow-xs transition-all hover:bg-emerald-700 active:scale-95 sm:text-sm"
                    title="Buka Manajemen Member"
                >
                    <i class="fa-solid fa-id-card text-xs" aria-hidden="true"></i>
                    <span>Manajemen Member</span>
                </a>
                <a
                    href="{{ route('admin.reports.index') }}"
                    class="btn-press inline-flex min-h-[42px] items-center gap-2 rounded-xl bg-primary px-3.5 text-xs font-semibold text-white shadow-xs transition-all hover:bg-primary-dark active:scale-95 sm:text-sm"
                    title="Buka Laporan Perpustakaan (Report)"
                >
                    <i class="fa-solid fa-file-lines text-xs" aria-hidden="true"></i>
                    <span>Laporan (Report)</span>
                </a>
            </div>
        </div>

        {{-- FILTER PERIODE SESUAI FLOWCHART: "PILIH TAHUN, BULAN, ATAU MINGGU" --}}
        <section aria-labelledby="filter-periode-heading" class="rounded-2xl border border-line bg-white p-4 sm:p-5 shadow-xs">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h2 id="filter-periode-heading" class="text-sm font-bold text-ink sm:text-base">
                        <i class="fa-solid fa-calendar-check text-indigo-600 mr-2"></i>Filter Rentang Periode Statistik
                    </h2>
                    <p class="text-xs text-muted">
                        Pilih tahun, bulan, atau minggu untuk mengamati tren sirkulasi &amp; keanggotaan pada rentang waktu tertentu.
                    </p>
                </div>

                <form method="GET" action="{{ route('admin.statistics.index') }}" class="flex flex-wrap items-center gap-2.5">
                    {{-- Tipe Periode --}}
                    <div class="flex items-center rounded-xl border border-line bg-subtle p-0.5 text-xs font-semibold">
                        <label class="cursor-pointer">
                            <input type="radio" name="periode" value="minggu" class="sr-only" onchange="this.form.submit()" @checked($periode === 'minggu')>
                            <span class="inline-block rounded-lg px-2.5 py-1.5 transition-colors {{ $periode === 'minggu' ? 'bg-white text-primary shadow-xs' : 'text-muted hover:text-ink' }}">Mingguan</span>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="periode" value="bulan" class="sr-only" onchange="this.form.submit()" @checked($periode === 'bulan')>
                            <span class="inline-block rounded-lg px-2.5 py-1.5 transition-colors {{ $periode === 'bulan' ? 'bg-white text-primary shadow-xs' : 'text-muted hover:text-ink' }}">Bulanan</span>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="periode" value="tahun" class="sr-only" onchange="this.form.submit()" @checked($periode === 'tahun')>
                            <span class="inline-block rounded-lg px-2.5 py-1.5 transition-colors {{ $periode === 'tahun' ? 'bg-white text-primary shadow-xs' : 'text-muted hover:text-ink' }}">Tahunan</span>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="periode" value="semua" class="sr-only" onchange="this.form.submit()" @checked($periode === 'semua')>
                            <span class="inline-block rounded-lg px-2.5 py-1.5 transition-colors {{ $periode === 'semua' ? 'bg-white text-primary shadow-xs' : 'text-muted hover:text-ink' }}">Semua</span>
                        </label>
                    </div>

                    {{-- Dropdown Tahun --}}
                    @if ($periode !== 'semua')
                        <select
                            name="tahun"
                            class="h-9 rounded-xl border border-line bg-white px-3 text-xs font-semibold text-ink focus:border-primary focus:outline-none"
                            onchange="this.form.submit()"
                        >
                            @foreach ($tahunList as $th)
                                <option value="{{ $th }}" @selected($tahun == $th)>Tahun {{ $th }}</option>
                            @endforeach
                        </select>
                    @endif

                    {{-- Dropdown Bulan --}}
                    @if (in_array($periode, ['bulan', 'minggu']))
                        <select
                            name="bulan"
                            class="h-9 rounded-xl border border-line bg-white px-3 text-xs font-semibold text-ink focus:border-primary focus:outline-none"
                            onchange="this.form.submit()"
                        >
                            @foreach ([1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'] as $mNum => $mName)
                                <option value="{{ $mNum }}" @selected($bulan == $mNum)>{{ $mName }}</option>
                            @endforeach
                        </select>
                    @endif

                    {{-- Dropdown Minggu --}}
                    @if ($periode === 'minggu')
                        <select
                            name="minggu"
                            class="h-9 rounded-xl border border-line bg-white px-3 text-xs font-semibold text-ink focus:border-primary focus:outline-none"
                            onchange="this.form.submit()"
                        >
                            @for ($w = 1; $w <= 5; $w++)
                                <option value="{{ $w }}" @selected($minggu == $w)>Minggu ke-{{ $w }}</option>
                            @endfor
                        </select>
                    @endif

                    <button
                        type="submit"
                        class="btn-press inline-flex h-9 items-center gap-1.5 rounded-xl bg-indigo-600 px-3.5 text-xs font-semibold text-white shadow-xs hover:bg-indigo-700 active:scale-95"
                    >
                        <i class="fa-solid fa-rotate text-xs"></i>
                        <span>Muat Data</span>
                    </button>
                </form>
            </div>
        </section>

        {{-- ========================================================================= --}}
        {{-- 4 PILAR STATISTIK SESUAI FLOWCHART                                        --}}
        {{-- ========================================================================= --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

            {{-- 1. MENAMPILKAN STATISTIK DATA MEMBER --}}
            <section class="flex flex-col rounded-2xl border border-line bg-white p-5 shadow-xs">
                <div class="flex items-center justify-between border-b border-line pb-3.5">
                    <div class="flex items-center gap-3">
                        <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">
                            <i class="fa-solid fa-id-card text-base" aria-hidden="true"></i>
                        </span>
                        <div>
                            <h2 class="text-sm sm:text-base font-bold text-ink">1. Statistik Data Member</h2>
                            <p class="text-xs text-muted">Aktivitas pendaftaran &amp; total anggota perpustakaan aktif</p>
                        </div>
                    </div>
                    <span class="rounded-full bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 text-xs font-bold text-emerald-700">
                        {{ $totalMemberBaru }} Member Baru
                    </span>
                </div>

                {{-- Metrik Ringkasan --}}
                <div class="mt-4 grid grid-cols-3 gap-3 text-center">
                    <div class="rounded-xl border border-slate-200 bg-slate-50/70 p-3">
                        <p class="text-[11px] font-semibold text-muted">Total Member Aktif</p>
                        <p class="mt-1 text-xl font-bold text-ink">{{ $totalMemberAktif }}</p>
                    </div>
                    <div class="rounded-xl border border-emerald-200 bg-emerald-50/70 p-3">
                        <p class="text-[11px] font-semibold text-emerald-800">Member Mahasiswa</p>
                        <p class="mt-1 text-xl font-bold text-emerald-700">{{ $totalMemberMahasiswa }}</p>
                    </div>
                    <div class="rounded-xl border border-blue-200 bg-blue-50/70 p-3">
                        <p class="text-[11px] font-semibold text-blue-800">Member Dosen</p>
                        <p class="mt-1 text-xl font-bold text-primary">{{ $totalMemberDosen }}</p>
                    </div>
                </div>

                {{-- Tabel Daftar Member Baru pada Periode Ini --}}
                <div class="mt-4 flex-1">
                    <h3 class="mb-2 text-xs font-bold uppercase tracking-wider text-muted">
                        Member yang Terdaftar pada Periode Ini:
                    </h3>
                    <div class="max-h-60 overflow-y-auto rounded-xl border border-line">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-subtle text-muted">
                                <tr>
                                    <th class="px-3 py-2 font-semibold">Nama / NIM</th>
                                    <th class="px-3 py-2 font-semibold">Peran &amp; Prodi</th>
                                    <th class="px-3 py-2 font-semibold text-right">No. Anggota</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-line">
                                @forelse ($memberBaruList as $mbr)
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-3 py-2.5">
                                            <p class="font-semibold text-ink">{{ $mbr->nama_lengkap }}</p>
                                            <p class="text-[11px] text-muted">{{ $mbr->nomor_identitas ?? '-' }}</p>
                                        </td>
                                        <td class="px-3 py-2.5">
                                            <span class="inline-block rounded px-1.5 py-0.5 text-[10px] font-semibold {{ $mbr->isMahasiswa() ? 'bg-emerald-100 text-emerald-800' : 'bg-blue-100 text-blue-800' }}">
                                                {{ $mbr->peran?->nama_peran ?? 'Member' }}
                                            </span>
                                            <p class="text-[11px] text-muted truncate max-w-[140px]">{{ $mbr->prodi?->nama_prodi ?? '-' }}</p>
                                        </td>
                                        <td class="px-3 py-2.5 text-right font-mono text-[11px] text-primary font-semibold">
                                            {{ $mbr->nomor_anggota ?? $mbr->id_pengguna }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-3 py-6 text-center text-xs text-muted">
                                            Tidak ada member baru yang terdaftar pada rentang periode ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            {{-- 2. MENAMPILKAN STATISTIK DATA MEMBER YANG MENGEMBALIKAN BUKU & DATA BUKU YANG DIKEMBALIKAN --}}
            <section class="flex flex-col rounded-2xl border border-line bg-white p-5 shadow-xs">
                <div class="flex items-center justify-between border-b border-line pb-3.5">
                    <div class="flex items-center gap-3">
                        <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-teal-100 text-teal-700">
                            <i class="fa-solid fa-arrow-rotate-left text-base" aria-hidden="true"></i>
                        </span>
                        <div>
                            <h2 class="text-sm sm:text-base font-bold text-ink">2. Statistik Pengembalian Buku</h2>
                            <p class="text-xs text-muted">Data member yang mengembalikan &amp; buku yang dikembalikan</p>
                        </div>
                    </div>
                    <span class="rounded-full bg-teal-50 border border-teal-200 px-2.5 py-0.5 text-xs font-bold text-teal-800">
                        {{ $totalBukuDikembalikan }} Buku Dikembalikan
                    </span>
                </div>

                {{-- Metrik Ringkasan --}}
                <div class="mt-4 grid grid-cols-2 gap-3 text-center">
                    <div class="rounded-xl border border-teal-200 bg-teal-50/60 p-3">
                        <p class="text-[11px] font-semibold text-teal-800">Total Transaksi Pengembalian</p>
                        <p class="mt-1 text-xl font-bold text-teal-900">{{ $totalBukuDikembalikan }}</p>
                    </div>
                    <div class="rounded-xl border border-slate-200 bg-slate-50/70 p-3">
                        <p class="text-[11px] font-semibold text-muted">Total Member Mengembalikan</p>
                        <p class="mt-1 text-xl font-bold text-ink">{{ $totalMemberMengembalikan }} Member</p>
                    </div>
                </div>

                {{-- Tabel Buku yang Dikembalikan --}}
                <div class="mt-4 flex-1">
                    <h3 class="mb-2 text-xs font-bold uppercase tracking-wider text-muted">
                        Daftar Transaksi Pengembalian Buku Periode Ini:
                    </h3>
                    <div class="max-h-60 overflow-y-auto rounded-xl border border-line">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-subtle text-muted">
                                <tr>
                                    <th class="px-3 py-2 font-semibold">Peminjam</th>
                                    <th class="px-3 py-2 font-semibold">Judul Buku &amp; Barcode</th>
                                    <th class="px-3 py-2 font-semibold text-right">Tgl Kembali</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-line">
                                @forelse ($pengembalianList as $ret)
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-3 py-2.5">
                                            <p class="font-semibold text-ink">{{ $ret->pengguna?->nama_lengkap ?? 'Member' }}</p>
                                            <p class="text-[10px] text-muted">{{ $ret->pengguna?->nomor_identitas ?? '-' }}</p>
                                        </td>
                                        <td class="px-3 py-2.5">
                                            <p class="font-medium text-ink truncate max-w-[200px]" title="{{ $ret->buku?->judul_buku }}">{{ $ret->buku?->judul_buku ?? 'Buku Perpustakaan' }}</p>
                                            <span class="font-mono text-[10px] text-muted">BC: {{ $ret->eksemplar?->nomor_barcode ?? '-' }}</span>
                                        </td>
                                        <td class="px-3 py-2.5 text-right whitespace-nowrap">
                                            <span class="inline-block rounded bg-teal-100 px-1.5 py-0.5 text-[10px] font-bold text-teal-800">
                                                {{ $ret->tanggal_kembali ? $ret->tanggal_kembali->format('d M Y') : '-' }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-3 py-6 text-center text-xs text-muted">
                                            Belum ada data buku yang dikembalikan pada periode ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            {{-- 3. MENAMPILKAN STATISTIK DATA MEMBER YANG MEMINJAM & DATA BUKU YANG DIPINJAM --}}
            <section class="flex flex-col rounded-2xl border border-line bg-white p-5 shadow-xs">
                <div class="flex items-center justify-between border-b border-line pb-3.5">
                    <div class="flex items-center gap-3">
                        <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-primary">
                            <i class="fa-solid fa-hand-holding-hand text-base" aria-hidden="true"></i>
                        </span>
                        <div>
                            <h2 class="text-sm sm:text-base font-bold text-ink">3. Statistik Peminjaman Buku</h2>
                            <p class="text-xs text-muted">Data member yang meminjam &amp; buku yang sedang dipinjam</p>
                        </div>
                    </div>
                    <span class="rounded-full bg-blue-50 border border-blue-200 px-2.5 py-0.5 text-xs font-bold text-primary">
                        {{ $totalBukuDipinjam }} Peminjaman
                    </span>
                </div>

                {{-- Metrik Ringkasan --}}
                <div class="mt-4 grid grid-cols-2 gap-3 text-center">
                    <div class="rounded-xl border border-blue-200 bg-blue-50/60 p-3">
                        <p class="text-[11px] font-semibold text-blue-800">Total Transaksi Peminjaman</p>
                        <p class="mt-1 text-xl font-bold text-primary">{{ $totalBukuDipinjam }}</p>
                    </div>
                    <div class="rounded-xl border border-slate-200 bg-slate-50/70 p-3">
                        <p class="text-[11px] font-semibold text-muted">Total Member Meminjam</p>
                        <p class="mt-1 text-xl font-bold text-ink">{{ $totalMemberMeminjam }} Member</p>
                    </div>
                </div>

                {{-- Tabel Buku yang Dipinjam --}}
                <div class="mt-4 flex-1">
                    <h3 class="mb-2 text-xs font-bold uppercase tracking-wider text-muted">
                        Daftar Transaksi Peminjaman Periode Ini:
                    </h3>
                    <div class="max-h-60 overflow-y-auto rounded-xl border border-line">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-subtle text-muted">
                                <tr>
                                    <th class="px-3 py-2 font-semibold">Peminjam</th>
                                    <th class="px-3 py-2 font-semibold">Buku &amp; Barcode</th>
                                    <th class="px-3 py-2 font-semibold text-right">Tgl Pinjam / Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-line">
                                @forelse ($peminjamanList as $brw)
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-3 py-2.5">
                                            <p class="font-semibold text-ink">{{ $brw->pengguna?->nama_lengkap ?? 'Member' }}</p>
                                            <p class="text-[10px] text-muted">{{ $brw->pengguna?->nomor_identitas ?? '-' }}</p>
                                        </td>
                                        <td class="px-3 py-2.5">
                                            <p class="font-medium text-ink truncate max-w-[200px]" title="{{ $brw->buku?->judul_buku }}">{{ $brw->buku?->judul_buku ?? 'Buku Perpustakaan' }}</p>
                                            <span class="font-mono text-[10px] text-muted">BC: {{ $brw->eksemplar?->nomor_barcode ?? '-' }}</span>
                                        </td>
                                        <td class="px-3 py-2.5 text-right whitespace-nowrap">
                                            <p class="font-medium text-ink">{{ $brw->tanggal_pinjam ? $brw->tanggal_pinjam->format('d M Y') : '-' }}</p>
                                            <span class="inline-block rounded px-1.5 py-0.2 text-[10px] font-bold {{ $brw->status === 'Kembali' ? 'bg-emerald-100 text-emerald-800' : ($brw->status === 'Terlambat' ? 'bg-red-100 text-red-800' : 'bg-amber-100 text-amber-800') }}">
                                                {{ $brw->status }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-3 py-6 text-center text-xs text-muted">
                                            Belum ada transaksi peminjaman buku pada periode ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            {{-- 4. MENAMPILKAN STATISTIK DATA BUKU YANG DITAMBAH --}}
            <section class="flex flex-col rounded-2xl border border-line bg-white p-5 shadow-xs">
                <div class="flex items-center justify-between border-b border-line pb-3.5">
                    <div class="flex items-center gap-3">
                        <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-purple-100 text-purple-700">
                            <i class="fa-solid fa-book-medical text-base" aria-hidden="true"></i>
                        </span>
                        <div>
                            <h2 class="text-sm sm:text-base font-bold text-ink">4. Statistik Buku yang Ditambah</h2>
                            <p class="text-xs text-muted">Koleksi judul buku baru dan eksemplar yang diinput ke sistem</p>
                        </div>
                    </div>
                    <span class="rounded-full bg-purple-50 border border-purple-200 px-2.5 py-0.5 text-xs font-bold text-purple-700">
                        +{{ $totalBukuDitambah }} Judul Baru
                    </span>
                </div>

                {{-- Metrik Ringkasan --}}
                <div class="mt-4 grid grid-cols-2 gap-3 text-center">
                    <div class="rounded-xl border border-purple-200 bg-purple-50/60 p-3">
                        <p class="text-[11px] font-semibold text-purple-800">Judul Buku Ditambahkan</p>
                        <p class="mt-1 text-xl font-bold text-purple-900">+{{ $totalBukuDitambah }} Judul</p>
                    </div>
                    <div class="rounded-xl border border-slate-200 bg-slate-50/70 p-3">
                        <p class="text-[11px] font-semibold text-muted">Eksemplar Fisik Ditambahkan</p>
                        <p class="mt-1 text-xl font-bold text-ink">+{{ $totalEksemplarDitambah }} Eksemplar</p>
                    </div>
                </div>

                {{-- Tabel Buku yang Baru Ditambahkan --}}
                <div class="mt-4 flex-1">
                    <h3 class="mb-2 text-xs font-bold uppercase tracking-wider text-muted">
                        Daftar Buku yang Baru Ditambahkan Periode Ini:
                    </h3>
                    <div class="max-h-60 overflow-y-auto rounded-xl border border-line">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-subtle text-muted">
                                <tr>
                                    <th class="px-3 py-2 font-semibold">Judul Buku</th>
                                    <th class="px-3 py-2 font-semibold">Kategori / Penerbit</th>
                                    <th class="px-3 py-2 font-semibold text-right">Eksemplar</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-line">
                                @forelse ($bukuBaruList as $bk)
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-3 py-2.5">
                                            <p class="font-semibold text-ink truncate max-w-[220px]" title="{{ $bk->judul_buku }}">{{ $bk->judul_buku }}</p>
                                            <p class="text-[10px] text-muted">ISBN: {{ $bk->isbn ?: '-' }} ({{ $bk->tahun_terbit }})</p>
                                        </td>
                                        <td class="px-3 py-2.5">
                                            <span class="inline-block rounded bg-purple-100 px-1.5 py-0.2 text-[10px] font-semibold text-purple-800">
                                                {{ $bk->kategoriBuku?->nama_kategori ?? 'Umum' }}
                                            </span>
                                            <p class="text-[10px] text-muted truncate max-w-[140px]">{{ $bk->penerbit?->nama_penerbit ?? '-' }}</p>
                                        </td>
                                        <td class="px-3 py-2.5 text-right font-bold text-ink whitespace-nowrap">
                                            {{ $bk->eksemplar?->count() ?? 0 }} Eks
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-3 py-6 text-center text-xs text-muted">
                                            Tidak ada penambahan koleksi buku baru pada rentang periode ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
