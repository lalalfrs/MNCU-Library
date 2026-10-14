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
                <div class="inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-3.5 py-1.5 text-xs font-medium text-emerald-800">
                    <span class="size-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Sistem Otomatis Aktif
                </div>
            </div>
        </div>

        {{-- Main Chat Container --}}
        <div class="grid flex-1 gap-6 lg:grid-cols-[320px_1fr] xl:grid-cols-[360px_1fr]">
            {{-- Sidebar Bantuan & Topik Populer --}}
            <aside class="flex flex-col gap-5 rounded-2xl border border-line bg-white p-5 lg:p-6">
                <div>
                    <h2 class="text-base font-semibold text-ink lg:text-lg">Topik Pertanyaan Populer</h2>
                    <p class="mt-1 text-xs text-muted">Klik salah satu pertanyaan di bawah untuk langsung menanyakan:</p>
                </div>

                <div class="flex flex-col gap-2" id="quick-prompt-list">
                    @foreach ($quickPrompts as $prompt)
                        <button
                            type="button"
                            class="quick-chip group flex items-start gap-2.5 rounded-xl border border-line bg-subtle/50 p-3 text-left text-xs font-medium text-ink transition-colors duration-150 hover:border-primary/40 hover:bg-white hover:text-primary"
                            data-query="{{ $prompt }}"
                        >
                            <svg class="mt-0.5 size-4 shrink-0 text-primary transition-transform duration-150 group-hover:scale-110" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/>
                                <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
                                <path d="M12 17h.01"/>
                            </svg>
                            <span class="leading-relaxed">{{ $prompt }}</span>
                        </button>
                    @endforeach
                </div>

                <div class="mt-auto border-t border-line pt-5">
                    <div class="rounded-xl bg-primary/5 p-4">
                        <h3 class="text-xs font-semibold text-primary">Butuh Bantuan Langsung?</h3>
                        <p class="mt-1 text-xs text-ink-secondary leading-relaxed">
                            Hubungi pustakawan bertugas melalui email perpustakaan di 
                            <a href="mailto:perpustakaan@mncuniversity.ac.id" class="font-medium text-primary underline">perpustakaan@mncuniversity.ac.id</a>
                            atau kunjungi Ruang Layanan Lantai 1.
                        </p>
                    </div>
                </div>
            </aside>

            {{-- Chat Room Box --}}
            <section aria-label="Kotak Percakapan" class="flex min-h-[560px] flex-1 flex-col rounded-2xl border border-line bg-white shadow-card">
                {{-- Chat Room Header --}}
                <div class="flex items-center justify-between border-b border-line px-5 py-4">
                    <div class="flex items-center gap-3">
                        <div class="flex size-10 items-center justify-center rounded-xl bg-primary text-white">
                            <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <span class="block text-sm font-semibold text-ink">MNCU Virtual Assistant</span>
                            <span class="block text-xs text-muted">Perpustakaan MNC University</span>
                        </div>
                    </div>
                    <button
                        type="button"
                        id="clear-chat-btn"
                        class="rounded-lg px-2.5 py-1 text-xs text-muted transition-colors hover:bg-subtle hover:text-ink"
                    >
                        Hapus Percakapan
                    </button>
                </div>

                {{-- Messages Container --}}
                <div id="messages-container" class="flex-1 space-y-4 overflow-y-auto p-5" style="max-height: 520px;">
                    {{-- Bot Welcome Message --}}
                    <div class="flex items-start gap-3">
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
                        <div class="max-w-[85%] rounded-2xl rounded-tl-sm border border-line bg-subtle p-4 text-xs leading-relaxed text-ink sm:text-sm">
                            <p class="font-medium text-primary">Halo! Selamat datang di Layanan Bantuan Perpustakaan MNC University.</p>
                            <p class="mt-2 text-ink-secondary">
                                Saya asisten virtual yang dapat membantu Anda menemukan buku fisik di OPAC, prosedur peminjaman, akses jurnal online, bebas pustaka, dan jadwal operasional. Ketik pertanyaan Anda di bawah atau pilih salah satu topik di sebelah kiri.
                            </p>
                            <span class="mt-2 block text-[10px] text-muted">{{ now()->format('H:i') }}</span>
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
                            placeholder="Tulis pertanyaan Anda di sini... (misal: jam operasional, cara pinjam buku)"
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
                        Respon dihasilkan otomatis oleh sistem asisten cerdas Perpustakaan MNC University.
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
            const chips = document.querySelectorAll('.quick-chip');

            const scrollToBottom = () => {
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
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

            const escapeHtml = (unsafe) => {
                return unsafe
                    .replace(/&/g, "&amp;")
                    .replace(/</g, "&lt;")
                    .replace(/>/g, "&gt;")
                    .replace(/"/g, "&quot;")
                    .replace(/'/g, "&#039;");
            };

            const sendMessage = async (messageText) => {
                if (!messageText.trim()) return;

                const now = new Date();
                const timeString = `${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')}`;

                appendMessage('user', messageText, timeString);
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
                        <span>MNCU Assistant sedang mengetik...</span>
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
                        body: JSON.stringify({ message: messageText })
                    });

                    const data = await response.json();
                    document.getElementById(loadingId)?.remove();
                    appendMessage('bot', data.reply || 'Mohon maaf, terjadi kendala teknis saat memproses pesan.', data.time || timeString);
                } catch (error) {
                    document.getElementById(loadingId)?.remove();
                    appendMessage('bot', 'Mohon maaf, koneksi ke server sedang bermasalah. Silakan coba sesaat lagi.', timeString);
                }
            };

            form.addEventListener('submit', (e) => {
                e.preventDefault();
                sendMessage(input.value);
            });

            chips.forEach(chip => {
                chip.addEventListener('click', () => {
                    const query = chip.getAttribute('data-query');
                    if (query) {
                        sendMessage(query);
                    }
                });
            });

            clearBtn.addEventListener('click', () => {
                if (confirm('Kosongkan riwayat percakapan?')) {
                    messagesContainer.innerHTML = '';
                    appendMessage('bot', 'Riwayat percakapan dibersihkan. Ada yang bisa saya bantu kembali seputar Perpustakaan MNC University?', 'Baru saja');
                }
            });
        });
    </script>
@endsection
