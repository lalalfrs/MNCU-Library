{{-- Drawer menu (spesifikasi §5). Dibuka dari tombol hamburger di navbar (desktop & mobile). --}}
<div id="sidebar-backdrop" class="pointer-events-none fixed inset-0 z-40 bg-overlay opacity-0 transition-opacity duration-200" aria-hidden="true"></div>

<aside
    id="sidebar"
    role="dialog"
    aria-modal="true"
    aria-label="Menu Navigasi & Panel Manajemen"
    aria-hidden="true"
    inert
    class="fixed inset-y-0 left-0 z-50 flex h-dvh w-[88%] max-w-[340px] -translate-x-full flex-col bg-white shadow-drawer transition-transform duration-250 ease-out motion-reduce:transition-none md:w-[380px] lg:w-[400px]"
    style="transform: translateX(-100%);"
>
    {{-- Header Sidebar --}}
    <div class="relative px-6 pt-5 pb-5 text-center border-b border-line">
        <button
            type="button"
            id="sidebar-close"
            class="absolute top-3 right-3 inline-flex size-10 items-center justify-center rounded-xl text-muted hover:bg-[#F3F4F6] transition-colors btn-press"
            aria-label="Tutup menu"
        >
            <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                <path d="M6 6l12 12M18 6L6 18" />
            </svg>
        </button>

        <a href="{{ url('/') }}" class="inline-block mt-2">
            <img src="{{ asset('images/Logo.png') }}" alt="Logo MNC Library" class="mx-auto h-12 max-w-[120px] w-auto object-contain md:h-14" style="max-height: 56px; max-width: 120px;" width="120" height="56">
            <p class="mt-2 text-base font-bold text-primary md:text-lg">Perpustakaan MNC University</p>
            <p class="text-[11px] text-muted">Portal Sivitas Akademika</p>
        </a>

        @auth
            {{-- Kartu Info Pengguna Login --}}
            <div class="mt-4 rounded-xl bg-primary/5 p-3 text-left border border-primary/10">
                <div class="flex items-center gap-3">
                    <div class="flex size-10 shrink-0 items-center justify-center rounded-full bg-primary font-bold text-white shadow-sm">
                        {{ strtoupper(substr(Auth::user()->nama_lengkap, 0, 1)) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-xs font-bold text-ink">{{ Auth::user()->nama_lengkap }}</p>
                        <p class="truncate text-[11px] text-muted">{{ Auth::user()->email }}</p>
                        <span class="inline-block mt-0.5 rounded bg-primary/15 px-2 py-0.5 text-[10px] font-semibold text-primary">
                            {{ Auth::user()->peran?->nama_peran }}
                        </span>
                    </div>
                </div>
            </div>
        @endauth
    </div>

    {{-- Daftar Menu Navigasi & Manajemen CRUD --}}
    <nav aria-label="Menu samping" class="flex-1 overflow-y-auto py-4">
        <ul class="flex flex-col space-y-1">
            {{-- 1. Beranda --}}
            <li>
                <a href="{{ url('/') }}" class="flex h-[44px] items-center gap-3.5 border-l-[3px] {{ request()->is('/') ? 'border-primary bg-[#EEF2FF] text-primary font-semibold' : 'border-transparent text-ink hover:bg-[#F3F4F6]' }} px-6 text-sm transition-colors">
                    <i class="fa-solid fa-house text-primary w-5 text-center"></i>
                    <span>Beranda Utama</span>
                </a>
            </li>

            {{-- 2. Menu Manajemen CRUD (Khusus Super Admin) --}}
            @if (Auth::check() && Auth::user()->isSuperAdmin())
                <li class="pt-3 pb-1 px-6">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-muted">
                        Panel Manajemen (CRUD)
                    </span>
                </li>

                {{-- Manajemen User --}}
                <li>
                    <a
                        href="{{ route('admin.users.index') }}"
                        class="flex h-[44px] items-center gap-3.5 border-l-[3px] {{ request()->routeIs('admin.users.*') ? 'border-[#7C3AED] bg-[#7C3AED]/10 text-[#7C3AED] font-semibold' : 'border-transparent text-ink hover:bg-[#F3F4F6] hover:text-[#7C3AED]' }} px-6 text-sm transition-colors"
                    >
                        <i class="fa-solid fa-users-gear text-[#7C3AED] w-5 text-center"></i>
                        <span class="flex-1">Manajemen User</span>
                        <span class="rounded bg-[#7C3AED]/15 px-1.5 py-0.5 text-[10px] font-semibold text-[#7C3AED]">CRUD</span>
                    </a>
                </li>

                {{-- Manajemen Buku --}}
                <li>
                    <a
                        href="{{ route('admin.books.index') }}"
                        class="flex h-[44px] items-center gap-3.5 border-l-[3px] {{ request()->routeIs('admin.books.*') ? 'border-[#2563EB] bg-[#2563EB]/10 text-[#2563EB] font-semibold' : 'border-transparent text-ink hover:bg-[#F3F4F6] hover:text-[#2563EB]' }} px-6 text-sm transition-colors"
                    >
                        <i class="fa-solid fa-book-bookmark text-[#2563EB] w-5 text-center"></i>
                        <span class="flex-1">Manajemen Buku</span>
                        <span class="rounded bg-[#2563EB]/15 px-1.5 py-0.5 text-[10px] font-semibold text-[#2563EB]">CRUD</span>
                    </a>
                </li>

                {{-- Manajemen Acara --}}
                <li>
                    <a
                        href="{{ route('admin.acara.index') }}"
                        class="flex h-[44px] items-center gap-3.5 border-l-[3px] {{ request()->routeIs('admin.acara.*') ? 'border-[#EA580C] bg-[#EA580C]/10 text-[#EA580C] font-semibold' : 'border-transparent text-ink hover:bg-[#F3F4F6] hover:text-[#EA580C]' }} px-6 text-sm transition-colors"
                    >
                        <i class="fa-solid fa-calendar-days text-[#EA580C] w-5 text-center"></i>
                        <span class="flex-1">Manajemen Acara</span>
                        <span class="rounded bg-[#EA580C]/15 px-1.5 py-0.5 text-[10px] font-semibold text-[#EA580C]">CRUD</span>
                    </a>
                </li>

                {{-- Manajemen Berita --}}
                <li>
                    <a
                        href="{{ route('admin.berita.index') }}"
                        class="flex h-[44px] items-center gap-3.5 border-l-[3px] {{ request()->routeIs('admin.berita.*') ? 'border-[#059669] bg-[#059669]/10 text-[#059669] font-semibold' : 'border-transparent text-ink hover:bg-[#F3F4F6] hover:text-[#059669]' }} px-6 text-sm transition-colors"
                    >
                        <i class="fa-solid fa-newspaper text-[#059669] w-5 text-center"></i>
                        <span class="flex-1">Manajemen Berita</span>
                        <span class="rounded bg-[#059669]/15 px-1.5 py-0.5 text-[10px] font-semibold text-[#059669]">CRUD</span>
                    </a>
                </li>
            @endif

            {{-- 3. Layanan Perpustakaan --}}
            <li class="pt-3 pb-1 px-6">
                <span class="text-[10px] font-bold uppercase tracking-wider text-muted">
                    Layanan Perpustakaan
                </span>
            </li>

            <li>
                <details class="group" {{ request()->routeIs('opac', 'repository', 'ejournal', 'ojs', 'ebook') ? 'open' : '' }}>
                    <summary class="flex h-[44px] cursor-pointer list-none items-center gap-3.5 px-6 text-sm text-ink hover:bg-[#F3F4F6] transition-colors [&::-webkit-details-marker]:hidden">
                        <i class="fa-solid fa-book-open-reader text-primary w-5 text-center"></i>
                        <span class="flex-1">Koleksi & Sirkulasi</span>
                        <svg class="size-4 text-muted transition-transform duration-200 group-open:rotate-180" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="m6 9 6 6 6-6" />
                        </svg>
                    </summary>
                    <ul class="space-y-0.5 pb-1">
                        @php
                            $sidebarServices = [
                                ['label' => 'Katalog OPAC', 'href' => route('opac'), 'faIcon' => 'fa-solid fa-magnifying-glass text-primary'],
                                ['label' => 'Repository Skripsi', 'href' => route('repository'), 'faIcon' => 'fa-solid fa-graduation-cap text-[#7C3AED]'],
                                ['label' => 'E-Journal Digital', 'href' => route('ejournal'), 'faIcon' => 'fa-solid fa-newspaper text-[#2563EB]'],
                                ['label' => 'OJS per Prodi', 'href' => route('ojs'), 'faIcon' => 'fa-solid fa-layer-group text-[#047857]'],
                                ['label' => 'E-Book Perpustakaan', 'href' => route('ebook'), 'faIcon' => 'fa-solid fa-tablet-screen-button text-[#E11D48]'],
                            ];
                        @endphp
                        @foreach ($sidebarServices as $service)
                            <li>
                                <a href="{{ $service['href'] }}" class="flex h-9 items-center gap-3 pl-14 pr-6 text-xs text-ink-secondary hover:text-primary hover:bg-[#F8FAFC] transition-colors">
                                    <i class="{{ $service['faIcon'] }} w-4 text-center"></i>
                                    <span>{{ $service['label'] }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </details>
            </li>

            {{-- 4. Informasi & Bantuan --}}
            <li class="pt-3 pb-1 px-6">
                <span class="text-[10px] font-bold uppercase tracking-wider text-muted">
                    Informasi & Bantuan
                </span>
            </li>

            @foreach ($navigationItems as $navItem)
                <li>
                    <a href="{{ $navItem['href'] }}" class="flex h-[40px] items-center gap-3.5 px-6 text-xs text-ink hover:bg-[#F3F4F6] hover:text-primary transition-colors">
                        <i class="fa-regular fa-circle-dot text-muted w-5 text-center text-[10px]"></i>
                        <span>{{ $navItem['label'] }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </nav>

    {{-- Footer Bawah: Tombol Login / Logout --}}
    <div class="border-t border-line p-4">
        @guest
            <div class="flex flex-col gap-2">
                <a href="{{ route('register') }}" class="flex h-10 w-full items-center justify-center gap-2 rounded-xl border border-line bg-white text-xs font-semibold text-ink shadow-sm transition-colors hover:bg-subtle">
                    <i class="fa-solid fa-user-plus text-xs text-primary"></i>
                    <span>Daftar Akun Baru</span>
                </a>
                <a href="{{ route('login') }}" class="flex h-10 w-full items-center justify-center gap-2 rounded-xl bg-primary text-xs font-semibold text-white shadow-sm transition-colors hover:bg-primary-dark">
                    <i class="fa-solid fa-arrow-right-to-bracket text-xs"></i>
                    <span>Masuk ke Akun</span>
                </a>
            </div>
        @else
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="flex h-10 w-full items-center justify-center gap-2 rounded-xl border border-red-200 bg-red-50 text-xs font-semibold text-[#E11D48] hover:bg-red-100 transition-colors">
                    <i class="fa-solid fa-arrow-right-from-bracket text-xs"></i>
                    <span>Keluar dari Akun</span>
                </button>
            </form>
        @endguest
    </div>
</aside>
