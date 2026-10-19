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

        {{-- Flash Notification --}}
        @if (session('success'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-xs font-semibold text-emerald-800 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-sm"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-950">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        {{-- Grid 2 Kolom: Kiri Profil Bibliografi, Kanan Metrik Stok & Manajemen Eksemplar --}}
        <div class="grid gap-6 lg:grid-cols-12">
            {{-- Panel Kiri: Sampul & Rincian Koleksi --}}
            <div class="lg:col-span-4 space-y-5">
                <div class="rounded-2xl border border-line bg-white p-5 shadow-card">
                    {{-- Cover --}}
                    <div class="relative aspect-[3/4] w-full overflow-hidden rounded-xl border border-line bg-subtle shadow-xs">
                        @if ($book->sampul_buku)
                            <img src="{{ $book->sampul_buku }}" alt="{{ $book->judul_buku }}" class="h-full w-full object-cover">
                        @else
                            <div class="flex h-full w-full flex-col items-center justify-center p-4 text-center text-muted">
                                <div class="flex size-14 items-center justify-center rounded-2xl bg-primary/10 text-primary">
                                    <i class="fa-solid fa-book text-2xl"></i>
                                </div>
                                <span class="mt-2 text-xs font-medium text-ink/70">Tanpa Sampul</span>
                            </div>
                        @endif
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
                                            <form action="{{ route('admin.books.eksemplar.update', [$book->id_buku, $ek->id_eksemplar]) }}" method="POST" class="inline-flex items-center">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="kode_inventaris" value="{{ $ek->kode_inventaris }}">
                                                <input type="hidden" name="nomor_panggil" value="{{ $ek->nomor_panggil }}">
                                                <input type="hidden" name="lokasi_rak" value="{{ $ek->lokasi_rak }}">

                                                <select
                                                    name="status"
                                                    onchange="this.form.submit()"
                                                    class="h-8 rounded-lg border text-xs font-semibold px-2 py-0.5 focus:outline-none transition-colors cursor-pointer {{ $ek->status === 'Tersedia' ? 'border-emerald-300 bg-emerald-50 text-emerald-800' : ($ek->status === 'Dipinjam' ? 'border-amber-300 bg-amber-50 text-amber-800' : 'border-rose-300 bg-rose-50 text-rose-800') }}"
                                                    title="Ubah status eksemplar ini langsung"
                                                >
                                                    <option value="Tersedia" @selected($ek->status === 'Tersedia')>Tersedia</option>
                                                    <option value="Dipinjam" @selected($ek->status === 'Dipinjam')>Dipinjam</option>
                                                    <option value="Rusak" @selected($ek->status === 'Rusak')>Rusak</option>
                                                    <option value="Perbaikan" @selected($ek->status === 'Perbaikan')>Perbaikan</option>
                                                    <option value="Hilang" @selected($ek->status === 'Hilang')>Hilang</option>
                                                </select>
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

    <script>
        function openEditEksemplarModal(ek) {
            const modal = document.getElementById('modal-edit-eksemplar');
            const form = document.getElementById('form-edit-eksemplar');
            if (!modal || !form || !ek) return;

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
        }

        document.getElementById('modal-edit-eksemplar')?.addEventListener('click', function (e) {
            if (e.target === this) closeEditEksemplarModal();
        });
    </script>
@endsection
