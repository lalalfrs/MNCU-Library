{{-- Footer Perpustakaan MNC University --}}
<footer class="mt-12 sm:mt-16 border-t border-line bg-white text-ink-secondary">
    {{-- Main Footer Content --}}
    <div class="mx-auto max-w-[1570px] px-4 sm:px-6 lg:px-8 py-10 sm:py-12 lg:py-14">
        <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4 lg:gap-10">
            {{-- Kolom 1: Profil & Jam Layanan --}}
            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary">
                        <i class="fa-solid fa-book-open text-base" aria-hidden="true"></i>
                    </span>
                    <div>
                        <h3 class="text-base font-bold text-primary leading-tight">Perpustakaan</h3>
                        <p class="text-xs font-semibold text-ink">MNC University</p>
                    </div>
                </div>
                <p class="text-xs sm:text-sm leading-relaxed text-muted">
                    Pusat sumber belajar, referensi riset, dan literasi akademik sivitas akademika MNC University. Menyediakan koleksi fisik dan digital terpercaya.
                </p>
                <div class="rounded-xl border border-line bg-subtle p-3.5 text-xs text-muted space-y-2">
                    <div class="flex items-center gap-2 font-semibold text-ink">
                        <i class="fa-regular fa-clock text-primary" aria-hidden="true"></i>
                        <span>Jam Operasional Layanan</span>
                    </div>
                    <div class="text-[11px] sm:text-xs space-y-1">
                        <div class="flex justify-between items-center text-muted">
                            <span>Senin – Jumat:</span>
                            <span class="font-medium text-ink">08:00 – 17:00 WIB</span>
                        </div>
                        <div class="flex justify-between items-center text-muted">
                            <span>Sabtu:</span>
                            <span class="font-medium text-ink">09:00 – 14:00 WIB</span>
                        </div>
                        <p class="text-[10px] text-muted/80 pt-0.5">Hari Minggu &amp; Libur Nasional Tutup</p>
                    </div>
                </div>
            </div>

            {{-- Kolom 2: Layanan & Fasilitas --}}
            <div class="space-y-3.5 sm:space-y-4">
                <h3 class="text-xs sm:text-sm font-bold uppercase tracking-wider text-ink">
                    Layanan Utama
                </h3>
                <ul class="space-y-2.5 text-xs sm:text-sm">
                    <li>
                        <a href="{{ route('opac') }}" class="group flex items-center gap-2 text-muted transition-colors hover:text-primary">
                            <i class="fa-solid fa-chevron-right text-[10px] text-muted transition-transform group-hover:translate-x-0.5 group-hover:text-primary" aria-hidden="true"></i>
                            <span>Katalog Buku (OPAC)</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('beranda') }}#buku-tamu" class="group flex items-center gap-2 text-muted transition-colors hover:text-primary">
                            <i class="fa-solid fa-chevron-right text-[10px] text-muted transition-transform group-hover:translate-x-0.5 group-hover:text-primary" aria-hidden="true"></i>
                            <span>Buku Tamu / Absensi</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('repository') }}" class="group flex items-center gap-2 text-muted transition-colors hover:text-primary">
                            <i class="fa-solid fa-chevron-right text-[10px] text-muted transition-transform group-hover:translate-x-0.5 group-hover:text-primary" aria-hidden="true"></i>
                            <span>Repository Skripsi &amp; Riset</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('ejournal') }}" class="group flex items-center gap-2 text-muted transition-colors hover:text-primary">
                            <i class="fa-solid fa-chevron-right text-[10px] text-muted transition-transform group-hover:translate-x-0.5 group-hover:text-primary" aria-hidden="true"></i>
                            <span>E-Journal Terakreditasi</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('ojs') }}" class="group flex items-center gap-2 text-muted transition-colors hover:text-primary">
                            <i class="fa-solid fa-chevron-right text-[10px] text-muted transition-transform group-hover:translate-x-0.5 group-hover:text-primary" aria-hidden="true"></i>
                            <span>Portal OJS per Prodi</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('ebook') }}" class="group flex items-center gap-2 text-muted transition-colors hover:text-primary">
                            <i class="fa-solid fa-chevron-right text-[10px] text-muted transition-transform group-hover:translate-x-0.5 group-hover:text-primary" aria-hidden="true"></i>
                            <span>Koleksi E-Book Digital</span>
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Kolom 3: Kontak & Alamat --}}
            <div class="space-y-3.5 sm:space-y-4">
                <h3 class="text-xs sm:text-sm font-bold uppercase tracking-wider text-ink">
                    Kontak Perpustakaan
                </h3>
                <ul class="space-y-3 text-xs sm:text-sm">
                    <li class="flex items-start gap-2.5">
                        <span class="flex size-7 shrink-0 items-center justify-center rounded-lg bg-primary/10 text-primary mt-0.5">
                            <i class="fa-solid fa-location-dot text-xs" aria-hidden="true"></i>
                        </span>
                        <span class="leading-relaxed text-muted">
                            Gedung Perpustakaan MNC University, Jl. Haji Ten No. 34, RT.7/RW.2, Kebon Sirih, Menteng, Jakarta Pusat, DKI Jakarta 10340
                        </span>
                    </li>
                    <li class="flex items-center gap-2.5">
                        <span class="flex size-7 shrink-0 items-center justify-center rounded-lg bg-primary/10 text-primary">
                            <i class="fa-solid fa-phone text-xs" aria-hidden="true"></i>
                        </span>
                        <a href="tel:+622139836888" class="text-muted transition-colors hover:text-primary">
                            (021) 3983-6888
                        </a>
                    </li>
                    <li class="flex items-center gap-2.5">
                        <span class="flex size-7 shrink-0 items-center justify-center rounded-lg bg-primary/10 text-primary">
                            <i class="fa-solid fa-envelope text-xs" aria-hidden="true"></i>
                        </span>
                        <a href="mailto:info@perpustakaan-kampus.ac.id" class="text-muted transition-colors hover:text-primary break-all">
                            info@perpustakaan-kampus.ac.id
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Kolom 4: Media Sosial & Navigasi Cepat --}}
            <div class="space-y-3.5 sm:space-y-4">
                <h3 class="text-xs sm:text-sm font-bold uppercase tracking-wider text-ink">
                    Media Sosial &amp; Bantuan
                </h3>
                <p class="text-xs sm:text-sm text-muted">
                    Ikuti akun resmi kami untuk mendapatkan pengumuman webinar, workshop, dan pembaruan koleksi.
                </p>

                {{-- Social Media Links --}}
                <div class="flex flex-col gap-2.5">
                    <a
                        href="https://instagram.com/perpus_official"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="btn-press inline-flex items-center gap-2.5 rounded-xl border border-line bg-white px-3.5 py-2.5 text-xs sm:text-sm font-medium text-ink transition-all hover:border-pink-300 hover:bg-pink-50/50 hover:text-pink-600"
                    >
                        <i class="fa-brands fa-instagram text-base text-pink-600" aria-hidden="true"></i>
                        <span>@perpus_official</span>
                        <span class="sr-only">(buka di tab baru)</span>
                    </a>

                    <a
                        href="mailto:info@perpustakaan-kampus.ac.id"
                        class="btn-press inline-flex items-center gap-2.5 rounded-xl border border-line bg-white px-3.5 py-2.5 text-xs sm:text-sm font-medium text-ink transition-all hover:border-primary/40 hover:bg-primary/5 hover:text-primary"
                    >
                        <i class="fa-solid fa-envelope text-base text-primary" aria-hidden="true"></i>
                        <span>Kirim Email Pertanyaan</span>
                    </a>
                </div>

                {{-- Fast links --}}
                <div class="flex flex-wrap gap-x-3 gap-y-1.5 text-xs text-muted pt-1">
                    <a href="{{ route('tentang-kami') }}" class="hover:text-primary hover:underline">Tentang Kami</a>
                    <span aria-hidden="true">&bull;</span>
                    <a href="{{ route('kontak') }}" class="hover:text-primary hover:underline">Kontak</a>
                    <span aria-hidden="true">&bull;</span>
                    <a href="{{ route('pustakawan') }}" class="hover:text-primary hover:underline">Pustakawan</a>
                    <span aria-hidden="true">&bull;</span>
                    <a href="{{ route('chat') }}" class="hover:text-primary hover:underline">Chatbot</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Bottom Copyright Bar --}}
    <div class="border-t border-line/80 bg-subtle py-4 sm:py-5">
        <div class="mx-auto flex max-w-[1570px] flex-col sm:flex-row items-center justify-between gap-2.5 px-4 sm:px-6 lg:px-8 text-center sm:text-left text-xs sm:text-[13px] text-muted">
            <p>
                &copy; <span id="footer-year">{{ date('Y') }}</span> Perpustakaan MNC University. Hak Cipta Dilindungi.
            </p>
            <p class="flex items-center gap-1.5 text-muted/80">
                <i class="fa-solid fa-shield-halved text-[11px] text-primary" aria-hidden="true"></i>
                <span>Sistem Informasi Perpustakaan &amp; Arsip Digital</span>
            </p>
        </div>
    </div>

    <script>
        (function () {
            var el = document.getElementById('footer-year');
            if (el) el.textContent = new Date().getFullYear();
        })();
    </script>
</footer>
