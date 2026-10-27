@extends('layouts.app')

@section('title', 'Tambah Koleksi Buku')

@section('content')
    <div class="mx-auto flex w-full max-w-5xl flex-1 flex-col gap-6 px-3 py-6 lg:px-6 lg:py-8">
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
            <p class="mt-0.5 text-xs text-ink-secondary sm:text-sm">
                Lengkapi rincian bibliografi buku, pengarang, dan tautan gambar sampul dengan pratinjau langsung.
            </p>
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

        {{-- Form Box with 2-Column Grid (Preview on Left/Top, Form on Right) --}}
        <div class="grid gap-6 lg:grid-cols-12">
            {{-- Panel Kiri: Live Preview Sampul Buku --}}
            <div class="lg:col-span-4">
                <div class="sticky top-24 rounded-2xl border border-line bg-white p-5 shadow-card">
                    <div class="flex items-center justify-between border-b border-line pb-3">
                        <span class="text-xs font-semibold uppercase tracking-wider text-primary">
                            <i class="fa-solid fa-eye mr-1.5"></i>Live Preview
                        </span>
                        <span id="preview-status-badge" class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-[11px] font-medium text-slate-600">
                            Menunggu URL
                        </span>
                    </div>

                    {{-- Card Mockup Buku --}}
                    <div class="mt-4 flex flex-col items-center">
                        <div class="relative aspect-[3/4] w-44 sm:w-48 overflow-hidden rounded-xl border-2 border-line bg-gradient-to-br from-subtle to-slate-100 shadow-md transition-all duration-300">
                            {{-- Placeholder jika gambar belum ada atau error --}}
                            <div id="cover-preview-placeholder" class="flex h-full w-full flex-col items-center justify-center p-4 text-center text-muted">
                                <div class="flex size-14 items-center justify-center rounded-2xl bg-primary/10 text-primary">
                                    <i class="fa-solid fa-book text-2xl"></i>
                                </div>
                                <span class="mt-3 text-xs font-medium text-ink">Pratinjau Sampul</span>
                                <span class="mt-1 text-[11px] text-muted leading-tight">Masukkan URL sampul buku di samping</span>
                            </div>

                            {{-- Gambar Sampul Nyata --}}
                            <img
                                id="cover-preview-img"
                                src=""
                                alt="Pratinjau Sampul Buku"
                                class="hidden h-full w-full object-cover transition-opacity duration-300"
                            >
                        </div>

                        {{-- Refleksi Judul & Pengarang di Bawah Pratinjau --}}
                        <div class="mt-4 w-full text-center">
                            <p id="preview-title-display" class="line-clamp-2 text-sm font-semibold text-ink">
                                [Judul buku akan tampil di sini]
                            </p>
                            <p id="preview-author-display" class="mt-1 truncate text-xs text-muted">
                                Pengarang: -
                            </p>
                        </div>
                    </div>

                    {{-- Preset Gambar Cepat --}}
                    <div class="mt-5 border-t border-line pt-3.5">
                        <p class="text-[11px] font-medium text-muted">Contoh gambar cepat:</p>
                        <div class="mt-2 flex flex-wrap gap-1.5">
                            <button
                                type="button"
                                onclick="applySampleCover('images/buku/buku-01.jpg')"
                                class="rounded-lg border border-line bg-subtle px-2 py-1 text-[11px] text-ink transition-colors hover:border-primary hover:text-primary"
                            >
                                Komputer
                            </button>
                            <button
                                type="button"
                                onclick="applySampleCover('images/buku/buku-02.jpg')"
                                class="rounded-lg border border-line bg-subtle px-2 py-1 text-[11px] text-ink transition-colors hover:border-primary hover:text-primary"
                            >
                                Bisnis
                            </button>
                            <button
                                type="button"
                                onclick="applySampleCover('images/buku/buku-03.jpg')"
                                class="rounded-lg border border-line bg-subtle px-2 py-1 text-[11px] text-ink transition-colors hover:border-primary hover:text-primary"
                            >
                                Desain
                            </button>
                            <button
                                type="button"
                                onclick="applySampleCover('images/buku/buku-05.jpg')"
                                class="rounded-lg border border-line bg-subtle px-2 py-1 text-[11px] text-ink transition-colors hover:border-primary hover:text-primary"
                            >
                                Pengembangan Diri
                            </button>
                            <button
                                type="button"
                                onclick="applySampleCover('images/buku/default-cover.jpg')"
                                class="rounded-lg border border-primary/40 bg-primary/5 px-2 py-1 text-[11px] font-semibold text-primary transition-colors hover:bg-primary/10"
                            >
                                Standar MNCU
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Panel Kanan: Form Entri Data Buku --}}
            <div class="lg:col-span-8">
                <div class="rounded-2xl border border-line bg-white p-6 shadow-card sm:p-8">
                    <form action="{{ route('admin.books.store') }}" method="POST" class="space-y-5">
                        @csrf

                        {{-- Judul Buku --}}
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

                        {{-- Kategori & Format Buku --}}
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
                                            {{ $type->nama_jenis_buku }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Penerbit & Pengarang Utama --}}
                        <div class="space-y-4 rounded-xl border border-line bg-subtle/30 p-4">
                            <div class="grid gap-5 sm:grid-cols-2">
                                <div>
                                    <label for="id_penerbit" class="block text-xs font-semibold text-ink">
                                        Penerbit Buku
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
                                        Pilih Pengarang Terdaftar
                                    </label>
                                    <select
                                        name="id_penulis"
                                        id="id_penulis"
                                        class="mt-1.5 h-11 w-full rounded-xl border border-line bg-white px-3.5 text-xs text-ink focus:border-primary focus:outline-none sm:text-sm"
                                    >
                                        <option value="">-- Pilih dari Pengarang yang Ada --</option>
                                        @foreach ($authors as $auth)
                                            <option value="{{ $auth->id_penulis }}" {{ old('id_penulis') === $auth->id_penulis ? 'selected' : '' }}>
                                                {{ $auth->nama_penulis }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- Input Manual Pengarang Baru via Keyboard --}}
                            <div class="border-t border-line/80 pt-3">
                                <label for="nama_penulis_manual" class="flex items-center justify-between text-xs font-semibold text-ink">
                                    <span>
                                        <i class="fa-solid fa-keyboard mr-1.5 text-primary"></i>Atau Tulis Manual Nama Pengarang Baru
                                    </span>
                                    <span class="text-[11px] font-normal text-muted">Ketik langsung di keyboard</span>
                                </label>
                                <div class="relative mt-1.5">
                                    <input
                                        type="text"
                                        name="nama_penulis_manual"
                                        id="nama_penulis_manual"
                                        value="{{ old('nama_penulis_manual') }}"
                                        placeholder="Ketik nama lengkap pengarang baru (contoh: Prof. Budi Rahardjo, Ph.D.)..."
                                        class="h-11 w-full rounded-xl border border-line bg-white pr-9 pl-3.5 text-xs text-ink placeholder:text-muted focus:border-primary focus:outline-none sm:text-sm"
                                    >
                                    <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-muted">
                                        <i class="fa-solid fa-pen-nib text-xs"></i>
                                    </span>
                                </div>
                                <p class="mt-1 text-[11px] text-muted">
                                    Jika diisi, sistem akan otomatis mendaftarkan nama pengarang baru ini ke basis data dan menghubungkannya dengan buku.
                                </p>
                            </div>
                        </div>

                        {{-- ISBN, Tahun, Halaman --}}
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

                        {{-- URL Sampul Buku (Terhubung ke Live Preview) --}}
                        <div>
                            <div class="flex items-center justify-between">
                                <label for="sampul_buku" class="block text-xs font-semibold text-ink">
                                    URL Sampul Buku (Opsional)
                                </label>
                                <span class="text-[11px] text-muted">Preview otomatis terupdate saat diketik</span>
                            </div>
                            <div class="relative mt-1.5">
                                <input
                                    type="url"
                                    name="sampul_buku"
                                    id="sampul_buku"
                                    value="{{ old('sampul_buku') }}"
                                    placeholder="https://images.unsplash.com/... atau URL gambar sampul"
                                    class="h-11 w-full rounded-xl border border-line bg-white pr-20 pl-3.5 text-xs text-ink placeholder:text-muted focus:border-primary focus:outline-none sm:text-sm"
                                >
                                <button
                                    type="button"
                                    onclick="clearCoverInput()"
                                    id="btn-clear-cover"
                                    class="hidden absolute inset-y-1.5 right-2 inline-flex items-center rounded-lg bg-slate-100 px-2.5 text-xs font-medium text-muted hover:bg-slate-200 hover:text-ink transition-colors"
                                >
                                    Reset
                                </button>
                            </div>
                        </div>

                        {{-- Sinopsis / Deskripsi --}}
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

                        {{-- Pengaturan Eksemplar Fisik & Stok Awal --}}
                        <div class="space-y-3.5 rounded-xl border border-emerald-200 bg-emerald-50/40 p-4">
                            <div class="flex items-center justify-between">
                                <span class="flex items-center gap-2 text-xs font-bold text-emerald-950">
                                    <i class="fa-solid fa-boxes-stacked text-emerald-700"></i>
                                    Registrasi Eksemplar Fisik &amp; Stok Awal (Opsional)
                                </span>
                                <span class="rounded bg-emerald-100 px-2 py-0.5 text-[10px] font-semibold text-emerald-800">Inventaris Rak</span>
                            </div>
                            <p class="text-[11px] text-emerald-900/80 leading-snug">
                                Tentukan jumlah salinan fisik buku yang langsung tersedia di rak perpustakaan. Sistem otomatis men-generate barcode inventaris dan nomor panggil per eksemplar.
                            </p>

                            <div class="grid gap-4 sm:grid-cols-3">
                                <div>
                                    <label for="stok_awal" class="block text-xs font-semibold text-ink">
                                        Jumlah Stok Awal
                                    </label>
                                    <input
                                        type="number"
                                        name="stok_awal"
                                        id="stok_awal"
                                        value="{{ old('stok_awal', 1) }}"
                                        min="0"
                                        max="50"
                                        placeholder="Misal: 2"
                                        class="mt-1 h-10 w-full rounded-xl border border-line bg-white px-3 text-xs text-ink focus:border-primary focus:outline-none"
                                    >
                                    <span class="text-[10px] text-muted">Isi 0 jika buku digital / belum ada fisik</span>
                                </div>

                                <div>
                                    <label for="nomor_panggil_dasar" class="block text-xs font-semibold text-ink">
                                        Nomor Panggil Dasar
                                    </label>
                                    <input
                                        type="text"
                                        name="nomor_panggil_dasar"
                                        id="nomor_panggil_dasar"
                                        value="{{ old('nomor_panggil_dasar') }}"
                                        placeholder="Contoh: 005.1 HID a"
                                        class="mt-1 h-10 w-full rounded-xl border border-line bg-white px-3 text-xs text-ink focus:border-primary focus:outline-none font-mono"
                                    >
                                    <span class="text-[10px] text-muted">Akan diberi suffix c.1, c.2, dst.</span>
                                </div>

                                <div>
                                    <label for="lokasi_rak_dasar" class="block text-xs font-semibold text-ink">
                                        Lokasi Rak Koleksi
                                    </label>
                                    <input
                                        type="text"
                                        name="lokasi_rak_dasar"
                                        id="lokasi_rak_dasar"
                                        value="{{ old('lokasi_rak_dasar', 'Lantai 2, Rak Koleksi') }}"
                                        placeholder="Contoh: Lantai 2, Rak Komputer 03"
                                        class="mt-1 h-10 w-full rounded-xl border border-line bg-white px-3 text-xs text-ink focus:border-primary focus:outline-none"
                                    >
                                    <span class="text-[10px] text-muted">Letak fisik buku di gedung</span>
                                </div>
                            </div>
                        </div>

                        {{-- Aksi --}}
                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-line">
                            <a
                                href="{{ route('admin.books.index') }}"
                                class="inline-flex h-10 items-center justify-center rounded-xl border border-line px-5 text-xs font-medium text-muted transition-colors hover:bg-subtle hover:text-ink sm:h-11 sm:text-sm"
                            >
                                Batal
                            </a>
                            <button
                                type="submit"
                                class="inline-flex h-10 items-center justify-center rounded-xl bg-primary px-6 text-xs font-semibold text-white shadow-sm transition-all hover:bg-primary-dark active:scale-[0.98] sm:h-11 sm:text-sm"
                            >
                                <i class="fa-solid fa-floppy-disk mr-1.5"></i>Simpan ke Katalog
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Script Interaktif: Live Cover Preview & Author Mirroring --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const coverInput = document.getElementById('sampul_buku');
            const previewImg = document.getElementById('cover-preview-img');
            const previewPlaceholder = document.getElementById('cover-preview-placeholder');
            const previewStatusBadge = document.getElementById('preview-status-badge');
            const btnClearCover = document.getElementById('btn-clear-cover');

            const titleInput = document.getElementById('judul_buku');
            const previewTitle = document.getElementById('preview-title-display');

            const authorSelect = document.getElementById('id_penulis');
            const authorManual = document.getElementById('nama_penulis_manual');
            const previewAuthor = document.getElementById('preview-author-display');

            // 1. Update Sampul Buku Preview
            function updateCoverPreview() {
                const url = coverInput.value.trim();

                if (!url) {
                    previewImg.classList.add('hidden');
                    previewImg.src = '';
                    previewPlaceholder.classList.remove('hidden');
                    previewStatusBadge.textContent = 'Menunggu URL';
                    previewStatusBadge.className = 'inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-[11px] font-medium text-slate-600';
                    btnClearCover.classList.add('hidden');
                    return;
                }

                btnClearCover.classList.remove('hidden');
                previewStatusBadge.textContent = 'Memuat...';
                previewStatusBadge.className = 'inline-flex items-center rounded-full bg-amber-100 px-2.5 py-0.5 text-[11px] font-medium text-amber-800';

                let testUrl = url;
                if (!url.startsWith('http://') && !url.startsWith('https://') && !url.startsWith('data:')) {
                    testUrl = '{{ asset("") }}' + url.replace(/^\/+/, '');
                }

                const testImage = new Image();
                testImage.onload = function () {
                    previewImg.src = testUrl;
                    previewImg.classList.remove('hidden');
                    previewPlaceholder.classList.add('hidden');
                    previewStatusBadge.textContent = 'Pratinjau Aktif';
                    previewStatusBadge.className = 'inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-[11px] font-medium text-emerald-800';
                };
                testImage.onerror = function () {
                    previewImg.classList.add('hidden');
                    previewPlaceholder.classList.remove('hidden');
                    previewStatusBadge.textContent = 'Gambar Gagal Dimuat';
                    previewStatusBadge.className = 'inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-[11px] font-medium text-red-800';
                };
                testImage.src = testUrl;
            }

            coverInput.addEventListener('input', updateCoverPreview);
            coverInput.addEventListener('change', updateCoverPreview);

            window.clearCoverInput = function () {
                coverInput.value = '';
                updateCoverPreview();
                coverInput.focus();
            };

            window.applySampleCover = function (sampleUrl) {
                coverInput.value = sampleUrl;
                updateCoverPreview();
            };

            // 2. Mirror Judul Buku
            titleInput.addEventListener('input', function () {
                const title = titleInput.value.trim();
                previewTitle.textContent = title || '[Judul buku akan tampil di sini]';
            });

            // 3. Mirror Pengarang (Manual vs Dropdown)
            function updateAuthorPreview() {
                const manual = authorManual.value.trim();
                if (manual) {
                    previewAuthor.textContent = 'Pengarang: ' + manual;
                    return;
                }
                const selectedOpt = authorSelect.options[authorSelect.selectedIndex];
                if (selectedOpt && selectedOpt.value) {
                    previewAuthor.textContent = 'Pengarang: ' + selectedOpt.text;
                } else {
                    previewAuthor.textContent = 'Pengarang: -';
                }
            }

            authorManual.addEventListener('input', function () {
                if (authorManual.value.trim()) {
                    authorSelect.value = '';
                }
                updateAuthorPreview();
            });

            authorSelect.addEventListener('change', function () {
                if (authorSelect.value) {
                    authorManual.value = '';
                }
                updateAuthorPreview();
            });

            // Inisialisasi awal saat load
            if (coverInput.value) {
                updateCoverPreview();
            }
            if (titleInput.value) {
                previewTitle.textContent = titleInput.value;
            }
            updateAuthorPreview();
        });
    </script>
@endsection

