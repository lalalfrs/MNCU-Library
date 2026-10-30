@extends('layouts.app')

@section('title', 'Manajemen Berita & Artikel')
@section('description', 'Kelola publikasi artikel, berita, dan pengumuman resmi perpustakaan MNC University.')

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
                    <span class="font-medium text-ink">Manajemen Berita</span>
                </nav>
                <h1 class="mt-1 text-2xl font-bold tracking-tight text-primary sm:text-3xl">
                    Manajemen Berita & Informasi Perpustakaan
                </h1>
                <p class="mt-0.5 text-xs text-ink-secondary sm:text-sm">
                    Tulis dan publikasikan informasi layanan, pengumuman jadwal, dan artikel literasi kampus.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a
                    href="{{ route('admin.berita.create') }}"
                    class="inline-flex h-10 items-center gap-2 rounded-xl bg-primary px-4 text-xs font-semibold text-white shadow-sm transition-all hover:bg-primary-dark active:scale-[0.98] sm:h-11 sm:text-sm"
                >
                    <i class="fa-solid fa-pen-nib text-xs"></i>
                    <span>Tulis Berita Baru</span>
                </a>
            </div>
        </div>

        {{-- Filter & Pencarian Bar --}}
        <div class="rounded-2xl border border-line bg-white p-4 shadow-card">
            <form method="GET" action="{{ route('admin.berita.index') }}" class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div class="relative flex-1">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </span>
                    <input
                        type="text"
                        name="q"
                        value="{{ $q }}"
                        placeholder="Cari judul atau isi berita..."
                        class="h-10 w-full rounded-xl border border-line bg-subtle/50 pr-4 pl-10 text-xs text-ink placeholder:text-muted focus:border-primary focus:bg-white focus:outline-none sm:text-sm"
                    >
                </div>

                <div class="flex items-center gap-2">
                    <select
                        name="kategori"
                        onchange="this.form.submit()"
                        class="h-10 rounded-xl border border-line bg-white px-3 text-xs text-ink focus:border-primary focus:outline-none sm:text-sm"
                    >
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat }}" {{ $kategori === $cat ? 'selected' : '' }}>
                                {{ $cat }}
                            </option>
                        @endforeach
                    </select>

                    <button
                        type="submit"
                        class="inline-flex h-10 items-center justify-center rounded-xl bg-primary/10 px-4 text-xs font-semibold text-primary transition-colors hover:bg-primary hover:text-white sm:text-sm"
                    >
                        Filter
                    </button>

                    @if ($q !== '' || $kategori !== '')
                        <a
                            href="{{ route('admin.berita.index') }}"
                            class="inline-flex h-10 items-center justify-center rounded-xl border border-line px-3 text-xs font-medium text-muted transition-colors hover:bg-subtle hover:text-ink sm:text-sm"
                            title="Reset Filter"
                        >
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Tabel Berita --}}
        <div class="overflow-hidden rounded-2xl border border-line bg-white shadow-card">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm">
                    <thead class="border-b border-line bg-[#F8FAFC] text-[11px] font-semibold uppercase tracking-wider text-muted">
                        <tr>
                            <th scope="col" class="px-5 py-3.5">Artikel Berita</th>
                            <th scope="col" class="px-5 py-3.5">Kategori</th>
                            <th scope="col" class="px-5 py-3.5">Penulis</th>
                            <th scope="col" class="px-5 py-3.5">Tanggal Publikasi</th>
                            <th scope="col" class="px-5 py-3.5">Dilihat</th>
                            <th scope="col" class="px-5 py-3.5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line text-ink">
                        @forelse ($news as $item)
                            <tr class="transition-colors hover:bg-[#F9FAFB]">
                                <td class="px-5 py-4">
                                    <div class="flex items-start gap-3">
                                        @if ($item->image)
                                            <img src="{{ $item->image }}" alt="{{ $item->judul_berita }}" class="h-12 w-16 shrink-0 rounded-lg object-cover border border-line" onerror="this.onerror=null; this.src='{{ asset('images/berita/berita-digital-library.jpg') }}';">
                                        @endif
                                        <div class="min-w-0">
                                            <a href="{{ route('berita.show', $item->slug ?? $item->id_berita) }}" target="_blank" class="font-semibold text-ink hover:text-primary leading-snug">
                                                {{ $item->judul_berita }}
                                            </a>
                                            <p class="truncate text-xs text-muted mt-0.5">{{ $item->deskripsi_berita }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-0.5 text-xs font-medium text-emerald-800">
                                        {{ $item->kategori_berita }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap text-xs text-ink-secondary">
                                    {{ $item->pengguna?->nama_lengkap ?? 'Admin Perpustakaan' }}
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap text-xs text-muted">
                                    {{ \Carbon\Carbon::parse($item->tanggal_publikasi)->translatedFormat('d M Y') }}
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap font-mono text-xs text-muted">
                                    {{ number_format($item->views_count) }}x
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap text-center">
                                    <div class="inline-flex items-center gap-1.5">
                                        <a
                                            href="{{ route('admin.berita.edit', $item->id_berita) }}"
                                            class="inline-flex size-8 items-center justify-center rounded-lg border border-line text-muted transition-colors hover:border-primary hover:bg-primary/5 hover:text-primary"
                                            title="Ubah Berita"
                                        >
                                            <i class="fa-regular fa-pen-to-square text-xs"></i>
                                        </a>

                                        <form
                                            action="{{ route('admin.berita.destroy', $item->id_berita) }}"
                                            method="POST"
                                            onsubmit="return confirm('Hapus artikel berita {{ $item->judul_berita }}?')"
                                            class="inline-block"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                class="inline-flex size-8 items-center justify-center rounded-lg border border-red-200 text-red-600 transition-colors hover:bg-red-50"
                                                title="Hapus Berita"
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
                                        <i class="fa-regular fa-newspaper text-3xl text-muted/40"></i>
                                        <p class="font-medium text-ink">Tidak ada artikel berita ditemukan.</p>
                                        <p class="text-xs text-muted">Coba ubah kata kunci pencarian atau filter kategori.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($news->hasPages())
                <div class="border-t border-line px-5 py-4">
                    {{ $news->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
