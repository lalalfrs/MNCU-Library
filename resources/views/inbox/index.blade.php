@extends('layouts.app')

@section('title', 'Inbox & Notifikasi Peminjaman')
@section('description', 'Kotak masuk notifikasi pengingat H-1 (24 jam sebelum batas waktu pengembalian) dan status peminjaman buku pemustaka Perpustakaan MNC University.')

@section('content')
    @php
        $alertCount = $deadlineAlerts->count();
        $activeCount = $activeLoans->count();
        $pendingCount = $pendingRequests->count();
        $historyCount = $historyLoans->count();
        $remainingQuota = max(0, $maxLoans - $activeCount);
    @endphp

    <div class="mx-auto flex w-full max-w-[1380px] flex-1 flex-col gap-6 px-3 py-6 sm:px-4 lg:px-6 lg:py-8">
        {{-- Breadcrumb & Header --}}
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <nav aria-label="Breadcrumb" class="flex items-center gap-2 text-xs text-muted sm:text-sm">
                    <a href="{{ route('beranda') }}" class="hover:text-primary transition-colors">Beranda</a>
                    <svg class="size-3.5 text-muted/60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
                    <span class="font-medium text-ink">Inbox &amp; Notifikasi Peminjaman</span>
                </nav>
                <div class="mt-1 flex flex-wrap items-center gap-2.5">
                    <h1 class="text-2xl font-bold tracking-tight text-primary sm:text-3xl">
                        Inbox &amp; Notifikasi Peminjaman
                    </h1>
                    @if ($alertCount > 0)
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-rose-100 px-3 py-1 text-xs font-bold text-rose-800 border border-rose-300">
                            <span class="size-2 rounded-full bg-rose-600 animate-ping"></span>
                            <span>{{ $alertCount }} Peringatan Deadline</span>
                        </span>
                    @endif
                </div>
                <p class="mt-0.5 text-xs text-ink-secondary sm:text-sm max-w-3xl leading-relaxed">
                    Pantau pengingat otomatis <strong>H-1 (24 jam sebelum batas pengembalian)</strong>, daftar buku yang sedang Anda pinjam (maksimal {{ $maxLoans }} buku), serta status pengajuan sirkulasi Anda.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2.5">
                @if ($user->isSuperAdmin() || $user->isPustakawan())
                    <a
                        href="{{ route('admin.inbox') }}"
                        class="btn-press inline-flex h-10 items-center gap-2 rounded-xl border border-amber-300 bg-amber-50 px-3.5 text-xs font-semibold text-amber-900 shadow-xs transition-all hover:bg-amber-100 sm:h-11 sm:text-sm"
                    >
                        <i class="fa-solid fa-sliders text-xs text-amber-700"></i>
                        <span>Inbox Validasi Admin</span>
                    </a>
                @endif
                <a
                    href="{{ route('opac') }}"
                    class="btn-press inline-flex h-10 items-center gap-2 rounded-xl bg-primary px-4 text-xs font-semibold text-white shadow-xs transition-all hover:bg-primary-dark sm:h-11 sm:text-sm"
                >
                    <i class="fa-solid fa-book-open text-xs"></i>
                    <span>Jelajahi Katalog OPAC</span>
                </a>
            </div>
        </div>

        {{-- 4 Kartu Ringkasan Kuota & Notifikasi User --}}
        <div class="grid grid-cols-2 gap-3 lg:grid-cols-4 lg:gap-4">
            {{-- 1. Notifikasi H-1 & Lewat Tempo --}}
            <div class="rounded-2xl border {{ $alertCount > 0 ? 'border-rose-300 bg-rose-50/70 shadow-sm' : 'border-line bg-white' }} p-4 sm:p-5">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold {{ $alertCount > 0 ? 'text-rose-900' : 'text-ink-secondary' }}">Notifikasi H-1 / Deadline</span>
                    <span class="flex size-9 items-center justify-center rounded-xl {{ $alertCount > 0 ? 'bg-rose-600 text-white' : 'bg-slate-100 text-slate-600' }}">
                        <i class="fa-solid fa-bell text-sm"></i>
                    </span>
                </div>
                <p class="mt-2 text-2xl font-bold {{ $alertCount > 0 ? 'text-rose-700' : 'text-ink' }} sm:text-3xl">{{ $alertCount }}</p>
                <p class="mt-1 text-[11px] {{ $alertCount > 0 ? 'text-rose-800 font-medium' : 'text-muted' }}">Pengingat &le; 24 jam sebelum tempo</p>
            </div>

            {{-- 2. Kuota Pinjaman Aktif (Maksimal 3 Buku) --}}
            <div class="rounded-2xl border border-line bg-white p-4 sm:p-5">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-primary">Kuota Pinjaman Aktif</span>
                    <span class="flex size-9 items-center justify-center rounded-xl bg-primary/10 text-primary">
                        <i class="fa-solid fa-book-bookmark text-sm"></i>
                    </span>
                </div>
                <p class="mt-2 text-2xl font-bold text-ink sm:text-3xl">
                    {{ $activeCount }} <span class="text-base font-semibold text-muted">/ {{ $maxLoans }} Buku</span>
                </p>
                <p class="mt-1 text-[11px] {{ $remainingQuota > 0 ? 'text-emerald-700 font-medium' : 'text-amber-700 font-medium' }}">
                    {{ $remainingQuota > 0 ? "Sisa kuota pinjam: {$remainingQuota} buku" : 'Kuota maksimal 3 buku terpakai' }}
                </p>
            </div>

            {{-- 3. Menunggu Persetujuan --}}
            <div class="rounded-2xl border border-line bg-white p-4 sm:p-5">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-amber-900">Menunggu Persetujuan</span>
                    <span class="flex size-9 items-center justify-center rounded-xl bg-amber-100 text-amber-800">
                        <i class="fa-solid fa-hourglass-half text-sm"></i>
                    </span>
                </div>
                <p class="mt-2 text-2xl font-bold text-ink sm:text-3xl">{{ $pendingCount }}</p>
                <p class="mt-1 text-[11px] text-muted">Pengajuan pinjam via OPAC</p>
            </div>

            {{-- 4. Riwayat Selesai --}}
            <div class="rounded-2xl border border-line bg-white p-4 sm:p-5">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-emerald-800">Riwayat Sirkulasi</span>
                    <span class="flex size-9 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">
                        <i class="fa-solid fa-check-double text-sm"></i>
                    </span>
                </div>
                <p class="mt-2 text-2xl font-bold text-ink sm:text-3xl">{{ $historyCount }}</p>
                <p class="mt-1 text-[11px] text-muted">Buku telah dikembalikan</p>
            </div>
        </div>

        {{-- Bagian Utama 1: Kotak Masuk Notifikasi Peringatan H-1 (24 Jam Sebelum Deadline) & Keterlambatan --}}
        <section aria-labelledby="heading-notifikasi-deadline" class="rounded-2xl border {{ $alertCount > 0 ? 'border-amber-300 bg-amber-50/40' : 'border-line bg-white' }} p-5 shadow-xs sm:p-6">
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-line pb-4">
                <div class="flex items-center gap-3">
                    <span class="flex size-10 shrink-0 items-center justify-center rounded-xl {{ $alertCount > 0 ? 'bg-amber-500 text-white' : 'bg-primary/10 text-primary' }}">
                        <i class="fa-solid fa-bell text-base"></i>
                    </span>
                    <div>
                        <h2 id="heading-notifikasi-deadline" class="text-base font-bold text-ink sm:text-lg">
                            Pesan Masuk: Pengingat Batas Pengembalian (H-1 / 24 Jam)
                        </h2>
                        <p class="text-xs text-muted">
                            Sistem mengirimkan notifikasi secara otomatis setiap kali buku pinjaman Anda memasuki H-1 (24 jam sebelum deadline) atau melewati jatuh tempo.
                        </p>
                    </div>
                </div>
            </div>

            @if ($deadlineAlerts->isNotEmpty())
                <div class="mt-4 space-y-3">
                    @foreach ($deadlineAlerts as $alertLoan)
                        @php
                            $sisaHari = $alertLoan->hitungSisaHariDeadline();
                            $isOverdue = $alertLoan->isTerlambat();
                            $hariTerlambat = $alertLoan->hitungHariTerlambat();
                        @endphp
                        <article class="rounded-2xl border {{ $isOverdue ? 'border-rose-300 bg-rose-50/90' : 'border-amber-300 bg-white' }} p-4 shadow-xs transition-all sm:p-5">
                            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                                <div class="flex items-start gap-3.5">
                                    <span class="flex size-10 shrink-0 items-center justify-center rounded-xl {{ $isOverdue ? 'bg-rose-600 text-white' : 'bg-amber-500 text-white' }} mt-0.5">
                                        <i class="fa-solid {{ $isOverdue ? 'fa-triangle-exclamation' : 'fa-clock' }} text-sm"></i>
                                    </span>
                                    <div class="space-y-1">
                                        <div class="flex flex-wrap items-center gap-2">
                                            @if ($isOverdue)
                                                <span class="inline-flex items-center gap-1 rounded-full bg-rose-600 px-2.5 py-0.5 text-[11px] font-bold text-white">
                                                    Lewat Deadline ({{ $hariTerlambat }} Hari)
                                                </span>
                                            @elseif ($sisaHari === 0)
                                                <span class="inline-flex items-center gap-1 rounded-full bg-amber-600 px-2.5 py-0.5 text-[11px] font-bold text-white">
                                                    Hari Ini Batas Akhir (&le; 24 Jam)
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 rounded-full bg-amber-500 px-2.5 py-0.5 text-[11px] font-bold text-white">
                                                    Pengingat H-1 (24 Jam Sebelum Deadline)
                                                </span>
                                            @endif
                                            <span class="text-[11px] font-medium text-muted">
                                                ID Transaksi: <code class="font-mono font-bold text-ink">{{ $alertLoan->id_peminjaman }}</code>
                                            </span>
                                        </div>

                                        <h3 class="text-sm font-bold text-ink sm:text-base">
                                            {{ $alertLoan->buku?->judul_buku ?? 'Koleksi Buku Perpustakaan' }}
                                        </h3>

                                        <p class="text-xs leading-relaxed {{ $isOverdue ? 'text-rose-900' : 'text-slate-700' }}">
                                            @if ($isOverdue)
                                                <strong>Peringatan Keterlambatan:</strong> Waktu peminjaman buku ini telah melewati batas pengembalian ({{ $alertLoan->tanggal_jatuh_tempo?->translatedFormat('d M Y') }}). Mohon segera kembalikan buku ke meja sirkulasi Perpustakaan MNC University Lantai 2.
                                            @elseif ($sisaHari === 0)
                                                <strong>Pengingat Jatuh Tempo Hari Ini (&le; 24 Jam):</strong> Batas waktu pengembalian buku Anda berakhir pada hari ini ({{ $alertLoan->tanggal_jatuh_tempo?->translatedFormat('d M Y') }}). Segera lakukan pengembalian agar tidak tercatat terlambat.
                                            @else
                                                <strong>Pengingat H-1 (24 Jam Sebelum Deadline):</strong> Masa peminjaman buku Anda akan berakhir besok ({{ $alertLoan->tanggal_jatuh_tempo?->translatedFormat('d M Y') }}). Silakan siapkan buku untuk dikembalikan tepat waktu.
                                            @endif
                                        </p>

                                        <div class="pt-1 flex flex-wrap items-center gap-x-4 gap-y-1 text-[11px] text-muted">
                                            <span>No. Induk Eksemplar: <strong class="font-mono text-ink">{{ $alertLoan->eksemplar?->kode_inventaris ?? '-' }}</strong></span>
                                            <span>Tanggal Pinjam: <strong class="text-ink">{{ $alertLoan->tanggal_pinjam?->format('d M Y') ?? '-' }}</strong></span>
                                            <span>Deadline Kembali: <strong class="{{ $isOverdue ? 'text-rose-700' : 'text-amber-800' }}">{{ $alertLoan->tanggal_jatuh_tempo?->format('d M Y') ?? '-' }}</strong></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex shrink-0 flex-wrap items-center gap-2 self-end lg:self-center">
                                    @if ($alertLoan->status === 'Menunggu Verifikasi')
                                        <span class="inline-flex items-center gap-1.5 rounded-xl border border-amber-300 bg-amber-100 px-3.5 py-2 text-xs font-bold text-amber-900">
                                            <i class="fa-solid fa-hourglass-half text-xs"></i>
                                            <span>Menunggu Verifikasi Admin</span>
                                        </span>
                                    @else
                                        <a
                                            href="{{ route('opac', ['q' => $alertLoan->buku?->judul_buku]) }}"
                                            class="btn-press inline-flex items-center gap-1.5 rounded-xl bg-primary px-4 py-2.5 text-xs font-semibold text-white shadow-xs transition-all hover:bg-primary-dark"
                                        >
                                            <i class="fa-solid fa-arrow-rotate-left text-xs"></i>
                                            <span>Kembalikan via OPAC</span>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="mt-4 rounded-xl border border-dashed border-line bg-slate-50/70 p-6 text-center">
                    <div class="mx-auto flex size-11 items-center justify-center rounded-full bg-emerald-100 text-emerald-700">
                        <i class="fa-solid fa-circle-check text-lg"></i>
                    </div>
                    <h3 class="mt-2.5 text-sm font-bold text-ink">Tidak Ada Peringatan Deadline H-1 Saat Ini</h3>
                    <p class="mt-1 text-xs text-muted">
                        Seluruh buku pinjaman Anda masih berada dalam masa pinjam aman atau telah dikembalikan tepat waktu.
                    </p>
                </div>
            @endif
        </section>

        {{-- Bagian Utama 2: Daftar Pinjaman Aktif & Pengajuan Pemustaka --}}
        <section aria-labelledby="heading-daftar-pinjaman" class="rounded-2xl border border-line bg-white p-5 shadow-xs sm:p-6">
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-line pb-4">
                <div>
                    <h2 id="heading-daftar-pinjaman" class="text-base font-bold text-ink sm:text-lg">
                        Daftar Pinjaman Aktif &amp; Riwayat Sirkulasi Saya
                    </h2>
                    <p class="text-xs text-muted">
                        Setiap pemustaka dapat meminjam maksimal {{ $maxLoans }} buku sekaligus.
                    </p>
                </div>
            </div>

            @if ($activeLoans->isEmpty() && $pendingRequests->isEmpty() && $historyLoans->isEmpty())
                <div class="mt-5 rounded-xl border border-dashed border-line bg-slate-50/60 p-8 text-center">
                    <i class="fa-solid fa-book-open text-2xl text-muted mb-2 block"></i>
                    <p class="text-sm font-semibold text-ink">Belum Ada Riwayat Peminjaman Buku</p>
                    <p class="mt-1 text-xs text-muted">Anda belum memiliki pinjaman aktif maupun pengajuan peminjaman buku di katalog OPAC.</p>
                </div>
            @else
                <div class="mt-4 overflow-x-auto">
                    <table class="w-full text-left text-xs text-ink">
                        <thead class="border-b border-line bg-slate-50/80 text-[11px] font-bold uppercase tracking-wider text-muted">
                            <tr>
                                <th scope="col" class="px-4 py-3">Koleksi Buku</th>
                                <th scope="col" class="px-4 py-3">Eksemplar</th>
                                <th scope="col" class="px-4 py-3">Tanggal Pinjam</th>
                                <th scope="col" class="px-4 py-3">Deadline Pengembalian</th>
                                <th scope="col" class="px-4 py-3">Status &amp; Pengingat</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-line/70">
                            @foreach ($activeLoans->concat($pendingRequests)->concat($historyLoans->take(10)) as $loan)
                                @php
                                    $sisaHari = $loan->hitungSisaHariDeadline();
                                    $isH1 = $loan->isMendekatiDeadline();
                                    $isOverdue = $loan->isTerlambat() && ! $loan->tanggal_kembali;
                                @endphp
                                <tr class="hover:bg-slate-50/70 transition-colors">
                                    <td class="px-4 py-3.5">
                                        <p class="font-bold text-ink">{{ $loan->buku?->judul_buku ?? '-' }}</p>
                                        <p class="text-[11px] text-muted">ID: <code class="font-mono">{{ $loan->id_peminjaman }}</code></p>
                                    </td>
                                    <td class="px-4 py-3.5 font-mono text-xs">
                                        {{ $loan->eksemplar?->kode_inventaris ?? '(Menunggu Alokasi)' }}
                                    </td>
                                    <td class="px-4 py-3.5 whitespace-nowrap">
                                        {{ $loan->tanggal_pinjam?->format('d M Y') ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3.5 whitespace-nowrap">
                                        <strong class="{{ $isOverdue ? 'text-rose-700' : ($isH1 ? 'text-amber-700' : 'text-ink') }}">
                                            {{ $loan->tanggal_jatuh_tempo?->format('d M Y') ?? '-' }}
                                        </strong>
                                    </td>
                                    <td class="px-4 py-3.5">
                                        @if ($loan->tanggal_kembali || $loan->status === 'Kembali')
                                            <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-2.5 py-0.5 text-[11px] font-semibold text-emerald-800">
                                                <i class="fa-solid fa-check text-[10px]"></i>
                                                <span>Sudah Dikembalikan ({{ $loan->tanggal_kembali?->format('d M Y') }})</span>
                                            </span>
                                        @elseif ($loan->status === 'Ditolak')
                                            <span class="inline-flex items-center gap-1 rounded-full bg-rose-100 px-2.5 py-0.5 text-[11px] font-semibold text-rose-800">
                                                Ditolak
                                            </span>
                                        @elseif ($loan->status === 'Menunggu Persetujuan')
                                            <span class="inline-flex items-center gap-1 rounded-full bg-blue-100 px-2.5 py-0.5 text-[11px] font-semibold text-primary">
                                                Menunggu Persetujuan Admin
                                            </span>
                                        @elseif ($isOverdue)
                                            <span class="inline-flex items-center gap-1 rounded-full bg-rose-100 px-2.5 py-0.5 text-[11px] font-bold text-rose-800">
                                                Terlambat {{ $loan->hitungHariTerlambat() }} Hari
                                            </span>
                                        @elseif ($isH1)
                                            <span class="inline-flex items-center gap-1 rounded-full bg-amber-100 px-2.5 py-0.5 text-[11px] font-bold text-amber-900">
                                                <i class="fa-solid fa-bell text-[10px] text-amber-600"></i>
                                                <span>{{ $sisaHari === 0 ? 'Jatuh Tempo Hari Ini (<= 24 Jam)' : 'H-1 (24 Jam Sebelum Deadline)' }}</span>
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 text-[11px] font-semibold text-emerald-800">
                                                Dipinjam ({{ $sisaHari }} hari lagi)
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>
    </div>
@endsection

