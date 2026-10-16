@extends('layouts.app')

@section('title', 'Manajemen User & Pengguna')
@section('description', 'Kelola data pengguna, peran, hak akses, dan informasi sivitas akademika perpustakaan MNC University.')

@section('content')
    <div class="mx-auto flex w-full max-w-[1570px] flex-1 flex-col gap-6 px-3 py-6 lg:px-6 lg:py-8">
        {{-- Breadcrumb & Header --}}
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <nav aria-label="Breadcrumb" class="flex items-center gap-2 text-xs text-muted sm:text-sm">
                    <a href="{{ route('beranda') }}" class="hover:text-primary">Beranda</a>
                    <svg class="size-3.5 text-muted/60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
                    <span class="text-ink-secondary">Panel Manajemen</span>
                    <svg class="size-3.5 text-muted/60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
                    <span class="font-medium text-ink">Manajemen User</span>
                </nav>
                <h1 class="mt-1 text-2xl font-bold tracking-tight text-primary sm:text-3xl">
                    Manajemen Pengguna Perpustakaan
                </h1>
                <p class="mt-0.5 text-xs text-ink-secondary sm:text-sm">
                    Kelola data pustakawan, mahasiswa, dosen, dan hak akses otentikasi.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a
                    href="{{ route('admin.users.create') }}"
                    class="inline-flex h-10 items-center gap-2 rounded-xl bg-primary px-4 text-xs font-semibold text-white shadow-sm transition-all hover:bg-primary-dark active:scale-[0.98] sm:h-11 sm:text-sm"
                >
                    <i class="fa-solid fa-user-plus text-xs"></i>
                    <span>Tambah Pengguna</span>
                </a>
            </div>
        </div>

        {{-- Filter & Pencarian Bar --}}
        <div class="rounded-2xl border border-line bg-white p-4 shadow-card">
            <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div class="relative flex-1">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </span>
                    <input
                        type="text"
                        name="q"
                        value="{{ $q }}"
                        placeholder="Cari nama, email, atau nomor identitas..."
                        class="h-10 w-full rounded-xl border border-line bg-subtle/50 pr-4 pl-10 text-xs text-ink placeholder:text-muted focus:border-primary focus:bg-white focus:outline-none sm:text-sm"
                    >
                </div>

                <div class="flex items-center gap-2">
                    <select
                        name="peran"
                        onchange="this.form.submit()"
                        class="h-10 rounded-xl border border-line bg-white px-3 text-xs text-ink focus:border-primary focus:outline-none sm:text-sm"
                    >
                        <option value="">Semua Peran</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id_peran }}" {{ $peranId === $role->id_peran ? 'selected' : '' }}>
                                {{ $role->nama_peran }}
                            </option>
                        @endforeach
                    </select>

                    <button
                        type="submit"
                        class="inline-flex h-10 items-center justify-center rounded-xl bg-primary/10 px-4 text-xs font-semibold text-primary transition-colors hover:bg-primary hover:text-white sm:text-sm"
                    >
                        Filter
                    </button>

                    @if ($q !== '' || $peranId !== '')
                        <a
                            href="{{ route('admin.users.index') }}"
                            class="inline-flex h-10 items-center justify-center rounded-xl border border-line px-3 text-xs font-medium text-muted transition-colors hover:bg-subtle hover:text-ink sm:text-sm"
                            title="Reset Filter"
                        >
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Tabel Pengguna --}}
        <div class="overflow-hidden rounded-2xl border border-line bg-white shadow-card">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm">
                    <thead class="border-b border-line bg-[#F8FAFC] text-[11px] font-semibold uppercase tracking-wider text-muted">
                        <tr>
                            <th scope="col" class="px-5 py-3.5">Pengguna</th>
                            <th scope="col" class="px-5 py-3.5">Peran</th>
                            <th scope="col" class="px-5 py-3.5">Program Studi</th>
                            <th scope="col" class="px-5 py-3.5">Nomor Identitas</th>
                            <th scope="col" class="px-5 py-3.5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line text-ink">
                        @forelse ($users as $user)
                            <tr class="transition-colors hover:bg-[#F9FAFB]">
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex size-9 shrink-0 items-center justify-center rounded-full bg-primary/10 font-bold text-primary">
                                            {{ strtoupper(substr($user->nama_lengkap, 0, 1)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-semibold text-ink">{{ $user->nama_lengkap }}</p>
                                            <p class="truncate text-xs text-muted">{{ $user->email }}</p>
                                            <span class="font-mono text-[10px] text-muted">{{ $user->id_pengguna }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap">
                                    @php
                                        $badgeColor = match ($user->peran?->nama_peran) {
                                            'Kepala Perpustakaan' => 'bg-amber-100 text-amber-800 border-amber-200',
                                            'Pustakawan' => 'bg-blue-100 text-blue-800 border-blue-200',
                                            'Mahasiswa' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                            'Dosen' => 'bg-purple-100 text-purple-800 border-purple-200',
                                            default => 'bg-gray-100 text-gray-800 border-gray-200',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-medium {{ $badgeColor }}">
                                        {{ $user->peran?->nama_peran ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap text-ink-secondary">
                                    {{ $user->prodi?->nama_prodi ?? '-' }}
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap font-mono text-xs text-muted">
                                    {{ $user->nomor_identitas ?? '-' }}
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap text-center">
                                    <div class="inline-flex items-center gap-1.5">
                                        <a
                                            href="{{ route('admin.users.edit', $user->id_pengguna) }}"
                                            class="inline-flex size-8 items-center justify-center rounded-lg border border-line text-muted transition-colors hover:border-primary hover:bg-primary/5 hover:text-primary"
                                            title="Ubah Data"
                                        >
                                            <i class="fa-regular fa-pen-to-square text-xs"></i>
                                        </a>

                                        @if (Auth::id() !== $user->id_pengguna)
                                            <form
                                                action="{{ route('admin.users.destroy', $user->id_pengguna) }}"
                                                method="POST"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna {{ $user->nama_lengkap }}?')"
                                                class="inline-block"
                                            >
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    type="submit"
                                                    class="inline-flex size-8 items-center justify-center rounded-lg border border-red-200 text-red-600 transition-colors hover:bg-red-50"
                                                    title="Hapus Pengguna"
                                                >
                                                    <i class="fa-regular fa-trash-can text-xs"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        <i class="fa-solid fa-users-slash text-3xl text-muted/40"></i>
                                        <p class="font-medium text-ink">Tidak ada data pengguna ditemukan.</p>
                                        <p class="text-xs text-muted">Coba ubah kata kunci pencarian atau filter peran.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($users->hasPages())
                <div class="border-t border-line px-5 py-4">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
