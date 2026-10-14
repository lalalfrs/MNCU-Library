@extends('layouts.app')

@section('title', 'Beranda')
@section('description', 'Beranda Perpustakaan MNC University. Akses katalog OPAC, repository, e-journal, OJS per prodi, dan e-book dari satu halaman.')

@section('content')
    @php
        /**
         * Layanan di bawah kartu utama (Katalog OPAC). Warna aksen mengikuti tabel §2.1 spesifikasi.
         * TODO (integrasi): isi 'href' dengan URL layanan / route halaman OJS per prodi.
         *
         * @var array<int, array{title: string, description: string, href: string, isExternal: bool, icon: string, iconBox: string, linkColor: string, hoverBorder: string, accentLine: string}> $digitalServices
         */
        $digitalServices = [
            [
                'title' => 'Repository',
                'description' => 'Skripsi, tesis, dan karya ilmiah sivitas MNC University.',
                'href' => '#',
                'isExternal' => true,
                'icon' => 'M3 7.5h18M5 7.5V19a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V7.5M4 4h16v3.5H4zM10 11.5h4',
                'iconBox' => 'bg-[#7C3AED]/8 text-[#7C3AED]',
                'linkColor' => 'text-[#7C3AED]',
                'hoverBorder' => 'hover:border-[#DDD6FE]',
                'accentLine' => 'from-[#8B5CF6] to-[#D946EF]',
            ],
            [
                'title' => 'E-Journal',
                'description' => 'Jurnal elektronik yang dilanggan perpustakaan.',
                'href' => '#',
                'isExternal' => true,
                'icon' => 'M7 3h7l5 5v12a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1zM14 3v5h5M9.5 13h6M9.5 16.5h6',
                'iconBox' => 'bg-[#2563EB]/8 text-[#2563EB]',
                'linkColor' => 'text-[#2563EB]',
                'hoverBorder' => 'hover:border-[#BFDBFE]',
                'accentLine' => 'from-[#0EA5E9] to-[#2563EB]',
            ],
            [
                'title' => 'OJS per Prodi',
                'description' => 'Pilih program studi untuk membuka jurnal OJS-nya.',
                'href' => '#',
                'isExternal' => false,
                'icon' => 'M3 9.5 12 4l9 5.5M5 10v8M9.5 10v8M14.5 10v8M19 10v8M3 20h18',
                'iconBox' => 'bg-[#059669]/8 text-[#047857]',
                'linkColor' => 'text-[#047857]',
                'hoverBorder' => 'hover:border-[#A7F3D0]',
                'accentLine' => 'from-[#10B981] to-[#14B8A6]',
            ],
            [
                'title' => 'E-Book',
                'description' => 'Buku elektronik yang bisa dibaca dari perangkat apa pun.',
                'href' => '#',
                'isExternal' => true,
                'icon' => 'M7 3h10a1 1 0 0 1 1 1v16a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1zM10.5 17.5h3',
                'iconBox' => 'bg-[#E11D48]/8 text-[#E11D48]',
                'linkColor' => 'text-[#E11D48]',
                'hoverBorder' => 'hover:border-[#FECDD3]',
                'accentLine' => 'from-[#F43F5E] to-[#EC4899]',
            ],
        ];

        /**
         * Placeholder tampilan. TODO (integrasi): ganti dengan data berita dari controller.
         * Jika kosong, blok @empty di bawah yang tampil.
         *
         * @var array<int, array{title: string, excerpt: string, date: string}> $latestNews
         */
        $latestNews = array_fill(0, 3, [
            'title' => '[Judul berita]',
            'excerpt' => '[Ringkasan berita, maksimal dua baris]',
            'date' => '[Tanggal]',
        ]);

        /**
         * Placeholder tampilan. TODO (integrasi): ganti dengan data acara dari controller.
         *
         * @var array<int, array{title: string, day: string, month: string, time: string, place: string}> $upcomingEvents
         */
        $upcomingEvents = array_fill(0, 3, [
            'title' => '[Nama acara]',
            'day' => '--',
            'month' => 'Bln',
            'time' => '[Waktu]',
            'place' => '[Tempat]',
        ]);
    @endphp

    <div class="mx-auto flex max-w-[1570px] flex-col gap-12 px-3 pt-4 lg:gap-16 lg:px-6 lg:pt-6">

        {{-- Page header / Hero --}}
        <section aria-labelledby="judul-beranda" class="flex flex-col justify-between px-1 lg:min-h-[calc(100vh-8.5rem)] lg:px-[26px]">
            <div class="my-auto grid items-center gap-8 py-6 md:grid-cols-[1fr_auto] lg:gap-14 lg:py-12">
                <div class="max-w-3xl">
                    <h1 id="judul-beranda" class="text-[32px] leading-tight font-semibold text-primary sm:text-4xl lg:text-[48px]">
                        Perpustakaan MNC University
                    </h1>
                    <p class="mt-4 text-[16px] leading-relaxed text-ink-secondary sm:text-lg lg:text-xl">
                        Pusat sumber belajar dan referensi akademik sivitas MNC University. Jelajahi katalog buku, repository skripsi dan riset, jurnal internasional terindeks, serta koleksi e-book resmi.
                    </p>
                    <div class="mt-8 flex flex-wrap items-center gap-4">
                        <a href="#layanan" class="inline-flex h-12 items-center rounded-xl bg-primary px-6 text-base font-semibold text-white shadow-sm transition-colors duration-150 hover:bg-primary-dark">
                            Pilih Layanan Perpustakaan
                        </a>
                        <a href="https://maps.google.com/maps?q=MNC%20University" target="_blank" rel="noopener" class="inline-flex h-12 items-center gap-2 rounded-xl border border-line bg-white px-5 text-base font-medium text-ink transition-colors duration-150 hover:border-primary/40 hover:bg-subtle">
                            <svg class="size-5 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z" />
                                <circle cx="12" cy="10" r="3" />
                            </svg>
                            Lokasi Kampus
                        </a>
                    </div>
                </div>

                <div class="flex items-center justify-center rounded-2xl bg-subtle p-8 md:p-8 lg:p-10">
                    <img src="{{ asset('images/LogoTXT.png') }}" alt="Logo MNC University" class="h-44 w-auto sm:h-52 md:h-56 lg:h-64">
                </div>
            </div>

            {{-- Garis biru di bawah hero dengan ruang kosong / gap yang lega sebelum layanan --}}
            <div class="mt-auto pt-10 pb-4 lg:pt-16 lg:pb-6">
                <div class="h-0.5 bg-primary"></div>
            </div>
        </section>

        {{-- Komponen card: layanan --}}
        <section id="layanan" aria-labelledby="judul-layanan" class="scroll-mt-28 rounded-2xl border border-line bg-white p-6 lg:p-8">
            <div class="flex flex-wrap items-end justify-between gap-2">
                <div>
                    <h2 id="judul-layanan" class="text-lg font-medium text-ink lg:text-2xl">Layanan Perpustakaan</h2>
                    <p class="mt-1 text-sm text-muted lg:text-base">Pilih layanan sesuai koleksi yang ingin kamu cari.</p>
                </div>
            </div>

            {{-- Kartu utama: satu-satunya layanan yang berupa halaman di situs ini, jadi diberi porsi lebih besar. --}}
            {{-- TODO (integrasi): arahkan ke route halaman OPAC. --}}
            <a href="#" class="group mt-6 flex flex-col gap-5 rounded-2xl border border-line bg-white p-6 transition duration-200 hover:-translate-y-0.5 hover:border-primary/40 hover:shadow-raised motion-reduce:transition-none motion-reduce:hover:translate-y-0 md:flex-row md:items-center md:gap-7 lg:p-8">
                <span class="flex size-16 shrink-0 items-center justify-center rounded-2xl bg-primary/8 text-primary lg:size-20">
                    <svg class="size-10 lg:size-12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M4 19.5V5a2 2 0 0 1 2-2h11v13H6a2 2 0 0 0-2 2zm0 0A1.5 1.5 0 0 0 5.5 21H17" />
                        <circle cx="16.5" cy="16.5" r="3" fill="white" />
                        <path d="m18.7 18.7 2.3 2.3" />
                    </svg>
                </span>
                <span class="flex-1">
                    <span class="block text-xl font-medium text-ink lg:text-2xl">Katalog OPAC</span>
                    <span class="mt-1.5 block text-sm leading-relaxed text-ink-secondary lg:text-base">Cari buku teks, monograf, dan referensi fisik. Cek ketersediaan eksemplar dan lokasi nomor panggil rak secara real-time.</span>
                </span>
                <span class="inline-flex items-center gap-1.5 text-base font-semibold text-primary lg:text-lg">
                    Buka Katalog
                    <svg class="size-5 transition-transform duration-200 group-hover:translate-x-1 motion-reduce:transition-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 6 6 6-6 6" /></svg>
                </span>
            </a>

            <ul class="mt-5 grid gap-5 sm:grid-cols-2 lg:mt-6 lg:grid-cols-4 lg:gap-6">
                @foreach ($digitalServices as $digitalService)
                    <li>
                        <a
                            href="{{ $digitalService['href'] }}"
                            @if ($digitalService['isExternal']) target="_blank" rel="noopener" @endif
                            class="group flex h-full flex-col rounded-xl border border-line bg-white p-5 transition duration-200 hover:-translate-y-0.5 hover:shadow-raised motion-reduce:transition-none motion-reduce:hover:translate-y-0 {{ $digitalService['hoverBorder'] }}"
                        >
                            <span class="flex items-start gap-4">
                                <span class="flex size-[60px] shrink-0 items-center justify-center rounded-xl {{ $digitalService['iconBox'] }}">
                                    <svg class="size-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="{{ $digitalService['icon'] }}" />
                                    </svg>
                                </span>
                                <span>
                                    <span class="block text-base font-medium text-ink lg:text-lg">{{ $digitalService['title'] }}</span>
                                    <span class="mt-1 block text-xs leading-relaxed text-muted lg:text-sm">{{ $digitalService['description'] }}</span>
                                </span>
                            </span>

                            <span class="mt-auto block pt-4">
                                <span class="block h-px bg-line"></span>
                                <span class="mt-4 flex items-center justify-between text-sm">
                                    <span class="text-ink">{{ $digitalService['isExternal'] ? 'Situs eksternal' : 'Halaman prodi' }}</span>
                                    <span class="inline-flex items-center gap-1 font-medium {{ $digitalService['linkColor'] }}">
                                        Buka
                                        @if ($digitalService['isExternal'])
                                            {{-- Panah diagonal menandai tautan membuka tab baru ke situs lain. --}}
                                            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 17 17 7M9 7h8v8" /></svg>
                                            <span class="sr-only">(tab baru)</span>
                                        @else
                                            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 6 6 6-6 6" /></svg>
                                        @endif
                                    </span>
                                </span>
                                <span class="mt-3 block h-1 rounded-full bg-linear-to-r {{ $digitalService['accentLine'] }}"></span>
                            </span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </section>

        {{-- Card berita --}}
        <section aria-labelledby="judul-berita" class="rounded-2xl border border-line bg-white p-5 lg:p-[30px]">
            <div class="flex flex-wrap items-end justify-between gap-3">
                <div>
                    <h2 id="judul-berita" class="text-base font-medium text-ink lg:text-xl">Berita Perpustakaan</h2>
                    <p class="mt-1 text-[13px] text-muted lg:text-base">Kabar terbaru dari perpustakaan MNC University.</p>
                </div>
                {{-- TODO (integrasi): arahkan ke halaman berita lengkap. --}}
                <a href="#" class="inline-flex min-h-11 items-center gap-1 rounded-lg text-sm font-medium text-primary hover:underline lg:text-base">
                    Semua berita
                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 6 6 6-6 6" /></svg>
                </a>
            </div>

            @forelse ($latestNews as $newsItem)
                @if ($loop->first)
                    <ul class="mt-5 grid gap-5 md:grid-cols-2 lg:mt-6 lg:grid-cols-3 lg:gap-[30px]">
                @endif
                        <li>
                            {{-- TODO (integrasi): arahkan ke detail berita. --}}
                            <a href="#" class="group flex h-full flex-col overflow-hidden rounded-xl border border-line bg-white transition duration-200 hover:-translate-y-0.5 hover:shadow-raised motion-reduce:transition-none motion-reduce:hover:translate-y-0">
                                <span class="flex aspect-video items-center justify-center bg-track text-[13px] text-muted">
                                    [Foto berita]
                                </span>
                                <span class="flex flex-1 flex-col p-5">
                                    <span class="text-[13px] text-muted">{{ $newsItem['date'] }}</span>
                                    <span class="mt-2 line-clamp-2 text-base font-medium text-ink group-hover:text-primary lg:text-lg">{{ $newsItem['title'] }}</span>
                                    <span class="mt-2 line-clamp-2 text-sm text-ink-secondary">{{ $newsItem['excerpt'] }}</span>
                                    <span class="mt-auto pt-4 text-sm font-medium text-primary">Baca berita</span>
                                </span>
                            </a>
                        </li>
                @if ($loop->last)
                    </ul>
                @endif
            @empty
                <div class="mt-5 rounded-lg border border-line bg-subtle p-6 text-center">
                    <p class="text-base text-ink">Belum ada berita yang diterbitkan.</p>
                    <p class="mt-1 text-[13px] text-muted">Berita baru dari perpustakaan akan muncul di sini.</p>
                </div>
            @endforelse

            <div class="mt-5 h-1 rounded-full bg-track"></div>
        </section>

        <div class="grid gap-6 lg:grid-cols-[3fr_2fr] lg:gap-[30px]">
            {{-- Acara / event --}}
            <section aria-labelledby="judul-acara" class="rounded-2xl border border-line bg-white p-5 lg:p-[30px]">
                <div class="flex flex-wrap items-end justify-between gap-3">
                    <div>
                        <h2 id="judul-acara" class="text-base font-medium text-ink lg:text-xl">Acara Mendatang</h2>
                        <p class="mt-1 text-[13px] text-muted lg:text-base">Kelas literasi, bedah buku, dan kegiatan perpustakaan lainnya.</p>
                    </div>
                    {{-- TODO (integrasi): arahkan ke halaman event lengkap. --}}
                    <a href="#" class="inline-flex min-h-11 items-center gap-1 rounded-lg text-sm font-medium text-primary hover:underline lg:text-base">
                        Semua acara
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 6 6 6-6 6" /></svg>
                    </a>
                </div>

                @forelse ($upcomingEvents as $eventItem)
                    @if ($loop->first)
                        <ul class="mt-5 flex flex-col gap-3">
                    @endif
                            <li>
                                {{-- TODO (integrasi): arahkan ke detail event. --}}
                                <a href="#" class="group flex items-center gap-4 rounded-lg border border-line bg-white p-3 transition-colors duration-200 hover:border-primary/30 hover:bg-subtle lg:p-4">
                                    <span class="flex size-14 shrink-0 flex-col items-center justify-center rounded-lg bg-primary text-white">
                                        <span class="text-lg leading-none font-semibold">{{ $eventItem['day'] }}</span>
                                        <span class="mt-1 text-[11px]">{{ $eventItem['month'] }}</span>
                                    </span>
                                    <span class="min-w-0 flex-1">
                                        <span class="block truncate text-[15px] font-medium text-ink group-hover:text-primary lg:text-base">{{ $eventItem['title'] }}</span>
                                        <span class="mt-0.5 block truncate text-[13px] text-muted">{{ $eventItem['time'] }} &middot; {{ $eventItem['place'] }}</span>
                                    </span>
                                    <span class="hidden shrink-0 text-sm font-medium text-primary sm:inline">Lihat detail</span>
                                </a>
                            </li>
                    @if ($loop->last)
                        </ul>
                    @endif
                @empty
                    <div class="mt-5 rounded-lg border border-line bg-subtle p-6 text-center">
                        <p class="text-base text-ink">Belum ada acara terjadwal.</p>
                        <p class="mt-1 text-[13px] text-muted">Jadwal kegiatan perpustakaan akan diumumkan di sini.</p>
                    </div>
                @endforelse
            </section>

            {{-- Statistik pengunjung. Angka sengaja kosong sampai data asli tersedia. --}}
            <section aria-labelledby="judul-statistik" class="flex flex-col rounded-2xl border border-line bg-white p-5 lg:p-[30px]">
                <h2 id="judul-statistik" class="text-base font-medium text-ink lg:text-xl">Statistik Pengunjung</h2>
                <p class="mt-1 text-[13px] text-muted lg:text-base">Jumlah kunjungan ke perpustakaan.</p>

                {{-- TODO (integrasi): isi angka dari data kunjungan. --}}
                <dl class="mt-5 grid flex-1 grid-cols-1 gap-3 sm:grid-cols-3 lg:grid-cols-1">
                    @foreach (['Hari ini', 'Bulan ini', 'Total kunjungan'] as $visitorPeriod)
                        <div class="flex items-center justify-between rounded-xl border border-line px-4 py-3 sm:flex-col sm:items-start lg:flex-row lg:items-center">
                            <dt class="text-xs text-muted lg:text-sm">{{ $visitorPeriod }}</dt>
                            <dd class="text-[30px] leading-tight font-medium text-ink lg:text-4xl">&ndash;</dd>
                        </div>
                    @endforeach
                </dl>
                <p class="mt-4 text-[13px] text-muted">Data kunjungan belum tersedia.</p>
            </section>
        </div>

        {{-- Map --}}
        <section aria-labelledby="judul-lokasi" class="rounded-2xl border border-line bg-white p-5 lg:p-[30px]">
            <div class="flex flex-wrap items-end justify-between gap-3">
                <div>
                    <h2 id="judul-lokasi" class="text-base font-medium text-ink lg:text-xl">Lokasi Perpustakaan</h2>
                    <p class="mt-1 text-[13px] text-muted lg:text-base">[Alamat lengkap perpustakaan]</p>
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
                    class="block h-72 w-full lg:h-[420px]"
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                ></iframe>
            </div>
        </section>
    </div>
@endsection
