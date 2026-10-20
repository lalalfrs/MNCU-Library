@extends('layouts.app')

@section('title', 'Tentang Kami')
@section('description', 'Informasi tentang Perpustakaan MNC University: sejarah, visi-misi, dan fasilitas yang tersedia.')

@section('content')
    <div class="mx-auto flex max-w-[1570px] flex-col gap-12 px-3 pt-4 lg:gap-16 lg:px-6 lg:pt-6">

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

            <ul class="mt-5 grid gap-5 sm:grid-cols-2 lg:mt-6 lg:gap-6">
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
            <h2 id="judul-jam" class="text-lg font-medium text-ink lg:text-2xl">Jam Operasional</h2>
            <p class="mt-1 text-sm text-muted lg:text-base">Waktu layanan perpustakaan setiap minggu.</p>

            <div class="mt-5 overflow-x-auto">
                <table class="w-full min-w-[400px] text-left text-sm lg:text-base">
                    <thead>
                        <tr class="border-b border-line">
                            <th class="py-3 pr-4 font-medium text-ink">Hari</th>
                            <th class="py-3 pr-4 font-medium text-ink">Jam Buka</th>
                            <th class="py-3 font-medium text-ink">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="text-ink-secondary">
                        <tr class="border-b border-line/50">
                            <td class="py-3 pr-4">Senin s.d. Jumat</td>
                            <td class="py-3 pr-4">08.00 s.d. 16.00 WIB</td>
                            <td class="py-3">Layanan penuh</td>
                        </tr>
                        <tr class="border-b border-line/50">
                            <td class="py-3 pr-4">Sabtu</td>
                            <td class="py-3 pr-4">09.00 s.d. 13.00 WIB</td>
                            <td class="py-3">Layanan terbatas</td>
                        </tr>
                        <tr>
                            <td class="py-3 pr-4">Minggu & Hari Libur</td>
                            <td class="py-3 pr-4 text-muted">Tutup</td>
                            <td class="py-3 text-muted">Akses digital tetap tersedia</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-6 h-1 rounded-full bg-linear-to-r from-[#0EA5E9] to-[#14B8A6]"></div>
        </section>
    </div>
@endsection
