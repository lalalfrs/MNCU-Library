/**
 * MNC University Library - Core Frontend & Micro-Animations
 * Mendukung: Top Progress Bar, Page Transitions, 3D Book Loading Screen,
 * Button Ripple & Hover Micro-Interactions, dan Form Double-Submit Protection.
 */

(() => {
    'use strict';

    // -------------------------------------------------------------------------
    // 1. Top Progress Bar & Loading Screen Handlers
    // -------------------------------------------------------------------------
    const progressBar = document.getElementById('mncu-progress-bar');
    const loadingScreen = document.getElementById('mncu-loading-screen');
    const mainContent = document.getElementById('konten') || document.querySelector('.page-transition-wrapper');

    let progressInterval = null;
    let progressValue = 0;

    /**
     * Memulai animasi progress bar di bagian atas viewport
     */
    const startProgressBar = () => {
        if (!progressBar) return;
        clearInterval(progressInterval);
        progressValue = 15;
        progressBar.style.opacity = '1';
        progressBar.style.width = `${progressValue}%`;

        progressInterval = setInterval(() => {
            if (progressValue < 85) {
                // Pertambahan dinamis yang melambat saat mendekati 85%
                const increment = Math.max(1, (85 - progressValue) * 0.12);
                progressValue += increment;
                progressBar.style.width = `${progressValue}%`;
            }
        }, 120);
    };

    /**
     * Menyelesaikan progress bar ke 100% lalu fade out
     */
    const completeProgressBar = () => {
        if (!progressBar) return;
        clearInterval(progressInterval);
        progressBar.style.width = '100%';

        setTimeout(() => {
            progressBar.style.opacity = '0';
            setTimeout(() => {
                progressBar.style.width = '0%';
            }, 250);
        }, 180);
    };

    /**
     * Menyembunyikan loading screen dengan transisi lembut
     */
    const hideLoadingScreen = () => {
        if (!loadingScreen) return;
        loadingScreen.classList.add('opacity-0', 'pointer-events-none');
        setTimeout(() => {
            loadingScreen.style.display = 'none';
        }, 320);
    };

    /**
     * Menampilkan loading screen jika navigasi membutuhkan waktu
     */
    const showLoadingScreen = () => {
        if (!loadingScreen) return;
        loadingScreen.style.display = 'flex';
        // Force reflow agar animasi transisi opacity berjalan
        void loadingScreen.offsetHeight;
        loadingScreen.classList.remove('opacity-0', 'pointer-events-none');
    };

    // Saat DOM siap: selesaikan progress bar & sembunyikan loading screen
    document.addEventListener('DOMContentLoaded', () => {
        startProgressBar();
        // Beri sedikit jeda halus agar perpindahan terasa nyaman
        setTimeout(() => {
            completeProgressBar();
            hideLoadingScreen();
        }, 120);
    });

    // Fail-safe jika asset lambat: maksimal 700ms loading screen tertutup
    setTimeout(() => {
        completeProgressBar();
        hideLoadingScreen();
    }, 700);

    // Tangani browser back/forward cache (bfcache)
    window.addEventListener('pageshow', (event) => {
        completeProgressBar();
        hideLoadingScreen();
        if (mainContent) {
            mainContent.classList.remove('page-exit-active');
        }
    });

    // -------------------------------------------------------------------------
    // 2. Smooth Page Transitions saat Mengklik Link Navigasi Internal
    // -------------------------------------------------------------------------
    document.addEventListener('click', (event) => {
        const link = event.target.closest('a');
        if (!link) return;

        const href = link.getAttribute('href');
        const target = link.getAttribute('target');

        // Lewatkan jika link kosong, anchor di halaman yang sama, javascript:, atau target baru
        if (!href || href.startsWith('#') || href.startsWith('javascript:') || href.startsWith('mailto:') || href.startsWith('tel:') || target === '_blank') {
            return;
        }

        // Lewatkan jika pengguna menahan Ctrl, Cmd, Shift, atau Alt (buka tab baru)
        if (event.ctrlKey || event.metaKey || event.shiftKey || event.altKey) {
            return;
        }

        // Pastikan URL internal (asal domain yang sama)
        try {
            const url = new URL(link.href, window.location.origin);
            if (url.origin !== window.location.origin) return;

            // Jangan transisi jika URL sama persis dengan halaman aktif
            if (url.pathname === window.location.pathname && url.search === window.location.search && url.hash) {
                return;
            }

            // Jalankan transisi keluar yang halus
            startProgressBar();

            if (mainContent) {
                mainContent.classList.add('page-exit-active');
            }

            // Jika respons memakan waktu lebih dari 160ms, tampilkan buku berbalik
            const slowTimer = setTimeout(() => {
                showLoadingScreen();
            }, 180);

            // Bersihkan timer jika window unload
            window.addEventListener('beforeunload', () => clearTimeout(slowTimer), { once: true });
        } catch {
            // URL parsing gagal, biarkan browser menangani secara default
        }
    });

    // -------------------------------------------------------------------------
    // 3. Efek Ripple Saat Tombol Diklik (Button Tactile Feedback)
    // -------------------------------------------------------------------------
    document.addEventListener('click', (e) => {
        const button = e.target.closest('.btn-press, button:not([disabled]), .btn-sheen');
        if (!button) return;

        // Jangan buat ripple jika tombol bertipe toggle atau icon kecil tertentu
        const rect = button.getBoundingClientRect();
        if (rect.width <= 0 || rect.height <= 0) return;

        const ripple = document.createElement('span');
        const diameter = Math.max(rect.width, rect.height);
        const radius = diameter / 2;

        ripple.style.width = ripple.style.height = `${diameter}px`;
        ripple.style.left = `${e.clientX - rect.left - radius}px`;
        ripple.style.top = `${e.clientY - rect.top - radius}px`;
        ripple.className = 'ripple-wave';

        // Sesuaikan kontras ripple jika latar belakang tombol terang
        const computedBg = window.getComputedStyle(button).backgroundColor;
        if (computedBg.includes('255, 255, 255') || button.classList.contains('bg-white') || button.classList.contains('bg-subtle')) {
            ripple.classList.add('ripple-wave-dark');
        }

        // Pastikan button memiliki positioning relatif
        if (window.getComputedStyle(button).position === 'static') {
            button.style.position = 'relative';
        }
        if (window.getComputedStyle(button).overflow !== 'hidden') {
            button.style.overflow = 'hidden';
        }

        const existingRipple = button.querySelector('.ripple-wave');
        if (existingRipple) {
            existingRipple.remove();
        }

        button.appendChild(ripple);
        setTimeout(() => {
            ripple.remove();
        }, 600);
    });

    // -------------------------------------------------------------------------
    // 4. Form Submit Protection & Button Loading Spinner
    // -------------------------------------------------------------------------
    document.addEventListener('submit', (e) => {
        const form = e.target;
        if (!form || form.getAttribute('data-no-loader') !== null) return;

        const submitBtn = form.querySelector('button[type="submit"]');
        if (!submitBtn || submitBtn.disabled) return;

        // Berikan feedback visual pada tombol submit
        startProgressBar();

        // Simpan label asli tombol
        if (!submitBtn.dataset.originalHtml) {
            submitBtn.dataset.originalHtml = submitBtn.innerHTML;
        }

        // Tampilkan spinner setelah form valid
        setTimeout(() => {
            if (!form.checkValidity || form.checkValidity()) {
                submitBtn.style.minWidth = `${submitBtn.offsetWidth}px`;
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-80', 'cursor-not-allowed');
                submitBtn.innerHTML = `
                    <svg class="mr-2 inline size-4 animate-spin text-current" viewBox="0 0 24 24" fill="none">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Memproses...</span>
                `;
            }
        }, 10);
    });
})();
