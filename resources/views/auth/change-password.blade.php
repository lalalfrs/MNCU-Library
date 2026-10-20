@extends('layouts.app')

@section('title', 'Ubah Kata Sandi')
@section('description', 'Perbarui kata sandi akun pemustaka Perpustakaan MNC University Anda dengan aman.')

@section('content')
    <div class="mx-auto flex max-w-[1570px] flex-col gap-8 px-3 pt-4 sm:gap-10 lg:px-6 lg:pt-6">

        {{-- Page Header & Breadcrumb --}}
        <section aria-labelledby="judul-ganti-password" class="px-1 lg:px-[26px]">
            <nav aria-label="Breadcrumb" class="mb-3 flex items-center gap-2 text-xs text-muted sm:text-sm">
                <a href="{{ route('beranda') }}" class="transition-colors hover:text-primary">Beranda</a>
                <svg class="size-3.5 text-muted/60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="m9 18 6-6-6-6"/>
                </svg>
                <span class="text-ink-secondary">Pengaturan Akun</span>
                <svg class="size-3.5 text-muted/60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="m9 18 6-6-6-6"/>
                </svg>
                <span class="font-semibold text-primary">Ubah Kata Sandi</span>
            </nav>

            <div class="flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
                <div>
                    <h1 id="judul-ganti-password" class="text-2xl font-bold tracking-tight text-primary sm:text-3xl lg:text-4xl">
                        Ubah Kata Sandi
                    </h1>
                    <p class="mt-1.5 text-xs leading-relaxed text-ink-secondary sm:text-sm lg:text-base">
                        Kelola keamanan akun pemustaka Anda dengan memperbarui kata sandi secara berkala.
                    </p>
                </div>
            </div>
            <div class="mt-5 h-0.5 w-full bg-gradient-to-r from-primary via-primary/60 to-transparent"></div>
        </section>

        {{-- Main Grid: Form dan Info Akun --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-12 lg:gap-8 px-1 lg:px-[26px] mb-12">
            
            {{-- Kolom Kiri: Formulir Ganti Kata Sandi (7 Kolom Desktop) --}}
            <div class="lg:col-span-7">
                <div class="rounded-2xl border border-line bg-white p-6 shadow-card sm:p-8">
                    
                    {{-- Alert Notifikasi Sukses --}}
                    @if (session('success'))
                        <div class="mb-6 flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50/90 p-4 text-xs sm:text-sm text-emerald-900 shadow-xs">
                            <div class="flex size-7 shrink-0 items-center justify-center rounded-lg bg-emerald-600 text-white">
                                <i class="fa-solid fa-circle-check text-xs"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-emerald-950">Berhasil Memperbarui Kata Sandi!</p>
                                <p class="mt-0.5 text-xs text-emerald-800 leading-relaxed">{{ session('success') }}</p>
                            </div>
                        </div>
                    @endif

                    {{-- Alert Kesalahan Umum --}}
                    @if ($errors->any())
                        <div class="mb-6 flex items-start gap-3 rounded-xl border border-rose-200 bg-rose-50/90 p-4 text-xs sm:text-sm text-rose-900 shadow-xs">
                            <div class="flex size-7 shrink-0 items-center justify-center rounded-lg bg-rose-600 text-white">
                                <i class="fa-solid fa-triangle-exclamation text-xs"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-rose-950">Periksa Kembali Data yang Anda Masukkan</p>
                                <ul class="mt-1 list-disc list-inside space-y-0.5 text-xs text-rose-800">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('password.update') }}" method="POST" class="space-y-5" novalidate id="form-change-password">
                        @csrf
                        @method('PUT')

                        {{-- 1. Kata Sandi Lama (Wajib diverifikasi) --}}
                        <div>
                            <div class="flex items-center justify-between">
                                <label for="password_lama" class="block text-xs font-semibold text-ink sm:text-sm">
                                    Kata Sandi Saat Ini <span class="text-rose-600">*</span>
                                </label>
                                <span class="text-[11px] text-muted">Wajib verifikasi keamanan</span>
                            </div>
                            <div class="relative mt-1.5">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted">
                                    <i class="fa-solid fa-shield-halved text-xs"></i>
                                </span>
                                <input
                                    type="password"
                                    name="password_lama"
                                    id="password_lama"
                                    required
                                    autocomplete="current-password"
                                    placeholder="Masukkan kata sandi lama Anda..."
                                    class="h-11 w-full rounded-xl border {{ $errors->has('password_lama') ? 'border-rose-500 bg-rose-50/30 ring-1 ring-rose-500' : 'border-line bg-subtle/40 focus:border-primary focus:bg-white focus:ring-1 focus:ring-primary' }} pr-10 pl-10 text-xs text-ink placeholder:text-muted/70 transition-all sm:text-sm"
                                >
                                <button
                                    type="button"
                                    onclick="togglePasswordVisibility('password_lama', 'btn-icon-old')"
                                    class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-muted transition-colors hover:text-ink"
                                    aria-label="Tampilkan atau sembunyikan kata sandi lama"
                                >
                                    <i id="btn-icon-old" class="fa-regular fa-eye text-xs"></i>
                                </button>
                            </div>
                            @error('password_lama')
                                <p class="mt-1.5 flex items-center gap-1.5 text-xs font-medium text-rose-600">
                                    <i class="fa-solid fa-circle-exclamation text-[11px]"></i>
                                    <span>{{ $message }}</span>
                                </p>
                            @else
                                <p class="mt-1.5 text-[11px] text-muted">
                                    Masukkan kata sandi yang saat ini digunakan untuk membuka akun Anda.
                                </p>
                            @enderror
                        </div>

                        {{-- Garis Pemisah Halus --}}
                        <div class="pt-2 border-t border-line/60"></div>

                        {{-- 2. Kata Sandi Baru --}}
                        <div>
                            <label for="password_baru" class="block text-xs font-semibold text-ink sm:text-sm">
                                Kata Sandi Baru <span class="text-rose-600">*</span>
                            </label>
                            <div class="relative mt-1.5">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted">
                                    <i class="fa-solid fa-lock text-xs"></i>
                                </span>
                                <input
                                    type="password"
                                    name="password_baru"
                                    id="password_baru"
                                    required
                                    minlength="6"
                                    autocomplete="new-password"
                                    placeholder="Minimal 6 karakter kombinasi..."
                                    oninput="checkPasswordStrength(this.value)"
                                    class="h-11 w-full rounded-xl border {{ $errors->has('password_baru') ? 'border-rose-500 bg-rose-50/30 ring-1 ring-rose-500' : 'border-line bg-subtle/40 focus:border-primary focus:bg-white focus:ring-1 focus:ring-primary' }} pr-10 pl-10 text-xs text-ink placeholder:text-muted/70 transition-all sm:text-sm"
                                >
                                <button
                                    type="button"
                                    onclick="togglePasswordVisibility('password_baru', 'btn-icon-new')"
                                    class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-muted transition-colors hover:text-ink"
                                    aria-label="Tampilkan atau sembunyikan kata sandi baru"
                                >
                                    <i id="btn-icon-new" class="fa-regular fa-eye text-xs"></i>
                                </button>
                            </div>

                            {{-- Indikator Kekuatan Kata Sandi (Strength Meter) --}}
                            <div class="mt-2.5">
                                <div class="flex items-center justify-between text-[11px] text-muted">
                                    <span>Kekuatan kata sandi:</span>
                                    <span id="strength-label" class="font-semibold text-muted">Belum diisi</span>
                                </div>
                                <div class="mt-1 grid grid-cols-4 gap-1.5">
                                    <div id="meter-1" class="h-1.5 rounded-full bg-slate-200 transition-all duration-300"></div>
                                    <div id="meter-2" class="h-1.5 rounded-full bg-slate-200 transition-all duration-300"></div>
                                    <div id="meter-3" class="h-1.5 rounded-full bg-slate-200 transition-all duration-300"></div>
                                    <div id="meter-4" class="h-1.5 rounded-full bg-slate-200 transition-all duration-300"></div>
                                </div>
                            </div>

                            @error('password_baru')
                                <p class="mt-1.5 flex items-center gap-1.5 text-xs font-medium text-rose-600">
                                    <i class="fa-solid fa-circle-exclamation text-[11px]"></i>
                                    <span>{{ $message }}</span>
                                </p>
                            @enderror
                        </div>

                        {{-- 3. Konfirmasi Kata Sandi Baru --}}
                        <div>
                            <div class="flex items-center justify-between">
                                <label for="password_baru_confirmation" class="block text-xs font-semibold text-ink sm:text-sm">
                                    Konfirmasi Kata Sandi Baru <span class="text-rose-600">*</span>
                                </label>
                                <span id="match-status" class="hidden text-[11px] font-semibold"></span>
                            </div>
                            <div class="relative mt-1.5">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted">
                                    <i class="fa-solid fa-check-double text-xs"></i>
                                </span>
                                <input
                                    type="password"
                                    name="password_baru_confirmation"
                                    id="password_baru_confirmation"
                                    required
                                    minlength="6"
                                    autocomplete="new-password"
                                    placeholder="Ulangi kata sandi baru..."
                                    oninput="checkPasswordMatch()"
                                    class="h-11 w-full rounded-xl border border-line bg-subtle/40 pr-10 pl-10 text-xs text-ink placeholder:text-muted/70 transition-all focus:border-primary focus:bg-white focus:ring-1 focus:ring-primary sm:text-sm"
                                >
                                <button
                                    type="button"
                                    onclick="togglePasswordVisibility('password_baru_confirmation', 'btn-icon-confirm')"
                                    class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-muted transition-colors hover:text-ink"
                                    aria-label="Tampilkan atau sembunyikan konfirmasi kata sandi baru"
                                >
                                    <i id="btn-icon-confirm" class="fa-regular fa-eye text-xs"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="flex flex-col-reverse gap-3 pt-5 border-t border-line sm:flex-row sm:items-center sm:justify-end">
                            <a
                                href="{{ route('beranda') }}"
                                class="inline-flex h-11 items-center justify-center rounded-xl border border-line bg-white px-5 text-xs font-semibold text-ink-secondary transition-all hover:bg-subtle hover:text-ink sm:text-sm"
                            >
                                <i class="fa-solid fa-arrow-left mr-2 text-xs"></i>
                                <span>Kembali ke Beranda</span>
                            </a>
                            <button
                                type="submit"
                                class="inline-flex h-11 items-center justify-center gap-2 rounded-xl bg-primary px-6 text-xs font-semibold text-white shadow-sm transition-all hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 sm:text-sm"
                            >
                                <i class="fa-solid fa-key text-xs"></i>
                                <span>Simpan Kata Sandi Baru</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Kolom Kanan: Profil Pemustaka & Panduan Keamanan (5 Kolom Desktop) --}}
            <div class="space-y-6 lg:col-span-5">
                
                {{-- Kartu Ringkasan Akun Pemustaka --}}
                <div class="rounded-2xl border border-line bg-white p-6 shadow-card">
                    <div class="flex items-center gap-3 border-b border-line pb-4">
                        <div class="flex size-11 items-center justify-center rounded-xl bg-primary/10 text-primary font-bold text-base">
                            {{ strtoupper(substr($user->nama_lengkap, 0, 1)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-bold text-ink sm:text-base">{{ $user->nama_lengkap }}</p>
                            <p class="truncate text-xs text-muted">{{ $user->email }}</p>
                        </div>
                    </div>

                    <dl class="mt-4 space-y-3 text-xs">
                        <div class="flex items-center justify-between">
                            <dt class="text-muted">Peran Akun:</dt>
                            <dd>
                                @php
                                    $roleBadge = match ($user->peran?->nama_peran) {
                                        'Dosen' => 'bg-purple-100 text-purple-800 border-purple-200',
                                        'Mahasiswa' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                        'Pustakawan' => 'bg-blue-100 text-blue-800 border-blue-200',
                                        'Kepala Perpustakaan', 'Super Admin' => 'bg-amber-100 text-amber-800 border-amber-200',
                                        default => 'bg-slate-100 text-slate-800 border-slate-200',
                                    };
                                @endphp
                                <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 font-medium {{ $roleBadge }}">
                                    {{ $user->peran?->nama_peran ?? 'Pemustaka' }}
                                </span>
                            </dd>
                        </div>

                        <div class="flex items-center justify-between">
                            <dt class="text-muted">ID Pemustaka:</dt>
                            <dd class="font-mono font-semibold text-primary">{{ $user->id_pengguna }}</dd>
                        </div>

                        <div class="flex items-center justify-between">
                            <dt class="text-muted">Nomor Identitas (NIM/NIDN):</dt>
                            <dd class="font-mono text-ink">{{ $user->nomor_identitas ?? '-' }}</dd>
                        </div>

                        <div class="flex items-center justify-between">
                            <dt class="text-muted">Program Studi:</dt>
                            <dd class="font-medium text-ink">{{ $user->prodi?->nama_prodi ?? 'Umum / Pusat' }}</dd>
                        </div>
                    </dl>
                </div>

                {{-- Panduan Kebijakan Kata Sandi Aman --}}
                <div class="rounded-2xl border border-line bg-gradient-to-br from-white via-white to-subtle/60 p-6 shadow-card">
                    <div class="flex items-center gap-2.5 text-ink font-semibold text-xs sm:text-sm">
                        <div class="flex size-7 items-center justify-center rounded-lg bg-amber-500/10 text-amber-600">
                            <i class="fa-solid fa-lightbulb text-xs"></i>
                        </div>
                        <span>Kriteria Kata Sandi yang Baik</span>
                    </div>

                    <ul class="mt-4 space-y-2.5 text-xs text-ink-secondary leading-relaxed">
                        <li class="flex items-start gap-2">
                            <i class="fa-solid fa-circle-check mt-1 text-emerald-600 text-[10px]"></i>
                            <span>Panjang minimal <strong>6 karakter</strong> (disarankan 8 karakter atau lebih).</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fa-solid fa-circle-check mt-1 text-emerald-600 text-[10px]"></i>
                            <span>Kombinasikan huruf besar, huruf kecil, dan angka (misalnya: <code>Mnc2026!</code>).</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fa-solid fa-circle-check mt-1 text-emerald-600 text-[10px]"></i>
                            <span>Jangan gunakan kata sandi yang sama dengan kata sandi lama Anda.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fa-solid fa-circle-check mt-1 text-emerald-600 text-[10px]"></i>
                            <span>Jaga kerahasiaan kata sandi Anda dan jangan berikan kepada pihak mana pun.</span>
                        </li>
                    </ul>

                    <div class="mt-5 rounded-xl border border-primary/20 bg-primary/5 p-3 text-[11px] text-ink-secondary">
                        <p class="font-medium text-primary">Butuh bantuan pengaturan akun?</p>
                        <p class="mt-0.5">Kunjungi meja sirkulasi atau hubungi <a href="{{ route('chat') }}" class="font-semibold text-primary underline underline-offset-2">Layanan Pustakawan</a>.</p>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Script Interaktivitas Form --}}
    <script>
        function togglePasswordVisibility(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (!input || !icon) return;

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        function checkPasswordStrength(password) {
            const label = document.getElementById('strength-label');
            const meters = [
                document.getElementById('meter-1'),
                document.getElementById('meter-2'),
                document.getElementById('meter-3'),
                document.getElementById('meter-4')
            ];

            // Reset all meters
            meters.forEach(m => {
                m.className = 'h-1.5 rounded-full bg-slate-200 transition-all duration-300';
            });

            if (!password) {
                label.textContent = 'Belum diisi';
                label.className = 'font-semibold text-muted';
                return;
            }

            let score = 0;
            if (password.length >= 6) score++;
            if (password.length >= 8) score++;
            if (/[0-9]/.test(password)) score++;
            if (/[^A-Za-z0-9]/.test(password) || (/[A-Z]/.test(password) && /[a-z]/.test(password))) score++;

            if (score <= 1) {
                label.textContent = 'Lemah';
                label.className = 'font-semibold text-rose-600';
                meters[0].classList.replace('bg-slate-200', 'bg-rose-500');
            } else if (score === 2) {
                label.textContent = 'Cukup';
                label.className = 'font-semibold text-amber-600';
                meters[0].classList.replace('bg-slate-200', 'bg-amber-500');
                meters[1].classList.replace('bg-slate-200', 'bg-amber-500');
            } else if (score === 3) {
                label.textContent = 'Baik';
                label.className = 'font-semibold text-blue-600';
                meters[0].classList.replace('bg-slate-200', 'bg-blue-500');
                meters[1].classList.replace('bg-slate-200', 'bg-blue-500');
                meters[2].classList.replace('bg-slate-200', 'bg-blue-500');
            } else {
                label.textContent = 'Sangat Kuat';
                label.className = 'font-semibold text-emerald-600';
                meters.forEach(m => m.classList.replace('bg-slate-200', 'bg-emerald-500'));
            }

            checkPasswordMatch();
        }

        function checkPasswordMatch() {
            const newPassword = document.getElementById('password_baru')?.value || '';
            const confirmPassword = document.getElementById('password_baru_confirmation')?.value || '';
            const statusEl = document.getElementById('match-status');
            if (!statusEl) return;

            if (!confirmPassword) {
                statusEl.classList.add('hidden');
                return;
            }

            statusEl.classList.remove('hidden');
            if (newPassword === confirmPassword) {
                statusEl.textContent = '✓ Kata sandi cocok';
                statusEl.className = 'text-[11px] font-semibold text-emerald-600';
            } else {
                statusEl.textContent = '✗ Belum cocok';
                statusEl.className = 'text-[11px] font-semibold text-rose-600';
            }
        }
    </script>
@endsection
