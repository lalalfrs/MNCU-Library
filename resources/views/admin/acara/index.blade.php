@extends('layouts.app')

@section('title', 'Manajemen Acara & Agenda')
@section('description', 'Kelola agenda webinar, workshop literasi, dan kegiatan perpustakaan MNC University.')

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
                    <span class="font-medium text-ink">Manajemen Acara</span>
                </nav>
                <h1 class="mt-1 text-2xl font-bold tracking-tight text-primary sm:text-3xl">
                    Manajemen Acara & Kegiatan Perpustakaan
                </h1>
                <p class="mt-0.5 text-xs text-ink-secondary sm:text-sm">
                    Publikasikan webinar, workshop literasi informasi, bedah buku, dan kegiatan pemustaka.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a
                    href="{{ route('admin.acara.create') }}"
                    class="inline-flex h-10 items-center gap-2 rounded-xl bg-primary px-4 text-xs font-semibold text-white shadow-sm transition-all hover:bg-primary-dark active:scale-[0.98] sm:h-11 sm:text-sm"
                >
                    <i class="fa-solid fa-calendar-plus text-xs"></i>
                    <span>Tambah Acara Baru</span>
                </a>
            </div>
        </div>

        {{-- Filter & Pencarian Bar --}}
        <div class="rounded-2xl border border-line bg-white p-4 shadow-card">
            <form method="GET" action="{{ route('admin.acara.index') }}" class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div class="relative flex-1">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </span>
                    <input
                        type="text"
                        name="q"
                        value="{{ $q }}"
                        placeholder="Cari nama acara, narasumber, atau lokasi..."
                        class="h-10 w-full rounded-xl border border-line bg-subtle/50 pr-4 pl-10 text-xs text-ink placeholder:text-muted focus:border-primary focus:bg-white focus:outline-none sm:text-sm"
                    >
                </div>

                <div class="flex items-center gap-2">
                    <select
                        name="status"
                        onchange="this.form.submit()"
                        class="h-10 rounded-xl border border-line bg-white px-3 text-xs text-ink focus:border-primary focus:outline-none sm:text-sm"
                    >
                        <option value="">Semua Status</option>
                        <option value="aktif" {{ $status === 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ $status === 'nonaktif' ? 'selected' : '' }}>Selesai / Non-aktif</option>
                    </select>

                    <button
                        type="submit"
                        class="inline-flex h-10 items-center justify-center rounded-xl bg-primary/10 px-4 text-xs font-semibold text-primary transition-colors hover:bg-primary hover:text-white sm:text-sm"
                    >
                        Filter
                    </button>

                    @if ($q !== '' || $status !== '')
                        <a
                            href="{{ route('admin.acara.index') }}"
                            class="inline-flex h-10 items-center justify-center rounded-xl border border-line px-3 text-xs font-medium text-muted transition-colors hover:bg-subtle hover:text-ink sm:text-sm"
                            title="Reset Filter"
                        >
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Tabel Acara --}}
        <div class="overflow-hidden rounded-2xl border border-line bg-white shadow-card">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm">
                    <thead class="border-b border-line bg-[#F8FAFC] text-[11px] font-semibold uppercase tracking-wider text-muted">
                        <tr>
                            <th scope="col" class="px-5 py-3.5">Nama Acara</th>
                            <th scope="col" class="px-5 py-3.5">Kategori & Narasumber</th>
                            <th scope="col" class="px-5 py-3.5">Waktu Pelaksanaan</th>
                            <th scope="col" class="px-5 py-3.5">Lokasi / Kuota</th>
                            <th scope="col" class="px-5 py-3.5">Status</th>
                            <th scope="col" class="px-5 py-3.5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line text-ink">
                        @forelse ($events as $event)
                            <tr class="transition-colors hover:bg-[#F9FAFB]">
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        @if ($event->image)
                                            <img src="{{ $event->image }}" alt="{{ $event->nama_acara }}" class="size-12 rounded-lg object-cover border border-line shrink-0">
                                        @else
                                            <div class="flex size-12 shrink-0 items-center justify-center rounded-lg bg-subtle border border-line text-muted">
                                                <i class="fa-regular fa-calendar-check text-base"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="font-semibold text-ink leading-snug">{{ $event->nama_acara }}</div>
                                            <span class="font-mono text-[10px] text-muted">{{ $event->id_acara }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center rounded-full border border-orange-200 bg-orange-50 px-2.5 py-0.5 text-xs font-medium text-orange-800">
                                        {{ $event->kategori_acara }}
                                    </span>
                                    <p class="mt-1 text-xs text-muted">Oleh: {{ $event->narasumber ?: 'Tim Perpustakaan' }}</p>
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap text-xs text-ink-secondary">
                                    <div class="font-medium text-ink">
                                        {{ \Carbon\Carbon::parse($event->tanggal_mulai)->translatedFormat('d F Y') }}
                                    </div>
                                    <div class="text-muted">
                                        {{ \Carbon\Carbon::parse($event->tanggal_mulai)->format('H:i') }} WIB
                                    </div>
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap text-xs text-ink-secondary">
                                    <p class="font-medium text-ink">{{ $event->lokasi_acara ?: 'Online via Zoom' }}</p>
                                    <p class="text-muted">{{ $event->kuota ? "Kuota: {$event->kuota} orang" : 'Kuota Tak Terbatas' }}</p>
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap">
                                    @if ($event->is_active)
                                        <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-0.5 text-xs font-medium text-emerald-800">
                                            <span class="size-1.5 rounded-full bg-emerald-500"></span>
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full border border-gray-200 bg-gray-50 px-2.5 py-0.5 text-xs font-medium text-gray-600">
                                            Selesai
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap text-center">
                                    <div class="inline-flex items-center gap-1.5">
                                        <a
                                            href="{{ route('admin.acara.edit', $event->id_acara) }}"
                                            class="inline-flex size-8 items-center justify-center rounded-lg border border-line text-muted transition-colors hover:border-primary hover:bg-primary/5 hover:text-primary"
                                            title="Ubah Acara"
                                        >
                                            <i class="fa-regular fa-pen-to-square text-xs"></i>
                                        </a>

                                        <form
                                            action="{{ route('admin.acara.destroy', $event->id_acara) }}"
                                            method="POST"
                                            onsubmit="return confirm('Hapus acara {{ $event->nama_acara }}?')"
                                            class="inline-block"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                class="inline-flex size-8 items-center justify-center rounded-lg border border-red-200 text-red-600 transition-colors hover:bg-red-50"
                                                title="Hapus Acara"
                                            >
                                                <i class="fa-regular fa-trash-can text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        <i class="fa-regular fa-calendar-xmark text-3xl text-muted/40"></i>
                                        <p class="font-medium text-ink">Tidak ada agenda acara ditemukan.</p>
                                        <p class="text-xs text-muted">Coba ubah kata kunci pencarian atau filter status.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($events->hasPages())
                <div class="border-t border-line px-5 py-4">
                    {{ $events->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
