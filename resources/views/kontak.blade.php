@extends('layouts.app')

@section('title', 'Kontak')
@section('description', 'Hubungi Perpustakaan MNC University. Temukan alamat, telepon, email, dan formulir pertanyaan.')

@section('content')
    <div class="mx-auto flex max-w-[1570px] flex-col gap-12 px-3 pt-4 lg:gap-16 lg:px-6 lg:pt-6">

        {{-- Page header --}}
        <section aria-labelledby="judul-kontak" class="px-1 lg:px-[26px]">
            <div class="flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
                <div>
                    <h1 id="judul-kontak" class="text-[28px] leading-tight font-semibold text-primary sm:text-3xl lg:text-4xl">
                        Kontak Perpustakaan
                    </h1>
                    <p class="mt-2 text-sm leading-relaxed text-ink-secondary lg:text-base">
                        Informasi kontak pengelola Perpustakaan MNC University.
                    </p>
                </div>
            </div>
            <div class="mt-6 h-0.5 bg-primary"></div>
        </section>

        {{-- Informasi kontak --}}
        <div class="grid gap-6 lg:grid-cols-[2fr_3fr] lg:gap-[30px]">

            {{-- Kartu kontak --}}
            <div class="flex flex-col gap-6">
                @php
                    $contacts = [
                        [
                            'label' => 'Alamat',
                            'value' => 'Gedung Perpustakaan MNC University, Jl. Haji Ten No.34, RT.7/RW.2, Kebon Sirih, Kec. Menteng, Kota Jakarta Pusat, DKI Jakarta 10340',
                            'icon' => 'M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z M12 13a3 3 0 1 0 0-6 3 3 0 0 0 0 6z',
                            'iconBox' => 'bg-[#E11D48]/8 text-[#E11D48]',
                            'accentLine' => 'from-[#F43F5E] to-[#EC4899]',
                        ],
                        [
                            'label' => 'Telepon',
                            'value' => '(021) 3900-822',
                            'icon' => 'M22 16.92v3a2 2 0 0 1-2.18 2A19.86 19.86 0 0 1 3.09 4.18 2 2 0 0 1 5.09 2h3a2 2 0 0 1 2 1.72c.13.81.36 1.6.68 2.34a2 2 0 0 1-.45 2.11L9 9.5a16 16 0 0 0 5.5 5.5l1.33-1.33a2 2 0 0 1 2.11-.45c.74.32 1.53.55 2.34.68A2 2 0 0 1 22 16.92z',
                            'iconBox' => 'bg-[#2563EB]/8 text-[#2563EB]',
                            'accentLine' => 'from-[#0EA5E9] to-[#2563EB]',
                        ],
                        [
                            'label' => 'Email',
                            'value' => 'perpustakaan@mncu.ac.id',
                            'icon' => 'M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z M22 6l-10 7L2 6',
                            'iconBox' => 'bg-[#7C3AED]/8 text-[#7C3AED]',
                            'accentLine' => 'from-[#8B5CF6] to-[#D946EF]',
                        ],
                        [
                            'label' => 'Jam Layanan',
                            'value' => 'Senin s.d. Jumat: 08.00 s.d. 16.00 WIB, Sabtu: 09.00 s.d. 13.00 WIB',
                            'icon' => 'M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z M12 6v6l4 2',
                            'iconBox' => 'bg-[#059669]/8 text-[#047857]',
                            'accentLine' => 'from-[#10B981] to-[#14B8A6]',
                        ],
                    ];
                @endphp

                @foreach ($contacts as $contact)
                    <div class="rounded-xl border border-line bg-white p-5 transition duration-200 hover:shadow-raised motion-reduce:transition-none">
                        <div class="flex gap-4">
                            <span class="flex size-12 shrink-0 items-center justify-center rounded-xl {{ $contact['iconBox'] }}">
                                <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="{{ $contact['icon'] }}" />
                                </svg>
                            </span>
                            <div class="min-w-0">
                                <span class="block text-xs font-medium tracking-wide text-muted">{{ $contact['label'] }}</span>
                                <span class="mt-1 block text-sm leading-relaxed text-ink lg:text-base">{{ $contact['value'] }}</span>
                            </div>
                        </div>
                        <div class="mt-4 h-1 rounded-full bg-linear-to-r {{ $contact['accentLine'] }}"></div>
                    </div>
                @endforeach
            </div>

            {{-- Formulir pertanyaan --}}
            <section aria-labelledby="judul-form" class="flex flex-col rounded-2xl border border-line bg-white p-6 lg:p-8">
                <h2 id="judul-form" class="text-lg font-medium text-ink lg:text-2xl">Kirim Pertanyaan</h2>
                <p class="mt-1 text-sm text-muted lg:text-base">Ada pertanyaan atau saran? Tulis pesan di bawah ini.</p>

                {{-- TODO (integrasi): hubungkan ke controller. --}}
                <form action="#" method="POST" class="mt-6 flex flex-1 flex-col gap-5">
                    @csrf
                    <div>
                        <label for="kontak-nama" class="block text-sm font-medium text-ink">Nama Lengkap</label>
                        <input
                            type="text"
                            id="kontak-nama"
                            name="nama"
                            required
                            class="mt-1.5 block w-full rounded-xl border border-line bg-white px-4 py-3 text-sm text-ink placeholder:text-muted focus:border-primary focus:ring-1 focus:ring-primary lg:text-base"
                            placeholder="Masukkan nama lengkap"
                        >
                    </div>

                    <div>
                        <label for="kontak-email" class="block text-sm font-medium text-ink">Email</label>
                        <input
                            type="email"
                            id="kontak-email"
                            name="email"
                            required
                            class="mt-1.5 block w-full rounded-xl border border-line bg-white px-4 py-3 text-sm text-ink placeholder:text-muted focus:border-primary focus:ring-1 focus:ring-primary lg:text-base"
                            placeholder="contoh@email.com"
                        >
                    </div>

                    <div>
                        <label for="kontak-subjek" class="block text-sm font-medium text-ink">Subjek</label>
                        <input
                            type="text"
                            id="kontak-subjek"
                            name="subjek"
                            required
                            class="mt-1.5 block w-full rounded-xl border border-line bg-white px-4 py-3 text-sm text-ink placeholder:text-muted focus:border-primary focus:ring-1 focus:ring-primary lg:text-base"
                            placeholder="Topik pertanyaan"
                        >
                    </div>

                    <div class="flex-1">
                        <label for="kontak-pesan" class="block text-sm font-medium text-ink">Pesan</label>
                        <textarea
                            id="kontak-pesan"
                            name="pesan"
                            rows="5"
                            required
                            class="mt-1.5 block w-full resize-y rounded-xl border border-line bg-white px-4 py-3 text-sm text-ink placeholder:text-muted focus:border-primary focus:ring-1 focus:ring-primary lg:text-base"
                            placeholder="Tulis pesan Anda di sini..."
                        ></textarea>
                    </div>

                    <div>
                        <button
                            type="submit"
                            class="inline-flex h-12 items-center rounded-xl bg-primary px-6 text-base font-semibold text-white shadow-sm transition-colors duration-150 hover:bg-primary-dark"
                        >
                            Kirim Pesan
                        </button>
                    </div>
                </form>

                <div class="mt-6 h-1 rounded-full bg-linear-to-r from-[#0EA5E9] to-[#2563EB]"></div>
            </section>
        </div>

        {{-- Peta --}}
        <section aria-labelledby="judul-lokasi-kontak" class="rounded-2xl border border-line bg-white p-5 lg:p-[30px]">
            <div class="flex flex-wrap items-end justify-between gap-3">
                <div>
                    <h2 id="judul-lokasi-kontak" class="text-base font-medium text-ink lg:text-xl">Lokasi Perpustakaan</h2>
                    <p class="mt-1 text-[13px] text-muted lg:text-base">Jl. Haji Ten No.34, Kebon Sirih, Jakarta Pusat</p>
                </div>
                <a href="https://www.google.com/maps/search/?api=1&query=MNC+University" target="_blank" rel="noopener" class="inline-flex min-h-11 items-center gap-1 rounded-lg text-sm font-medium text-primary hover:underline lg:text-base">
                    Buka di Google Maps
                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 17 17 7M9 7h8v8" /></svg>
                    <span class="sr-only">(tab baru)</span>
                </a>
            </div>

            <div class="mt-5 overflow-hidden rounded-xl border border-line bg-track">
                <iframe
                    title="Peta lokasi Perpustakaan MNC University"
                    src="https://maps.google.com/maps?q=MNC%20University&z=16&output=embed"
                    class="block h-72 w-full lg:h-[380px]"
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                ></iframe>
            </div>
        </section>
    </div>
@endsection
