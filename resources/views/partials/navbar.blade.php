<header id="main-header" class="sticky top-2 sm:top-3 z-40 mx-2 sm:mx-3 lg:top-4 lg:mx-6 xl:mx-8 lg:mt-4 transition-all duration-300 ease-in-out">
    <nav aria-label="Navigasi utama" class="mx-auto flex h-14 max-w-[1570px] items-center justify-between gap-2 sm:gap-3 lg:gap-4 rounded-2xl bg-white px-2.5 sm:px-4 shadow-raised lg:h-[70px] lg:px-6 xl:px-7">
        {{-- Kiri: Tombol Hamburger & Logo Perpustakaan --}}
        <div class="flex min-w-0 items-center gap-1.5 sm:gap-2 lg:gap-2.5 xl:gap-3">
            <button
                type="button"
                id="sidebar-open"
                class="-ml-1 sm:-ml-2 inline-flex size-9 sm:size-10 lg:size-11 shrink-0 items-center justify-center rounded-xl text-primary hover:bg-[#F3F4F6] btn-press @guest lg:hidden @else flex @endguest"
                aria-label="Buka menu navigasi & panel manajemen"
                aria-expanded="false"
                aria-controls="sidebar"
                title="Buka Menu & Panel Manajemen"
            >
                <svg class="size-5 sm:size-6 transition-transform duration-200 group-hover:scale-105" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                    <path d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <a href="{{ url('/') }}" class="flex min-w-0 items-center gap-1.5 sm:gap-2 lg:gap-2.5 xl:gap-3 rounded-lg transition-transform duration-150 hover:scale-[1.01]">
                <img src="{{ asset('images/Logo.png') }}" alt="Logo MNC Library" class="h-7 sm:h-8 max-w-[90px] sm:max-w-[120px] w-auto shrink-0 object-contain lg:h-9" style="max-height: 36px; max-width: 120px;" width="120" height="36">
                <span class="truncate text-xs sm:text-base font-semibold text-primary lg:text-[17px] xl:text-[20px] 2xl:text-[22px]">Perpustakaan MNC University</span>
            </a>
        </div>

        {{-- Tengah: Navigasi Menu (Hanya tampil untuk pengunjung yang belum login) --}}
        @guest
            <div class="hidden items-center gap-0.5 lg:flex xl:gap-1.5 2xl:gap-2">
                @foreach ($navigationItems as $navigationItem)
                    <a href="{{ $navigationItem['href'] }}" class="nav-link-animated rounded-lg px-2 xl:px-3 py-1.5 xl:py-2 text-xs xl:text-[14px] 2xl:text-[15px] font-medium text-ink-secondary transition-colors duration-150 hover:bg-[#F3F4F6] hover:text-primary whitespace-nowrap">
                        {{ $navigationItem['label'] }}
                    </a>
                @endforeach
            </div>
        @endguest

        {{-- Kanan: Tombol Login & Daftar saat Guest / Profil & Dropdown saat Auth --}}
        <div class="flex shrink-0 items-center gap-1.5 sm:gap-2 lg:gap-2.5 xl:gap-3">
            @guest
                <a href="{{ route('register') }}" class="group inline-flex h-9 sm:h-10 items-center gap-1.5 rounded-xl border border-line bg-white px-2.5 sm:px-3.5 text-xs font-semibold text-ink shadow-sm transition-all duration-150 hover:bg-subtle hover:border-gray-300 btn-press lg:h-10 xl:h-11 lg:px-3.5 xl:px-4 lg:text-xs xl:text-sm">
                    <i class="fa-solid fa-user-plus text-xs text-primary transition-transform duration-150 group-hover:scale-110"></i>
                    <span>Daftar</span>
                </a>
                <a href="{{ route('login') }}" class="group inline-flex h-9 sm:h-10 items-center gap-1.5 sm:gap-2 rounded-xl bg-primary px-3 sm:px-3.5 text-xs font-semibold text-white shadow-sm transition-all duration-150 hover:bg-primary-dark btn-press btn-sheen lg:h-10 xl:h-11 lg:px-3.5 xl:px-4 lg:text-xs xl:text-sm">
                    <i class="fa-solid fa-arrow-right-to-bracket text-xs transition-transform duration-150 group-hover:translate-x-0.5"></i>
                    <span>Login</span>
                </a>
            @else
                @if (Auth::user()->isSuperAdmin() || Auth::user()->isPustakawan())
                    {{-- Tombol & Notifikasi Inbox Pengajuan Pengembalian Buku --}}
                    <div class="relative">
                        <button
                            type="button"
                            id="inbox-notification-button"
                            class="relative inline-flex size-9 sm:size-10 lg:size-11 items-center justify-center rounded-xl border border-line bg-white text-ink hover:bg-subtle hover:text-primary transition-colors focus:outline-none focus:ring-2 focus:ring-primary/20 btn-press"
                            aria-label="Kotak Masuk Pengajuan Pengembalian"
                            title="Kotak Masuk Pengajuan Pengembalian Buku"
                        >
                            <i class="fa-solid fa-inbox text-sm sm:text-base text-primary"></i>
                            @if (! empty($inboxPendingCount) && $inboxPendingCount > 0)
                                <span class="absolute -top-1 -right-1 flex h-5 min-w-[20px] items-center justify-center rounded-full bg-rose-600 px-1 text-[10px] font-bold text-white shadow-xs">
                                    {{ $inboxPendingCount > 99 ? '99+' : $inboxPendingCount }}
                                </span>
                            @endif
                        </button>

                        {{-- Dropdown Preview Notifikasi Inbox --}}
                        <div
                            id="inbox-notification-dropdown"
                            class="absolute right-0 mt-2 hidden w-80 sm:w-88 rounded-2xl border border-line bg-white p-3 shadow-raised z-50 animate-in fade-in zoom-in-95 duration-150"
                            role="menu"
                        >
                            <div class="flex items-center justify-between border-b border-line pb-2.5 px-1">
                                <div class="flex items-center gap-2">
                                    <span class="flex size-7 items-center justify-center rounded-lg bg-primary/10 text-primary">
                                        <i class="fa-solid fa-inbox text-xs"></i>
                                    </span>
                                    <div>
                                        <p class="text-xs font-bold text-ink">Inbox Pengembalian</p>
                                        <p class="text-[10px] text-muted">{{ $inboxPendingCount ?? 0 }} permohonan menunggu validasi</p>
                                    </div>
                                </div>
                                <a href="{{ route('admin.inbox') }}" class="text-[11px] font-semibold text-primary hover:underline">
                                    Buka Semua
                                </a>
                            </div>

                            <div class="divide-y divide-line/60 max-h-64 overflow-y-auto py-1">
                                @forelse ($inboxPendingItems ?? [] as $pendingItem)
                                    @php
                                        $isPinjam = $pendingItem->status === 'Menunggu Persetujuan';
                                        $targetTab = $isPinjam ? 'pinjam' : 'kembali';
                                    @endphp
                                    <a href="{{ route('admin.inbox', ['tab' => $targetTab]) }}" class="group block p-2 rounded-xl hover:bg-slate-50 transition-colors">
                                        <div class="flex items-start justify-between gap-2">
                                            <p class="text-xs font-bold text-ink group-hover:text-primary transition-colors line-clamp-1">
                                                {{ $pendingItem->pengguna->nama_lengkap }}
                                            </p>
                                            <span class="text-[9px] font-semibold {{ $isPinjam ? 'text-primary bg-primary/10' : 'text-amber-800 bg-amber-100' }} rounded px-1.5 py-0.5 shrink-0">
                                                {{ $isPinjam ? 'Pinjam' : 'Kembali' }}
                                            </span>
                                        </div>
                                        <p class="text-[11px] text-ink-secondary truncate mt-0.5">
                                            {{ $pendingItem->buku->judul_buku }}
                                        </p>
                                        <p class="text-[10px] text-muted mt-1 flex items-center justify-between">
                                            <span>No: {{ $pendingItem->eksemplar?->kode_inventaris ?: ($isPinjam ? 'Antrean Pinjam' : '-') }}</span>
                                            <span>{{ $pendingItem->tanggal_pengajuan_pinjam ? $pendingItem->tanggal_pengajuan_pinjam->diffForHumans() : ($pendingItem->tanggal_pengajuan_kembali ? $pendingItem->tanggal_pengajuan_kembali->diffForHumans() : 'Baru saja') }}</span>
                                        </p>
                                    </a>
                                @empty
                                    <div class="py-6 text-center text-xs text-muted">
                                        <i class="fa-solid fa-circle-check text-emerald-500 text-lg mb-1 block"></i>
                                        <span>Tidak ada permohonan pinjam atau kembali pending</span>
                                    </div>
                                @endforelse
                            </div>

                            <div class="border-t border-line pt-2 text-center">
                                <a href="{{ route('admin.inbox') }}" class="block w-full rounded-xl bg-primary/10 py-1.5 text-center text-xs font-bold text-primary hover:bg-primary hover:text-white transition-colors">
                                    Buka Inbox Validasi Lengkap
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="relative">
                    <button
                        type="button"
                        id="user-menu-button"
                        class="flex items-center gap-2 lg:gap-2.5 rounded-xl p-1.5 transition-colors hover:bg-subtle focus:outline-none"
                        aria-expanded="false"
                        aria-haspopup="true"
                    >
                        <div class="hidden text-right lg:block max-w-[130px] xl:max-w-[200px] 2xl:max-w-[260px]">
                            <span class="block truncate text-xs xl:text-sm font-semibold text-primary leading-tight">{{ Auth::user()->nama_lengkap }}</span>
                            <span class="block truncate text-[10px] xl:text-xs text-muted">{{ Auth::user()->peran?->nama_peran ?? Auth::user()->nomor_identitas }}</span>
                        </div>
                        @if (Auth::user()->foto_profil)
                            <img src="{{ asset('storage/'.Auth::user()->foto_profil) }}" alt="{{ Auth::user()->nama_lengkap }}" class="size-9 sm:size-10 rounded-full border-2 border-line object-cover lg:size-10 xl:size-11 shrink-0">
                        @else
                            <div class="flex size-9 sm:size-10 items-center justify-center rounded-full border-2 border-line bg-primary/10 font-bold text-primary lg:size-10 xl:size-11 shrink-0 text-xs sm:text-sm">
                                {{ strtoupper(substr(Auth::user()->nama_lengkap, 0, 1)) }}
                            </div>
                        @endif
                        <svg class="size-4 text-muted transition-transform duration-150 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
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
                                {{ Auth::user()->peran?->nama_peran ?? ucfirst(Auth::user()->role) }}
                            </span>
                        </div>

                        {{-- Akses Cepat CRUD Manajemen (Khusus Super Admin & Pustakawan) --}}
                        @if (Auth::user()->isSuperAdmin() || Auth::user()->isPustakawan())
                            <div class="py-1 border-b border-line">
                                <span class="block px-3 pt-1 pb-0.5 text-[10px] font-bold uppercase tracking-wider text-muted">Panel CRUD</span>
                                <a href="{{ route('admin.inbox') }}" class="flex items-center justify-between rounded-lg px-3 py-1.5 text-xs font-medium text-ink transition-colors hover:bg-subtle hover:text-amber-700">
                                    <div class="flex items-center gap-2.5">
                                        <i class="fa-solid fa-inbox text-amber-600 w-4"></i>
                                        <span>Inbox Pengembalian</span>
                                    </div>
                                    @if (! empty($inboxPendingCount) && $inboxPendingCount > 0)
                                        <span class="rounded-full bg-rose-600 px-1.5 py-0.2 text-[9px] font-bold text-white">
                                            {{ $inboxPendingCount }}
                                        </span>
                                    @endif
                                </a>
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
                                <a href="{{ route('admin.peminjaman.index') }}" class="flex items-center gap-2.5 rounded-lg px-3 py-1.5 text-xs font-medium text-ink transition-colors hover:bg-subtle hover:text-primary">
                                    <i class="fa-solid fa-arrows-rotate text-primary w-4"></i>
                                    <span>Peminjaman &amp; Sirkulasi</span>
                                </a>
                            </div>
                        @endif

                        <div class="py-1">
                            <a href="{{ route('profile.show') }}" class="flex items-center gap-2.5 rounded-lg px-3 py-1.5 text-xs font-medium text-ink transition-colors hover:bg-subtle hover:text-primary">
                                <i class="fa-solid fa-user-circle text-primary w-4"></i>
                                <span>Profil Saya</span>
                            </a>
                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 rounded-lg px-3 py-1.5 text-xs font-medium text-ink transition-colors hover:bg-subtle hover:text-primary">
                                <i class="fa-solid fa-user-pen text-muted w-4"></i>
                                <span>Edit Profil</span>
                            </a>
                            <a href="{{ route('password.edit') }}" class="flex items-center gap-2.5 rounded-lg px-3 py-1.5 text-xs font-medium text-ink transition-colors hover:bg-subtle hover:text-primary">
                                <i class="fa-solid fa-key text-muted w-4"></i>
                                <span>Ubah Kata Sandi</span>
                            </a>
                            <a href="{{ route('pustakawan') }}" class="flex items-center gap-2.5 rounded-lg px-3 py-1.5 text-xs font-medium text-ink transition-colors hover:bg-subtle hover:text-primary">
                                <i class="fa-regular fa-id-badge text-muted w-4"></i>
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
        const userBtn = document.getElementById('user-menu-button');
        const userDropdown = document.getElementById('user-dropdown');
        if (userBtn && userDropdown) {
            userBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                const isHidden = userDropdown.classList.contains('hidden');
                userDropdown.classList.toggle('hidden', !isHidden);
                userBtn.setAttribute('aria-expanded', String(isHidden));
                document.getElementById('inbox-notification-dropdown')?.classList.add('hidden');
            });
        }

        const inboxBtn = document.getElementById('inbox-notification-button');
        const inboxDropdown = document.getElementById('inbox-notification-dropdown');
        if (inboxBtn && inboxDropdown) {
            inboxBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                const isHidden = inboxDropdown.classList.contains('hidden');
                inboxDropdown.classList.toggle('hidden', !isHidden);
                userDropdown?.classList.add('hidden');
            });
        }

        document.addEventListener('click', () => {
            userDropdown?.classList.add('hidden');
            inboxDropdown?.classList.add('hidden');
            userBtn?.setAttribute('aria-expanded', 'false');
        });
    })();
</script>
