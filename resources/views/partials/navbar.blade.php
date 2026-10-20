<header class="sticky top-3 z-40 mx-3 mt-3 lg:top-4 lg:mx-6 lg:mt-4">
    <nav aria-label="Navigasi utama" class="mx-auto flex h-14 max-w-[1570px] items-center justify-between gap-4 rounded-2xl bg-white px-4 shadow-raised lg:h-[70px] lg:px-7">
        {{-- Kiri: Tombol Hamburger & Logo Perpustakaan --}}
        <div class="flex min-w-0 items-center gap-2 lg:gap-3">
            <button
                type="button"
                id="sidebar-open"
                class="-ml-2 inline-flex size-11 shrink-0 items-center justify-center rounded-xl text-primary hover:bg-[#F3F4F6] btn-press @guest lg:hidden @else flex @endguest"
                aria-label="Buka menu navigasi & panel manajemen"
                aria-expanded="false"
                aria-controls="sidebar"
                title="Buka Menu & Panel Manajemen"
            >
                <svg class="size-6 transition-transform duration-200 group-hover:scale-105" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                    <path d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <a href="{{ url('/') }}" class="flex min-w-0 items-center gap-2.5 rounded-lg transition-transform duration-150 hover:scale-[1.01] lg:gap-3">
                <img src="{{ asset('images/Logo.png') }}" alt="Logo MNC Library" class="h-8 w-auto shrink-0 lg:h-9">
                <span class="truncate text-base font-semibold text-primary lg:text-[20px] xl:text-[22px]">Perpustakaan MNC University</span>
            </a>
        </div>

        {{-- Tengah: Navigasi Menu (Hanya tampil untuk pengunjung yang belum login) --}}
        @guest
            <div class="hidden items-center gap-1 lg:flex">
                @foreach ($navigationItems as $navigationItem)
                    <a href="{{ $navigationItem['href'] }}" class="nav-link-animated rounded-lg px-3 py-2 text-[15px] text-ink-secondary transition-colors duration-150 hover:bg-[#F3F4F6] hover:text-primary">
                        {{ $navigationItem['label'] }}
                    </a>
                @endforeach
            </div>
        @endguest

        {{-- Kanan: Tombol Login & Daftar saat Guest / Profil & Dropdown saat Auth --}}
        <div class="flex items-center gap-2 lg:gap-3">
            @guest
                <a href="{{ route('register') }}" class="group inline-flex h-10 items-center gap-1.5 rounded-xl border border-line bg-white px-3.5 text-xs font-semibold text-ink shadow-sm transition-all duration-150 hover:bg-subtle hover:border-gray-300 btn-press lg:h-11 lg:px-4 lg:text-sm">
                    <i class="fa-solid fa-user-plus text-xs text-primary transition-transform duration-150 group-hover:scale-110"></i>
                    <span>Daftar</span>
                </a>
                <a href="{{ route('login') }}" class="group inline-flex h-10 items-center gap-2 rounded-xl bg-primary px-3.5 text-xs font-semibold text-white shadow-sm transition-all duration-150 hover:bg-primary-dark btn-press btn-sheen lg:h-11 lg:px-4 lg:text-sm">
                    <i class="fa-solid fa-arrow-right-to-bracket text-xs transition-transform duration-150 group-hover:translate-x-0.5"></i>
                    <span>Login</span>
                </a>
            @else
                <div class="relative">
                    <button
                        type="button"
                        id="user-menu-button"
                        class="flex items-center gap-2.5 rounded-xl p-1.5 transition-colors hover:bg-subtle focus:outline-none"
                        aria-expanded="false"
                        aria-haspopup="true"
                    >
                        <div class="hidden text-right lg:block">
                            <span class="block text-sm font-semibold text-primary leading-tight">{{ Auth::user()->nama_lengkap }}</span>
                            <span class="block text-xs text-muted">{{ Auth::user()->peran?->nama_peran ?? Auth::user()->nomor_identitas }}</span>
                        </div>
                        <div class="flex size-10 items-center justify-center rounded-full border-2 border-line bg-primary/10 font-bold text-primary lg:size-11">
                            {{ strtoupper(substr(Auth::user()->nama_lengkap, 0, 1)) }}
                        </div>
                        <svg class="size-4 text-muted transition-transform duration-150" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="m6 9 6 6 6-6"/>
                        </svg>
                    </button>

                    {{-- Dropdown Menu Pengguna --}}
                    <div
                        id="user-dropdown"
                        class="absolute right-0 mt-2 hidden w-64 rounded-2xl border border-line bg-white p-2 shadow-raised z-50"
                        role="menu"
                    >
                        <div class="border-b border-line px-3 py-2.5">
                            <p class="text-xs font-semibold text-ink">{{ Auth::user()->nama_lengkap }}</p>
                            <p class="truncate text-[11px] text-muted">{{ Auth::user()->email }}</p>
                            <span class="mt-1 inline-block rounded-full bg-primary/10 px-2 py-0.5 text-[10px] font-semibold text-primary">
                                {{ Auth::user()->peran?->nama_peran }}
                            </span>
                        </div>

                        {{-- Akses Cepat CRUD Manajemen (Khusus Super Admin) --}}
                        @if (Auth::user()->isSuperAdmin())
                            <div class="py-1 border-b border-line">
                                <span class="block px-3 pt-1 pb-0.5 text-[10px] font-bold uppercase tracking-wider text-muted">Panel CRUD</span>
                                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-2.5 rounded-lg px-3 py-1.5 text-xs font-medium text-ink transition-colors hover:bg-subtle hover:text-[#7C3AED]">
                                    <i class="fa-solid fa-users-gear text-[#7C3AED] w-4"></i>
                                    <span>Manajemen User</span>
                                </a>
                                <a href="{{ route('admin.books.index') }}" class="flex items-center gap-2.5 rounded-lg px-3 py-1.5 text-xs font-medium text-ink transition-colors hover:bg-subtle hover:text-[#2563EB]">
                                    <i class="fa-solid fa-book-bookmark text-[#2563EB] w-4"></i>
                                    <span>Manajemen Buku</span>
                                </a>
                                <a href="{{ route('admin.acara.index') }}" class="flex items-center gap-2.5 rounded-lg px-3 py-1.5 text-xs font-medium text-ink transition-colors hover:bg-subtle hover:text-[#EA580C]">
                                    <i class="fa-solid fa-calendar-days text-[#EA580C] w-4"></i>
                                    <span>Manajemen Acara</span>
                                </a>
                                <a href="{{ route('admin.berita.index') }}" class="flex items-center gap-2.5 rounded-lg px-3 py-1.5 text-xs font-medium text-ink transition-colors hover:bg-subtle hover:text-[#059669]">
                                    <i class="fa-solid fa-newspaper text-[#059669] w-4"></i>
                                    <span>Manajemen Berita</span>
                                </a>
                            </div>
                        @endif

                        <div class="py-1">
                            <a href="{{ route('pustakawan') }}" class="flex items-center gap-2.5 rounded-lg px-3 py-1.5 text-xs font-medium text-ink transition-colors hover:bg-subtle hover:text-primary">
                                <i class="fa-regular fa-user text-muted w-4"></i>
                                <span>Profil Pustakawan</span>
                            </a>
                            <a href="{{ route('chat') }}" class="flex items-center gap-2.5 rounded-lg px-3 py-1.5 text-xs font-medium text-ink transition-colors hover:bg-subtle hover:text-primary">
                                <i class="fa-regular fa-comments text-muted w-4"></i>
                                <span>Bantuan Pemustaka</span>
                            </a>
                        </div>

                        <div class="border-t border-line pt-1">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-left text-xs font-semibold text-[#E11D48] transition-colors hover:bg-red-50">
                                    <i class="fa-solid fa-arrow-right-from-bracket w-4"></i>
                                    <span>Keluar</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endguest
        </div>
    </nav>
</header>

<script>
    (() => {
        const btn = document.getElementById('user-menu-button');
        const dropdown = document.getElementById('user-dropdown');
        if (btn && dropdown) {
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                const isHidden = dropdown.classList.contains('hidden');
                dropdown.classList.toggle('hidden', !isHidden);
                btn.setAttribute('aria-expanded', String(isHidden));
            });
            document.addEventListener('click', () => {
                dropdown.classList.add('hidden');
                btn.setAttribute('aria-expanded', 'false');
            });
        }
    })();
</script>
