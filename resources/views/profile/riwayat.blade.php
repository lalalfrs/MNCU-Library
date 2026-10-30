@extends('layouts.app')

@section('title', 'Riwayat Peminjaman Saya | Perpustakaan MNC University')
@section('description', 'Riwayat transaksi sirkulasi peminjaman dan pengembalian buku pemustaka di Perpustakaan MNC University.')

@section('content')
<div class="min-h-screen bg-slate-50/70 py-8 lg:py-12">
    <div class="mx-auto w-full max-w-6xl px-4 sm:px-6 lg:px-8">
        {{-- Breadcrumbs (print:hidden) --}}
        <nav class="mb-4 flex items-center gap-2 text-xs font-medium text-muted sm:text-sm print:hidden" aria-label="Breadcrumb">
            <a href="{{ route('beranda') }}" class="inline-flex min-h-[44px] items-center text-muted transition-colors hover:text-primary">
                <i class="fa-solid fa-house mr-1.5 text-xs text-slate-400" aria-hidden="true"></i>
                <span>Beranda</span>
            </a>
            <i class="fa-solid fa-chevron-right text-[10px] text-slate-400" aria-hidden="true"></i>
            <a href="{{ route('profile.show') }}" class="inline-flex min-h-[44px] items-center text-muted transition-colors hover:text-primary">
                <span>Profil</span>
            </a>
            <i class="fa-solid fa-chevron-right text-[10px] text-slate-400" aria-hidden="true"></i>
            <span class="inline-flex min-h-[44px] items-center font-semibold text-ink" aria-current="page">Riwayat Peminjaman</span>
        </nav>

        {{-- DOKUMEN CETAK PDF RESMI (KHUSUS TAMPIL SAAT CETAK / WINDOW.PRINT) --}}
        {{-- Layout persis sesuai format resmi Lembar Riwayat & Bebas Sirkulasi Perpustakaan MNC University --}}
        <div class="hidden print:block w-full bg-white text-slate-900 font-sans print:p-0">
            {{-- Header Surat --}}
            <div class="text-center">
                <h2 class="text-sm font-bold uppercase tracking-wider text-[#0F2A6B]">PERPUSTAKAAN MNC UNIVERSITY</h2>
                <h1 class="mt-0.5 text-xl font-extrabold uppercase tracking-wide text-[#0F2A6B]">
                    LEMBAR RIWAYAT &amp; BEBAS SIRKULASI PEMINJAMAN BUKU
                </h1>
                <p class="mt-1 text-xs italic text-slate-600">
                    Gedung MNC University, Jl. Arteri Kedoya, Jakarta Barat | Tanggal Cetak: {{ now()->translatedFormat('d F Y') }}
                </p>
            </div>

            <div class="mt-3 mb-4 border-b-2 border-[#0F2A6B]"></div>

            {{-- 1. DATA PEMUSTAKA --}}
            <div class="overflow-hidden rounded-t border border-[#0F2A6B]">
                <div class="bg-[#0F2A6B] py-1 text-center text-xs font-bold uppercase tracking-wider text-white">
                    DATA PEMUSTAKA
                </div>
                <div class="grid grid-cols-2 divide-x divide-slate-300 bg-white">
                    <div class="p-3 space-y-2.5">
                        <div>
                            <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-500">NAMA LENGKAP PEMUSTAKA</span>
                            <span class="block text-base font-extrabold text-[#0F2A6B] leading-snug">{{ $user->nama_lengkap }}</span>
                        </div>
                        <div>
                            <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-500">NOMOR IDENTITAS (NIM/NIDN)</span>
                            <span class="block text-sm font-bold text-slate-900">{{ $user->nomor_identitas ?? $user->nim ?? $user->nidn ?? $user->id_pengguna }}</span>
                        </div>
                    </div>
                    <div class="p-3 space-y-2.5">
                        <div>
                            <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-500">STATUS KEANGGOTAAN</span>
                            <div class="mt-0.5">
                                <span class="inline-block rounded bg-emerald-50 px-2 py-0.5 text-xs font-bold text-emerald-600 border border-emerald-200">
                                    Member Resmi Aktif ({{ ucfirst($role) }})
                                </span>
                            </div>
                        </div>
                        <div>
                            <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-500">TOTAL TRANSAKSI TERDAFTAR</span>
                            <span class="block text-sm font-bold text-slate-900">{{ $ringkasan['total'] ?? $riwayatPeminjaman->total() }} Transaksi</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. RIWAYAT TRANSAKSI PEMINJAMAN BUKU --}}
            <div class="mt-4 overflow-hidden border border-[#0F2A6B]">
                <div class="bg-[#0F2A6B] py-1 text-center text-xs font-bold uppercase tracking-wider text-white">
                    RIWAYAT TRANSAKSI PEMINJAMAN BUKU
                </div>
                <table class="w-full text-xs border-collapse">
                    <thead>
                        <tr class="bg-[#0F2A6B] text-white font-bold uppercase text-[11px]">
                            <th class="border border-white/30 px-2 py-2 w-12 text-center">NO</th>
                            <th class="border border-white/30 px-3 py-2 text-left">NAMA BUKU</th>
                            <th class="border border-white/30 px-3 py-2 w-44 text-center">TANGGAL PEMINJAMAN</th>
                            <th class="border border-white/30 px-3 py-2 w-64 text-center">TANGGAL PENGEMBALIAN</th>
                            <th class="border border-white/30 px-3 py-2 w-36 text-center">STATUS SIRKULASI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-300">
                        @forelse ($riwayatPeminjaman as $index => $pinjam)
                            @php
                                $hariTerlambat = method_exists($pinjam, 'hitungHariTerlambat') ? $pinjam->hitungHariTerlambat() : 0;
                            @endphp
                            <tr class="text-slate-800">
                                <td class="border border-slate-300 px-2 py-2.5 text-center font-medium">{{ $loop->iteration }}</td>
                                <td class="border border-slate-300 px-3 py-2.5 text-left font-medium">
                                    {{ $pinjam->buku?->judul_buku ?? 'Judul Buku Tidak Ditemukan' }} <span class="text-slate-500 font-mono">(ID: {{ $pinjam->id_peminjaman }})</span>
                                </td>
                                <td class="border border-slate-300 px-3 py-2.5 text-center whitespace-nowrap">
                                    {{ $pinjam->tanggal_pinjam ? $pinjam->tanggal_pinjam->format('d M Y') : ($pinjam->created_at ? $pinjam->created_at->format('d M Y') : '-') }}
                                </td>
                                <td class="border border-slate-300 px-3 py-2.5 text-center whitespace-nowrap">
                                    @php
                                        $isKembali = in_array($pinjam->status, ['Kembali', 'Dikembalikan']) || ! empty($pinjam->tanggal_kembali);
                                    @endphp
                                    @if ($pinjam->tanggal_kembali)
                                        {{ $pinjam->tanggal_kembali->format('d M Y') }} <span class="text-emerald-700 font-semibold">(Selesai Dikembalikan)</span>
                                    @elseif ($isKembali)
                                        {{ $pinjam->tanggal_jatuh_tempo ? $pinjam->tanggal_jatuh_tempo->format('d M Y') : '-' }} <span class="text-emerald-700 font-semibold">(Selesai Dikembalikan)</span>
                                    @elseif ($pinjam->tanggal_jatuh_tempo)
                                        {{ $pinjam->tanggal_jatuh_tempo->format('d M Y') }} <span class="{{ $hariTerlambat > 0 ? 'text-rose-600 font-semibold' : 'text-slate-600' }}">(Jatuh Tempo)</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="border border-slate-300 px-3 py-2.5 text-center whitespace-nowrap">
                                    @if ($isKembali)
                                        <span class="font-bold text-emerald-600">Kembali</span>
                                    @elseif ($pinjam->status === 'Dipinjam')
                                        <span class="font-bold text-blue-600">Dipinjam</span>
                                    @elseif ($pinjam->status === 'Terlambat' || $hariTerlambat > 0)
                                        <span class="font-bold text-rose-600">Terlambat</span>
                                    @elseif (in_array($pinjam->status, ['Menunggu Verifikasi', 'Menunggu Persetujuan']))
                                        <span class="font-bold text-indigo-600">Verifikasi</span>
                                    @else
                                        <span class="font-bold text-slate-700">{{ $pinjam->status }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="border border-slate-300 px-3 py-4 text-center text-slate-500 italic">
                                    Tidak ada transaksi peminjaman buku yang tercatat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="border border-slate-300 bg-slate-50 px-3 py-2 text-right font-bold italic text-slate-700 text-xs">
                                @if (($ringkasan['aktif'] ?? 0) === 0)
                                    Total Transaksi: {{ $riwayatPeminjaman->total() }} Buku | Seluruh Koleksi Telah Selesai Dikembalikan
                                @else
                                    Total Transaksi: {{ $riwayatPeminjaman->total() }} Buku | {{ $ringkasan['aktif'] }} Buku Sedang Dipinjam
                                @endif
                            </td>
                            <td class="border border-slate-300 px-3 py-2 text-center font-bold text-xs {{ ($ringkasan['aktif'] ?? 0) === 0 ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-800' }}">
                                @if (($ringkasan['aktif'] ?? 0) === 0)
                                    BEBAS SIRKULASI
                                @else
                                    TANGGUNGAN AKTIF
                                @endif
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- 3. PERNYATAAN BEBAS SIRKULASI PERPUSTAKAAN --}}
            <div class="mt-4 rounded border border-emerald-300 bg-emerald-50/70 p-3 text-center">
                <h4 class="text-xs font-bold uppercase tracking-wider text-emerald-800">PERNYATAAN BEBAS SIRKULASI PERPUSTAKAAN</h4>
                <p class="mt-1 text-xs leading-relaxed text-emerald-950">
                    @if (($ringkasan['aktif'] ?? 0) === 0)
                        Berdasarkan data sirkulasi per tanggal {{ now()->translatedFormat('d F Y') }}, pemustaka atas nama <strong class="font-bold text-slate-900">{{ $user->nama_lengkap }}</strong> ({{ $role === 'dosen' ? 'NIDN' : 'NIM' }}: {{ $user->nomor_identitas ?? $user->nim ?? $user->nidn ?? $user->id_pengguna }}) telah mengembalikan seluruh buku pinjaman dalam kondisi baik dan <strong class="font-bold">TIDAK MEMILIKI TANGGUNGAN</strong> sirkulasi maupun denda di Perpustakaan MNC University. Lembar ini sah digunakan sebagai bukti bebas pinjaman perpustakaan.
                    @else
                        Berdasarkan data sirkulasi per tanggal {{ now()->translatedFormat('d F Y') }}, pemustaka atas nama <strong class="font-bold text-slate-900">{{ $user->nama_lengkap }}</strong> ({{ $role === 'dosen' ? 'NIDN' : 'NIM' }}: {{ $user->nomor_identitas ?? $user->nim ?? $user->nidn ?? $user->id_pengguna }}) saat ini tercatat memiliki <strong class="font-bold">{{ $ringkasan['aktif'] }} buku pinjaman</strong> yang sedang berjalan di Perpustakaan MNC University. Harap menyelesaikan pengembalian seluruh koleksi sebelum batas waktu jatuh tempo.
                    @endif
                </p>
            </div>

            {{-- 4. TANDA TANGAN RESMI --}}
            <div class="mt-8 pt-4 flex justify-between items-start text-xs text-slate-800 print:break-inside-avoid px-8">
                <div class="text-center w-64">
                    <p>Mengetahui,</p>
                    <p class="font-bold text-[#0F2A6B] mt-1">Kepala Perpustakaan MNC University</p>
                    <div class="h-20"></div>
                    <p class="font-bold underline text-slate-900">Dr. H. Muhammad Arifin, M.Hum.</p>
                    <p class="text-[11px] text-slate-500 mt-0.5">NIP. 19780812 200501 1 002</p>
                </div>
                <div class="text-center w-64">
                    <p>Jakarta Barat, {{ now()->translatedFormat('d F Y') }}</p>
                    <p class="font-bold text-[#0F2A6B] mt-1">Pemustaka Yang Bersangkutan</p>
                    <div class="h-20"></div>
                    <p class="font-bold underline text-slate-900">{{ $user->nama_lengkap }}</p>
                    <p class="text-[11px] text-slate-500 mt-0.5">ID: {{ $user->nomor_identitas ?? $user->nim ?? $user->nidn ?? $user->id_pengguna }}</p>
                </div>
            </div>

            {{-- 5. CATATAN DOKUMEN --}}
            <div class="mt-8 text-center text-[10px] italic text-slate-500">
                Dokumen ini diterbitkan secara resmi oleh Perpustakaan MNC University dan sah tanpa cap basah apabila telah ditandatangani.
            </div>
        </div>

        {{-- TAMPILAN INTERAKTIF LAYAR / SCREEN (print:hidden) --}}
        <div class="print:hidden space-y-6">
            {{-- Page Header & Actions --}}
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between border-b border-line pb-6">
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-2xl font-bold tracking-tight text-ink sm:text-3xl">Riwayat Peminjaman Buku</h1>
                        <span class="rounded-full bg-blue-50 px-2.5 py-0.5 text-xs font-semibold text-primary border border-blue-200">
                            Pemustaka
                        </span>
                    </div>
                    <p class="mt-1 text-xs text-muted sm:text-sm">
                        Lacak status sirkulasi buku yang sedang Anda pinjam, riwayat pengembalian, dan tanggal jatuh tempo.
                    </p>
                </div>
                <div class="flex flex-wrap items-center gap-2.5">
                    <button
                        type="button"
                        onclick="window.print()"
                        class="btn-press inline-flex min-h-[42px] items-center gap-2 rounded-xl bg-primary px-4 text-xs font-semibold text-white shadow-xs transition-all hover:bg-primary-dark active:scale-95"
                        title="Cetak Bukti Riwayat / Simpan PDF"
                    >
                        <i class="fa-solid fa-print text-xs" aria-hidden="true"></i>
                        <span>Cetak PDF</span>
                    </button>
                    <button
                        type="button"
                        onclick="openPdfPreviewModal()"
                        class="btn-press inline-flex min-h-[42px] items-center gap-2 rounded-xl border border-line bg-white px-3.5 text-xs font-semibold text-ink shadow-xs transition-all hover:border-primary hover:text-primary active:scale-95"
                        title="Pratinjau Lembar Bebas Sirkulasi"
                    >
                        <i class="fa-solid fa-eye text-xs text-primary" aria-hidden="true"></i>
                        <span>Pratinjau Lembar</span>
                    </button>
                    <a
                        href="{{ route('opac') }}"
                        class="btn-press inline-flex min-h-[42px] items-center gap-2 rounded-xl border border-line bg-white px-3.5 text-xs font-semibold text-slate-700 shadow-xs transition-all hover:bg-slate-50 active:scale-95"
                    >
                        <i class="fa-solid fa-book-open text-xs text-muted" aria-hidden="true"></i>
                        <span>Katalog OPAC</span>
                    </a>
                </div>
            </div>

        {{-- 4 Kartu Metrik Ringkasan Riwayat Pemustaka --}}
        <div class="mb-6 grid grid-cols-2 gap-3 sm:grid-cols-4 sm:gap-4 print:grid-cols-4 print:gap-2 print:mb-4">
            <div class="rounded-2xl border border-line bg-white p-4 shadow-card print:border-slate-300 print:shadow-none print:p-2.5">
                <div class="flex items-center justify-between">
                    <span class="flex size-9 items-center justify-center rounded-xl bg-blue-50 text-primary">
                        <i class="fa-solid fa-book-bookmark text-sm" aria-hidden="true"></i>
                    </span>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-muted">Akumulasi</span>
                </div>
                <p class="mt-2 text-xl sm:text-2xl font-bold text-ink">{{ $ringkasan['total'] ?? 0 }}</p>
                <p class="text-[11px] font-medium text-muted">Total Buku Dipinjam</p>
            </div>

            <div class="rounded-2xl border border-amber-200 bg-amber-50/50 p-4 shadow-card print:border-slate-300 print:bg-white print:shadow-none print:p-2.5">
                <div class="flex items-center justify-between">
                    <span class="flex size-9 items-center justify-center rounded-xl bg-amber-100 text-amber-800">
                        <i class="fa-solid fa-clock text-sm" aria-hidden="true"></i>
                    </span>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-amber-800">Aktif</span>
                </div>
                <p class="mt-2 text-xl sm:text-2xl font-bold text-amber-900">{{ $ringkasan['aktif'] ?? 0 }}</p>
                <p class="text-[11px] font-medium text-amber-950">Sedang Berjalan</p>
            </div>

            <div class="rounded-2xl border border-emerald-200 bg-emerald-50/50 p-4 shadow-card print:border-slate-300 print:bg-white print:shadow-none print:p-2.5">
                <div class="flex items-center justify-between">
                    <span class="flex size-9 items-center justify-center rounded-xl bg-emerald-100 text-emerald-800">
                        <i class="fa-solid fa-circle-check text-sm" aria-hidden="true"></i>
                    </span>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-800">Tertib</span>
                </div>
                <p class="mt-2 text-xl sm:text-2xl font-bold text-emerald-900">{{ $ringkasan['selesai'] ?? 0 }}</p>
                <p class="text-[11px] font-medium text-emerald-950">Selesai Dikembalikan</p>
            </div>

            <div class="rounded-2xl border border-line bg-white p-4 shadow-card print:border-slate-300 print:shadow-none print:p-2.5">
                <div class="flex items-center justify-between">
                    <span class="flex size-9 items-center justify-center rounded-xl bg-rose-50 text-rose-700">
                        <i class="fa-solid fa-triangle-exclamation text-sm" aria-hidden="true"></i>
                    </span>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-rose-700">Tenggat</span>
                </div>
                <p class="mt-2 text-xl sm:text-2xl font-bold text-rose-700">{{ $ringkasan['terlambat'] ?? 0 }}</p>
                <p class="text-[11px] font-medium text-muted">Keterlambatan</p>
            </div>
        </div>

        {{-- Filter Status Tab (print:hidden) --}}
        <div class="mb-4 flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-line bg-white p-3 shadow-card print:hidden">
            <div class="inline-flex flex-wrap items-center rounded-xl bg-slate-100 p-1 text-xs font-medium">
                <a
                    href="{{ route('profile.riwayat', ['status' => 'semua']) }}"
                    class="inline-flex min-h-[36px] items-center rounded-lg px-3 py-1.5 transition-colors {{ in_array($statusFilter, ['semua', '']) ? 'bg-white font-bold text-primary shadow-xs' : 'text-slate-600 hover:text-ink' }}"
                >
                    Semua ({{ $ringkasan['total'] ?? 0 }})
                </a>
                <a
                    href="{{ route('profile.riwayat', ['status' => 'aktif']) }}"
                    class="inline-flex min-h-[36px] items-center gap-1.5 rounded-lg px-3 py-1.5 transition-colors {{ in_array($statusFilter, ['aktif', 'berjalan', 'sedang_berjalan']) ? 'bg-white font-bold text-amber-900 shadow-xs' : 'text-slate-600 hover:text-ink' }}"
                >
                    <span class="size-1.5 rounded-full bg-amber-500"></span>
                    <span>Sedang Berjalan ({{ $ringkasan['aktif'] ?? 0 }})</span>
                </a>
                <a
                    href="{{ route('profile.riwayat', ['status' => 'selesai']) }}"
                    class="inline-flex min-h-[36px] items-center gap-1.5 rounded-lg px-3 py-1.5 transition-colors {{ in_array($statusFilter, ['selesai', 'kembali', 'dikembalikan']) ? 'bg-white font-bold text-emerald-800 shadow-xs' : 'text-slate-600 hover:text-ink' }}"
                >
                    <span class="size-1.5 rounded-full bg-emerald-500"></span>
                    <span>Dikembalikan ({{ $ringkasan['selesai'] ?? 0 }})</span>
                </a>
                <a
                    href="{{ route('profile.riwayat', ['status' => 'terlambat']) }}"
                    class="inline-flex min-h-[36px] items-center gap-1.5 rounded-lg px-3 py-1.5 transition-colors {{ in_array($statusFilter, ['terlambat', 'keterlambatan']) ? 'bg-white font-bold text-rose-800 shadow-xs' : 'text-slate-600 hover:text-ink' }}"
                >
                    <span class="size-1.5 rounded-full bg-rose-500"></span>
                    <span>Keterlambatan ({{ $ringkasan['terlambat'] ?? 0 }})</span>
                </a>
                <a
                    href="{{ route('profile.riwayat', ['status' => 'menunggu']) }}"
                    class="inline-flex min-h-[36px] items-center gap-1.5 rounded-lg px-3 py-1.5 transition-colors {{ in_array($statusFilter, ['menunggu', 'verifikasi', 'menunggu_verifikasi']) ? 'bg-white font-bold text-indigo-800 shadow-xs' : 'text-slate-600 hover:text-ink' }}"
                >
                    <span class="size-1.5 rounded-full bg-indigo-500"></span>
                    <span>Menunggu Verifikasi ({{ $ringkasan['menunggu'] ?? 0 }})</span>
                </a>
            </div>

            <div class="text-xs text-muted">
                Menampilkan data peminjaman <strong>{{ $user->nama_lengkap }}</strong>
            </div>
        </div>

        {{-- Kontainer Tabel Utama & Card List --}}
        <div class="overflow-hidden rounded-2xl border border-line bg-white shadow-card print:border-slate-300 print:shadow-none">
            {{-- Tabel Desktop --}}
            <div class="hidden sm:block overflow-x-auto print:overflow-visible">
                <table class="w-full text-left text-xs print:text-[10px]">
                    <thead class="border-b border-line bg-subtle text-muted print:bg-slate-100 print:text-black">
                        <tr>
                            <th scope="col" class="px-5 py-3.5 font-bold uppercase tracking-wider text-[11px] print:px-2 print:py-2">No</th>
                            <th scope="col" class="px-5 py-3.5 font-bold uppercase tracking-wider text-[11px] print:px-2 print:py-2">Nama Buku</th>
                            <th scope="col" class="px-5 py-3.5 font-bold uppercase tracking-wider text-[11px] print:px-2 print:py-2">Tanggal Peminjaman</th>
                            <th scope="col" class="px-5 py-3.5 font-bold uppercase tracking-wider text-[11px] print:px-2 print:py-2">Tanggal Pengembalian</th>
                            <th scope="col" class="px-5 py-3.5 font-bold uppercase tracking-wider text-[11px] print:px-2 print:py-2">Status Sirkulasi</th>
                            <th scope="col" class="px-5 py-3.5 font-bold uppercase tracking-wider text-[11px] text-right print:hidden">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line/70 print:divide-slate-300">
                        @forelse ($riwayatPeminjaman as $index => $pinjam)
                            @php
                                $hariTerlambat = method_exists($pinjam, 'hitungHariTerlambat') ? $pinjam->hitungHariTerlambat() : 0;
                            @endphp
                            <tr class="transition-colors hover:bg-slate-50/70 print:break-inside-avoid">
                                <td class="px-5 py-4 font-mono text-muted text-xs print:px-2 print:py-2">
                                    {{ $riwayatPeminjaman->firstItem() + $index }}
                                </td>
                                <td class="px-5 py-4 print:px-2 print:py-2">
                                    <div class="flex items-start gap-3">
                                        <div class="size-11 shrink-0 overflow-hidden rounded-lg border border-line bg-slate-100 shadow-xs print:hidden">
                                            @if ($pinjam->buku && $pinjam->buku->sampul_url)
                                                <img src="{{ $pinjam->buku->sampul_url }}" alt="{{ $pinjam->buku->judul_buku }}" class="size-full object-cover">
                                            @else
                                                <div class="flex size-full items-center justify-center bg-primary/10 text-primary">
                                                    <i class="fa-solid fa-book text-sm" aria-hidden="true"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="min-w-0 max-w-[340px]">
                                            <p class="font-bold text-ink leading-snug line-clamp-2" title="{{ $pinjam->buku?->judul_buku }}">
                                                {{ $pinjam->buku?->judul_buku ?? 'Judul Buku Tidak Ditemukan' }}
                                            </p>
                                            <div class="mt-1 flex flex-wrap items-center gap-2 text-[11px] text-muted print:text-[9px]">
                                                <span class="font-mono">ID: {{ $pinjam->id_peminjaman }}</span>
                                                @if ($pinjam->eksemplar?->nomor_barcode)
                                                    <span>•</span>
                                                    <span class="font-mono">Barcode: {{ $pinjam->eksemplar->nomor_barcode }}</span>
                                                @endif
                                                @if ($pinjam->buku?->kategoriBuku?->nama_kategori_buku)
                                                    <span>•</span>
                                                    <span>{{ $pinjam->buku->kategoriBuku->nama_kategori_buku }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap print:px-2 print:py-2">
                                    <div class="flex items-center gap-1.5 font-semibold text-ink">
                                        <i class="fa-regular fa-calendar-check text-xs text-primary print:hidden" aria-hidden="true"></i>
                                        <span>{{ $pinjam->tanggal_pinjam ? $pinjam->tanggal_pinjam->format('d M Y') : ($pinjam->created_at ? $pinjam->created_at->format('d M Y') : '-') }}</span>
                                    </div>
                                    @if ($pinjam->tanggal_pengajuan_pinjam && ! $pinjam->tanggal_pinjam)
                                        <span class="mt-0.5 block text-[10px] text-muted">Pengajuan: {{ $pinjam->tanggal_pengajuan_pinjam->format('d M Y') }}</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap print:px-2 print:py-2">
                                    @php
                                        $isKembali = in_array($pinjam->status, ['Kembali', 'Dikembalikan']) || ! empty($pinjam->tanggal_kembali);
                                    @endphp
                                    @if ($pinjam->tanggal_kembali)
                                        <div class="flex items-center gap-1.5 font-bold text-emerald-700">
                                            <i class="fa-solid fa-circle-check text-xs text-emerald-600 print:hidden" aria-hidden="true"></i>
                                            <span>{{ $pinjam->tanggal_kembali->format('d M Y') }}</span>
                                        </div>
                                        <span class="mt-0.5 block text-[10px] text-muted">Selesai Dikembalikan</span>
                                    @elseif ($isKembali)
                                        <div class="flex items-center gap-1.5 font-bold text-emerald-700">
                                            <i class="fa-solid fa-circle-check text-xs text-emerald-600 print:hidden" aria-hidden="true"></i>
                                            <span>{{ $pinjam->tanggal_jatuh_tempo ? $pinjam->tanggal_jatuh_tempo->format('d M Y') : '-' }}</span>
                                        </div>
                                        <span class="mt-0.5 block text-[10px] text-muted">Selesai Dikembalikan</span>
                                    @else
                                        @if ($pinjam->tanggal_jatuh_tempo)
                                            <div class="flex items-center gap-1.5 font-bold {{ $hariTerlambat > 0 ? 'text-rose-600' : 'text-amber-700' }}">
                                                <i class="fa-regular fa-clock text-xs print:hidden" aria-hidden="true"></i>
                                                <span>{{ $pinjam->tanggal_jatuh_tempo->format('d M Y') }}</span>
                                            </div>
                                            @if ($hariTerlambat > 0)
                                                <span class="mt-0.5 block text-[10px] font-semibold text-rose-600">Terlambat {{ $hariTerlambat }} Hari</span>
                                            @else
                                                <span class="mt-0.5 block text-[10px] text-muted">Batas Waktu</span>
                                            @endif
                                        @else
                                            <span class="font-medium text-slate-500">Belum Ditentukan</span>
                                        @endif
                                    @endif
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap print:px-2 print:py-2">
                                    @if ($isKembali)
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 border border-emerald-200 print:border-slate-300">
                                            <i class="fa-solid fa-check text-[10px] print:hidden" aria-hidden="true"></i>
                                            <span>Dikembalikan</span>
                                        </span>
                                    @elseif ($pinjam->status === 'Dipinjam')
                                        @if ($hariTerlambat > 0)
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-rose-50 px-2.5 py-1 text-xs font-semibold text-rose-700 border border-rose-200 print:border-slate-300">
                                                <i class="fa-solid fa-triangle-exclamation text-[10px] print:hidden" aria-hidden="true"></i>
                                                <span>Terlambat</span>
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-50 px-2.5 py-1 text-xs font-semibold text-primary border border-blue-200 print:border-slate-300">
                                                <i class="fa-solid fa-book-open-reader text-[10px] print:hidden" aria-hidden="true"></i>
                                                <span>Sedang Dipinjam</span>
                                            </span>
                                        @endif
                                    @elseif (in_array($pinjam->status, ['Menunggu Verifikasi', 'Menunggu Persetujuan']))
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-indigo-50 px-2.5 py-1 text-xs font-semibold text-indigo-700 border border-indigo-200 print:border-slate-300">
                                            <i class="fa-solid fa-hourglass-half text-[10px] print:hidden" aria-hidden="true"></i>
                                            <span>{{ $pinjam->status }}</span>
                                        </span>
                                    @elseif ($pinjam->status === 'Terlambat')
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-rose-50 px-2.5 py-1 text-xs font-semibold text-rose-700 border border-rose-200 print:border-slate-300">
                                            <i class="fa-solid fa-triangle-exclamation text-[10px] print:hidden" aria-hidden="true"></i>
                                            <span>Terlambat</span>
                                        </span>
                                    @elseif ($pinjam->status === 'Ditolak')
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-rose-50 px-2.5 py-1 text-xs font-semibold text-rose-700 border border-rose-200 print:border-slate-300">
                                            <span>Ditolak</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-700">
                                            {{ $pinjam->status }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-right whitespace-nowrap print:hidden">
                                    <a
                                        href="{{ route('opac', ['cari' => $pinjam->buku?->judul_buku ?? '']) }}"
                                        class="inline-flex min-h-[34px] items-center gap-1.5 rounded-lg border border-line bg-white px-2.5 py-1 text-xs font-medium text-ink hover:bg-slate-50 hover:text-primary transition-colors"
                                        title="Buka katalog buku di OPAC"
                                    >
                                        <i class="fa-solid fa-book-open text-xs text-muted" aria-hidden="true"></i>
                                        <span>Lihat di OPAC</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="mx-auto flex size-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                                        <i class="fa-solid fa-book text-2xl" aria-hidden="true"></i>
                                    </div>
                                    <h3 class="mt-3 text-sm font-bold text-ink">Tidak Ada Riwayat Peminjaman</h3>
                                    <p class="mx-auto mt-1 max-w-sm text-xs text-muted leading-relaxed">
                                        Tidak ditemukan transaksi sirkulasi peminjaman buku dengan filter yang dipilih.
                                    </p>
                                    <div class="mt-4">
                                        <a href="{{ route('opac') }}" class="btn-press inline-flex min-h-[40px] items-center gap-2 rounded-xl bg-primary px-4 py-2 text-xs font-semibold text-white shadow-xs">
                                            <i class="fa-solid fa-magnifying-glass text-xs" aria-hidden="true"></i>
                                            <span>Jelajahi Katalog Buku (OPAC)</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Card List Mobile (print:hidden) --}}
            <div class="block sm:hidden divide-y divide-line/70 print:hidden">
                @forelse ($riwayatPeminjaman as $pinjam)
                    @php
                        $hariTerlambat = method_exists($pinjam, 'hitungHariTerlambat') ? $pinjam->hitungHariTerlambat() : 0;
                    @endphp
                    <div class="p-4 space-y-3">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-start gap-3 min-w-0">
                                <div class="size-12 shrink-0 overflow-hidden rounded-xl border border-line bg-slate-100 shadow-xs">
                                    @if ($pinjam->buku && $pinjam->buku->sampul_url)
                                        <img src="{{ $pinjam->buku->sampul_url }}" alt="{{ $pinjam->buku->judul_buku }}" class="size-full object-cover">
                                    @else
                                        <div class="flex size-full items-center justify-center bg-primary/10 text-primary">
                                            <i class="fa-solid fa-book text-base" aria-hidden="true"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h3 class="font-bold text-xs text-ink line-clamp-2 leading-snug">
                                        {{ $pinjam->buku?->judul_buku ?? 'Judul Buku Tidak Ditemukan' }}
                                    </h3>
                                    <p class="mt-0.5 font-mono text-[11px] text-muted">ID: {{ $pinjam->id_peminjaman }}</p>
                                </div>
                            </div>
                            <div class="shrink-0">
                                @php
                                    $isKembali = in_array($pinjam->status, ['Kembali', 'Dikembalikan']) || ! empty($pinjam->tanggal_kembali);
                                @endphp
                                @if ($isKembali)
                                    <span class="inline-flex rounded-full bg-emerald-50 px-2 py-0.5 text-[10px] font-bold text-emerald-700 border border-emerald-200">
                                        Dikembalikan
                                    </span>
                                @elseif ($pinjam->status === 'Dipinjam')
                                    @if ($hariTerlambat > 0)
                                        <span class="inline-flex rounded-full bg-rose-50 px-2 py-0.5 text-[10px] font-bold text-rose-700 border border-rose-200">
                                            Terlambat
                                        </span>
                                    @else
                                        <span class="inline-flex rounded-full bg-blue-50 px-2 py-0.5 text-[10px] font-bold text-primary border border-blue-200">
                                            Dipinjam
                                        </span>
                                    @endif
                                @elseif (in_array($pinjam->status, ['Menunggu Verifikasi', 'Menunggu Persetujuan']))
                                    <span class="inline-flex rounded-full bg-indigo-50 px-2 py-0.5 text-[10px] font-bold text-indigo-700 border border-indigo-200">
                                        Verifikasi
                                    </span>
                                @elseif ($pinjam->status === 'Terlambat')
                                    <span class="inline-flex rounded-full bg-rose-50 px-2 py-0.5 text-[10px] font-bold text-rose-700 border border-rose-200">
                                        Terlambat
                                    </span>
                                @else
                                    <span class="inline-flex rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-bold text-slate-700">
                                        {{ $pinjam->status }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Metadata Grid Tanggal Peminjaman & Pengembalian --}}
                        <div class="grid grid-cols-2 gap-2 rounded-xl bg-slate-50 p-2.5 text-[11px] border border-line/60">
                            <div>
                                <span class="block text-muted text-[10px]">Tanggal Peminjaman</span>
                                <span class="font-bold text-ink">
                                    {{ $pinjam->tanggal_pinjam ? $pinjam->tanggal_pinjam->format('d M Y') : ($pinjam->created_at ? $pinjam->created_at->format('d M Y') : '-') }}
                                </span>
                            </div>
                            <div>
                                <span class="block text-muted text-[10px]">Tanggal Pengembalian</span>
                                <span class="font-bold {{ $pinjam->tanggal_kembali ? 'text-emerald-700' : ($hariTerlambat > 0 ? 'text-rose-600' : 'text-ink') }}">
                                    @if ($pinjam->tanggal_kembali)
                                        {{ $pinjam->tanggal_kembali->format('d M Y') }}
                                    @elseif ($pinjam->tanggal_jatuh_tempo)
                                        {{ $pinjam->tanggal_jatuh_tempo->format('d M Y') }}
                                    @else
                                        -
                                    @endif
                                </span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between pt-1">
                            @if ($pinjam->eksemplar?->nomor_barcode)
                                <span class="font-mono text-[10px] text-muted">Barcode: {{ $pinjam->eksemplar->nomor_barcode }}</span>
                            @else
                                <span></span>
                            @endif
                            <a
                                href="{{ route('opac', ['cari' => $pinjam->buku?->judul_buku ?? '']) }}"
                                class="inline-flex min-h-[44px] items-center gap-1 text-xs font-semibold text-primary hover:underline"
                            >
                                <span>Lihat di OPAC</span>
                                <i class="fa-solid fa-angle-right text-[10px]" aria-hidden="true"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center">
                        <i class="fa-solid fa-book-open text-2xl text-slate-300 mb-2 block"></i>
                        <p class="text-xs font-semibold text-ink">Belum Ada Riwayat Peminjaman</p>
                        <p class="mt-1 text-[11px] text-muted">Buku yang Anda pinjam akan tercatat otomatis di sini.</p>
                        <a href="{{ route('opac') }}" class="btn-press mt-3 inline-flex min-h-[44px] items-center gap-1.5 rounded-xl bg-primary px-4 py-2 text-xs font-semibold text-white shadow-xs">
                            <i class="fa-solid fa-magnifying-glass text-xs"></i>
                            <span>Buka Katalog OPAC</span>
                        </a>
                    </div>
                @endforelse
            </div>

            {{-- Paginasi (print:hidden) --}}
            @if ($riwayatPeminjaman->hasPages())
                <div class="border-t border-line bg-slate-50/70 p-4 print:hidden">
                    {{ $riwayatPeminjaman->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- MODAL PRATINJAU LEMBAR BEBAS SIRKULASI & CETAK PDF --}}
    <div id="pdf-preview-modal" class="fixed inset-0 z-50 hidden overflow-y-auto print:hidden" aria-labelledby="modal-preview-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" onclick="closePdfPreviewModal()"></div>

        <div class="flex min-h-screen items-center justify-center p-3 sm:p-6">
            <div class="relative w-full max-w-4xl rounded-2xl bg-slate-100 shadow-2xl border border-line flex flex-col max-h-[90vh] overflow-hidden animate-scale-in">
                {{-- Modal Header Bar --}}
                <div class="flex items-center justify-between border-b border-line bg-white px-5 py-3.5">
                    <div class="flex items-center gap-2.5">
                        <span class="flex size-8 items-center justify-center rounded-lg bg-primary/10 text-primary">
                            <i class="fa-solid fa-file-pdf text-sm"></i>
                        </span>
                        <div>
                            <h3 id="modal-preview-title" class="text-sm font-bold text-ink">
                                Pratinjau Dokumen Cetak Lembar Bebas Sirkulasi
                            </h3>
                            <p class="text-[11px] text-muted">Tampilan dokumen resmi A4 cetak PDF Perpustakaan MNC University</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <button
                            type="button"
                            onclick="window.print()"
                            class="btn-press inline-flex min-h-[36px] items-center gap-1.5 rounded-lg bg-primary px-3.5 text-xs font-semibold text-white shadow-xs hover:bg-primary-dark"
                        >
                            <i class="fa-solid fa-print text-xs"></i>
                            <span>Cetak Sekarang</span>
                        </button>
                        <button
                            type="button"
                            onclick="closePdfPreviewModal()"
                            class="inline-flex size-9 items-center justify-center rounded-lg border border-line bg-white text-muted hover:bg-slate-50 hover:text-ink transition-colors"
                            aria-label="Tutup"
                        >
                            <i class="fa-solid fa-xmark text-sm"></i>
                        </button>
                    </div>
                </div>

                {{-- Modal Body: Sheet Container --}}
                <div class="flex-1 overflow-y-auto p-4 sm:p-8 bg-slate-200/60 flex justify-center">
                    <div class="w-full max-w-3xl bg-white p-6 sm:p-10 shadow-lg rounded-sm border border-slate-300 font-sans text-slate-900">
                        {{-- Header Surat --}}
                        <div class="text-center">
                            <h2 class="text-xs sm:text-sm font-bold uppercase tracking-wider text-[#0F2A6B]">PERPUSTAKAAN MNC UNIVERSITY</h2>
                            <h1 class="mt-0.5 text-lg sm:text-xl font-extrabold uppercase tracking-wide text-[#0F2A6B]">
                                LEMBAR RIWAYAT &amp; BEBAS SIRKULASI PEMINJAMAN BUKU
                            </h1>
                            <p class="mt-1 text-[11px] sm:text-xs italic text-slate-600">
                                Gedung MNC University, Jl. Arteri Kedoya, Jakarta Barat | Tanggal Cetak: {{ now()->translatedFormat('d F Y') }}
                            </p>
                        </div>

                        <div class="mt-3 mb-4 border-b-2 border-[#0F2A6B]"></div>

                        {{-- 1. DATA PEMUSTAKA --}}
                        <div class="overflow-hidden rounded-t border border-[#0F2A6B]">
                            <div class="bg-[#0F2A6B] py-1 text-center text-xs font-bold uppercase tracking-wider text-white">
                                DATA PEMUSTAKA
                            </div>
                            <div class="grid grid-cols-2 divide-x divide-slate-300 bg-white">
                                <div class="p-3 space-y-2">
                                    <div>
                                        <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-500">NAMA LENGKAP PEMUSTAKA</span>
                                        <span class="block text-sm sm:text-base font-extrabold text-[#0F2A6B] leading-snug">{{ $user->nama_lengkap }}</span>
                                    </div>
                                    <div>
                                        <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-500">NOMOR IDENTITAS (NIM/NIDN)</span>
                                        <span class="block text-xs sm:text-sm font-bold text-slate-900">{{ $user->nomor_identitas ?? $user->nim ?? $user->nidn ?? $user->id_pengguna }}</span>
                                    </div>
                                </div>
                                <div class="p-3 space-y-2">
                                    <div>
                                        <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-500">STATUS KEANGGOTAAN</span>
                                        <div class="mt-0.5">
                                            <span class="inline-block rounded bg-emerald-50 px-2 py-0.5 text-[11px] font-bold text-emerald-600 border border-emerald-200">
                                                Member Resmi Aktif ({{ ucfirst($role) }})
                                            </span>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-500">TOTAL TRANSAKSI TERDAFTAR</span>
                                        <span class="block text-xs sm:text-sm font-bold text-slate-900">{{ $ringkasan['total'] ?? $riwayatPeminjaman->total() }} Transaksi</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- 2. RIWAYAT TRANSAKSI PEMINJAMAN BUKU --}}
                        <div class="mt-4 overflow-hidden border border-[#0F2A6B]">
                            <div class="bg-[#0F2A6B] py-1 text-center text-xs font-bold uppercase tracking-wider text-white">
                                RIWAYAT TRANSAKSI PEMINJAMAN BUKU
                            </div>
                            <table class="w-full text-[11px] sm:text-xs border-collapse">
                                <thead>
                                    <tr class="bg-[#0F2A6B] text-white font-bold uppercase text-[10px] sm:text-[11px]">
                                        <th class="border border-white/30 px-2 py-2 w-10 text-center">NO</th>
                                        <th class="border border-white/30 px-2.5 py-2 text-left">NAMA BUKU</th>
                                        <th class="border border-white/30 px-2.5 py-2 w-36 sm:w-44 text-center">TANGGAL PEMINJAMAN</th>
                                        <th class="border border-white/30 px-2.5 py-2 w-48 sm:w-60 text-center">TANGGAL PENGEMBALIAN</th>
                                        <th class="border border-white/30 px-2.5 py-2 w-28 sm:w-36 text-center">STATUS SIRKULASI</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-300">
                                    @forelse ($riwayatPeminjaman as $index => $pinjam)
                                        @php
                                            $hariTerlambat = method_exists($pinjam, 'hitungHariTerlambat') ? $pinjam->hitungHariTerlambat() : 0;
                                        @endphp
                                        <tr class="text-slate-800">
                                            <td class="border border-slate-300 px-2 py-2 text-center font-medium">{{ $loop->iteration }}</td>
                                            <td class="border border-slate-300 px-2.5 py-2 text-left font-medium">
                                                {{ $pinjam->buku?->judul_buku ?? 'Judul Buku Tidak Ditemukan' }} <span class="text-slate-500 font-mono text-[10px]">(ID: {{ $pinjam->id_peminjaman }})</span>
                                            </td>
                                            <td class="border border-slate-300 px-2.5 py-2 text-center whitespace-nowrap">
                                                {{ $pinjam->tanggal_pinjam ? $pinjam->tanggal_pinjam->format('d M Y') : ($pinjam->created_at ? $pinjam->created_at->format('d M Y') : '-') }}
                                            </td>
                                            <td class="border border-slate-300 px-2.5 py-2 text-center whitespace-nowrap">
                                                @php
                                                    $isKembali = in_array($pinjam->status, ['Kembali', 'Dikembalikan']) || ! empty($pinjam->tanggal_kembali);
                                                @endphp
                                                @if ($pinjam->tanggal_kembali)
                                                    {{ $pinjam->tanggal_kembali->format('d M Y') }} <span class="text-emerald-700 font-semibold">(Selesai Dikembalikan)</span>
                                                @elseif ($isKembali)
                                                    {{ $pinjam->tanggal_jatuh_tempo ? $pinjam->tanggal_jatuh_tempo->format('d M Y') : '-' }} <span class="text-emerald-700 font-semibold">(Selesai Dikembalikan)</span>
                                                @elseif ($pinjam->tanggal_jatuh_tempo)
                                                    {{ $pinjam->tanggal_jatuh_tempo->format('d M Y') }} <span class="{{ $hariTerlambat > 0 ? 'text-rose-600 font-semibold' : 'text-slate-600' }}">(Jatuh Tempo)</span>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="border border-slate-300 px-2.5 py-2 text-center whitespace-nowrap">
                                                @if ($isKembali)
                                                    <span class="font-bold text-emerald-600">Kembali</span>
                                                @elseif ($pinjam->status === 'Dipinjam')
                                                    <span class="font-bold text-blue-600">Dipinjam</span>
                                                @elseif ($pinjam->status === 'Terlambat' || $hariTerlambat > 0)
                                                    <span class="font-bold text-rose-600">Terlambat</span>
                                                @elseif (in_array($pinjam->status, ['Menunggu Verifikasi', 'Menunggu Persetujuan']))
                                                    <span class="font-bold text-indigo-600">Verifikasi</span>
                                                @else
                                                    <span class="font-bold text-slate-700">{{ $pinjam->status }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="border border-slate-300 px-3 py-4 text-center text-slate-500 italic">
                                                Tidak ada transaksi peminjaman buku yang tercatat.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="4" class="border border-slate-300 bg-slate-50 px-2.5 py-2 text-right font-bold italic text-slate-700 text-[11px] sm:text-xs">
                                            @if (($ringkasan['aktif'] ?? 0) === 0)
                                                Total Transaksi: {{ $riwayatPeminjaman->total() }} Buku | Seluruh Koleksi Telah Selesai Dikembalikan
                                            @else
                                                Total Transaksi: {{ $riwayatPeminjaman->total() }} Buku | {{ $ringkasan['aktif'] }} Buku Sedang Dipinjam
                                            @endif
                                        </td>
                                        <td class="border border-slate-300 px-2 py-2 text-center font-bold text-[11px] sm:text-xs {{ ($ringkasan['aktif'] ?? 0) === 0 ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-800' }}">
                                            @if (($ringkasan['aktif'] ?? 0) === 0)
                                                BEBAS SIRKULASI
                                            @else
                                                TANGGUNGAN AKTIF
                                            @endif
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        {{-- 3. PERNYATAAN BEBAS SIRKULASI PERPUSTAKAAN --}}
                        <div class="mt-4 rounded border border-emerald-300 bg-emerald-50/70 p-3 text-center">
                            <h4 class="text-[11px] sm:text-xs font-bold uppercase tracking-wider text-emerald-800">PERNYATAAN BEBAS SIRKULASI PERPUSTAKAAN</h4>
                            <p class="mt-1 text-[11px] sm:text-xs leading-relaxed text-emerald-950">
                                @if (($ringkasan['aktif'] ?? 0) === 0)
                                    Berdasarkan data sirkulasi per tanggal {{ now()->translatedFormat('d F Y') }}, pemustaka atas nama <strong class="font-bold text-slate-900">{{ $user->nama_lengkap }}</strong> ({{ $role === 'dosen' ? 'NIDN' : 'NIM' }}: {{ $user->nomor_identitas ?? $user->nim ?? $user->nidn ?? $user->id_pengguna }}) telah mengembalikan seluruh buku pinjaman dalam kondisi baik dan <strong class="font-bold">TIDAK MEMILIKI TANGGUNGAN</strong> sirkulasi maupun denda di Perpustakaan MNC University. Lembar ini sah digunakan sebagai bukti bebas pinjaman perpustakaan.
                                @else
                                    Berdasarkan data sirkulasi per tanggal {{ now()->translatedFormat('d F Y') }}, pemustaka atas nama <strong class="font-bold text-slate-900">{{ $user->nama_lengkap }}</strong> ({{ $role === 'dosen' ? 'NIDN' : 'NIM' }}: {{ $user->nomor_identitas ?? $user->nim ?? $user->nidn ?? $user->id_pengguna }}) saat ini tercatat memiliki <strong class="font-bold">{{ $ringkasan['aktif'] }} buku pinjaman</strong> yang sedang berjalan di Perpustakaan MNC University. Harap menyelesaikan pengembalian seluruh koleksi sebelum batas waktu jatuh tempo.
                                @endif
                            </p>
                        </div>

                        {{-- 4. TANDA TANGAN RESMI --}}
                        <div class="mt-6 pt-2 flex justify-between items-start text-[11px] sm:text-xs text-slate-800 px-4 sm:px-8">
                            <div class="text-center w-56">
                                <p>Mengetahui,</p>
                                <p class="font-bold text-[#0F2A6B] mt-0.5">Kepala Perpustakaan MNC University</p>
                                <div class="h-16"></div>
                                <p class="font-bold underline text-slate-900">Dr. H. Muhammad Arifin, M.Hum.</p>
                                <p class="text-[10px] text-slate-500 mt-0.5">NIP. 19780812 200501 1 002</p>
                            </div>
                            <div class="text-center w-56">
                                <p>Jakarta Barat, {{ now()->translatedFormat('d F Y') }}</p>
                                <p class="font-bold text-[#0F2A6B] mt-0.5">Pemustaka Yang Bersangkutan</p>
                                <div class="h-16"></div>
                                <p class="font-bold underline text-slate-900">{{ $user->nama_lengkap }}</p>
                                <p class="text-[10px] text-slate-500 mt-0.5">ID: {{ $user->nomor_identitas ?? $user->nim ?? $user->nidn ?? $user->id_pengguna }}</p>
                            </div>
                        </div>

                        {{-- 5. CATATAN DOKUMEN --}}
                        <div class="mt-6 text-center text-[10px] italic text-slate-500">
                            Dokumen ini diterbitkan secara resmi oleh Perpustakaan MNC University dan sah tanpa cap basah apabila telah ditandatangani.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function openPdfPreviewModal() {
        const modal = document.getElementById('pdf-preview-modal');
        if (modal) {
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }
    }

    function closePdfPreviewModal() {
        const modal = document.getElementById('pdf-preview-modal');
        if (modal) {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    }

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closePdfPreviewModal();
        }
    });
</script>
@endsection
