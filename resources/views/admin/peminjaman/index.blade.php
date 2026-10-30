@extends('layouts.app')

@section('title', 'Manajemen Peminjaman & Keterlambatan')
@section('description', 'Kelola sirkulasi peminjaman buku perpustakaan MNC University, validasi eligibilitas pemustaka, pantau keterlambatan pengembalian tanpa sanksi denda, dan integrasi API Sanctum.')

@section('content')
    <div class="mx-auto flex w-full max-w-[1570px] flex-1 flex-col gap-6 px-3 sm:px-4 lg:px-6 xl:px-8 py-4 sm:py-6 lg:py-8 responsive-desktop-container">
        {{-- Breadcrumb & Header --}}
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <nav aria-label="Breadcrumb" class="flex items-center gap-2 text-xs text-muted sm:text-sm">
                    <a href="{{ route('beranda') }}" class="hover:text-primary">Beranda</a>
                    <svg class="size-3.5 text-muted/60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
                    <span class="text-ink-secondary">Panel Manajemen</span>
                    <svg class="size-3.5 text-muted/60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
                    <span class="font-medium text-ink">Peminjaman &amp; Sirkulasi</span>
                </nav>
                <h1 class="mt-1 text-2xl font-bold tracking-tight text-primary sm:text-3xl">
                    Sirkulasi Peminjaman &amp; Keterlambatan Buku
                </h1>
                <p class="mt-0.5 text-xs text-ink-secondary sm:text-sm">
                    Kelola data transaksi sirkulasi, validasi eligibilitas mahasiswa, visualisasi durasi keterlambatan tanpa sanksi finansial, serta status peminjaman pemustaka.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2.5">
                {{-- Tombol Catat Peminjaman Baru --}}
                <a
                    href="{{ route('admin.peminjaman.create') }}"
                    class="inline-flex h-10 items-center gap-2 rounded-xl bg-primary px-4 text-xs font-semibold text-white shadow-sm transition-all hover:bg-primary-dark sm:h-11 sm:text-sm"
                >
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>Catat Peminjaman Baru</span>
                </a>
            </div>
        </div>

        {{-- Kartu Metrik Sirkulasi & Keterlambatan --}}
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-5 lg:gap-4">
            {{-- 1. Total Transaksi --}}
            <div class="rounded-2xl border border-line bg-white p-4 shadow-xs sm:p-5">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-medium text-muted">Total Peminjaman</span>
                    <span class="flex size-8 items-center justify-center rounded-xl bg-primary/10 text-primary">
                        <i class="fa-solid fa-book-bookmark text-sm"></i>
                    </span>
                </div>
                <p class="mt-2 text-2xl font-bold text-ink sm:text-3xl">{{ number_format($totalPinjaman) }}</p>
                <p class="mt-1 text-[11px] text-muted">Seluruh riwayat transaksi</p>
            </div>

            {{-- 2. Sedang Dipinjam (Aktif) --}}
            <div class="rounded-2xl border border-blue-200 bg-blue-50/50 p-4 shadow-xs sm:p-5">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-medium text-blue-700">Sedang Dipinjam</span>
                    <span class="flex size-8 items-center justify-center rounded-xl bg-blue-100 text-blue-700">
                        <i class="fa-solid fa-clock text-sm"></i>
                    </span>
                </div>
                <p class="mt-2 text-2xl font-bold text-blue-900 sm:text-3xl">{{ number_format($totalAktif) }}</p>
                <p class="mt-1 text-[11px] text-blue-700">Buku masih di pemustaka</p>
            </div>

            {{-- 3. Menunggu Approval Pengembalian --}}
            <div class="rounded-2xl border border-amber-300 bg-amber-50/70 p-4 shadow-xs sm:p-5">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-medium text-amber-800">Menunggu Verifikasi</span>
                    <span class="flex size-8 items-center justify-center rounded-xl bg-amber-200/80 text-amber-800">
                        <i class="fa-solid fa-clock-rotate-left text-sm"></i>
                    </span>
                </div>
                <p class="mt-2 text-2xl font-bold text-amber-900 sm:text-3xl">{{ number_format($totalMenungguVerifikasi ?? 0) }}</p>
                <p class="mt-1 text-[11px] text-amber-800">Perlu persetujuan admin</p>
            </div>

            {{-- 4. Terlambat Pengembalian --}}
            <div class="rounded-2xl border border-rose-200 bg-rose-50/50 p-4 shadow-xs sm:p-5">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-medium text-rose-700">Keterlambatan</span>
                    <span class="flex size-8 items-center justify-center rounded-xl bg-rose-100 text-rose-700">
                        <i class="fa-solid fa-triangle-exclamation text-sm"></i>
                    </span>
                </div>
                <p class="mt-2 text-2xl font-bold text-rose-900 sm:text-3xl">{{ number_format($totalTerlambat) }}</p>
                <p class="mt-1 text-[11px] text-rose-700">Lewat batas jatuh tempo</p>
            </div>

            {{-- 5. Selesai Dikembalikan --}}
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50/50 p-4 shadow-xs sm:p-5 col-span-2 sm:col-span-1">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-medium text-emerald-700">Selesai Kembali</span>
                    <span class="flex size-8 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">
                        <i class="fa-solid fa-circle-check text-sm"></i>
                    </span>
                </div>
                <p class="mt-2 text-2xl font-bold text-emerald-900 sm:text-3xl">{{ number_format($totalKembali) }}</p>
                <p class="mt-1 text-[11px] text-emerald-700">Stok eksemplar tersedia</p>
            </div>
        </div>

        {{-- Filter & Pencarian --}}
        <div class="flex flex-col gap-3 rounded-2xl border border-line bg-white p-4 shadow-xs sm:p-5">
            <form action="{{ route('admin.peminjaman.index') }}" method="GET" class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                {{-- Input Pencarian --}}
                <div class="relative flex-1">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-muted text-xs"></i>
                    <input
                        type="text"
                        name="q"
                        value="{{ $q }}"
                        placeholder="Cari ID transaksi, nama mahasiswa, NIM, judul buku, atau kode inventaris..."
                        class="h-10 w-full rounded-xl border border-line bg-slate-50/50 pl-9 pr-4 text-xs text-ink placeholder:text-muted focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 sm:text-sm"
                    >
                </div>

                {{-- Tab Filter Status --}}
                <div class="flex flex-wrap items-center gap-1.5 border-t border-line pt-3 lg:border-t-0 lg:pt-0">
                    <a
                        href="{{ route('admin.peminjaman.index', array_filter(['q' => $q])) }}"
                        class="rounded-lg px-3 py-1.5 text-xs font-semibold transition-colors {{ $status === '' ? 'bg-primary text-white shadow-xs' : 'bg-slate-100 text-muted hover:text-ink' }}"
                    >
                        Semua
                    </a>
                    <a
                        href="{{ route('admin.peminjaman.index', array_filter(['q' => $q, 'status' => 'dipinjam'])) }}"
                        class="rounded-lg px-3 py-1.5 text-xs font-semibold transition-colors {{ $status === 'dipinjam' ? 'bg-blue-600 text-white shadow-xs' : 'bg-slate-100 text-muted hover:text-ink' }}"
                    >
                        Sedang Dipinjam
                    </a>
                    <a
                        href="{{ route('admin.peminjaman.index', array_filter(['q' => $q, 'status' => 'menunggu-verifikasi'])) }}"
                        class="rounded-lg px-3 py-1.5 text-xs font-semibold transition-colors {{ $status === 'menunggu-verifikasi' ? 'bg-amber-600 text-white shadow-xs' : 'bg-slate-100 text-muted hover:text-ink' }}"
                    >
                        Menunggu Verifikasi ({{ $totalMenungguVerifikasi ?? 0 }})
                    </a>
                    <a
                        href="{{ route('admin.peminjaman.index', array_filter(['q' => $q, 'status' => 'terlambat'])) }}"
                        class="rounded-lg px-3 py-1.5 text-xs font-semibold transition-colors {{ $status === 'terlambat' ? 'bg-rose-600 text-white shadow-xs' : 'bg-slate-100 text-muted hover:text-ink' }}"
                    >
                        Terlambat
                    </a>
                    <a
                        href="{{ route('admin.peminjaman.index', array_filter(['q' => $q, 'status' => 'kembali'])) }}"
                        class="rounded-lg px-3 py-1.5 text-xs font-semibold transition-colors {{ $status === 'kembali' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-slate-100 text-muted hover:text-ink' }}"
                    >
                        Selesai Kembali
                    </a>
                </div>
            </form>
        </div>

        {{-- Tabel Sirkulasi Peminjaman Buku --}}
        <div class="overflow-hidden rounded-2xl border border-line bg-white shadow-xs">
            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-left text-xs">
                    <thead>
                        <tr class="border-b border-line bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-muted">
                            <th class="px-4 py-3.5 sm:px-6">ID &amp; Peminjam</th>
                            <th class="px-4 py-3.5 sm:px-6">Buku &amp; Eksemplar</th>
                            <th class="px-4 py-3.5 sm:px-6">Jadwal Pinjam</th>
                            <th class="px-4 py-3.5 sm:px-6">Status &amp; Keterlambatan</th>
                            <th class="px-4 py-3.5 text-right sm:px-6">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line">
                        @forelse ($loans as $loan)
                            @php
                                $isLate = $loan->isTerlambat();
                                $hariTerlambat = $loan->hitungHariTerlambat();
                                $isReturned = $loan->tanggal_kembali !== null;
                            @endphp
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                {{-- 1. ID & Peminjam --}}
                                <td class="px-4 py-3.5 sm:px-6">
                                    <div class="flex flex-col">
                                        <a href="{{ route('admin.peminjaman.show', $loan->id_peminjaman) }}" class="font-mono text-xs font-bold text-primary hover:underline">
                                            {{ $loan->id_peminjaman }}
                                        </a>
                                        <span class="mt-0.5 font-semibold text-ink sm:text-sm">
                                            {{ $loan->pengguna->nama_lengkap }}
                                        </span>
                                        <span class="text-[11px] text-muted">
                                            {{ $loan->pengguna->nim ? 'NIM: ' . $loan->pengguna->nim : $loan->pengguna->email }}
                                            &bull;
                                            <span class="font-medium text-slate-600">{{ $loan->pengguna->peran?->nama_peran }}</span>
                                        </span>
                                    </div>
                                </td>

                                {{-- 2. Buku & Eksemplar --}}
                                <td class="px-4 py-3.5 sm:px-6">
                                    <div class="flex flex-col">
                                        <span class="font-semibold text-ink line-clamp-1 sm:text-sm">
                                            {{ $loan->buku->judul_buku }}
                                        </span>
                                        <div class="mt-0.5 flex flex-wrap items-center gap-1.5 text-[11px] text-muted">
                                            <span class="inline-flex items-center gap-1 rounded bg-slate-100 px-1.5 py-0.5 font-mono text-[10px] font-semibold text-slate-700">
                                                <i class="fa-solid fa-barcode text-[9px]"></i>
                                                {{ $loan->eksemplar->kode_inventaris }}
                                            </span>
                                            @if ($loan->eksemplar->nomor_panggil)
                                                <span>{{ $loan->eksemplar->nomor_panggil }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                {{-- 3. Jadwal Pinjam --}}
                                <td class="px-4 py-3.5 sm:px-6">
                                    <div class="flex flex-col text-[11px]">
                                        <div class="flex items-center gap-1.5 text-ink">
                                            <i class="fa-regular fa-calendar text-muted"></i>
                                            <span>Pinjam: {{ $loan->tanggal_pinjam->format('d M Y') }}</span>
                                        </div>
                                        <div class="mt-1 flex items-center gap-1.5 {{ ! $isReturned && $isLate ? 'font-semibold text-rose-700' : 'text-slate-600' }}">
                                            <i class="fa-regular fa-calendar-check {{ ! $isReturned && $isLate ? 'text-rose-600' : 'text-muted' }}"></i>
                                            <span>Tempo: {{ $loan->tanggal_jatuh_tempo->format('d M Y') }}</span>
                                        </div>
                                    </div>
                                </td>

                                {{-- 4. Status & Keterlambatan (Hanya tampilkan keterlambatan tanpa sanksi) --}}
                                <td class="px-4 py-3.5 sm:px-6">
                                    @if ($isReturned)
                                        <div class="flex flex-col items-start gap-1">
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-2.5 py-0.5 text-[11px] font-semibold text-emerald-800">
                                                <i class="fa-solid fa-check text-[10px]"></i>
                                                Selesai Dikembalikan
                                            </span>
                                            <span class="text-[10px] text-muted">
                                                Kembali: {{ $loan->tanggal_kembali->format('d M Y') }}
                                            </span>
                                            @if ($loan->hari_keterlambatan > 0)
                                                <span class="rounded bg-rose-50 px-1.5 py-0.5 text-[10px] font-medium text-rose-700 border border-rose-200">
                                                    Keterlambatan {{ $loan->hari_keterlambatan }} hari
                                                </span>
                                            @endif
                                        </div>
                                    @elseif ($loan->status === 'Menunggu Verifikasi')
                                        <div class="flex flex-col items-start gap-1">
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-100 px-2.5 py-0.5 text-[11px] font-bold text-amber-900 border border-amber-300 animate-pulse">
                                                <i class="fa-solid fa-clock-rotate-left text-[10px]"></i>
                                                Menunggu Verifikasi
                                            </span>
                                            @if ($isLate)
                                                <span class="rounded bg-rose-50 px-1.5 py-0.5 text-[10px] font-semibold text-rose-800 border border-rose-200">
                                                    Terlambat {{ $hariTerlambat }} Hari
                                                </span>
                                            @endif
                                            @if ($loan->tanggal_pengajuan_kembali)
                                                <span class="text-[10px] text-muted">
                                                    Diajukan: {{ $loan->tanggal_pengajuan_kembali->format('d M Y H:i') }}
                                                </span>
                                            @endif
                                            @if ($loan->catatan_pengajuan_kembali)
                                                <span class="text-[10px] text-ink-secondary italic line-clamp-1 max-w-[200px]" title="{{ $loan->catatan_pengajuan_kembali }}">
                                                    "{{ $loan->catatan_pengajuan_kembali }}"
                                                </span>
                                            @endif
                                        </div>
                                    @elseif ($isLate)
                                        <div class="flex flex-col items-start gap-1">
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-rose-100 px-2.5 py-0.5 text-[11px] font-bold text-rose-800 animate-pulse">
                                                <i class="fa-solid fa-triangle-exclamation text-[10px]"></i>
                                                Terlambat {{ $hariTerlambat }} Hari
                                            </span>
                                            @if ($loan->sanksi)
                                                <span class="rounded bg-rose-50 px-1.5 py-0.5 text-[10px] font-semibold text-rose-800 border border-rose-200">
                                                    <i class="fa-solid fa-gavel text-[9px] mr-1"></i>{{ $loan->sanksi }}
                                                </span>
                                            @else
                                                <span class="text-[10px] text-muted">
                                                    Belum ada sanksi dicatat
                                                </span>
                                            @endif
                                            @if ($loan->notifikasi_peringatan_dikirim)
                                                <span class="inline-flex items-center gap-1 text-[10px] font-medium text-amber-700">
                                                    <i class="fa-solid fa-bell text-[9px]"></i>
                                                    Peringatan terkirim
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <div class="flex flex-col items-start gap-1">
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-100 px-2.5 py-0.5 text-[11px] font-semibold text-blue-800">
                                                <i class="fa-solid fa-book-reader text-[10px]"></i>
                                                Sedang Dipinjam
                                            </span>
                                            @php
                                                $sisaHari = \Carbon\Carbon::now()->startOfDay()->diffInDays($loan->tanggal_jatuh_tempo->startOfDay(), false);
                                            @endphp
                                            <span class="text-[10px] text-muted">
                                                Sisa tempo: {{ max(0, $sisaHari) }} hari
                                            </span>
                                        </div>
                                    @endif
                                </td>

                                {{-- 5. Aksi --}}
                                <td class="px-4 py-3.5 text-right sm:px-6">
                                    <div class="flex items-center justify-end gap-1.5">
                                        {{-- Tombol Detail --}}
                                        <a
                                            href="{{ route('admin.peminjaman.show', $loan->id_peminjaman) }}"
                                            class="inline-flex size-8 items-center justify-center rounded-lg border border-line bg-white text-ink-secondary transition-colors hover:border-primary hover:text-primary"
                                            title="Lihat Detail Transaksi"
                                        >
                                            <i class="fa-solid fa-eye text-xs"></i>
                                        </a>

                                        @if (! $isReturned)
                                            @if ($loan->status === 'Menunggu Verifikasi')
                                                {{-- Tombol Setujui / Approve Pengembalian --}}
                                                <button
                                                    type="button"
                                                    onclick="openKembalikanModal('{{ $loan->id_peminjaman }}', '{{ addslashes($loan->buku->judul_buku) }}', '{{ addslashes($loan->pengguna->nama_lengkap) }}', {{ $hariTerlambat }})"
                                                    class="inline-flex h-8 items-center gap-1.5 rounded-lg bg-emerald-600 px-2.5 text-xs font-semibold text-white shadow-xs transition-colors hover:bg-emerald-700"
                                                    title="Setujui dan selesaikan pengembalian buku ini"
                                                >
                                                    <i class="fa-solid fa-check text-[11px]"></i>
                                                    <span>Setujui</span>
                                                </button>

                                                {{-- Tombol Tolak Pengajuan Pengembalian --}}
                                                <button
                                                    type="button"
                                                    onclick="openTolakModal('{{ $loan->id_peminjaman }}', '{{ addslashes($loan->buku->judul_buku) }}', '{{ addslashes($loan->pengguna->nama_lengkap) }}')"
                                                    class="inline-flex size-8 items-center justify-center rounded-lg border border-rose-200 bg-rose-50 text-rose-700 transition-colors hover:bg-rose-100"
                                                    title="Tolak Pengajuan Pengembalian (Buku Belum Diterima)"
                                                >
                                                    <i class="fa-solid fa-xmark text-xs"></i>
                                                </button>
                                            @else
                                                {{-- Tombol Input Sanksi & Keterlambatan jika Terlambat --}}
                                                @if ($isLate)
                                                    <button
                                                        type="button"
                                                        onclick="openSanksiModal('{{ $loan->id_peminjaman }}', '{{ addslashes($loan->buku->judul_buku) }}', '{{ addslashes($loan->pengguna->nama_lengkap) }}', {{ $hariTerlambat }}, '{{ addslashes($loan->sanksi ?? '') }}', '{{ addslashes($loan->catatan_admin ?? '') }}')"
                                                        class="inline-flex size-8 items-center justify-center rounded-lg border border-rose-200 bg-rose-50 text-rose-700 transition-colors hover:bg-rose-100"
                                                        title="Catat Data Keterlambatan & Sanksi"
                                                    >
                                                        <i class="fa-solid fa-gavel text-xs"></i>
                                                    </button>

                                                    {{-- Tombol Kirim Peringatan Keterlambatan --}}
                                                    <form action="{{ route('admin.peminjaman.kirim-peringatan', $loan->id_peminjaman) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button
                                                            type="submit"
                                                            class="inline-flex size-8 items-center justify-center rounded-lg border border-amber-200 bg-amber-50 text-amber-700 transition-colors hover:bg-amber-100"
                                                            title="Kirim Notifikasi Peringatan Batas Waktu ke Mahasiswa"
                                                        >
                                                            <i class="fa-solid fa-bell text-xs"></i>
                                                        </button>
                                                    </form>
                                                @endif

                                                {{-- Tombol Pengembalian Buku --}}
                                                <button
                                                    type="button"
                                                    onclick="openKembalikanModal('{{ $loan->id_peminjaman }}', '{{ addslashes($loan->buku->judul_buku) }}', '{{ addslashes($loan->pengguna->nama_lengkap) }}', {{ $hariTerlambat }})"
                                                    class="inline-flex h-8 items-center gap-1.5 rounded-lg bg-emerald-600 px-2.5 text-xs font-semibold text-white shadow-xs transition-colors hover:bg-emerald-700"
                                                    title="Proses Pengembalian Buku"
                                                >
                                                    <i class="fa-solid fa-arrow-rotate-left text-[11px]"></i>
                                                    <span class="hidden sm:inline">Kembalikan</span>
                                                </button>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-muted">
                                    <div class="mx-auto flex size-14 items-center justify-center rounded-2xl bg-slate-100 text-muted">
                                        <i class="fa-solid fa-book-open-reader text-2xl"></i>
                                    </div>
                                    <p class="mt-3 text-sm font-semibold text-ink">Tidak ada data transaksi peminjaman</p>
                                    <p class="mt-1 text-xs text-muted">Belum ada peminjaman buku yang cocok dengan kriteria pencarian.</p>
                                    <a href="{{ route('admin.peminjaman.create') }}" class="mt-4 inline-flex items-center gap-1.5 rounded-xl bg-primary px-3.5 py-2 text-xs font-semibold text-white shadow-xs hover:bg-primary-dark">
                                        <i class="fa-solid fa-plus text-xs"></i>
                                        Catat Peminjaman Pertama
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($loans->hasPages())
                <div class="border-t border-line px-4 py-3 sm:px-6">
                    {{ $loans->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- Modal Konfirmasi Pengembalian Buku --}}
    <div id="modal-kembalikan" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/50 p-4 backdrop-blur-xs flex items-center justify-center">
        <div class="relative w-full max-w-md rounded-2xl border border-line bg-white p-6 shadow-2xl">
            <div class="flex items-center justify-between border-b border-line pb-3">
                <div class="flex items-center gap-2">
                    <span class="flex size-8 items-center justify-center rounded-lg bg-emerald-100 text-emerald-700">
                        <i class="fa-solid fa-arrow-rotate-left text-sm"></i>
                    </span>
                    <h3 class="text-sm font-bold text-ink">Validasi Pengembalian Buku</h3>
                </div>
                <button type="button" onclick="closeKembalikanModal()" class="text-muted hover:text-ink">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form id="form-kembalikan" method="POST" class="mt-4 space-y-4">
                @csrf
                <div class="rounded-xl bg-slate-50 p-3.5 text-xs text-ink-secondary space-y-1.5 border border-line">
                    <p><strong class="text-ink">ID Transaksi:</strong> <span id="modal-id-peminjaman" class="font-mono text-primary font-semibold"></span></p>
                    <p><strong class="text-ink">Mahasiswa:</strong> <span id="modal-nama-peminjam"></span></p>
                    <p><strong class="text-ink">Judul Buku:</strong> <span id="modal-judul-buku"></span></p>
                </div>

                {{-- Alert Keterlambatan Jika Ada --}}
                <div id="modal-late-alert" class="hidden rounded-xl border border-rose-200 bg-rose-50 p-3 text-xs text-rose-800">
                    <div class="flex items-center gap-2 font-bold">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        <span>Pengembalian Terlambat <span id="modal-late-days"></span> Hari</span>
                    </div>
                    <p class="mt-1 text-[11px]">
                        Buku dikembalikan melewati batas jadwal. Sesuai ketentuan perpustakaan, <strong>tidak dikenakan sanksi denda finansial</strong>, hanya catatan durasi keterlambatan yang akan disimpan.
                    </p>
                </div>

                <div>
                    <label for="modal-catatan" class="block text-xs font-semibold text-ink">Catatan Pengembalian (Opsional)</label>
                    <textarea
                        id="modal-catatan"
                        name="catatan"
                        rows="2"
                        placeholder="Contoh: Buku kembali dalam kondisi fisik baik..."
                        class="mt-1.5 w-full rounded-xl border border-line bg-white p-2.5 text-xs text-ink focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20"
                    ></textarea>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2 border-t border-line">
                    <button
                        type="button"
                        onclick="closeKembalikanModal()"
                        class="h-9 rounded-xl border border-line bg-white px-4 text-xs font-semibold text-ink-secondary hover:bg-slate-50"
                    >
                        Batal
                    </button>
                    <button
                        type="submit"
                        class="h-9 inline-flex items-center gap-1.5 rounded-xl bg-emerald-600 px-4 text-xs font-semibold text-white hover:bg-emerald-700 shadow-xs"
                    >
                        <i class="fa-solid fa-check text-xs"></i>
                        <span>Konfirmasi Pengembalian</span>
                    </button>
                </div>
            </form>
        </div>
    </div>



    {{-- Modal Input Keterlambatan & Sanksi --}}
    <div id="modal-sanksi" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/50 p-4 backdrop-blur-xs flex items-center justify-center">
        <div class="relative w-full max-w-lg rounded-2xl border border-line bg-white p-6 shadow-2xl">
            <div class="flex items-center justify-between border-b border-line pb-3">
                <div class="flex items-center gap-2">
                    <span class="flex size-8 items-center justify-center rounded-lg bg-rose-100 text-rose-700">
                        <i class="fa-solid fa-gavel text-sm"></i>
                    </span>
                    <div>
                        <h3 class="text-sm font-bold text-ink">Catat Keterlambatan &amp; Sanksi Pemustaka</h3>
                        <p class="text-[11px] text-muted">Penanganan peminjam yang belum mengembalikan buku lewat jadwal</p>
                    </div>
                </div>
                <button type="button" onclick="closeSanksiModal()" class="text-muted hover:text-ink">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form id="form-sanksi" action="" method="POST" class="mt-4 space-y-4 text-xs">
                @csrf
                <div class="rounded-xl border border-rose-200 bg-rose-50/70 p-3.5 text-rose-900">
                    <div class="flex items-center gap-1.5 font-bold">
                        <i class="fa-solid fa-triangle-exclamation text-rose-600"></i>
                        <span>Peminjaman Terlambat: <span id="modal-sanksi-late-days">0</span> Hari</span>
                    </div>
                    <div class="mt-1 text-[11px] space-y-0.5">
                        <p><strong>Peminjam:</strong> <span id="modal-sanksi-nama-peminjam">-</span></p>
                        <p><strong>Judul Buku:</strong> <span id="modal-sanksi-judul-buku">-</span></p>
                    </div>
                </div>

                <div>
                    <label for="input-jenis-sanksi" class="block font-semibold text-ink mb-1">
                        Jenis Sanksi / Tindakan <span class="text-rose-500">*</span>
                    </label>
                    <select
                        name="sanksi"
                        id="input-jenis-sanksi"
                        required
                        class="w-full h-10 rounded-xl border border-line bg-white px-3 text-xs text-ink focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary"
                    >
                        <option value="">Pilih Jenis Sanksi...</option>
                        <option value="Peringatan Tertulis & Penangguhan Peminjaman">Peringatan Tertulis &amp; Penangguhan Peminjaman</option>
                        <option value="Penangguhan Peminjaman 1 Minggu">Penangguhan Peminjaman 1 Minggu</option>
                        <option value="Penangguhan Peminjaman 1 Bulan">Penangguhan Peminjaman 1 Bulan</option>
                        <option value="Tugas Edukatif Perpustakaan">Tugas Edukatif Perpustakaan</option>
                        <option value="Sanksi Ganti Buku / Kompensasi">Sanksi Ganti Buku / Kompensasi</option>
                    </select>
                </div>

                <div>
                    <label for="input-status-sanksi" class="block font-semibold text-ink mb-1">
                        Status Sanksi
                    </label>
                    <select
                        name="status_sanksi"
                        id="input-status-sanksi"
                        class="w-full h-10 rounded-xl border border-line bg-white px-3 text-xs text-ink focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary"
                    >
                        <option value="Aktif">Aktif (Berlaku)</option>
                        <option value="Selesai">Selesai (Sudah Dipenuhi)</option>
                    </select>
                </div>

                <div>
                    <label for="input-catatan-sanksi" class="block font-semibold text-ink mb-1">
                        Catatan Keterlambatan &amp; Riwayat Tindakan
                    </label>
                    <textarea
                        name="catatan_admin"
                        id="input-catatan-sanksi"
                        rows="3"
                        placeholder="Contoh: Pemustaka belum mengembalikan buku setelah dikirimi peringatan..."
                        class="w-full rounded-xl border border-line bg-white p-3 text-xs text-ink focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary"
                    ></textarea>
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-line">
                    <button
                        type="button"
                        onclick="closeSanksiModal()"
                        class="h-9 rounded-xl border border-line bg-white px-4 text-xs font-semibold text-ink hover:bg-slate-50"
                    >
                        Batal
                    </button>
                    <button
                        type="submit"
                        class="h-9 inline-flex items-center gap-1.5 rounded-xl bg-rose-600 px-4 text-xs font-semibold text-white hover:bg-rose-700 shadow-xs"
                    >
                        <i class="fa-solid fa-save text-xs"></i>
                        <span>Simpan Sanksi Keterlambatan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Tolak Pengajuan Pengembalian --}}
    <div
        id="modal-tolak"
        class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 p-4 backdrop-blur-xs hidden"
    >
        <div class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl">
            <div class="flex items-center justify-between border-b border-line pb-3">
                <div class="flex items-center gap-2 text-rose-700 font-bold">
                    <i class="fa-solid fa-circle-xmark"></i>
                    <span>Tolak Pengajuan Pengembalian</span>
                </div>
                <button
                    type="button"
                    onclick="closeTolakModal()"
                    class="rounded-lg p-1.5 text-muted hover:bg-slate-100 hover:text-ink"
                >
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <form id="form-tolak" method="POST" class="mt-4 space-y-4">
                @csrf
                <div class="rounded-xl bg-slate-50 p-3.5 text-xs space-y-1.5 border border-line">
                    <div class="flex justify-between">
                        <span class="text-muted">Kode Pinjam:</span>
                        <span id="modal-tolak-id-peminjaman" class="font-mono font-bold text-ink"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-muted">Peminjam:</span>
                        <span id="modal-tolak-nama-peminjam" class="font-semibold text-ink"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-muted">Buku:</span>
                        <span id="modal-tolak-judul-buku" class="font-medium text-ink max-w-[200px] truncate text-right"></span>
                    </div>
                </div>

                <div class="rounded-xl bg-amber-50 p-3 text-xs text-amber-900 border border-amber-200">
                    <p class="font-semibold flex items-center gap-1.5">
                        <i class="fa-solid fa-triangle-exclamation text-amber-600"></i>
                        <span>Konfirmasi Penolakan</span>
                    </p>
                    <p class="mt-1 text-[11px] leading-relaxed text-amber-800">
                        Status peminjaman akan dikembalikan ke status aktif semula (Dipinjam atau Terlambat) dan pemustaka akan diminta menyerahkan fisik buku ke meja sirkulasi.
                    </p>
                </div>

                <div>
                    <label for="input-alasan-penolakan" class="block font-semibold text-ink mb-1 text-xs">
                        Alasan Penolakan (Opsional / Info untuk Pemustaka)
                    </label>
                    <textarea
                        name="alasan"
                        id="input-alasan-penolakan"
                        rows="3"
                        placeholder="Contoh: Fisik buku belum diterima di meja sirkulasi atau buku memerlukan pengecekan kondisi fisik bersama pemustaka..."
                        class="w-full rounded-xl border border-line bg-white p-3 text-xs text-ink focus:border-rose-500 focus:outline-none focus:ring-1 focus:ring-rose-500"
                    ></textarea>
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-line">
                    <button
                        type="button"
                        onclick="closeTolakModal()"
                        class="h-9 rounded-xl border border-line bg-white px-4 text-xs font-semibold text-ink hover:bg-slate-50"
                    >
                        Batal
                    </button>
                    <button
                        type="submit"
                        class="h-9 inline-flex items-center gap-1.5 rounded-xl bg-rose-600 px-4 text-xs font-semibold text-white hover:bg-rose-700 shadow-xs"
                    >
                        <i class="fa-solid fa-xmark text-xs"></i>
                        <span>Tolak Pengajuan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openKembalikanModal(idPeminjaman, judulBuku, namaPeminjam, hariTerlambat) {
            document.getElementById('modal-id-peminjaman').textContent = idPeminjaman;
            document.getElementById('modal-nama-peminjam').textContent = namaPeminjam;
            document.getElementById('modal-judul-buku').textContent = judulBuku;
            
            const form = document.getElementById('form-kembalikan');
            form.action = `/admin/peminjaman/${idPeminjaman}/kembalikan`;

            const lateAlert = document.getElementById('modal-late-alert');
            const lateDays = document.getElementById('modal-late-days');
            if (hariTerlambat > 0) {
                lateDays.textContent = hariTerlambat;
                lateAlert.classList.remove('hidden');
            } else {
                lateAlert.classList.add('hidden');
            }

            document.getElementById('modal-kembalikan').classList.remove('hidden');
        }

        function closeKembalikanModal() {
            document.getElementById('modal-kembalikan').classList.add('hidden');
        }

        function openTolakModal(idPeminjaman, judulBuku, namaPeminjam) {
            document.getElementById('modal-tolak-id-peminjaman').textContent = idPeminjaman;
            document.getElementById('modal-tolak-nama-peminjam').textContent = namaPeminjam;
            document.getElementById('modal-tolak-judul-buku').textContent = judulBuku;

            const form = document.getElementById('form-tolak');
            form.action = `/admin/peminjaman/${idPeminjaman}/tolak-pengembalian`;

            document.getElementById('input-alasan-penolakan').value = '';
            document.getElementById('modal-tolak').classList.remove('hidden');
        }

        function closeTolakModal() {
            document.getElementById('modal-tolak').classList.add('hidden');
        }

        function openSanksiModal(idPeminjaman, judulBuku, namaPeminjam, hariTerlambat, sanksiExisting, catatanExisting) {
            document.getElementById('modal-sanksi-nama-peminjam').textContent = namaPeminjam;
            document.getElementById('modal-sanksi-judul-buku').textContent = judulBuku;
            document.getElementById('modal-sanksi-late-days').textContent = hariTerlambat;

            const form = document.getElementById('form-sanksi');
            form.action = `/admin/peminjaman/${idPeminjaman}/input-keterlambatan`;

            const selectSanksi = document.getElementById('input-jenis-sanksi');
            if (sanksiExisting) {
                selectSanksi.value = sanksiExisting;
            } else {
                selectSanksi.value = '';
            }

            const inputCatatan = document.getElementById('input-catatan-sanksi');
            inputCatatan.value = catatanExisting || '';

            document.getElementById('modal-sanksi').classList.remove('hidden');
        }

        function closeSanksiModal() {
            document.getElementById('modal-sanksi').classList.add('hidden');
        }
    </script>
@endsection
