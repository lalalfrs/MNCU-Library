<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title', 'MNC Library') | Perpustakaan MNC University</title>
        <meta name="description" content="@yield('description', 'Perpustakaan MNC University: katalog OPAC, repository, e-journal, OJS per prodi, dan e-book.')">

        <link rel="icon" type="image/png" href="{{ asset('images/Logo.png') }}">

        @fonts
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="flex min-h-screen flex-col bg-white font-sans text-ink antialiased overflow-x-hidden">
        {{-- Loading Screen & Top Progress Bar --}}
        @include('partials.loading-screen')

        @php
            /**
             * Menu navbar sesuai flowchart Beranda.
             * TODO (integrasi): ganti 'href' dengan route() setelah halaman tujuan dibuat.
             *
             * @var array<int, array{label: string, href: string}> $navigationItems
             */
            $navigationItems = [
                ['label' => 'Tentang Kami', 'href' => route('tentang-kami')],
                ['label' => 'Kontak', 'href' => route('kontak')],
                ['label' => 'Pustakawan', 'href' => route('pustakawan')],
                ['label' => 'Chatbot / Bantuan', 'href' => route('chat')],
            ];
        @endphp

        @include('partials.navbar')
        @include('partials.sidebar')

        <main id="konten" class="flex w-full min-w-0 max-w-full flex-1 flex-col page-transition-wrapper">
            @php
                $databaseAlert = session('database_error') ?? ($dbErrorMessage ?? null);
                if (! $databaseAlert && request()->query('db_error')) {
                    $databaseAlert = 'Database belum terhubung. Pastikan layanan database (MySQL / XAMPP) sudah dijalankan.';
                }
            @endphp

            @if ($databaseAlert)
                <div id="database-alert-banner" class="mx-auto mt-4 w-full max-w-[1570px] px-3 lg:px-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3.5 rounded-2xl border border-red-200 bg-red-50/95 p-4 text-xs text-red-900 shadow-sm sm:text-sm">
                        <div class="flex items-start sm:items-center gap-3">
                            <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-red-100 text-red-600">
                                <i class="fa-solid fa-triangle-exclamation text-base" aria-hidden="true"></i>
                            </span>
                            <div>
                                <h3 class="font-bold text-red-900 text-sm sm:text-base">Koneksi Database Belum Terhubung</h3>
                                <p class="text-red-700 text-xs sm:text-sm leading-relaxed mt-0.5">
                                    {{ $databaseAlert }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 self-end sm:self-center shrink-0">
                            <a href="{{ route('beranda') }}" class="btn-press inline-flex items-center gap-1.5 rounded-xl bg-red-600 px-4 py-2 text-xs font-semibold text-white shadow-xs hover:bg-red-700 transition-colors">
                                <i class="fa-solid fa-rotate-right text-xs"></i>
                                <span>Coba Lagi</span>
                            </a>
                            <button type="button" onclick="document.getElementById('database-alert-banner').remove()" class="inline-flex size-8 items-center justify-center rounded-lg text-red-500 hover:bg-red-100 hover:text-red-800 transition-colors" title="Tutup pemberitahuan" aria-label="Tutup pemberitahuan">
                                <i class="fa-solid fa-xmark text-sm"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            @if (session('success'))
                <div class="mx-auto mt-4 w-full max-w-[1570px] px-3 lg:px-6">
                    <div class="flex items-center justify-between rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-xs font-medium text-emerald-800 shadow-sm sm:text-sm">
                        <div class="flex items-center gap-2.5">
                            <i class="fa-solid fa-circle-check text-emerald-600"></i>
                            <span>{{ session('success') }}</span>
                        </div>
                        <button type="button" onclick="this.parentElement.parentElement.remove()" class="text-emerald-700 hover:text-emerald-900" aria-label="Tutup pemberitahuan">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                </div>
            @endif

            @if (session('info'))
                <div class="mx-auto mt-4 w-full max-w-[1570px] px-3 lg:px-6">
                    <div class="flex items-center justify-between rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 text-xs font-medium text-blue-800 shadow-sm sm:text-sm">
                        <div class="flex items-center gap-2.5">
                            <i class="fa-solid fa-circle-info text-blue-600"></i>
                            <span>{{ session('info') }}</span>
                        </div>
                        <button type="button" onclick="this.parentElement.parentElement.remove()" class="text-blue-700 hover:text-blue-900" aria-label="Tutup pemberitahuan">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>

        @include('partials.footer')

        {{-- Buka/tutup drawer (spesifikasi §5.6). Hanya perilaku tampilan, boleh diganti tim saat integrasi. --}}
        <script>
            (() => {
                const sidebar = document.getElementById('sidebar');
                const backdrop = document.getElementById('sidebar-backdrop');
                const openButton = document.getElementById('sidebar-open');
                const closeButton = document.getElementById('sidebar-close');

                const setOpen = (isOpen) => {
                    sidebar.classList.toggle('-translate-x-full', !isOpen);
                    sidebar.style.transform = isOpen ? 'translateX(0)' : 'translateX(-100%)';
                    sidebar.setAttribute('aria-hidden', String(!isOpen));
                    sidebar.inert = !isOpen;
                    backdrop.classList.toggle('opacity-0', !isOpen);
                    backdrop.classList.toggle('pointer-events-none', !isOpen);
                    openButton.setAttribute('aria-expanded', String(isOpen));
                    document.body.classList.toggle('overflow-hidden', isOpen);
                    (isOpen ? closeButton : openButton).focus();
                };

                openButton.addEventListener('click', () => setOpen(true));
                closeButton.addEventListener('click', () => setOpen(false));
                backdrop.addEventListener('click', () => setOpen(false));
                document.addEventListener('keydown', (event) => {
                    if (event.key === 'Escape' && openButton.getAttribute('aria-expanded') === 'true') {
                        setOpen(false);
                    }
                });
            })();
        </script>
    </body>
</html>
