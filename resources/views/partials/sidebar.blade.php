{{-- Drawer menu (spesifikasi §5). Dibuka dari tombol hamburger di navbar (desktop & mobile). --}}
<div id="sidebar-backdrop" class="pointer-events-none fixed inset-0 z-40 bg-overlay opacity-0 transition-opacity duration-200" aria-hidden="true"></div>

<aside
    id="sidebar"
    role="dialog"
    aria-modal="true"
    aria-label="Menu Navigasi & Panel Manajemen"
    aria-hidden="true"
    inert
    class="fixed inset-y-0 left-0 z-50 flex h-dvh w-[88%] max-w-[340px] -translate-x-full flex-col bg-white shadow-drawer transition-transform duration-250 ease-out motion-reduce:transition-none sm:w-[360px] md:w-[390px]"
    style="transform: translateX(-100%);"
>
    {{-- Header Sidebar: Logo & Info Pengguna --}}
    <div class="relative border-b border-line px-5 pt-4 pb-4 bg-gradient-to-b from-subtle/80 to-white">
        <button
            type="button"
            id="sidebar-close"
            class="btn-press absolute top-3.5 right-3.5 inline-flex size-9 items-center justify-center rounded-xl border border-line bg-white text-muted hover:bg-subtle hover:text-ink transition-colors shadow-xs"
            aria-label="Tutup menu samping"
        >
            <i class="fa-solid fa-xmark text-sm" aria-hidden="true"></i>
        </button>

        {{-- Brand Perpustakaan --}}
        <a href="{{ url('/') }}" class="group flex items-center gap-3 pt-1">
            <div class="flex size-11 shrink-0 items-center justify-center rounded-xl border border-line bg-white p-1.5 shadow-xs transition-transform duration-200 group-hover:scale-105">
                <img src="{{ asset('images/Logo.png') }}" alt="Logo MNC Library" class="h-full w-full object-contain">
            </div>
            <div class="min-w-0 flex-1">
                <p class="truncate text-base font-bold text-primary leading-tight transition-colors group-hover:text-primary-dark">Perpustakaan MNC</p>
                <span class="inline-flex items-center gap-1.5 text-xs text-muted">
                    <span class="size-1.5 rounded-full bg-primary"></span>
                    MNC University Portal
                </span>
            </div>
        </a>

        @auth
            {{-- Kartu Info Pengguna Login --}}
            <div class="mt-3.5 rounded-2xl border border-line bg-white p-3.5 shadow-card transition-all duration-200 hover:border-primary/30">
                <div class="flex items-center gap-3">
                    @if (Auth::user()->foto_profil)
                        <img src="{{ asset('storage/' . Auth::user()->foto_profil) }}" alt="{{ Auth::user()->nama_lengkap }}" class="size-11 rounded-full border border-line object-cover shrink-0">
                    @else
                        <div class="flex size-11 shrink-0 items-center justify-center rounded-full bg-primary font-bold text-white shadow-xs text-sm">
                            {{ strtoupper(substr(Auth::user()->nama_lengkap, 0, 1)) }}
                        </div>
                    @endif
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-bold text-ink leading-tight">{{ Auth::user()->nama_lengkap }}</p>
                        <p class="truncate text-xs text-muted mt-0.5">{{ Auth::user()->email }}</p>
                        <p class="truncate text-[11px] font-medium text-primary mt-0.5">{{ Auth::user()->peran?->nama_peran ?? ucfirst(Auth::user()->role) }}</p>
                    </div>
                </div>

                <div class="mt-3 flex items-center justify-between border-t border-line/60 pt-2.5 text-xs">
                    <span class="text-muted text-[11px]">Akun Terverifikasi</span>
                    <a href="{{ route('profile.show') }}" class="group/p font-semibold text-primary hover:underline inline-flex items-center gap-1">
                        <span>Profil Saya</span>
                        <i class="fa-solid fa-angle-right text-[10px] transition-transform duration-200 group-hover/p:translate-x-0.5"></i>
                    </a>
                </div>
            </div>
        @endauth
    </div>

    {{-- Daftar Menu Navigasi Beranda, Dropdown CRUD, Layanan, dan Informasi --}}
    <nav aria-label="Menu samping" class="flex-1 overflow-y-auto px-3.5 py-4 space-y-2">
        {{-- 1. Beranda Utama --}}
        @php
            $isHomeActive = request()->is('/');
        @endphp
        <div>
            <a
                href="{{ url('/') }}"
                class="group flex items-center justify-between gap-3 rounded-2xl border p-3 text-sm transition-all duration-200 ease-out active:scale-[0.97] {{ $isHomeActive ? 'border-primary/40 bg-primary/10 text-primary font-bold shadow-xs' : 'border-line/70 bg-white text-ink hover:bg-subtle hover:translate-x-1.5 hover:border-primary/30' }}"
            >
                <div class="flex items-center gap-3">
                    <span class="flex size-9 shrink-0 items-center justify-center rounded-xl transition-all duration-200 ease-out {{ $isHomeActive ? 'bg-primary text-white shadow-xs scale-105 ring-2 ring-primary/25' : 'bg-slate-100 text-slate-600 group-hover:bg-primary group-hover:text-white group-hover:scale-110 group-hover:-rotate-6 group-hover:shadow-xs' }}">
                        <i class="fa-solid fa-house text-sm transition-transform duration-200 group-hover:scale-110"></i>
                    </span>
                    <div class="text-left">
                        <span class="block leading-tight transition-colors {{ $isHomeActive ? 'text-primary' : 'group-hover:text-primary' }}">Beranda Utama</span>
                        <span class="block text-[11px] text-muted">Halaman utama perpustakaan</span>
                    </div>
                </div>
                @if ($isHomeActive)
                    <span class="size-2 rounded-full bg-primary ring-4 ring-primary/20 shrink-0 mr-1 animate-pulse"></span>
                @else
                    <i class="fa-solid fa-angle-right text-[10px] text-muted/50 transition-all duration-200 group-hover:translate-x-1 group-hover:text-primary mr-1"></i>
                @endif
            </a>
        </div>

        {{-- 2. Dropdown Panel Manajemen CRUD (Khusus Super Admin & Pustakawan) --}}
        @if (Auth::check() && (Auth::user()->isSuperAdmin() || Auth::user()->isPustakawan()))
            @php
                $isAdminActive = request()->routeIs('admin.*');
            @endphp
            <details class="group rounded-2xl border transition-all duration-200 {{ $isAdminActive ? 'border-primary/40 bg-primary/[0.03]' : 'border-line/70 bg-white hover:border-primary/30' }} p-1" {{ $isAdminActive ? 'open' : '' }}>
                <summary class="flex cursor-pointer list-none items-center justify-between gap-3 rounded-xl p-2.5 text-sm transition-all duration-200 hover:bg-subtle active:scale-[0.98] [&::-webkit-details-marker]:hidden">
                    <div class="flex items-center gap-3 min-w-0">
                        <span class="flex size-9 shrink-0 items-center justify-center rounded-xl transition-all duration-200 ease-out {{ $isAdminActive ? 'bg-primary text-white shadow-xs scale-105 ring-2 ring-primary/25' : 'bg-primary/10 text-primary group-hover:bg-primary group-hover:text-white group-hover:scale-110 group-hover:-rotate-6 group-hover:shadow-xs' }}">
                            <i class="fa-solid fa-sliders text-sm transition-transform duration-200 group-hover:scale-110"></i>
                        </span>
                        <div class="min-w-0 text-left">
                            <span class="block font-bold text-ink truncate leading-tight group-hover:text-primary transition-colors">Panel Manajemen</span>
                            <span class="block text-[11px] text-muted truncate">Katalog, acara, berita &amp; user</span>
                        </div>
                    </div>
                    <div class="shrink-0 pr-1">
                        <i class="fa-solid fa-chevron-down text-xs text-muted transition-transform duration-300 ease-out group-open:rotate-180 group-hover:text-primary"></i>
                    </div>
                </summary>

                <ul class="mt-1 space-y-1 border-t border-line/60 pt-2 pb-1 px-1">
                    {{-- Manajemen User --}}
                    @php $isUserActive = request()->routeIs('admin.users.*'); @endphp
                    <li>
                        <a
                            href="{{ route('admin.users.index') }}"
                            class="group/item flex items-center justify-between rounded-xl px-2.5 py-2 text-xs transition-all duration-200 ease-out active:scale-[0.97] {{ $isUserActive ? 'bg-primary/10 font-bold text-primary shadow-xs border border-primary/20' : 'text-ink-secondary hover:bg-subtle hover:translate-x-1.5 hover:text-primary' }}"
                        >
                            <div class="flex items-center gap-2.5 min-w-0">
                                <span class="flex size-7 shrink-0 items-center justify-center rounded-lg transition-all duration-200 ease-out {{ $isUserActive ? 'bg-primary text-white shadow-xs scale-105 ring-2 ring-primary/25' : 'bg-slate-100 text-slate-600 group-hover/item:bg-primary group-hover/item:text-white group-hover/item:scale-110 group-hover/item:-rotate-6' }}">
                                    <i class="fa-solid fa-users-gear text-xs transition-transform duration-200 group-hover/item:scale-110"></i>
                                </span>
                                <div class="min-w-0">
                                    <span class="block truncate font-medium">Manajemen User</span>
                                    <span class="block text-[10px] text-muted truncate">Data anggota &amp; peran</span>
                                </div>
                            </div>
                            @if ($isUserActive)
                                <span class="size-1.5 rounded-full bg-primary ring-2 ring-primary/20 shrink-0 animate-pulse"></span>
                            @else
                                <i class="fa-solid fa-angle-right text-[10px] text-muted/50 transition-all duration-200 group-hover/item:translate-x-1 group-hover/item:text-primary"></i>
                            @endif
                        </a>
                    </li>

                    {{-- Manajemen Buku --}}
                    @php $isBookActive = request()->routeIs('admin.books.*'); @endphp
                    <li>
                        <a
                            href="{{ route('admin.books.index') }}"
                            class="group/item flex items-center justify-between rounded-xl px-2.5 py-2 text-xs transition-all duration-200 ease-out active:scale-[0.97] {{ $isBookActive ? 'bg-primary/10 font-bold text-primary shadow-xs border border-primary/20' : 'text-ink-secondary hover:bg-subtle hover:translate-x-1.5 hover:text-primary' }}"
                        >
                            <div class="flex items-center gap-2.5 min-w-0">
                                <span class="flex size-7 shrink-0 items-center justify-center rounded-lg transition-all duration-200 ease-out {{ $isBookActive ? 'bg-primary text-white shadow-xs scale-105 ring-2 ring-primary/25' : 'bg-slate-100 text-slate-600 group-hover/item:bg-primary group-hover/item:text-white group-hover/item:scale-110 group-hover/item:-rotate-6' }}">
                                    <i class="fa-solid fa-book-bookmark text-xs transition-transform duration-200 group-hover/item:scale-110"></i>
                                </span>
                                <div class="min-w-0">
                                    <span class="block truncate font-medium">Manajemen Buku</span>
                                    <span class="block text-[10px] text-muted truncate">Katalog &amp; stok rak</span>
                                </div>
                            </div>
                            @if ($isBookActive)
                                <span class="size-1.5 rounded-full bg-primary ring-2 ring-primary/20 shrink-0 animate-pulse"></span>
                            @else
                                <i class="fa-solid fa-angle-right text-[10px] text-muted/50 transition-all duration-200 group-hover/item:translate-x-1 group-hover/item:text-primary"></i>
                            @endif
                        </a>
                    </li>

                    {{-- Manajemen Acara --}}
                    @php $isAcaraActive = request()->routeIs('admin.acara.*'); @endphp
                    <li>
                        <a
                            href="{{ route('admin.acara.index') }}"
                            class="group/item flex items-center justify-between rounded-xl px-2.5 py-2 text-xs transition-all duration-200 ease-out active:scale-[0.97] {{ $isAcaraActive ? 'bg-primary/10 font-bold text-primary shadow-xs border border-primary/20' : 'text-ink-secondary hover:bg-subtle hover:translate-x-1.5 hover:text-primary' }}"
                        >
                            <div class="flex items-center gap-2.5 min-w-0">
                                <span class="flex size-7 shrink-0 items-center justify-center rounded-lg transition-all duration-200 ease-out {{ $isAcaraActive ? 'bg-primary text-white shadow-xs scale-105 ring-2 ring-primary/25' : 'bg-slate-100 text-slate-600 group-hover/item:bg-primary group-hover/item:text-white group-hover/item:scale-110 group-hover/item:-rotate-6' }}">
                                    <i class="fa-solid fa-calendar-days text-xs transition-transform duration-200 group-hover/item:scale-110"></i>
                                </span>
                                <div class="min-w-0">
                                    <span class="block truncate font-medium">Manajemen Acara</span>
                                    <span class="block text-[10px] text-muted truncate">Agenda &amp; poster kegiatan</span>
                                </div>
                            </div>
                            @if ($isAcaraActive)
                                <span class="size-1.5 rounded-full bg-primary ring-2 ring-primary/20 shrink-0 animate-pulse"></span>
                            @else
                                <i class="fa-solid fa-angle-right text-[10px] text-muted/50 transition-all duration-200 group-hover/item:translate-x-1 group-hover/item:text-primary"></i>
                            @endif
                        </a>
                    </li>

                    {{-- Manajemen Berita --}}
                    @php $isBeritaActive = request()->routeIs('admin.berita.*'); @endphp
                    <li>
                        <a
                            href="{{ route('admin.berita.index') }}"
                            class="group/item flex items-center justify-between rounded-xl px-2.5 py-2 text-xs transition-all duration-200 ease-out active:scale-[0.97] {{ $isBeritaActive ? 'bg-primary/10 font-bold text-primary shadow-xs border border-primary/20' : 'text-ink-secondary hover:bg-subtle hover:translate-x-1.5 hover:text-primary' }}"
                        >
                            <div class="flex items-center gap-2.5 min-w-0">
                                <span class="flex size-7 shrink-0 items-center justify-center rounded-lg transition-all duration-200 ease-out {{ $isBeritaActive ? 'bg-primary text-white shadow-xs scale-105 ring-2 ring-primary/25' : 'bg-slate-100 text-slate-600 group-hover/item:bg-primary group-hover/item:text-white group-hover/item:scale-110 group-hover/item:-rotate-6' }}">
                                    <i class="fa-solid fa-newspaper text-xs transition-transform duration-200 group-hover/item:scale-110"></i>
                                </span>
                                <div class="min-w-0">
                                    <span class="block truncate font-medium">Manajemen Berita</span>
                                    <span class="block text-[10px] text-muted truncate">Rilis pengumuman kampus</span>
                                </div>
                            </div>
                            @if ($isBeritaActive)
                                <span class="size-1.5 rounded-full bg-primary ring-2 ring-primary/20 shrink-0 animate-pulse"></span>
                            @else
                                <i class="fa-solid fa-angle-right text-[10px] text-muted/50 transition-all duration-200 group-hover/item:translate-x-1 group-hover/item:text-primary"></i>
                            @endif
                        </a>
                    </li>

                    {{-- Manajemen Peminjaman & Keterlambatan --}}
                    @php $isPinjamActive = request()->routeIs('admin.peminjaman.*'); @endphp
                    <li>
                        <a
                            href="{{ route('admin.peminjaman.index') }}"
                            class="group/item flex items-center justify-between rounded-xl px-2.5 py-2 text-xs transition-all duration-200 ease-out active:scale-[0.97] {{ $isPinjamActive ? 'bg-primary/10 font-bold text-primary shadow-xs border border-primary/20' : 'text-ink-secondary hover:bg-subtle hover:translate-x-1.5 hover:text-primary' }}"
                        >
                            <div class="flex items-center gap-2.5 min-w-0">
                                <span class="flex size-7 shrink-0 items-center justify-center rounded-lg transition-all duration-200 ease-out {{ $isPinjamActive ? 'bg-primary text-white shadow-xs scale-105 ring-2 ring-primary/25' : 'bg-slate-100 text-slate-600 group-hover/item:bg-primary group-hover/item:text-white group-hover/item:scale-110 group-hover/item:-rotate-6' }}">
                                    <i class="fa-solid fa-arrows-rotate text-xs transition-transform duration-200 group-hover/item:scale-110"></i>
                                </span>
                                <div class="min-w-0">
                                    <span class="block truncate font-medium">Sirkulasi &amp; Peminjaman</span>
                                    <span class="block text-[10px] text-muted truncate">Peminjaman &amp; denda buku</span>
                                </div>
                            </div>
                            @if ($isPinjamActive)
                                <span class="size-1.5 rounded-full bg-primary ring-2 ring-primary/20 shrink-0 animate-pulse"></span>
                            @else
                                <i class="fa-solid fa-angle-right text-[10px] text-muted/50 transition-all duration-200 group-hover/item:translate-x-1 group-hover/item:text-primary"></i>
                            @endif
                        </a>
                    </li>
                </ul>
            </details>
        @endif

        {{-- 3. Dropdown Layanan Perpustakaan (Koleksi & Sirkulasi) --}}
        @php
            $isServicesActive = request()->routeIs('opac', 'repository', 'ejournal', 'ojs', 'ebook');
        @endphp
        <details class="group rounded-2xl border transition-all duration-200 {{ $isServicesActive ? 'border-primary/40 bg-primary/[0.03]' : 'border-line/70 bg-white hover:border-primary/30' }} p-1" {{ $isServicesActive ? 'open' : '' }}>
            <summary class="flex cursor-pointer list-none items-center justify-between gap-3 rounded-xl p-2.5 text-sm transition-all duration-200 hover:bg-subtle active:scale-[0.98] [&::-webkit-details-marker]:hidden">
                <div class="flex items-center gap-3 min-w-0">
                    <span class="flex size-9 shrink-0 items-center justify-center rounded-xl transition-all duration-200 ease-out {{ $isServicesActive ? 'bg-primary text-white shadow-xs scale-105 ring-2 ring-primary/25' : 'bg-primary/10 text-primary group-hover:bg-primary group-hover:text-white group-hover:scale-110 group-hover:-rotate-6 group-hover:shadow-xs' }}">
                        <i class="fa-solid fa-book-open-reader text-sm transition-transform duration-200 group-hover:scale-110"></i>
                    </span>
                    <div class="min-w-0 text-left">
                        <span class="block font-bold text-ink truncate leading-tight group-hover:text-primary transition-colors">Layanan Perpustakaan</span>
                        <span class="block text-[11px] text-muted truncate">Koleksi fisik &amp; referensi digital</span>
                    </div>
                </div>
                <div class="shrink-0 pr-1">
                    <i class="fa-solid fa-chevron-down text-xs text-muted transition-transform duration-300 ease-out group-open:rotate-180 group-hover:text-primary"></i>
                </div>
            </summary>

            <ul class="mt-1 space-y-1 border-t border-line/60 pt-2 pb-1 px-1">
                {{-- Katalog OPAC --}}
                @php $isOpacActive = request()->routeIs('opac'); @endphp
                <li>
                    <a
                        href="{{ route('opac') }}"
                        class="group/item flex items-center justify-between rounded-xl px-2.5 py-2 text-xs transition-all duration-200 ease-out active:scale-[0.97] {{ $isOpacActive ? 'bg-primary/10 font-bold text-primary shadow-xs border border-primary/20' : 'text-ink-secondary hover:bg-subtle hover:translate-x-1.5 hover:text-primary' }}"
                    >
                        <div class="flex items-center gap-2.5 min-w-0">
                            <span class="flex size-7 shrink-0 items-center justify-center rounded-lg transition-all duration-200 ease-out {{ $isOpacActive ? 'bg-primary text-white shadow-xs scale-105 ring-2 ring-primary/25' : 'bg-slate-100 text-slate-600 group-hover/item:bg-primary group-hover/item:text-white group-hover/item:scale-110 group-hover/item:-rotate-6' }}">
                                <i class="fa-solid fa-magnifying-glass text-xs transition-transform duration-200 group-hover/item:scale-110"></i>
                            </span>
                            <div class="min-w-0">
                                <span class="block truncate font-medium">Katalog OPAC</span>
                                <span class="block text-[10px] text-muted truncate">Cari koleksi buku fisik di rak</span>
                            </div>
                        </div>
                        @if ($isOpacActive)
                            <span class="size-1.5 rounded-full bg-primary ring-2 ring-primary/20 shrink-0 animate-pulse"></span>
                        @else
                            <i class="fa-solid fa-angle-right text-[10px] text-muted/50 transition-all duration-200 group-hover/item:translate-x-1 group-hover/item:text-primary"></i>
                        @endif
                    </a>
                </li>

                {{-- Repository Skripsi --}}
                @php $isRepoActive = request()->routeIs('repository'); @endphp
                <li>
                    <a
                        href="{{ route('repository') }}"
                        class="group/item flex items-center justify-between rounded-xl px-2.5 py-2 text-xs transition-all duration-200 ease-out active:scale-[0.97] {{ $isRepoActive ? 'bg-primary/10 font-bold text-primary shadow-xs border border-primary/20' : 'text-ink-secondary hover:bg-subtle hover:translate-x-1.5 hover:text-primary' }}"
                    >
                        <div class="flex items-center gap-2.5 min-w-0">
                            <span class="flex size-7 shrink-0 items-center justify-center rounded-lg transition-all duration-200 ease-out {{ $isRepoActive ? 'bg-primary text-white shadow-xs scale-105 ring-2 ring-primary/25' : 'bg-slate-100 text-slate-600 group-hover/item:bg-primary group-hover/item:text-white group-hover/item:scale-110 group-hover/item:-rotate-6' }}">
                                <i class="fa-solid fa-graduation-cap text-xs transition-transform duration-200 group-hover/item:scale-110"></i>
                            </span>
                            <div class="min-w-0">
                                <span class="block truncate font-medium">Repository Skripsi</span>
                                <span class="block text-[10px] text-muted truncate">Karya ilmiah &amp; tugas akhir</span>
                            </div>
                        </div>
                        @if ($isRepoActive)
                            <span class="size-1.5 rounded-full bg-primary ring-2 ring-primary/20 shrink-0 animate-pulse"></span>
                        @else
                            <i class="fa-solid fa-angle-right text-[10px] text-muted/50 transition-all duration-200 group-hover/item:translate-x-1 group-hover/item:text-primary"></i>
                        @endif
                    </a>
                </li>

                {{-- E-Journal Digital --}}
                @php $isJournalActive = request()->routeIs('ejournal'); @endphp
                <li>
                    <a
                        href="{{ route('ejournal') }}"
                        class="group/item flex items-center justify-between rounded-xl px-2.5 py-2 text-xs transition-all duration-200 ease-out active:scale-[0.97] {{ $isJournalActive ? 'bg-primary/10 font-bold text-primary shadow-xs border border-primary/20' : 'text-ink-secondary hover:bg-subtle hover:translate-x-1.5 hover:text-primary' }}"
                    >
                        <div class="flex items-center gap-2.5 min-w-0">
                            <span class="flex size-7 shrink-0 items-center justify-center rounded-lg transition-all duration-200 ease-out {{ $isJournalActive ? 'bg-primary text-white shadow-xs scale-105 ring-2 ring-primary/25' : 'bg-slate-100 text-slate-600 group-hover/item:bg-primary group-hover/item:text-white group-hover/item:scale-110 group-hover/item:-rotate-6' }}">
                                <i class="fa-solid fa-newspaper text-xs transition-transform duration-200 group-hover/item:scale-110"></i>
                            </span>
                            <div class="min-w-0">
                                <span class="block truncate font-medium">E-Journal Digital</span>
                                <span class="block text-[10px] text-muted truncate">Jurnal internasional Scopus</span>
                            </div>
                        </div>
                        @if ($isJournalActive)
                            <span class="size-1.5 rounded-full bg-primary ring-2 ring-primary/20 shrink-0 animate-pulse"></span>
                        @else
                            <i class="fa-solid fa-angle-right text-[10px] text-muted/50 transition-all duration-200 group-hover/item:translate-x-1 group-hover/item:text-primary"></i>
                        @endif
                    </a>
                </li>

                {{-- Portal OJS Prodi --}}
                @php $isOjsActive = request()->routeIs('ojs'); @endphp
                <li>
                    <a
                        href="{{ route('ojs') }}"
                        class="group/item flex items-center justify-between rounded-xl px-2.5 py-2 text-xs transition-all duration-200 ease-out active:scale-[0.97] {{ $isOjsActive ? 'bg-primary/10 font-bold text-primary shadow-xs border border-primary/20' : 'text-ink-secondary hover:bg-subtle hover:translate-x-1.5 hover:text-primary' }}"
                    >
                        <div class="flex items-center gap-2.5 min-w-0">
                            <span class="flex size-7 shrink-0 items-center justify-center rounded-lg transition-all duration-200 ease-out {{ $isOjsActive ? 'bg-primary text-white shadow-xs scale-105 ring-2 ring-primary/25' : 'bg-slate-100 text-slate-600 group-hover/item:bg-primary group-hover/item:text-white group-hover/item:scale-110 group-hover/item:-rotate-6' }}">
                                <i class="fa-solid fa-layer-group text-xs transition-transform duration-200 group-hover/item:scale-110"></i>
                            </span>
                            <div class="min-w-0">
                                <span class="block truncate font-medium">Portal OJS Prodi</span>
                                <span class="block text-[10px] text-muted truncate">Publikasi jurnal program studi</span>
                            </div>
                        </div>
                        @if ($isOjsActive)
                            <span class="size-1.5 rounded-full bg-primary ring-2 ring-primary/20 shrink-0 animate-pulse"></span>
                        @else
                            <i class="fa-solid fa-angle-right text-[10px] text-muted/50 transition-all duration-200 group-hover/item:translate-x-1 group-hover/item:text-primary"></i>
                        @endif
                    </a>
                </li>

                {{-- E-Book Reader --}}
                @php $isEbookActive = request()->routeIs('ebook'); @endphp
                <li>
                    <a
                        href="{{ route('ebook') }}"
                        class="group/item flex items-center justify-between rounded-xl px-2.5 py-2 text-xs transition-all duration-200 ease-out active:scale-[0.97] {{ $isEbookActive ? 'bg-primary/10 font-bold text-primary shadow-xs border border-primary/20' : 'text-ink-secondary hover:bg-subtle hover:translate-x-1.5 hover:text-primary' }}"
                    >
                        <div class="flex items-center gap-2.5 min-w-0">
                            <span class="flex size-7 shrink-0 items-center justify-center rounded-lg transition-all duration-200 ease-out {{ $isEbookActive ? 'bg-primary text-white shadow-xs scale-105 ring-2 ring-primary/25' : 'bg-slate-100 text-slate-600 group-hover/item:bg-primary group-hover/item:text-white group-hover/item:scale-110 group-hover/item:-rotate-6' }}">
                                <i class="fa-solid fa-tablet-screen-button text-xs transition-transform duration-200 group-hover/item:scale-110"></i>
                            </span>
                            <div class="min-w-0">
                                <span class="block truncate font-medium">E-Book Reader</span>
                                <span class="block text-[10px] text-muted truncate">Buku digital interaktif resmi</span>
                            </div>
                        </div>
                        @if ($isEbookActive)
                            <span class="size-1.5 rounded-full bg-primary ring-2 ring-primary/20 shrink-0 animate-pulse"></span>
                        @else
                            <i class="fa-solid fa-angle-right text-[10px] text-muted/50 transition-all duration-200 group-hover/item:translate-x-1 group-hover/item:text-primary"></i>
                        @endif
                    </a>
                </li>
            </ul>
        </details>

        {{-- 4. Dropdown Informasi & Bantuan --}}
        @php
            $isHelpActive = request()->routeIs('tentang-kami', 'kontak', 'pustakawan', 'chat', 'berita.*', 'acara.*');
        @endphp
        <details class="group rounded-2xl border transition-all duration-200 {{ $isHelpActive ? 'border-primary/40 bg-primary/[0.03]' : 'border-line/70 bg-white hover:border-primary/30' }} p-1" {{ $isHelpActive ? 'open' : '' }}>
            <summary class="flex cursor-pointer list-none items-center justify-between gap-3 rounded-xl p-2.5 text-sm transition-all duration-200 hover:bg-subtle active:scale-[0.98] [&::-webkit-details-marker]:hidden">
                <div class="flex items-center gap-3 min-w-0">
                    <span class="flex size-9 shrink-0 items-center justify-center rounded-xl transition-all duration-200 ease-out {{ $isHelpActive ? 'bg-primary text-white shadow-xs scale-105 ring-2 ring-primary/25' : 'bg-primary/10 text-primary group-hover:bg-primary group-hover:text-white group-hover:scale-110 group-hover:-rotate-6 group-hover:shadow-xs' }}">
                        <i class="fa-solid fa-circle-info text-sm transition-transform duration-200 group-hover:scale-110"></i>
                    </span>
                    <div class="min-w-0 text-left">
                        <span class="block font-bold text-ink truncate leading-tight group-hover:text-primary transition-colors">Informasi &amp; Bantuan</span>
                        <span class="block text-[11px] text-muted truncate">Panduan, staf, berita &amp; kontak</span>
                    </div>
                </div>
                <div class="shrink-0 pr-1">
                    <i class="fa-solid fa-chevron-down text-xs text-muted transition-transform duration-300 ease-out group-open:rotate-180 group-hover:text-primary"></i>
                </div>
            </summary>

            <ul class="mt-1 space-y-1 border-t border-line/60 pt-2 pb-1 px-1">
                {{-- Tentang Kami --}}
                @php $isAboutActive = request()->routeIs('tentang-kami'); @endphp
                <li>
                    <a
                        href="{{ route('tentang-kami') }}"
                        class="group/item flex items-center justify-between rounded-xl px-2.5 py-2 text-xs transition-all duration-200 ease-out active:scale-[0.97] {{ $isAboutActive ? 'bg-primary/10 font-bold text-primary shadow-xs border border-primary/20' : 'text-ink-secondary hover:bg-subtle hover:translate-x-1.5 hover:text-primary' }}"
                    >
                        <div class="flex items-center gap-2.5 min-w-0">
                            <span class="flex size-7 shrink-0 items-center justify-center rounded-lg transition-all duration-200 ease-out {{ $isAboutActive ? 'bg-primary text-white shadow-xs scale-105 ring-2 ring-primary/25' : 'bg-slate-100 text-slate-600 group-hover/item:bg-primary group-hover/item:text-white group-hover/item:scale-110 group-hover/item:-rotate-6' }}">
                                <i class="fa-solid fa-building-columns text-xs transition-transform duration-200 group-hover/item:scale-110"></i>
                            </span>
                            <div class="min-w-0">
                                <span class="block truncate font-medium">Tentang Kami</span>
                                <span class="block text-[10px] text-muted truncate">Visi, misi &amp; profil perpustakaan</span>
                            </div>
                        </div>
                        @if ($isAboutActive)
                            <span class="size-1.5 rounded-full bg-primary ring-2 ring-primary/20 shrink-0 animate-pulse"></span>
                        @else
                            <i class="fa-solid fa-angle-right text-[10px] text-muted/50 transition-all duration-200 group-hover/item:translate-x-1 group-hover/item:text-primary"></i>
                        @endif
                    </a>
                </li>

                {{-- Kontak --}}
                @php $isContactActive = request()->routeIs('kontak'); @endphp
                <li>
                    <a
                        href="{{ route('kontak') }}"
                        class="group/item flex items-center justify-between rounded-xl px-2.5 py-2 text-xs transition-all duration-200 ease-out active:scale-[0.97] {{ $isContactActive ? 'bg-primary/10 font-bold text-primary shadow-xs border border-primary/20' : 'text-ink-secondary hover:bg-subtle hover:translate-x-1.5 hover:text-primary' }}"
                    >
                        <div class="flex items-center gap-2.5 min-w-0">
                            <span class="flex size-7 shrink-0 items-center justify-center rounded-lg transition-all duration-200 ease-out {{ $isContactActive ? 'bg-primary text-white shadow-xs scale-105 ring-2 ring-primary/25' : 'bg-slate-100 text-slate-600 group-hover/item:bg-primary group-hover/item:text-white group-hover/item:scale-110 group-hover/item:-rotate-6' }}">
                                <i class="fa-solid fa-address-book text-xs transition-transform duration-200 group-hover/item:scale-110"></i>
                            </span>
                            <div class="min-w-0">
                                <span class="block truncate font-medium">Kontak &amp; Lokasi</span>
                                <span class="block text-[10px] text-muted truncate">Alamat kampus &amp; jam layanan</span>
                            </div>
                        </div>
                        @if ($isContactActive)
                            <span class="size-1.5 rounded-full bg-primary ring-2 ring-primary/20 shrink-0 animate-pulse"></span>
                        @else
                            <i class="fa-solid fa-angle-right text-[10px] text-muted/50 transition-all duration-200 group-hover/item:translate-x-1 group-hover/item:text-primary"></i>
                        @endif
                    </a>
                </li>

                {{-- Profil Pustakawan --}}
                @php $isLibrarianActive = request()->routeIs('pustakawan'); @endphp
                <li>
                    <a
                        href="{{ route('pustakawan') }}"
                        class="group/item flex items-center justify-between rounded-xl px-2.5 py-2 text-xs transition-all duration-200 ease-out active:scale-[0.97] {{ $isLibrarianActive ? 'bg-primary/10 font-bold text-primary shadow-xs border border-primary/20' : 'text-ink-secondary hover:bg-subtle hover:translate-x-1.5 hover:text-primary' }}"
                    >
                        <div class="flex items-center gap-2.5 min-w-0">
                            <span class="flex size-7 shrink-0 items-center justify-center rounded-lg transition-all duration-200 ease-out {{ $isLibrarianActive ? 'bg-primary text-white shadow-xs scale-105 ring-2 ring-primary/25' : 'bg-slate-100 text-slate-600 group-hover/item:bg-primary group-hover/item:text-white group-hover/item:scale-110 group-hover/item:-rotate-6' }}">
                                <i class="fa-solid fa-user-tie text-xs transition-transform duration-200 group-hover/item:scale-110"></i>
                            </span>
                            <div class="min-w-0">
                                <span class="block truncate font-medium">Profil Pustakawan</span>
                                <span class="block text-[10px] text-muted truncate">Staf pengelola perpustakaan</span>
                            </div>
                        </div>
                        @if ($isLibrarianActive)
                            <span class="size-1.5 rounded-full bg-primary ring-2 ring-primary/20 shrink-0 animate-pulse"></span>
                        @else
                            <i class="fa-solid fa-angle-right text-[10px] text-muted/50 transition-all duration-200 group-hover/item:translate-x-1 group-hover/item:text-primary"></i>
                        @endif
                    </a>
                </li>

                {{-- Chatbot / Bantuan Pemustaka --}}
                @php $isChatActive = request()->routeIs('chat'); @endphp
                <li>
                    <a
                        href="{{ route('chat') }}"
                        class="group/item flex items-center justify-between rounded-xl px-2.5 py-2 text-xs transition-all duration-200 ease-out active:scale-[0.97] {{ $isChatActive ? 'bg-primary/10 font-bold text-primary shadow-xs border border-primary/20' : 'text-ink-secondary hover:bg-subtle hover:translate-x-1.5 hover:text-primary' }}"
                    >
                        <div class="flex items-center gap-2.5 min-w-0">
                            <span class="flex size-7 shrink-0 items-center justify-center rounded-lg transition-all duration-200 ease-out {{ $isChatActive ? 'bg-primary text-white shadow-xs scale-105 ring-2 ring-primary/25' : 'bg-slate-100 text-slate-600 group-hover/item:bg-primary group-hover/item:text-white group-hover/item:scale-110 group-hover/item:-rotate-6' }}">
                                <i class="fa-solid fa-headset text-xs transition-transform duration-200 group-hover/item:scale-110"></i>
                            </span>
                            <div class="min-w-0">
                                <span class="block truncate font-medium">Chatbot &amp; Bantuan</span>
                                <span class="block text-[10px] text-muted truncate">Tanya syarat denda &amp; turnitin</span>
                            </div>
                        </div>
                        @if ($isChatActive)
                            <span class="size-1.5 rounded-full bg-primary ring-2 ring-primary/20 shrink-0 animate-pulse"></span>
                        @else
                            <i class="fa-solid fa-angle-right text-[10px] text-muted/50 transition-all duration-200 group-hover/item:translate-x-1 group-hover/item:text-primary"></i>
                        @endif
                    </a>
                </li>

                {{-- Berita Perpustakaan --}}
                @php $isNewsActive = request()->routeIs('berita.*'); @endphp
                <li>
                    <a
                        href="{{ route('berita.index') }}"
                        class="group/item flex items-center justify-between rounded-xl px-2.5 py-2 text-xs transition-all duration-200 ease-out active:scale-[0.97] {{ $isNewsActive ? 'bg-primary/10 font-bold text-primary shadow-xs border border-primary/20' : 'text-ink-secondary hover:bg-subtle hover:translate-x-1.5 hover:text-primary' }}"
                    >
                        <div class="flex items-center gap-2.5 min-w-0">
                            <span class="flex size-7 shrink-0 items-center justify-center rounded-lg transition-all duration-200 ease-out {{ $isNewsActive ? 'bg-primary text-white shadow-xs scale-105 ring-2 ring-primary/25' : 'bg-slate-100 text-slate-600 group-hover/item:bg-primary group-hover/item:text-white group-hover/item:scale-110 group-hover/item:-rotate-6' }}">
                                <i class="fa-solid fa-newspaper text-xs transition-transform duration-200 group-hover/item:scale-110"></i>
                            </span>
                            <div class="min-w-0">
                                <span class="block truncate font-medium">Berita &amp; Rilis</span>
                                <span class="block text-[10px] text-muted truncate">Kabar terkini sivitas kampus</span>
                            </div>
                        </div>
                        @if ($isNewsActive)
                            <span class="size-1.5 rounded-full bg-primary ring-2 ring-primary/20 shrink-0 animate-pulse"></span>
                        @else
                            <i class="fa-solid fa-angle-right text-[10px] text-muted/50 transition-all duration-200 group-hover/item:translate-x-1 group-hover/item:text-primary"></i>
                        @endif
                    </a>
                </li>

                {{-- Agenda Acara --}}
                @php $isEventActive = request()->routeIs('acara.*'); @endphp
                <li>
                    <a
                        href="{{ route('acara.index') }}"
                        class="group/item flex items-center justify-between rounded-xl px-2.5 py-2 text-xs transition-all duration-200 ease-out active:scale-[0.97] {{ $isEventActive ? 'bg-primary/10 font-bold text-primary shadow-xs border border-primary/20' : 'text-ink-secondary hover:bg-subtle hover:translate-x-1.5 hover:text-primary' }}"
                    >
                        <div class="flex items-center gap-2.5 min-w-0">
                            <span class="flex size-7 shrink-0 items-center justify-center rounded-lg transition-all duration-200 ease-out {{ $isEventActive ? 'bg-primary text-white shadow-xs scale-105 ring-2 ring-primary/25' : 'bg-slate-100 text-slate-600 group-hover/item:bg-primary group-hover/item:text-white group-hover/item:scale-110 group-hover/item:-rotate-6' }}">
                                <i class="fa-solid fa-calendar-days text-xs transition-transform duration-200 group-hover/item:scale-110"></i>
                            </span>
                            <div class="min-w-0">
                                <span class="block truncate font-medium">Agenda Acara</span>
                                <span class="block text-[10px] text-muted truncate">Webinar &amp; kelas literasi</span>
                            </div>
                        </div>
                        @if ($isEventActive)
                            <span class="size-1.5 rounded-full bg-primary ring-2 ring-primary/20 shrink-0 animate-pulse"></span>
                        @else
                            <i class="fa-solid fa-angle-right text-[10px] text-muted/50 transition-all duration-200 group-hover/item:translate-x-1 group-hover/item:text-primary"></i>
                        @endif
                    </a>
                </li>
            </ul>
        </details>
    </nav>

    {{-- Footer Bawah: Tombol Login / Logout & Pengaturan --}}
    <div class="border-t border-line p-4 bg-gradient-to-t from-subtle/80 to-white">
        @guest
            <div class="flex flex-col gap-2">
                <a href="{{ route('register') }}" class="btn-press flex h-10 w-full items-center justify-center gap-2 rounded-xl border border-line bg-white text-xs font-semibold text-ink shadow-xs transition-all duration-200 hover:bg-subtle hover:border-primary/40 active:scale-[0.98]">
                    <i class="fa-solid fa-user-plus text-xs text-primary transition-transform duration-200 hover:scale-110"></i>
                    <span>Daftar Anggota Baru</span>
                </a>
                <a href="{{ route('login') }}" class="btn-press btn-sheen flex h-10 w-full items-center justify-center gap-2 rounded-xl bg-primary text-xs font-semibold text-white shadow-xs transition-all duration-200 hover:bg-primary-dark active:scale-[0.98]">
                    <i class="fa-solid fa-arrow-right-to-bracket text-xs transition-transform duration-200 hover:translate-x-0.5"></i>
                    <span>Masuk ke Akun</span>
                </a>
            </div>
        @else
            <div class="flex items-center gap-2">
                @php $isProfileEditActive = request()->routeIs('profile.edit'); @endphp
                <a href="{{ route('profile.edit') }}" class="group/set flex h-11 flex-1 items-center justify-center gap-2 rounded-xl border text-xs font-semibold shadow-xs transition-all duration-200 ease-out active:scale-[0.97] {{ $isProfileEditActive ? 'bg-primary/10 text-primary border-primary/30 font-bold shadow-xs' : 'border-line bg-white text-ink hover:bg-subtle hover:border-primary/40 hover:text-primary hover:translate-y-[-1px]' }}">
                    <i class="fa-solid fa-user-gear text-xs transition-transform duration-200 group-hover/set:scale-125 group-hover/set:-rotate-12 text-primary"></i>
                    <span>Pengaturan Akun</span>
                </a>
                <form action="{{ route('logout') }}" method="POST" class="shrink-0">
                    @csrf
                    <button type="submit" class="group/out flex h-11 items-center justify-center gap-1.5 rounded-xl border border-red-200 bg-red-50 px-4 text-xs font-semibold text-[#E11D48] transition-all duration-200 hover:bg-red-100 hover:border-red-300 active:scale-[0.96]" title="Keluar dari akun">
                        <i class="fa-solid fa-arrow-right-from-bracket text-xs transition-transform duration-200 group-hover/out:translate-x-0.5"></i>
                        <span class="sr-only sm:not-sr-only">Keluar</span>
                    </button>
                </form>
            </div>
        @endguest
    </div>
</aside>
