@extends('layouts.app')

@section('title', 'Profil Saya - ' . ($user->nama_lengkap ?? 'Pengguna'))
@section('description', 'Halaman informasi profil pemustaka dan staf perpustakaan MNC University.')

@section('content')
<div class="min-h-screen bg-slate-50/70 py-8 lg:py-12">
    <div class="mx-auto w-full max-w-5xl px-4 sm:px-6 lg:px-8">
        {{-- Header & Breadcrumbs --}}
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <nav class="flex items-center gap-2 text-xs text-muted" aria-label="Breadcrumb">
                    <a href="{{ route('beranda') }}" class="hover:text-primary transition-colors">Beranda</a>
                    <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                    <span class="font-medium text-ink">Profil Pengguna</span>
                </nav>
                <h1 class="mt-2 text-2xl font-bold tracking-tight text-ink sm:text-3xl">Profil Pengguna</h1>
                <p class="text-xs text-muted sm:text-sm">Rincian identitas dan informasi keanggotaan Perpustakaan MNC University.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('profile.edit') }}" class="inline-flex min-h-[44px] items-center gap-2 rounded-xl bg-primary px-5 py-2.5 text-xs font-semibold text-white shadow-sm transition-all hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-primary/20 sm:text-sm">
                    <i class="fa-solid fa-user-pen"></i>
                    <span>Edit Profil</span>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            {{-- Kolom Kiri: Kartu Ringkasan Akun --}}
            <div class="space-y-6 lg:col-span-1">
                <div class="overflow-hidden rounded-2xl border border-line bg-white p-6 text-center shadow-card">
                    {{-- Foto Profil --}}
                    <div class="mx-auto size-28 overflow-hidden rounded-2xl border-2 border-line bg-slate-100 shadow-sm sm:size-32">
                        @if ($user->foto_profil)
                            <img src="{{ asset('storage/' . $user->foto_profil) }}" alt="{{ $user->nama_lengkap }}" class="size-full object-cover">
                        @else
                            <div class="flex size-full items-center justify-center bg-primary/10 text-3xl font-extrabold text-primary">
                                {{ strtoupper(substr($user->nama_lengkap ?? 'U', 0, 1)) }}
                            </div>
                        @endif
                    </div>

                    {{-- Nama & Role --}}
                    <h2 class="mt-4 text-base font-bold text-ink sm:text-lg">{{ $user->nama_lengkap }}</h2>
                    <p class="text-xs text-muted truncate">{{ $user->email }}</p>

                    <div class="mt-3">
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

                    <div class="mt-6 border-t border-line pt-4 text-left space-y-3">
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-muted">Status Keanggotaan</span>
                            <span class="font-semibold text-emerald-600 flex items-center gap-1">
                                <i class="fa-solid fa-circle-check text-[10px]"></i> Aktif
                            </span>
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-muted">Terdaftar Sejak</span>
                            <span class="font-medium text-ink">{{ $user->created_at ? $user->created_at->format('d M Y') : '16 Okt 2026' }}</span>
                        </div>
                    </div>
                </div>

                {{-- Bantuan / Akses Cepat --}}
                <div class="rounded-2xl border border-line bg-white p-5 shadow-card space-y-3">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-muted">Aksi Cepat</h3>
                    <div class="space-y-1.5">
                        <a href="{{ route('profile.edit') }}" class="flex items-center justify-between rounded-xl p-2.5 text-xs font-medium text-ink transition-colors hover:bg-slate-50">
                            <span class="flex items-center gap-2.5">
                                <i class="fa-solid fa-user-gear text-primary"></i>
                                <span>Perbarui Data Diri</span>
                            </span>
                            <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                        </a>
                        <a href="{{ route('opac') }}" class="flex items-center justify-between rounded-xl p-2.5 text-xs font-medium text-ink transition-colors hover:bg-slate-50">
                            <span class="flex items-center gap-2.5">
                                <i class="fa-solid fa-book text-emerald-600"></i>
                                <span>Cari Buku di OPAC</span>
                            </span>
                            <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                        </a>
                        <a href="{{ route('chat') }}" class="flex items-center justify-between rounded-xl p-2.5 text-xs font-medium text-ink transition-colors hover:bg-slate-50">
                            <span class="flex items-center gap-2.5">
                                <i class="fa-solid fa-comments text-amber-600"></i>
                                <span>Bantuan Perpustakaan</span>
                            </span>
                            <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Kolom Kanan: Rincian Data Lengkap --}}
            <div class="space-y-6 lg:col-span-2">
                {{-- Data Khusus Role --}}
                <div class="overflow-hidden rounded-2xl border border-line bg-white shadow-card">
                    <div class="border-b border-line bg-slate-50/50 px-6 py-4 flex items-center justify-between">
                        <h2 class="text-sm font-bold text-ink sm:text-base flex items-center gap-2">
                            <i class="fa-solid fa-id-card text-primary"></i>
                            <span>Identitas Khusus Peran</span>
                        </h2>
                        <span class="text-xs font-medium text-muted">Peran: {{ ucfirst($role) }}</span>
                    </div>

                    <div class="p-6">
                        @if ($role === 'mahasiswa')
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <span class="block text-xs font-medium text-muted">Nomor Induk Mahasiswa (NIM)</span>
                                    <span class="mt-1 block text-sm font-bold text-ink">
                                        {{ $user->nim ?? $user->nomor_identitas ?? 'Belum diisi' }}
                                    </span>
                                </div>
                                <div>
                                    <span class="block text-xs font-medium text-muted">Status Akademik</span>
                                    <span class="mt-1 inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-700">
                                        <i class="fa-solid fa-user-check"></i> Mahasiswa Aktif MNC University
                                    </span>
                                </div>
                            </div>
                        @elseif ($role === 'dosen')
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <span class="block text-xs font-medium text-muted">Nomor Induk Dosen Nasional (NIDN)</span>
                                    <span class="mt-1 block text-sm font-bold text-ink">
                                        {{ $user->nidn ?? $user->nomor_identitas ?? 'Belum diisi' }}
                                    </span>
                                </div>
                                <div>
                                    <span class="block text-xs font-medium text-muted">Status Pendidik</span>
                                    <span class="mt-1 inline-flex items-center gap-1.5 text-xs font-semibold text-violet-700">
                                        <i class="fa-solid fa-chalkboard-user"></i> Tenaga Pendidik / Peneliti
                                    </span>
                                </div>
                            </div>
                        @elseif ($role === 'admin')
                            <div class="flex items-center gap-3 rounded-xl border border-blue-200 bg-blue-50/70 p-4 text-xs text-blue-900 sm:text-sm">
                                <i class="fa-solid fa-shield-halved text-blue-600 text-lg"></i>
                                <div>
                                    <p class="font-semibold">Administrator Perpustakaan</p>
                                    <p class="text-xs text-blue-800">Akun ini memiliki hak akses penuh pengelolaan operasional, katalog buku, acara, dan pemustaka.</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Data Umum --}}
                <div class="overflow-hidden rounded-2xl border border-line bg-white shadow-card">
                    <div class="border-b border-line bg-slate-50/50 px-6 py-4 flex items-center justify-between">
                        <h2 class="text-sm font-bold text-ink sm:text-base flex items-center gap-2">
                            <i class="fa-solid fa-address-book text-primary"></i>
                            <span>Data Umum & Kontak</span>
                        </h2>
                        <a href="{{ route('profile.edit') }}" class="text-xs font-semibold text-primary hover:underline">
                            Ubah
                        </a>
                    </div>

                    <div class="p-6">
                        <dl class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-2">
                            <div>
                                <dt class="text-xs font-medium text-muted">Nama Lengkap</dt>
                                <dd class="mt-1 text-xs font-semibold text-ink sm:text-sm">{{ $user->nama_lengkap ?? '-' }}</dd>
                            </div>

                            <div>
                                <dt class="text-xs font-medium text-muted">Alamat Email</dt>
                                <dd class="mt-1 text-xs font-semibold text-ink sm:text-sm">{{ $user->email ?? '-' }}</dd>
                            </div>

                            <div>
                                <dt class="text-xs font-medium text-muted">Nomor WhatsApp</dt>
                                <dd class="mt-1 text-xs font-semibold text-ink sm:text-sm flex items-center gap-1.5">
                                    <i class="fa-brands fa-whatsapp text-emerald-600"></i>
                                    <span>{{ $user->no_wa ?? 'Belum diatur' }}</span>
                                </dd>
                            </div>

                            <div>
                                <dt class="text-xs font-medium text-muted">Nomor Induk Kependudukan (NIK)</dt>
                                <dd class="mt-1 text-xs font-semibold text-ink sm:text-sm">{{ $user->nik ?? 'Belum diatur' }}</dd>
                            </div>

                            <div>
                                <dt class="text-xs font-medium text-muted">Tempat Lahir</dt>
                                <dd class="mt-1 text-xs font-semibold text-ink sm:text-sm">{{ $user->tempat_lahir ?? 'Belum diatur' }}</dd>
                            </div>

                            <div>
                                <dt class="text-xs font-medium text-muted">Tanggal Lahir</dt>
                                <dd class="mt-1 text-xs font-semibold text-ink sm:text-sm">
                                    {{ $user->tanggal_lahir ? $user->tanggal_lahir->format('d F Y') : 'Belum diatur' }}
                                </dd>
                            </div>

                            <div class="sm:col-span-2">
                                <dt class="text-xs font-medium text-muted">Alamat Lengkap</dt>
                                <dd class="mt-1 text-xs font-medium text-ink sm:text-sm leading-relaxed">
                                    {{ $user->alamat_lengkap ?? 'Belum diatur' }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
