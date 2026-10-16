@extends('layouts.app')

@section('title', 'Manajemen Buku & Katalog')
@section('description', 'Kelola koleksi buku fisik, e-book, kategori, penerbit, dan nomor ISBN perpustakaan MNC University.')

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
                    <span class="font-medium text-ink">Manajemen Buku</span>
                </nav>
                <h1 class="mt-1 text-2xl font-bold tracking-tight text-primary sm:text-3xl">
                    Manajemen Katalog Koleksi Buku
                </h1>
                <p class="mt-0.5 text-xs text-ink-secondary sm:text-sm">
                    Kelola koleksi buku cetak, e-book, data bibliografi, dan eksemplar rak perpustakaan.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a
                    href="{{ route('admin.books.create') }}"
                    class="inline-flex h-10 items-center gap-2 rounded-xl bg-primary px-4 text-xs font-semibold text-white shadow-sm transition-all hover:bg-primary-dark active:scale-[0.98] sm:h-11 sm:text-sm"
                >
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>Tambah Koleksi Buku</span>
                </a>
            </div>
        </div>

        {{-- Filter & Pencarian Bar --}}
        <div class="rounded-2xl border border-line bg-white p-4 shadow-card">
            <form method="GET" action="{{ route('admin.books.index') }}" class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div class="relative flex-1">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </span>
                    <input
                        type="text"
                        name="q"
                        value="{{ $q }}"
                        placeholder="Cari judul buku, ISBN, atau pengarang..."
                        class="h-10 w-full rounded-xl border border-line bg-subtle/50 pr-4 pl-10 text-xs text-ink placeholder:text-muted focus:border-primary focus:bg-white focus:outline-none sm:text-sm"
                    >
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <select
                        name="kategori"
                        onchange="this.form.submit()"
                        class="h-10 rounded-xl border border-line bg-white px-3 text-xs text-ink focus:border-primary focus:outline-none sm:text-sm"
                    >
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id_kategori_buku }}" {{ $kategoriId === $cat->id_kategori_buku ? 'selected' : '' }}>
                                {{ $cat->nama_kategori }}
                            </option>
                        @endforeach
                    </select>

                    <select
                        name="jenis"
                        onchange="this.form.submit()"
                        class="h-10 rounded-xl border border-line bg-white px-3 text-xs text-ink focus:border-primary focus:outline-none sm:text-sm"
                    >
                        <option value="">Semua Format</option>
                        @foreach ($types as $type)
                            <option value="{{ $type->id_jenis_buku }}" {{ $jenisId === $type->id_jenis_buku ? 'selected' : '' }}>
                                {{ $type->nama_jenis }}
                            </option>
                        @endforeach
                    </select>

                    <button
                        type="submit"
                        class="inline-flex h-10 items-center justify-center rounded-xl bg-primary/10 px-4 text-xs font-semibold text-primary transition-colors hover:bg-primary hover:text-white sm:text-sm"
                    >
                        Filter
                    </button>

                    @if ($q !== '' || $kategoriId !== '' || $jenisId !== '')
                        <a
                            href="{{ route('admin.books.index') }}"
                            class="inline-flex h-10 items-center justify-center rounded-xl border border-line px-3 text-xs font-medium text-muted transition-colors hover:bg-subtle hover:text-ink sm:text-sm"
                            title="Reset Filter"
                        >
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Tabel Koleksi Buku --}}
        <div class="overflow-hidden rounded-2xl border border-line bg-white shadow-card">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm">
                    <thead class="border-b border-line bg-[#F8FAFC] text-[11px] font-semibold uppercase tracking-wider text-muted">
                        <tr>
                            <th scope="col" class="px-5 py-3.5">Koleksi Buku</th>
                            <th scope="col" class="px-5 py-3.5">Format & Kategori</th>
                            <th scope="col" class="px-5 py-3.5">Penerbit & Tahun</th>
                            <th scope="col" class="px-5 py-3.5">ISBN</th>
                            <th scope="col" class="px-5 py-3.5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line text-ink">
                        @forelse ($books as $book)
                            <tr class="transition-colors hover:bg-[#F9FAFB]">
                                <td class="px-5 py-4">
                                    <div class="flex items-start gap-3.5">
                                        <div class="relative h-16 w-11 shrink-0 overflow-hidden rounded-md border border-line bg-subtle">
                                            @if ($book->sampul_buku)
                                                <img src="{{ $book->sampul_buku }}" alt="{{ $book->judul_buku }}" class="h-full w-full object-cover">
                                            @else
                                                <div class="flex h-full w-full items-center justify-center bg-primary/10 text-primary">
                                                    <i class="fa-solid fa-book text-sm"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-semibold text-ink leading-snug">{{ $book->judul_buku }}</p>
                                            <p class="mt-0.5 text-xs text-muted">
                                                Penulis: {{ $book->penulis->pluck('nama_penulis')->join(', ') ?: 'Tim Perpustakaan' }}
                                            </p>
                                            <span class="font-mono text-[10px] text-muted">{{ $book->id_buku }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <div class="flex flex-col gap-1">
                                        <span class="inline-flex w-fit items-center rounded-full border border-blue-200 bg-blue-50 px-2.5 py-0.5 text-[11px] font-medium text-blue-800">
                                            {{ $book->jenisBuku?->nama_jenis ?? 'Buku Cetak' }}
                                        </span>
                                        <span class="text-xs text-muted">{{ $book->kategoriBuku?->nama_kategori ?? '-' }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap text-ink-secondary">
                                    <div class="text-xs">
                                        <p class="font-medium text-ink">{{ $book->penerbit?->nama_penerbit ?? 'Penerbit MNCU' }}</p>
                                        <p class="text-muted">{{ $book->tahun_terbit ?? '-' }} {{ $book->edisi ? "• {$book->edisi}" : '' }}</p>
                                    </div>
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap font-mono text-xs text-muted">
                                    {{ $book->isbn ?? '-' }}
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap text-center">
                                    <div class="inline-flex items-center gap-1.5">
                                        <a
                                            href="{{ route('admin.books.edit', $book->id_buku) }}"
                                            class="inline-flex size-8 items-center justify-center rounded-lg border border-line text-muted transition-colors hover:border-primary hover:bg-primary/5 hover:text-primary"
                                            title="Ubah Buku"
                                        >
                                            <i class="fa-regular fa-pen-to-square text-xs"></i>
                                        </a>

                                        <form
                                            action="{{ route('admin.books.destroy', $book->id_buku) }}"
                                            method="POST"
                                            onsubmit="return confirm('Hapus buku {{ $book->judul_buku }} dari katalog?')"
                                            class="inline-block"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                class="inline-flex size-8 items-center justify-center rounded-lg border border-red-200 text-red-600 transition-colors hover:bg-red-50"
                                                title="Hapus Buku"
                                            >
                                                <i class="fa-regular fa-trash-can text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        <i class="fa-solid fa-book-open text-3xl text-muted/40"></i>
                                        <p class="font-medium text-ink">Tidak ada koleksi buku ditemukan.</p>
                                        <p class="text-xs text-muted">Coba ubah kata kunci pencarian atau filter kategori.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($books->hasPages())
                <div class="border-t border-line px-5 py-4">
                    {{ $books->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
