<header class="sticky top-3 z-40 mx-3 mt-3 lg:top-4 lg:mx-6 lg:mt-4">
    <nav aria-label="Navigasi utama" class="mx-auto flex h-14 max-w-[1570px] items-center justify-between gap-4 rounded-2xl bg-white px-4 shadow-raised lg:h-[70px] lg:px-7">
        <div class="flex min-w-0 items-center gap-2 lg:gap-3">
            <button
                type="button"
                id="sidebar-open"
                class="-ml-2 inline-flex size-11 shrink-0 items-center justify-center rounded-xl text-primary hover:bg-[#F3F4F6] lg:hidden"
                aria-label="Buka menu"
                aria-expanded="false"
                aria-controls="sidebar"
            >
                <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                    <path d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <a href="{{ url('/') }}" class="flex min-w-0 items-center gap-2.5 rounded-lg lg:gap-3">
                <img src="{{ asset('images/Logo.png') }}" alt="Logo MNC Library" class="h-8 w-auto shrink-0 lg:h-9">
                <span class="truncate text-base font-medium text-primary lg:hidden">MNC Library</span>
                <span class="hidden truncate text-[22px] font-medium text-primary lg:inline">Perpustakaan MNC University</span>
            </a>
        </div>

        <div class="flex items-center gap-2 lg:gap-8">
            <ul class="hidden items-center gap-1 lg:flex">
                @foreach ($navigationItems as $navigationItem)
                    <li>
                        <a href="{{ $navigationItem['href'] }}" class="rounded-lg px-3 py-2 text-[15px] text-ink-secondary transition-colors duration-150 hover:bg-[#F3F4F6] hover:text-primary">
                            {{ $navigationItem['label'] }}
                        </a>
                    </li>
                @endforeach
            </ul>

            {{-- TODO (integrasi): arahkan ke route login. --}}
            <a href="#" class="inline-flex h-11 items-center rounded-xl bg-primary px-4 text-sm font-semibold text-white transition-colors duration-150 hover:bg-primary-dark lg:h-12 lg:px-5 lg:text-base">
                Login
            </a>
        </div>
    </nav>
</header>
