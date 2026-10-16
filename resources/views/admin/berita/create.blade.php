@extends('layouts.app')

@section('title', 'Tulis Berita Baru')

@section('content')
    <div class="mx-auto flex w-full max-w-4xl flex-1 flex-col gap-6 px-3 py-6 lg:px-6 lg:py-8">
        {{-- Breadcrumb --}}
        <div>
            <nav aria-label="Breadcrumb" class="flex items-center gap-2 text-xs text-muted sm:text-sm">
                <a href="{{ route('beranda') }}" class="hover:text-primary">Beranda</a>
                <svg class="size-3.5 text-muted/60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
                <a href="{{ route('admin.berita.index') }}" class="hover:text-primary">Manajemen Berita</a>
                <svg class="size-3.5 text-muted/60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
                <span class="font-medium text-ink">Tulis Berita</span>
            </nav>
            <h1 class="mt-1 text-2xl font-bold tracking-tight text-primary sm:text-3xl">
                Tulis Berita & Pengumuman Baru
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
            <form action="{{ route('admin.berita.store') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label for="judul_berita" class="block text-xs font-semibold text-ink">
                        Judul Berita / Artikel <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        name="judul_berita"
                        id="judul_berita"
                        value="{{ old('judul_berita') }}"
                        required
                        placeholder="Contoh: Peresmian Ruang Riset Digital dan Akses E-Journal Terbaru MNC University"
                        class="mt-1.5 h-11 w-full rounded-xl border border-line bg-white px-3.5 text-xs text-ink focus:border-primary focus:outline-none sm:text-sm"
                    >
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="kategori_berita" class="block text-xs font-semibold text-ink">
                            Kategori Berita <span class="text-red-500">*</span>
                        </label>
                        <select
                            name="kategori_berita"
                            id="kategori_berita"
                            required
                            class="mt-1.5 h-11 w-full rounded-xl border border-line bg-white px-3.5 text-xs text-ink focus:border-primary focus:outline-none sm:text-sm"
                        >
                            <option value="Pengumuman" {{ old('kategori_berita') === 'Pengumuman' ? 'selected' : '' }}>Pengumuman</option>
                            <option value="Layanan & Fasilitas" {{ old('kategori_berita') === 'Layanan & Fasilitas' ? 'selected' : '' }}>Layanan & Fasilitas</option>
                            <option value="Prestasi & Kegiatan" {{ old('kategori_berita') === 'Prestasi & Kegiatan' ? 'selected' : '' }}>Prestasi & Kegiatan</option>
                            <option value="Koleksi Baru" {{ old('kategori_berita') === 'Koleksi Baru' ? 'selected' : '' }}>Koleksi Baru</option>
                            <option value="Tips Literasi" {{ old('kategori_berita') === 'Tips Literasi' ? 'selected' : '' }}>Tips Literasi</option>
                        </select>
                    </div>

                    <div>
                        <label for="tanggal_publikasi" class="block text-xs font-semibold text-ink">
                            Tanggal Publikasi <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="date"
                            name="tanggal_publikasi"
                            id="tanggal_publikasi"
                            value="{{ old('tanggal_publikasi', date('Y-m-d')) }}"
                            required
                            class="mt-1.5 h-11 w-full rounded-xl border border-line bg-white px-3.5 text-xs text-ink focus:border-primary focus:outline-none sm:text-sm"
                        >
                    </div>
                </div>

                <div>
                    <label for="gambar_berita" class="block text-xs font-semibold text-ink">
                        URL Gambar Banner / Thumbnail (Opsional)
                    </label>
                    <input
                        type="url"
                        name="gambar_berita"
                        id="gambar_berita"
                        value="{{ old('gambar_berita') }}"
                        placeholder="https://images.unsplash.com/photo-..."
                        class="mt-1.5 h-11 w-full rounded-xl border border-line bg-white px-3.5 text-xs text-ink focus:border-primary focus:outline-none sm:text-sm"
                    >
                </div>

                <div>
                    <label for="deskripsi_berita" class="block text-xs font-semibold text-ink">
                        Ringkasan Singkat (Lead Paragraph) <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        name="deskripsi_berita"
                        id="deskripsi_berita"
                        value="{{ old('deskripsi_berita') }}"
                        required
                        placeholder="Ringkasan 1-2 kalimat pengantar berita..."
                        class="mt-1.5 h-11 w-full rounded-xl border border-line bg-white px-3.5 text-xs text-ink focus:border-primary focus:outline-none sm:text-sm"
                    >
                </div>

                <div>
                    <label for="isi_berita" class="block text-xs font-semibold text-ink">
                        Isi Lengkap Berita <span class="text-red-500">*</span>
                    </label>
                    <textarea
                        name="isi_berita"
                        id="isi_berita"
                        rows="8"
                        required
                        placeholder="Tuliskan isi berita lengkap di sini..."
                        class="mt-1.5 w-full rounded-xl border border-line bg-white p-3.5 text-xs text-ink focus:border-primary focus:outline-none sm:text-sm leading-relaxed"
                    >{{ old('isi_berita') }}</textarea>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-line">
                    <a
                        href="{{ route('admin.berita.index') }}"
                        class="inline-flex h-10 items-center justify-center rounded-xl border border-line px-5 text-xs font-medium text-muted transition-colors hover:bg-subtle hover:text-ink sm:h-11 sm:text-sm"
                    >
                        Batal
                    </a>
                    <button
                        type="submit"
                        class="inline-flex h-10 items-center justify-center rounded-xl bg-primary px-6 text-xs font-semibold text-white shadow-sm transition-all hover:bg-primary-dark sm:h-11 sm:text-sm"
                    >
                        Terbitkan Berita
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
