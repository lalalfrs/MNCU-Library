@extends('layouts.app')

@section('title', 'Manajemen Peminjaman & Keterlambatan')
@section('description', 'Kelola sirkulasi peminjaman buku perpustakaan MNC University, validasi eligibilitas pemustaka, pantau keterlambatan pengembalian tanpa sanksi denda, dan integrasi API Sanctum.')

@section('content')
    <div class="mx-auto flex w-full max-w-[1570px] flex-1 flex-col gap-6 px-3 py-6 lg:px-6 lg:py-8">
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
                    Kelola data transaksi sirkulasi, validasi eligibilitas mahasiswa, visualisasi durasi keterlambatan tanpa sanksi finansial, serta integrasi validasi Sanctum.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2.5">
                {{-- Tombol Informasi Sanctum API --}}
                <button
                    type="button"
                    onclick="openSanctumModal()"
                    class="inline-flex h-10 items-center gap-2 rounded-xl border border-primary/20 bg-primary/5 px-3.5 text-xs font-semibold text-primary shadow-xs transition-all hover:bg-primary/10 sm:h-11 sm:text-sm"
                    title="Informasi Validasi API Sanctum"
                >
                    <i class="fa-solid fa-key text-xs"></i>
                    <span>API Sanctum</span>
                </button>

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
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-4 lg:gap-4">
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
                <p class="mt-1 text-[11px] text-blue-700">Buku masih di tangan pemustaka</p>
            </div>

            {{-- 3. Terlambat Pengembalian --}}
            <div class="rounded-2xl border border-rose-200 bg-rose-50/50 p-4 shadow-xs sm:p-5">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-medium text-rose-700">Keterlambatan</span>
                    <span class="flex size-8 items-center justify-center rounded-xl bg-rose-100 text-rose-700">
                        <i class="fa-solid fa-triangle-exclamation text-sm"></i>
                    </span>
                </div>
                <p class="mt-2 text-2xl font-bold text-rose-900 sm:text-3xl">{{ number_format($totalTerlambat) }}</p>
                <p class="mt-1 text-[11px] text-rose-700">Lewat jatuh tempo (tanpa sanksi)</p>
            </div>

            {{-- 4. Selesai Dikembalikan --}}
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50/50 p-4 shadow-xs sm:p-5">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-medium text-emerald-700">Selesai Kembali</span>
                    <span class="flex size-8 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">
                        <i class="fa-solid fa-circle-check text-sm"></i>
                    </span>
                </div>
                <p class="mt-2 text-2xl font-bold text-emerald-900 sm:text-3xl">{{ number_format($totalKembali) }}</p>
                <p class="mt-1 text-[11px] text-emerald-700">Stok eksemplar telah kembali</p>
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
                                    @elseif ($isLate)
                                        <div class="flex flex-col items-start gap-1">
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-rose-100 px-2.5 py-0.5 text-[11px] font-bold text-rose-800 animate-pulse">
                                                <i class="fa-solid fa-triangle-exclamation text-[10px]"></i>
                                                Terlambat {{ $hariTerlambat }} Hari
                                            </span>
                                            <span class="text-[10px] text-muted">
                                                Tanpa sanksi denda
                                            </span>
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
                                            {{-- Tombol Kirim Peringatan Keterlambatan jika Terlambat --}}
                                            @if ($isLate)
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

    {{-- Modal Informasi API Sanctum --}}
    <div id="modal-sanctum" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/50 p-4 backdrop-blur-xs flex items-center justify-center">
        <div class="relative w-full max-w-2xl rounded-2xl border border-line bg-white p-6 shadow-2xl">
            <div class="flex items-center justify-between border-b border-line pb-3">
                <div class="flex items-center gap-2">
                    <span class="flex size-8 items-center justify-center rounded-lg bg-primary/10 text-primary">
                        <i class="fa-solid fa-shield-halved text-sm"></i>
                    </span>
                    <div>
                        <h3 class="text-sm font-bold text-ink">Integrasi Validasi API Sanctum</h3>
                        <p class="text-[11px] text-muted">Layanan API Sirkulasi Perpustakaan MNC University</p>
                    </div>
                </div>
                <button type="button" onclick="closeSanctumModal()" class="text-muted hover:text-ink">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="mt-4 space-y-4 text-xs text-ink-secondary max-h-[70vh] overflow-y-auto pr-1">
                <p>
                    Seluruh validasi sirkulasi dan aturan eligibilitas peminjaman dilindungi menggunakan <strong>Laravel Sanctum</strong> (<code class="bg-slate-100 px-1 py-0.5 rounded text-primary">auth:sanctum</code>).
                </p>

                <div class="rounded-xl border border-line bg-slate-50 p-3.5 space-y-2">
                    <p class="font-bold text-ink text-xs uppercase tracking-wide">Daftar Endpoint API Sanctum:</p>
                    <div class="space-y-1.5 font-mono text-[11px]">
                        <div class="flex items-center gap-2">
                            <span class="rounded bg-blue-100 px-1.5 py-0.5 text-[10px] font-bold text-blue-800">GET</span>
                            <span>/api/peminjaman/cek-eligibilitas/{id_pengguna}</span>
                        </div>
                        <p class="text-[10px] text-muted pl-12 font-sans">Validasi apakah mahasiswa memiliki tanggungan buku yang belum dikembalikan.</p>

                        <div class="flex items-center gap-2 pt-1">
                            <span class="rounded bg-blue-100 px-1.5 py-0.5 text-[10px] font-bold text-blue-800">GET</span>
                            <span>/api/peminjaman/eksemplar-tersedia/{id_buku}</span>
                        </div>
                        <p class="text-[10px] text-muted pl-12 font-sans">Daftar eksemplar fisik buku yang berstatus 'Tersedia' untuk dipinjam.</p>

                        <div class="flex items-center gap-2 pt-1">
                            <span class="rounded bg-emerald-100 px-1.5 py-0.5 text-[10px] font-bold text-emerald-800">POST</span>
                            <span>/api/peminjaman</span>
                        </div>
                        <p class="text-[10px] text-muted pl-12 font-sans">Catat peminjaman baru (mengurangi stok eksemplar menjadi Dipinjam).</p>

                        <div class="flex items-center gap-2 pt-1">
                            <span class="rounded bg-emerald-100 px-1.5 py-0.5 text-[10px] font-bold text-emerald-800">POST</span>
                            <span>/api/peminjaman/{id}/kembalikan</span>
                        </div>
                        <p class="text-[10px] text-muted pl-12 font-sans">Proses pengembalian buku (menambah stok eksemplar kembali Tersedia &amp; catat keterlambatan).</p>

                        <div class="flex items-center gap-2 pt-1">
                            <span class="rounded bg-amber-100 px-1.5 py-0.5 text-[10px] font-bold text-amber-800">POST</span>
                            <span>/api/peminjaman/{id}/kirim-peringatan</span>
                        </div>
                        <p class="text-[10px] text-muted pl-12 font-sans">Kirim notifikasi peringatan lewat batas waktu ke mahasiswa.</p>
                    </div>
                </div>

                <div class="rounded-xl border border-primary/20 bg-primary/5 p-3.5">
                    <p class="font-bold text-primary">Cara Otentikasi:</p>
                    <p class="mt-1 text-[11px] text-ink-secondary">
                        Sertakan header <code class="font-mono bg-white px-1.5 py-0.5 rounded border border-line">Authorization: Bearer &lt;token&gt;</code> pada setiap HTTP request ke endpoint di atas.
                    </p>
                </div>
            </div>

            <div class="flex items-center justify-end pt-4 border-t border-line mt-4">
                <button
                    type="button"
                    onclick="closeSanctumModal()"
                    class="h-9 rounded-xl bg-primary px-4 text-xs font-semibold text-white hover:bg-primary-dark"
                >
                    Tutup
                </button>
            </div>
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

        function openSanctumModal() {
            document.getElementById('modal-sanctum').classList.remove('hidden');
        }

        function closeSanctumModal() {
            document.getElementById('modal-sanctum').classList.add('hidden');
        }
    </script>
@endsection
