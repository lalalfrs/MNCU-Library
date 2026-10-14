{{-- Drawer menu (spesifikasi §5). Dipakai di mobile dan tablet, dibuka dari tombol hamburger di navbar. --}}
<div id="sidebar-backdrop" class="pointer-events-none fixed inset-0 z-40 bg-overlay opacity-0 transition-opacity duration-200 lg:hidden" aria-hidden="true"></div>

<aside
    id="sidebar"
    role="dialog"
    aria-modal="true"
    aria-label="Menu"
    aria-hidden="true"
    inert
    class="fixed inset-y-0 left-0 z-50 flex h-dvh w-[82%] max-w-[320px] -translate-x-full flex-col bg-white shadow-drawer transition-transform duration-250 ease-out motion-reduce:transition-none md:w-[400px] md:max-w-none lg:hidden"
>
    <div class="relative px-6 pt-4 pb-6 text-center">
        <button
            type="button"
            id="sidebar-close"
            class="absolute top-3 right-3 inline-flex size-11 items-center justify-center rounded-xl text-muted hover:bg-[#F3F4F6]"
            aria-label="Tutup menu"
        >
            <svg class="size-[22px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                <path d="M6 6l12 12M18 6L6 18" />
            </svg>
        </button>

        <img src="{{ asset('images/Logo.png') }}" alt="Logo MNC Library" class="mx-auto mt-6 h-20 w-auto md:h-[110px]">
        <p class="mt-3 text-[17px] font-semibold text-ink md:text-xl">MNC Library</p>
    </div>

    <div class="mx-0 h-px bg-[#2563EB]"></div>

    <nav aria-label="Menu samping" class="flex-1 overflow-y-auto py-6">
        <ul class="flex flex-col">
            <li>
                <a href="{{ url('/') }}" aria-current="page" class="flex h-[46px] items-center gap-4 border-l-[3px] border-primary bg-[#EEF2FF] pl-[19px] text-[15px] font-medium text-primary md:h-[52px] md:pl-[25px] md:text-[17px]">
                    <svg class="size-6 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M3 10.5 12 3l9 7.5V20a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1z" />
                    </svg>
                    Beranda
                </a>
            </li>

            <li>
                <details class="group">
                    <summary class="flex h-[46px] cursor-pointer list-none items-center gap-4 pr-5 pl-[22px] text-[15px] text-ink hover:bg-[#F3F4F6] md:h-[52px] md:pl-7 md:text-[17px] [&::-webkit-details-marker]:hidden">
                        <svg class="size-6 shrink-0 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M4 5.5A1.5 1.5 0 0 1 5.5 4H10v16H5.5A1.5 1.5 0 0 1 4 18.5zM14 4h4.5A1.5 1.5 0 0 1 20 5.5v13a1.5 1.5 0 0 1-1.5 1.5H14z" />
                        </svg>
                        <span class="flex-1">Layanan Perpustakaan</span>
                        <svg class="size-[18px] text-muted transition-transform duration-200 group-open:rotate-180 motion-reduce:transition-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="m6 9 6 6 6-6" />
                        </svg>
                    </summary>
                    {{-- TODO (integrasi): isi href tiap layanan. --}}
                    <ul class="pb-2">
                        @foreach (['Katalog OPAC', 'Repository', 'E-Journal', 'OJS per Prodi', 'E-Book'] as $serviceLabel)
                            <li>
                                <a href="#" class="flex h-10 items-center gap-2.5 pl-[62px] text-sm text-[#4B5563] hover:text-primary md:h-11 md:pl-20 md:text-base">
                                    <span class="size-1.5 rounded-full bg-ink" aria-hidden="true"></span>
                                    {{ $serviceLabel }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </details>
            </li>

            @foreach ($navigationItems as $navigationItem)
                <li>
                    <a href="{{ $navigationItem['href'] }}" class="flex h-[46px] items-center pl-[62px] text-[15px] text-ink hover:bg-[#F3F4F6] md:h-[52px] md:pl-20 md:text-[17px]">
                        {{ $navigationItem['label'] }}
                    </a>
                </li>
            @endforeach
        </ul>
    </nav>
</aside>
