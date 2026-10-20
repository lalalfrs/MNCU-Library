@extends('layouts.app')

@section('title', 'Edit Profil Pengguna')
@section('description', 'Perbarui data diri, informasi akademik sesuai peran, dan foto profil Anda di Perpustakaan MNC University.')

@section('content')
<div class="min-h-screen bg-slate-50/70 py-8 lg:py-12">
    <div class="mx-auto w-full max-w-5xl px-4 sm:px-6 lg:px-8">
        {{-- Header & Breadcrumbs --}}
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <nav class="flex items-center gap-2 text-xs text-muted" aria-label="Breadcrumb">
                    <a href="{{ route('beranda') }}" class="hover:text-primary transition-colors">Beranda</a>
                    <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                    <a href="{{ route('profile.show') }}" class="hover:text-primary transition-colors">Profil Pengguna</a>
                    <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                    <span class="font-medium text-ink">Edit Profil</span>
                </nav>
                <h1 class="mt-2 text-2xl font-bold tracking-tight text-ink sm:text-3xl">Pengaturan Profil</h1>
                <p class="text-xs text-muted sm:text-sm">Kelola informasi data diri, foto profil, dan kredensial peran perpustakaan Anda.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('profile.show') }}" class="inline-flex min-h-[44px] items-center gap-2 rounded-xl border border-line bg-white px-4 py-2 text-xs font-semibold text-ink shadow-sm transition-all hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-primary/20">
                    <i class="fa-solid fa-arrow-left"></i>
                    <span>Kembali ke Profil</span>
                </a>
            </div>
        </div>

        {{-- Form Edit Profil --}}
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            <input type="hidden" name="role" value="{{ $role }}">

            {{-- 1. Kartu Foto Profil & Identitas Peran --}}
            <div class="overflow-hidden rounded-2xl border border-line bg-white shadow-card">
                <div class="border-b border-line bg-slate-50/50 px-6 py-4">
                    <h2 class="text-sm font-bold text-ink sm:text-base flex items-center gap-2">
                        <i class="fa-solid fa-camera text-primary"></i>
                        <span>Foto Profil & Peran Pengguna</span>
                    </h2>
                    <p class="text-xs text-muted">Unggah foto resmi untuk identitas kartu anggota perpustakaan digital.</p>
                </div>

                <div class="p-6">
                    <div class="flex flex-col items-center gap-6 sm:flex-row sm:items-start">
                        {{-- Wadah Foto & Preview --}}
                        <div class="relative shrink-0">
                            <div class="relative size-28 overflow-hidden rounded-2xl border-2 border-line bg-slate-100 shadow-sm sm:size-32">
                                @if ($user->foto_profil)
                                    <img id="profile-preview" src="{{ asset('storage/' . $user->foto_profil) }}" alt="{{ $user->nama_lengkap }}" class="size-full object-cover">
                                @else
                                    <img id="profile-preview" src="" alt="Preview Foto" class="hidden size-full object-cover">
                                    <div id="profile-avatar-fallback" class="flex size-full items-center justify-center bg-primary/10 text-3xl font-extrabold text-primary">
                                        {{ strtoupper(substr($user->nama_lengkap ?? 'U', 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <div class="mt-2 text-center">
                                @if ($role === 'mahasiswa')
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 border border-emerald-200">
                                        <i class="fa-solid fa-graduation-cap"></i> Mahasiswa
                                    </span>
                                @elseif ($role === 'dosen')
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-violet-50 px-3 py-1 text-xs font-semibold text-violet-700 border border-violet-200">
                                        <i class="fa-solid fa-chalkboard-user"></i> Dosen
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-800 border border-amber-200">
                                        <i class="fa-solid fa-shield-halved"></i> Administrator
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Input Unggah Foto --}}
                        <div class="flex-1 space-y-3">
                            <label for="foto_profil" class="block text-xs font-bold text-ink sm:text-sm">
                                Pilih Berkas Foto Baru
                            </label>
                            <input
                                type="file"
                                name="foto_profil"
                                id="foto_profil"
                                accept=".jpg,.jpeg,.png"
                                class="block w-full rounded-xl border border-line bg-white px-3 py-2 text-xs text-slate-700 shadow-sm file:mr-4 file:min-h-[38px] file:rounded-lg file:border-0 file:bg-primary file:px-4 file:py-2 file:text-xs file:font-semibold file:text-white hover:file:bg-primary/90 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20"
                            >
                            <p class="text-xs text-muted flex items-center gap-1.5">
                                <i class="fa-solid fa-circle-info text-primary"></i>
                                <span>Ketentuan: Format <strong>.jpg, .jpeg, .png</strong> dengan ukuran maksimal <strong>200 KB</strong>.</span>
                            </p>
                            <p id="file-size-warning" class="hidden text-xs font-medium text-rose-600 flex items-center gap-1.5">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                                <span>Ukuran berkas yang Anda pilih melebihi batas maksimal 200 KB!</span>
                            </p>
                            @error('foto_profil')
                                <p class="text-xs font-medium text-rose-600 flex items-center gap-1.5">
                                    <i class="fa-solid fa-circle-exclamation"></i>
                                    <span>{{ $message }}</span>
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. Data Khusus Sesuai Role (@if role) --}}
            <div class="overflow-hidden rounded-2xl border border-line bg-white shadow-card">
                <div class="border-b border-line bg-slate-50/50 px-6 py-4">
                    <h2 class="text-sm font-bold text-ink sm:text-base flex items-center gap-2">
                        <i class="fa-solid fa-id-card-clip text-primary"></i>
                        <span>Informasi Khusus Peran: {{ ucfirst($role) }}</span>
                    </h2>
                    <p class="text-xs text-muted">Kolom identitas akademik yang disesuaikan dengan jenis peran Anda di universitas.</p>
                </div>

                <div class="p-6">
                    @if ($role === 'mahasiswa')
                        {{-- Role Mahasiswa: Wajib NIM --}}
                        <div class="max-w-md space-y-1.5">
                            <label for="nim" class="block text-xs font-bold text-ink sm:text-sm">
                                Nomor Induk Mahasiswa (NIM) <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted pointer-events-none">
                                    <i class="fa-solid fa-user-graduate"></i>
                                </span>
                                <input
                                    type="text"
                                    name="nim"
                                    id="nim"
                                    value="{{ old('nim', $user->nim ?? $user->nomor_identitas) }}"
                                    required
                                    placeholder="Contoh: 202401001"
                                    class="block w-full min-h-[44px] rounded-xl border border-line pl-10 pr-4 text-xs font-medium text-ink placeholder:text-slate-400 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 sm:text-sm @error('nim') border-rose-400 bg-rose-50/30 @enderror"
                                >
                            </div>
                            <p class="text-[11px] text-muted">Wajib diisi sesuai NIM resmi terdaftar di SIAKAD MNC University.</p>
                            @error('nim')
                                <p class="text-xs font-medium text-rose-600 flex items-center gap-1.5">
                                    <i class="fa-solid fa-circle-exclamation"></i>
                                    <span>{{ $message }}</span>
                                </p>
                            @enderror
                        </div>
                    @elseif ($role === 'dosen')
                        {{-- Role Dosen: Wajib NIDN --}}
                        <div class="max-w-md space-y-1.5">
                            <label for="nidn" class="block text-xs font-bold text-ink sm:text-sm">
                                Nomor Induk Dosen Nasional (NIDN) <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted pointer-events-none">
                                    <i class="fa-solid fa-award"></i>
                                </span>
                                <input
                                    type="text"
                                    name="nidn"
                                    id="nidn"
                                    value="{{ old('nidn', $user->nidn ?? $user->nomor_identitas) }}"
                                    required
                                    placeholder="Contoh: 0312058001"
                                    class="block w-full min-h-[44px] rounded-xl border border-line pl-10 pr-4 text-xs font-medium text-ink placeholder:text-slate-400 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 sm:text-sm @error('nidn') border-rose-400 bg-rose-50/30 @enderror"
                                >
                            </div>
                            <p class="text-[11px] text-muted">Wajib diisi sesuai NIDN resmi yang tercatat pada PDDikti Kemendikbud.</p>
                            @error('nidn')
                                <p class="text-xs font-medium text-rose-600 flex items-center gap-1.5">
                                    <i class="fa-solid fa-circle-exclamation"></i>
                                    <span>{{ $message }}</span>
                                </p>
                            @enderror
                        </div>
                    @elseif ($role === 'admin')
                        {{-- Role Admin: Tidak perlu input NIM/NIDN --}}
                        <div class="flex items-center gap-3 rounded-xl border border-blue-200 bg-blue-50/70 p-4 text-xs text-blue-900 sm:text-sm">
                            <i class="fa-solid fa-circle-info text-blue-600 text-lg"></i>
                            <div>
                                <p class="font-semibold">Peran Administrator Sistem</p>
                                <p class="text-xs text-blue-800">Sebagai administrator atau staf perpustakaan, Anda tidak memerlukan pengisian nomor identitas mahasiswa (NIM) maupun dosen (NIDN).</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- 3. Data Umum Pengguna --}}
            <div class="overflow-hidden rounded-2xl border border-line bg-white shadow-card">
                <div class="border-b border-line bg-slate-50/50 px-6 py-4">
                    <h2 class="text-sm font-bold text-ink sm:text-base flex items-center gap-2">
                        <i class="fa-solid fa-user text-primary"></i>
                        <span>Data Umum Pengguna</span>
                    </h2>
                    <p class="text-xs text-muted">Informasi data diri umum yang digunakan untuk komunikasi dan pendataan perpustakaan.</p>
                </div>

                <div class="grid grid-cols-1 gap-5 p-6 sm:grid-cols-2">
                    {{-- Nama Lengkap --}}
                    <div class="space-y-1.5 sm:col-span-2">
                        <label for="nama_lengkap" class="block text-xs font-bold text-ink sm:text-sm">
                            Nama Lengkap <span class="text-rose-500">*</span>
                        </label>
                        <input
                            type="text"
                            name="nama_lengkap"
                            id="nama_lengkap"
                            value="{{ old('nama_lengkap', $user->nama_lengkap) }}"
                            required
                            placeholder="Nama lengkap beserta gelar (jika ada)"
                            class="block w-full min-h-[44px] rounded-xl border border-line px-4 text-xs font-medium text-ink placeholder:text-slate-400 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 sm:text-sm @error('nama_lengkap') border-rose-400 bg-rose-50/30 @enderror"
                        >
                        @error('nama_lengkap')
                            <p class="text-xs font-medium text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="space-y-1.5">
                        <label for="email" class="block text-xs font-bold text-ink sm:text-sm">
                            Alamat Email <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted pointer-events-none">
                                <i class="fa-solid fa-envelope"></i>
                            </span>
                            <input
                                type="email"
                                name="email"
                                id="email"
                                value="{{ old('email', $user->email) }}"
                                required
                                placeholder="nama@mncu.ac.id"
                                class="block w-full min-h-[44px] rounded-xl border border-line pl-10 pr-4 text-xs font-medium text-ink placeholder:text-slate-400 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 sm:text-sm @error('email') border-rose-400 bg-rose-50/30 @enderror"
                            >
                        </div>
                        @error('email')
                            <p class="text-xs font-medium text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- No WhatsApp --}}
                    <div class="space-y-1.5">
                        <label for="no_wa" class="block text-xs font-bold text-ink sm:text-sm">
                            Nomor WhatsApp / Telepon <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted pointer-events-none">
                                <i class="fa-brands fa-whatsapp text-emerald-600"></i>
                            </span>
                            <input
                                type="tel"
                                name="no_wa"
                                id="no_wa"
                                value="{{ old('no_wa', $user->no_wa) }}"
                                required
                                placeholder="081234567890"
                                class="block w-full min-h-[44px] rounded-xl border border-line pl-10 pr-4 text-xs font-medium text-ink placeholder:text-slate-400 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 sm:text-sm @error('no_wa') border-rose-400 bg-rose-50/30 @enderror"
                            >
                        </div>
                        @error('no_wa')
                            <p class="text-xs font-medium text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- NIK --}}
                    <div class="space-y-1.5">
                        <label for="nik" class="block text-xs font-bold text-ink sm:text-sm">
                            Nomor Induk Kependudukan (NIK) <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted pointer-events-none">
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
                                class="block w-full min-h-[44px] rounded-xl border border-line pl-10 pr-4 text-xs font-medium text-ink placeholder:text-slate-400 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 sm:text-sm @error('nik') border-rose-400 bg-rose-50/30 @enderror"
                            >
                        </div>
                        @error('nik')
                            <p class="text-xs font-medium text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tempat Lahir --}}
                    <div class="space-y-1.5">
                        <label for="tempat_lahir" class="block text-xs font-bold text-ink sm:text-sm">
                            Tempat Lahir <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted pointer-events-none">
                                <i class="fa-solid fa-location-dot"></i>
                            </span>
                            <input
                                type="text"
                                name="tempat_lahir"
                                id="tempat_lahir"
                                value="{{ old('tempat_lahir', $user->tempat_lahir) }}"
                                required
                                placeholder="Kota / Kabupaten kelahiran"
                                class="block w-full min-h-[44px] rounded-xl border border-line pl-10 pr-4 text-xs font-medium text-ink placeholder:text-slate-400 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 sm:text-sm @error('tempat_lahir') border-rose-400 bg-rose-50/30 @enderror"
                            >
                        </div>
                        @error('tempat_lahir')
                            <p class="text-xs font-medium text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tanggal Lahir --}}
                    <div class="space-y-1.5">
                        <label for="tanggal_lahir" class="block text-xs font-bold text-ink sm:text-sm">
                            Tanggal Lahir <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted pointer-events-none">
                                <i class="fa-solid fa-calendar-day"></i>
                            </span>
                            <input
                                type="date"
                                name="tanggal_lahir"
                                id="tanggal_lahir"
                                value="{{ old('tanggal_lahir', $user->tanggal_lahir ? $user->tanggal_lahir->format('Y-m-d') : '') }}"
                                required
                                class="block w-full min-h-[44px] rounded-xl border border-line pl-10 pr-4 text-xs font-medium text-ink focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 sm:text-sm @error('tanggal_lahir') border-rose-400 bg-rose-50/30 @enderror"
                            >
                        </div>
                        @error('tanggal_lahir')
                            <p class="text-xs font-medium text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Alamat Lengkap --}}
                    <div class="space-y-1.5 sm:col-span-2">
                        <label for="alamat_lengkap" class="block text-xs font-bold text-ink sm:text-sm">
                            Alamat Lengkap <span class="text-rose-500">*</span>
                        </label>
                        <textarea
                            name="alamat_lengkap"
                            id="alamat_lengkap"
                            rows="3"
                            required
                            placeholder="Alamat domisili lengkap (nama jalan, RT/RW, kelurahan, kecamatan, kota/kabupaten)"
                            class="block w-full rounded-xl border border-line p-3 text-xs font-medium text-ink placeholder:text-slate-400 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 sm:text-sm @error('alamat_lengkap') border-rose-400 bg-rose-50/30 @enderror"
                        >{{ old('alamat_lengkap', $user->alamat_lengkap) }}</textarea>
                        @error('alamat_lengkap')
                            <p class="text-xs font-medium text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Aksi Formulir --}}
                <div class="flex flex-col-reverse gap-3 border-t border-line bg-slate-50/50 px-6 py-4 sm:flex-row sm:items-center sm:justify-end">
                    <a href="{{ route('profile.show') }}" class="inline-flex min-h-[44px] items-center justify-center rounded-xl border border-line bg-white px-5 py-2.5 text-xs font-semibold text-slate-700 shadow-sm transition-all hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-slate-300 sm:text-sm">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex min-h-[44px] items-center justify-center gap-2 rounded-xl bg-primary px-6 py-2.5 text-xs font-semibold text-white shadow-sm transition-all hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-primary/20 sm:text-sm">
                        <i class="fa-solid fa-floppy-disk"></i>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>
            </div>
        </form>
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

                // Validasi ukuran klien: 200 KB = 200 * 1024 bytes = 204800 bytes
                if (file.size > 200 * 1024) {
                    if (warning) warning.classList.remove('hidden');
                } else {
                    if (warning) warning.classList.add('hidden');
                }

                // Tampilkan preview foto langsung
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
</script>
@endsection
