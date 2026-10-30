@extends('layouts.app')

@section('title', 'Catat Peminjaman Buku Baru')
@section('description', 'Entri data peminjaman buku perpustakaan MNC University dengan validasi eligibilitas mahasiswa dan otomatisasi status stok eksemplar.')

@section('content')
    <div class="mx-auto flex w-full max-w-[1100px] flex-1 flex-col gap-6 px-3 py-6 lg:px-6 lg:py-8">
        {{-- Breadcrumb & Header --}}
        <div>
            <nav aria-label="Breadcrumb" class="flex items-center gap-2 text-xs text-muted sm:text-sm">
                <a href="{{ route('beranda') }}" class="hover:text-primary">Beranda</a>
                <svg class="size-3.5 text-muted/60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
                <a href="{{ route('admin.peminjaman.index') }}" class="text-ink-secondary hover:text-primary">Peminjaman &amp; Sirkulasi</a>
                <svg class="size-3.5 text-muted/60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
                <span class="font-medium text-ink">Catat Peminjaman</span>
            </nav>
            <h1 class="mt-1 text-2xl font-bold tracking-tight text-primary sm:text-3xl">
                Catat Transaksi Peminjaman Buku
            </h1>
            <p class="mt-0.5 text-xs text-ink-secondary sm:text-sm">
                Ikuti alur sirkulasi: validasi eligibilitas mahasiswa, pilih eksemplar buku tersedia, dan tentukan tenggat waktu pengembalian.
            </p>
        </div>

        {{-- Form Utama Peminjaman --}}
        <form action="{{ route('admin.peminjaman.store') }}" method="POST" id="form-peminjaman" class="space-y-6">
            @csrf

            {{-- 1. Blok Validasi Eligibilitas Mahasiswa --}}
            <div class="rounded-2xl border border-line bg-white p-5 shadow-xs sm:p-6">
                <div class="flex items-center gap-2.5 border-b border-line pb-4">
                    <span class="flex size-7 items-center justify-center rounded-lg bg-primary/10 text-xs font-bold text-primary">
                        1
                    </span>
                    <div>
                        <h2 class="text-sm font-bold text-ink sm:text-base">Pilih Mahasiswa &amp; Validasi Eligibilitas</h2>
                        <p class="text-[11px] text-muted">Mahasiswa yang masih memiliki pinjaman buku belum dikembalikan tidak dapat meminjam buku baru.</p>
                    </div>
                </div>

                <div class="mt-5 space-y-4">
                    <div>
                        <label for="select-user" class="block text-xs font-bold text-ink">
                            Pilih Mahasiswa / Pemustaka <span class="text-rose-500">*</span>
                        </label>
                        <select
                            id="select-user"
                            name="id_pengguna"
                            required
                            class="mt-1.5 h-11 w-full rounded-xl border border-line bg-white px-3.5 text-xs text-ink focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 sm:text-sm"
                            onchange="checkUserEligibility(this.value)"
                        >
                            <option value="">-- Cari atau Pilih Mahasiswa / Pemustaka --</option>
                            @foreach ($users as $user)
                                @php
                                    $hasUnreturned = $user->memilikiTanggunganPinjaman();
                                    $isMember = $user->isMember();
                                @endphp
                                <option
                                    value="{{ $user->id_pengguna }}"
                                    data-role="{{ $user->peran?->nama_peran ?? $user->role }}"
                                    data-nim="{{ $user->nim ?? $user->nomor_identitas ?? '-' }}"
                                    data-email="{{ $user->email }}"
                                    data-is-member="{{ $isMember ? '1' : '0' }}"
                                    data-unreturned="{{ $hasUnreturned ? '1' : '0' }}"
                                    @selected(old('id_pengguna', $selectedUserId) == $user->id_pengguna)
                                >
                                    {{ $user->nama_lengkap }} ({{ $user->nim ? 'NIM: '.$user->nim : $user->email }}) [{{ $user->peran?->nama_peran ?? 'Pemustaka' }}] {{ ! $isMember ? '⛔ Bukan Member' : ($hasUnreturned ? '⚠️ Ada Tanggungan Pinjaman' : '✓ Member Aktif - Siap Pinjam') }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_pengguna')
                            <p class="mt-1.5 text-xs font-semibold text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Kotak Status Eligibilitas (Dinamis Berdasarkan Pilihan Mahasiswa) --}}
                    <div id="eligibility-box" class="hidden rounded-xl p-4 text-xs transition-all">
                        <div id="eligibility-blocked" class="hidden space-y-2">
                            <div class="flex items-center gap-2 font-bold text-rose-800 sm:text-sm">
                                <i class="fa-solid fa-circle-xmark text-base text-rose-600" aria-hidden="true"></i>
                                <span id="eligibility-error-title">User Tidak Bisa Meminjam Buku!</span>
                            </div>
                            <p id="eligibility-error-desc" class="text-rose-700">
                                Mahasiswa ini belum memenuhi syarat untuk meminjam buku perpustakaan.
                            </p>
                            <div id="blocked-loans-list" class="mt-2 rounded-lg bg-white/80 p-2.5 text-[11px] text-rose-900 border border-rose-200">
                                Sedang memuat riwayat tanggungan buku...
                            </div>
                        </div>

                        <div id="eligibility-passed" class="hidden space-y-1">
                            <div class="flex items-center gap-2 font-bold text-emerald-800 sm:text-sm">
                                <i class="fa-solid fa-circle-check text-base text-emerald-600" aria-hidden="true"></i>
                                <span>Member Memenuhi Syarat (Eligible)</span>
                            </div>
                            <p class="text-emerald-700">
                                Pengguna terdaftar sebagai member aktif dan tidak ada tanggungan buku yang belum dikembalikan. Berhak meminjam koleksi buku perpustakaan.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. Blok Input Data Pinjam Buku (Pilih Buku & Eksemplar) --}}
            <div class="rounded-2xl border border-line bg-white p-5 shadow-xs sm:p-6">
                <div class="flex items-center gap-2.5 border-b border-line pb-4">
                    <span class="flex size-7 items-center justify-center rounded-lg bg-primary/10 text-xs font-bold text-primary">
                        2
                    </span>
                    <div>
                        <h2 class="text-sm font-bold text-ink sm:text-base">Pilih Buku &amp; Eksemplar Fisik</h2>
                        <p class="text-[11px] text-muted">Pilih judul buku katalog dan kode inventaris eksemplar fisik yang berstatus Tersedia.</p>
                    </div>
                </div>

                <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2">
                    {{-- Pilih Buku --}}
                    <div>
                        <label for="select-book" class="block text-xs font-bold text-ink">
                            Judul Koleksi Buku <span class="text-rose-500">*</span>
                        </label>
                        <select
                            id="select-book"
                            name="id_buku"
                            class="mt-1.5 h-11 w-full rounded-xl border border-line bg-white px-3.5 text-xs text-ink focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 sm:text-sm"
                            onchange="fetchAvailableCopies(this.value)"
                        >
                            <option value="">-- Pilih Judul Buku dari Katalog --</option>
                            @foreach ($books as $book)
                                <option
                                    value="{{ $book->id_buku }}"
                                    data-available="{{ $book->eksemplar_tersedia_count }}"
                                    @selected(old('id_buku', $selectedBookId) == $book->id_buku)
                                >
                                    {{ $book->judul_buku }} ({{ $book->eksemplar_tersedia_count }} eksemplar tersedia)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Pilih Eksemplar Fisik --}}
                    <div>
                        <label for="select-copy" class="block text-xs font-bold text-ink">
                            Eksemplar Fisik (Kode Inventaris / Rak) <span class="text-rose-500">*</span>
                        </label>
                        <select
                            id="select-copy"
                            name="id_eksemplar"
                            required
                            class="mt-1.5 h-11 w-full rounded-xl border border-line bg-white px-3.5 text-xs text-ink focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 sm:text-sm"
                        >
                            <option value="">-- Pilih Eksemplar Fisik Tersedia --</option>
                            @foreach ($availableCopies as $copy)
                                <option value="{{ $copy->id_eksemplar }}" @selected(old('id_eksemplar') == $copy->id_eksemplar)>
                                    {{ $copy->kode_inventaris }} &bull; {{ $copy->nomor_panggil ?? 'Tanpa Call No' }} ({{ $copy->lokasi_rak ?? 'Lantai 1' }})
                                </option>
                            @endforeach
                        </select>
                        @error('id_eksemplar')
                            <p class="mt-1.5 text-xs font-semibold text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- 3. Blok Jadwal Peminjaman & Pengembalian --}}
            <div class="rounded-2xl border border-line bg-white p-5 shadow-xs sm:p-6">
                <div class="flex items-center gap-2.5 border-b border-line pb-4">
                    <span class="flex size-7 items-center justify-center rounded-lg bg-primary/10 text-xs font-bold text-primary">
                        3
                    </span>
                    <div>
                        <h2 class="text-sm font-bold text-ink sm:text-base">Input Tanggal Peminjaman &amp; Pengembalian</h2>
                        <p class="text-[11px] text-muted">Jangka waktu standar peminjaman koleksi MNC University adalah 14 hari kalender.</p>
                    </div>
                </div>

                <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2">
                    {{-- Tanggal Pinjam --}}
                    <div>
                        <label for="tanggal_pinjam" class="block text-xs font-bold text-ink">
                            Tanggal Peminjaman <span class="text-rose-500">*</span>
                        </label>
                        <input
                            type="date"
                            id="tanggal_pinjam"
                            name="tanggal_pinjam"
                            value="{{ old('tanggal_pinjam', now()->toDateString()) }}"
                            required
                            class="mt-1.5 h-11 w-full rounded-xl border border-line bg-white px-3.5 text-xs text-ink focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 sm:text-sm"
                            onchange="autoAdjustDueDate(this.value)"
                        >
                        @error('tanggal_pinjam')
                            <p class="mt-1.5 text-xs font-semibold text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tanggal Jatuh Tempo (Pengembalian) --}}
                    <div>
                        <label for="tanggal_jatuh_tempo" class="block text-xs font-bold text-ink">
                            Batas Waktu Pengembalian (Jatuh Tempo) <span class="text-rose-500">*</span>
                        </label>
                        <input
                            type="date"
                            id="tanggal_jatuh_tempo"
                            name="tanggal_jatuh_tempo"
                            value="{{ old('tanggal_jatuh_tempo', now()->addDays(14)->toDateString()) }}"
                            required
                            class="mt-1.5 h-11 w-full rounded-xl border border-line bg-white px-3.5 text-xs text-ink focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 sm:text-sm"
                        >
                        @error('tanggal_jatuh_tempo')
                            <p class="mt-1.5 text-xs font-semibold text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Catatan Sirkulasi --}}
                <div class="mt-4">
                    <label for="catatan" class="block text-xs font-bold text-ink">
                        Catatan Sirkulasi (Opsional)
                    </label>
                    <textarea
                        id="catatan"
                        name="catatan"
                        rows="2"
                        placeholder="Contoh: Keperluan penelitian skripsi semester akhir..."
                        class="mt-1.5 w-full rounded-xl border border-line bg-white p-3 text-xs text-ink focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 sm:text-sm"
                    >{{ old('catatan') }}</textarea>
                </div>
            </div>

            {{-- Tombol Aksi Simpan --}}
            <div class="flex items-center justify-end gap-3 pt-2">
                <a
                    href="{{ route('admin.peminjaman.index') }}"
                    class="h-11 rounded-xl border border-line bg-white px-5 text-xs font-semibold text-ink-secondary hover:bg-slate-50 transition-colors sm:text-sm flex items-center"
                >
                    Batal
                </a>
                <button
                    type="submit"
                    id="btn-submit-peminjaman"
                    class="h-11 inline-flex items-center gap-2 rounded-xl bg-primary px-6 text-xs font-semibold text-white shadow-sm hover:bg-primary-dark transition-all disabled:opacity-50 disabled:cursor-not-allowed sm:text-sm"
                >
                    <i class="fa-solid fa-check text-xs"></i>
                    <span>Proses Peminjaman Buku</span>
                </button>
            </div>
        </form>
    </div>

    <script>
        // Otomatis atur tanggal jatuh tempo +14 hari saat tanggal pinjam berubah
        function autoAdjustDueDate(pinjamDateStr) {
            if (!pinjamDateStr) return;
            const d = new Date(pinjamDateStr);
            d.setDate(d.getDate() + 14);
            const y = d.getFullYear();
            const m = String(d.getMonth() + 1).padStart(2, '0');
            const day = String(d.getDate()).padStart(2, '0');
            document.getElementById('tanggal_jatuh_tempo').value = `${y}-${m}-${day}`;
        }

        // Cek eligibilitas mahasiswa saat dropdown berubah
        async function checkUserEligibility(userId) {
            const box = document.getElementById('eligibility-box');
            const blocked = document.getElementById('eligibility-blocked');
            const passed = document.getElementById('eligibility-passed');
            const btnSubmit = document.getElementById('btn-submit-peminjaman');
            const loansList = document.getElementById('blocked-loans-list');

            if (!userId) {
                box.classList.add('hidden');
                btnSubmit.disabled = false;
                return;
            }

            box.classList.remove('hidden');

            try {
                // Request ke API cek eligibilitas
                const res = await fetch(`/api/peminjaman/cek-eligibilitas/${userId}`);
                const data = await res.json();

                if (res.status === 200 && data.data.eligible) {
                    // Eligible
                    box.className = 'rounded-xl border border-emerald-200 bg-emerald-50/70 p-4 text-xs transition-all';
                    blocked.classList.add('hidden');
                    passed.classList.remove('hidden');
                    btnSubmit.disabled = false;
                    btnSubmit.title = '';
                } else {
                    // Blocked (Bukan member ATAU Ada buku belum dikembalikan)
                    box.className = 'rounded-xl border border-rose-300 bg-rose-50/90 p-4 text-xs transition-all';
                    passed.classList.add('hidden');
                    blocked.classList.remove('hidden');
                    btnSubmit.disabled = true;
                    btnSubmit.title = data.message || 'User tidak memenuhi syarat untuk meminjam buku';

                    const errDesc = document.getElementById('eligibility-error-desc');
                    errDesc.textContent = data.message || 'Pengguna belum memenuhi syarat untuk meminjam buku.';

                    if (data.data && Array.isArray(data.data.active_loans) && data.data.active_loans.length > 0) {
                        loansList.innerHTML = '<strong>Daftar Buku Belum Dikembalikan:</strong><ul class="mt-1 list-disc list-inside space-y-0.5">' +
                            data.data.active_loans.map(l => `<li>${l.buku ? l.buku.judul_buku : 'Buku'} (${l.eksemplar ? l.eksemplar.kode_inventaris : '-'}) &bull; Tempo: ${l.tanggal_jatuh_tempo}</li>`).join('') +
                            '</ul>';
                    } else if (data.message && data.message.includes('member')) {
                        loansList.innerHTML = `<div class="flex items-center justify-between gap-2 flex-wrap"><span>Pengguna belum dipromosikan menjadi member perpustakaan oleh super admin.</span><a href="{{ route('admin.users.index') }}" target="_blank" class="inline-flex items-center gap-1 font-bold text-primary hover:underline">Buka Manajemen User &rarr;</a></div>`;
                    } else {
                        loansList.innerHTML = 'Pemustaka memiliki catatan transaksi yang belum memenuhi syarat peminjaman sirkulasi.';
                    }
                }
            } catch (err) {
                // Fallback cek atribut lokal bila API gagal
                const select = document.getElementById('select-user');
                const selectedOpt = select.options[select.selectedIndex];
                const isMember = selectedOpt.getAttribute('data-is-member') === '1';
                const hasUnreturned = selectedOpt.getAttribute('data-unreturned') === '1';

                if (!isMember) {
                    box.className = 'rounded-xl border border-rose-300 bg-rose-50/90 p-4 text-xs transition-all';
                    passed.classList.add('hidden');
                    blocked.classList.remove('hidden');
                    btnSubmit.disabled = true;
                    document.getElementById('eligibility-error-desc').textContent = 'Pengguna belum terdaftar sebagai member resmi perpustakaan. Harus dipromote oleh Super Admin terlebih dahulu.';
                    loansList.innerHTML = `<a href="{{ route('admin.users.index') }}" target="_blank" class="font-bold text-primary hover:underline">Promote di Manajemen User &rarr;</a>`;
                } else if (hasUnreturned) {
                    box.className = 'rounded-xl border border-rose-300 bg-rose-50/90 p-4 text-xs transition-all';
                    passed.classList.add('hidden');
                    blocked.classList.remove('hidden');
                    btnSubmit.disabled = true;
                    document.getElementById('eligibility-error-desc').textContent = 'Member masih memiliki tanggungan peminjaman buku yang belum dikembalikan.';
                    loansList.innerHTML = 'Ada buku yang belum dikembalikan.';
                } else {
                    box.className = 'rounded-xl border border-emerald-200 bg-emerald-50/70 p-4 text-xs transition-all';
                    blocked.classList.add('hidden');
                    passed.classList.remove('hidden');
                    btnSubmit.disabled = false;
                }
            }
        }

        // Ambil daftar eksemplar tersedia untuk buku terpilih via API
        async function fetchAvailableCopies(bookId) {
            const selectCopy = document.getElementById('select-copy');
            selectCopy.innerHTML = '<option value="">Memuat eksemplar tersedia...</option>';

            if (!bookId) {
                selectCopy.innerHTML = '<option value="">-- Pilih Eksemplar Fisik Tersedia --</option>';
                return;
            }

            try {
                const res = await fetch(`/api/peminjaman/eksemplar-tersedia/${bookId}`);
                const json = await res.json();

                if (json.success && Array.isArray(json.eksemplar) && json.eksemplar.length > 0) {
                    selectCopy.innerHTML = '<option value="">-- Pilih Eksemplar Fisik Tersedia (' + json.eksemplar.length + ') --</option>' +
                        json.eksemplar.map(c => `<option value="${c.id_eksemplar}">${c.kode_inventaris} &bull; ${c.nomor_panggil || 'Tanpa Call No'} (${c.lokasi_rak || 'Lantai 1'})</option>`).join('');
                } else {
                    selectCopy.innerHTML = '<option value="">⚠️ Tidak ada eksemplar fisik berstatus Tersedia untuk buku ini</option>';
                }
            } catch (err) {
                selectCopy.innerHTML = '<option value="">Gagal memuat eksemplar, silakan pilih ulang</option>';
            }
        }

        // Jalankan pengecekan eligibilitas awal jika sudah ada user terpilih
        document.addEventListener('DOMContentLoaded', () => {
            const currentUserId = document.getElementById('select-user').value;
            if (currentUserId) {
                checkUserEligibility(currentUserId);
            }
        });
    </script>
@endsection
