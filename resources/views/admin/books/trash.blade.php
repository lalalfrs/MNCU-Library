@extends('layouts.app')

@section('title', 'Kotak Sampah Koleksi Buku (Recycle Bin)')
@section('description', 'Kelola koleksi buku perpustakaan yang dihapus sementara. Pulihkan kembali ke katalog atau hapus secara permanen.')

@section('content')
    <div class="mx-auto flex w-full max-w-[1570px] flex-1 flex-col gap-6 px-3 sm:px-4 lg:px-6 xl:px-8 py-4 sm:py-6 lg:py-8 responsive-desktop-container">
        {{-- Breadcrumb & Header --}}
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <nav aria-label="Breadcrumb" class="flex items-center gap-2 text-xs text-muted sm:text-sm">
                    <a href="{{ route('beranda') }}" class="hover:text-primary">Beranda</a>
                    <svg class="size-3.5 text-muted/60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
                    <span class="text-ink-secondary">Panel Manajemen</span>
                    <svg class="size-3.5 text-muted/60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
                    <a href="{{ route('admin.books.index') }}" class="hover:text-primary">Manajemen Buku</a>
                    <svg class="size-3.5 text-muted/60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
                    <span class="font-medium text-rose-600">Kotak Sampah</span>
                </nav>
                <div class="mt-1 flex items-center gap-2.5">
                    <h1 class="text-2xl font-bold tracking-tight text-primary sm:text-3xl flex items-center gap-2.5">
                        <span class="flex size-9 items-center justify-center rounded-xl bg-rose-100 text-rose-600">
                            <i class="fa-solid fa-trash-can text-lg"></i>
                        </span>
                        <span>Kotak Sampah Koleksi (Recycle Bin)</span>
                    </h1>
                </div>
                <p class="mt-0.5 text-xs text-ink-secondary sm:text-sm">
                    Daftar koleksi buku yang telah dihapus sementara. Anda dapat memulihkan buku ke katalog aktif atau menghapusnya secara permanen.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2.5">
                {{-- Tombol Kembali ke Katalog Utama --}}
                <a
                    href="{{ route('admin.books.index') }}"
                    class="inline-flex h-10 items-center gap-2 rounded-xl border border-line bg-white px-4 text-xs font-semibold text-ink shadow-xs transition-all hover:bg-subtle hover:text-primary sm:h-11 sm:text-sm"
                >
                    <i class="fa-solid fa-arrow-left text-xs"></i>
                    <span>Kembali ke Katalog Buku</span>
                </a>

                @if ($totalTrashed > 0)
                    {{-- Pulihkan Semua --}}
                    <form
                        action="{{ route('admin.books.restore-all') }}"
                        method="POST"
                        onsubmit="return confirm('Pulihkan seluruh {{ $totalTrashed }} koleksi buku dari Kotak Sampah kembali ke katalog aktif?')"
                        class="inline-block"
                    >
                        @csrf
                        <button
                            type="submit"
                            class="inline-flex h-10 items-center gap-2 rounded-xl border border-emerald-300 bg-emerald-50 px-3.5 text-xs font-semibold text-emerald-800 shadow-xs transition-all hover:bg-emerald-100 sm:h-11 sm:text-sm"
                            title="Pulihkan Semua Buku dari Kotak Sampah"
                        >
                            <i class="fa-solid fa-rotate-left text-xs"></i>
                            <span>Pulihkan Semua ({{ $totalTrashed }})</span>
                        </button>
                    </form>

                    {{-- Kosongkan Kotak Sampah --}}
                    <form
                        action="{{ route('admin.books.empty-trash') }}"
                        method="POST"
                        onsubmit="return confirm('PERINGATAN KERAS: Semua buku di dalam Kotak Sampah akan dihapus secara PERMANEN beserta seluruh eksemplarnya. Tindakan ini tidak dapat dibatalkan. Lanjutkan?')"
                        class="inline-block"
                    >
                        @csrf
                        @method('DELETE')
                        <button
                            type="submit"
                            class="inline-flex h-10 items-center gap-2 rounded-xl bg-rose-600 px-3.5 text-xs font-semibold text-white shadow-xs transition-all hover:bg-rose-700 sm:h-11 sm:text-sm"
                            title="Hapus Permanen Seluruh Buku di Kotak Sampah"
                        >
                            <i class="fa-solid fa-trash text-xs"></i>
                            <span>Kosongkan Sampah</span>
                        </button>
                    </form>
                @endif
            </div>
        </div>

        {{-- Search Bar Kotak Sampah --}}
        <div class="rounded-2xl border border-line bg-white p-4 shadow-card">
            <form method="GET" action="{{ route('admin.books.trash') }}" class="flex flex-col gap-2.5 sm:flex-row sm:items-center">
                <div class="relative flex-1">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </span>
                    <input
                        type="text"
                        name="q"
                        value="{{ $q }}"
                        placeholder="Cari buku di kotak sampah berdasarkan judul, pengarang, nomor ISBN, atau barcode..."
                        class="h-10 w-full rounded-xl border border-line bg-subtle/50 pr-9 pl-10 text-xs text-ink placeholder:text-muted focus:border-rose-500 focus:bg-white focus:outline-none sm:text-sm"
                    >
                    @if ($q !== '')
                        <a
                            href="{{ route('admin.books.trash') }}"
                            class="absolute right-2.5 top-1/2 -translate-y-1/2 flex size-6 items-center justify-center rounded-md text-muted hover:bg-slate-200 hover:text-ink transition-colors"
                            title="Bersihkan Pencarian"
                        >
                            <i class="fa-solid fa-xmark text-xs"></i>
                        </a>
                    @endif
                </div>

                <div class="flex items-center gap-2 shrink-0">
                    <button
                        type="submit"
                        class="inline-flex h-10 items-center justify-center gap-2 rounded-xl bg-primary px-4 text-xs font-semibold text-white shadow-xs transition-colors hover:bg-primary-dark sm:text-sm"
                    >
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                        <span>Cari</span>
                    </button>
                    @if ($q !== '')
                        <a
                            href="{{ route('admin.books.trash') }}"
                            class="inline-flex h-10 items-center justify-center rounded-xl border border-line px-3 text-xs font-medium text-muted transition-colors hover:bg-subtle hover:text-ink sm:text-sm"
                            title="Reset Pencarian"
                        >
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Tabel Koleksi Terhapus --}}
        <div class="overflow-hidden rounded-2xl border border-line bg-white shadow-card">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between border-b border-line px-5 py-4 bg-slate-50/60">
                <div class="flex items-center gap-2">
                    <span class="flex size-7 items-center justify-center rounded-lg bg-rose-100 text-rose-700 text-xs">
                        <i class="fa-solid fa-dumpster"></i>
                    </span>
                    <h2 class="text-sm sm:text-base font-bold text-ink">
                        Daftar Buku Terhapus Sementara
                    </h2>
                </div>
                <span class="text-xs text-muted">
                    Total: <strong>{{ $trashedBooks->total() }}</strong> koleksi buku di Kotak Sampah
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm">
                    <thead class="border-b border-line bg-[#F8FAFC] text-[11px] font-semibold uppercase tracking-wider text-muted">
                        <tr>
                            <th scope="col" class="px-5 py-3.5">Koleksi Buku</th>
                            <th scope="col" class="px-5 py-3.5">Format &amp; Kategori</th>
                            <th scope="col" class="px-5 py-3.5">Stok Eksemplar</th>
                            <th scope="col" class="px-5 py-3.5">Waktu Dihapus</th>
                            <th scope="col" class="px-5 py-3.5 text-center">Opsi Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line text-ink">
                        @forelse ($trashedBooks as $book)
                            @php
                                $totalEk = $book->eksemplar->count();
                                $penulisStr = $book->penulis->pluck('nama_penulis')->join(', ') ?: 'Tim Perpustakaan';
                            @endphp
                            <tr class="transition-colors hover:bg-rose-50/25">
                                {{-- Judul & Cover --}}
                                <td class="px-5 py-4">
                                    <div class="flex items-start gap-3.5">
                                        <div class="relative h-16 w-11 shrink-0 overflow-hidden rounded-md border border-line bg-subtle shadow-xs opacity-80">
                                            <img
                                                src="{{ $book->sampul_url }}"
                                                alt="{{ $book->judul_buku }}"
                                                class="h-full w-full object-cover grayscale-50"
                                                onerror="this.onerror=null; this.src='{{ asset('images/buku/default-cover.jpg') }}';"
                                            >
                                        </div>
                                        <div class="min-w-0">
                                            <h3 class="font-semibold text-ink leading-snug line-clamp-2">
                                                {{ $book->judul_buku }}
                                            </h3>
                                            <p class="mt-0.5 text-xs text-muted truncate">
                                                Penulis: {{ $penulisStr }}
                                            </p>
                                            <div class="mt-1 flex items-center gap-2">
                                                <span class="font-mono text-[10px] text-muted">#{{ $book->id_buku }}</span>
                                                @if ($book->isbn)
                                                    <span class="font-mono text-[10px] text-muted">ISBN: {{ $book->isbn }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Format & Kategori --}}
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <div class="flex flex-col gap-1">
                                        <span class="inline-flex w-fit items-center rounded-full border border-blue-200 bg-blue-50 px-2.5 py-0.5 text-[11px] font-medium text-blue-800">
                                            {{ $book->jenisBuku?->nama_jenis_buku ?? 'Buku Cetak' }}
                                        </span>
                                        <span class="text-xs text-muted">{{ $book->kategoriBuku?->nama_kategori ?? '-' }}</span>
                                    </div>
                                </td>

                                {{-- Eksemplar --}}
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1.5 rounded-full border border-line bg-subtle px-2.5 py-0.5 text-[11px] font-semibold text-ink-secondary">
                                        <i class="fa-solid fa-boxes-stacked text-[10px] text-muted"></i>
                                        <span>{{ $totalEk }} Eksemplar Fisik</span>
                                    </span>
                                </td>

                                {{-- Waktu Dihapus --}}
                                <td class="px-5 py-4 whitespace-nowrap text-xs text-muted">
                                    <div class="flex items-center gap-1.5 text-rose-700 font-medium">
                                        <i class="fa-regular fa-clock text-[11px]"></i>
                                        <span>{{ $book->deleted_at?->translatedFormat('d M Y, H:i') }}</span>
                                    </div>
                                    <span class="text-[10px] text-muted">({{ $book->deleted_at?->diffForHumans() }})</span>
                                </td>

                                {{-- 2 Opsi Aksi: Restore & Hapus Permanen --}}
                                <td class="px-5 py-4 whitespace-nowrap text-center">
                                    <div class="inline-flex items-center gap-2">
                                        {{-- 1. OPSI RESTORE (PULIHKAN) --}}
                                        <form
                                            action="{{ route('admin.books.restore', $book->id_buku) }}"
                                            method="POST"
                                            onsubmit="return confirm('Pulihkan buku \"{{ addslashes($book->judul_buku) }}\" kembali ke katalog aktif?')"
                                            class="inline-block"
                                        >
                                            @csrf
                                            <button
                                                type="submit"
                                                class="inline-flex h-8 items-center gap-1.5 rounded-lg border border-emerald-300 bg-emerald-50 px-3 text-xs font-semibold text-emerald-800 transition-colors hover:bg-emerald-600 hover:text-white"
                                                title="Pulihkan buku ini ke katalog aktif"
                                            >
                                                <i class="fa-solid fa-rotate-left text-[11px]"></i>
                                                <span>Restore</span>
                                            </button>
                                        </form>

                                        {{-- 2. OPSI HAPUS PERMANEN (FORCE DELETE) --}}
                                        <form
                                            action="{{ route('admin.books.force-delete', $book->id_buku) }}"
                                            method="POST"
                                            onsubmit="return confirm('PERINGATAN: Buku \"{{ addslashes($book->judul_buku) }}\" akan dihapus secara PERMANEN beserta seluruh eksemplarnya. Tindakan ini tidak dapat dibatalkan. Lanjutkan?')"
                                            class="inline-block"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                class="inline-flex h-8 items-center gap-1.5 rounded-lg border border-rose-300 bg-rose-50 px-3 text-xs font-semibold text-rose-700 transition-colors hover:bg-rose-600 hover:text-white"
                                                title="Hapus permanen buku ini dari database"
                                            >
                                                <i class="fa-solid fa-trash-can text-[11px]"></i>
                                                <span>Hapus Permanen</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center gap-3">
                                        <div class="flex size-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                                            <i class="fa-solid fa-trash-can text-2xl"></i>
                                        </div>
                                        <div>
                                            <p class="font-bold text-ink text-base">Kotak Sampah Kosong</p>
                                            <p class="mt-1 text-xs text-muted max-w-md">
                                                Tidak ada koleksi buku yang sedang berada di dalam Kotak Sampah. Semua koleksi aktif tersedia di katalog utama.
                                            </p>
                                        </div>
                                        <a
                                            href="{{ route('admin.books.index') }}"
                                            class="mt-2 inline-flex items-center gap-2 rounded-xl bg-primary px-4 py-2 text-xs font-semibold text-white shadow-xs hover:bg-primary-dark transition-colors"
                                        >
                                            <i class="fa-solid fa-arrow-left text-xs"></i>
                                            <span>Kembali ke Katalog Utama</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($trashedBooks->hasPages())
                <div class="border-t border-line p-4">
                    {{ $trashedBooks->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

