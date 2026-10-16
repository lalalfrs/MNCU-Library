@extends('layouts.app')

@section('title', 'Tambah Pengguna Baru')

@section('content')
    <div class="mx-auto flex w-full max-w-4xl flex-1 flex-col gap-6 px-3 py-6 lg:px-6 lg:py-8">
        {{-- Breadcrumb --}}
        <div>
            <nav aria-label="Breadcrumb" class="flex items-center gap-2 text-xs text-muted sm:text-sm">
                <a href="{{ route('beranda') }}" class="hover:text-primary">Beranda</a>
                <svg class="size-3.5 text-muted/60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
                <a href="{{ route('admin.users.index') }}" class="hover:text-primary">Manajemen User</a>
                <svg class="size-3.5 text-muted/60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
                <span class="font-medium text-ink">Tambah Pengguna</span>
            </nav>
            <h1 class="mt-1 text-2xl font-bold tracking-tight text-primary sm:text-3xl">
                Tambah Pengguna Baru
            </h1>
        </div>

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
            <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-5">
                @csrf

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="nama_lengkap" class="block text-xs font-semibold text-ink">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            name="nama_lengkap"
                            id="nama_lengkap"
                            value="{{ old('nama_lengkap') }}"
                            required
                            placeholder="Contoh: Ahmad Maulana, M.Kom."
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
                            value="{{ old('email') }}"
                            required
                            placeholder="nama@mncu.ac.id"
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
                            <option value="">Pilih Peran</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id_peran }}" {{ old('id_peran') === $role->id_peran ? 'selected' : '' }}>
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
                                <option value="{{ $prodi->id_prodi }}" {{ old('id_prodi') === $prodi->id_prodi ? 'selected' : '' }}>
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
                            value="{{ old('nomor_identitas') }}"
                            placeholder="Contoh: 202401001"
                            class="mt-1.5 h-11 w-full rounded-xl border border-line bg-white px-3.5 text-xs text-ink focus:border-primary focus:outline-none sm:text-sm"
                        >
                    </div>

                    <div>
                        <label for="kata_sandi" class="block text-xs font-semibold text-ink">
                            Kata Sandi Awal <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="password"
                            name="kata_sandi"
                            id="kata_sandi"
                            required
                            placeholder="Minimal 6 karakter"
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
                        Simpan Pengguna
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
