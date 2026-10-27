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
                        @if ($user->isMember())
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-500/20 px-3 py-1 text-xs font-semibold text-emerald-200 ring-1 ring-inset ring-emerald-400/40">
                                <i class="fa-solid fa-circle-check text-[10px]" aria-hidden="true"></i>
                                <span>Status: Member Aktif</span>
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-400/20 px-3 py-1 text-xs font-semibold text-amber-200 ring-1 ring-inset ring-amber-300/40">
                                <i class="fa-solid fa-circle-exclamation text-[10px]" aria-hidden="true"></i>
                                <span>Status: Belum Menjadi Member</span>
                            </span>
                        @endif
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
                                <span class="block text-[11px] font-medium text-muted">Member Sejak</span>
                                <span class="mt-0.5 block text-xs font-semibold text-ink sm:text-sm">
                                    {{ $user->member_sejak ? $user->member_sejak->format('d M Y') : ($user->isMember() ? ($user->created_at ? $user->created_at->format('d M Y') : '16 Okt 2026') : 'Belum Aktif') }}
                                </span>
                            </div>
                        </div>

                        {{-- Barcode Simulasi Anggota --}}
                        <div class="mt-5 flex flex-col items-center justify-between gap-3 rounded-xl bg-slate-50 p-3.5 border border-line sm:flex-row">
                            <div class="flex items-center gap-3">
                                <i class="fa-solid fa-barcode text-3xl text-slate-700" aria-hidden="true"></i>
                                <div>
                                    <span class="block text-[10px] font-bold uppercase tracking-wider text-muted">Nomor Member Perpustakaan</span>
                                    <span class="font-mono text-xs font-bold text-ink sm:text-sm tracking-wider">
                                        {{ $user->nomor_anggota ?? ($user->isMember() ? ('MNCU-LIB-' . str_pad((string)($user->id ?? 1), 5, '0', STR_PAD_LEFT)) : 'BELUM-TERDAFTAR') }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex flex-wrap items-center gap-2">
                                <a href="{{ route('profile.edit') }}#ubah-password" class="inline-flex min-h-[44px] items-center gap-2 rounded-xl border border-line bg-white px-3.5 py-2 text-xs font-semibold text-ink shadow-sm transition-all hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-amber-500/20">
                                    <i class="fa-solid fa-key text-amber-600" aria-hidden="true"></i>
                                    <span>Ubah Kata Sandi</span>
                                </a>
                                <a href="{{ route('profile.edit') }}" class="inline-flex min-h-[44px] items-center gap-2 rounded-xl bg-primary px-4 py-2 text-xs font-semibold text-white shadow-sm transition-all hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-primary/20">
                                    <i class="fa-solid fa-user-pen" aria-hidden="true"></i>
                                    <span>Perbarui Data Diri</span>
                                </a>
                            </div>
                        </div>

                        @if (! $user->isMember())
                            {{-- Kotak Informasi Aktivasi Keanggotaan Tatap Muka Langsung --}}
                            <div class="mt-4 rounded-xl border border-amber-200 bg-amber-50/90 p-4 text-xs text-amber-950 sm:text-sm">
                                <div class="flex items-start gap-3">
                                    <div class="flex size-8 shrink-0 items-center justify-center rounded-lg bg-amber-200 text-amber-900">
                                        <i class="fa-solid fa-triangle-exclamation text-sm" aria-hidden="true"></i>
                                    </div>
                                    <div class="flex-1 space-y-1">
                                        <p class="font-bold text-amber-950">Status Keanggotaan Belum Aktif</p>
                                        <p class="text-xs text-amber-900 leading-relaxed">
                                            Untuk meminjam buku fisik di Perpustakaan MNC University, Anda harus menjadi member terlebih dahulu.
                                        </p>
                                        <p class="text-xs font-semibold text-amber-950">
                                            Prosedur Aktivasi Member: Permohonan menjadi member tidak dapat dilakukan melalui website. Silakan kunjungi Meja Sirkulasi Perpustakaan MNC University Lantai 2 secara langsung dan temui pustakawan atau super admin dengan membawa Kartu Identitas (KTM / Kartu Pegawai).
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
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
                        <a href="{{ route('profile.edit') }}#ubah-password" class="flex min-h-[44px] items-center justify-between rounded-xl p-3 text-xs font-medium text-ink transition-colors hover:bg-slate-50">
                            <span class="flex items-center gap-3">
                                <i class="fa-solid fa-key text-amber-600" aria-hidden="true"></i>
                                <span>Ubah Kata Sandi Akun</span>
                            </span>
                            <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-primary">
                                <span>Ganti</span>
                                <i class="fa-solid fa-chevron-right text-[10px] text-slate-400" aria-hidden="true"></i>
                            </span>
                        </a>
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
@endsection
