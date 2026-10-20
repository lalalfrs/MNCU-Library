@extends('layouts.app')

@section('title', 'Ubah Data Pengguna')

@section('content')
    <div class="mx-auto flex w-full max-w-4xl flex-1 flex-col gap-6 px-3 py-6 lg:px-6 lg:py-8">
        {{-- Breadcrumb --}}
        <div>
            <nav aria-label="Breadcrumb" class="flex items-center gap-2 text-xs text-muted sm:text-sm">
                <a href="{{ route('beranda') }}" class="hover:text-primary">Beranda</a>
                <svg class="size-3.5 text-muted/60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
                <a href="{{ route('admin.users.index') }}" class="hover:text-primary">Manajemen User</a>
                <svg class="size-3.5 text-muted/60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
                <span class="font-medium text-ink">Ubah Pengguna</span>
            </nav>
            <h1 class="mt-1 text-2xl font-bold tracking-tight text-primary sm:text-3xl">
                Ubah Data Pengguna: {{ $user->nama_lengkap }}
            </h1>
            <p class="text-xs font-mono text-muted">{{ $user->id_pengguna }} • {{ $user->email }}</p>
        </div>

        @if (session('success'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50/90 p-4 text-xs sm:text-sm text-emerald-900 shadow-xs">
                <div class="flex items-start gap-3">
                    <div class="flex size-7 shrink-0 items-center justify-center rounded-lg bg-emerald-600 text-white">
                        <i class="fa-solid fa-circle-check text-xs"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-emerald-950">{{ session('success') }}</p>
                        @if (session('reset_password_preview'))
                            <div class="mt-2 flex flex-wrap items-center gap-2 rounded-lg border border-emerald-300 bg-white px-3 py-2 text-xs">
                                <span class="font-medium text-emerald-800">Password Baru Akun:</span>
                                <code class="rounded bg-emerald-100 px-2 py-0.5 font-mono font-bold text-emerald-900 text-sm select-all">{{ session('reset_password_preview') }}</code>
                                <span class="text-[11px] text-muted">(Salin dan informasikan kepada pengguna bersangkutan)</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-xs font-medium text-red-800">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form Box --}}
        <div class="rounded-2xl border border-line bg-white p-6 shadow-card sm:p-8">
            <form action="{{ route('admin.users.update', $user->id_pengguna) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="nama_lengkap" class="block text-xs font-semibold text-ink">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            name="nama_lengkap"
                            id="nama_lengkap"
                            value="{{ old('nama_lengkap', $user->nama_lengkap) }}"
                            required
                            class="mt-1.5 h-11 w-full rounded-xl border border-line bg-white px-3.5 text-xs text-ink focus:border-primary focus:outline-none sm:text-sm"
                        >
                    </div>

                    <div>
                        <label for="email" class="block text-xs font-semibold text-ink">
                            Email Resmi <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="email"
                            name="email"
                            id="email"
                            value="{{ old('email', $user->email) }}"
                            required
                            class="mt-1.5 h-11 w-full rounded-xl border border-line bg-white px-3.5 text-xs text-ink focus:border-primary focus:outline-none sm:text-sm"
                        >
                    </div>
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="id_peran" class="block text-xs font-semibold text-ink">
                            Peran Pengguna <span class="text-red-500">*</span>
                        </label>
                        <select
                            name="id_peran"
                            id="id_peran"
                            required
                            class="mt-1.5 h-11 w-full rounded-xl border border-line bg-white px-3.5 text-xs text-ink focus:border-primary focus:outline-none sm:text-sm"
                        >
                            @foreach ($roles as $role)
                                <option value="{{ $role->id_peran }}" {{ old('id_peran', $user->id_peran) === $role->id_peran ? 'selected' : '' }}>
                                    {{ $role->nama_peran }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="id_prodi" class="block text-xs font-semibold text-ink">
                            Program Studi (Opsional)
                        </label>
                        <select
                            name="id_prodi"
                            id="id_prodi"
                            class="mt-1.5 h-11 w-full rounded-xl border border-line bg-white px-3.5 text-xs text-ink focus:border-primary focus:outline-none sm:text-sm"
                        >
                            <option value="">Tidak terafiliasi prodi (Staf/Pustakawan)</option>
                            @foreach ($prodis as $prodi)
                                <option value="{{ $prodi->id_prodi }}" {{ old('id_prodi', $user->id_prodi) === $prodi->id_prodi ? 'selected' : '' }}>
                                    {{ $prodi->nama_prodi }} ({{ $prodi->jenjang }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="nomor_identitas" class="block text-xs font-semibold text-ink">
                            Nomor Identitas (NIM / NIDN / NIP)
                        </label>
                        <input
                            type="text"
                            name="nomor_identitas"
                            id="nomor_identitas"
                            value="{{ old('nomor_identitas', $user->nomor_identitas) }}"
                            class="mt-1.5 h-11 w-full rounded-xl border border-line bg-white px-3.5 text-xs text-ink focus:border-primary focus:outline-none sm:text-sm"
                        >
                    </div>

                    <div>
                        <label for="kata_sandi" class="block text-xs font-semibold text-ink">
                            Ganti Kata Sandi (Kosongkan jika tidak diubah)
                        </label>
                        <input
                            type="password"
                            name="kata_sandi"
                            id="kata_sandi"
                            placeholder="Biarkan kosong jika tidak ingin mengubah"
                            class="mt-1.5 h-11 w-full rounded-xl border border-line bg-white px-3.5 text-xs text-ink focus:border-primary focus:outline-none sm:text-sm"
                        >
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-line">
                    <a
                        href="{{ route('admin.users.index') }}"
                        class="inline-flex h-10 items-center justify-center rounded-xl border border-line px-5 text-xs font-medium text-muted transition-colors hover:bg-subtle hover:text-ink sm:h-11 sm:text-sm"
                    >
                        Batal
                    </a>
                    <button
                        type="submit"
                        class="inline-flex h-10 items-center justify-center rounded-xl bg-primary px-6 text-xs font-semibold text-white shadow-sm transition-all hover:bg-primary-dark sm:h-11 sm:text-sm"
                    >
                        Perbarui Pengguna
                    </button>
                </div>
            </form>
        </div>

        {{-- Skenario 2: Panel Khusus Reset Password Pengguna oleh Admin --}}
        <div class="rounded-2xl border border-amber-200/80 bg-white p-6 shadow-card sm:p-8">
            <div class="flex items-center gap-3 border-b border-line pb-4">
                <span class="flex size-10 items-center justify-center rounded-xl bg-amber-500/10 text-amber-700">
                    <i class="fa-solid fa-key text-base"></i>
                </span>
                <div>
                    <h2 class="text-base font-bold text-ink sm:text-lg">Reset Password oleh Administrator</h2>
                    <p class="text-xs text-muted">
                        Admin dapat mereset password akun pengguna ini tanpa perlu mengetahui password lama.
                    </p>
                </div>
            </div>

            <div class="mt-6 grid gap-6 md:grid-cols-2">
                {{-- Opsi 1: Reset Cepat ke Password Default --}}
                <div class="flex flex-col justify-between rounded-xl border border-line bg-subtle/50 p-5">
                    <div>
                        <div class="flex items-center gap-2 text-xs font-bold text-ink sm:text-sm">
                            <i class="fa-solid fa-wand-magic-sparkles text-amber-600"></i>
                            <span>Reset ke Password Standar Cepat</span>
                        </div>
                        <p class="mt-1 text-xs text-muted leading-relaxed">
                            Secara otomatis membuat password standar:
                            <code class="font-mono font-semibold text-amber-900">Mncu#{{ $user->nomor_identitas ?: substr($user->id_pengguna, -5) }}</code>
                        </p>
                    </div>

                    <form action="{{ route('admin.users.reset-password', $user->id_pengguna) }}" method="POST" class="mt-4" onsubmit="return confirm('Reset password akun {{ $user->nama_lengkap }} ke password default?')">
                        @csrf
                        <input type="hidden" name="reset_to_default" value="1">
                        <button
                            type="submit"
                            class="inline-flex h-10 w-full items-center justify-center gap-2 rounded-xl bg-amber-600 px-4 text-xs font-semibold text-white shadow-xs hover:bg-amber-700 transition-colors"
                        >
                            <i class="fa-solid fa-rotate-left text-xs"></i>
                            <span>Reset ke Password Default</span>
                        </button>
                    </form>
                </div>

                {{-- Opsi 2: Set Password Baru Manual --}}
                <div class="rounded-xl border border-line bg-white p-5">
                    <div class="flex items-center gap-2 text-xs font-bold text-ink sm:text-sm">
                        <i class="fa-solid fa-pen-to-square text-primary"></i>
                        <span>Tentukan Password Baru Manual</span>
                    </div>
                    <p class="mt-1 text-xs text-muted leading-relaxed">
                        Ketik password baru khusus untuk pengguna sasaran.
                    </p>

                    <form action="{{ route('admin.users.reset-password', $user->id_pengguna) }}" method="POST" class="mt-4 space-y-3">
                        @csrf
                        <div>
                            <input
                                type="password"
                                name="password_baru"
                                required
                                minlength="6"
                                placeholder="Password baru (min. 6 karakter)..."
                                class="h-10 w-full rounded-xl border border-line bg-subtle/40 px-3 text-xs text-ink placeholder:text-muted/70 focus:border-primary focus:bg-white focus:outline-none sm:text-sm"
                            >
                        </div>
                        <div>
                            <input
                                type="password"
                                name="password_baru_confirmation"
                                required
                                minlength="6"
                                placeholder="Ulangi password baru..."
                                class="h-10 w-full rounded-xl border border-line bg-subtle/40 px-3 text-xs text-ink placeholder:text-muted/70 focus:border-primary focus:bg-white focus:outline-none sm:text-sm"
                            >
                        </div>
                        <button
                            type="submit"
                            class="inline-flex h-10 w-full items-center justify-center gap-2 rounded-xl bg-primary px-4 text-xs font-semibold text-white shadow-xs hover:bg-primary-dark transition-colors"
                        >
                            <i class="fa-solid fa-check text-xs"></i>
                            <span>Terapkan Password Baru</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
