@extends('layouts.app')

@section('title', 'Tambah Koleksi Buku')

@section('content')
    <div class="mx-auto flex w-full max-w-4xl flex-1 flex-col gap-6 px-3 py-6 lg:px-6 lg:py-8">
        {{-- Breadcrumb --}}
        <div>
            <nav aria-label="Breadcrumb" class="flex items-center gap-2 text-xs text-muted sm:text-sm">
                <a href="{{ route('beranda') }}" class="hover:text-primary">Beranda</a>
                <svg class="size-3.5 text-muted/60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
                <a href="{{ route('admin.books.index') }}" class="hover:text-primary">Manajemen Buku</a>
                <svg class="size-3.5 text-muted/60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
                <span class="font-medium text-ink">Tambah Buku</span>
            </nav>
            <h1 class="mt-1 text-2xl font-bold tracking-tight text-primary sm:text-3xl">
                Tambah Koleksi Buku Baru
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
            <form action="{{ route('admin.books.store') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label for="judul_buku" class="block text-xs font-semibold text-ink">
                        Judul Buku <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        name="judul_buku"
                        id="judul_buku"
                        value="{{ old('judul_buku') }}"
                        required
                        placeholder="Contoh: Pengantar Algoritma dan Pemrograman Modern"
                        class="mt-1.5 h-11 w-full rounded-xl border border-line bg-white px-3.5 text-xs text-ink focus:border-primary focus:outline-none sm:text-sm"
                    >
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="id_kategori_buku" class="block text-xs font-semibold text-ink">
                            Kategori Buku <span class="text-red-500">*</span>
                        </label>
                        <select
                            name="id_kategori_buku"
                            id="id_kategori_buku"
                            required
                            class="mt-1.5 h-11 w-full rounded-xl border border-line bg-white px-3.5 text-xs text-ink focus:border-primary focus:outline-none sm:text-sm"
                        >
                            <option value="">Pilih Kategori</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id_kategori_buku }}" {{ old('id_kategori_buku') === $cat->id_kategori_buku ? 'selected' : '' }}>
                                    {{ $cat->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="id_jenis_buku" class="block text-xs font-semibold text-ink">
                            Format / Jenis Buku <span class="text-red-500">*</span>
                        </label>
                        <select
                            name="id_jenis_buku"
                            id="id_jenis_buku"
                            required
                            class="mt-1.5 h-11 w-full rounded-xl border border-line bg-white px-3.5 text-xs text-ink focus:border-primary focus:outline-none sm:text-sm"
                        >
                            <option value="">Pilih Format</option>
                            @foreach ($types as $type)
                                <option value="{{ $type->id_jenis_buku }}" {{ old('id_jenis_buku') === $type->id_jenis_buku ? 'selected' : '' }}>
                                    {{ $type->nama_jenis }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="id_penerbit" class="block text-xs font-semibold text-ink">
                            Penerbit
                        </label>
                        <select
                            name="id_penerbit"
                            id="id_penerbit"
                            class="mt-1.5 h-11 w-full rounded-xl border border-line bg-white px-3.5 text-xs text-ink focus:border-primary focus:outline-none sm:text-sm"
                        >
                            <option value="">Pilih Penerbit</option>
                            @foreach ($publishers as $pub)
                                <option value="{{ $pub->id_penerbit }}" {{ old('id_penerbit') === $pub->id_penerbit ? 'selected' : '' }}>
                                    {{ $pub->nama_penerbit }} ({{ $pub->kota }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="id_penulis" class="block text-xs font-semibold text-ink">
                            Pengarang Utama
                        </label>
                        <select
                            name="id_penulis"
                            id="id_penulis"
                            class="mt-1.5 h-11 w-full rounded-xl border border-line bg-white px-3.5 text-xs text-ink focus:border-primary focus:outline-none sm:text-sm"
                        >
                            <option value="">Pilih Pengarang</option>
                            @foreach ($authors as $auth)
                                <option value="{{ $auth->id_penulis }}" {{ old('id_penulis') === $auth->id_penulis ? 'selected' : '' }}>
                                    {{ $auth->nama_penulis }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid gap-5 sm:grid-cols-3">
                    <div>
                        <label for="isbn" class="block text-xs font-semibold text-ink">
                            Nomor ISBN
                        </label>
                        <input
                            type="text"
                            name="isbn"
                            id="isbn"
                            value="{{ old('isbn') }}"
                            placeholder="978-602-xxx"
                            class="mt-1.5 h-11 w-full rounded-xl border border-line bg-white px-3.5 text-xs text-ink focus:border-primary focus:outline-none sm:text-sm"
                        >
                    </div>

                    <div>
                        <label for="tahun_terbit" class="block text-xs font-semibold text-ink">
                            Tahun Terbit
                        </label>
                        <input
                            type="number"
                            name="tahun_terbit"
                            id="tahun_terbit"
                            value="{{ old('tahun_terbit', date('Y')) }}"
                            min="1900"
                            max="{{ date('Y') + 1 }}"
                            class="mt-1.5 h-11 w-full rounded-xl border border-line bg-white px-3.5 text-xs text-ink focus:border-primary focus:outline-none sm:text-sm"
                        >
                    </div>

                    <div>
                        <label for="jumlah_halaman" class="block text-xs font-semibold text-ink">
                            Jumlah Halaman
                        </label>
                        <input
                            type="number"
                            name="jumlah_halaman"
                            id="jumlah_halaman"
                            value="{{ old('jumlah_halaman') }}"
                            placeholder="Contoh: 350"
                            class="mt-1.5 h-11 w-full rounded-xl border border-line bg-white px-3.5 text-xs text-ink focus:border-primary focus:outline-none sm:text-sm"
                        >
                    </div>
                </div>

                <div>
                    <label for="sampul_buku" class="block text-xs font-semibold text-ink">
                        URL Sampul Buku (Opsional)
                    </label>
                    <input
                        type="url"
                        name="sampul_buku"
                        id="sampul_buku"
                        value="{{ old('sampul_buku') }}"
                        placeholder="https://example.com/cover.jpg"
                        class="mt-1.5 h-11 w-full rounded-xl border border-line bg-white px-3.5 text-xs text-ink focus:border-primary focus:outline-none sm:text-sm"
                    >
                </div>

                <div>
                    <label for="deskripsi" class="block text-xs font-semibold text-ink">
                        Sinopsis / Deskripsi Koleksi
                    </label>
                    <textarea
                        name="deskripsi"
                        id="deskripsi"
                        rows="4"
                        placeholder="Tuliskan ringkasan pokok bahasan buku..."
                        class="mt-1.5 w-full rounded-xl border border-line bg-white p-3.5 text-xs text-ink focus:border-primary focus:outline-none sm:text-sm"
                    >{{ old('deskripsi') }}</textarea>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-line">
                    <a
                        href="{{ route('admin.books.index') }}"
                        class="inline-flex h-10 items-center justify-center rounded-xl border border-line px-5 text-xs font-medium text-muted transition-colors hover:bg-subtle hover:text-ink sm:h-11 sm:text-sm"
                    >
                        Batal
                    </a>
                    <button
                        type="submit"
                        class="inline-flex h-10 items-center justify-center rounded-xl bg-primary px-6 text-xs font-semibold text-white shadow-sm transition-all hover:bg-primary-dark sm:h-11 sm:text-sm"
                    >
                        Simpan ke Katalog
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
