@extends('layouts.app')

@section('title', 'E-Journal & Database Ilmiah')
@section('description', 'Akses basis data jurnal elektronik internasional dan nasional terakreditasi untuk sivitas akademika MNC University. Menemukan referensi riset terpercaya.')

@section('content')
    <div class="mx-auto flex w-full max-w-[1570px] flex-1 flex-col gap-8 px-3 py-6 sm:px-4 lg:px-6 xl:px-8 lg:py-10 responsive-desktop-container">
        {{-- Breadcrumb & Header --}}
        <div class="flex flex-col gap-2">
            <nav aria-label="Breadcrumb" class="flex items-center gap-2 text-xs text-muted sm:text-sm">
                <a href="{{ route('beranda') }}" class="hover:text-primary">Beranda</a>
                <svg class="size-3.5 text-muted/60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
                <span class="font-medium text-ink">E-Journal</span>
            </nav>
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-semibold text-primary sm:text-3xl lg:text-4xl">
                        Portal E-Journal & Database Riset
                    </h1>
                    <p class="mt-1 text-sm text-ink-secondary sm:text-base">
                        Koleksi database jurnal internasional dan nasional untuk mendukung riset serta penulisan karya ilmiah mahasiswa dan dosen.
                    </p>
                </div>
                <div class="rounded-xl border border-[#BFDBFE] bg-[#2563EB]/5 px-4 py-2 text-xs text-[#2563EB]">
                    Database Terintegrasi: <span class="font-bold">3 Subjek Koleksi Riset</span>
                </div>
            </div>
        </div>

        {{-- Section: Akses Portal Jurnal Berdasarkan Subjek (Gale Cengage) --}}
        <section aria-labelledby="subjek-jurnal-heading" class="flex flex-col gap-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-200/80 pb-3">
                <div>
                    <div class="inline-flex items-center gap-1.5 rounded-full bg-[#0b2a5b]/10 px-3 py-1 text-xs font-bold text-[#0b2a5b] mb-1.5">
                        <i class="fa-solid fa-key text-[#f59e0b] text-[11px]"></i>
                        Akses Database Jurnal Internasional
                    </div>
                    <h2 id="subjek-jurnal-heading" class="text-lg sm:text-xl lg:text-2xl font-bold text-[#0b1f3a]">
                        Akses Portal Jurnal Berdasarkan Subjek
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                        Gunakan tautan resmi dan password tertera di bawah untuk mengakses koleksi jurnal serta basis data riset Gale Cengage.
                    </p>
                </div>
                <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700 border border-emerald-200 shrink-0 self-start sm:self-auto">
                    <span class="size-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Akses Aktif Kampus
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 lg:gap-6">
                {{-- 1. Subjek Ekonomi --}}
                <div class="group relative flex flex-col justify-between overflow-hidden rounded-[24px] border border-slate-200/90 bg-gradient-to-br from-white via-slate-50/80 to-blue-50/50 p-6 sm:p-7 shadow-[0_8px_30px_rgba(15,23,42,0.04)] transition-all duration-300 hover:shadow-md hover:border-primary/40">
                    <div class="pointer-events-none absolute -top-10 -right-10 size-32 rounded-full bg-blue-100/40 blur-xl" aria-hidden="true"></div>
                    <div class="pointer-events-none absolute -bottom-10 -left-10 size-32 rounded-full bg-amber-100/30 blur-xl" aria-hidden="true"></div>

                    <div class="relative z-10">
                        <div class="flex items-center justify-between gap-3">
                            <span class="flex size-12 items-center justify-center rounded-2xl bg-white p-2.5 shadow-xs border border-slate-100 text-[#0b2a5b]">
                                <i class="fa-solid fa-chart-line text-lg text-[#0b2a5b]"></i>
                            </span>
                            <span class="rounded-full bg-[#0b2a5b]/10 px-3 py-1 text-[11px] font-bold text-[#0b2a5b]">
                                Gale Economics
                            </span>
                        </div>

                        <h3 class="mt-4 text-lg font-bold text-[#0b1f3a] group-hover:text-primary transition-colors">
                            Subjek Ekonomi
                        </h3>
                        <p class="mt-1 text-xs text-slate-500 leading-relaxed">
                            Manajemen bisnis, akuntansi, perpajakan, perbankan, dan ekonomi global.
                        </p>

                        {{-- Kotak Password --}}
                        <div class="mt-4 rounded-xl border border-slate-200/80 bg-white/95 p-3.5 shadow-2xs backdrop-blur-xs">
                            <div class="flex items-center justify-between text-xs text-slate-500 mb-1.5">
                                <span class="font-medium flex items-center gap-1.5">
                                    <i class="fa-solid fa-lock text-[#f59e0b] text-[11px]"></i>
                                    Password Akses:
                                </span>
                                <span class="text-[10px] text-slate-400">Klik tombol salin</span>
                            </div>
                            <div class="flex items-center justify-between gap-2">
                                <code class="font-mono text-sm sm:text-base font-extrabold text-[#0b1f3a] tracking-wider select-all bg-slate-50 px-2.5 py-1 rounded-lg border border-slate-200">
                                    harmony
                                </code>
                                <button
                                    type="button"
                                    onclick="copyPassword('harmony', this)"
                                    class="inline-flex items-center gap-1.5 rounded-lg bg-[#0b2a5b]/10 hover:bg-[#0b2a5b] text-[#0b2a5b] hover:text-white px-3 py-1.5 text-xs font-semibold transition-all cursor-pointer"
                                    title="Salin password ke clipboard"
                                >
                                    <i class="fa-regular fa-copy text-xs"></i>
                                    <span>Salin</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="relative z-10 mt-6 pt-4 border-t border-slate-200/70">
                        <a
                            href="https://link.gale.com/apps/PPBE?u=fjktbus"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="btn-press inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#0b2a5b] hover:bg-primary-dark px-4 py-3 text-xs sm:text-sm font-bold text-white shadow-sm transition-all cursor-pointer"
                        >
                            <span>Buka Portal Subjek Ekonomi</span>
                            <i class="fa-solid fa-arrow-up-right-from-square text-xs text-[#f59e0b]"></i>
                        </a>
                    </div>
                </div>

                {{-- 2. Subjek Teknik --}}
                <div class="group relative flex flex-col justify-between overflow-hidden rounded-[24px] border border-slate-200/90 bg-gradient-to-br from-white via-slate-50/80 to-blue-50/50 p-6 sm:p-7 shadow-[0_8px_30px_rgba(15,23,42,0.04)] transition-all duration-300 hover:shadow-md hover:border-primary/40">
                    <div class="pointer-events-none absolute -top-10 -right-10 size-32 rounded-full bg-blue-100/40 blur-xl" aria-hidden="true"></div>
                    <div class="pointer-events-none absolute -bottom-10 -left-10 size-32 rounded-full bg-amber-100/30 blur-xl" aria-hidden="true"></div>

                    <div class="relative z-10">
                        <div class="flex items-center justify-between gap-3">
                            <span class="flex size-12 items-center justify-center rounded-2xl bg-white p-2.5 shadow-xs border border-slate-100 text-[#0284c7]">
                                <i class="fa-solid fa-microchip text-lg text-[#0284c7]"></i>
                            </span>
                            <span class="rounded-full bg-[#0284c7]/10 px-3 py-1 text-[11px] font-bold text-[#0284c7]">
                                Gale Science & Tech
                            </span>
                        </div>

                        <h3 class="mt-4 text-lg font-bold text-[#0b1f3a] group-hover:text-primary transition-colors">
                            Subjek Teknik
                        </h3>
                        <p class="mt-1 text-xs text-slate-500 leading-relaxed">
                            Informatika, rekayasa perangkat lunak, sistem cerdas, dan sains terapan.
                        </p>

                        {{-- Kotak Password --}}
                        <div class="mt-4 rounded-xl border border-slate-200/80 bg-white/95 p-3.5 shadow-2xs backdrop-blur-xs">
                            <div class="flex items-center justify-between text-xs text-slate-500 mb-1.5">
                                <span class="font-medium flex items-center gap-1.5">
                                    <i class="fa-solid fa-lock text-[#f59e0b] text-[11px]"></i>
                                    Password Akses:
                                </span>
                                <span class="text-[10px] text-slate-400">Klik tombol salin</span>
                            </div>
                            <div class="flex items-center justify-between gap-2">
                                <code class="font-mono text-sm sm:text-base font-extrabold text-[#0b1f3a] tracking-wider select-all bg-slate-50 px-2.5 py-1 rounded-lg border border-slate-200">
                                    keeper
                                </code>
                                <button
                                    type="button"
                                    onclick="copyPassword('keeper', this)"
                                    class="inline-flex items-center gap-1.5 rounded-lg bg-[#0b2a5b]/10 hover:bg-[#0b2a5b] text-[#0b2a5b] hover:text-white px-3 py-1.5 text-xs font-semibold transition-all cursor-pointer"
                                    title="Salin password ke clipboard"
                                >
                                    <i class="fa-regular fa-copy text-xs"></i>
                                    <span>Salin</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="relative z-10 mt-6 pt-4 border-t border-slate-200/70">
                        <a
                            href="https://link.gale.com/apps/PPGS?u=fjktsci"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="btn-press inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#0b2a5b] hover:bg-primary-dark px-4 py-3 text-xs sm:text-sm font-bold text-white shadow-sm transition-all cursor-pointer"
                        >
                            <span>Buka Portal Subjek Teknik</span>
                            <i class="fa-solid fa-arrow-up-right-from-square text-xs text-[#f59e0b]"></i>
                        </a>
                    </div>
                </div>

                {{-- 3. Subjek Sosial --}}
                <div class="group relative flex flex-col justify-between overflow-hidden rounded-[24px] border border-slate-200/90 bg-gradient-to-br from-white via-slate-50/80 to-blue-50/50 p-6 sm:p-7 shadow-[0_8px_30px_rgba(15,23,42,0.04)] transition-all duration-300 hover:shadow-md hover:border-primary/40">
                    <div class="pointer-events-none absolute -top-10 -right-10 size-32 rounded-full bg-blue-100/40 blur-xl" aria-hidden="true"></div>
                    <div class="pointer-events-none absolute -bottom-10 -left-10 size-32 rounded-full bg-amber-100/30 blur-xl" aria-hidden="true"></div>

                    <div class="relative z-10">
                        <div class="flex items-center justify-between gap-3">
                            <span class="flex size-12 items-center justify-center rounded-2xl bg-white p-2.5 shadow-xs border border-slate-100 text-[#7c3aed]">
                                <i class="fa-solid fa-users text-lg text-[#7c3aed]"></i>
                            </span>
                            <span class="rounded-full bg-[#7c3aed]/10 px-3 py-1 text-[11px] font-bold text-[#7c3aed]">
                                Gale Social Studies
                            </span>
                        </div>

                        <h3 class="mt-4 text-lg font-bold text-[#0b1f3a] group-hover:text-primary transition-colors">
                            Subjek Sosial
                        </h3>
                        <p class="mt-1 text-xs text-slate-500 leading-relaxed">
                            Sains komunikasi, media massa, sosiologi, psikologi, dan hubungan masyarakat.
                        </p>

                        {{-- Kotak Password --}}
                        <div class="mt-4 rounded-xl border border-slate-200/80 bg-white/95 p-3.5 shadow-2xs backdrop-blur-xs">
                            <div class="flex items-center justify-between text-xs text-slate-500 mb-1.5">
                                <span class="font-medium flex items-center gap-1.5">
                                    <i class="fa-solid fa-lock text-[#f59e0b] text-[11px]"></i>
                                    Password Akses:
                                </span>
                                <span class="text-[10px] text-slate-400">Klik tombol salin</span>
                            </div>
                            <div class="flex items-center justify-between gap-2">
                                <code class="font-mono text-sm sm:text-base font-extrabold text-[#0b1f3a] tracking-wider select-all bg-slate-50 px-2.5 py-1 rounded-lg border border-slate-200">
                                    journey
                                </code>
                                <button
                                    type="button"
                                    onclick="copyPassword('journey', this)"
                                    class="inline-flex items-center gap-1.5 rounded-lg bg-[#0b2a5b]/10 hover:bg-[#0b2a5b] text-[#0b2a5b] hover:text-white px-3 py-1.5 text-xs font-semibold transition-all cursor-pointer"
                                    title="Salin password ke clipboard"
                                >
                                    <i class="fa-regular fa-copy text-xs"></i>
                                    <span>Salin</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="relative z-10 mt-6 pt-4 border-t border-slate-200/70">
                        <a
                            href="https://link.gale.com/apps/PPBE?u=fjktbus"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="btn-press inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#0b2a5b] hover:bg-primary-dark px-4 py-3 text-xs sm:text-sm font-bold text-white shadow-sm transition-all cursor-pointer"
                        >
                            <span>Buka Portal Subjek Sosial</span>
                            <i class="fa-solid fa-arrow-up-right-from-square text-xs text-[#f59e0b]"></i>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        {{-- Guide Banner --}}
        <div class="rounded-2xl border border-slate-200/90 bg-gradient-to-br from-white via-slate-50/70 to-blue-50/30 p-5 lg:p-6 shadow-xs">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div class="flex items-start gap-3.5">
                    <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-[#2563EB] text-white">
                        <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" x2="12" y1="8" y2="12"/>
                            <line x1="12" x2="12.01" y1="16" y2="16"/>
                        </svg>
                    </span>
                    <div>
                        <h2 class="text-sm font-semibold text-ink sm:text-base">Panduan Akses Jurnal dari Luar Kampus (Remote Access)</h2>
                        <p class="mt-1 text-xs text-ink-secondary leading-relaxed sm:text-sm">
                            Untuk database berbayar nasional seperti Perpusnas e-Resources, gunakan nomor anggota Perpustakaan Nasional Anda. Untuk bantuan aktivasi akun riset, hubungi meja layanan perpustakaan.
                        </p>
                    </div>
                </div>
                <a
                    href="{{ route('chat') }}?q=Bagaimana+akses+e-journal+dari+rumah"
                    class="inline-flex shrink-0 items-center gap-1.5 rounded-xl border border-line bg-white px-4 py-2.5 text-xs font-semibold text-primary shadow-sm transition-colors hover:border-primary/40 hover:bg-white"
                >
                    Tanya Akses Remote
                </a>
            </div>
        </div>
    </div>

    <script>
        function copyPassword(text, btn) {
            if (!navigator.clipboard) {
                const textArea = document.createElement('textarea');
                textArea.value = text;
                textArea.style.position = 'fixed';
                textArea.style.opacity = '0';
                document.body.appendChild(textArea);
                textArea.focus();
                textArea.select();
                try {
                    document.execCommand('copy');
                } catch (err) {
                    console.error('Copy fallback failed: ', err);
                }
                document.body.removeChild(textArea);
            } else {
                navigator.clipboard.writeText(text).catch(err => {
                    console.error('Clipboard copy failed: ', err);
                });
            }

            const originalContent = btn.innerHTML;
            btn.innerHTML = '<i class="fa-solid fa-check text-xs text-emerald-600"></i><span class="text-emerald-700 font-bold">Tersalin!</span>';
            btn.classList.add('bg-emerald-100', 'text-emerald-800');

            setTimeout(() => {
                btn.innerHTML = originalContent;
                btn.classList.remove('bg-emerald-100', 'text-emerald-800');
            }, 2000);
        }
    </script>
@endsection
