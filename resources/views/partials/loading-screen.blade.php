{{-- Top Progress Bar (meluncur di bagian atas viewport saat navigasi) --}}
<div id="mncu-progress-bar" class="fixed top-0 left-0 right-0 z-[10000] h-[3px] w-0 bg-gradient-to-r from-primary via-blue-500 to-indigo-500 shadow-[0_1px_8px_rgba(37,99,235,0.45)] transition-all duration-200 ease-out pointer-events-none" aria-hidden="true"></div>

{{-- Loading Screen Overlay Unik Bertema Perpustakaan MNC --}}
<div
    id="mncu-loading-screen"
    class="fixed inset-0 z-[9999] flex flex-col items-center justify-center bg-white/95 backdrop-blur-md transition-all duration-300 ease-out"
    role="status"
    aria-live="polite"
    aria-label="Memuat halaman perpustakaan"
>
    <div class="flex flex-col items-center justify-center p-6 text-center select-none">
        {{-- Animasi Buku 3D Lembaran Berbalik (Flipping Book) --}}
        <div class="mncu-book-wrapper mb-5" aria-hidden="true">
            <div class="mncu-book">
                {{-- Sampul Kiri & Halaman Kiri --}}
                <div class="mncu-book-page mncu-page-left">
                    <div class="mncu-page-lines">
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>

                {{-- Halaman Tengah yang Berbalik (Flipping Pages) --}}
                <div class="mncu-book-page mncu-page-turning mncu-page-turning-1"></div>
                <div class="mncu-book-page mncu-page-turning mncu-page-turning-2"></div>
                <div class="mncu-book-page mncu-page-turning mncu-page-turning-3"></div>

                {{-- Halaman Kanan & Sampul Kanan --}}
                <div class="mncu-book-page mncu-page-right">
                    <div class="mncu-page-lines">
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>

                {{-- Bayangan Buku --}}
                <div class="mncu-book-shadow"></div>
            </div>
        </div>

        {{-- Brand & Status Text --}}
        <div class="flex items-center gap-2.5">
            <img src="{{ asset('images/Logo.png') }}" alt="" class="h-6 w-auto opacity-90 animate-pulse">
            <span class="text-sm font-semibold tracking-wide text-primary">MNC Library</span>
        </div>

        <p class="mt-1.5 flex items-center gap-1 text-xs font-medium text-muted">
            <span>Menyiapkan halaman</span>
            <span class="inline-flex">
                <span class="animate-bounce" style="animation-delay: 0ms;">.</span>
                <span class="animate-bounce" style="animation-delay: 150ms;">.</span>
                <span class="animate-bounce" style="animation-delay: 300ms;">.</span>
            </span>
        </p>
    </div>
</div>
