<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title', 'MNC Library') | Perpustakaan MNC University</title>
        <meta name="description" content="@yield('description', 'Perpustakaan MNC University: katalog OPAC, repository, e-journal, OJS per prodi, dan e-book.')">

        <link rel="icon" type="image/png" href="{{ asset('images/Logo.png') }}">

        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="flex min-h-screen flex-col bg-white font-sans text-ink antialiased">
        @php
            /**
             * Menu navbar sesuai flowchart Beranda.
             * TODO (integrasi): ganti 'href' dengan route() setelah halaman tujuan dibuat.
             *
             * @var array<int, array{label: string, href: string}> $navigationItems
             */
            $navigationItems = [
                ['label' => 'Tentang Kami', 'href' => '#'],
                ['label' => 'Kontak', 'href' => '#'],
                ['label' => 'Pustakawan', 'href' => '#'],
                ['label' => 'Bantuan', 'href' => '#'],
            ];
        @endphp

        @include('partials.navbar')
        @include('partials.sidebar')

        <main id="konten" class="flex flex-1 flex-col">
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
