@extends('layouts.app')

@section('title', 'Profil Saya - ' . ($user->nama_lengkap ?? 'Pengguna'))
@section('description', 'Informasi profil dan kartu anggota digital pemustaka Perpustakaan MNC University.')

@section('content')
<div class="min-h-screen bg-slate-50/70 py-8 lg:py-12">
    <div class="mx-auto w-full max-w-5xl px-4 sm:px-6 lg:px-8">
        {{-- Breadcrumbs --}}
        <nav class="mb-4 flex items-center gap-2 text-xs font-medium text-muted sm:text-sm" aria-label="Breadcrumb">
            <a href="{{ route('beranda') }}" class="inline-flex min-h-[44px] items-center text-muted transition-colors hover:text-primary">
                <i class="fa-solid fa-house mr-1.5 text-xs text-slate-400" aria-hidden="true"></i>
                <span>Beranda</span>
            </a>
            <i class="fa-solid fa-chevron-right text-[10px] text-slate-400" aria-hidden="true"></i>
            <span class="inline-flex min-h-[44px] items-center font-semibold text-ink" aria-current="page">Profil Pengguna</span>
        </nav>

        {{-- Alert Notifikasi Sukses / Feedback --}}
        @if (session('success'))
            <div class="mb-6 flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50/90 p-4 text-xs sm:text-sm text-emerald-900 shadow-xs" role="alert">
                <div class="flex size-7 shrink-0 items-center justify-center rounded-lg bg-emerald-600 text-white">
                    <i class="fa-solid fa-circle-check text-xs" aria-hidden="true"></i>
                </div>
                <div class="flex-1">
                    <p class="font-bold text-emerald-950">Berhasil!</p>
                    <p class="mt-0.5 text-xs text-emerald-800 leading-relaxed">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 flex items-start gap-3 rounded-2xl border border-rose-200 bg-rose-50/90 p-4 text-xs sm:text-sm text-rose-900 shadow-xs" role="alert">
                <div class="flex size-7 shrink-0 items-center justify-center rounded-lg bg-rose-600 text-white">
                    <i class="fa-solid fa-triangle-exclamation text-xs" aria-hidden="true"></i>
                </div>
                <div class="flex-1">
                    <p class="font-bold text-rose-950">Periksa Kembali Input Anda</p>
                    <ul class="mt-1 list-disc list-inside space-y-0.5 text-xs text-rose-800">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        {{-- Page Header & Sub Navigation Tabs --}}
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between border-b border-line pb-6">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-ink sm:text-3xl">Profil Pemustaka</h1>
                <p class="mt-1 text-xs text-muted sm:text-sm">Kartu anggota resmi dan rincian identitas terdaftar di Perpustakaan MNC University.</p>
            </div>
            <div class="flex items-center gap-2">
                {{-- Segmented Navigation Tab --}}
                <div class="inline-flex rounded-xl bg-slate-200/70 p-1 text-xs font-medium">
                    <span class="inline-flex min-h-[40px] items-center gap-1.5 rounded-lg bg-white px-3.5 py-1.5 font-semibold text-primary shadow-sm">
                        <i class="fa-solid fa-id-card text-xs" aria-hidden="true"></i>
                        <span>Ringkasan</span>
                    </span>
                    <a href="{{ route('profile.edit') }}" class="inline-flex min-h-[40px] items-center gap-1.5 rounded-lg px-3.5 py-1.5 text-slate-600 transition-colors hover:text-ink hover:bg-white/60">
                        <i class="fa-solid fa-user-pen text-xs" aria-hidden="true"></i>
                        <span>Edit Profil</span>
                    </a>
                    <button type="button" onclick="openProfileResetModal()" class="inline-flex min-h-[40px] items-center gap-1.5 rounded-lg px-3.5 py-1.5 text-slate-600 transition-colors hover:text-ink hover:bg-white/60">
                        <i class="fa-solid fa-key text-xs" aria-hidden="true"></i>
                        <span>Reset Password</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- 1. Kartu Anggota Perpustakaan Digital (Digital Library Card) --}}
        <div class="mb-8 overflow-hidden rounded-2xl border border-line bg-white shadow-card">
            {{-- Header Kartu Digital --}}
            <div class="relative bg-gradient-to-r from-[#0F2A6B] via-[#143A8F] to-[#0A1E4F] px-6 py-5 text-white">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('images/Logo.png') }}" alt="Logo MNC University" class="size-10 rounded-lg bg-white p-1 object-contain shadow-sm">
                        <div>
                            <p class="text-[11px] font-semibold uppercase tracking-wider text-blue-200">Kartu Anggota Pemustaka</p>
                            <h2 class="text-base font-bold sm:text-lg">Perpustakaan MNC University</h2>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 self-start sm:self-center">
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-500/20 px-3 py-1 text-xs font-semibold text-emerald-200 ring-1 ring-inset ring-emerald-400/40">
                            <i class="fa-solid fa-circle-check text-[10px]" aria-hidden="true"></i>
                            <span>Status: Aktif</span>
                        </span>
                    </div>
                </div>
            </div>

            {{-- Badan Kartu Digital --}}
            <div class="p-6 sm:p-8">
                <div class="flex flex-col items-center gap-6 sm:flex-row sm:items-start sm:gap-8">
                    {{-- Foto Profil / Avatar Fallback --}}
                    <div class="relative shrink-0">
                        <div class="size-32 overflow-hidden rounded-2xl border-4 border-white bg-slate-100 shadow-md ring-1 ring-slate-200 sm:size-36">
                            @if ($user->foto_profil)
                                <img src="{{ asset('storage/' . $user->foto_profil) }}" alt="{{ $user->nama_lengkap }}" class="size-full object-cover">
                            @else
                                <div class="flex size-full flex-col items-center justify-center bg-primary/10 text-primary">
                                    <span class="text-4xl font-extrabold">{{ strtoupper(substr($user->nama_lengkap ?? 'U', 0, 1)) }}</span>
                                    <span class="mt-1 text-[10px] font-semibold uppercase tracking-wider text-primary/70">Pemustaka</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Data Ringkas Pemustaka --}}
                    <div class="flex-1 text-center sm:text-left">
                        <div class="flex flex-wrap items-center justify-center gap-2 sm:justify-start">
                            <h3 class="text-xl font-bold text-ink sm:text-2xl">{{ $user->nama_lengkap ?? 'Pengguna Perpustakaan' }}</h3>
                            @if ($role === 'mahasiswa')
                                <span class="inline-flex items-center gap-1.5 rounded-lg bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-800 border border-blue-200">
                                    <i class="fa-solid fa-graduation-cap" aria-hidden="true"></i> Mahasiswa
                                </span>
                            @elseif ($role === 'dosen')
                                <span class="inline-flex items-center gap-1.5 rounded-lg bg-indigo-50 px-2.5 py-1 text-xs font-semibold text-indigo-800 border border-indigo-200">
                                    <i class="fa-solid fa-chalkboard-user" aria-hidden="true"></i> Dosen
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 rounded-lg bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-800 border border-slate-300">
                                    <i class="fa-solid fa-shield-halved" aria-hidden="true"></i> Administrator
                                </span>
                            @endif
                        </div>

                        <p class="mt-1 text-xs font-medium text-muted sm:text-sm">{{ $user->email }}</p>

                        {{-- Metadata Grid Sejajar & Bergaris --}}
                        <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-3 border-t border-line pt-4 text-left divide-y sm:divide-y-0 sm:divide-x divide-line/60">
                            <div class="sm:pr-4">
                                <span class="block text-[11px] font-medium text-muted">Nomor Identitas</span>
                                <span class="mt-0.5 block text-xs font-bold text-ink sm:text-sm">
                                    @if ($role === 'mahasiswa')
                                        {{ $user->nim ?? $user->nomor_identitas ?? 'Belum diisi' }}
                                    @elseif ($role === 'dosen')
                                        {{ $user->nidn ?? $user->nomor_identitas ?? 'Belum diisi' }}
                                    @else
                                        {{ $user->nomor_identitas ?? 'STAF-MNCU' }}
                                    @endif
                                </span>
                            </div>
                            <div class="pt-2 sm:pt-0 sm:px-4">
                                <span class="block text-[11px] font-medium text-muted">Masa Berlaku</span>
                                <span class="mt-0.5 block text-xs font-semibold text-ink sm:text-sm">Semester Berjalan</span>
                            </div>
                            <div class="pt-2 sm:pt-0 sm:pl-4">
                                <span class="block text-[11px] font-medium text-muted">Terdaftar Sejak</span>
                                <span class="mt-0.5 block text-xs font-semibold text-ink sm:text-sm">
                                    {{ $user->created_at ? $user->created_at->format('d M Y') : '16 Okt 2026' }}
                                </span>
                            </div>
                        </div>

                        {{-- Barcode Simulasi Anggota --}}
                        <div class="mt-5 flex flex-col items-center justify-between gap-3 rounded-xl bg-slate-50 p-3.5 border border-line sm:flex-row">
                            <div class="flex items-center gap-3">
                                <i class="fa-solid fa-barcode text-3xl text-slate-700" aria-hidden="true"></i>
                                <div>
                                    <span class="block text-[10px] font-bold uppercase tracking-wider text-muted">ID Anggota Digital</span>
                                    <span class="font-mono text-xs font-bold text-ink sm:text-sm tracking-wider">
                                        MNCU-LIB-{{ str_pad((string)($user->id ?? 1), 5, '0', STR_PAD_LEFT) }}
                                    </span>
                                </div>
                            </div>
                            <a href="{{ route('profile.edit') }}" class="inline-flex min-h-[44px] items-center gap-2 rounded-xl bg-primary px-4 py-2 text-xs font-semibold text-white shadow-sm transition-all hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-primary/20">
                                <i class="fa-solid fa-user-pen" aria-hidden="true"></i>
                                <span>Perbarui Data Diri</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. Detail Data & Akses Cepat --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            {{-- Kolom Kiri: Layanan & Akses Cepat --}}
            <div class="space-y-6 lg:col-span-1">
                {{-- Kartu Hak Akses Layanan (Tertata Sejajar & Rapi) --}}
                <div class="overflow-hidden rounded-2xl border border-line bg-white shadow-card">
                    <div class="border-b border-line bg-slate-50/70 px-5 py-3.5">
                        <h2 class="text-xs font-bold uppercase tracking-wider text-muted flex items-center gap-2">
                            <i class="fa-solid fa-id-badge text-primary" aria-hidden="true"></i>
                            <span>Hak Akses Layanan</span>
                        </h2>
                    </div>
                    <div class="p-5 divide-y divide-line/60">
                        <div class="py-3 first:pt-0 flex items-center justify-between gap-3 text-xs">
                            <span class="font-medium text-slate-700">Peminjaman Buku</span>
                            <span class="font-semibold text-ink whitespace-nowrap">3 Buku / 7 Hari</span>
                        </div>
                        <div class="py-3 flex items-center justify-between gap-3 text-xs">
                            <span class="font-medium text-slate-700">Repository & Jurnal</span>
                            <span class="inline-flex items-center gap-1 font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200 whitespace-nowrap">
                                <i class="fa-solid fa-check text-[10px]" aria-hidden="true"></i>
                                <span>Tersedia</span>
                            </span>
                        </div>
                        <div class="py-3 last:pb-0 flex items-center justify-between gap-3 text-xs">
                            <span class="font-medium text-slate-700">Ruang Baca & Lab</span>
                            <span class="inline-flex items-center gap-1 font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200 whitespace-nowrap">
                                <i class="fa-solid fa-check text-[10px]" aria-hidden="true"></i>
                                <span>Aktif</span>
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Akses Cepat Perpustakaan --}}
                <div class="overflow-hidden rounded-2xl border border-line bg-white shadow-card">
                    <div class="border-b border-line bg-slate-50/70 px-5 py-3.5">
                        <h2 class="text-xs font-bold uppercase tracking-wider text-muted flex items-center gap-2">
                            <i class="fa-solid fa-compass text-primary" aria-hidden="true"></i>
                            <span>Menu Cepat Pemustaka</span>
                        </h2>
                    </div>
                    <div class="p-3 space-y-1">
                        <a href="{{ route('opac') }}" class="flex min-h-[44px] items-center justify-between rounded-xl p-3 text-xs font-medium text-ink transition-colors hover:bg-slate-50">
                            <span class="flex items-center gap-3">
                                <i class="fa-solid fa-book-open text-primary" aria-hidden="true"></i>
                                <span>Pencarian Buku (OPAC)</span>
                            </span>
                            <i class="fa-solid fa-chevron-right text-[10px] text-slate-400" aria-hidden="true"></i>
                        </a>
                        <a href="{{ route('chat') }}" class="flex min-h-[44px] items-center justify-between rounded-xl p-3 text-xs font-medium text-ink transition-colors hover:bg-slate-50">
                            <span class="flex items-center gap-3">
                                <i class="fa-solid fa-comments text-primary" aria-hidden="true"></i>
                                <span>Chatbot & Tanya Pustakawan</span>
                            </span>
                            <i class="fa-solid fa-chevron-right text-[10px] text-slate-400" aria-hidden="true"></i>
                        </a>
                        <button type="button" onclick="openProfileResetModal()" class="flex min-h-[44px] w-full items-center justify-between rounded-xl p-3 text-xs font-medium text-ink transition-colors hover:bg-slate-50 text-left">
                            <span class="flex items-center gap-3">
                                <i class="fa-solid fa-key text-primary" aria-hidden="true"></i>
                                <span>Reset Kata Sandi Akun</span>
                            </span>
                            <i class="fa-solid fa-chevron-right text-[10px] text-slate-400" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Kolom Kanan: Rincian Lengkap Data Pemustaka (Tata Letak Sejajar & Lurus) --}}
            <div class="space-y-6 lg:col-span-2">
                <div class="overflow-hidden rounded-2xl border border-line bg-white shadow-card">
                    {{-- Header Rincian Data Lengkap --}}
                    <div class="border-b border-line bg-slate-50/70 px-6 py-4 flex items-center justify-between">
                        <div>
                            <h2 class="text-sm font-bold text-ink sm:text-base flex items-center gap-2">
                                <i class="fa-solid fa-address-book text-primary" aria-hidden="true"></i>
                                <span>Rincian Data Lengkap Pemustaka</span>
                            </h2>
                            <p class="mt-0.5 text-xs text-muted">Informasi identitas akademik, data pribadi, dan kontak resmi terdaftar.</p>
                        </div>
                        <a href="{{ route('profile.edit') }}" class="inline-flex min-h-[38px] items-center gap-1.5 rounded-lg border border-line bg-white px-3.5 py-1.5 text-xs font-semibold text-primary shadow-sm hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-primary/20">
                            <i class="fa-solid fa-user-pen" aria-hidden="true"></i>
                            <span>Ubah Data</span>
                        </a>
                    </div>

                    {{-- Tabel Data Bergaris Sejajar & Lurus --}}
                    <div class="divide-y divide-line/70">
                        {{-- 1. Identitas Khusus Peran --}}
                        @if ($role === 'mahasiswa')
                            <div class="flex flex-col py-3.5 px-6 sm:flex-row sm:items-center">
                                <span class="w-full sm:w-56 shrink-0 text-xs font-medium text-muted">Nomor Induk Mahasiswa (NIM)</span>
                                <span class="mt-1 sm:mt-0 flex-1 text-xs sm:text-sm font-bold text-ink">
                                    {{ $user->nim ?? $user->nomor_identitas ?? 'Belum diisi' }}
                                </span>
                            </div>
                            <div class="flex flex-col py-3.5 px-6 sm:flex-row sm:items-center">
                                <span class="w-full sm:w-56 shrink-0 text-xs font-medium text-muted">Status Kemahasiswaan</span>
                                <span class="mt-1 sm:mt-0 flex-1 text-xs sm:text-sm font-semibold text-emerald-700 flex items-center gap-1.5">
                                    <i class="fa-solid fa-circle-check text-xs" aria-hidden="true"></i>
                                    <span>Mahasiswa Aktif MNC University</span>
                                </span>
                            </div>
                        @elseif ($role === 'dosen')
                            <div class="flex flex-col py-3.5 px-6 sm:flex-row sm:items-center">
                                <span class="w-full sm:w-56 shrink-0 text-xs font-medium text-muted">Nomor Induk Dosen Nasional (NIDN)</span>
                                <span class="mt-1 sm:mt-0 flex-1 text-xs sm:text-sm font-bold text-ink">
                                    {{ $user->nidn ?? $user->nomor_identitas ?? 'Belum diisi' }}
                                </span>
                            </div>
                            <div class="flex flex-col py-3.5 px-6 sm:flex-row sm:items-center">
                                <span class="w-full sm:w-56 shrink-0 text-xs font-medium text-muted">Status Tenaga Pendidik</span>
                                <span class="mt-1 sm:mt-0 flex-1 text-xs sm:text-sm font-semibold text-indigo-700 flex items-center gap-1.5">
                                    <i class="fa-solid fa-circle-check text-xs" aria-hidden="true"></i>
                                    <span>Dosen / Peneliti MNC University</span>
                                </span>
                            </div>
                        @elseif ($role === 'admin')
                            <div class="flex flex-col py-3.5 px-6 sm:flex-row sm:items-center">
                                <span class="w-full sm:w-56 shrink-0 text-xs font-medium text-muted">Hak Akses Sistem</span>
                                <span class="mt-1 sm:mt-0 flex-1 text-xs sm:text-sm font-semibold text-blue-800 flex items-center gap-1.5">
                                    <i class="fa-solid fa-shield-halved text-blue-600" aria-hidden="true"></i>
                                    <span>Administrator Perpustakaan (Hak Akses Penuh)</span>
                                </span>
                            </div>
                        @endif

                        {{-- 2. Data Pribadi & Kontak --}}
                        <div class="flex flex-col py-3.5 px-6 sm:flex-row sm:items-center">
                            <span class="w-full sm:w-56 shrink-0 text-xs font-medium text-muted">Nama Lengkap</span>
                            <span class="mt-1 sm:mt-0 flex-1 text-xs sm:text-sm font-semibold text-ink">
                                {{ $user->nama_lengkap ?? 'Belum dilengkapi' }}
                            </span>
                        </div>

                        <div class="flex flex-col py-3.5 px-6 sm:flex-row sm:items-center">
                            <span class="w-full sm:w-56 shrink-0 text-xs font-medium text-muted">Alamat Email Resmi</span>
                            <span class="mt-1 sm:mt-0 flex-1 text-xs sm:text-sm font-semibold text-ink">
                                {{ $user->email }}
                            </span>
                        </div>

                        <div class="flex flex-col py-3.5 px-6 sm:flex-row sm:items-center">
                            <span class="w-full sm:w-56 shrink-0 text-xs font-medium text-muted">Nomor WhatsApp / Telepon</span>
                            <span class="mt-1 sm:mt-0 flex-1 text-xs sm:text-sm font-semibold text-ink flex items-center gap-1.5">
                                @if ($user->no_wa)
                                    <i class="fa-brands fa-whatsapp text-emerald-600 text-sm" aria-hidden="true"></i>
                                    <span>{{ $user->no_wa }}</span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-xs text-amber-800 bg-amber-50 px-2 py-0.5 rounded border border-amber-200">
                                        Belum dilengkapi
                                    </span>
                                @endif
                            </span>
                        </div>

                        <div class="flex flex-col py-3.5 px-6 sm:flex-row sm:items-center">
                            <span class="w-full sm:w-56 shrink-0 text-xs font-medium text-muted">Nomor Induk Kependudukan (NIK)</span>
                            <span class="mt-1 sm:mt-0 flex-1 text-xs sm:text-sm font-semibold text-ink">
                                @if ($user->nik)
                                    <span>{{ $user->nik }}</span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-xs text-amber-800 bg-amber-50 px-2 py-0.5 rounded border border-amber-200">
                                        Belum dilengkapi
                                    </span>
                                @endif
                            </span>
                        </div>

                        <div class="flex flex-col py-3.5 px-6 sm:flex-row sm:items-center">
                            <span class="w-full sm:w-56 shrink-0 text-xs font-medium text-muted">Tempat & Tanggal Lahir</span>
                            <span class="mt-1 sm:mt-0 flex-1 text-xs sm:text-sm font-semibold text-ink">
                                @if ($user->tempat_lahir || $user->tanggal_lahir)
                                    <span>{{ $user->tempat_lahir ?? '-' }}, {{ $user->tanggal_lahir ? $user->tanggal_lahir->format('d F Y') : '-' }}</span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-xs text-amber-800 bg-amber-50 px-2 py-0.5 rounded border border-amber-200">
                                        Belum dilengkapi
                                    </span>
                                @endif
                            </span>
                        </div>

                        <div class="flex flex-col py-3.5 px-6 sm:flex-row sm:items-start">
                            <span class="w-full sm:w-56 shrink-0 text-xs font-medium text-muted pt-0.5">Alamat Domisili Lengkap</span>
                            <div class="mt-1 sm:mt-0 flex-1 text-xs sm:text-sm font-medium text-ink leading-relaxed">
                                @if ($user->alamat_lengkap)
                                    <span>{{ $user->alamat_lengkap }}</span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-xs text-amber-800 bg-amber-50 px-2 py-0.5 rounded border border-amber-200">
                                        Belum dilengkapi
                                    </span>
                                    <span class="block mt-1 text-xs text-muted">
                                        Alamat domisili diperlukan untuk verifikasi sirkulasi peminjaman buku fisik.
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ========================================================================= --}}
{{-- MODAL RESET PASSWORD LANGSUNG (TANPA PASSWORD LAMA, 2X INPUT)             --}}
{{-- ========================================================================= --}}
<div
    id="profile-reset-password-modal"
    class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-xs transition-opacity duration-200"
    role="dialog"
    aria-modal="true"
    aria-labelledby="modal-reset-profile-title"
>
    <div class="flex min-h-screen items-center justify-center p-4 sm:p-6">
        <div class="relative w-full max-w-lg overflow-hidden rounded-2xl border border-line bg-white shadow-2xl transition-all">
            {{-- Header Modal --}}
            <div class="flex items-center justify-between border-b border-line bg-slate-50/70 px-6 py-4">
                <div class="flex items-center gap-3">
                    <span class="flex size-9 items-center justify-center rounded-xl bg-primary/10 text-primary">
                        <i class="fa-solid fa-key text-sm" aria-hidden="true"></i>
                    </span>
                    <div>
                        <h3 id="modal-reset-profile-title" class="text-sm font-bold text-ink sm:text-base">Reset Kata Sandi Akun</h3>
                        <p class="text-xs text-muted">Perbarui kata sandi langsung tanpa perlu kata sandi lama.</p>
                    </div>
                </div>
                <button
                    type="button"
                    onclick="closeProfileResetModal()"
                    class="inline-flex size-8 items-center justify-center rounded-lg text-slate-400 hover:bg-slate-200/60 hover:text-slate-700 focus:outline-none focus:ring-2 focus:ring-primary/20"
                    aria-label="Tutup jendela reset kata sandi"
                >
                    <i class="fa-solid fa-xmark text-sm" aria-hidden="true"></i>
                </button>
            </div>

            {{-- Isi Formulir Modal --}}
            <form action="{{ route('profile.password.update') }}" method="POST" class="p-6 space-y-5">
                @csrf
                @method('PUT')

                {{-- Alert Info: Tanpa Password Lama --}}
                <div class="flex items-start gap-3 rounded-xl border border-blue-200 bg-blue-50/80 p-3.5 text-xs text-blue-900">
                    <i class="fa-solid fa-circle-info text-blue-600 mt-0.5 shrink-0" aria-hidden="true"></i>
                    <p class="leading-relaxed">
                        Anda dapat langsung membuat kata sandi baru. Masukkan kata sandi baru sebanyak <strong>2 kali</strong> pada kolom di bawah ini untuk verifikasi.
                    </p>
                </div>

                {{-- 1. Kata Sandi Baru --}}
                <div class="space-y-1.5">
                    <label for="modal_password_baru" class="block text-xs font-bold text-ink sm:text-sm">
                        Kata Sandi Baru <span class="text-rose-500" aria-hidden="true">*</span>
                    </label>
                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted">
                            <i class="fa-solid fa-lock text-xs" aria-hidden="true"></i>
                        </span>
                        <input
                            type="password"
                            name="password_baru"
                            id="modal_password_baru"
                            required
                            minlength="6"
                            placeholder="Minimal 6 karakter kombinasi..."
                            class="block w-full min-h-[44px] rounded-xl border border-line pl-10 pr-10 text-xs font-medium text-ink placeholder:text-muted/70 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 sm:text-sm"
                            oninput="checkModalPasswordMatch()"
                        >
                        <button
                            type="button"
                            onclick="toggleModalPassword('modal_password_baru', 'modal-eye-new')"
                            class="absolute inset-y-0 right-0 flex min-h-[44px] items-center pr-3.5 text-muted hover:text-ink focus:outline-none"
                            aria-label="Tampilkan atau sembunyikan kata sandi baru"
                        >
                            <i id="modal-eye-new" class="fa-regular fa-eye text-xs" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>

                {{-- 2. Konfirmasi Kata Sandi Baru --}}
                <div class="space-y-1.5">
                    <label for="modal_password_baru_confirmation" class="block text-xs font-bold text-ink sm:text-sm">
                        Ulangi Kata Sandi Baru <span class="text-rose-500" aria-hidden="true">*</span>
                    </label>
                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted">
                            <i class="fa-solid fa-lock-open text-xs" aria-hidden="true"></i>
                        </span>
                        <input
                            type="password"
                            name="password_baru_confirmation"
                            id="modal_password_baru_confirmation"
                            required
                            minlength="6"
                            placeholder="Ketik ulang kata sandi baru yang sama..."
                            class="block w-full min-h-[44px] rounded-xl border border-line pl-10 pr-10 text-xs font-medium text-ink placeholder:text-muted/70 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 sm:text-sm"
                            oninput="checkModalPasswordMatch()"
                        >
                        <button
                            type="button"
                            onclick="toggleModalPassword('modal_password_baru_confirmation', 'modal-eye-confirm')"
                            class="absolute inset-y-0 right-0 flex min-h-[44px] items-center pr-3.5 text-muted hover:text-ink focus:outline-none"
                            aria-label="Tampilkan atau sembunyikan konfirmasi kata sandi"
                        >
                            <i id="modal-eye-confirm" class="fa-regular fa-eye text-xs" aria-hidden="true"></i>
                        </button>
                    </div>
                    <p id="modal-match-feedback" class="text-[11px] font-medium text-muted mt-1 hidden"></p>
                </div>

                {{-- Info Keamanan Hashing --}}
                <div class="rounded-xl bg-slate-50 p-3 border border-line/70 flex items-center gap-2 text-[11px] text-muted">
                    <i class="fa-solid fa-shield-halved text-emerald-600" aria-hidden="true"></i>
                    <span>Terenkripsi aman menggunakan standar hash Bcrypt Laravel.</span>
                </div>

                {{-- Footer Tombol Aksi --}}
                <div class="flex flex-col-reverse gap-2.5 pt-3 sm:flex-row sm:items-center sm:justify-end border-t border-line/70">
                    <button
                        type="button"
                        onclick="closeProfileResetModal()"
                        class="inline-flex min-h-[44px] items-center justify-center rounded-xl border border-line bg-white px-5 py-2 text-xs font-semibold text-slate-700 shadow-sm hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-slate-300"
                    >
                        Batal
                    </button>
                    <button
                        type="submit"
                        class="inline-flex min-h-[44px] items-center justify-center gap-2 rounded-xl bg-primary px-6 py-2 text-xs font-semibold text-white shadow-sm transition-all hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-primary/20"
                    >
                        <i class="fa-solid fa-floppy-disk" aria-hidden="true"></i>
                        <span>Simpan Kata Sandi Baru</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openProfileResetModal() {
        const modal = document.getElementById('profile-reset-password-modal');
        if (modal) {
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
            setTimeout(() => {
                document.getElementById('modal_password_baru')?.focus();
            }, 100);
        }
    }

    function closeProfileResetModal() {
        const modal = document.getElementById('profile-reset-password-modal');
        if (modal) {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    }

    function toggleModalPassword(inputId, iconId) {
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

    function checkModalPasswordMatch() {
        const p1 = document.getElementById('modal_password_baru')?.value || '';
        const p2 = document.getElementById('modal_password_baru_confirmation')?.value || '';
        const feedback = document.getElementById('modal-match-feedback');
        if (!feedback) return;

        if (!p2) {
            feedback.classList.add('hidden');
            return;
        }

        feedback.classList.remove('hidden');
        if (p1 === p2) {
            feedback.className = 'text-[11px] font-medium text-emerald-600 flex items-center gap-1 mt-1';
            feedback.innerHTML = '<i class="fa-solid fa-circle-check"></i> <span>Kata sandi cocok!</span>';
        } else {
            feedback.className = 'text-[11px] font-medium text-rose-600 flex items-center gap-1 mt-1';
            feedback.innerHTML = '<i class="fa-solid fa-circle-xmark"></i> <span>Konfirmasi kata sandi belum sama.</span>';
        }
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeProfileResetModal();
        }
    });

    document.getElementById('profile-reset-password-modal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeProfileResetModal();
        }
    });
</script>
@endsection
