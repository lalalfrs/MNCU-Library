@extends('layouts.app')

@section('title', 'Laporan & Rekapitulasi Perpustakaan | MNC University')
@section('description', 'Laporan resmi rekapitulasi data perpustakaan MNC University meliputi total koleksi buku, total anggota member, peminjam aktif, dan buku belum kembali.')

@section('content')
    <div class="mx-auto flex w-full max-w-[1570px] flex-1 flex-col gap-6 px-3 sm:px-4 lg:px-6 xl:px-8 py-4 sm:py-6 lg:py-8 responsive-desktop-container print:max-w-none print:px-0 print:py-0 print:gap-0 print:m-0">

        {{-- ========================================================================= --}}
        {{-- DOKUMEN CETAK PDF RESMI 1 HALAMAN A4 (KHUSUS SAAT PRINT / SIMPAN PDF)     --}}
        {{-- ========================================================================= --}}
        <div class="hidden print:block w-full bg-white text-slate-900 font-sans print:p-0">
            {{-- Kop Surat Resmi Perpustakaan MNC University --}}
            <div class="flex items-center justify-between gap-4 pb-2.5">
                <div class="flex items-center gap-3.5">
                    <img
                        src="{{ asset('images/Logo.png') }}"
                        alt="Logo MNC University"
                        width="48"
                        height="48"
                        style="width: 48px; height: 48px; max-width: 48px; max-height: 48px; object-fit: contain;"
                        class="print-kop-logo shrink-0"
                    >
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-600">
                            UNIVERSITAS MEDIA NUSANTARA CITRA (MNC UNIVERSITY)
                        </p>
                        <h2 class="text-base font-extrabold uppercase tracking-wide text-[#0F2A6B] leading-tight">
                            PERPUSTAKAAN MNC UNIVERSITY
                        </h2>
                        <p class="text-[10px] text-slate-600 leading-snug">
                            Gedung MNC University, Jl. Arteri Kedoya No. 2, Kebon Jeruk, Jakarta Barat 11520
                        </p>
                        <p class="text-[10px] text-slate-500 leading-snug">
                            Laman: perpustakaan.mncu.ac.id &bull; Surel: library@mncu.ac.id &bull; Telp: (021) 5835-8696
                        </p>
                    </div>
                </div>
                <div class="text-right shrink-0">
                    <div class="inline-block rounded border border-[#0F2A6B]/30 bg-slate-50 px-2.5 py-1 text-right">
                        <span class="block text-[8px] font-bold uppercase tracking-wider text-slate-500">KODE DOKUMEN LAPORAN</span>
                        <span class="font-mono text-[10px] font-bold text-[#0F2A6B]">LAP-SIR/{{ now()->format('Y/m') }}/MNCU</span>
                    </div>
                </div>
            </div>

            {{-- Garis Ganda Kop Surat --}}
            <div class="border-b-2 border-[#0F2A6B]"></div>
            <div class="mt-0.5 mb-3 border-b border-slate-400"></div>

            {{-- Judul Dokumen & Metadata Periode --}}
            <div class="mb-3 text-center">
                <h1 class="text-sm font-extrabold uppercase tracking-wider text-[#0F2A6B] underline decoration-[#0F2A6B] underline-offset-4">
                    LAPORAN REKAPITULASI SIRKULASI &amp; KOLEKSI PERPUSTAKAAN
                </h1>
                <p class="mt-1 text-[10px] text-slate-600">
                    Cakupan Periode: <strong class="font-bold text-slate-900">{{ $periodeLabel }}</strong>
                    &nbsp;|&nbsp; Tanggal Cetak: <strong class="font-bold text-slate-900">{{ now()->translatedFormat('d F Y') }}</strong>
                    &nbsp;|&nbsp; Dicetak Oleh: <strong class="font-bold text-slate-900">{{ Auth::user()->nama_lengkap }}</strong>
                </p>
            </div>

            {{-- I. RINGKASAN EKSEKUTIF 4 PILAR UTAMA --}}
            <div class="mb-3 overflow-hidden border border-[#0F2A6B] print:break-inside-avoid">
                <div class="bg-[#0F2A6B] px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-white">
                    I. RINGKASAN EKSEKUTIF KOLEKSI &amp; SIRKULASI
                </div>
                <div class="grid grid-cols-4 divide-x divide-slate-300 bg-white text-left">
                    {{-- Pilar 1 --}}
                    <div class="p-2">
                        <span class="block text-[9px] font-bold uppercase tracking-wider text-slate-500">1. Total Koleksi Buku</span>
                        <p class="mt-0.5 text-sm font-extrabold text-[#0F2A6B]">
                            {{ number_format($totalJudulBuku) }} <span class="text-[10px] font-semibold text-slate-600">Judul</span>
                        </p>
                        <p class="mt-0.5 text-[9px] text-slate-600">
                            Fisik: <strong class="text-slate-900">{{ number_format($totalEksemplarFisik) }} eks</strong>
                            &bull; Tersedia: <strong class="text-emerald-700">{{ number_format($totalEksemplarTersedia) }}</strong>
                        </p>
                    </div>
                    {{-- Pilar 2 --}}
                    <div class="p-2">
                        <span class="block text-[9px] font-bold uppercase tracking-wider text-slate-500">2. Total Anggota Member</span>
                        <p class="mt-0.5 text-sm font-extrabold text-emerald-700">
                            {{ number_format($totalAnggotaMember) }} <span class="text-[10px] font-semibold text-slate-600">Member</span>
                        </p>
                        <p class="mt-0.5 text-[9px] text-slate-600">
                            Mhs: <strong class="text-slate-900">{{ $totalMemberMahasiswa }}</strong>
                            &bull; Dosen: <strong class="text-slate-900">{{ $totalMemberDosen }}</strong>
                            &bull; Baru: <strong class="text-[#0F2A6B]">+{{ $memberBaruPeriode }}</strong>
                        </p>
                    </div>
                    {{-- Pilar 3 --}}
                    <div class="p-2">
                        <span class="block text-[9px] font-bold uppercase tracking-wider text-slate-500">3. Total User Meminjam Buku</span>
                        <p class="mt-0.5 text-sm font-extrabold text-indigo-800">
                            {{ number_format($totalUserMeminjam) }} <span class="text-[10px] font-semibold text-slate-600">Orang</span>
                        </p>
                        <p class="mt-0.5 text-[9px] text-slate-600">
                            Frekuensi Pinjam: <strong class="text-slate-900">{{ number_format($totalTransaksiPeminjaman) }} Transaksi</strong>
                        </p>
                    </div>
                    {{-- Pilar 4 --}}
                    <div class="p-2">
                        <span class="block text-[9px] font-bold uppercase tracking-wider text-slate-500">4. Buku Belum Dikembalikan</span>
                        <p class="mt-0.5 text-sm font-extrabold {{ $totalBukuBelumKembali > 0 ? 'text-amber-800' : 'text-emerald-700' }}">
                            {{ number_format($totalBukuBelumKembali) }} <span class="text-[10px] font-semibold text-slate-600">Eksemplar</span>
                        </p>
                        <p class="mt-0.5 text-[9px] text-slate-600">
                            Status Sirkulasi: <strong class="text-slate-900">{{ $totalBukuBelumKembali > 0 ? 'Pinjaman Berjalan' : 'Nihil / Tertib' }}</strong>
                        </p>
                    </div>
                </div>
            </div>

            {{-- II. TABEL DAFTAR BUKU YANG BELUM DIKEMBALIKAN --}}
            <div class="mb-3 overflow-hidden border border-[#0F2A6B] print:break-inside-avoid">
                <div class="flex items-center justify-between bg-[#0F2A6B] px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-white">
                    <span>II. DAFTAR BUKU YANG BELUM DIKEMBALIKAN (TANGGUNGAN AKTIF)</span>
                    <span>TOTAL: {{ $totalBukuBelumKembali }} EKSEMPLAR</span>
                </div>
                <table class="w-full border-collapse text-[10px]">
                    <thead>
                        <tr class="bg-slate-100 text-slate-800 font-bold uppercase text-[9px]">
                            <th class="border border-slate-300 px-1.5 py-1.5 w-8 text-center">NO</th>
                            <th class="border border-slate-300 px-2 py-1.5 text-left">NAMA PEMINJAM &amp; IDENTITAS</th>
                            <th class="border border-slate-300 px-2 py-1.5 text-left">JUDUL BUKU &amp; KODE BARCODE</th>
                            <th class="border border-slate-300 px-2 py-1.5 w-24 text-center">TGL PINJAM</th>
                            <th class="border border-slate-300 px-2 py-1.5 w-24 text-center">JATUH TEMPO</th>
                            <th class="border border-slate-300 px-2 py-1.5 w-32 text-center">STATUS PINJAMAN</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($daftarBukuBelumKembali as $idx => $item)
                            @php
                                $hariTelat = $item->hitungHariTerlambat();
                            @endphp
                            <tr class="text-slate-800">
                                <td class="border border-slate-300 px-1.5 py-1 text-center font-medium">{{ $idx + 1 }}</td>
                                <td class="border border-slate-300 px-2 py-1">
                                    <span class="font-bold text-slate-900">{{ $item->pengguna?->nama_lengkap ?? 'Member' }}</span>
                                    <span class="block text-[9px] text-slate-500">{{ $item->pengguna?->nomor_identitas ?? '-' }} ({{ $item->pengguna?->peran?->nama_peran ?? 'Pemustaka' }})</span>
                                </td>
                                <td class="border border-slate-300 px-2 py-1">
                                    <span class="font-semibold text-slate-900">{{ $item->buku?->judul_buku ?? 'Buku' }}</span>
                                    <span class="block font-mono text-[9px] text-slate-500">Barcode: {{ $item->eksemplar?->nomor_barcode ?? '-' }}</span>
                                </td>
                                <td class="border border-slate-300 px-2 py-1 text-center whitespace-nowrap">
                                    {{ $item->tanggal_pinjam ? $item->tanggal_pinjam->format('d M Y') : '-' }}
                                </td>
                                <td class="border border-slate-300 px-2 py-1 text-center font-semibold {{ $hariTelat > 0 ? 'text-rose-700' : 'text-slate-800' }} whitespace-nowrap">
                                    {{ $item->tanggal_jatuh_tempo ? $item->tanggal_jatuh_tempo->format('d M Y') : '-' }}
                                </td>
                                <td class="border border-slate-300 px-2 py-1 text-center whitespace-nowrap">
                                    @if ($item->status === 'Menunggu Verifikasi')
                                        <span class="font-bold text-blue-700">Verifikasi Admin</span>
                                    @elseif ($hariTelat > 0 || $item->status === 'Terlambat')
                                        <span class="font-bold text-rose-700">Terlambat {{ $hariTelat }} Hari</span>
                                    @else
                                        <span class="font-bold text-emerald-700">Masa Pinjam Aktif</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="border border-slate-300 px-2 py-2.5 text-center italic text-slate-500">
                                    Seluruh koleksi buku telah dikembalikan dengan tertib. Tidak ada pinjaman buku yang tertunggak.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- III. TABEL REKAPITULASI PEMINJAMAN PERIODE TERPILIH --}}
            <div class="mb-3 overflow-hidden border border-[#0F2A6B] print:break-inside-avoid">
                <div class="flex items-center justify-between bg-[#0F2A6B] px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-white">
                    <span>III. REKAPITULASI TRANSAKSI PEMINJAMAN PERIODE ({{ strtoupper($periodeLabel) }})</span>
                    <span>TOTAL: {{ $totalTransaksiPeminjaman }} TRANSAKSI</span>
                </div>
                <table class="w-full border-collapse text-[10px]">
                    <thead>
                        <tr class="bg-slate-100 text-slate-800 font-bold uppercase text-[9px]">
                            <th class="border border-slate-300 px-1.5 py-1.5 w-8 text-center">NO</th>
                            <th class="border border-slate-300 px-2 py-1.5 text-left">PEMINJAM &amp; IDENTITAS</th>
                            <th class="border border-slate-300 px-2 py-1.5 text-left">JUDUL BUKU &amp; KODE BARCODE</th>
                            <th class="border border-slate-300 px-2 py-1.5 w-20 text-center">TGL PINJAM</th>
                            <th class="border border-slate-300 px-2 py-1.5 w-20 text-center">JATUH TEMPO</th>
                            <th class="border border-slate-300 px-2 py-1.5 w-20 text-center">TGL KEMBALI</th>
                            <th class="border border-slate-300 px-2 py-1.5 w-24 text-center">STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($daftarPeminjamanPeriode as $idx2 => $trx)
                            <tr class="text-slate-800">
                                <td class="border border-slate-300 px-1.5 py-1 text-center font-medium">{{ $idx2 + 1 }}</td>
                                <td class="border border-slate-300 px-2 py-1">
                                    <span class="font-bold text-slate-900">{{ $trx->pengguna?->nama_lengkap ?? 'Member' }}</span>
                                    <span class="block text-[9px] text-slate-500">{{ $trx->pengguna?->nomor_identitas ?? '-' }}</span>
                                </td>
                                <td class="border border-slate-300 px-2 py-1">
                                    <span class="font-semibold text-slate-900">{{ $trx->buku?->judul_buku ?? 'Buku' }}</span>
                                    <span class="block font-mono text-[9px] text-slate-500">BC: {{ $trx->eksemplar?->nomor_barcode ?? '-' }}</span>
                                </td>
                                <td class="border border-slate-300 px-2 py-1 text-center whitespace-nowrap">
                                    {{ $trx->tanggal_pinjam ? $trx->tanggal_pinjam->format('d M Y') : '-' }}
                                </td>
                                <td class="border border-slate-300 px-2 py-1 text-center whitespace-nowrap">
                                    {{ $trx->tanggal_jatuh_tempo ? $trx->tanggal_jatuh_tempo->format('d M Y') : '-' }}
                                </td>
                                <td class="border border-slate-300 px-2 py-1 text-center whitespace-nowrap">
                                    @if ($trx->tanggal_kembali)
                                        <span class="font-semibold text-emerald-700">{{ $trx->tanggal_kembali->format('d M Y') }}</span>
                                    @else
                                        <span class="italic text-amber-800">Belum Kembali</span>
                                    @endif
                                </td>
                                <td class="border border-slate-300 px-2 py-1 text-center font-bold whitespace-nowrap {{ $trx->status === 'Kembali' ? 'text-emerald-700' : ($trx->status === 'Terlambat' ? 'text-rose-700' : 'text-[#0F2A6B]') }}">
                                    {{ $trx->status }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="border border-slate-300 px-2 py-2.5 text-center italic text-slate-500">
                                    Tidak ada transaksi peminjaman buku yang tercatat pada rentang periode ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="6" class="border border-slate-300 bg-slate-50 px-2.5 py-1.5 text-right font-bold italic text-slate-700 text-[9px]">
                                Akumulasi Periode ({{ $periodeLabel }}): {{ $totalTransaksiPeminjaman }} Transaksi Peminjaman | {{ $totalBukuBelumKembali }} Eksemplar Sedang Dipinjam
                            </td>
                            <td class="border border-slate-300 bg-slate-100 px-2 py-1.5 text-center font-bold text-[9px] text-[#0F2A6B]">
                                TERVERIFIKASI
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- IV. PENGESAHAN & TANDA TANGAN RESMI PUSTAKAWAN --}}
            <div class="mt-5 pt-2 flex justify-between items-start text-[10px] text-slate-800 print:break-inside-avoid px-6">
                <div class="text-center w-56">
                    <p>Mengetahui,</p>
                    <p class="font-bold text-[#0F2A6B] mt-0.5">Kepala Perpustakaan MNC University</p>
                    <div class="h-14"></div>
                    <p class="font-bold underline text-slate-900">Dr. H. Muhammad Arifin, M.Hum.</p>
                    <p class="text-[9px] text-slate-500 mt-0.5">NIP. 19780812 200501 1 002</p>
                </div>
                <div class="text-center w-56">
                    <p>Jakarta Barat, {{ now()->translatedFormat('d F Y') }}</p>
                    <p class="font-bold text-[#0F2A6B] mt-0.5">Pustakawan Pelaksana</p>
                    <div class="h-14"></div>
                    <p class="font-bold underline text-slate-900">{{ Auth::user()->nama_lengkap }}</p>
                    <p class="text-[9px] text-slate-500 mt-0.5">ID: {{ Auth::user()->nomor_identitas ?? 'Petugas Perpustakaan' }}</p>
                </div>
            </div>

            {{-- Catatan Kaki Resmi --}}
            <div class="mt-4 border-t border-slate-200 pt-1.5 text-center text-[9px] italic text-slate-500">
                Dokumen laporan ini dihasilkan secara otomatis oleh Sistem Informasi Perpustakaan MNC University (MNCU Library) dan sah sebagai laporan operasional resmi.
            </div>
        </div>

        {{-- ========================================================================= --}}
        {{-- TAMPILAN INTERAKTIF LAYAR WEB (DISEMBUNYIKAN SAAT PRINT: print:hidden)    --}}
        {{-- ========================================================================= --}}
        <div class="flex flex-col gap-6 print:hidden">
            {{-- Breadcrumb & Header --}}
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <nav aria-label="Breadcrumb" class="flex items-center gap-2 text-xs text-muted sm:text-sm">
                        <a href="{{ route('beranda') }}" class="hover:text-primary transition-colors">Beranda</a>
                        <svg class="size-3.5 text-muted/60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
                        <a href="{{ route('opac') }}" class="hover:text-primary transition-colors">Katalog OPAC</a>
                        <svg class="size-3.5 text-muted/60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
                        <span class="font-medium text-ink">Laporan (Report)</span>
                    </nav>
                    <div class="mt-1 flex flex-wrap items-center gap-2.5">
                        <h1 class="text-2xl font-bold tracking-tight text-primary sm:text-3xl">
                            Laporan Rekapitulasi Perpustakaan
                        </h1>
                        <span class="inline-flex items-center gap-1.5 rounded-full border border-primary/20 bg-primary/10 px-3 py-0.5 text-xs font-semibold text-primary shadow-xs">
                            <i class="fa-solid fa-file-lines text-[11px]"></i>
                            <span>{{ $periodeLabel }}</span>
                        </span>
                    </div>
                    <p class="mt-0.5 text-xs text-ink-secondary sm:text-sm">
                        Dokumen rekapitulasi operasional: total koleksi, keanggotaan member, aktivitas peminjam, dan buku yang belum dikembalikan.
                    </p>
                </div>

                {{-- Action Buttons Navigasi & Ekspor --}}
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
                        href="{{ route('admin.statistics.index') }}"
                        class="btn-press inline-flex min-h-[42px] items-center gap-2 rounded-xl bg-indigo-600 px-3.5 text-xs font-semibold text-white shadow-xs transition-all hover:bg-indigo-700 active:scale-95 sm:text-sm"
                        title="Buka Statistik Perpustakaan"
                    >
                        <i class="fa-solid fa-chart-simple text-xs" aria-hidden="true"></i>
                        <span>Statistik</span>
                    </a>
                    <button
                        type="button"
                        onclick="openReportPdfPreviewModal()"
                        class="btn-press inline-flex min-h-[42px] items-center gap-2 rounded-xl border border-line bg-white px-3.5 text-xs font-semibold text-ink shadow-xs transition-all hover:border-primary hover:text-primary active:scale-95 sm:text-sm"
                        title="Pratinjau Lembar Laporan PDF A4"
                    >
                        <i class="fa-solid fa-eye text-xs text-primary" aria-hidden="true"></i>
                        <span>Pratinjau PDF</span>
                    </button>
                    <button
                        type="button"
                        onclick="window.print()"
                        class="btn-press inline-flex min-h-[42px] items-center gap-2 rounded-xl bg-primary px-4 text-xs font-semibold text-white shadow-xs transition-all hover:bg-primary-dark active:scale-95 sm:text-sm"
                        title="Cetak Laporan / Simpan PDF"
                    >
                        <i class="fa-solid fa-print text-xs" aria-hidden="true"></i>
                        <span>Cetak PDF</span>
                    </button>
                    <a
                        href="{{ route('admin.reports.export-csv', request()->query()) }}"
                        class="btn-press inline-flex min-h-[42px] items-center gap-2 rounded-xl bg-emerald-600 px-4 text-xs font-semibold text-white shadow-xs transition-all hover:bg-emerald-700 active:scale-95 sm:text-sm"
                        title="Unduh Data Format CSV"
                    >
                        <i class="fa-solid fa-file-csv text-xs" aria-hidden="true"></i>
                        <span>Export CSV</span>
                    </a>
                </div>
            </div>

            {{-- FILTER PERIODE SESUAI FLOWCHART: "PILIH TAHUN, BULAN, ATAU MINGGU" --}}
            <section aria-labelledby="filter-report-heading" class="rounded-2xl border border-line bg-white p-4 sm:p-5 shadow-xs">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <h2 id="filter-report-heading" class="text-sm font-bold text-ink sm:text-base">
                            <i class="fa-solid fa-filter text-primary mr-2"></i>Pilih Periode Laporan
                        </h2>
                        <p class="text-xs text-muted">
                            Pilih tahun, bulan, atau minggu untuk menyaring cakupan rekapitulasi peminjaman dan sirkulasi.
                        </p>
                    </div>

                    <form method="GET" action="{{ route('admin.reports.index') }}" class="flex flex-wrap items-center gap-2.5">
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
                            class="btn-press inline-flex h-9 items-center gap-1.5 rounded-xl bg-primary px-3.5 text-xs font-semibold text-white shadow-xs hover:bg-primary-dark active:scale-95"
                        >
                            <i class="fa-solid fa-rotate text-xs"></i>
                            <span>Terapkan</span>
                        </button>
                    </form>
                </div>
            </section>

            {{-- 4 KARTU METRIK UTAMA SESUAI FLOWCHART REPORT --}}
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                {{-- 1. MENAMPILKAN TOTAL KOLEKSI BUKU --}}
                <div class="rounded-2xl border border-line bg-white p-5 shadow-xs">
                    <div class="flex items-center justify-between">
                        <span class="flex size-11 items-center justify-center rounded-xl bg-blue-100 text-primary">
                            <i class="fa-solid fa-book-bookmark text-lg" aria-hidden="true"></i>
                        </span>
                        <span class="rounded-full bg-blue-50 border border-blue-200 px-2 py-0.5 text-[10px] font-bold text-primary">
                            Koleksi Fisik
                        </span>
                    </div>
                    <h3 class="mt-3 text-xs font-semibold uppercase tracking-wider text-muted">1. Total Koleksi Buku</h3>
                    <p class="mt-1 text-2xl sm:text-3xl font-extrabold text-ink">{{ number_format($totalJudulBuku) }} <span class="text-xs font-semibold text-muted">Judul</span></p>
                    <div class="mt-2.5 flex items-center justify-between border-t border-line/60 pt-2 text-xs text-muted">
                        <span>Total Fisik: <strong class="text-ink">{{ number_format($totalEksemplarFisik) }} eks</strong></span>
                        <span>Tersedia: <strong class="text-emerald-600">{{ number_format($totalEksemplarTersedia) }}</strong></span>
                    </div>
                </div>

                {{-- 2. MENAMPILKAN TOTAL ANGGOTA MEMBER --}}
                <div class="rounded-2xl border border-line bg-white p-5 shadow-xs">
                    <div class="flex items-center justify-between">
                        <span class="flex size-11 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">
                            <i class="fa-solid fa-id-card text-lg" aria-hidden="true"></i>
                        </span>
                        <span class="rounded-full bg-emerald-50 border border-emerald-200 px-2 py-0.5 text-[10px] font-bold text-emerald-700">
                            Terverifikasi
                        </span>
                    </div>
                    <h3 class="mt-3 text-xs font-semibold uppercase tracking-wider text-muted">2. Total Anggota Member</h3>
                    <p class="mt-1 text-2xl sm:text-3xl font-extrabold text-emerald-700">{{ number_format($totalAnggotaMember) }} <span class="text-xs font-semibold text-muted">Member</span></p>
                    <div class="mt-2.5 flex items-center justify-between border-t border-line/60 pt-2 text-xs text-muted">
                        <span>Mhs: <strong class="text-ink">{{ $totalMemberMahasiswa }}</strong></span>
                        <span>Dosen: <strong class="text-ink">{{ $totalMemberDosen }}</strong></span>
                        <span>+Baru: <strong class="text-primary">{{ $memberBaruPeriode }}</strong></span>
                    </div>
                </div>

                {{-- 3. MENAMPILKAN TOTAL USER YANG MEMINJAM BUKU --}}
                <div class="rounded-2xl border border-line bg-white p-5 shadow-xs">
                    <div class="flex items-center justify-between">
                        <span class="flex size-11 items-center justify-center rounded-xl bg-indigo-100 text-indigo-700">
                            <i class="fa-solid fa-users text-lg" aria-hidden="true"></i>
                        </span>
                        <span class="rounded-full bg-indigo-50 border border-indigo-200 px-2 py-0.5 text-[10px] font-bold text-indigo-700">
                            Periode Ini
                        </span>
                    </div>
                    <h3 class="mt-3 text-xs font-semibold uppercase tracking-wider text-muted">3. Total User Meminjam Buku</h3>
                    <p class="mt-1 text-2xl sm:text-3xl font-extrabold text-indigo-700">{{ number_format($totalUserMeminjam) }} <span class="text-xs font-semibold text-muted">Orang</span></p>
                    <div class="mt-2.5 flex items-center justify-between border-t border-line/60 pt-2 text-xs text-muted">
                        <span>Total Transaksi Pinjam:</span>
                        <strong class="text-indigo-900 font-bold">{{ number_format($totalTransaksiPeminjaman) }} kali</strong>
                    </div>
                </div>

                {{-- 4. MENAMPILKAN TOTAL BUKU YANG BELUM DIKEMBALIKAN --}}
                <div class="rounded-2xl border border-amber-300 bg-amber-50/50 p-5 shadow-xs">
                    <div class="flex items-center justify-between">
                        <span class="flex size-11 items-center justify-center rounded-xl bg-amber-500 text-white shadow-xs">
                            <i class="fa-solid fa-clock text-lg" aria-hidden="true"></i>
                        </span>
                        <span class="rounded-full bg-amber-100 border border-amber-300 px-2 py-0.5 text-[10px] font-bold text-amber-900">
                            Perlu Perhatian
                        </span>
                    </div>
                    <h3 class="mt-3 text-xs font-semibold uppercase tracking-wider text-amber-900">4. Buku Belum Dikembalikan</h3>
                    <p class="mt-1 text-2xl sm:text-3xl font-extrabold text-amber-900">{{ number_format($totalBukuBelumKembali) }} <span class="text-xs font-semibold text-amber-700">Eksemplar</span></p>
                    <div class="mt-2.5 flex items-center justify-between border-t border-amber-200 pt-2 text-xs text-amber-900">
                        <span>Status Sirkulasi Berjalan:</span>
                        <strong class="font-bold">Aktif</strong>
                    </div>
                </div>
            </div>

            {{-- TABEL RINCIAN 1: DAFTAR BUKU YANG BELUM DIKEMBALIKAN --}}
            <section class="rounded-2xl border border-line bg-white p-5 shadow-xs">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between border-b border-line pb-4">
                    <div>
                        <h2 class="text-base font-bold text-ink">
                            <i class="fa-solid fa-triangle-exclamation text-amber-600 mr-2"></i>Daftar Buku yang Belum Dikembalikan
                        </h2>
                        <p class="text-xs text-muted">
                            Daftar seluruh buku yang masih berada di pemustaka (status Dipinjam, Terlambat, atau Menunggu Verifikasi Pengembalian).
                        </p>
                    </div>
                    <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-bold text-amber-900">
                        {{ $totalBukuBelumKembali }} Buku Tanggungan
                    </span>
                </div>

                <div class="mt-4 overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-subtle text-muted">
                            <tr>
                                <th class="px-3.5 py-2.5 font-semibold">No</th>
                                <th class="px-3.5 py-2.5 font-semibold">Nama Peminjam / NIM</th>
                                <th class="px-3.5 py-2.5 font-semibold">Judul Buku &amp; Barcode</th>
                                <th class="px-3.5 py-2.5 font-semibold">Tgl Pinjam</th>
                                <th class="px-3.5 py-2.5 font-semibold">Jatuh Tempo</th>
                                <th class="px-3.5 py-2.5 font-semibold">Status / Terlambat</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-line">
                            @forelse ($daftarBukuBelumKembali as $idx => $item)
                                @php
                                    $hariTelat = $item->hitungHariTerlambat();
                                @endphp
                                <tr class="hover:bg-slate-50">
                                    <td class="px-3.5 py-3 text-muted">{{ $idx + 1 }}</td>
                                    <td class="px-3.5 py-3">
                                        <p class="font-semibold text-ink">{{ $item->pengguna?->nama_lengkap ?? 'Member' }}</p>
                                        <p class="text-[11px] text-muted">{{ $item->pengguna?->nomor_identitas ?? '-' }} ({{ $item->pengguna?->peran?->nama_peran ?? 'Pemustaka' }})</p>
                                    </td>
                                    <td class="px-3.5 py-3">
                                        <p class="font-medium text-ink truncate max-w-[260px]" title="{{ $item->buku?->judul_buku }}">{{ $item->buku?->judul_buku ?? 'Buku' }}</p>
                                        <span class="font-mono text-[10px] text-muted">Barcode: {{ $item->eksemplar?->nomor_barcode ?? '-' }}</span>
                                    </td>
                                    <td class="px-3.5 py-3 text-muted whitespace-nowrap">
                                        {{ $item->tanggal_pinjam ? $item->tanggal_pinjam->format('d M Y') : '-' }}
                                    </td>
                                    <td class="px-3.5 py-3 font-semibold {{ $hariTelat > 0 ? 'text-red-600' : 'text-ink' }} whitespace-nowrap">
                                        {{ $item->tanggal_jatuh_tempo ? $item->tanggal_jatuh_tempo->format('d M Y') : '-' }}
                                    </td>
                                    <td class="px-3.5 py-3 whitespace-nowrap">
                                        @if ($item->status === 'Menunggu Verifikasi')
                                            <span class="inline-block rounded-full bg-blue-100 px-2 py-0.5 text-[10px] font-bold text-blue-800">
                                                Menunggu Verifikasi Admin
                                            </span>
                                        @elseif ($hariTelat > 0 || $item->status === 'Terlambat')
                                            <span class="inline-block rounded-full bg-red-100 px-2 py-0.5 text-[10px] font-bold text-red-800">
                                                Terlambat {{ $hariTelat }} Hari
                                            </span>
                                        @else
                                            <span class="inline-block rounded-full bg-emerald-100 px-2 py-0.5 text-[10px] font-bold text-emerald-800">
                                                Masa Pinjam Aktif
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-3.5 py-8 text-center text-xs text-muted">
                                        <i class="fa-solid fa-circle-check text-2xl text-emerald-500 mb-1 block"></i>
                                        Semua buku telah dikembalikan dengan tertib. Tidak ada buku yang sedang berstatus pinjaman tertunggak.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            {{-- TABEL RINCIAN 2: DAFTAR TRANSAKSI PEMINJAMAN PADA PERIODE TERPILIH --}}
            <section class="rounded-2xl border border-line bg-white p-5 shadow-xs">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between border-b border-line pb-4">
                    <div>
                        <h2 class="text-base font-bold text-ink">
                            <i class="fa-solid fa-clock-rotate-left text-primary mr-2"></i>Rekapitulasi Peminjaman Periode: {{ $periodeLabel }}
                        </h2>
                        <p class="text-xs text-muted">
                            Riwayat peminjaman buku yang dilakukan oleh member perpustakaan pada rentang waktu yang dipilih.
                        </p>
                    </div>
                    <span class="rounded-full bg-primary/10 px-3 py-1 text-xs font-bold text-primary">
                        {{ $totalTransaksiPeminjaman }} Transaksi Peminjaman
                    </span>
                </div>

                <div class="mt-4 overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-subtle text-muted">
                            <tr>
                                <th class="px-3.5 py-2.5 font-semibold">No</th>
                                <th class="px-3.5 py-2.5 font-semibold">Peminjam</th>
                                <th class="px-3.5 py-2.5 font-semibold">Buku &amp; Barcode</th>
                                <th class="px-3.5 py-2.5 font-semibold">Tgl Pinjam</th>
                                <th class="px-3.5 py-2.5 font-semibold">Jatuh Tempo</th>
                                <th class="px-3.5 py-2.5 font-semibold">Tgl Kembali</th>
                                <th class="px-3.5 py-2.5 font-semibold text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-line">
                            @forelse ($daftarPeminjamanPeriode as $idx2 => $trx)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-3.5 py-3 text-muted">{{ $idx2 + 1 }}</td>
                                    <td class="px-3.5 py-3">
                                        <p class="font-semibold text-ink">{{ $trx->pengguna?->nama_lengkap ?? 'Member' }}</p>
                                        <p class="text-[11px] text-muted">{{ $trx->pengguna?->nomor_identitas ?? '-' }}</p>
                                    </td>
                                    <td class="px-3.5 py-3">
                                        <p class="font-medium text-ink truncate max-w-[240px]" title="{{ $trx->buku?->judul_buku }}">{{ $trx->buku?->judul_buku ?? 'Buku' }}</p>
                                        <span class="font-mono text-[10px] text-muted">BC: {{ $trx->eksemplar?->nomor_barcode ?? '-' }}</span>
                                    </td>
                                    <td class="px-3.5 py-3 text-muted whitespace-nowrap">
                                        {{ $trx->tanggal_pinjam ? $trx->tanggal_pinjam->format('d M Y') : '-' }}
                                    </td>
                                    <td class="px-3.5 py-3 text-muted whitespace-nowrap">
                                        {{ $trx->tanggal_jatuh_tempo ? $trx->tanggal_jatuh_tempo->format('d M Y') : '-' }}
                                    </td>
                                    <td class="px-3.5 py-3 whitespace-nowrap">
                                        @if ($trx->tanggal_kembali)
                                            <span class="font-medium text-emerald-700">{{ $trx->tanggal_kembali->format('d M Y') }}</span>
                                        @else
                                            <span class="text-amber-700 italic">Belum Kembali</span>
                                        @endif
                                    </td>
                                    <td class="px-3.5 py-3 text-right whitespace-nowrap">
                                        <span class="inline-block rounded px-2 py-0.5 text-[10px] font-bold {{ $trx->status === 'Kembali' ? 'bg-emerald-100 text-emerald-800' : ($trx->status === 'Terlambat' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800') }}">
                                            {{ $trx->status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-3.5 py-8 text-center text-xs text-muted">
                                        Tidak ada transaksi peminjaman buku yang tercatat pada rentang periode ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </div>

        {{-- ========================================================================= --}}
        {{-- MODAL PRATINJAU LEMBAR PDF A4 (UNTUK DILIHAT DI LAYAR SEBELUM CETAK)      --}}
        {{-- ========================================================================= --}}
        <div
            id="report-pdf-preview-modal"
            class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/65 backdrop-blur-xs p-3 sm:p-6 print:hidden"
            role="dialog"
            aria-modal="true"
            aria-labelledby="report-pdf-preview-title"
        >
            <div class="mx-auto flex max-w-4xl flex-col overflow-hidden rounded-2xl border border-slate-200 bg-slate-100 shadow-2xl">
                {{-- Modal Topbar --}}
                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 bg-white px-4 py-3 sm:px-6">
                    <div class="flex items-center gap-2.5">
                        <span class="flex size-8 items-center justify-center rounded-lg bg-primary/10 text-primary">
                            <i class="fa-solid fa-file-pdf text-sm"></i>
                        </span>
                        <div>
                            <h2 id="report-pdf-preview-title" class="text-sm font-bold text-ink">
                                Pratinjau Lembar Laporan Cetak A4
                            </h2>
                            <p class="text-[11px] text-muted">
                                Format lembar resmi 1 halaman siap cetak untuk periode {{ $periodeLabel }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <button
                            type="button"
                            onclick="window.print()"
                            class="btn-press inline-flex h-9 items-center gap-2 rounded-xl bg-primary px-4 text-xs font-semibold text-white shadow-xs hover:bg-primary-dark"
                        >
                            <i class="fa-solid fa-print text-xs"></i>
                            <span>Cetak / Simpan PDF</span>
                        </button>
                        <button
                            type="button"
                            onclick="closeReportPdfPreviewModal()"
                            class="inline-flex size-9 items-center justify-center rounded-xl border border-line bg-white text-slate-500 hover:bg-slate-50 hover:text-ink"
                            aria-label="Tutup Pratinjau"
                        >
                            <i class="fa-solid fa-xmark text-sm"></i>
                        </button>
                    </div>
                </div>

                {{-- Kertas A4 Preview --}}
                <div class="overflow-y-auto p-4 sm:p-6 max-h-[82vh]">
                    <div class="mx-auto w-full max-w-[210mm] rounded-lg border border-slate-300 bg-white p-6 sm:p-8 shadow-md text-slate-900">
                        {{-- Kop Surat Preview --}}
                        <div class="flex items-center justify-between gap-4 pb-2.5">
                            <div class="flex items-center gap-3.5">
                                <img
                                    src="{{ asset('images/Logo.png') }}"
                                    alt="Logo MNC University"
                                    width="48"
                                    height="48"
                                    style="width: 48px; height: 48px; max-width: 48px; max-height: 48px; object-fit: contain;"
                                    class="print-kop-logo shrink-0"
                                >
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-widest text-slate-600">
                                        UNIVERSITAS MEDIA NUSANTARA CITRA (MNC UNIVERSITY)
                                    </p>
                                    <h3 class="text-base font-extrabold uppercase tracking-wide text-[#0F2A6B] leading-tight">
                                        PERPUSTAKAAN MNC UNIVERSITY
                                    </h3>
                                    <p class="text-[10px] text-slate-600 leading-snug">
                                        Gedung MNC University, Jl. Arteri Kedoya No. 2, Kebon Jeruk, Jakarta Barat 11520
                                    </p>
                                    <p class="text-[10px] text-slate-500 leading-snug">
                                        Laman: perpustakaan.mncu.ac.id &bull; Surel: library@mncu.ac.id &bull; Telp: (021) 5835-8696
                                    </p>
                                </div>
                            </div>
                            <div class="text-right shrink-0 hidden sm:block">
                                <div class="inline-block rounded border border-[#0F2A6B]/30 bg-slate-50 px-2.5 py-1 text-right">
                                    <span class="block text-[8px] font-bold uppercase tracking-wider text-slate-500">KODE DOKUMEN LAPORAN</span>
                                    <span class="font-mono text-[10px] font-bold text-[#0F2A6B]">LAP-SIR/{{ now()->format('Y/m') }}/MNCU</span>
                                </div>
                            </div>
                        </div>

                        <div class="border-b-2 border-[#0F2A6B]"></div>
                        <div class="mt-0.5 mb-3 border-b border-slate-400"></div>

                        <div class="mb-3 text-center">
                            <h4 class="text-sm font-extrabold uppercase tracking-wider text-[#0F2A6B] underline decoration-[#0F2A6B] underline-offset-4">
                                LAPORAN REKAPITULASI SIRKULASI &amp; KOLEKSI PERPUSTAKAAN
                            </h4>
                            <p class="mt-1 text-[10px] text-slate-600">
                                Cakupan Periode: <strong class="font-bold text-slate-900">{{ $periodeLabel }}</strong>
                                &nbsp;|&nbsp; Tanggal Cetak: <strong class="font-bold text-slate-900">{{ now()->translatedFormat('d F Y') }}</strong>
                                &nbsp;|&nbsp; Dicetak Oleh: <strong class="font-bold text-slate-900">{{ Auth::user()->nama_lengkap }}</strong>
                            </p>
                        </div>

                        {{-- Ringkasan 4 Pilar Preview --}}
                        <div class="mb-3 overflow-hidden border border-[#0F2A6B]">
                            <div class="bg-[#0F2A6B] px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-white">
                                I. RINGKASAN EKSEKUTIF KOLEKSI &amp; SIRKULASI
                            </div>
                            <div class="grid grid-cols-2 sm:grid-cols-4 divide-y sm:divide-y-0 sm:divide-x divide-slate-300 bg-white text-left">
                                <div class="p-2.5">
                                    <span class="block text-[9px] font-bold uppercase tracking-wider text-slate-500">1. Total Koleksi Buku</span>
                                    <p class="mt-0.5 text-sm font-extrabold text-[#0F2A6B]">{{ number_format($totalJudulBuku) }} Judul</p>
                                    <p class="mt-0.5 text-[9px] text-slate-600">Fisik: {{ number_format($totalEksemplarFisik) }} eks &bull; Tersedia: {{ number_format($totalEksemplarTersedia) }}</p>
                                </div>
                                <div class="p-2.5">
                                    <span class="block text-[9px] font-bold uppercase tracking-wider text-slate-500">2. Total Anggota Member</span>
                                    <p class="mt-0.5 text-sm font-extrabold text-emerald-700">{{ number_format($totalAnggotaMember) }} Member</p>
                                    <p class="mt-0.5 text-[9px] text-slate-600">Mhs: {{ $totalMemberMahasiswa }} &bull; Dosen: {{ $totalMemberDosen }} &bull; Baru: +{{ $memberBaruPeriode }}</p>
                                </div>
                                <div class="p-2.5">
                                    <span class="block text-[9px] font-bold uppercase tracking-wider text-slate-500">3. Total User Meminjam Buku</span>
                                    <p class="mt-0.5 text-sm font-extrabold text-indigo-800">{{ number_format($totalUserMeminjam) }} Orang</p>
                                    <p class="mt-0.5 text-[9px] text-slate-600">Total Pinjam: {{ number_format($totalTransaksiPeminjaman) }} Transaksi</p>
                                </div>
                                <div class="p-2.5">
                                    <span class="block text-[9px] font-bold uppercase tracking-wider text-slate-500">4. Buku Belum Dikembalikan</span>
                                    <p class="mt-0.5 text-sm font-extrabold {{ $totalBukuBelumKembali > 0 ? 'text-amber-800' : 'text-emerald-700' }}">{{ number_format($totalBukuBelumKembali) }} Eksemplar</p>
                                    <p class="mt-0.5 text-[9px] text-slate-600">Status: {{ $totalBukuBelumKembali > 0 ? 'Pinjaman Berjalan' : 'Nihil / Tertib' }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Tabel II Preview --}}
                        <div class="mb-3 overflow-x-auto border border-[#0F2A6B]">
                            <div class="flex items-center justify-between bg-[#0F2A6B] px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-white">
                                <span>II. DAFTAR BUKU YANG BELUM DIKEMBALIKAN</span>
                                <span>TOTAL: {{ $totalBukuBelumKembali }} EKSEMPLAR</span>
                            </div>
                            <table class="w-full border-collapse text-[10px]">
                                <thead>
                                    <tr class="bg-slate-100 text-slate-800 font-bold uppercase text-[9px]">
                                        <th class="border border-slate-300 px-1.5 py-1 text-center">NO</th>
                                        <th class="border border-slate-300 px-2 py-1 text-left">NAMA PEMINJAM</th>
                                        <th class="border border-slate-300 px-2 py-1 text-left">JUDUL BUKU &amp; BARCODE</th>
                                        <th class="border border-slate-300 px-2 py-1 text-center">TGL PINJAM</th>
                                        <th class="border border-slate-300 px-2 py-1 text-center">JATUH TEMPO</th>
                                        <th class="border border-slate-300 px-2 py-1 text-center">STATUS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($daftarBukuBelumKembali as $idx => $item)
                                        @php
                                            $hariTelat = $item->hitungHariTerlambat();
                                        @endphp
                                        <tr>
                                            <td class="border border-slate-300 px-1.5 py-1 text-center">{{ $idx + 1 }}</td>
                                            <td class="border border-slate-300 px-2 py-1 font-semibold">{{ $item->pengguna?->nama_lengkap ?? 'Member' }}</td>
                                            <td class="border border-slate-300 px-2 py-1">{{ $item->buku?->judul_buku ?? 'Buku' }} ({{ $item->eksemplar?->nomor_barcode ?? '-' }})</td>
                                            <td class="border border-slate-300 px-2 py-1 text-center">{{ $item->tanggal_pinjam ? $item->tanggal_pinjam->format('d M Y') : '-' }}</td>
                                            <td class="border border-slate-300 px-2 py-1 text-center">{{ $item->tanggal_jatuh_tempo ? $item->tanggal_jatuh_tempo->format('d M Y') : '-' }}</td>
                                            <td class="border border-slate-300 px-2 py-1 text-center font-bold {{ $hariTelat > 0 ? 'text-rose-700' : 'text-emerald-700' }}">
                                                {{ $hariTelat > 0 ? 'Terlambat '.$hariTelat.' Hari' : 'Masa Pinjam Aktif' }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="border border-slate-300 px-2 py-2 text-center italic text-slate-500">Tidak ada pinjaman tertunggak.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- Tabel III Preview --}}
                        <div class="mb-3 overflow-x-auto border border-[#0F2A6B]">
                            <div class="flex items-center justify-between bg-[#0F2A6B] px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-white">
                                <span>III. REKAPITULASI TRANSAKSI PEMINJAMAN PERIODE ({{ strtoupper($periodeLabel) }})</span>
                                <span>TOTAL: {{ $totalTransaksiPeminjaman }} TRANSAKSI</span>
                            </div>
                            <table class="w-full border-collapse text-[10px]">
                                <thead>
                                    <tr class="bg-slate-100 text-slate-800 font-bold uppercase text-[9px]">
                                        <th class="border border-slate-300 px-1.5 py-1 text-center">NO</th>
                                        <th class="border border-slate-300 px-2 py-1 text-left">PEMINJAM</th>
                                        <th class="border border-slate-300 px-2 py-1 text-left">JUDUL BUKU</th>
                                        <th class="border border-slate-300 px-2 py-1 text-center">TGL PINJAM</th>
                                        <th class="border border-slate-300 px-2 py-1 text-center">TGL KEMBALI</th>
                                        <th class="border border-slate-300 px-2 py-1 text-center">STATUS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($daftarPeminjamanPeriode as $idx2 => $trx)
                                        <tr>
                                            <td class="border border-slate-300 px-1.5 py-1 text-center">{{ $idx2 + 1 }}</td>
                                            <td class="border border-slate-300 px-2 py-1 font-semibold">{{ $trx->pengguna?->nama_lengkap ?? 'Member' }}</td>
                                            <td class="border border-slate-300 px-2 py-1">{{ $trx->buku?->judul_buku ?? 'Buku' }}</td>
                                            <td class="border border-slate-300 px-2 py-1 text-center">{{ $trx->tanggal_pinjam ? $trx->tanggal_pinjam->format('d M Y') : '-' }}</td>
                                            <td class="border border-slate-300 px-2 py-1 text-center">{{ $trx->tanggal_kembali ? $trx->tanggal_kembali->format('d M Y') : 'Belum Kembali' }}</td>
                                            <td class="border border-slate-300 px-2 py-1 text-center font-bold">{{ $trx->status }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="border border-slate-300 px-2 py-2 text-center italic text-slate-500">Tidak ada transaksi pada periode ini.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- Tanda Tangan Preview --}}
                        <div class="mt-5 pt-2 flex justify-between items-start text-[10px] text-slate-800 px-4 sm:px-6">
                            <div class="text-center w-52">
                                <p>Mengetahui,</p>
                                <p class="font-bold text-[#0F2A6B] mt-0.5">Kepala Perpustakaan MNC University</p>
                                <div class="h-12"></div>
                                <p class="font-bold underline text-slate-900">Dr. H. Muhammad Arifin, M.Hum.</p>
                                <p class="text-[9px] text-slate-500 mt-0.5">NIP. 19780812 200501 1 002</p>
                            </div>
                            <div class="text-center w-52">
                                <p>Jakarta Barat, {{ now()->translatedFormat('d F Y') }}</p>
                                <p class="font-bold text-[#0F2A6B] mt-0.5">Pustakawan Pelaksana</p>
                                <div class="h-12"></div>
                                <p class="font-bold underline text-slate-900">{{ Auth::user()->nama_lengkap }}</p>
                                <p class="text-[9px] text-slate-500 mt-0.5">ID: {{ Auth::user()->nomor_identitas ?? 'Petugas Perpustakaan' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openReportPdfPreviewModal() {
            const modal = document.getElementById('report-pdf-preview-modal');
            if (modal) {
                modal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }
        }

        function closeReportPdfPreviewModal() {
            const modal = document.getElementById('report-pdf-preview-modal');
            if (modal) {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        }

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeReportPdfPreviewModal();
            }
        });
    </script>
@endsection
