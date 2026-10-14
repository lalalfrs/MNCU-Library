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
    <body class="flex min-h-screen flex-col bg-white font-sans text-ink antialiased">
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
