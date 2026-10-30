@extends('layouts.app')

@section('title', 'Kehadiran Pengunjung')
@section('description', 'Buku Tamu dan Presensi Kehadiran Pengunjung Perpustakaan MNC University.')

@section('content')
    <div class="mx-auto flex w-full min-w-0 max-w-[1570px] flex-col gap-8 px-3 sm:px-4 lg:px-6 xl:px-8 pt-4 sm:pt-6 lg:pt-8 pb-12">

        {{-- Breadcrumb & Kembali ke Beranda --}}
        <div class="flex flex-wrap items-center justify-between gap-3">
            <nav aria-label="Breadcrumb" class="flex items-center gap-2 text-xs sm:text-sm text-slate-500">
                <a href="{{ route('beranda') }}" class="font-medium text-primary hover:underline inline-flex items-center gap-1.5">
                    <i class="fa-solid fa-house text-xs"></i>
                    <span>Beranda</span>
                </a>
                <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                <span class="font-semibold text-slate-800">Kehadiran Pengunjung</span>
            </nav>

            <a href="{{ route('beranda') }}" class="btn-press inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 text-xs sm:text-sm font-semibold text-slate-700 shadow-2xs transition-all hover:border-primary/40 hover:text-primary">
                <i class="fa-solid fa-arrow-left text-xs text-primary"></i>
                <span>Kembali ke Beranda</span>
            </a>
        </div>

        {{-- Grid Utama: Form Input Kehadiran (Kiri) + Statistik & Riwayat Kehadiran (Kanan) --}}
        <div class="grid grid-cols-1 items-start gap-6 lg:grid-cols-12 lg:gap-8">

            {{-- Kolom Kiri (7 Kolom): Form Buku Tamu / Kehadiran --}}
            <div class="lg:col-span-7 rounded-[28px] border border-slate-200/90 bg-white p-6 sm:p-8 lg:p-10 shadow-[0_8px_30px_rgba(15,23,42,0.05)] relative overflow-hidden">
                {{-- Siluet Watermark Logo di Belakang Kartu --}}
                <div class="pointer-events-none select-none absolute -right-10 -bottom-10 z-0" aria-hidden="true">
                    <img
                        src="{{ asset('images/LogoTXT.png') }}"
                        alt=""
                        class="w-[260px] sm:w-[320px] max-w-none object-contain opacity-[0.04] grayscale contrast-125 -rotate-6"
                    >
                </div>

                <div class="relative z-10">
                    <div class="mb-4 flex flex-wrap items-center gap-3">
                        <span class="inline-flex items-center gap-2 rounded-full bg-[#f1f5f9] px-3.5 py-1.5 text-xs font-bold text-[#0f2a6b]">
                            <span class="size-2 rounded-full bg-[#f59e0b]"></span>
                            Buku Tamu &amp; Presensi
                        </span>
                        <span class="inline-flex items-center gap-1.5 text-xs font-medium text-slate-400">
                            <i class="fa-regular fa-calendar text-[11px]" aria-hidden="true"></i>
                            {{ now()->translatedFormat('d F Y') }}
                        </span>
                    </div>

                    <h1 class="text-xl sm:text-2xl lg:text-[28px] font-extrabold uppercase tracking-tight text-[#0b1f3a] leading-tight">
                        Daftar Kehadiran Pengunjung Perpustakaan
                    </h1>
                    <p class="mt-2.5 text-xs sm:text-sm text-slate-600 leading-relaxed">
                        Silakan isi nama lengkap serta identitas Anda (NIM untuk Mahasiswa, NIP untuk Dosen/Staf, atau Alamat untuk Pengunjung Umum). Setiap pengisian daftar hadir akan tercatat langsung pada <strong>Statistik Kunjungan Perpustakaan</strong>.
                    </p>

                    <form action="{{ route('kehadiran.store') }}" method="POST" class="mt-6 space-y-5">
                        @csrf

                        {{-- Pilihan Kategori Pengunjung (Opsional untuk mempermudah label, tetap menyimpan ke kolom identitas) --}}
                        <div>
                            <label class="block text-xs sm:text-sm font-bold text-[#0b1f3a] mb-2">
                                Kategori Pengunjung
                            </label>
                            @php
                                $defaultKategori = old('kategori_pengunjung');
                                if (! $defaultKategori && auth()->check()) {
                                    if (auth()->user()->isDosen()) {
                                        $defaultKategori = 'Dosen / Staf';
                                    } elseif (auth()->user()->isSuperAdmin() || auth()->user()->isPustakawan()) {
                                        $defaultKategori = 'Dosen / Staf';
                                    } else {
                                        $defaultKategori = 'Mahasiswa';
                                    }
                                }
                                $defaultKategori = $defaultKategori ?: 'Mahasiswa';
                            @endphp
                            <div class="grid grid-cols-3 gap-2.5 sm:gap-3" id="kategori-pengunjung-group">
                                @foreach ([
                                    ['value' => 'Mahasiswa', 'icon' => 'fa-user-graduate', 'placeholder' => 'Masukkan NIM Mahasiswa (Contoh: 2024010012)'],
                                    ['value' => 'Dosen / Staf', 'icon' => 'fa-chalkboard-user', 'placeholder' => 'Masukkan NIP / NIDN Dosen atau Staf'],
                                    ['value' => 'Umum', 'icon' => 'fa-users', 'placeholder' => 'Masukkan Alamat Lengkap / Instansi Asal (untuk Umum)'],
                                ] as $kat)
                                    <label class="cursor-pointer">
                                        <input
                                            type="radio"
                                            name="kategori_pengunjung"
                                            value="{{ $kat['value'] }}"
                                            data-placeholder="{{ $kat['placeholder'] }}"
                                            class="peer sr-only kategori-radio"
                                            {{ $defaultKategori === $kat['value'] ? 'checked' : '' }}
                                        >
                                        <div class="flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-slate-50/70 px-3 py-2.5 text-xs sm:text-sm font-semibold text-slate-600 transition-all peer-checked:border-[#0b2a5b] peer-checked:bg-[#0b2a5b] peer-checked:text-white hover:border-slate-300">
                                            <i class="fa-solid {{ $kat['icon'] }} text-xs"></i>
                                            <span>{{ $kat['value'] }}</span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Input Text: Nama Lengkap --}}
                        <div>
                            <label for="nama_pengunjung" class="block text-xs sm:text-sm font-bold text-[#0b1f3a] mb-1.5">
                                Nama Lengkap <span class="text-rose-600">*</span>
                            </label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                    <i class="fa-solid fa-user text-xs sm:text-sm"></i>
                                </span>
                                <input
                                    type="text"
                                    id="nama_pengunjung"
                                    name="nama_pengunjung"
                                    value="{{ old('nama_pengunjung', auth()->user()->nama_lengkap ?? '') }}"
                                    required
                                    maxlength="150"
                                    placeholder="Ketik nama lengkap Anda..."
                                    class="w-full rounded-xl border border-slate-300 bg-white pl-10 pr-4 py-3 text-xs sm:text-sm text-slate-800 placeholder:text-slate-400 focus:border-[#0b2a5b] focus:outline-none focus:ring-2 focus:ring-[#0b2a5b]/20"
                                >
                            </div>
                            @error('nama_pengunjung')
                                <p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Input Var: NIM / NIP / Alamat (untuk Umum) --}}
                        <div>
                            <label for="identitas_pengunjung" class="block text-xs sm:text-sm font-bold text-[#0b1f3a] mb-1.5">
                                NIM / NIP / Alamat (untuk Umum) <span class="text-rose-600">*</span>
                            </label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                    <i class="fa-solid fa-id-card text-xs sm:text-sm"></i>
                                </span>
                                <input
                                    type="text"
                                    id="identitas_pengunjung"
                                    name="identitas_pengunjung"
                                    value="{{ old('identitas_pengunjung', auth()->user()->nomor_induk ?? '') }}"
                                    required
                                    maxlength="255"
                                    placeholder="Masukkan NIM / NIP / Alamat lengkap (untuk pengunjung Umum)..."
                                    class="w-full rounded-xl border border-slate-300 bg-white pl-10 pr-4 py-3 text-xs sm:text-sm text-slate-800 placeholder:text-slate-400 focus:border-[#0b2a5b] focus:outline-none focus:ring-2 focus:ring-[#0b2a5b]/20"
                                >
                            </div>
                            <p class="mt-1.5 text-[11px] sm:text-xs text-slate-500">
                                Diisi dengan <strong>NIM</strong> (Mahasiswa), <strong>NIP/NIDN</strong> (Dosen/Staf), atau <strong>Alamat / Instansi</strong> jika Anda pengunjung Umum.
                            </p>
                            @error('identitas_pengunjung')
                                <p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Input Keperluan Kunjungan (Opsional) --}}
                        <div>
                            <label for="keperluan" class="block text-xs sm:text-sm font-bold text-[#0b1f3a] mb-1.5">
                                Keperluan Kunjungan <span class="text-xs font-normal text-slate-400">(Opsional)</span>
                            </label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                    <i class="fa-solid fa-book-open-reader text-xs sm:text-sm"></i>
                                </span>
                                <input
                                    type="text"
                                    id="keperluan"
                                    name="keperluan"
                                    value="{{ old('keperluan') }}"
                                    maxlength="255"
                                    placeholder="Contoh: Membaca di tempat, Peminjaman buku OPAC, Diskusi tugas akhir..."
                                    class="w-full rounded-xl border border-slate-300 bg-white pl-10 pr-4 py-3 text-xs sm:text-sm text-slate-800 placeholder:text-slate-400 focus:border-[#0b2a5b] focus:outline-none focus:ring-2 focus:ring-[#0b2a5b]/20"
                                >
                            </div>
                        </div>

                        <div class="pt-2 flex flex-wrap items-center gap-3">
                            <button
                                type="submit"
                                class="btn-press inline-flex items-center justify-center gap-2.5 rounded-full bg-[#0b2a5b] hover:bg-primary-dark px-7 py-3.5 text-xs sm:text-sm font-bold text-white shadow-sm transition-all cursor-pointer"
                            >
                                <i class="fa-solid fa-clipboard-check text-xs text-[#f59e0b]"></i>
                                <span>Catat Kehadiran Pengunjung</span>
                                <i class="fa-solid fa-arrow-right text-xs text-[#f59e0b]"></i>
                            </button>

                            <a
                                href="{{ route('beranda') }}"
                                class="btn-press inline-flex items-center justify-center gap-2 rounded-full border border-slate-200 bg-white px-5 py-3.5 text-xs sm:text-sm font-semibold text-slate-700 transition-all hover:border-primary/40 hover:text-primary"
                            >
                                <span>Batal</span>
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Kolom Kanan (5 Kolom): Kartu Statistik Kunjungan Real-Time & Daftar Pengunjung Terbaru --}}
            <div class="lg:col-span-5 flex flex-col gap-6">

                {{-- Statistik Kunjungan --}}
                <div class="rounded-[26px] border border-slate-200/90 bg-white p-5 sm:p-6 shadow-[0_8px_30px_rgba(15,23,42,0.05)]">
                    <div class="flex items-center justify-between border-b border-slate-200/80 pb-3.5">
                        <div class="flex items-center gap-2.5">
                            <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-[#0b2a5b]/10 text-[#0b2a5b]">
                                <i class="fa-solid fa-chart-simple text-base" aria-hidden="true"></i>
                            </span>
                            <div>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Aktivitas Pemustaka</span>
                                <h2 class="text-base sm:text-lg font-bold text-[#0b1f3a]">Statistik Kunjungan</h2>
                            </div>
                        </div>
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-50 px-3 py-1 text-xs font-semibold text-slate-600 border border-slate-200/80">
                            <i class="fa-solid fa-clock-rotate-left text-[10px] text-[#f59e0b]"></i>
                            Real-time
                        </span>
                    </div>

                    <dl class="mt-4 flex flex-col gap-3">
                        <div class="flex items-center justify-between rounded-xl border border-blue-200/80 bg-blue-50/40 px-4 py-3">
                            <div class="flex items-center gap-3">
                                <span class="flex size-9 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-600">
                                    <i class="fa-solid fa-user-clock text-xs" aria-hidden="true"></i>
                                </span>
                                <div>
                                    <dt class="text-xs sm:text-sm font-bold text-slate-800">Hari ini</dt>
                                    <span class="text-[11px] text-slate-500">Kunjungan tercatat hari ini</span>
                                </div>
                            </div>
                            <dd id="stat-today-count" class="text-xl sm:text-2xl font-extrabold text-blue-700">{{ number_format($visitorStats['today'] ?? 0) }}</dd>
                        </div>

                        <div class="flex items-center justify-between rounded-xl border border-purple-200/80 bg-purple-50/40 px-4 py-3">
                            <div class="flex items-center gap-3">
                                <span class="flex size-9 shrink-0 items-center justify-center rounded-xl bg-purple-100 text-[#7C3AED]">
                                    <i class="fa-solid fa-calendar-check text-xs" aria-hidden="true"></i>
                                </span>
                                <div>
                                    <dt class="text-xs sm:text-sm font-bold text-slate-800">Bulan ini</dt>
                                    <span class="text-[11px] text-slate-500">{{ now()->translatedFormat('F Y') }}</span>
                                </div>
                            </div>
                            <dd id="stat-month-count" class="text-xl sm:text-2xl font-extrabold text-[#7C3AED]">{{ number_format($visitorStats['month'] ?? 0) }}</dd>
                        </div>

                        <div class="flex items-center justify-between rounded-xl border border-emerald-200/80 bg-emerald-50/40 px-4 py-3">
                            <div class="flex items-center gap-3">
                                <span class="flex size-9 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600">
                                    <i class="fa-solid fa-users text-xs" aria-hidden="true"></i>
                                </span>
                                <div>
                                    <dt class="text-xs sm:text-sm font-bold text-slate-800">Total Kunjungan</dt>
                                    <span class="text-[11px] text-slate-500">Akumulasi seluruh pengunjung</span>
                                </div>
                            </div>
                            <dd id="stat-total-count" class="text-xl sm:text-2xl font-extrabold text-emerald-700">{{ number_format($visitorStats['total'] ?? 0) }}</dd>
                        </div>
                    </dl>
                </div>

                {{-- Daftar Kehadiran Terbaru --}}
                <div class="rounded-[26px] border border-slate-200/90 bg-white p-5 sm:p-6 shadow-[0_8px_30px_rgba(15,23,42,0.05)]">
                    <div class="flex items-center justify-between border-b border-slate-200/80 pb-3">
                        <div class="flex items-center gap-2.5">
                            <span class="flex size-9 shrink-0 items-center justify-center rounded-xl bg-amber-500/10 text-amber-600">
                                <i class="fa-solid fa-list-check text-sm" aria-hidden="true"></i>
                            </span>
                            <div>
                                <h2 class="text-sm sm:text-base font-bold text-[#0b1f3a]">Kehadiran Terbaru</h2>
                                <p class="text-[11px] text-slate-500">Pengunjung yang baru saja mengisi buku tamu</p>
                            </div>
                        </div>
                    </div>

                    @if ($recentAttendees->isEmpty())
                        <div class="py-8 text-center text-xs sm:text-sm text-slate-500">
                            Belum ada data buku tamu yang diisi secara manual hari ini.
                        </div>
                    @else
                        <ul class="mt-3.5 divide-y divide-slate-100">
                            @foreach ($recentAttendees as $attendee)
                                <li class="py-3 flex items-start justify-between gap-3">
                                    <div class="min-w-0">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <p class="text-xs sm:text-sm font-bold text-slate-800 truncate">
                                                {{ $attendee->nama_pengunjung }}
                                            </p>
                                            @if ($attendee->kategori_pengunjung)
                                                <span class="inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-semibold text-[#0b2a5b]">
                                                    {{ $attendee->kategori_pengunjung }}
                                                </span>
                                            @endif
                                        </div>
                                        <p class="mt-0.5 text-[11px] sm:text-xs text-slate-500 truncate">
                                            <span class="font-medium text-slate-600">ID/Alamat:</span> {{ $attendee->identitas_pengunjung }}
                                        </p>
                                        @if ($attendee->keperluan)
                                            <p class="mt-0.5 text-[11px] text-slate-400 truncate">
                                                {{ $attendee->keperluan }}
                                            </p>
                                        @endif
                                    </div>
                                    <span class="shrink-0 text-[11px] font-medium text-slate-400">
                                        {{ $attendee->waktu_kunjungan?->format('H:i') ?? '' }} WIB
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

            </div>
        </div>
    </div>

    <script>
        (function () {
            const radios = document.querySelectorAll('.kategori-radio');
            const identitasInput = document.getElementById('identitas_pengunjung');
            if (!radios.length || !identitasInput) return;

            function syncPlaceholder() {
                const checked = document.querySelector('.kategori-radio:checked');
                if (checked && checked.dataset.placeholder) {
                    identitasInput.setAttribute('placeholder', checked.dataset.placeholder);
                }
            }

            radios.forEach((radio) => {
                radio.addEventListener('change', syncPlaceholder);
            });
        })();
    </script>
@endsection

