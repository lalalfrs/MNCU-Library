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
                        Silakan isi nama lengkap serta identitas Anda (NIM/NIP untuk Mahasiswa, Dosen, atau Tendik, atau Alamat untuk Pengunjung Umum). Setiap pengisian daftar hadir akan tercatat langsung pada <strong>Statistik Kunjungan Perpustakaan</strong>.
                    </p>

                    <form action="{{ route('kehadiran.store') }}" method="POST" class="mt-6 space-y-5">
                        @csrf

                        {{-- Pilihan Kategori Pengunjung (2 Pilihan: NIM / NIP dan Umum) --}}
                        <div>
                            <label class="block text-xs sm:text-sm font-bold text-[#0b1f3a] mb-2">
                                Kategori Pengunjung
                            </label>
                            @php
                                $defaultKategori = old('kategori_pengunjung');
                                if (! $defaultKategori && auth()->check()) {
                                    $defaultKategori = 'NIM / NIP';
                                }
                                $defaultKategori = $defaultKategori ?: 'NIM / NIP';
                            @endphp
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 sm:gap-3" id="kategori-pengunjung-group">
                                @foreach ([
                                    ['value' => 'NIM / NIP', 'icon' => 'fa-id-card', 'label' => 'NIM / NIP', 'placeholder' => 'Masukkan 5 digit NIM atau NIP (cth: 20001 / 10001)', 'hint' => 'Ketik 5 digit NIM Mahasiswa atau NIP Dosen/Tendik. Nama akan otomatis terdeteksi dari database.'],
                                    ['value' => 'Umum', 'icon' => 'fa-users', 'label' => 'Alamat Domisili / Instansi', 'placeholder' => 'Masukkan alamat domisili atau instansi asal Anda', 'hint' => 'Masukkan alamat domisili atau instansi asal Anda.'],
                                ] as $kat)
                                    <label class="cursor-pointer">
                                        <input
                                            type="radio"
                                            name="kategori_pengunjung"
                                            value="{{ $kat['value'] }}"
                                            data-label="{{ $kat['label'] }}"
                                            data-placeholder="{{ $kat['placeholder'] }}"
                                            data-hint="{{ $kat['hint'] }}"
                                            class="peer sr-only kategori-radio"
                                            {{ $defaultKategori === $kat['value'] ? 'checked' : '' }}
                                        >
                                        <div class="flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-slate-50/70 px-4 py-3 text-xs sm:text-sm font-semibold text-slate-600 transition-all peer-checked:border-[#0b2a5b] peer-checked:bg-[#0b2a5b] peer-checked:text-white hover:border-slate-300">
                                            <i class="fa-solid {{ $kat['icon'] }} text-xs"></i>
                                            <span>{{ $kat['value'] }}</span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div id="form-inputs-container" class="space-y-4">
                            {{-- Input Var: NIM / NIP / Alamat sesuai Kategori Pengunjung (Untuk Civitas ditaruh di atas) --}}
                            <div id="wrapper-identitas">
                                <label for="identitas_pengunjung" class="block text-xs sm:text-sm font-bold text-[#0b1f3a] mb-1.5">
                                    <span id="label-identitas">{{ $defaultKategori === 'Umum' ? 'Alamat Domisili / Instansi' : 'NIM / NIP' }}</span> <span class="text-rose-600">*</span>
                                </label>
                                <div class="relative">
                                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                        <i class="fa-solid fa-id-card text-xs sm:text-sm"></i>
                                    </span>
                                    <input
                                        type="text"
                                        id="identitas_pengunjung"
                                        name="identitas_pengunjung"
                                        value="{{ old('identitas_pengunjung', auth()->user()->nomor_identitas ?? '') }}"
                                        required
                                        maxlength="255"
                                        autocomplete="off"
                                        placeholder="{{ $defaultKategori === 'Umum' ? 'Masukkan alamat domisili atau instansi asal Anda' : 'Masukkan 5 digit NIM atau NIP (cth: 20001 / 10001)' }}"
                                        class="w-full rounded-xl border border-slate-300 bg-white pl-10 pr-10 py-3 text-xs sm:text-sm text-slate-800 placeholder:text-slate-400 focus:border-[#0b2a5b] focus:outline-none focus:ring-2 focus:ring-[#0b2a5b]/20"
                                    >
                                    <span id="identitas-status-icon" class="hidden absolute inset-y-0 right-0 flex items-center pr-3.5 pointer-events-none" aria-hidden="true">
                                        <i class="fa-solid fa-spinner fa-spin text-blue-600 text-xs"></i>
                                    </span>
                                </div>
                                <div id="identitas-feedback"></div>
                                <p id="hint-identitas" class="mt-1.5 text-[11px] sm:text-xs text-slate-500">
                                    {{ $defaultKategori === 'Umum' ? 'Masukkan alamat domisili atau instansi asal Anda.' : 'Ketik 5 digit NIM Mahasiswa atau NIP Dosen/Tendik. Nama akan otomatis terdeteksi dari database.' }}
                                </p>
                                @error('identitas_pengunjung')
                                    <p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Input Text: Nama Lengkap (Textbox bawahnya, otomatis terisi untuk double check) --}}
                            <div id="wrapper-nama">
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
                                        class="w-full rounded-xl border border-slate-300 bg-white pl-10 pr-4 py-3 text-xs sm:text-sm text-slate-800 placeholder:text-slate-400 focus:border-[#0b2a5b] focus:outline-none focus:ring-2 focus:ring-[#0b2a5b]/20 transition-all"
                                    >
                                </div>
                                <p id="hint-nama" class="mt-1.5 text-[11px] sm:text-xs text-slate-500">
                                    Terisi otomatis saat NIM/NIP terdaftar ditemukan, atau ketik nama lengkap manual.
                                </p>
                                @error('nama_pengunjung')
                                    <p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Input Keperluan Kunjungan (Opsional) --}}
                            <div id="wrapper-keperluan">
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
            const namaInput = document.getElementById('nama_pengunjung');
            const labelIdentitas = document.getElementById('label-identitas');
            const hintIdentitas = document.getElementById('hint-identitas');
            const hintNama = document.getElementById('hint-nama');
            const feedbackEl = document.getElementById('identitas-feedback');
            const statusIcon = document.getElementById('identitas-status-icon');
            const container = document.getElementById('form-inputs-container');
            const wrapperIdentitas = document.getElementById('wrapper-identitas');
            const wrapperNama = document.getElementById('wrapper-nama');

            if (!radios.length || !identitasInput || !namaInput) return;

            let lookupTimeout = null;
            let lastLookedUpVal = '';

            function escapeHtml(text) {
                const div = document.createElement('div');
                div.textContent = text || '';
                return div.innerHTML;
            }

            function getActiveKategori() {
                const checked = document.querySelector('.kategori-radio:checked');
                return checked ? checked.value : 'NIM / NIP';
            }

            function updateLayoutAndPlaceholders() {
                const checked = document.querySelector('.kategori-radio:checked');
                const kategori = checked ? checked.value : 'NIM / NIP';
                const isUmum = (kategori === 'Umum');

                if (checked) {
                    if (checked.dataset.placeholder) {
                        identitasInput.setAttribute('placeholder', checked.dataset.placeholder);
                    }
                    if (labelIdentitas && checked.dataset.label) {
                        labelIdentitas.textContent = checked.dataset.label;
                    }
                    if (hintIdentitas && checked.dataset.hint) {
                        hintIdentitas.textContent = checked.dataset.hint;
                    }
                }

                // Atur urutan tampilan:
                // Mahasiswa / Dosen / Tendik -> NIM/NIP di atas, Nama Lengkap di bawahnya.
                // Tamu Umum -> Nama Lengkap di atas, Alamat di bawahnya.
                if (container && wrapperIdentitas && wrapperNama) {
                    if (isUmum) {
                        container.insertBefore(wrapperNama, wrapperIdentitas);
                        if (hintNama) {
                            hintNama.textContent = 'Masukkan nama lengkap Anda sesuai identitas pengenal.';
                        }
                        namaInput.setAttribute('placeholder', 'Ketik nama lengkap Anda...');
                        if (feedbackEl) feedbackEl.innerHTML = '';
                        if (statusIcon) statusIcon.classList.add('hidden');
                    } else {
                        container.insertBefore(wrapperIdentitas, wrapperNama);
                        if (hintNama) {
                            hintNama.textContent = 'Terisi otomatis saat NIM/NIP terdaftar ditemukan, atau ketik nama lengkap manual.';
                        }
                        if (identitasInput.value.trim().length >= 3) {
                            performLookup(false);
                        }
                    }
                }
            }

            function performLookup(forceClear = false) {
                const kategori = getActiveKategori();
                const identitasVal = identitasInput.value.trim();

                if (kategori === 'Umum') {
                    if (feedbackEl) feedbackEl.innerHTML = '';
                    if (statusIcon) statusIcon.classList.add('hidden');
                    return;
                }

                if (identitasVal.length < 3) {
                    if (feedbackEl) feedbackEl.innerHTML = '';
                    if (statusIcon) statusIcon.classList.add('hidden');
                    namaInput.setAttribute('placeholder', 'Ketik nama lengkap Anda...');
                    if (forceClear) {
                        namaInput.value = '';
                    }
                    lastLookedUpVal = '';
                    return;
                }

                if (identitasVal === lastLookedUpVal && !forceClear) {
                    return;
                }

                if (statusIcon) {
                    statusIcon.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-blue-600 text-xs"></i>';
                    statusIcon.classList.remove('hidden');
                }
                if (feedbackEl) {
                    feedbackEl.innerHTML = '<p class="mt-1.5 flex items-center gap-1.5 text-[11px] sm:text-xs text-blue-600 font-medium"><i class="fa-solid fa-spinner fa-spin text-[10px]"></i> Mencari data pemustaka di database...</p>';
                }

                const lookupUrl = `{{ route('kehadiran.lookup') }}?identitas=${encodeURIComponent(identitasVal)}&kategori=${encodeURIComponent(kategori)}`;

                fetch(lookupUrl, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    lastLookedUpVal = identitasVal;

                    if (data.found) {
                        // Menaruh nama terdaftar di placeholder untuk double check
                        namaInput.setAttribute('placeholder', `Nama terdaftar: ${data.nama_lengkap}`);
                        // Tertulis sendiri di textbox bawahnya
                        namaInput.value = data.nama_lengkap;

                        if (statusIcon) {
                            statusIcon.innerHTML = '<i class="fa-solid fa-circle-check text-emerald-600 text-sm"></i>';
                            statusIcon.classList.remove('hidden');
                        }
                        if (feedbackEl) {
                            feedbackEl.innerHTML = `
                                <div class="mt-2 flex items-center justify-between rounded-xl border border-emerald-200 bg-emerald-50/90 px-3.5 py-2 text-[11px] sm:text-xs text-emerald-800">
                                    <span class="inline-flex items-center gap-1.5 font-semibold">
                                        <i class="fa-solid fa-circle-check text-emerald-600"></i>
                                        Identitas Terdaftar: <strong>${escapeHtml(data.nama_lengkap)}</strong>
                                    </span>
                                    <span class="rounded-md bg-emerald-200/80 px-2 py-0.5 text-[10px] font-bold text-emerald-900 uppercase">
                                        ${escapeHtml(data.kategori)}${data.prodi ? ' &bull; ' + escapeHtml(data.prodi) : ''}
                                    </span>
                                </div>`;
                        }
                        if (hintNama) {
                            hintNama.innerHTML = '<span class="text-emerald-700 font-semibold flex items-center gap-1.5"><i class="fa-solid fa-circle-check text-emerald-600"></i> Nama berhasil terverifikasi otomatis sesuai NIM/NIP di atas.</span>';
                        }
                    } else {
                        if (statusIcon) {
                            if (identitasVal.length >= 5) {
                                statusIcon.innerHTML = '<i class="fa-solid fa-circle-info text-amber-500 text-sm"></i>';
                                statusIcon.classList.remove('hidden');
                            } else {
                                statusIcon.classList.add('hidden');
                            }
                        }
                        if (feedbackEl) {
                            if (identitasVal.length >= 5) {
                                feedbackEl.innerHTML = `
                                    <div class="mt-2 flex items-center gap-1.5 rounded-xl border border-amber-200 bg-amber-50/90 px-3 py-2 text-[11px] sm:text-xs text-amber-800 font-medium">
                                        <i class="fa-solid fa-circle-info text-amber-600 text-xs shrink-0"></i>
                                        <span>${escapeHtml(data.message || 'NIM / NIP belum terdaftar. Silakan masukkan nama lengkap secara manual pada kotak di bawah.')}</span>
                                    </div>`;
                            } else {
                                feedbackEl.innerHTML = '';
                            }
                        }
                        if (hintNama) {
                            hintNama.textContent = 'Ketik nama lengkap Anda sesuai kartu identitas.';
                        }
                        namaInput.setAttribute('placeholder', 'Ketik nama lengkap Anda...');
                    }
                })
                .catch(() => {
                    if (statusIcon) statusIcon.classList.add('hidden');
                    if (feedbackEl) feedbackEl.innerHTML = '';
                });
            }

            // Listener input debounce pada NIM/NIP
            identitasInput.addEventListener('input', function () {
                clearTimeout(lookupTimeout);
                lookupTimeout = setTimeout(() => {
                    performLookup(false);
                }, 250);
            });

            // Trigger langsung saat blur (keluar dari kotak input NIM/NIP)
            identitasInput.addEventListener('blur', function () {
                clearTimeout(lookupTimeout);
                performLookup(false);
            });

            // Listener ganti kategori radio
            radios.forEach((radio) => {
                radio.addEventListener('change', function () {
                    updateLayoutAndPlaceholders();
                });
            });

            // Inisialisasi saat halaman pertama kali dimuat
            updateLayoutAndPlaceholders();
            if (identitasInput.value.trim().length >= 3) {
                performLookup(false);
            }
        })();
    </script>
@endsection

