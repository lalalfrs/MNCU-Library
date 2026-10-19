@extends('layouts.app')

@section('title', 'Chatbot & Bantuan Pemustaka')
@section('description', 'Layanan asisten virtual dan bantuan pemustaka Perpustakaan MNC University. Tanyakan informasi jam operasional, peminjaman buku, katalog OPAC, dan akses e-resources.')

@section('content')
    <div class="mx-auto flex w-full max-w-[1570px] flex-1 flex-col gap-8 px-3 py-6 lg:px-6 lg:py-10">
        {{-- Breadcrumb & Header --}}
        <div class="flex flex-col gap-2">
            <nav aria-label="Breadcrumb" class="flex items-center gap-2 text-xs text-muted sm:text-sm">
                <a href="{{ route('beranda') }}" class="hover:text-primary">Beranda</a>
                <svg class="size-3.5 text-muted/60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
                <span class="font-medium text-ink">Chatbot & Bantuan</span>
            </nav>
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-semibold text-primary sm:text-3xl lg:text-4xl">
                        Chatbot & Bantuan Pemustaka
                    </h1>
                    <p class="mt-1 text-sm text-ink-secondary sm:text-base">
                        Asisten virtual Perpustakaan MNC University siap menjawab pertanyaan seputar layanan dan fasilitas 24/7.
                    </p>
                </div>
                <div class="inline-flex items-center gap-1.5 text-xs text-muted">
                    <i class="fa-solid fa-headset text-primary text-xs"></i>
                    <span>Layanan Bantuan Pemustaka</span>
                </div>
            </div>
        </div>

        {{-- Main Chat Container --}}
        <div class="grid flex-1 gap-6 lg:grid-cols-[330px_1fr] xl:grid-cols-[380px_1fr]">
            {{-- Sidebar Bantuan & Topik Populer --}}
            <aside class="flex flex-col gap-5 rounded-2xl border border-line bg-white p-5 lg:p-6">
                <div>
                    <h2 class="text-base font-semibold text-ink lg:text-lg">Topik Pertanyaan Populer</h2>
                    <p class="mt-1 text-xs text-muted">Pilih kategori atau klik pertanyaan untuk langsung mendapatkan jawaban otomatis:</p>
                </div>

                {{-- Filter Kategori --}}
                <div class="flex flex-wrap gap-1.5" id="category-filter-bar">
                    <button type="button" class="cat-filter-btn rounded-lg bg-primary px-2.5 py-1 text-[11px] font-medium text-white transition-colors" data-category="all">Semua</button>
                    <button type="button" class="cat-filter-btn rounded-lg border border-line bg-subtle/70 px-2.5 py-1 text-[11px] font-medium text-ink transition-colors hover:border-primary/40 hover:text-primary" data-category="Sirkulasi">Sirkulasi</button>
                    <button type="button" class="cat-filter-btn rounded-lg border border-line bg-subtle/70 px-2.5 py-1 text-[11px] font-medium text-ink transition-colors hover:border-primary/40 hover:text-primary" data-category="Turnitin & Riset">Riset</button>
                    <button type="button" class="cat-filter-btn rounded-lg border border-line bg-subtle/70 px-2.5 py-1 text-[11px] font-medium text-ink transition-colors hover:border-primary/40 hover:text-primary" data-category="Digital">Digital</button>
                    <button type="button" class="cat-filter-btn rounded-lg border border-line bg-subtle/70 px-2.5 py-1 text-[11px] font-medium text-ink transition-colors hover:border-primary/40 hover:text-primary" data-category="Fasilitas">Fasilitas</button>
                    <button type="button" class="cat-filter-btn rounded-lg border border-line bg-subtle/70 px-2.5 py-1 text-[11px] font-medium text-ink transition-colors hover:border-primary/40 hover:text-primary" data-category="Layanan">Layanan</button>
                </div>

                {{-- List Pertanyaan Populer --}}
                <div class="flex max-h-[440px] flex-col gap-2 overflow-y-auto pr-1" id="quick-prompt-list">
                    @foreach ($quickPrompts as $prompt)
                        @php
                            $queryText = is_array($prompt) ? $prompt['question'] : $prompt;
                            $category = is_array($prompt) ? ($prompt['category'] ?? 'Layanan') : 'Layanan';
                            $icon = is_array($prompt) ? ($prompt['icon'] ?? 'fa-circle-question') : 'fa-circle-question';
                        @endphp
                        <button
                            type="button"
                            class="quick-chip group flex items-start gap-2.5 rounded-xl border border-line bg-subtle/50 p-3 text-left text-xs font-medium text-ink transition-all duration-150 hover:border-primary/40 hover:bg-white hover:text-primary hover:shadow-xs"
                            data-query="{{ $queryText }}"
                            data-category="{{ $category }}"
                        >
                            <div class="mt-0.5 flex size-6 shrink-0 items-center justify-center rounded-lg bg-primary/10 text-primary transition-transform duration-150 group-hover:scale-110">
                                <i class="fa-solid {{ $icon }} text-xs"></i>
                            </div>
                            <div class="flex flex-1 flex-col gap-1">
                                <span class="leading-relaxed text-ink group-hover:text-primary">{{ $queryText }}</span>
                                <span class="w-fit rounded bg-primary/5 px-1.5 py-0.5 text-[10px] font-semibold text-primary">
                                    {{ $category }}
                                </span>
                            </div>
                        </button>
                    @endforeach
                </div>

                <div class="mt-auto border-t border-line pt-4">
                    <div class="rounded-xl bg-primary/5 p-4">
                        <div class="flex items-center gap-2 text-primary">
                            <i class="fa-solid fa-headset text-sm"></i>
                            <h3 class="text-xs font-semibold">Butuh Bantuan Langsung?</h3>
                        </div>
                        <p class="mt-1.5 text-xs leading-relaxed text-ink-secondary">
                            Hubungi staf pustakawan melalui email 
                            <a href="mailto:perpustakaan@mncuniversity.ac.id" class="font-medium text-primary underline">perpustakaan@mncuniversity.ac.id</a>
                            atau kunjungi Ruang Layanan Perpustakaan Lantai 2.
                        </p>
                    </div>
                </div>
            </aside>

            {{-- Chat Room Box --}}
            <section aria-label="Kotak Percakapan" class="flex min-h-[580px] flex-1 flex-col rounded-2xl border border-line bg-white shadow-card">
                {{-- Chat Room Header --}}
                <div class="flex items-center justify-between border-b border-line px-5 py-4">
                    <div class="flex items-center gap-3">
                        <div class="flex size-10 items-center justify-center rounded-xl bg-primary text-white shadow-xs">
                            <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-semibold text-ink">MNCU Virtual Assistant</span>
                                <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2 py-0.5 text-[10px] font-medium text-emerald-700">
                                    <span class="size-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    Online
                                </span>
                            </div>
                            <span class="block text-xs text-muted">Layanan Bantuan 24/7 Perpustakaan MNC University</span>
                        </div>
                    </div>
                    <button
                        type="button"
                        id="clear-chat-btn"
                        class="inline-flex items-center gap-1.5 rounded-lg border border-line/60 px-2.5 py-1.5 text-xs text-muted transition-colors hover:border-line hover:bg-subtle hover:text-ink"
                    >
                        <i class="fa-solid fa-trash-can text-[11px]"></i>
                        <span>Hapus Chat</span>
                    </button>
                </div>

                {{-- Messages Container --}}
                <div id="messages-container" class="flex-1 space-y-4 overflow-y-auto p-5" style="max-height: 520px;">
                    {{-- Bot Welcome Message --}}
                    <div class="flex items-start gap-3" id="welcome-message-block">
                        <div class="flex size-8 shrink-0 items-center justify-center rounded-lg bg-primary/10 text-primary">
                            <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 8V4H8"/>
                                <rect width="16" height="12" x="4" y="8" rx="2"/>
                                <path d="M2 14h2"/>
                                <path d="M20 14h2"/>
                                <path d="M15 13v2"/>
                                <path d="M9 13v2"/>
                            </svg>
                        </div>
                        <div class="max-w-[88%] rounded-2xl rounded-tl-sm border border-line bg-subtle p-4 text-xs leading-relaxed text-ink sm:text-sm">
                            <p class="font-semibold text-primary">Halo! Selamat datang di Asisten Bantuan Perpustakaan MNC University.</p>
                            <p class="mt-2 text-ink-secondary">
                                Saya siap membantu menjawab pertanyaan seputar jam buka, peminjaman buku, katalog OPAC, uji similarity Turnitin, e-journal luar kampus, repository skripsi, hingga pengurusan bebas pustaka wisuda.
                            </p>
                            
                            {{-- Quick Starter Suggestions --}}
                            <div class="mt-3.5 border-t border-line/80 pt-3">
                                <span class="mb-2 block text-[11px] font-semibold text-ink-secondary">Pilih topik cepat:</span>
                                <div class="flex flex-wrap gap-1.5">
                                    <button type="button" class="inline-chip rounded-lg border border-line bg-white px-2.5 py-1 text-[11px] font-medium text-ink transition-colors hover:border-primary hover:text-primary hover:bg-primary/5" data-query="Jam operasional perpustakaan?">
                                        <i class="fa-regular fa-clock text-primary mr-1"></i>Jam Operasional
                                    </button>
                                    <button type="button" class="inline-chip rounded-lg border border-line bg-white px-2.5 py-1 text-[11px] font-medium text-ink transition-colors hover:border-primary hover:text-primary hover:bg-primary/5" data-query="Batas durasi & perpanjangan buku?">
                                        <i class="fa-solid fa-book-bookmark text-primary mr-1"></i>Perpanjangan Buku
                                    </button>
                                    <button type="button" class="inline-chip rounded-lg border border-line bg-white px-2.5 py-1 text-[11px] font-medium text-ink transition-colors hover:border-primary hover:text-primary hover:bg-primary/5" data-query="Bagaimana cara cek Turnitin untuk skripsi?">
                                        <i class="fa-solid fa-magnifying-glass text-primary mr-1"></i>Cek Turnitin
                                    </button>
                                    <button type="button" class="inline-chip rounded-lg border border-line bg-white px-2.5 py-1 text-[11px] font-medium text-ink transition-colors hover:border-primary hover:text-primary hover:bg-primary/5" data-query="Syarat bebas pustaka untuk wisuda?">
                                        <i class="fa-solid fa-graduation-cap text-primary mr-1"></i>Bebas Pustaka
                                    </button>
                                    <button type="button" class="inline-chip rounded-lg border border-line bg-white px-2.5 py-1 text-[11px] font-medium text-ink transition-colors hover:border-primary hover:text-primary hover:bg-primary/5" data-query="Bagaimana akses e-journal dari luar kampus?">
                                        <i class="fa-solid fa-globe text-primary mr-1"></i>E-Journal Remote
                                    </button>
                                    <button type="button" class="inline-chip rounded-lg border border-line bg-white px-2.5 py-1 text-[11px] font-medium text-ink transition-colors hover:border-primary hover:text-primary hover:bg-primary/5" data-query="Cara peminjaman ruang diskusi kelompok?">
                                        <i class="fa-solid fa-users text-primary mr-1"></i>Ruang Diskusi
                                    </button>
                                    <button type="button" class="inline-chip rounded-lg border border-line bg-white px-2.5 py-1 text-[11px] font-medium text-ink transition-colors hover:border-primary hover:text-primary hover:bg-primary/5" data-query="Berapa denda keterlambatan buku?">
                                        <i class="fa-solid fa-money-bill-wave text-primary mr-1"></i>Denda Keterlambatan
                                    </button>
                                </div>
                            </div>
                            <span class="mt-2.5 block text-[10px] text-muted">{{ now()->format('H:i') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Chat Input Box --}}
                <div class="border-t border-line bg-white p-4">
                    <form id="chat-form" action="{{ route('chat') }}" method="POST" class="flex items-center gap-2">
                        @csrf
                        <label for="chat-input" class="sr-only">Tulis pertanyaan</label>
                        <input
                            type="text"
                            id="chat-input"
                            name="message"
                            placeholder="Tulis pertanyaan Anda di sini... (misal: cek turnitin, cara perpanjang buku)"
                            class="flex-1 rounded-xl border border-line bg-subtle px-4 py-3 text-xs text-ink placeholder:text-muted focus:border-primary focus:bg-white focus:outline-none focus:ring-1 focus:ring-primary sm:text-sm"
                            autocomplete="off"
                            required
                        >
                        <button
                            type="submit"
                            id="send-button"
                            class="inline-flex h-11 items-center justify-center gap-2 rounded-xl bg-primary px-5 text-xs font-semibold text-white shadow-sm transition-colors duration-150 hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 sm:text-sm"
                        >
                            <span>Kirim</span>
                            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="m22 2-7 20-4-9-9-4Z"/>
                                <path d="M22 2 11 13"/>
                            </svg>
                        </button>
                    </form>
                    <p class="mt-2 text-center text-[11px] text-muted">
                        Respon asisten otomatis Perpustakaan MNC University. Untuk layanan personal, silakan kunjungi meja sirkulasi Lantai 2.
                    </p>
                </div>
            </section>
        </div>
    </div>

    {{-- Chatbot Interaction Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('chat-form');
            const input = document.getElementById('chat-input');
            const messagesContainer = document.getElementById('messages-container');
            const clearBtn = document.getElementById('clear-chat-btn');
            const categoryBtns = document.querySelectorAll('.cat-filter-btn');
            const quickChips = document.querySelectorAll('.quick-chip');

            const scrollToBottom = () => {
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            };

            const escapeHtml = (unsafe) => {
                return unsafe
                    .replace(/&/g, "&amp;")
                    .replace(/</g, "&lt;")
                    .replace(/>/g, "&gt;")
                    .replace(/"/g, "&quot;")
                    .replace(/'/g, "&#039;");
            };

            const appendMessage = (sender, text, time) => {
                const wrapper = document.createElement('div');
                wrapper.className = sender === 'user' ? 'flex items-start justify-end gap-3' : 'flex items-start gap-3';

                const avatar = sender === 'user' 
                    ? `<div class="flex size-8 shrink-0 items-center justify-center rounded-lg bg-primary text-white"><svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div>`
                    : `<div class="flex size-8 shrink-0 items-center justify-center rounded-lg bg-primary/10 text-primary"><svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 8V4H8"/><rect width="16" height="12" x="4" y="8" rx="2"/><path d="M2 14h2"/><path d="M20 14h2"/><path d="M15 13v2"/><path d="M9 13v2"/></svg></div>`;

                const bubbleClasses = sender === 'user'
                    ? 'max-w-[85%] rounded-2xl rounded-tr-sm bg-primary text-white p-4 text-xs sm:text-sm leading-relaxed shadow-sm'
                    : 'max-w-[85%] rounded-2xl rounded-tl-sm border border-line bg-subtle p-4 text-xs sm:text-sm leading-relaxed text-ink';

                const timeColor = sender === 'user' ? 'text-white/70' : 'text-muted';

                const contentHtml = `
                    <div class="${bubbleClasses}">
                        <p class="whitespace-pre-line">${escapeHtml(text)}</p>
                        <span class="mt-2 block text-[10px] ${timeColor}">${time}</span>
                    </div>
                `;

                if (sender === 'user') {
                    wrapper.innerHTML = contentHtml + avatar;
                } else {
                    wrapper.innerHTML = avatar + contentHtml;
                }

                messagesContainer.appendChild(wrapper);
                scrollToBottom();
            };

            const sendMessage = async (messageText) => {
                if (!messageText || !messageText.trim()) return;

                const query = messageText.trim();
                const now = new Date();
                const timeString = `${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')}`;

                appendMessage('user', query, timeString);
                input.value = '';
                input.focus();

                // Loading typing indicator
                const loadingId = 'loading-bubble-' + Date.now();
                const loadingWrapper = document.createElement('div');
                loadingWrapper.id = loadingId;
                loadingWrapper.className = 'flex items-start gap-3';
                loadingWrapper.innerHTML = `
                    <div class="flex size-8 shrink-0 items-center justify-center rounded-lg bg-primary/10 text-primary">
                        <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 8V4H8"/><rect width="16" height="12" x="4" y="8" rx="2"/><path d="M2 14h2"/><path d="M20 14h2"/>
                        </svg>
                    </div>
                    <div class="rounded-2xl rounded-tl-sm border border-line bg-subtle p-3 text-xs text-muted flex items-center gap-1.5">
                        <span class="size-2 rounded-full bg-primary/40 animate-ping"></span>
                        <span>MNCU Virtual Assistant sedang mengetik...</span>
                    </div>
                `;
                messagesContainer.appendChild(loadingWrapper);
                scrollToBottom();

                try {
                    const response = await fetch("{{ route('chat') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ message: query })
                    });

                    const data = await response.json();
                    document.getElementById(loadingId)?.remove();
                    appendMessage('bot', data.reply || 'Mohon maaf, terjadi kendala saat memproses jawaban.', data.time || timeString);
                } catch (error) {
                    document.getElementById(loadingId)?.remove();
                    appendMessage('bot', 'Mohon maaf, terjadi kendala koneksi ke server. Silakan coba kembali sesaat lagi.', timeString);
                }
            };

            form.addEventListener('submit', (e) => {
                e.preventDefault();
                sendMessage(input.value);
            });

            // Delegasi click untuk chip prompt cepat di sidebar dan dalam bubble pesan
            document.addEventListener('click', (e) => {
                const targetChip = e.target.closest('.quick-chip, .inline-chip');
                if (targetChip) {
                    const query = targetChip.getAttribute('data-query');
                    if (query) {
                        sendMessage(query);
                    }
                }
            });

            // Filter Kategori Sidebar
            categoryBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    const selectedCat = btn.getAttribute('data-category');
                    
                    categoryBtns.forEach(b => {
                        b.className = 'cat-filter-btn rounded-lg border border-line bg-subtle/70 px-2.5 py-1 text-[11px] font-medium text-ink transition-colors hover:border-primary/40 hover:text-primary';
                    });
                    btn.className = 'cat-filter-btn rounded-lg bg-primary px-2.5 py-1 text-[11px] font-medium text-white transition-colors';

                    quickChips.forEach(chip => {
                        const chipCat = chip.getAttribute('data-category');
                        if (selectedCat === 'all' || chipCat === selectedCat) {
                            chip.style.display = 'flex';
                        } else {
                            chip.style.display = 'none';
                        }
                    });
                });
            });

            // Clear chat handler
            clearBtn.addEventListener('click', () => {
                if (confirm('Kosongkan riwayat percakapan?')) {
                    const welcomeBlock = document.getElementById('welcome-message-block');
                    if (welcomeBlock) {
                        messagesContainer.innerHTML = '';
                        messagesContainer.appendChild(welcomeBlock);
                    } else {
                        messagesContainer.innerHTML = '';
                        appendMessage('bot', 'Riwayat percakapan telah dibersihkan. Silakan tanyakan hal seputar layanan perpustakaan MNC University yang Anda butuhkan.', 'Baru saja');
                    }
                }
            });

            // Auto-send jika ada parameter query dari halaman lain (seperti OPAC atau E-Journal)
            const urlParams = new URLSearchParams(window.location.search);
            const initialQuery = urlParams.get('q') || urlParams.get('query');
            if (initialQuery && initialQuery.trim()) {
                input.value = initialQuery.trim();
                sendMessage(initialQuery.trim());
            }
        });
    </script>
@endsection
