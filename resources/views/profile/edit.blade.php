@extends('layouts.app')

@section('title', 'Edit Profil Pengguna')
@section('description', 'Perbarui data diri, nomor identitas peran, dan foto profil Anda di Perpustakaan MNC University.')

@section('content')
<div class="min-h-screen bg-slate-50/70 py-8 lg:py-12">
    <div class="mx-auto w-full max-w-5xl px-4 sm:px-6 lg:px-8">
        {{-- Breadcrumbs --}}
        <nav class="mb-4 flex items-center gap-2 text-xs font-medium text-muted sm:text-sm" aria-label="Breadcrumb">
            <a href="{{ route('beranda') }}" class="inline-flex min-h-[44px] items-center text-muted transition-colors hover:text-primary">
                <i class="fa-solid fa-house mr-1.5 text-xs text-slate-400"></i>
                <span>Beranda</span>
            </a>
            <i class="fa-solid fa-chevron-right text-[10px] text-slate-400" aria-hidden="true"></i>
            <a href="{{ route('profile.show') }}" class="inline-flex min-h-[44px] items-center text-muted transition-colors hover:text-primary">
                <span>Profil Pengguna</span>
            </a>
            <i class="fa-solid fa-chevron-right text-[10px] text-slate-400" aria-hidden="true"></i>
            <span class="inline-flex min-h-[44px] items-center font-semibold text-ink" aria-current="page">Edit Profil</span>
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
                <h1 class="text-2xl font-bold tracking-tight text-ink sm:text-3xl">Pengaturan Profil</h1>
                <p class="mt-1 text-xs text-muted sm:text-sm">Kelola informasi data diri, foto identitas anggota, dan kredensial peran perpustakaan Anda.</p>
            </div>
            <div class="flex items-center gap-2">
                {{-- Segmented Navigation Tab --}}
                <div class="inline-flex rounded-xl bg-slate-200/70 p-1 text-xs font-medium">
                    <a href="{{ route('profile.show') }}" class="inline-flex min-h-[40px] items-center gap-1.5 rounded-lg px-3.5 py-1.5 text-slate-600 transition-colors hover:text-ink hover:bg-white/60">
                        <i class="fa-solid fa-id-card text-xs"></i>
                        <span>Ringkasan</span>
                    </a>
                    <span class="inline-flex min-h-[40px] items-center gap-1.5 rounded-lg bg-white px-3.5 py-1.5 font-semibold text-primary shadow-sm">
                        <i class="fa-solid fa-user-pen text-xs"></i>
                        <span>Edit Profil</span>
                    </span>
                    <a href="#section-reset-password" class="inline-flex min-h-[40px] items-center gap-1.5 rounded-lg px-3.5 py-1.5 text-slate-600 transition-colors hover:text-ink hover:bg-white/60">
                        <i class="fa-solid fa-key text-xs"></i>
                        <span>Reset Password</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- Form Pembaruan Profil --}}
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            <input type="hidden" name="role" value="{{ $role }}">

            {{-- 1. Kartu Foto Profil & Identitas Peran --}}
            <div class="overflow-hidden rounded-2xl border border-line bg-white shadow-card">
                <div class="border-b border-line bg-slate-50/70 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <span class="flex size-7 items-center justify-center rounded-lg bg-primary/10 text-primary">
                                <i class="fa-solid fa-camera text-xs"></i>
                            </span>
                            <h2 class="text-sm font-bold text-ink sm:text-base">Foto Profil & Identitas Kartu</h2>
                        </div>
                        <div>
                            @if ($role === 'mahasiswa')
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-800 border border-blue-200">
                                    <i class="fa-solid fa-graduation-cap"></i> Mahasiswa
                                </span>
                            @elseif ($role === 'dosen')
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-800 border border-indigo-200">
                                    <i class="fa-solid fa-chalkboard-user"></i> Dosen
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-800 border border-slate-300">
                                    <i class="fa-solid fa-shield-halved"></i> Administrator
                                </span>
                            @endif
                        </div>
                    </div>
                    <p class="mt-1 text-xs text-muted">Foto ini ditampilkan pada kartu anggota perpustakaan digital dan verifikasi layanan pemustaka.</p>
                </div>

                <div class="p-6">
                    <div class="flex flex-col items-center gap-6 sm:flex-row sm:items-start sm:gap-8">
                        {{-- Wadah Foto & Preview --}}
                        <div class="relative shrink-0">
                            <div class="relative size-28 overflow-hidden rounded-2xl border-4 border-white bg-slate-100 shadow-sm ring-1 ring-slate-200 sm:size-32">
                                @if ($user->foto_profil)
                                    <img id="profile-preview" src="{{ asset('storage/' . $user->foto_profil) }}" alt="{{ $user->nama_lengkap }}" class="size-full object-cover">
                                @else
                                    <img id="profile-preview" src="" alt="Pratinjau Foto" class="hidden size-full object-cover">
                                    <div id="profile-avatar-fallback" class="flex size-full flex-col items-center justify-center bg-primary/10 text-primary">
                                        <span class="text-3xl font-extrabold">{{ strtoupper(substr($user->nama_lengkap ?? 'U', 0, 1)) }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Input Unggah Foto & Panduan --}}
                        <div class="flex-1 space-y-3 text-center sm:text-left">
                            <label for="foto_profil" class="block text-xs font-bold text-ink sm:text-sm">
                                Unggah Berkas Foto Anggota
                            </label>
                            <div>
                                <input
                                    type="file"
                                    name="foto_profil"
                                    id="foto_profil"
                                    accept=".jpg,.jpeg,.png"
                                    aria-describedby="foto-profil-desc file-size-warning @error('foto_profil') foto-profil-error @enderror"
                                    class="block w-full cursor-pointer rounded-xl border border-line bg-white px-3 py-2 text-xs text-slate-700 shadow-sm file:mr-4 file:min-h-[40px] file:cursor-pointer file:rounded-lg file:border-0 file:bg-primary file:px-4 file:py-2 file:text-xs file:font-semibold file:text-white hover:file:bg-primary/90 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20"
                                >
                            </div>
                            <p id="foto-profil-desc" class="text-xs text-muted flex items-center justify-center sm:justify-start gap-1.5">
                                <i class="fa-solid fa-circle-info text-primary" aria-hidden="true"></i>
                                <span>Format berkas: <strong>.jpg, .jpeg, .png</strong> dengan batas maksimal <strong>200 KB</strong>.</span>
                            </p>
                            <p id="file-size-warning" class="hidden text-xs font-semibold text-rose-600 flex items-center justify-center sm:justify-start gap-1.5" role="alert">
                                <i class="fa-solid fa-triangle-exclamation" aria-hidden="true"></i>
                                <span>Ukuran berkas yang dipilih melebihi batas 200 KB! Silakan pilih foto lain yang lebih ringkas.</span>
                            </p>
                            @error('foto_profil')
                                <p id="foto-profil-error" class="text-xs font-semibold text-rose-600 flex items-center justify-center sm:justify-start gap-1.5" role="alert">
                                    <i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i>
                                    <span>{{ $message }}</span>
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. Data Khusus Sesuai Peran --}}
            <div class="overflow-hidden rounded-2xl border border-line bg-white shadow-card">
                <div class="border-b border-line bg-slate-50/70 px-6 py-4">
                    <div class="flex items-center gap-2.5">
                        <span class="flex size-7 items-center justify-center rounded-lg bg-primary/10 text-primary">
                            <i class="fa-solid fa-id-card-clip text-xs"></i>
                        </span>
                        <h2 class="text-sm font-bold text-ink sm:text-base">Informasi Khusus Peran: {{ ucfirst($role) }}</h2>
                    </div>
                    <p class="mt-1 text-xs text-muted">Kolom identitas akademik atau administratif resmi di lingkungan MNC University.</p>
                </div>

                <div class="p-6">
                    @if ($role === 'mahasiswa')
                        {{-- Role Mahasiswa: Wajib NIM --}}
                        <div class="max-w-md space-y-1.5">
                            <label for="nim" class="block text-xs font-bold text-ink sm:text-sm">
                                Nomor Induk Mahasiswa (NIM) <span class="text-rose-500" aria-hidden="true">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted pointer-events-none" aria-hidden="true">
                                    <i class="fa-solid fa-user-graduate"></i>
                                </span>
                                <input
                                    type="text"
                                    name="nim"
                                    id="nim"
                                    value="{{ old('nim', $user->nim ?? $user->nomor_identitas) }}"
                                    required
                                    placeholder="Contoh: 202401001"
                                    aria-describedby="nim-desc @error('nim') nim-error @enderror"
                                    @error('nim') aria-invalid="true" @enderror
                                    class="block w-full min-h-[44px] rounded-xl border border-line pl-10 pr-4 text-xs font-medium text-ink placeholder:text-slate-400 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 sm:text-sm @error('nim') border-rose-400 bg-rose-50/30 @enderror"
                                >
                            </div>
                            <p id="nim-desc" class="text-xs text-muted">Wajib diisi sesuai NIM resmi terdaftar di SIAKAD MNC University.</p>
                            @error('nim')
                                <p id="nim-error" class="text-xs font-semibold text-rose-600 flex items-center gap-1.5" role="alert">
                                    <i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i>
                                    <span>{{ $message }}</span>
                                </p>
                            @enderror
                        </div>
                    @elseif ($role === 'dosen')
                        {{-- Role Dosen: Wajib NIDN --}}
                        <div class="max-w-md space-y-1.5">
                            <label for="nidn" class="block text-xs font-bold text-ink sm:text-sm">
                                Nomor Induk Dosen Nasional (NIDN) <span class="text-rose-500" aria-hidden="true">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted pointer-events-none" aria-hidden="true">
                                    <i class="fa-solid fa-award"></i>
                                </span>
                                <input
                                    type="text"
                                    name="nidn"
                                    id="nidn"
                                    value="{{ old('nidn', $user->nidn ?? $user->nomor_identitas) }}"
                                    required
                                    placeholder="Contoh: 0312058001"
                                    aria-describedby="nidn-desc @error('nidn') nidn-error @enderror"
                                    @error('nidn') aria-invalid="true" @enderror
                                    class="block w-full min-h-[44px] rounded-xl border border-line pl-10 pr-4 text-xs font-medium text-ink placeholder:text-slate-400 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 sm:text-sm @error('nidn') border-rose-400 bg-rose-50/30 @enderror"
                                >
                            </div>
                            <p id="nidn-desc" class="text-xs text-muted">Wajib diisi sesuai NIDN resmi yang tercatat pada PDDikti Kemendikbud.</p>
                            @error('nidn')
                                <p id="nidn-error" class="text-xs font-semibold text-rose-600 flex items-center gap-1.5" role="alert">
                                    <i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i>
                                    <span>{{ $message }}</span>
                                </p>
                            @enderror
                        </div>
                    @elseif ($role === 'admin')
                        {{-- Role Admin: Keterangan Administrator Sistem --}}
                        <div class="flex items-center gap-3 rounded-xl border border-blue-200 bg-blue-50/70 p-4 text-xs text-blue-900 sm:text-sm">
                            <i class="fa-solid fa-circle-info text-blue-600 text-lg" aria-hidden="true"></i>
                            <div>
                                <p class="font-bold">Peran Administrator Sistem</p>
                                <p class="mt-0.5 text-xs text-blue-800">Sebagai administrator atau pustakawan pengelola, Anda tidak memerlukan pengisian nomor identitas mahasiswa (NIM) maupun dosen (NIDN).</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- 3. Data Pribadi & Kontak --}}
            <div class="overflow-hidden rounded-2xl border border-line bg-white shadow-card">
                <div class="border-b border-line bg-slate-50/70 px-6 py-4">
                    <div class="flex items-center gap-2.5">
                        <span class="flex size-7 items-center justify-center rounded-lg bg-primary/10 text-primary">
                            <i class="fa-solid fa-user text-xs"></i>
                        </span>
                        <h2 class="text-sm font-bold text-ink sm:text-base">Data Pribadi & Kontak Pemustaka</h2>
                    </div>
                    <p class="mt-1 text-xs text-muted">Informasi data diri yang digunakan untuk komunikasi, pemberitahuan peminjaman, dan pendataan anggota.</p>
                </div>

                <div class="grid grid-cols-1 gap-5 p-6 sm:grid-cols-2">
                    {{-- Nama Lengkap --}}
                    <div class="space-y-1.5 sm:col-span-2">
                        <label for="nama_lengkap" class="block text-xs font-bold text-ink sm:text-sm">
                            Nama Lengkap <span class="text-rose-500" aria-hidden="true">*</span>
                        </label>
                        <input
                            type="text"
                            name="nama_lengkap"
                            id="nama_lengkap"
                            value="{{ old('nama_lengkap', $user->nama_lengkap) }}"
                            required
                            placeholder="Nama lengkap beserta gelar (jika ada)"
                            @error('nama_lengkap') aria-invalid="true" @enderror
                            class="block w-full min-h-[44px] rounded-xl border border-line px-4 text-xs font-medium text-ink placeholder:text-slate-400 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 sm:text-sm @error('nama_lengkap') border-rose-400 bg-rose-50/30 @enderror"
                        >
                        @error('nama_lengkap')
                            <p class="text-xs font-semibold text-rose-600" role="alert">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Alamat Email --}}
                    <div class="space-y-1.5">
                        <label for="email" class="block text-xs font-bold text-ink sm:text-sm">
                            Alamat Email Resmi <span class="text-rose-500" aria-hidden="true">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted pointer-events-none" aria-hidden="true">
                                <i class="fa-solid fa-envelope"></i>
                            </span>
                            <input
                                type="email"
                                name="email"
                                id="email"
                                value="{{ old('email', $user->email) }}"
                                required
                                placeholder="nama@mncu.ac.id"
                                @error('email') aria-invalid="true" @enderror
                                class="block w-full min-h-[44px] rounded-xl border border-line pl-10 pr-4 text-xs font-medium text-ink placeholder:text-slate-400 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 sm:text-sm @error('email') border-rose-400 bg-rose-50/30 @enderror"
                            >
                        </div>
                        @error('email')
                            <p class="text-xs font-semibold text-rose-600" role="alert">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Nomor WhatsApp --}}
                    <div class="space-y-1.5">
                        <label for="no_wa" class="block text-xs font-bold text-ink sm:text-sm">
                            Nomor WhatsApp / Telepon <span class="text-rose-500" aria-hidden="true">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted pointer-events-none" aria-hidden="true">
                                <i class="fa-brands fa-whatsapp text-emerald-600"></i>
                            </span>
                            <input
                                type="tel"
                                name="no_wa"
                                id="no_wa"
                                value="{{ old('no_wa', $user->no_wa) }}"
                                required
                                placeholder="081234567890"
                                @error('no_wa') aria-invalid="true" @enderror
                                class="block w-full min-h-[44px] rounded-xl border border-line pl-10 pr-4 text-xs font-medium text-ink placeholder:text-slate-400 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 sm:text-sm @error('no_wa') border-rose-400 bg-rose-50/30 @enderror"
                            >
                        </div>
                        @error('no_wa')
                            <p class="text-xs font-semibold text-rose-600" role="alert">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Nomor Induk Kependudukan (NIK) --}}
                    <div class="space-y-1.5">
                        <label for="nik" class="block text-xs font-bold text-ink sm:text-sm">
                            Nomor Induk Kependudukan (NIK) <span class="text-rose-500" aria-hidden="true">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted pointer-events-none" aria-hidden="true">
                                <i class="fa-solid fa-address-card"></i>
                            </span>
                            <input
                                type="text"
                                name="nik"
                                id="nik"
                                value="{{ old('nik', $user->nik) }}"
                                required
                                maxlength="20"
                                placeholder="16 digit NIK sesuai KTP"
                                @error('nik') aria-invalid="true" @enderror
                                class="block w-full min-h-[44px] rounded-xl border border-line pl-10 pr-4 text-xs font-medium text-ink placeholder:text-slate-400 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 sm:text-sm @error('nik') border-rose-400 bg-rose-50/30 @enderror"
                            >
                        </div>
                        @error('nik')
                            <p class="text-xs font-semibold text-rose-600" role="alert">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tempat Lahir --}}
                    <div class="space-y-1.5">
                        <label for="tempat_lahir" class="block text-xs font-bold text-ink sm:text-sm">
                            Tempat Lahir <span class="text-rose-500" aria-hidden="true">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted pointer-events-none" aria-hidden="true">
                                <i class="fa-solid fa-location-dot"></i>
                            </span>
                            <input
                                type="text"
                                name="tempat_lahir"
                                id="tempat_lahir"
                                value="{{ old('tempat_lahir', $user->tempat_lahir) }}"
                                required
                                placeholder="Kota atau kabupaten kelahiran"
                                @error('tempat_lahir') aria-invalid="true" @enderror
                                class="block w-full min-h-[44px] rounded-xl border border-line pl-10 pr-4 text-xs font-medium text-ink placeholder:text-slate-400 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 sm:text-sm @error('tempat_lahir') border-rose-400 bg-rose-50/30 @enderror"
                            >
                        </div>
                        @error('tempat_lahir')
                            <p class="text-xs font-semibold text-rose-600" role="alert">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tanggal Lahir --}}
                    <div class="space-y-1.5">
                        <label for="tanggal_lahir" class="block text-xs font-bold text-ink sm:text-sm">
                            Tanggal Lahir <span class="text-rose-500" aria-hidden="true">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted pointer-events-none" aria-hidden="true">
                                <i class="fa-solid fa-calendar-day"></i>
                            </span>
                            <input
                                type="date"
                                name="tanggal_lahir"
                                id="tanggal_lahir"
                                value="{{ old('tanggal_lahir', $user->tanggal_lahir ? $user->tanggal_lahir->format('Y-m-d') : '') }}"
                                required
                                @error('tanggal_lahir') aria-invalid="true" @enderror
                                class="block w-full min-h-[44px] rounded-xl border border-line pl-10 pr-4 text-xs font-medium text-ink focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 sm:text-sm @error('tanggal_lahir') border-rose-400 bg-rose-50/30 @enderror"
                            >
                        </div>
                        @error('tanggal_lahir')
                            <p class="text-xs font-semibold text-rose-600" role="alert">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Alamat Domisili Lengkap --}}
                    <div class="space-y-1.5 sm:col-span-2">
                        <label for="alamat_lengkap" class="block text-xs font-bold text-ink sm:text-sm">
                            Alamat Domisili Lengkap <span class="text-rose-500" aria-hidden="true">*</span>
                        </label>
                        <textarea
                            name="alamat_lengkap"
                            id="alamat_lengkap"
                            rows="3"
                            required
                            placeholder="Alamat domisili lengkap (nama jalan, nomor rumah, RT/RW, kelurahan, kecamatan, kota/kabupaten)"
                            @error('alamat_lengkap') aria-invalid="true" @enderror
                            class="block w-full rounded-xl border border-line p-3 text-xs font-medium text-ink placeholder:text-slate-400 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 sm:text-sm @error('alamat_lengkap') border-rose-400 bg-rose-50/30 @enderror"
                        >{{ old('alamat_lengkap', $user->alamat_lengkap) }}</textarea>
                        @error('alamat_lengkap')
                            <p class="text-xs font-semibold text-rose-600" role="alert">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Aksi Formulir --}}
                <div class="flex flex-col-reverse gap-3 border-t border-line bg-slate-50/70 px-6 py-4 sm:flex-row sm:items-center sm:justify-end">
                    <a href="{{ route('profile.show') }}" class="inline-flex min-h-[44px] items-center justify-center rounded-xl border border-line bg-white px-5 py-2.5 text-xs font-semibold text-slate-700 shadow-sm transition-all hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-slate-300 sm:text-sm">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex min-h-[44px] items-center justify-center gap-2 rounded-xl bg-primary px-6 py-2.5 text-xs font-semibold text-white shadow-sm transition-all hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-primary/20 sm:text-sm">
                        <i class="fa-solid fa-floppy-disk"></i>
                        <span>Simpan Pembaruan Profil</span>
                    </button>
                </div>
            </div>
        </form>

        {{-- ========================================================================= --}}
        {{-- 3. KARTU KEAMANAN AKUN: RESET KATA SANDI LANGSUNG (TANPA PASSWORD LAMA)    --}}
        {{-- ========================================================================= --}}
        <div id="section-reset-password" class="mt-8 overflow-hidden rounded-2xl border border-line bg-white shadow-card scroll-mt-6">
            <div class="border-b border-line bg-slate-50/70 px-6 py-4">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center gap-2.5">
                        <span class="flex size-8 items-center justify-center rounded-xl bg-primary/10 text-primary">
                            <i class="fa-solid fa-key text-xs" aria-hidden="true"></i>
                        </span>
                        <div>
                            <h2 class="text-sm font-bold text-ink sm:text-base">Reset Kata Sandi Akun</h2>
                            <p class="text-xs text-muted">Perbarui kata sandi akun langsung tanpa perlu mengingat atau memasukkan kata sandi lama.</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-800 border border-emerald-200 self-start sm:self-center">
                        <i class="fa-solid fa-shield-halved text-[11px]" aria-hidden="true"></i>
                        <span>Langsung Password Baru (2x)</span>
                    </span>
                </div>
            </div>

            <form action="{{ route('profile.password.update') }}" method="POST" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                {{-- Alert Info Verifikasi --}}
                <div class="flex items-start gap-3 rounded-xl border border-blue-200 bg-blue-50/80 p-4 text-xs text-blue-900">
                    <i class="fa-solid fa-circle-info text-blue-600 mt-0.5 shrink-0" aria-hidden="true"></i>
                    <div class="space-y-1">
                        <p class="font-bold text-blue-950">Petunjuk Pembaruan Kata Sandi Mandiri:</p>
                        <p class="text-blue-800 leading-relaxed">
                            Anda tidak perlu memasukkan kata sandi lama. Cukup masukkan kata sandi baru yang Anda inginkan sebanyak <strong>2 kali</strong> pada kolom di bawah ini (Kata Sandi Baru dan Konfirmasi Kata Sandi Baru).
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    {{-- 1. Kata Sandi Baru --}}
                    <div class="space-y-1.5">
                        <label for="edit_password_baru" class="block text-xs font-bold text-ink sm:text-sm">
                            Kata Sandi Baru <span class="text-rose-500" aria-hidden="true">*</span>
                        </label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted">
                                <i class="fa-solid fa-lock text-xs" aria-hidden="true"></i>
                            </span>
                            <input
                                type="password"
                                name="password_baru"
                                id="edit_password_baru"
                                required
                                minlength="6"
                                placeholder="Minimal 6 karakter kombinasi..."
                                class="block w-full min-h-[44px] rounded-xl border border-line pl-10 pr-10 text-xs font-medium text-ink placeholder:text-muted/70 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 sm:text-sm"
                                oninput="checkEditPasswordMatch()"
                            >
                            <button
                                type="button"
                                onclick="toggleEditPassword('edit_password_baru', 'edit-eye-new')"
                                class="absolute inset-y-0 right-0 flex min-h-[44px] items-center pr-3.5 text-muted hover:text-ink focus:outline-none"
                                aria-label="Tampilkan atau sembunyikan kata sandi baru"
                            >
                                <i id="edit-eye-new" class="fa-regular fa-eye text-xs" aria-hidden="true"></i>
                            </button>
                        </div>
                        <p class="text-[11px] text-muted">Gunakan minimal 6 karakter kombinasi huruf dan angka.</p>
                    </div>

                    {{-- 2. Konfirmasi Kata Sandi Baru --}}
                    <div class="space-y-1.5">
                        <label for="edit_password_baru_confirmation" class="block text-xs font-bold text-ink sm:text-sm">
                            Ulangi Kata Sandi Baru <span class="text-rose-500" aria-hidden="true">*</span>
                        </label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted">
                                <i class="fa-solid fa-lock-open text-xs" aria-hidden="true"></i>
                            </span>
                            <input
                                type="password"
                                name="password_baru_confirmation"
                                id="edit_password_baru_confirmation"
                                required
                                minlength="6"
                                placeholder="Ketik ulang kata sandi baru..."
                                class="block w-full min-h-[44px] rounded-xl border border-line pl-10 pr-10 text-xs font-medium text-ink placeholder:text-muted/70 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 sm:text-sm"
                                oninput="checkEditPasswordMatch()"
                            >
                            <button
                                type="button"
                                onclick="toggleEditPassword('edit_password_baru_confirmation', 'edit-eye-confirm')"
                                class="absolute inset-y-0 right-0 flex min-h-[44px] items-center pr-3.5 text-muted hover:text-ink focus:outline-none"
                                aria-label="Tampilkan atau sembunyikan konfirmasi kata sandi"
                            >
                                <i id="edit-eye-confirm" class="fa-regular fa-eye text-xs" aria-hidden="true"></i>
                            </button>
                        </div>
                        <p id="edit-match-feedback" class="text-[11px] font-medium text-muted mt-1 hidden"></p>
                    </div>
                </div>

                {{-- Footer Tombol Aksi Reset Password --}}
                <div class="flex flex-col-reverse gap-3 border-t border-line bg-slate-50/70 -mx-6 -mb-6 px-6 py-4 sm:flex-row sm:items-center sm:justify-between rounded-b-2xl">
                    <div class="flex items-center gap-2 text-xs text-muted">
                        <i class="fa-solid fa-shield-halved text-emerald-600" aria-hidden="true"></i>
                        <span>Enkripsi Bcrypt aktif berstandar keamanan MNC University</span>
                    </div>
                    <button
                        type="submit"
                        class="inline-flex min-h-[44px] items-center justify-center gap-2 rounded-xl bg-primary px-6 py-2.5 text-xs font-semibold text-white shadow-sm transition-all hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-primary/20 sm:text-sm"
                    >
                        <i class="fa-solid fa-key" aria-hidden="true"></i>
                        <span>Perbarui Kata Sandi Akun</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const fileInput = document.getElementById('foto_profil');
        const previewImg = document.getElementById('profile-preview');
        const avatarFallback = document.getElementById('profile-avatar-fallback');
        const warning = document.getElementById('file-size-warning');

        if (fileInput) {
            fileInput.addEventListener('change', (event) => {
                const file = event.target.files && event.target.files[0];
                if (!file) return;

                if (file.size > 200 * 1024) {
                    if (warning) warning.classList.remove('hidden');
                } else {
                    if (warning) warning.classList.add('hidden');
                }

                const reader = new FileReader();
                reader.onload = (e) => {
                    if (previewImg) {
                        previewImg.src = e.target.result;
                        previewImg.classList.remove('hidden');
                    }
                    if (avatarFallback) {
                        avatarFallback.classList.add('hidden');
                    }
                };
                reader.readAsDataURL(file);
            });
        }
    });

    function toggleEditPassword(inputId, iconId) {
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

    function checkEditPasswordMatch() {
        const p1 = document.getElementById('edit_password_baru')?.value || '';
        const p2 = document.getElementById('edit_password_baru_confirmation')?.value || '';
        const feedback = document.getElementById('edit-match-feedback');
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
</script>
@endsection
