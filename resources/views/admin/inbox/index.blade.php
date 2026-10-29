@extends('layouts.app')

@section('title', 'Inbox Validasi Peminjaman & Pengembalian Buku')
@section('description', 'Kotak masuk pengajuan peminjaman dan pengembalian buku oleh pemustaka via OPAC. Verifikasi permohonan peminjaman, atur prioritas antrean multi-user, dan cek fisik buku pengembalian.')

@section('content')
    <div class="mx-auto flex w-full max-w-[1570px] flex-1 flex-col gap-6 px-3 sm:px-4 lg:px-6 xl:px-8 py-4 sm:py-6 lg:py-8 responsive-desktop-container">
        {{-- Breadcrumb & Header --}}
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <nav aria-label="Breadcrumb" class="flex items-center gap-2 text-xs text-muted sm:text-sm">
                    <a href="{{ route('beranda') }}" class="hover:text-primary transition-colors">Beranda</a>
                    <svg class="size-3.5 text-muted/60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
                    <span class="text-ink-secondary">Panel Manajemen</span>
                    <svg class="size-3.5 text-muted/60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
                    <span class="font-medium text-ink">Inbox Pengembalian &amp; Peminjaman</span>
                </nav>
                <div class="mt-1 flex flex-wrap items-center gap-3">
                    <h1 class="text-2xl font-bold tracking-tight text-primary sm:text-3xl">
                        Inbox Validasi Sirkulasi Buku
                    </h1>
                    @if ($totalMenungguPinjam > 0)
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-500/15 px-3 py-1 text-xs font-bold text-primary border border-primary/30">
                            <span class="size-2 rounded-full bg-primary animate-ping"></span>
                            <span>{{ $totalMenungguPinjam }} Pengajuan Pinjam</span>
                        </span>
                    @endif
                    @if ($totalMenungguVerifikasi > 0)
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-500/15 px-3 py-1 text-xs font-bold text-amber-900 border border-amber-300">
                            <span class="size-2 rounded-full bg-amber-500"></span>
                            <span>{{ $totalMenungguVerifikasi }} Verifikasi Kembali</span>
                        </span>
                    @endif
                </div>
                <p class="mt-0.5 text-xs text-ink-secondary sm:text-sm max-w-3xl leading-relaxed">
                    Kelola dan verifikasi permohonan sirkulasi dari pemustaka via katalog OPAC. Admin berhak menentukan prioritas persetujuan saat beberapa pemustaka merequest buku yang sama, serta memeriksa fisik buku di meja sirkulasi.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2.5">
                {{-- Tombol Menuju Halaman Sirkulasi --}}
                <a
                    href="{{ route('admin.peminjaman.index') }}"
                    class="btn-press inline-flex h-10 items-center gap-2 rounded-xl border border-line bg-white px-3.5 text-xs font-semibold text-ink shadow-xs transition-all hover:bg-subtle sm:h-11 sm:text-sm"
                    title="Buka Sirkulasi Peminjaman Lengkap"
                >
                    <i class="fa-solid fa-arrows-rotate text-xs text-primary"></i>
                    <span>Semua Sirkulasi Peminjaman</span>
                </a>
            </div>
        </div>



        @if ($errors->any())
            <div class="rounded-2xl border border-rose-300 bg-rose-50/90 p-4 text-xs font-semibold text-rose-950 shadow-xs sm:text-sm animate-in fade-in duration-200">
                <div class="flex items-start gap-3">
                    <span class="flex size-8 shrink-0 items-center justify-center rounded-xl bg-rose-600 text-white shadow-xs">
                        <i class="fa-solid fa-triangle-exclamation text-sm"></i>
                    </span>
                    <div>
                        <p class="font-bold">Terjadi kendala dalam memproses permohonan:</p>
                        <ul class="mt-1 list-disc list-inside space-y-0.5 text-rose-800 text-xs">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        {{-- Kartu Ringkasan Status Inbox --}}
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4 lg:gap-4">
            {{-- 1. Pengajuan Peminjaman --}}
            <div class="rounded-2xl border {{ $totalMenungguPinjam > 0 ? 'border-primary/40 bg-blue-50/70 shadow-sm' : 'border-line bg-white' }} p-4 sm:p-5 transition-all">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-primary">Pengajuan Pinjam Baru</span>
                    <span class="flex size-9 items-center justify-center rounded-xl bg-primary/10 text-primary shadow-2xs">
                        <i class="fa-solid fa-hand-holding-hand text-sm"></i>
                    </span>
                </div>
                <p class="mt-2 text-2xl font-bold text-primary sm:text-3xl">{{ number_format($totalMenungguPinjam) }}</p>
                <p class="mt-1 text-[11px] text-ink-secondary">Admin alokasikan eksemplar fisik</p>
            </div>

            {{-- 2. Menunggu Verifikasi Pengembalian --}}
            <div class="rounded-2xl border {{ $totalMenungguVerifikasi > 0 ? 'border-amber-300 bg-amber-50/70 shadow-sm' : 'border-line bg-white' }} p-4 sm:p-5 transition-all">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-amber-900">Verifikasi Pengembalian</span>
                    <span class="flex size-9 items-center justify-center rounded-xl bg-amber-200/90 text-amber-900 shadow-2xs">
                        <i class="fa-solid fa-arrow-rotate-left text-sm"></i>
                    </span>
                </div>
                <p class="mt-2 text-2xl font-bold text-amber-950 sm:text-3xl">{{ number_format($totalMenungguVerifikasi) }}</p>
                <p class="mt-1 text-[11px] text-amber-800">Periksa fisik di meja sirkulasi</p>
            </div>

            {{-- 3. Selesai Diverifikasi --}}
            <div class="rounded-2xl border border-line bg-white p-4 sm:p-5">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-ink-secondary">Riwayat Selesai</span>
                    <span class="flex size-9 items-center justify-center rounded-xl bg-emerald-100 text-emerald-800 shadow-2xs">
                        <i class="fa-solid fa-check-double text-sm"></i>
                    </span>
                </div>
                <p class="mt-2 text-2xl font-bold text-ink sm:text-3xl">{{ number_format($totalRiwayatVerifikasi) }}</p>
                <p class="mt-1 text-[11px] text-muted">Total transaksi divalidasi</p>
            </div>

            {{-- 4. Panduan Prioritas Multi-User --}}
            <div class="rounded-2xl border border-line bg-slate-50/80 p-4 sm:p-5">
                <div class="flex items-center gap-2 text-xs font-semibold text-primary">
                    <i class="fa-solid fa-scale-balanced"></i>
                    <span>Hak Prioritas Admin</span>
                </div>
                <p class="mt-1.5 text-[11px] text-ink-secondary leading-relaxed">
                    Bila 2 user merequest buku yang sama, admin bebas memilih siapa yang didahulukan dengan mengklik <strong>Setujui</strong>.
                </p>
            </div>
        </div>

        {{-- Filter & Tab Navigasi Inbox --}}
        <div class="flex flex-col gap-3 rounded-2xl border border-line bg-white p-4 shadow-xs sm:p-5">
            <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                {{-- Tab Navigasi 3 Tab --}}
                <div class="flex flex-wrap items-center gap-1.5">
                    {{-- Tab 1: Pengajuan Peminjaman --}}
                    <a
                        href="{{ route('admin.inbox', array_filter(['tab' => 'pinjam', 'q' => $q])) }}"
                        class="inline-flex items-center gap-2 rounded-xl px-3.5 py-2 text-xs font-bold transition-all {{ $tab === 'pinjam' ? 'bg-primary text-white shadow-xs' : 'bg-slate-100 text-ink-secondary hover:text-ink' }}"
                    >
                        <i class="fa-solid fa-hand-holding-hand text-xs"></i>
                        <span>Pengajuan Peminjaman</span>
                        <span class="rounded-full {{ $tab === 'pinjam' ? 'bg-white/20 text-white' : 'bg-primary/10 text-primary' }} px-2 py-0.5 text-[10px]">
                            {{ $totalMenungguPinjam }}
                        </span>
                    </a>

                    {{-- Tab 2: Pengajuan Pengembalian --}}
                    <a
                        href="{{ route('admin.inbox', array_filter(['tab' => 'kembali', 'q' => $q])) }}"
                        class="inline-flex items-center gap-2 rounded-xl px-3.5 py-2 text-xs font-bold transition-all {{ $tab === 'kembali' ? 'bg-amber-600 text-white shadow-xs' : 'bg-slate-100 text-ink-secondary hover:text-ink' }}"
                    >
                        <i class="fa-solid fa-clock-rotate-left text-xs"></i>
                        <span>Pengajuan Pengembalian</span>
                        <span class="rounded-full {{ $tab === 'kembali' ? 'bg-white/20 text-white' : 'bg-amber-200 text-amber-900' }} px-2 py-0.5 text-[10px]">
                            {{ $totalMenungguVerifikasi }}
                        </span>
                    </a>

                    {{-- Tab 3: Riwayat Validasi --}}
                    <a
                        href="{{ route('admin.inbox', array_filter(['tab' => 'riwayat', 'q' => $q])) }}"
                        class="inline-flex items-center gap-2 rounded-xl px-3.5 py-2 text-xs font-bold transition-all {{ $tab === 'riwayat' ? 'bg-slate-800 text-white shadow-xs' : 'bg-slate-100 text-ink-secondary hover:text-ink' }}"
                    >
                        <i class="fa-solid fa-list-check text-xs"></i>
                        <span>Riwayat Validasi</span>
                        <span class="rounded-full {{ $tab === 'riwayat' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700' }} px-2 py-0.5 text-[10px]">
                            {{ $totalRiwayatVerifikasi }}
                        </span>
                    </a>
                </div>

                {{-- Form Pencarian --}}
                <form action="{{ route('admin.inbox') }}" method="GET" class="relative min-w-[280px] lg:w-80">
                    <input type="hidden" name="tab" value="{{ $tab }}">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-muted text-xs"></i>
                    <input
                        type="text"
                        name="q"
                        value="{{ $q }}"
                        placeholder="Cari peminjam, NIM, judul buku..."
                        class="h-9 w-full rounded-xl border border-line bg-slate-50/60 pl-9 pr-8 text-xs text-ink placeholder:text-muted focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20"
                    >
                    @if ($q !== '')
                        <a href="{{ route('admin.inbox', ['tab' => $tab]) }}" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-muted hover:text-ink text-xs" title="Reset pencarian">
                            <i class="fa-solid fa-xmark"></i>
                        </a>
                    @endif
                </form>
            </div>
        </div>

        {{-- Tabel Daftar Pengajuan --}}
        <div class="overflow-hidden rounded-2xl border border-line bg-white shadow-xs">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-ink">
                    <thead class="border-b border-line bg-slate-50/80 text-[11px] font-bold uppercase tracking-wider text-muted">
                        <tr>
                            <th scope="col" class="px-4 py-3.5 sm:px-6">Pemustaka / Pemohon</th>
                            <th scope="col" class="px-4 py-3.5">Buku &amp; Ketersediaan Stok</th>
                            <th scope="col" class="px-4 py-3.5">
                                {{ $tab === 'pinjam' ? 'Rencana Pinjam & Tempo' : 'Jadwal Pinjam & Tempo' }}
                            </th>
                            <th scope="col" class="px-4 py-3.5">
                                {{ $tab === 'pinjam' ? 'Waktu Pengajuan & Keperluan' : 'Pengajuan & Catatan' }}
                            </th>
                            <th scope="col" class="px-4 py-3.5">Status</th>
                            <th scope="col" class="px-4 py-3.5 sm:px-6 text-right">Aksi Validasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line/70">
                        @forelse ($requests as $item)
                            @php
                                $isCompeting = ($tab === 'pinjam') && (! empty($competingCounts[$item->id_buku]) && $competingCounts[$item->id_buku] > 1);
                                $availableCopies = $item->buku ? $item->buku->eksemplar->where('status', 'Tersedia') : collect();
                                $availCount = $availableCopies->count();
                                $isTerlambat = $item->isTerlambat();
                                $hariTerlambat = $item->hitungHariTerlambat();
                            @endphp
                            <tr class="transition-colors hover:bg-slate-50/70 {{ $isCompeting ? 'bg-rose-50/20' : ($item->status === 'Menunggu Persetujuan' ? 'bg-blue-50/25' : ($item->status === 'Menunggu Verifikasi' ? 'bg-amber-50/25' : '')) }}">
                                {{-- 1. Info Pemustaka --}}
                                <td class="px-4 py-3.5 sm:px-6">
                                    <div class="flex items-center gap-3">
                                        @if ($item->pengguna->foto_profil)
                                            <img src="{{ asset('storage/' . $item->pengguna->foto_profil) }}" alt="{{ $item->pengguna->nama_lengkap }}" class="size-9 rounded-full border border-line object-cover shrink-0">
                                        @else
                                            <div class="flex size-9 shrink-0 items-center justify-center rounded-full bg-primary/10 font-bold text-primary text-xs">
                                                {{ strtoupper(substr($item->pengguna->nama_lengkap, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div class="min-w-0">
                                            <p class="font-bold text-ink leading-tight truncate max-w-[180px] sm:max-w-[220px]">
                                                {{ $item->pengguna->nama_lengkap }}
                                            </p>
                                            <p class="text-[11px] text-muted truncate">
                                                {{ $item->pengguna->nim ?: ($item->pengguna->nidn ?: ($item->pengguna->nomor_identitas ?: '-')) }}
                                                &bull; {{ $item->pengguna->peran?->nama_peran ?: 'Mahasiswa' }}
                                            </p>
                                            <p class="text-[10px] text-slate-400 truncate">
                                                {{ $item->pengguna->prodi?->nama_prodi ?: 'MNC University' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                {{-- 2. Buku & Stok / Antrean Bersaing --}}
                                <td class="px-4 py-3.5">
                                    <div class="min-w-0 max-w-[280px]">
                                        <p class="font-bold text-ink leading-tight line-clamp-1" title="{{ $item->buku->judul_buku }}">
                                            {{ $item->buku->judul_buku }}
                                        </p>
                                        <p class="text-[11px] text-muted truncate mt-0.5">
                                            ISBN: {{ $item->buku->isbn ?: '-' }}
                                        </p>

                                        {{-- Indikator Persaingan Multi-User untuk Buku yang Sama --}}
                                        @if ($tab === 'pinjam')
                                            <div class="mt-1.5 flex flex-wrap items-center gap-1.5">
                                                @if ($isCompeting)
                                                    <span class="inline-flex items-center gap-1 rounded-full bg-rose-100 text-rose-800 border border-rose-300 px-2 py-0.5 text-[10px] font-bold" title="Terdapat beberapa user yang merequest buku ini">
                                                        <i class="fa-solid fa-users text-[9px]"></i>
                                                        <span>Antrean Bersaing ({{ $competingCounts[$item->id_buku] }} Pemohon)</span>
                                                    </span>
                                                @endif
                                                <span class="inline-flex items-center gap-1 rounded-md bg-slate-100 px-2 py-0.5 text-[10px] font-medium text-slate-700">
                                                    <span>Stok Tersedia:</span>
                                                    <strong class="{{ $availCount > 0 ? 'text-emerald-700' : 'text-rose-700' }}">{{ $availCount }} eks</strong>
                                                </span>
                                            </div>
                                        @else
                                            <div class="mt-1 flex items-center gap-1.5 text-[11px]">
                                                <span class="text-muted">No. Induk:</span>
                                                <code class="font-mono font-bold text-primary">{{ $item->eksemplar?->kode_inventaris ?: '-' }}</code>
                                            </div>
                                        @endif
                                    </div>
                                </td>

                                {{-- 3. Jadwal Pinjam & Batas Kembali --}}
                                <td class="px-4 py-3.5 whitespace-nowrap">
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-1.5 text-[11px]">
                                            <span class="text-muted">Pinjam:</span>
                                            <strong class="text-ink">{{ $item->tanggal_pinjam ? $item->tanggal_pinjam->format('d M Y') : '-' }}</strong>
                                            @if ($item->tanggal_pinjam && $item->tanggal_pinjam->isToday())
                                                <span class="rounded bg-emerald-100 px-1.5 py-0.2 text-[9px] font-bold text-emerald-800">Hari Ini</span>
                                            @elseif ($item->tanggal_pinjam && $item->tanggal_pinjam->isFuture())
                                                <span class="rounded bg-blue-100 px-1.5 py-0.2 text-[9px] font-bold text-primary">+{{ $item->tanggal_pinjam->diffInDays(now()) }} hari</span>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-1.5 text-[11px]">
                                            <span class="text-muted">Jatuh Tempo:</span>
                                            <strong class="{{ $isTerlambat ? 'text-rose-700 font-bold' : 'text-amber-900' }}">
                                                {{ $item->tanggal_jatuh_tempo ? $item->tanggal_jatuh_tempo->format('d M Y') : '-' }}
                                            </strong>
                                        </div>
                                        @if ($item->tanggal_kembali)
                                            <div class="flex items-center gap-1.5 text-[11px] text-emerald-700">
                                                <span class="text-muted">Kembali:</span>
                                                <strong>{{ $item->tanggal_kembali->format('d M Y') }}</strong>
                                            </div>
                                        @endif
                                    </div>
                                </td>

                                {{-- 4. Waktu Pengajuan & Catatan Pemohon --}}
                                <td class="px-4 py-3.5">
                                    <div class="min-w-0 max-w-[220px]">
                                        <div class="text-[11px] text-muted">
                                            <i class="fa-regular fa-clock text-[10px] mr-1"></i>
                                            @if ($tab === 'pinjam')
                                                <span>{{ $item->tanggal_pengajuan_pinjam ? $item->tanggal_pengajuan_pinjam->format('d M Y H:i') : ($item->created_at ? $item->created_at->format('d M Y H:i') : '-') }}</span>
                                            @else
                                                <span>{{ $item->tanggal_pengajuan_kembali ? $item->tanggal_pengajuan_kembali->diffForHumans() : '-' }}</span>
                                            @endif
                                        </div>
                                        @php
                                            $catatanText = ($tab === 'pinjam') ? $item->catatan_pengajuan_pinjam : $item->catatan_pengajuan_kembali;
                                        @endphp
                                        @if ($catatanText)
                                            <div class="mt-1 rounded-lg border border-line/80 bg-slate-50/80 p-2 text-[11px] text-ink-secondary italic line-clamp-2" title="{{ $catatanText }}">
                                                "{{ $catatanText }}"
                                            </div>
                                        @else
                                            <span class="text-[10px] text-muted italic">Tidak ada catatan</span>
                                        @endif
                                    </div>
                                </td>

                                {{-- 5. Status Transaksi --}}
                                <td class="px-4 py-3.5 whitespace-nowrap">
                                    @if ($item->status === 'Menunggu Persetujuan')
                                        <span class="inline-flex items-center gap-1.5 rounded-full border border-blue-300 bg-blue-100 px-2.5 py-1 text-[11px] font-bold text-primary shadow-2xs">
                                            <i class="fa-solid fa-hourglass-half text-[10px]"></i>
                                            <span>Menunggu Approval</span>
                                        </span>
                                    @elseif ($item->status === 'Menunggu Verifikasi')
                                        <span class="inline-flex items-center gap-1.5 rounded-full border border-amber-300 bg-amber-100 px-2.5 py-1 text-[11px] font-bold text-amber-900 shadow-2xs">
                                            <i class="fa-solid fa-clock-rotate-left text-[10px]"></i>
                                            <span>Menunggu Validasi</span>
                                        </span>
                                    @elseif ($item->status === 'Dipinjam')
                                        <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-300 bg-emerald-100 px-2.5 py-1 text-[11px] font-bold text-emerald-900">
                                            <i class="fa-solid fa-book-bookmark text-[10px]"></i>
                                            <span>Disetujui / Dipinjam</span>
                                        </span>
                                    @elseif ($item->status === 'Kembali')
                                        <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-300 bg-emerald-100 px-2.5 py-1 text-[11px] font-bold text-emerald-900 shadow-2xs">
                                            <i class="fa-solid fa-circle-check text-[10px]"></i>
                                            <span>Selesai Kembali</span>
                                        </span>
                                    @elseif ($item->status === 'Ditolak')
                                        <span class="inline-flex items-center gap-1.5 rounded-full border border-rose-300 bg-rose-100 px-2.5 py-1 text-[11px] font-bold text-rose-900" title="{{ $item->alasan_penolakan }}">
                                            <i class="fa-solid fa-ban text-[10px]"></i>
                                            <span>Ditolak Admin</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-slate-100 px-2.5 py-1 text-[11px] font-bold text-slate-700">
                                            <span>{{ $item->status }}</span>
                                        </span>
                                    @endif
                                </td>

                                {{-- 6. Aksi Validasi --}}
                                <td class="px-4 py-3.5 sm:px-6 text-right whitespace-nowrap">
                                    @if ($item->status === 'Menunggu Persetujuan')
                                        {{-- Aksi Pengajuan Peminjaman: Setujui Pinjam & Tolak Pinjam --}}
                                        <div class="inline-flex items-center gap-1.5">
                                            @php
                                                $exemplarsJson = $availableCopies->map(function ($ek) {
                                                    return [
                                                        'id' => $ek->id_eksemplar,
                                                        'kode' => $ek->kode_inventaris,
                                                        'lokasi' => $ek->lokasi_rak ?: 'Lantai 2',
                                                        'panggil' => $ek->nomor_panggil ?: '-'
                                                    ];
                                                })->values()->toJson();
                                            @endphp
                                            <button
                                                type="button"
                                                onclick="openApproveBorrowModal('{{ $item->id_peminjaman }}', '{{ addslashes($item->pengguna->nama_lengkap) }}', '{{ addslashes($item->buku->judul_buku) }}', '{{ $item->tanggal_pinjam ? $item->tanggal_pinjam->format('d M Y') : '-' }}', '{{ $item->tanggal_jatuh_tempo ? $item->tanggal_jatuh_tempo->format('d M Y') : '-' }}', {{ $exemplarsJson }})"
                                                class="btn-press inline-flex h-8 items-center gap-1.5 rounded-xl bg-primary px-3 text-xs font-semibold text-white shadow-xs hover:bg-primary-dark transition-colors focus:outline-none focus:ring-2 focus:ring-primary/20"
                                                title="Setujui permohonan dan alokasikan eksemplar fisik ke user ini"
                                            >
                                                <i class="fa-solid fa-check text-xs"></i>
                                                <span>Setujui</span>
                                            </button>

                                            <button
                                                type="button"
                                                onclick="openRejectBorrowModal('{{ $item->id_peminjaman }}', '{{ addslashes($item->pengguna->nama_lengkap) }}', '{{ addslashes($item->buku->judul_buku) }}')"
                                                class="btn-press inline-flex h-8 items-center gap-1.5 rounded-xl border border-rose-200 bg-white px-2.5 text-xs font-semibold text-rose-700 shadow-xs hover:bg-rose-50 transition-colors focus:outline-none focus:ring-2 focus:ring-rose-400"
                                                title="Tolak permohonan pinjam (misal stok dialokasikan untuk pemohon lain)"
                                            >
                                                <i class="fa-solid fa-xmark text-xs"></i>
                                                <span>Tolak</span>
                                            </button>
                                        </div>
                                    @elseif ($item->status === 'Menunggu Verifikasi')
                                        {{-- Aksi Pengajuan Pengembalian --}}
                                        <div class="inline-flex items-center gap-1.5">
                                            <button
                                                type="button"
                                                onclick="openApproveModal('{{ $item->id_peminjaman }}', '{{ addslashes($item->pengguna->nama_lengkap) }}', '{{ addslashes($item->buku->judul_buku) }}', '{{ $item->eksemplar?->kode_inventaris }}', {{ $item->hari_keterlambatan ?: 0 }})"
                                                class="btn-press inline-flex h-8 items-center gap-1.5 rounded-xl bg-emerald-600 px-3 text-xs font-semibold text-white shadow-xs hover:bg-emerald-700 transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                                title="Setujui dan validasi pengembalian fisik buku"
                                            >
                                                <i class="fa-solid fa-check text-xs"></i>
                                                <span>Setujui</span>
                                            </button>

                                            <button
                                                type="button"
                                                onclick="openRejectModal('{{ $item->id_peminjaman }}', '{{ addslashes($item->pengguna->nama_lengkap) }}', '{{ addslashes($item->buku->judul_buku) }}')"
                                                class="btn-press inline-flex h-8 items-center gap-1.5 rounded-xl border border-rose-200 bg-white px-2.5 text-xs font-semibold text-rose-700 shadow-xs hover:bg-rose-50 transition-colors focus:outline-none focus:ring-2 focus:ring-rose-400"
                                                title="Tolak pengajuan jika fisik buku belum diserahkan atau ada masalah"
                                            >
                                                <i class="fa-solid fa-xmark text-xs"></i>
                                                <span>Tolak</span>
                                            </button>
                                        </div>
                                    @else
                                        <a
                                            href="{{ route('admin.peminjaman.show', $item->id_peminjaman) }}"
                                            class="inline-flex h-8 items-center gap-1 rounded-lg border border-line bg-white px-2.5 text-xs font-medium text-ink-secondary hover:bg-subtle hover:text-ink transition-colors"
                                        >
                                            <i class="fa-regular fa-eye text-xs"></i>
                                            <span>Detail</span>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="mx-auto flex size-14 items-center justify-center rounded-2xl bg-slate-100 text-muted mb-3">
                                        <i class="fa-solid fa-inbox text-2xl"></i>
                                    </div>
                                    <p class="text-sm font-bold text-ink">
                                        {{ $tab === 'pinjam' ? 'Tidak Ada Pengajuan Peminjaman' : ($tab === 'kembali' ? 'Tidak Ada Pengajuan Pengembalian' : 'Belum Ada Riwayat Validasi') }}
                                    </p>
                                    <p class="mt-1 text-xs text-muted max-w-sm mx-auto">
                                        @if ($tab === 'pinjam')
                                            Saat ini belum ada permohonan peminjaman buku baru yang menunggu persetujuan admin.
                                        @elseif ($tab === 'kembali')
                                            Saat ini belum ada pemustaka yang mengajukan pengembalian buku mandiri via katalog OPAC.
                                        @else
                                            Belum ada catatan riwayat verifikasi peminjaman atau pengembalian buku.
                                        @endif
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination Links --}}
            @if ($requests->hasPages())
                <div class="border-t border-line bg-slate-50/50 px-4 py-3 sm:px-6">
                    {{ $requests->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- 1. MODAL PERSETUJUAN PENGAJUAN PINJAM (APPROVE BORROW & ALLOCATE COPY) --}}
    <div
        id="modal-approve-borrow"
        class="fixed inset-0 z-50 hidden flex items-center justify-center p-3 sm:p-4 bg-slate-950/60 backdrop-blur-sm overflow-y-auto"
        role="dialog"
        aria-modal="true"
        aria-labelledby="approve-borrow-modal-title"
    >
        <div class="relative w-full max-w-lg overflow-hidden rounded-2xl border border-line bg-white shadow-2xl transition-all my-auto animate-in fade-in zoom-in-95 duration-200">
            <div class="flex items-center justify-between border-b border-line bg-blue-50/80 px-5 py-4">
                <div class="flex items-center gap-3">
                    <div class="flex size-9 items-center justify-center rounded-xl bg-primary text-white shadow-xs">
                        <i class="fa-solid fa-check text-sm"></i>
                    </div>
                    <div>
                        <h3 id="approve-borrow-modal-title" class="text-sm font-bold text-primary">
                            Setujui Pengajuan Peminjaman Buku
                        </h3>
                        <p class="text-[11px] text-ink-secondary">
                            Pilih eksemplar fisik yang akan dialokasikan ke peminjam ini
                        </p>
                    </div>
                </div>
                <button type="button" onclick="closeApproveBorrowModal()" class="rounded-lg p-1.5 text-muted hover:bg-slate-200/60 hover:text-ink">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <form id="form-approve-borrow" method="POST" action="" class="p-5 sm:p-6 space-y-4">
                @csrf

                <div class="rounded-xl border border-line bg-slate-50/70 p-3.5 text-xs space-y-1.5">
                    <div>Pemohon: <strong id="approve-borrow-user-name" class="text-ink">-</strong></div>
                    <div>Judul Buku: <strong id="approve-borrow-book-title" class="text-ink">-</strong></div>
                    <div>Rencana Pinjam: <strong id="approve-borrow-date" class="text-primary">-</strong> &bull; Batas Tempo: <strong id="approve-borrow-due" class="text-amber-900">-</strong></div>
                </div>

                <div>
                    <label for="approve-borrow-exemplar-select" class="block text-xs font-semibold text-ink mb-1">
                        Pilih Eksemplar Fisik yang Dialokasikan <span class="text-rose-600">*</span>
                    </label>
                    <select
                        id="approve-borrow-exemplar-select"
                        name="id_eksemplar"
                        required
                        class="w-full rounded-xl border border-line bg-white px-3 py-2 text-xs text-ink shadow-2xs focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20"
                    >
                        {{-- Diisi secara dinamis via JS --}}
                    </select>
                    <div id="approve-borrow-no-copy-warning" class="hidden mt-1.5 rounded-lg border border-rose-200 bg-rose-50 p-2.5 text-xs text-rose-800">
                        <i class="fa-solid fa-triangle-exclamation mr-1"></i>
                        <span>Saat ini tidak ada eksemplar berstatus Tersedia untuk buku ini. Anda dapat menolak permohonan atau menunggu hingga ada buku yang dikembalikan.</span>
                    </div>
                </div>

                <div>
                    <label for="approve-borrow-catatan" class="block text-xs font-semibold text-ink mb-1">
                        Catatan Persetujuan <span class="text-muted font-normal">(Opsional)</span>
                    </label>
                    <textarea
                        id="approve-borrow-catatan"
                        name="catatan"
                        rows="2"
                        placeholder="Contoh: Disetujui petugas. Silakan ambil buku fisik di meja sirkulasi Lantai 2..."
                        class="w-full rounded-xl border border-line bg-slate-50/50 p-2.5 text-xs text-ink placeholder:text-muted focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20"
                    ></textarea>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2 border-t border-line">
                    <button
                        type="button"
                        onclick="closeApproveBorrowModal()"
                        class="h-9 rounded-xl border border-line bg-white px-4 text-xs font-semibold text-ink hover:bg-subtle transition-colors"
                    >
                        Batal
                    </button>
                    <button
                        type="submit"
                        id="btn-submit-approve-borrow"
                        class="btn-press inline-flex h-9 items-center gap-1.5 rounded-xl bg-primary px-5 text-xs font-semibold text-white shadow-xs hover:bg-primary-dark transition-colors"
                    >
                        <i class="fa-solid fa-check text-xs"></i>
                        <span>Ya, Setujui &amp; Alokasikan Buku</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- 2. MODAL PENOLAKAN PENGAJUAN PINJAM --}}
    <div
        id="modal-reject-borrow"
        class="fixed inset-0 z-50 hidden flex items-center justify-center p-3 sm:p-4 bg-slate-950/60 backdrop-blur-sm overflow-y-auto"
        role="dialog"
        aria-modal="true"
        aria-labelledby="reject-borrow-modal-title"
    >
        <div class="relative w-full max-w-lg overflow-hidden rounded-2xl border border-line bg-white shadow-2xl transition-all my-auto animate-in fade-in zoom-in-95 duration-200">
            <div class="flex items-center justify-between border-b border-line bg-rose-50/80 px-5 py-4">
                <div class="flex items-center gap-3">
                    <div class="flex size-9 items-center justify-center rounded-xl bg-rose-600 text-white shadow-xs">
                        <i class="fa-solid fa-ban text-sm"></i>
                    </div>
                    <div>
                        <h3 id="reject-borrow-modal-title" class="text-sm font-bold text-rose-950">
                            Tolak Pengajuan Peminjaman Buku
                        </h3>
                        <p class="text-[11px] text-rose-800">
                            Status pengajuan akan diubah menjadi Ditolak
                        </p>
                    </div>
                </div>
                <button type="button" onclick="closeRejectBorrowModal()" class="rounded-lg p-1.5 text-muted hover:bg-slate-200/60 hover:text-ink">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <form id="form-reject-borrow" method="POST" action="" class="p-5 sm:p-6 space-y-4">
                @csrf

                <div class="rounded-xl border border-line bg-slate-50/70 p-3.5 text-xs space-y-1">
                    <div>Pemohon: <strong id="reject-borrow-user-name" class="text-ink">-</strong></div>
                    <div>Judul Buku: <strong id="reject-borrow-book-title" class="text-ink">-</strong></div>
                </div>

                <div>
                    <label for="reject-borrow-alasan" class="block text-xs font-semibold text-ink mb-1">
                        Alasan Penolakan <span class="text-rose-600">*</span>
                    </label>
                    <textarea
                        id="reject-borrow-alasan"
                        name="alasan"
                        rows="3"
                        required
                        placeholder="Contoh: Stok eksemplar dialokasikan untuk pemohon lain yang lebih dahulu mengajukan, atau buku sedang dalam proses pemeliharaan..."
                        class="w-full rounded-xl border border-line bg-slate-50/50 p-2.5 text-xs text-ink placeholder:text-muted focus:border-rose-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-rose-500/20"
                    ></textarea>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2 border-t border-line">
                    <button
                        type="button"
                        onclick="closeRejectBorrowModal()"
                        class="h-9 rounded-xl border border-line bg-white px-4 text-xs font-semibold text-ink hover:bg-subtle transition-colors"
                    >
                        Batal
                    </button>
                    <button
                        type="submit"
                        class="btn-press inline-flex h-9 items-center gap-1.5 rounded-xl bg-rose-600 px-5 text-xs font-semibold text-white shadow-xs hover:bg-rose-700 transition-colors"
                    >
                        <i class="fa-solid fa-ban text-xs"></i>
                        <span>Tolak Pengajuan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- 3. MODAL PERSETUJUAN PENGEMBALIAN BUKU --}}
    <div
        id="modal-approve-return"
        class="fixed inset-0 z-50 hidden flex items-center justify-center p-3 sm:p-4 bg-slate-950/60 backdrop-blur-sm overflow-y-auto"
        role="dialog"
        aria-modal="true"
        aria-labelledby="approve-modal-title"
    >
        <div class="relative w-full max-w-lg overflow-hidden rounded-2xl border border-line bg-white shadow-2xl transition-all my-auto animate-in fade-in zoom-in-95 duration-200">
            <div class="flex items-center justify-between border-b border-line bg-emerald-50/80 px-5 py-4">
                <div class="flex items-center gap-3">
                    <div class="flex size-9 items-center justify-center rounded-xl bg-emerald-600 text-white shadow-xs">
                        <i class="fa-solid fa-check text-sm"></i>
                    </div>
                    <div>
                        <h3 id="approve-modal-title" class="text-sm font-bold text-emerald-950">
                            Validasi &amp; Setujui Pengembalian Buku
                        </h3>
                        <p class="text-[11px] text-emerald-800">
                            Stok eksemplar akan bertambah menjadi Tersedia di rak
                        </p>
                    </div>
                </div>
                <button type="button" onclick="closeApproveModal()" class="rounded-lg p-1.5 text-muted hover:bg-slate-200/60 hover:text-ink">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <form id="form-approve-return" method="POST" action="" class="p-5 sm:p-6 space-y-4">
                @csrf
                <input type="hidden" name="redirect_to" value="inbox">

                <div class="rounded-xl border border-line bg-slate-50/70 p-3.5 text-xs space-y-1.5">
                    <div>Peminjam: <strong id="approve-user-name" class="text-ink">-</strong></div>
                    <div>Judul Buku: <strong id="approve-book-title" class="text-ink">-</strong></div>
                    <div>No. Induk: <code id="approve-kode-inv" class="font-mono font-bold text-primary">-</code></div>
                    <div id="approve-late-warning" class="hidden text-rose-700 font-semibold pt-1">
                        <i class="fa-solid fa-triangle-exclamation mr-1"></i>
                        <span>Tercatat keterlambatan sebanyak <span id="approve-late-days">0</span> hari.</span>
                    </div>
                </div>

                <div>
                    <label for="approve-catatan" class="block text-xs font-semibold text-ink mb-1">
                        Catatan Pemeriksaan Fisik Buku <span class="text-muted font-normal">(Opsional)</span>
                    </label>
                    <textarea
                        id="approve-catatan"
                        name="catatan"
                        rows="2"
                        placeholder="Contoh: Fisik buku diterima dalam kondisi baik, bersih, dan lengkap..."
                        class="w-full rounded-xl border border-line bg-slate-50/50 p-2.5 text-xs text-ink placeholder:text-muted focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20"
                    ></textarea>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2 border-t border-line">
                    <button
                        type="button"
                        onclick="closeApproveModal()"
                        class="h-9 rounded-xl border border-line bg-white px-4 text-xs font-semibold text-ink hover:bg-subtle transition-colors"
                    >
                        Batal
                    </button>
                    <button
                        type="submit"
                        class="btn-press inline-flex h-9 items-center gap-1.5 rounded-xl bg-emerald-600 px-5 text-xs font-semibold text-white shadow-xs hover:bg-emerald-700 transition-colors"
                    >
                        <i class="fa-solid fa-check text-xs"></i>
                        <span>Ya, Setujui &amp; Kembalikan Stok</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- 4. MODAL PENOLAKAN PENGAJUAN PENGEMBALIAN --}}
    <div
        id="modal-reject-return"
        class="fixed inset-0 z-50 hidden flex items-center justify-center p-3 sm:p-4 bg-slate-950/60 backdrop-blur-sm overflow-y-auto"
        role="dialog"
        aria-modal="true"
        aria-labelledby="reject-modal-title"
    >
        <div class="relative w-full max-w-lg overflow-hidden rounded-2xl border border-line bg-white shadow-2xl transition-all my-auto animate-in fade-in zoom-in-95 duration-200">
            <div class="flex items-center justify-between border-b border-line bg-rose-50/80 px-5 py-4">
                <div class="flex items-center gap-3">
                    <div class="flex size-9 items-center justify-center rounded-xl bg-rose-600 text-white shadow-xs">
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </div>
                    <div>
                        <h3 id="reject-modal-title" class="text-sm font-bold text-rose-950">
                            Tolak Pengajuan Pengembalian
                        </h3>
                        <p class="text-[11px] text-rose-800">
                            Status transaksi akan dikembalikan menjadi Dipinjam / Terlambat
                        </p>
                    </div>
                </div>
                <button type="button" onclick="closeRejectModal()" class="rounded-lg p-1.5 text-muted hover:bg-slate-200/60 hover:text-ink">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <form id="form-reject-return" method="POST" action="" class="p-5 sm:p-6 space-y-4">
                @csrf
                <input type="hidden" name="redirect_to" value="inbox">

                <div class="rounded-xl border border-line bg-slate-50/70 p-3.5 text-xs space-y-1">
                    <div>Peminjam: <strong id="reject-user-name" class="text-ink">-</strong></div>
                    <div>Judul Buku: <strong id="reject-book-title" class="text-ink">-</strong></div>
                </div>

                <div>
                    <label for="reject-alasan" class="block text-xs font-semibold text-ink mb-1">
                        Alasan Penolakan <span class="text-rose-600">*</span>
                    </label>
                    <textarea
                        id="reject-alasan"
                        name="alasan"
                        rows="3"
                        required
                        placeholder="Contoh: Buku fisik belum diserahkan ke meja sirkulasi perpustakaan atau terdapat lembaran yang rusak/hilang..."
                        class="w-full rounded-xl border border-line bg-slate-50/50 p-2.5 text-xs text-ink placeholder:text-muted focus:border-rose-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-rose-500/20"
                    ></textarea>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2 border-t border-line">
                    <button
                        type="button"
                        onclick="closeRejectModal()"
                        class="h-9 rounded-xl border border-line bg-white px-4 text-xs font-semibold text-ink hover:bg-subtle transition-colors"
                    >
                        Batal
                    </button>
                    <button
                        type="submit"
                        class="btn-press inline-flex h-9 items-center gap-1.5 rounded-xl bg-rose-600 px-5 text-xs font-semibold text-white shadow-xs hover:bg-rose-700 transition-colors"
                    >
                        <i class="fa-solid fa-ban text-xs"></i>
                        <span>Tolak Pengajuan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Modal Approve Borrow
        function openApproveBorrowModal(id, userName, bookTitle, pickupDate, dueDate, exemplars) {
            const form = document.getElementById('form-approve-borrow');
            form.action = '{{ url('admin/peminjaman') }}/' + id + '/setujui-pinjam';
            document.getElementById('approve-borrow-user-name').textContent = userName;
            document.getElementById('approve-borrow-book-title').textContent = bookTitle;
            document.getElementById('approve-borrow-date').textContent = pickupDate;
            document.getElementById('approve-borrow-due').textContent = dueDate;

            const select = document.getElementById('approve-borrow-exemplar-select');
            select.innerHTML = '';
            const warningEl = document.getElementById('approve-borrow-no-copy-warning');
            const submitBtn = document.getElementById('btn-submit-approve-borrow');

            if (exemplars && exemplars.length > 0) {
                warningEl.classList.add('hidden');
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');

                exemplars.forEach(function (ek) {
                    const opt = document.createElement('option');
                    opt.value = ek.id;
                    opt.textContent = 'No. Induk: ' + ek.kode + ' (Rak: ' + ek.lokasi + ') - ' + ek.panggil;
                    select.appendChild(opt);
                });
            } else {
                warningEl.classList.remove('hidden');
                const opt = document.createElement('option');
                opt.value = '';
                opt.textContent = '-- Tidak Ada Eksemplar Tersedia --';
                select.appendChild(opt);
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
            }

            document.getElementById('modal-approve-borrow').classList.remove('hidden');
        }

        function closeApproveBorrowModal() {
            document.getElementById('modal-approve-borrow').classList.add('hidden');
        }

        // Modal Reject Borrow
        function openRejectBorrowModal(id, userName, bookTitle) {
            const form = document.getElementById('form-reject-borrow');
            form.action = '{{ url('admin/peminjaman') }}/' + id + '/tolak-pinjam';
            document.getElementById('reject-borrow-user-name').textContent = userName;
            document.getElementById('reject-borrow-book-title').textContent = bookTitle;
            document.getElementById('modal-reject-borrow').classList.remove('hidden');
        }

        function closeRejectBorrowModal() {
            document.getElementById('modal-reject-borrow').classList.add('hidden');
        }

        // Modal Approve Return
        function openApproveModal(id, userName, bookTitle, kodeInv, lateDays) {
            const form = document.getElementById('form-approve-return');
            form.action = '{{ url('admin/peminjaman') }}/' + id + '/kembalikan';
            document.getElementById('approve-user-name').textContent = userName;
            document.getElementById('approve-book-title').textContent = bookTitle;
            document.getElementById('approve-kode-inv').textContent = kodeInv || '-';

            const lateBox = document.getElementById('approve-late-warning');
            const lateDaysEl = document.getElementById('approve-late-days');
            if (lateDays > 0) {
                lateDaysEl.textContent = lateDays;
                lateBox.classList.remove('hidden');
            } else {
                lateBox.classList.add('hidden');
            }

            document.getElementById('modal-approve-return').classList.remove('hidden');
        }

        function closeApproveModal() {
            document.getElementById('modal-approve-return').classList.add('hidden');
        }

        // Modal Reject Return
        function openRejectModal(id, userName, bookTitle) {
            const form = document.getElementById('form-reject-return');
            form.action = '{{ url('admin/peminjaman') }}/' + id + '/tolak-pengembalian';
            document.getElementById('reject-user-name').textContent = userName;
            document.getElementById('reject-book-title').textContent = bookTitle;
            document.getElementById('modal-reject-return').classList.remove('hidden');
        }

        function closeRejectModal() {
            document.getElementById('modal-reject-return').classList.add('hidden');
        }

        // Close on ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeApproveBorrowModal();
                closeRejectBorrowModal();
                closeApproveModal();
                closeRejectModal();
            }
        });
    </script>
@endsection
