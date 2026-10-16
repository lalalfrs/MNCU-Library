@extends('layouts.app')

@section('title', 'Ubah Acara Perpustakaan')

@section('content')
    <div class="mx-auto flex w-full max-w-4xl flex-1 flex-col gap-6 px-3 py-6 lg:px-6 lg:py-8">
        {{-- Breadcrumb --}}
        <div>
            <nav aria-label="Breadcrumb" class="flex items-center gap-2 text-xs text-muted sm:text-sm">
                <a href="{{ route('beranda') }}" class="hover:text-primary">Beranda</a>
                <svg class="size-3.5 text-muted/60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
                <a href="{{ route('admin.acara.index') }}" class="hover:text-primary">Manajemen Acara</a>
                <svg class="size-3.5 text-muted/60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
                <span class="font-medium text-ink">Ubah Acara</span>
            </nav>
            <h1 class="mt-1 text-2xl font-bold tracking-tight text-primary sm:text-3xl">
                Ubah Acara: {{ $event->nama_acara }}
            </h1>
            <p class="text-xs font-mono text-muted">{{ $event->id_acara }}</p>
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
            <form action="{{ route('admin.acara.update', $event->id_acara) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label for="nama_acara" class="block text-xs font-semibold text-ink">
                        Nama Acara / Kegiatan <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        name="nama_acara"
                        id="nama_acara"
                        value="{{ old('nama_acara', $event->nama_acara) }}"
                        required
                        class="mt-1.5 h-11 w-full rounded-xl border border-line bg-white px-3.5 text-xs text-ink focus:border-primary focus:outline-none sm:text-sm"
                    >
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="kategori_acara" class="block text-xs font-semibold text-ink">
                            Kategori Acara <span class="text-red-500">*</span>
                        </label>
                        <select
                            name="kategori_acara"
                            id="kategori_acara"
                            required
                            class="mt-1.5 h-11 w-full rounded-xl border border-line bg-white px-3.5 text-xs text-ink focus:border-primary focus:outline-none sm:text-sm"
                        >
                            @php
                                $categories = ['Webinar Nasional', 'Workshop Literasi', 'Bedah Buku', 'Pelatihan Riset', 'Acara Perpustakaan'];
                            @endphp
                            @foreach ($categories as $cat)
                                <option value="{{ $cat }}" {{ old('kategori_acara', $event->kategori_acara) === $cat ? 'selected' : '' }}>
                                    {{ $cat }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="narasumber" class="block text-xs font-semibold text-ink">
                            Narasumber / Pembicara
                        </label>
                        <input
                            type="text"
                            name="narasumber"
                            id="narasumber"
                            value="{{ old('narasumber', $event->narasumber) }}"
                            class="mt-1.5 h-11 w-full rounded-xl border border-line bg-white px-3.5 text-xs text-ink focus:border-primary focus:outline-none sm:text-sm"
                        >
                    </div>
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="tanggal_mulai" class="block text-xs font-semibold text-ink">
                            Waktu Mulai <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="datetime-local"
                            name="tanggal_mulai"
                            id="tanggal_mulai"
                            value="{{ old('tanggal_mulai', $event->tanggal_mulai ? date('Y-m-d\TH:i', strtotime($event->tanggal_mulai)) : '') }}"
                            required
                            class="mt-1.5 h-11 w-full rounded-xl border border-line bg-white px-3.5 text-xs text-ink focus:border-primary focus:outline-none sm:text-sm"
                        >
                    </div>

                    <div>
                        <label for="tanggal_selesai" class="block text-xs font-semibold text-ink">
                            Waktu Selesai (Opsional)
                        </label>
                        <input
                            type="datetime-local"
                            name="tanggal_selesai"
                            id="tanggal_selesai"
                            value="{{ old('tanggal_selesai', $event->tanggal_selesai ? date('Y-m-d\TH:i', strtotime($event->tanggal_selesai)) : '') }}"
                            class="mt-1.5 h-11 w-full rounded-xl border border-line bg-white px-3.5 text-xs text-ink focus:border-primary focus:outline-none sm:text-sm"
                        >
                    </div>
                </div>

                <div class="grid gap-5 sm:grid-cols-3">
                    <div>
                        <label for="lokasi_acara" class="block text-xs font-semibold text-ink">
                            Lokasi / Tempat
                        </label>
                        <input
                            type="text"
                            name="lokasi_acara"
                            id="lokasi_acara"
                            value="{{ old('lokasi_acara', $event->lokasi_acara) }}"
                            class="mt-1.5 h-11 w-full rounded-xl border border-line bg-white px-3.5 text-xs text-ink focus:border-primary focus:outline-none sm:text-sm"
                        >
                    </div>

                    <div>
                        <label for="kuota" class="block text-xs font-semibold text-ink">
                            Kuota Peserta
                        </label>
                        <input
                            type="number"
                            name="kuota"
                            id="kuota"
                            value="{{ old('kuota', $event->kuota) }}"
                            min="1"
                            class="mt-1.5 h-11 w-full rounded-xl border border-line bg-white px-3.5 text-xs text-ink focus:border-primary focus:outline-none sm:text-sm"
                        >
                    </div>

                    <div>
                        <label for="tautan_pendaftaran" class="block text-xs font-semibold text-ink">
                            Tautan Pendaftaran (URL)
                        </label>
                        <input
                            type="url"
                            name="tautan_pendaftaran"
                            id="tautan_pendaftaran"
                            value="{{ old('tautan_pendaftaran', $event->tautan_pendaftaran) }}"
                            class="mt-1.5 h-11 w-full rounded-xl border border-line bg-white px-3.5 text-xs text-ink focus:border-primary focus:outline-none sm:text-sm"
                        >
                    </div>
                </div>

                <div>
                    <label for="ringkasan_acara" class="block text-xs font-semibold text-ink">
                        Ringkasan Singkat Acara
                    </label>
                    <input
                        type="text"
                        name="ringkasan_acara"
                        id="ringkasan_acara"
                        value="{{ old('ringkasan_acara', $event->ringkasan_acara) }}"
                        class="mt-1.5 h-11 w-full rounded-xl border border-line bg-white px-3.5 text-xs text-ink focus:border-primary focus:outline-none sm:text-sm"
                    >
                </div>

                <div>
                    <label for="deskripsi_acara" class="block text-xs font-semibold text-ink">
                        Deskripsi Lengkap & Materi Acara
                    </label>
                    <textarea
                        name="deskripsi_acara"
                        id="deskripsi_acara"
                        rows="4"
                        class="mt-1.5 w-full rounded-xl border border-line bg-white p-3.5 text-xs text-ink focus:border-primary focus:outline-none sm:text-sm"
                    >{{ old('deskripsi_acara', $event->deskripsi_acara) }}</textarea>
                </div>

                <div class="flex items-center gap-2 pt-2">
                    <input
                        type="checkbox"
                        name="is_active"
                        id="is_active"
                        value="1"
                        class="size-4 rounded border-line text-primary accent-primary"
                        {{ old('is_active', $event->is_active) ? 'checked' : '' }}
                    >
                    <label for="is_active" class="text-xs font-medium text-ink cursor-pointer">
                        Status Aktif (Ditampilkan ke pemustaka)
                    </label>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-line">
                    <a
                        href="{{ route('admin.acara.index') }}"
                        class="inline-flex h-10 items-center justify-center rounded-xl border border-line px-5 text-xs font-medium text-muted transition-colors hover:bg-subtle hover:text-ink sm:h-11 sm:text-sm"
                    >
                        Batal
                    </a>
                    <button
                        type="submit"
                        class="inline-flex h-10 items-center justify-center rounded-xl bg-primary px-6 text-xs font-semibold text-white shadow-sm transition-all hover:bg-primary-dark sm:h-11 sm:text-sm"
                    >
                        Perbarui Acara
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
