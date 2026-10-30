@extends('layouts.app')

@section('title', 'Detail Transaksi Peminjaman ' . $loan->id_peminjaman)
@section('description', 'Detail transaksi sirkulasi peminjaman buku perpustakaan MNC University, kalkulasi hari keterlambatan tanpa sanksi, dan riwayat pengembalian.')

@section('content')
    <div class="mx-auto flex w-full max-w-[1100px] flex-1 flex-col gap-6 px-3 py-6 lg:px-6 lg:py-8">
        {{-- Breadcrumb & Header --}}
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <nav aria-label="Breadcrumb" class="flex items-center gap-2 text-xs text-muted sm:text-sm">
                    <a href="{{ route('beranda') }}" class="hover:text-primary">Beranda</a>
                    <svg class="size-3.5 text-muted/60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
                    <a href="{{ route('admin.peminjaman.index') }}" class="text-ink-secondary hover:text-primary">Peminjaman &amp; Sirkulasi</a>
                    <svg class="size-3.5 text-muted/60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
                    <span class="font-medium text-ink">{{ $loan->id_peminjaman }}</span>
                </nav>
                <div class="mt-1 flex flex-wrap items-center gap-2.5">
                    <h1 class="text-2xl font-bold tracking-tight text-primary sm:text-3xl">
                        Transaksi {{ $loan->id_peminjaman }}
                    </h1>
                    @if ($loan->tanggal_kembali)
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-800">
                            <i class="fa-solid fa-check text-[11px]"></i>
                            Selesai Dikembalikan
                        </span>
                    @elseif ($loan->isTerlambat())
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-rose-100 px-3 py-1 text-xs font-bold text-rose-800">
                            <i class="fa-solid fa-triangle-exclamation text-[11px]"></i>
                            Terlambat {{ $hariTerlambat }} Hari
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-800">
                            <i class="fa-solid fa-clock text-[11px]"></i>
                            Sedang Dipinjam
                        </span>
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a
                    href="{{ route('admin.peminjaman.index') }}"
                    class="h-10 rounded-xl border border-line bg-white px-4 text-xs font-semibold text-ink-secondary hover:bg-slate-50 transition-colors flex items-center gap-2"
                >
                    <i class="fa-solid fa-arrow-left text-xs"></i>
                    <span>Kembali</span>
                </a>

                @if (! $loan->tanggal_kembali)
                    {{-- Tombol Pengembalian --}}
                    <form action="{{ route('admin.peminjaman.kembalikan', $loan->id_peminjaman) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin memproses pengembalian buku ini? Stok eksemplar akan bertambah kembali.')">
                        @csrf
                        <button
                            type="submit"
                            class="h-10 inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 text-xs font-semibold text-white shadow-xs hover:bg-emerald-700 transition-colors"
                        >
                            <i class="fa-solid fa-arrow-rotate-left text-xs"></i>
                            <span>Kembalikan Buku</span>
                        </button>
                    </form>

                    {{-- Tombol Kirim Peringatan bila terlambat --}}
                    @if ($loan->isTerlambat())
                        <form action="{{ route('admin.peminjaman.kirim-peringatan', $loan->id_peminjaman) }}" method="POST">
                            @csrf
                            <button
                                type="submit"
                                class="h-10 inline-flex items-center gap-2 rounded-xl border border-amber-300 bg-amber-50 px-4 text-xs font-semibold text-amber-800 hover:bg-amber-100 transition-colors"
                            >
                                <i class="fa-solid fa-bell text-xs"></i>
                                <span>Kirim Peringatan</span>
                            </button>
                        </form>
                    @endif
                @endif
            </div>
        </div>

        {{-- Kartu Status Keterlambatan Khusus --}}
        @if ($loan->isTerlambat() || $loan->hari_keterlambatan > 0)
            <div class="rounded-2xl border border-rose-200 bg-rose-50/70 p-5 shadow-xs">
                <div class="flex items-start gap-3">
                    <span class="flex size-9 shrink-0 items-center justify-center rounded-xl bg-rose-100 text-rose-700">
                        <i class="fa-solid fa-triangle-exclamation text-base"></i>
                    </span>
                    <div class="space-y-1">
                        <h2 class="text-sm font-bold text-rose-900 sm:text-base">
                            Informasi Keterlambatan: {{ $loan->hari_keterlambatan > 0 ? $loan->hari_keterlambatan : $hariTerlambat }} Hari Melewati Batas Waktu
                        </h2>
                        <p class="text-xs text-rose-800">
                            Batas waktu pengembalian buku ini adalah <strong>{{ $loan->tanggal_jatuh_tempo->format('d F Y') }}</strong>. Sesuai kebijakan tata tertib, <strong>tidak diberlakukan sanksi denda finansial</strong> kepada mahasiswa, hanya metrik keterlambatan yang dicatat untuk evaluasi sirkulasi pustaka.
                        </p>
                        @if ($loan->notifikasi_peringatan_dikirim)
                            <p class="pt-1 text-[11px] font-semibold text-amber-800">
                                <i class="fa-solid fa-bell text-amber-600"></i>
                                Notifikasi peringatan telah dikirimkan pada: {{ $loan->tanggal_notifikasi ? $loan->tanggal_notifikasi->format('d M Y H:i') : '-' }} WIB.
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        {{-- Rincian 2 Kolom: Peminjam & Buku --}}
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            {{-- Data Peminjam --}}
            <div class="rounded-2xl border border-line bg-white p-5 shadow-xs sm:p-6 space-y-4">
                <div class="flex items-center gap-2.5 border-b border-line pb-3">
                    <span class="flex size-8 items-center justify-center rounded-lg bg-primary/10 text-primary">
                        <i class="fa-solid fa-user-graduate text-sm"></i>
                    </span>
                    <h2 class="text-sm font-bold text-ink">Identitas Mahasiswa / Pemustaka</h2>
                </div>

                <div class="space-y-3 text-xs">
                    <div>
                        <span class="text-muted">Nama Lengkap:</span>
                        <p class="font-bold text-ink text-sm">{{ $loan->pengguna->nama_lengkap }}</p>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <span class="text-muted">NIM / Nomor Identitas:</span>
                            <p class="font-mono font-semibold text-ink">{{ $loan->pengguna->nim ?? $loan->pengguna->nomor_identitas ?? '-' }}</p>
                        </div>
                        <div>
                            <span class="text-muted">Peran:</span>
                            <p class="font-semibold text-primary">{{ $loan->pengguna->peran?->nama_peran ?? 'Pemustaka' }}</p>
                        </div>
                    </div>
                    <div>
                        <span class="text-muted">Program Studi:</span>
                        <p class="font-semibold text-ink">{{ $loan->pengguna->prodi?->nama_prodi ?? 'Umum / Sivitas Akademika' }}</p>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <span class="text-muted">Email:</span>
                            <p class="truncate font-medium text-ink">{{ $loan->pengguna->email }}</p>
                        </div>
                        <div>
                            <span class="text-muted">Nomor WhatsApp:</span>
                            <p class="font-medium text-ink">{{ $loan->pengguna->no_wa ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Data Buku & Eksemplar --}}
            <div class="rounded-2xl border border-line bg-white p-5 shadow-xs sm:p-6 space-y-4">
                <div class="flex items-center gap-2.5 border-b border-line pb-3">
                    <span class="flex size-8 items-center justify-center rounded-lg bg-primary/10 text-primary">
                        <i class="fa-solid fa-book text-sm"></i>
                    </span>
                    <h2 class="text-sm font-bold text-ink">Informasi Koleksi &amp; Eksemplar</h2>
                </div>

                <div class="space-y-3 text-xs">
                    <div>
                        <span class="text-muted">Judul Koleksi Buku:</span>
                        <p class="font-bold text-ink text-sm">{{ $loan->buku->judul_buku }}</p>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <span class="text-muted">Kode Inventaris:</span>
                            <p class="font-mono font-semibold text-primary">{{ $loan->eksemplar->kode_inventaris }}</p>
                        </div>
                        <div>
                            <span class="text-muted">Status Eksemplar Saat Ini:</span>
                            <span class="inline-block mt-0.5 rounded px-2 py-0.5 text-[10px] font-bold {{ $loan->eksemplar->status === 'Tersedia' ? 'bg-emerald-100 text-emerald-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ $loan->eksemplar->status }}
                            </span>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <span class="text-muted">Nomor Panggil (Call No):</span>
                            <p class="font-medium text-ink">{{ $loan->eksemplar->nomor_panggil ?? '-' }}</p>
                        </div>
                        <div>
                            <span class="text-muted">Lokasi Rak:</span>
                            <p class="font-medium text-ink">{{ $loan->eksemplar->lokasi_rak ?? 'Lantai 1' }}</p>
                        </div>
                    </div>
                    <div>
                        <span class="text-muted">ISBN:</span>
                        <p class="font-mono text-ink">{{ $loan->buku->isbn ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Garis Waktu Sirkulasi --}}
        <div class="rounded-2xl border border-line bg-white p-5 shadow-xs sm:p-6">
            <h2 class="text-sm font-bold text-ink border-b border-line pb-3">Garis Waktu Transaksi Sirkulasi</h2>
            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-3 text-xs">
                <div class="rounded-xl bg-slate-50 p-4 border border-line">
                    <span class="text-muted">Tanggal Peminjaman</span>
                    <p class="mt-1 text-sm font-bold text-ink">{{ $loan->tanggal_pinjam->format('d F Y') }}</p>
                    <p class="mt-0.5 text-[11px] text-muted">Dicatat oleh sistem perpustakaan</p>
                </div>
                <div class="rounded-xl bg-slate-50 p-4 border border-line">
                    <span class="text-muted">Batas Pengembalian (Jatuh Tempo)</span>
                    <p class="mt-1 text-sm font-bold text-ink">{{ $loan->tanggal_jatuh_tempo->format('d F Y') }}</p>
                    <p class="mt-0.5 text-[11px] text-muted">Masa pinjam 14 hari</p>
                </div>
                <div class="rounded-xl bg-slate-50 p-4 border border-line">
                    <span class="text-muted">Tanggal Pengembalian Aktual</span>
                    @if ($loan->tanggal_kembali)
                        <p class="mt-1 text-sm font-bold text-emerald-700">{{ $loan->tanggal_kembali->format('d F Y') }}</p>
                        <p class="mt-0.5 text-[11px] text-muted">Buku telah diterima kembali</p>
                    @else
                        <p class="mt-1 text-sm font-bold text-blue-700">Belum Dikembalikan</p>
                        <p class="mt-0.5 text-[11px] text-muted">Menunggu pengembalian pemustaka</p>
                    @endif
                </div>
            </div>

            @if ($loan->catatan_keterlambatan)
                <div class="mt-4 rounded-xl bg-slate-50 p-3.5 text-xs text-ink-secondary border border-line">
                    <strong class="text-ink">Catatan Sirkulasi:</strong> {{ $loan->catatan_keterlambatan }}
                </div>
            @endif
        </div>
    </div>
@endsection
