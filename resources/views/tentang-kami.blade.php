@extends('layouts.app')

@section('title', 'Tentang Kami')
@section('description', 'Informasi tentang Perpustakaan MNC University: sejarah, visi-misi, dan fasilitas yang tersedia.')

@section('content')
    <div class="mx-auto flex max-w-[1570px] flex-col gap-10 sm:gap-12 px-3 sm:px-4 pt-4 lg:gap-14 xl:gap-16 lg:px-6 xl:px-8 lg:pt-6 responsive-desktop-container">

        {{-- Page header --}}
        <section aria-labelledby="judul-tentang" class="px-1 lg:px-[26px]">
            <div class="flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
                <div>
                    <h1 id="judul-tentang" class="text-[28px] leading-tight font-semibold text-primary sm:text-3xl lg:text-4xl">
                        Tentang Perpustakaan
                    </h1>
                    <p class="mt-2 text-sm leading-relaxed text-ink-secondary lg:text-base">
                        Mengenal lebih dekat Perpustakaan MNC University.
                    </p>
                </div>
            </div>
            <div class="mt-6 h-0.5 bg-primary"></div>
        </section>

        {{-- Sejarah --}}
        <section aria-labelledby="judul-sejarah" class="rounded-2xl border border-line bg-white p-6 lg:p-8">
            <div class="grid items-center gap-8 md:grid-cols-[auto_1fr] lg:gap-12">
                <div class="flex items-center justify-center rounded-2xl bg-subtle p-6 md:p-8">
                    <img src="{{ asset('images/LogoTXT.png') }}" alt="Logo MNC University" class="h-20 max-w-[200px] w-auto object-contain sm:h-24 sm:max-w-[240px] lg:h-28" style="max-height: 112px; max-width: 240px;" width="240" height="112">
                </div>
                <div>
                    <h2 id="judul-sejarah" class="text-lg font-medium text-ink lg:text-2xl">Sejarah Perpustakaan</h2>
                    <p class="mt-3 text-sm leading-relaxed text-ink-secondary lg:text-base">
                        Perpustakaan MNC University berdiri sebagai bagian integral dari kampus sejak awal pendirian universitas.
                        Fasilitas ini dirancang untuk mendukung kegiatan akademik sivitas akademika melalui penyediaan koleksi
                        buku teks, jurnal ilmiah, dan sumber referensi digital yang terus berkembang sesuai kebutuhan kurikulum.
                    </p>
                    <p class="mt-3 text-sm leading-relaxed text-ink-secondary lg:text-base">
                        Seiring pertumbuhan universitas, perpustakaan memperluas layanannya ke ranah digital: katalog OPAC daring,
                        repository skripsi dan riset, akses jurnal internasional terindeks, serta koleksi e-book yang dapat
                        diakses dari perangkat apa pun.
                    </p>
                </div>
            </div>
            <div class="mt-6 h-1 rounded-full bg-linear-to-r from-[#0EA5E9] to-[#2563EB]"></div>
        </section>

        {{-- Visi & Misi --}}
        <div class="grid gap-6 md:grid-cols-2 lg:gap-[30px]">
            <section aria-labelledby="judul-visi" class="flex flex-col rounded-2xl border border-line bg-white p-6 lg:p-8">
                <div class="flex size-14 items-center justify-center rounded-xl bg-[#7C3AED]/8 text-[#7C3AED] lg:size-16">
                    <svg class="size-8 lg:size-9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="10" />
                        <circle cx="12" cy="12" r="6" />
                        <circle cx="12" cy="12" r="2" />
                    </svg>
                </div>
                <h2 id="judul-visi" class="mt-5 text-lg font-medium text-ink lg:text-2xl">Visi</h2>
                <p class="mt-3 flex-1 text-sm leading-relaxed text-ink-secondary lg:text-base">
                    Menjadi pusat sumber belajar dan informasi yang unggul, terpercaya, serta mampu mendukung
                    tri dharma perguruan tinggi di lingkungan MNC University.
                </p>
                <div class="mt-6 h-1 rounded-full bg-linear-to-r from-[#8B5CF6] to-[#D946EF]"></div>
            </section>

            <section aria-labelledby="judul-misi" class="flex flex-col rounded-2xl border border-line bg-white p-6 lg:p-8">
                <div class="flex size-14 items-center justify-center rounded-xl bg-[#059669]/8 text-[#047857] lg:size-16">
                    <svg class="size-8 lg:size-9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M9 11l3 3L22 4" />
                        <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11" />
                    </svg>
                </div>
                <h2 id="judul-misi" class="mt-5 text-lg font-medium text-ink lg:text-2xl">Misi</h2>
                <ul class="mt-3 flex-1 space-y-2 text-sm leading-relaxed text-ink-secondary lg:text-base">
                    <li class="flex gap-2">
                        <span class="mt-1.5 size-1.5 shrink-0 rounded-full bg-[#047857]" aria-hidden="true"></span>
                        Menyediakan koleksi bahan pustaka cetak dan digital yang relevan dengan kebutuhan akademik.
                    </li>
                    <li class="flex gap-2">
                        <span class="mt-1.5 size-1.5 shrink-0 rounded-full bg-[#047857]" aria-hidden="true"></span>
                        Menyelenggarakan layanan informasi yang cepat, tepat, dan mudah diakses.
                    </li>
                    <li class="flex gap-2">
                        <span class="mt-1.5 size-1.5 shrink-0 rounded-full bg-[#047857]" aria-hidden="true"></span>
                        Mendukung kegiatan penelitian dan pengabdian masyarakat melalui penyediaan akses jurnal dan repository.
                    </li>
                    <li class="flex gap-2">
                        <span class="mt-1.5 size-1.5 shrink-0 rounded-full bg-[#047857]" aria-hidden="true"></span>
                        Meningkatkan literasi informasi sivitas akademika MNC University.
                    </li>
                </ul>
                <div class="mt-6 h-1 rounded-full bg-linear-to-r from-[#10B981] to-[#14B8A6]"></div>
            </section>
        </div>

        {{-- Fasilitas --}}
        <section aria-labelledby="judul-fasilitas" class="rounded-2xl border border-line bg-white p-6 lg:p-8">
            <h2 id="judul-fasilitas" class="text-lg font-medium text-ink lg:text-2xl">Fasilitas</h2>
            <p class="mt-1 text-sm text-muted lg:text-base">Ruang dan sarana yang tersedia di perpustakaan.</p>

            @php
                $facilities = [
                    [
                        'name' => 'Ruang Baca',
                        'description' => 'Area membaca dengan pencahayaan yang nyaman dan meja baca individual maupun kelompok.',
                        'icon' => 'M4 19.5V5a2 2 0 0 1 2-2h11v13H6a2 2 0 0 0-2 2zm0 0A1.5 1.5 0 0 0 5.5 21H17',
                        'iconBox' => 'bg-[#2563EB]/8 text-[#2563EB]',
                    ],
                    [
                        'name' => 'Ruang Diskusi',
                        'description' => 'Ruang tertutup untuk diskusi kelompok kecil, dilengkapi whiteboard dan proyektor.',
                        'icon' => 'M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75',
                        'iconBox' => 'bg-[#7C3AED]/8 text-[#7C3AED]',
                    ],
                    [
                        'name' => 'Akses Internet & Wi-Fi',
                        'description' => 'Jaringan Wi-Fi kampus tersedia di seluruh area perpustakaan untuk akses sumber digital.',
                        'icon' => 'M5 12.55a11 11 0 0 1 14.08 0M1.42 9a16 16 0 0 1 21.16 0M8.53 16.11a6 6 0 0 1 6.95 0M12 20h.01',
                        'iconBox' => 'bg-[#059669]/8 text-[#047857]',
                    ],
                    [
                        'name' => 'Komputer Katalog',
                        'description' => 'Terminal komputer untuk mencari koleksi melalui OPAC dan mengakses database jurnal.',
                        'icon' => 'M4 5h16a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1zM8 21h8M12 17v4',
                        'iconBox' => 'bg-[#E11D48]/8 text-[#E11D48]',
                    ],
                ];
            @endphp

            <ul class="mt-5 grid gap-5 sm:grid-cols-2 lg:mt-6 lg:grid-cols-2 xl:grid-cols-4 lg:gap-5 xl:gap-6">
                @foreach ($facilities as $facility)
                    <li class="flex gap-4 rounded-xl border border-line bg-white p-5 transition duration-200 hover:-translate-y-0.5 hover:shadow-raised motion-reduce:transition-none motion-reduce:hover:translate-y-0">
                        <span class="flex size-[52px] shrink-0 items-center justify-center rounded-xl {{ $facility['iconBox'] }}">
                            <svg class="size-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="{{ $facility['icon'] }}" />
                            </svg>
                        </span>
                        <span>
                            <span class="block text-base font-medium text-ink">{{ $facility['name'] }}</span>
                            <span class="mt-1 block text-sm leading-relaxed text-muted">{{ $facility['description'] }}</span>
                        </span>
                    </li>
                @endforeach
            </ul>

            <div class="mt-6 h-1 rounded-full bg-track"></div>
        </section>

        {{-- Jam operasional --}}
        <section aria-labelledby="judul-jam" class="rounded-2xl border border-line bg-white p-6 lg:p-8">
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3 border-b border-line/70 pb-4">
                <div>
                    <h2 id="judul-jam" class="text-lg font-medium text-ink lg:text-2xl">Jam Operasional Perpustakaan</h2>
                    <p class="mt-1 text-sm text-muted lg:text-base">Jadwal resmi waktu layanan luring (offline) dan daring (online) bagi sivitas akademika.</p>
                </div>
            </div>

            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-5 lg:gap-6">
                {{-- 1. Senin s.d. Jumat --}}
                <div class="relative flex flex-col justify-between overflow-hidden rounded-2xl border border-blue-200/80 bg-gradient-to-br from-white via-slate-50/80 to-blue-50/40 p-6 shadow-xs transition-all duration-300 hover:shadow-md hover:border-blue-400">
                    <div>
                        <div class="flex items-center justify-between gap-3">
                            <span class="flex size-12 items-center justify-center rounded-xl bg-[#2563EB]/10 text-[#2563EB] shadow-2xs border border-blue-100">
                                <i class="fa-solid fa-building-columns text-lg" aria-hidden="true"></i>
                            </span>
                            <span class="rounded-lg bg-blue-50 px-2.5 py-1 text-xs font-semibold text-[#2563EB] border border-blue-200">
                                Layanan Penuh
                            </span>
                        </div>

                        <h3 class="mt-4 text-base font-bold text-ink sm:text-lg">
                            Senin s.d. Jumat
                        </h3>
                        <p class="mt-1 text-xs text-muted">Hari Kerja Reguler</p>

                        <div class="mt-4 rounded-xl border border-blue-200/70 bg-white/90 p-3.5">
                            <span class="text-[11px] font-medium text-slate-500 block">Jam Buka Layanan:</span>
                            <span class="mt-0.5 text-base sm:text-lg font-extrabold text-[#0b1f3a] tracking-tight block">
                                08.00 &ndash; 16.00 WIB
                            </span>
                        </div>

                        <p class="mt-4 text-xs leading-relaxed text-slate-600">
                            Sirkulasi peminjaman &amp; pengembalian fisik, ruang baca, komputer katalog, serta konsultasi pustakawan beroperasi penuh.
                        </p>
                    </div>

                    <div class="mt-6 h-1 w-full rounded-full bg-gradient-to-r from-[#0EA5E9] to-[#2563EB]"></div>
                </div>

                {{-- 2. Sabtu --}}
                <div class="relative flex flex-col justify-between overflow-hidden rounded-2xl border border-purple-200/80 bg-gradient-to-br from-white via-slate-50/80 to-purple-50/40 p-6 shadow-xs transition-all duration-300 hover:shadow-md hover:border-purple-400">
                    <div>
                        <div class="flex items-center justify-between gap-3">
                            <span class="flex size-12 items-center justify-center rounded-xl bg-[#7C3AED]/10 text-[#7C3AED] shadow-2xs border border-purple-100">
                                <i class="fa-solid fa-clock text-lg" aria-hidden="true"></i>
                            </span>
                            <span class="rounded-lg bg-purple-50 px-2.5 py-1 text-xs font-semibold text-[#7C3AED] border border-purple-200">
                                Layanan Terbatas
                            </span>
                        </div>

                        <h3 class="mt-4 text-base font-bold text-ink sm:text-lg">
                            Sabtu
                        </h3>
                        <p class="mt-1 text-xs text-muted">Akhir Pekan</p>

                        <div class="mt-4 rounded-xl border border-purple-200/70 bg-white/90 p-3.5">
                            <span class="text-[11px] font-medium text-slate-500 block">Jam Buka Layanan:</span>
                            <span class="mt-0.5 text-base sm:text-lg font-extrabold text-[#0b1f3a] tracking-tight block">
                                09.00 &ndash; 13.00 WIB
                            </span>
                        </div>

                        <p class="mt-4 text-xs leading-relaxed text-slate-600">
                            Layanan sirkulasi buku, pengembalian mandiri, dan fasilitas ruang baca untuk mahasiswa perkuliahan akhir pekan.
                        </p>
                    </div>

                    <div class="mt-6 h-1 w-full rounded-full bg-gradient-to-r from-[#8B5CF6] to-[#D946EF]"></div>
                </div>

                {{-- 3. Minggu & Libur Nasional --}}
                <div class="relative flex flex-col justify-between overflow-hidden rounded-2xl border border-rose-200/80 bg-gradient-to-br from-white via-slate-50/80 to-rose-50/40 p-6 shadow-xs transition-all duration-300 hover:shadow-md hover:border-rose-400">
                    <div>
                        <div class="flex items-center justify-between gap-3">
                            <span class="flex size-12 items-center justify-center rounded-xl bg-[#E11D48]/10 text-[#E11D48] shadow-2xs border border-rose-100">
                                <i class="fa-solid fa-calendar-xmark text-lg" aria-hidden="true"></i>
                            </span>
                            <span class="rounded-lg bg-rose-50 px-2.5 py-1 text-xs font-semibold text-[#E11D48] border border-rose-200">
                                Fisik Tutup
                            </span>
                        </div>

                        <h3 class="mt-4 text-base font-bold text-ink sm:text-lg">
                            Minggu &amp; Hari Libur
                        </h3>
                        <p class="mt-1 text-xs text-muted">Libur Nasional</p>

                        <div class="mt-4 rounded-xl border border-rose-200/70 bg-white/90 p-3.5">
                            <span class="text-[11px] font-medium text-slate-500 block">Status Operasional Fisik:</span>
                            <span class="mt-0.5 text-base sm:text-lg font-extrabold text-rose-700 tracking-tight block">
                                Tutup (Layanan Fisik)
                            </span>
                        </div>

                        <p class="mt-4 text-xs leading-relaxed text-slate-600">
                            Gedung perpustakaan tutup. Seluruh portal digital (OPAC, Repository, E-Book, dan E-Journal) tetap aktif 24 jam.
                        </p>
                    </div>

                    <div class="mt-6 h-1 w-full rounded-full bg-gradient-to-r from-[#F43F5E] to-[#E11D48]"></div>
                </div>
            </div>

            {{-- Info Akses Digital 24/7 --}}
            <div class="mt-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3 rounded-xl border border-emerald-200/80 bg-emerald-50/50 p-4 text-xs sm:text-sm text-emerald-900">
                <div class="flex items-center gap-2.5">
                    <span class="flex size-8 shrink-0 items-center justify-center rounded-lg bg-emerald-600 text-white">
                        <i class="fa-solid fa-globe text-xs" aria-hidden="true"></i>
                    </span>
                    <span>
                        <strong>Layanan Digital 24 Jam:</strong> Akses katalog OPAC, e-journal internasional, dan repository skripsi tetap dapat diakses kapan saja secara daring.
                    </span>
                </div>
                <a href="{{ route('opac') }}" class="font-semibold text-emerald-800 hover:text-emerald-950 underline shrink-0">
                    Jelajahi OPAC &rarr;
                </a>
            </div>
        </section>
    </div>
@endsection
