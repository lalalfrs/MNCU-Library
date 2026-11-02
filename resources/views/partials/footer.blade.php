{{-- Footer Perpustakaan MNC University (spesifikasi-desain-ui.md §11 & §13) --}}
<footer class="mt-8 border-t border-line bg-white py-5 text-center text-[13px] text-muted lg:text-base print:hidden">
    <div class="mx-auto flex w-full max-w-[1570px] flex-col items-center justify-between gap-2.5 px-3 sm:px-4 md:flex-row lg:px-6 xl:px-8 responsive-desktop-container">
        <p class="text-[13px] text-ink-secondary lg:text-sm">
            &copy; <span id="footer-year">{{ date('Y') }}</span> Perpustakaan MNC University. Hak Cipta Dilindungi.
        </p>

        <div class="flex flex-wrap items-center justify-center gap-2 sm:gap-4 text-[13px] lg:text-sm" aria-label="Kontak dan Media Sosial Perpustakaan">
            <a
                href="https://instagram.com/perpustakaan.mncu"
                target="_blank"
                rel="noopener noreferrer"
                class="inline-flex min-h-[40px] items-center gap-2 rounded-lg px-2.5 font-medium text-ink-secondary transition-colors hover:bg-subtle hover:text-primary"
                aria-label="Instagram Perpustakaan MNC University @perpustakaan.mncu"
            >
                <i class="fa-brands fa-instagram text-sm text-primary" aria-hidden="true"></i>
                <span>@perpustakaan.mncu</span>
            </a>

            <span class="hidden text-line sm:inline" aria-hidden="true">|</span>

            <a
                href="mailto:perpustakaan@mncu.ac.id"
                class="inline-flex min-h-[40px] items-center gap-2 rounded-lg px-2.5 font-medium text-ink-secondary transition-colors hover:bg-subtle hover:text-primary"
                aria-label="Kirim email ke perpustakaan@mncu.ac.id"
            >
                <i class="fa-regular fa-envelope text-sm text-primary" aria-hidden="true"></i>
                <span>perpustakaan@mncu.ac.id</span>
            </a>
        </div>
    </div>

    <script>
        (function () {
            var el = document.getElementById('footer-year');
            if (el) el.textContent = new Date().getFullYear();
        })();
    </script>
</footer>
