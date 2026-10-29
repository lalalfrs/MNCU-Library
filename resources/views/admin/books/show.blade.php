@extends('layouts.app')

@section('title', 'Detail Koleksi & Stok: ' . $book->judul_buku)
@section('description', 'Detail bibliografi dan manajemen inventaris stok eksemplar fisik buku perpustakaan MNC University.')

@section('content')
    <div class="mx-auto flex w-full max-w-[1570px] flex-1 flex-col gap-6 px-3 py-6 lg:px-6 lg:py-8">
        {{-- Breadcrumb --}}
        <div>
            <nav aria-label="Breadcrumb" class="flex items-center gap-2 text-xs text-muted sm:text-sm">
                <a href="{{ route('beranda') }}" class="hover:text-primary">Beranda</a>
                <svg class="size-3.5 text-muted/60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
                <a href="{{ route('admin.books.index') }}" class="hover:text-primary">Manajemen Buku</a>
                <svg class="size-3.5 text-muted/60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
                <span class="font-medium text-ink">Detail &amp; Stok Eksemplar</span>
            </nav>

            <div class="mt-2 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-primary sm:text-3xl">
                        {{ $book->judul_buku }}
                    </h1>
                    <p class="mt-0.5 text-xs text-ink-secondary sm:text-sm">
                        ID Katalog: <span class="font-mono font-bold text-primary">{{ $book->id_buku }}</span> &bull; Kelola data fisik, nomor panggil, lokasi rak, dan ketersediaan buku.
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-2.5">
                    <a
                        href="{{ route('opac', ['buku' => $book->id_buku]) }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex h-10 items-center gap-1.5 rounded-xl border border-emerald-300 bg-emerald-50 px-4 text-xs font-semibold text-emerald-800 shadow-xs hover:bg-emerald-100 transition-all sm:text-sm"
                        title="Buka Tampilan Detail Buku di Katalog OPAC (Tab Baru)"
                    >
                        <i class="fa-solid fa-magnifying-glass text-xs text-emerald-700"></i>
                        <span>Lihat di OPAC</span>
                    </a>

                    <a
                        href="{{ route('admin.books.edit', $book->id_buku) }}"
                        class="inline-flex h-10 items-center gap-1.5 rounded-xl border border-line bg-white px-4 text-xs font-semibold text-ink shadow-xs hover:border-primary hover:text-primary transition-all sm:text-sm"
                    >
                        <i class="fa-regular fa-pen-to-square text-xs"></i>
                        <span>Ubah Katalog</span>
                    </a>

                    <a
                        href="{{ route('admin.books.index') }}"
                        class="inline-flex h-10 items-center gap-1.5 rounded-xl border border-line bg-subtle px-4 text-xs font-medium text-muted hover:bg-line hover:text-ink transition-colors sm:text-sm"
                    >
                        <i class="fa-solid fa-arrow-left text-xs"></i>
                        <span>Kembali</span>
                    </a>
                </div>
            </div>
        </div>



        {{-- Grid 2 Kolom: Kiri Profil Bibliografi, Kanan Metrik Stok & Manajemen Eksemplar --}}
        <div class="grid gap-6 lg:grid-cols-12">
            {{-- Panel Kiri: Sampul & Rincian Koleksi --}}
            <div class="lg:col-span-4 space-y-5">
                <div class="rounded-2xl border border-line bg-white p-5 shadow-card">
                    {{-- Cover --}}
                    <div class="relative aspect-[3/4] w-full overflow-hidden rounded-xl border border-line bg-subtle shadow-xs">
                        <img src="{{ $book->sampul_url }}" alt="{{ $book->judul_buku }}" class="h-full w-full object-cover" onerror="this.onerror=null; this.src='{{ asset('images/buku/default-cover.jpg') }}';">
                    </div>

                    {{-- Spesifikasi Bibliografi Ringkas --}}
                    <div class="mt-5 space-y-3 text-xs border-t border-line pt-4">
                        <div class="flex items-center justify-between">
                            <span class="text-muted">Kategori:</span>
                            <span class="font-semibold text-primary">{{ $book->kategoriBuku?->nama_kategori ?? '-' }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-muted">Format Koleksi:</span>
                            <span class="rounded bg-subtle px-2 py-0.5 font-medium text-ink">{{ $book->jenisBuku?->nama_jenis_buku ?? 'Buku Cetak' }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-muted">Pengarang:</span>
                            <span class="font-medium text-ink text-right">{{ $book->penulis->pluck('nama_penulis')->join(', ') ?: '-' }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-muted">Penerbit:</span>
                            <span class="font-medium text-ink">{{ $book->penerbit?->nama_penerbit ?? '-' }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-muted">Kota Terbit:</span>
                            <span class="font-medium text-ink">{{ $book->penerbit?->kota ?? 'Jakarta' }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-muted">Tahun &amp; Edisi:</span>
                            <span class="font-medium text-ink">{{ $book->tahun_terbit ?? '-' }} {{ $book->edisi ? "({$book->edisi})" : '' }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-muted">Jumlah Halaman:</span>
                            <span class="font-medium text-ink">{{ $book->jumlah_halaman ? "{$book->jumlah_halaman} halaman" : '-' }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-muted">ISBN:</span>
                            <code class="rounded bg-subtle px-1.5 py-0.5 font-mono text-[11px] text-ink border border-line">{{ $book->isbn ?? '-' }}</code>
                        </div>
                    </div>
                </div>

                {{-- Sinopsis Buku --}}
                <div class="rounded-2xl border border-line bg-white p-5 shadow-card">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-muted">Sinopsis &amp; Deskripsi</h3>
                    <p class="mt-2 text-xs leading-relaxed text-ink-secondary whitespace-pre-line">
                        {{ $book->deskripsi ?: 'Tidak ada sinopsis untuk koleksi ini.' }}
                    </p>
                </div>

                {{-- Kata Kunci Buku (Pencarian OPAC) --}}
                <div class="rounded-2xl border border-line bg-white p-5 shadow-card">
                    <div class="flex items-center justify-between border-b border-line pb-3">
                        <div class="flex items-center gap-2">
                            <span class="flex size-7 items-center justify-center rounded-lg bg-primary/10 text-primary">
                                <i class="fa-solid fa-tags text-xs"></i>
                            </span>
                            <h3 class="text-xs font-bold uppercase tracking-wider text-ink">Kata Kunci Pencarian OPAC</h3>
                        </div>
                        <button
                            type="button"
                            onclick="document.getElementById('form-edit-kata-kunci').classList.toggle('hidden')"
                            class="inline-flex items-center gap-1 text-[11px] font-semibold text-primary hover:text-primary-dark transition-colors"
                        >
                            <i class="fa-solid fa-pen text-[10px]"></i>
                            <span>Kelola Kata Kunci</span>
                        </button>
                    </div>

                    {{-- Tag Kata Kunci Aktif --}}
                    <div class="mt-3">
                        @if (! empty($book->kata_kunci_list))
                            <div class="flex flex-wrap gap-1.5">
                                @foreach ($book->kata_kunci_list as $kw)
                                    <span class="inline-flex items-center gap-1 rounded-lg border border-primary/25 bg-primary/5 px-2.5 py-1 text-xs font-semibold text-primary">
                                        <i class="fa-solid fa-hashtag text-[9px] opacity-70"></i>
                                        <span>{{ $kw }}</span>
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <p class="text-xs italic text-muted">
                                Belum ada kata kunci yang ditambahkan untuk buku ini.
                            </p>
                        @endif
                    </div>

                    {{-- Form Cepat Update Kata Kunci (Bisa dibuka/tutup) --}}
                    <form
                        id="form-edit-kata-kunci"
                        action="{{ route('admin.books.kata-kunci.update', $book->id_buku) }}"
                        method="POST"
                        class="{{ empty($book->kata_kunci) ? '' : 'hidden' }} mt-4 border-t border-line pt-3.5 space-y-2.5"
                    >
                        @csrf
                        <label for="input-kata-kunci" class="block text-[11px] font-semibold text-ink">
                            Ubah / Tambah Kata Kunci (Pisahkan dengan koma):
                        </label>
                        <div class="relative">
                            <input
                                type="text"
                                name="kata_kunci"
                                id="input-kata-kunci"
                                value="{{ old('kata_kunci', $book->kata_kunci) }}"
                                placeholder="Contoh: Coding, Pembelajaran, Python (atau Hiburan, Sastra)"
                                class="h-10 w-full rounded-xl border border-line bg-white pr-9 pl-3 text-xs text-ink placeholder:text-muted focus:border-primary focus:outline-none"
                            >
                            <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2.5 text-muted">
                                <i class="fa-solid fa-tags text-xs"></i>
                            </span>
                        </div>
                        <p class="text-[10px] text-muted">
                            Kata kunci ini akan dicocokkan saat pemustaka mencari di katalog OPAC dan tampil sebagai tag topik yang dapat diklik.
                        </p>
                        <div class="flex items-center justify-end gap-2 pt-1">
                            <button
                                type="button"
                                onclick="document.getElementById('form-edit-kata-kunci').classList.add('hidden')"
                                class="h-8 rounded-lg border border-line px-3 text-[11px] font-medium text-muted hover:bg-subtle hover:text-ink"
                            >
                                Batal
                            </button>
                            <button
                                type="submit"
                                class="inline-flex h-8 items-center gap-1.5 rounded-lg bg-primary px-3 text-[11px] font-semibold text-white hover:bg-primary-dark transition-all"
                            >
                                <i class="fa-solid fa-floppy-disk text-[10px]"></i>
                                <span>Simpan Kata Kunci</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Panel Kanan: Status Stok & Manajemen Inventaris Eksemplar --}}
            <div class="lg:col-span-8 space-y-6">
                {{-- 4 Kartu Status Stok Buku Ini --}}
                <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
                    <div class="rounded-2xl border border-line bg-white p-4 shadow-card">
                        <span class="text-[11px] font-semibold uppercase tracking-wider text-muted">Total Fisik</span>
                        <div class="mt-1.5 text-2xl font-bold text-ink">{{ $totalCopies }}</div>
                        <span class="text-[11px] text-muted">Eksemplar terdaftar</span>
                    </div>

                    <div class="rounded-2xl border border-emerald-200 bg-emerald-50/50 p-4 shadow-card">
                        <span class="text-[11px] font-semibold uppercase tracking-wider text-emerald-800">Tersedia di Rak</span>
                        <div class="mt-1.5 text-2xl font-bold text-emerald-900">{{ $availableCopies }}</div>
                        <span class="text-[11px] text-emerald-700">Siap dipinjam</span>
                    </div>

                    <div class="rounded-2xl border border-amber-200 bg-amber-50/50 p-4 shadow-card">
                        <span class="text-[11px] font-semibold uppercase tracking-wider text-amber-800">Sedang Dipinjam</span>
                        <div class="mt-1.5 text-2xl font-bold text-amber-900">{{ $borrowedCopies }}</div>
                        <span class="text-[11px] text-amber-700">Di tangan pemustaka</span>
                    </div>

                    <div class="rounded-2xl border border-rose-200 bg-rose-50/50 p-4 shadow-card">
                        <span class="text-[11px] font-semibold uppercase tracking-wider text-rose-800">Perbaikan / Lainnya</span>
                        <div class="mt-1.5 text-2xl font-bold text-rose-900">{{ $otherCopies }}</div>
                        <span class="text-[11px] text-rose-700">Kondisi nonaktif</span>
                    </div>
                </div>

                {{-- Form Tambah Eksemplar Baru (Collapsible Card) --}}
                <div class="rounded-2xl border border-line bg-white shadow-card overflow-hidden">
                    <div class="flex items-center justify-between border-b border-line bg-slate-50/80 px-5 py-4">
                        <div class="flex items-center gap-2">
                            <span class="flex size-7 items-center justify-center rounded-lg bg-primary/10 text-primary">
                                <i class="fa-solid fa-plus text-xs"></i>
                            </span>
                            <h2 class="text-sm font-bold text-ink">Registrasi Eksemplar Fisik Baru</h2>
                        </div>
                        <span class="text-xs text-muted">Tambah stok fisik ke rak</span>
                    </div>

                    <form action="{{ route('admin.books.eksemplar.store', $book->id_buku) }}" method="POST" class="p-5 sm:p-6 space-y-4">
                        @csrf
                        <div class="grid gap-4 sm:grid-cols-2 md:grid-cols-4">
                            {{-- Kode Inventaris --}}
                            <div>
                                <label for="kode_inventaris" class="block text-xs font-semibold text-ink">
                                    Kode Inventaris / Barcode
                                </label>
                                <input
                                    type="text"
                                    name="kode_inventaris"
                                    id="kode_inventaris"
                                    placeholder="Otomatis jika kosong"
                                    class="mt-1.5 h-10 w-full rounded-xl border border-line bg-white px-3 text-xs text-ink placeholder:text-muted focus:border-primary focus:outline-none"
                                >
                                <span class="text-[10px] text-muted">Contoh: INV-{{ $book->id_buku }}-01</span>
                            </div>

                            {{-- Nomor Panggil --}}
                            <div>
                                <label for="nomor_panggil" class="block text-xs font-semibold text-ink">
                                    Nomor Panggil (Call Number)
                                </label>
                                <input
                                    type="text"
                                    name="nomor_panggil"
                                    id="nomor_panggil"
                                    value="{{ $book->eksemplar->first()?->nomor_panggil ?? '' }}"
                                    placeholder="Contoh: 005.1 HID a c.1"
                                    class="mt-1.5 h-10 w-full rounded-xl border border-line bg-white px-3 text-xs text-ink placeholder:text-muted focus:border-primary focus:outline-none font-mono"
                                >
                            </div>

                            {{-- Lokasi Rak --}}
                            <div>
                                <label for="lokasi_rak" class="block text-xs font-semibold text-ink">
                                    Lokasi Rak Koleksi
                                </label>
                                <input
                                    type="text"
                                    name="lokasi_rak"
                                    id="lokasi_rak"
                                    value="{{ $book->eksemplar->first()?->lokasi_rak ?? 'Lantai 2, Rak Koleksi' }}"
                                    placeholder="Contoh: Lantai 2, Rak Komputer 03"
                                    class="mt-1.5 h-10 w-full rounded-xl border border-line bg-white px-3 text-xs text-ink placeholder:text-muted focus:border-primary focus:outline-none"
                                >
                            </div>

                            {{-- Status Awal --}}
                            <div>
                                <label for="status" class="block text-xs font-semibold text-ink">
                                    Status Ketersediaan <span class="text-red-500">*</span>
                                </label>
                                <select
                                    name="status"
                                    id="status"
                                    required
                                    class="mt-1.5 h-10 w-full rounded-xl border border-line bg-white px-3 text-xs font-medium text-ink focus:border-primary focus:outline-none"
                                >
                                    <option value="Tersedia" selected>Tersedia (Siap Pinjam)</option>
                                    <option value="Dipinjam">Dipinjam</option>
                                    <option value="Rusak">Rusak</option>
                                    <option value="Perbaikan">Perbaikan / Perawatan</option>
                                    <option value="Hilang">Hilang</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex items-center justify-end pt-2">
                            <button
                                type="submit"
                                class="inline-flex h-9 items-center gap-1.5 rounded-xl bg-primary px-5 text-xs font-semibold text-white shadow-xs hover:bg-primary-dark transition-all active:scale-[0.98]"
                            >
                                <i class="fa-solid fa-plus text-xs"></i>
                                <span>Simpan Eksemplar</span>
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Tabel Manajemen Seluruh Eksemplar Fisik --}}
                <div class="rounded-2xl border border-line bg-white shadow-card overflow-hidden">
                    <div class="flex items-center justify-between border-b border-line bg-[#F8FAFC] px-5 py-3.5">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-boxes-stacked text-primary text-sm"></i>
                            <h2 class="text-sm font-bold text-ink">Daftar Eksemplar Fisik ({{ $book->eksemplar->count() }})</h2>
                        </div>
                        <span class="text-xs text-muted">Kelola nomor panggil, rak, dan status</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs sm:text-sm">
                            <thead class="border-b border-line bg-slate-50 text-[11px] font-semibold uppercase tracking-wider text-muted">
                                <tr>
                                    <th class="px-4 py-3">No</th>
                                    <th class="px-4 py-3">Kode Inventaris</th>
                                    <th class="px-4 py-3">Nomor Panggil</th>
                                    <th class="px-4 py-3">Lokasi Rak</th>
                                    <th class="px-4 py-3 text-center">Status Eksemplar</th>
                                    <th class="px-4 py-3 text-center">Perbarui / Hapus</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-line text-ink">
                                @forelse ($book->eksemplar as $index => $ek)
                                    <tr class="hover:bg-slate-50/70 transition-colors">
                                        <td class="px-4 py-3 text-muted font-mono">{{ $index + 1 }}</td>
                                        <td class="px-4 py-3">
                                            <span class="font-mono font-bold text-ink">{{ $ek->kode_inventaris }}</span>
                                            <span class="block text-[10px] text-muted font-mono">{{ $ek->id_eksemplar }}</span>
                                        </td>
                                        <td class="px-4 py-3 font-mono text-primary font-medium">
                                            {{ $ek->nomor_panggil ?? '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-ink-secondary">
                                            <i class="fa-solid fa-location-dot text-[10px] text-primary/70 mr-1"></i>
                                            {{ $ek->lokasi_rak ?? '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-center whitespace-nowrap">
                                            {{-- Form Ganti Status Cepat --}}
                                            <form action="{{ route('admin.books.eksemplar.update', [$book->id_buku, $ek->id_eksemplar]) }}" method="POST" class="inline-flex flex-col items-center">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="kode_inventaris" value="{{ $ek->kode_inventaris }}">
                                                <input type="hidden" name="nomor_panggil" value="{{ $ek->nomor_panggil }}">
                                                <input type="hidden" name="lokasi_rak" value="{{ $ek->lokasi_rak }}">

                                                <select
                                                    name="status"
                                                    data-current-status="{{ $ek->status }}"
                                                    onchange="handleExemplarStatusSelect(this, {{ json_encode($ek) }})"
                                                    class="h-8 rounded-lg border text-xs font-semibold px-2 py-0.5 focus:outline-none transition-colors cursor-pointer {{ $ek->status === 'Tersedia' ? 'border-emerald-300 bg-emerald-50 text-emerald-800' : ($ek->status === 'Dipinjam' ? 'border-amber-300 bg-amber-50 text-amber-800' : 'border-rose-300 bg-rose-50 text-rose-800') }}"
                                                    title="Ubah status eksemplar ini langsung"
                                                >
                                                    <option value="Tersedia" @selected($ek->status === 'Tersedia')>Tersedia</option>
                                                    <option value="Dipinjam" @selected($ek->status === 'Dipinjam')>Dipinjam</option>
                                                    <option value="Rusak" @selected($ek->status === 'Rusak')>Rusak</option>
                                                    <option value="Perbaikan" @selected($ek->status === 'Perbaikan')>Perbaikan</option>
                                                    <option value="Hilang" @selected($ek->status === 'Hilang')>Hilang</option>
                                                </select>

                                                @if ($ek->status === 'Dipinjam' && $ek->peminjamanAktif)
                                                    <div class="mt-1 flex items-center justify-center gap-1 text-[10px] text-amber-900 bg-amber-50 rounded-md px-1.5 py-0.5 border border-amber-200" title="Dipinjam oleh {{ $ek->peminjamanAktif->pengguna?->nama_lengkap }} (Batas: {{ $ek->peminjamanAktif->tanggal_jatuh_tempo?->format('d/m/Y') }})">
                                                        <i class="fa-solid fa-user-clock text-amber-600 text-[9px]"></i>
                                                        <span class="font-medium truncate max-w-[110px]">{{ $ek->peminjamanAktif->pengguna?->nama_lengkap }}</span>
                                                    </div>
                                                @endif
                                            </form>
                                        </td>
                                        <td class="px-4 py-3 text-center whitespace-nowrap">
                                            <div class="inline-flex items-center gap-1.5">
                                                {{-- Tombol Modal Edit Eksemplar Lengkap --}}
                                                <button
                                                    type="button"
                                                    onclick="openEditEksemplarModal({{ json_encode($ek) }})"
                                                    class="inline-flex size-7 items-center justify-center rounded-lg border border-line text-muted hover:border-primary hover:text-primary transition-colors"
                                                    title="Edit Rincian Eksemplar"
                                                >
                                                    <i class="fa-regular fa-pen-to-square text-xs"></i>
                                                </button>

                                                {{-- Tombol Hapus Eksemplar --}}
                                                <form
                                                    action="{{ route('admin.books.eksemplar.destroy', [$book->id_buku, $ek->id_eksemplar]) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Hapus eksemplar fisik {{ $ek->kode_inventaris }} dari inventaris?')"
                                                    class="inline-block"
                                                >
                                                    @csrf
                                                    @method('DELETE')
                                                    <button
                                                        type="submit"
                                                        class="inline-flex size-7 items-center justify-center rounded-lg border border-red-200 text-red-600 hover:bg-red-50 transition-colors"
                                                        title="Hapus Eksemplar"
                                                    >
                                                        <i class="fa-regular fa-trash-can text-xs"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-5 py-10 text-center">
                                            <div class="flex flex-col items-center justify-center gap-1.5 text-muted">
                                                <i class="fa-solid fa-boxes-stacked text-2xl text-muted/40"></i>
                                                <p class="font-medium text-ink">Belum ada eksemplar fisik terdaftar.</p>
                                                <p class="text-xs text-muted">Gunakan form di atas untuk mendaftarkan salinan fisik pertama ke rak perpustakaan.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Edit Eksemplar --}}
    <div
        id="modal-edit-eksemplar"
        class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-xs"
        role="dialog"
        aria-modal="true"
        aria-labelledby="modal-edit-title"
    >
        <div class="flex min-h-screen items-center justify-center p-4">
            <div class="relative w-full max-w-md overflow-hidden rounded-2xl border border-line bg-white shadow-2xl">
                <div class="flex items-center justify-between border-b border-line bg-slate-50 px-5 py-3.5">
                    <h3 id="modal-edit-title" class="text-sm font-bold text-ink">Ubah Rincian Eksemplar Fisik</h3>
                    <button type="button" onclick="closeEditEksemplarModal()" class="text-muted hover:text-ink">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <form id="form-edit-eksemplar" method="POST" action="" class="p-5 space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="edit_kode_inventaris" class="block text-xs font-semibold text-ink">
                            Kode Inventaris / Barcode
                        </label>
                        <input
                            type="text"
                            name="kode_inventaris"
                            id="edit_kode_inventaris"
                            required
                            class="mt-1 h-10 w-full rounded-xl border border-line bg-white px-3 text-xs text-ink focus:border-primary focus:outline-none font-mono"
                        >
                    </div>

                    <div>
                        <label for="edit_nomor_panggil" class="block text-xs font-semibold text-ink">
                            Nomor Panggil
                        </label>
                        <input
                            type="text"
                            name="nomor_panggil"
                            id="edit_nomor_panggil"
                            class="mt-1 h-10 w-full rounded-xl border border-line bg-white px-3 text-xs text-ink focus:border-primary focus:outline-none font-mono"
                        >
                    </div>

                    <div>
                        <label for="edit_lokasi_rak" class="block text-xs font-semibold text-ink">
                            Lokasi Rak
                        </label>
                        <input
                            type="text"
                            name="lokasi_rak"
                            id="edit_lokasi_rak"
                            class="mt-1 h-10 w-full rounded-xl border border-line bg-white px-3 text-xs text-ink focus:border-primary focus:outline-none"
                        >
                    </div>

                    <div>
                        <label for="edit_status" class="block text-xs font-semibold text-ink">
                            Status Ketersediaan
                        </label>
                        <select
                            name="status"
                            id="edit_status"
                            required
                            class="mt-1 h-10 w-full rounded-xl border border-line bg-white px-3 text-xs text-ink focus:border-primary focus:outline-none font-medium"
                        >
                            <option value="Tersedia">Tersedia (Siap Pinjam)</option>
                            <option value="Dipinjam">Dipinjam</option>
                            <option value="Rusak">Rusak</option>
                            <option value="Perbaikan">Perbaikan</option>
                            <option value="Hilang">Hilang</option>
                        </select>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-line">
                        <button
                            type="button"
                            onclick="closeEditEksemplarModal()"
                            class="inline-flex h-9 items-center justify-center rounded-xl border border-line px-4 text-xs font-medium text-muted hover:bg-subtle"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            class="inline-flex h-9 items-center justify-center rounded-xl bg-primary px-5 text-xs font-semibold text-white shadow-xs hover:bg-primary-dark"
                        >
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Peminjaman Eksemplar Fisik oleh Admin (Alur Flowchart Sirkulasi & Validasi Eligibilitas) --}}
    <div
        id="modal-pinjam-eksemplar"
        class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4"
        role="dialog"
        aria-modal="true"
        aria-labelledby="modal-pinjam-title"
    >
        <div class="relative w-full max-w-lg overflow-hidden rounded-2xl border border-line bg-white shadow-2xl transition-all my-auto animate-in fade-in zoom-in-95 duration-200">
            {{-- Modal Header --}}
            <div class="flex items-center justify-between border-b border-line bg-slate-50 px-5 py-3.5">
                <div class="flex items-center gap-2.5">
                    <div class="flex size-9 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">
                        <i class="fa-solid fa-hand-holding-hand text-sm"></i>
                    </div>
                    <div>
                        <h3 id="modal-pinjam-title" class="text-sm font-bold text-ink">
                            Peminjaman Eksemplar Fisik
                        </h3>
                        <p id="modal-pinjam-subtitle" class="text-[11px] text-muted">
                            Pilih data mahasiswa peminjam sesuai alur sirkulasi perpustakaan
                        </p>
                    </div>
                </div>
                <button
                    type="button"
                    onclick="closeLoanModal()"
                    class="rounded-lg p-1 text-muted hover:bg-slate-200 hover:text-ink transition-colors"
                >
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            {{-- Modal Form --}}
            <form id="form-pinjam-eksemplar" method="POST" action="" class="p-5 space-y-4">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="Dipinjam">
                <input type="hidden" name="kode_inventaris" id="modal_pinjam_kode_inventaris" value="">
                <input type="hidden" name="nomor_panggil" id="modal_pinjam_nomor_panggil" value="">
                <input type="hidden" name="lokasi_rak" id="modal_pinjam_lokasi_rak" value="">

                {{-- Detail Eksemplar Terpilih --}}
                <div class="rounded-xl border border-line bg-slate-50 p-3 text-xs flex items-center justify-between">
                    <div>
                        <span class="text-muted block text-[10px]">Eksemplar Fisik:</span>
                        <strong id="display_kode_inventaris" class="font-mono text-ink text-xs font-bold">-</strong>
                    </div>
                    <div class="text-right">
                        <span class="text-muted block text-[10px]">Lokasi Rak:</span>
                        <span id="display_lokasi_rak" class="text-primary font-medium">-</span>
                    </div>
                </div>

                {{-- Input Mahasiswa / Pemustaka --}}
                <div>
                    <label for="modal_pinjam_id_pengguna" class="block text-xs font-semibold text-ink mb-1">
                        Pilih Mahasiswa / User Peminjam <span class="text-rose-500">*</span>
                    </label>
                    <select
                        name="id_pengguna"
                        id="modal_pinjam_id_pengguna"
                        required
                        onchange="handleLoanUserSelection(this.value)"
                        class="w-full rounded-xl border border-line bg-white px-3 py-2 text-xs text-ink focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-500/20"
                    >
                        <option value="">-- Cari / Pilih Mahasiswa atau User --</option>
                        @foreach ($users as $u)
                            <option
                                value="{{ $u->id_pengguna }}"
                                data-name="{{ $u->nama_lengkap }}"
                                data-role="{{ $u->peran?->nama_peran ?: 'Pemustaka' }}"
                                data-nim="{{ $u->nim ?: ($u->nidn ?: ($u->nomor_identitas ?: '-')) }}"
                                data-member="{{ $u->isMember() ? '1' : '0' }}"
                            >
                                {{ $u->nama_lengkap }} ({{ $u->nim ?: ($u->nidn ?: ($u->nomor_identitas ?: '-')) }} &bull; {{ $u->peran?->nama_peran ?: 'Pemustaka' }}){{ $u->isMember() ? ' [Member]' : ' [Bukan Member]' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Box Validasi Eligibilitas Mahasiswa --}}
                <div id="loan-eligibility-box" class="space-y-2">
                    {{-- State Loading --}}
                    <div id="loan-eligibility-loading" class="hidden rounded-xl border border-slate-200 bg-slate-50 p-3 text-xs text-muted flex items-center gap-2">
                        <i class="fa-solid fa-circle-notch fa-spin text-emerald-600"></i>
                        <span>Memeriksa eligibilitas dan riwayat pinjaman aktif mahasiswa...</span>
                    </div>

                    {{-- State Blocked: User Tidak Bisa Meminjam Buku --}}
                    <div id="loan-eligibility-blocked" class="hidden rounded-xl border border-rose-200 bg-rose-50 p-3 text-xs text-rose-950 space-y-2">
                        <div class="flex items-start gap-2">
                            <i class="fa-solid fa-circle-xmark text-rose-600 text-sm mt-0.5 shrink-0"></i>
                            <div>
                                <h4 class="font-bold text-rose-900 text-xs">User Tidak Bisa Meminjam Buku!</h4>
                                <p id="loan-eligibility-blocked-msg" class="text-[11px] text-rose-800 leading-relaxed font-medium mt-0.5"></p>
                            </div>
                        </div>

                        {{-- Daftar Buku Belum Dikembalikan --}}
                        <div id="loan-unreturned-container" class="rounded-lg border border-amber-200 bg-white p-2.5 text-[11px] space-y-1.5 hidden">
                            <span class="font-bold text-amber-950 block">Buku Pinjaman yang Belum Dikembalikan:</span>
                            <div id="loan-unreturned-list" class="space-y-1 text-muted"></div>
                        </div>
                    </div>

                    {{-- State Eligible --}}
                    <div id="loan-eligibility-eligible" class="hidden rounded-xl border border-emerald-200 bg-emerald-50 p-3 text-xs text-emerald-950 flex items-start gap-2">
                        <i class="fa-solid fa-circle-check text-emerald-600 text-sm mt-0.5 shrink-0"></i>
                        <div>
                            <span class="font-bold text-emerald-900 block">Mahasiswa Memenuhi Syarat (Eligible)</span>
                            <span class="text-[11px] text-emerald-800">Tidak ada tanggungan buku pinjaman aktif. Peminjaman dapat dicatat ke sistem.</span>
                        </div>
                    </div>
                </div>

                {{-- Tanggal Pengambilan & Pengembalian --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-semibold text-ink mb-1">
                            Tanggal Pengambilan
                        </label>
                        <div class="flex items-center justify-between rounded-xl border border-line bg-slate-50 px-3 py-2 text-xs">
                            <span class="font-medium text-ink">{{ now()->translatedFormat('d M Y') }}</span>
                            <span class="rounded bg-emerald-100 px-1.5 py-0.5 text-[10px] font-bold text-emerald-800">Hari Ini</span>
                        </div>
                        <input type="hidden" name="tanggal_pinjam" value="{{ now()->toDateString() }}">
                    </div>

                    <div>
                        <label for="modal_pinjam_tanggal_jatuh_tempo" class="block text-[11px] font-semibold text-ink mb-1">
                            Tanggal Pengembalian <span class="text-rose-500">*</span>
                        </label>
                        <input
                            type="date"
                            name="tanggal_jatuh_tempo"
                            id="modal_pinjam_tanggal_jatuh_tempo"
                            required
                            min="{{ now()->toDateString() }}"
                            value="{{ now()->addDays(14)->toDateString() }}"
                            class="w-full rounded-xl border border-line bg-white px-3 py-1.5 text-xs text-ink focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-500/20"
                        >
                        <span class="text-[10px] text-muted">Default 14 hari kerja.</span>
                    </div>
                </div>

                {{-- Catatan Peminjaman --}}
                <div>
                    <label for="modal_pinjam_catatan" class="block text-[11px] font-semibold text-ink mb-1">
                        Catatan Peminjaman (Opsional)
                    </label>
                    <textarea
                        name="catatan"
                        id="modal_pinjam_catatan"
                        rows="2"
                        placeholder="Catatan keperluan peminjaman..."
                        class="w-full rounded-xl border border-line bg-white px-3 py-2 text-xs text-ink focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-500/20"
                    ></textarea>
                </div>

                {{-- Tombol Aksi Modal --}}
                <div class="flex items-center justify-end gap-2 pt-3 border-t border-line">
                    <button
                        type="button"
                        onclick="closeLoanModal()"
                        class="inline-flex h-9 items-center justify-center rounded-xl border border-line px-4 text-xs font-medium text-muted hover:bg-subtle transition-colors"
                    >
                        Batal
                    </button>
                    <button
                        type="submit"
                        id="modal-pinjam-submit-btn"
                        disabled
                        class="inline-flex h-9 items-center justify-center gap-1.5 rounded-xl bg-emerald-600 px-5 text-xs font-semibold text-white shadow-xs hover:bg-emerald-700 transition-all focus:outline-none focus:ring-2 focus:ring-emerald-500 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <i class="fa-solid fa-check text-xs"></i>
                        <span>Simpan & Ubah Status Dipinjam</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let currentEditingEksemplar = null;

        function openEditEksemplarModal(ek) {
            const modal = document.getElementById('modal-edit-eksemplar');
            const form = document.getElementById('form-edit-eksemplar');
            if (!modal || !form || !ek) return;

            currentEditingEksemplar = ek;
            form.action = "{{ route('admin.books.eksemplar.update', [$book->id_buku, ':id']) }}".replace(':id', ek.id_eksemplar);
            document.getElementById('edit_kode_inventaris').value = ek.kode_inventaris || '';
            document.getElementById('edit_nomor_panggil').value = ek.nomor_panggil || '';
            document.getElementById('edit_lokasi_rak').value = ek.lokasi_rak || '';
            document.getElementById('edit_status').value = ek.status || 'Tersedia';

            modal.classList.remove('hidden');
        }

        function closeEditEksemplarModal() {
            const modal = document.getElementById('modal-edit-eksemplar');
            if (modal) modal.classList.add('hidden');
            currentEditingEksemplar = null;
        }

        // Jika admin memilih 'Dipinjam' di modal edit eksemplar, arahkan ke alur peminjaman
        document.getElementById('edit_status')?.addEventListener('change', function () {
            if (this.value === 'Dipinjam' && currentEditingEksemplar && currentEditingEksemplar.status !== 'Dipinjam') {
                const targetEk = { ...currentEditingEksemplar };
                targetEk.kode_inventaris = document.getElementById('edit_kode_inventaris').value || targetEk.kode_inventaris;
                targetEk.nomor_panggil = document.getElementById('edit_nomor_panggil').value || targetEk.nomor_panggil;
                targetEk.lokasi_rak = document.getElementById('edit_lokasi_rak').value || targetEk.lokasi_rak;

                closeEditEksemplarModal();
                openLoanModalForExemplar(targetEk);
            }
        });

        // Intercept dropdown status cepat di tabel daftar eksemplar
        function handleExemplarStatusSelect(selectEl, ek) {
            const newStatus = selectEl.value;
            const currentStatus = selectEl.getAttribute('data-current-status') || ek.status;

            if (newStatus === 'Dipinjam' && currentStatus !== 'Dipinjam') {
                // Revert status pada dropdown sementara waktu sebelum form loan disubmit
                selectEl.value = currentStatus;
                openLoanModalForExemplar(ek);
                return;
            }

            if (newStatus === 'Tersedia' && currentStatus === 'Dipinjam') {
                const confirmed = confirm('Eksemplar ini sedang berstatus Dipinjam. Mengubah status menjadi Tersedia akan mencatat bahwa buku telah dikembalikan oleh peminjam. Lanjutkan pengembalian buku?');
                if (confirmed) {
                    selectEl.form.submit();
                } else {
                    selectEl.value = 'Dipinjam';
                }
                return;
            }

            // Status lainnya langsung disubmit
            selectEl.form.submit();
        }

        function openLoanModalForExemplar(ek) {
            const modal = document.getElementById('modal-pinjam-eksemplar');
            const form = document.getElementById('form-pinjam-eksemplar');
            if (!modal || !form || !ek) return;

            form.action = "{{ route('admin.books.eksemplar.update', [$book->id_buku, ':id']) }}".replace(':id', ek.id_eksemplar);
            document.getElementById('modal_pinjam_kode_inventaris').value = ek.kode_inventaris || '';
            document.getElementById('modal_pinjam_nomor_panggil').value = ek.nomor_panggil || '';
            document.getElementById('modal_pinjam_lokasi_rak').value = ek.lokasi_rak || '';
            document.getElementById('display_kode_inventaris').textContent = ek.kode_inventaris || '-';
            document.getElementById('display_lokasi_rak').textContent = ek.lokasi_rak || 'Lantai 2, Rak Koleksi';

            // Reset pilihan user & kotak eligibilitas
            const userSelect = document.getElementById('modal_pinjam_id_pengguna');
            userSelect.value = '';

            document.getElementById('loan-eligibility-loading').classList.add('hidden');
            document.getElementById('loan-eligibility-blocked').classList.add('hidden');
            document.getElementById('loan-eligibility-eligible').classList.add('hidden');
            document.getElementById('modal-pinjam-submit-btn').disabled = true;

            modal.classList.remove('hidden');
        }

        function closeLoanModal() {
            const modal = document.getElementById('modal-pinjam-eksemplar');
            if (modal) modal.classList.add('hidden');
        }

        async function handleLoanUserSelection(userId) {
            const loadingBox = document.getElementById('loan-eligibility-loading');
            const blockedBox = document.getElementById('loan-eligibility-blocked');
            const eligibleBox = document.getElementById('loan-eligibility-eligible');
            const submitBtn = document.getElementById('modal-pinjam-submit-btn');

            if (!userId) {
                loadingBox.classList.add('hidden');
                blockedBox.classList.add('hidden');
                eligibleBox.classList.add('hidden');
                submitBtn.disabled = true;
                return;
            }

            loadingBox.classList.remove('hidden');
            blockedBox.classList.add('hidden');
            eligibleBox.classList.add('hidden');
            submitBtn.disabled = true;

            try {
                const response = await fetch("{{ url('admin/peminjaman/cek-eligibilitas') }}/" + encodeURIComponent(userId), {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const json = await response.json();
                loadingBox.classList.add('hidden');

                const data = json.data || {};
                if (data.eligible) {
                    eligibleBox.classList.remove('hidden');
                    blockedBox.classList.add('hidden');
                    submitBtn.disabled = false;
                } else {
                    blockedBox.classList.remove('hidden');
                    eligibleBox.classList.add('hidden');
                    submitBtn.disabled = true;

                    const msgEl = document.getElementById('loan-eligibility-blocked-msg');
                    if (msgEl) {
                        msgEl.textContent = data.message || 'User tidak bisa meminjam buku karena masih memiliki tanggungan peminjaman aktif.';
                    }

                    const unreturnedContainer = document.getElementById('loan-unreturned-container');
                    const unreturnedList = document.getElementById('loan-unreturned-list');
                    const loans = data.active_loans || [];

                    if (unreturnedContainer && unreturnedList) {
                        if (loans.length > 0) {
                            unreturnedContainer.classList.remove('hidden');
                            unreturnedList.innerHTML = loans.map(l => `
                                <div class="rounded border border-line bg-slate-50 p-1.5 flex items-center justify-between text-[10px]">
                                    <span class="font-bold text-ink">${l.buku ? l.buku.judul_buku : 'Buku'}</span>
                                    <span class="text-rose-700 font-medium">Batas: ${l.tanggal_jatuh_tempo || '-'}</span>
                                </div>
                            `).join('');
                        } else {
                            unreturnedContainer.classList.add('hidden');
                        }
                    }
                }
            } catch (err) {
                loadingBox.classList.add('hidden');
                blockedBox.classList.remove('hidden');
                document.getElementById('loan-eligibility-blocked-msg').textContent = 'Terjadi kesalahan jaringan saat memvalidasi eligibilitas user.';
                submitBtn.disabled = true;
            }
        }

        document.getElementById('modal-edit-eksemplar')?.addEventListener('click', function (e) {
            if (e.target === this) closeEditEksemplarModal();
        });

        document.getElementById('modal-pinjam-eksemplar')?.addEventListener('click', function (e) {
            if (e.target === this) closeLoanModal();
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeEditEksemplarModal();
                closeLoanModal();
            }
        });
    </script>
@endsection
