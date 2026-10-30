<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk ke Akun | Perpustakaan MNC University</title>
    <meta name="description" content="Masuk ke portal perpustakaan MNC University untuk mengakses katalog OPAC, repository skripsi, e-journal, dan koleksi e-book digital.">
    <link rel="icon" type="image/png" href="{{ asset('images/Logo.png') }}">

    @fonts
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#FDFBF7] font-sans text-ink antialiased">
    {{-- Loading Screen & Top Progress Bar --}}
    @include('partials.loading-screen')

    <div class="grid min-h-screen lg:grid-cols-2 page-transition-wrapper">
        {{-- Sisi Kiri: Formulir Login --}}
        <div class="flex flex-col justify-between bg-white px-6 py-8 sm:px-12 md:px-16 lg:px-14 xl:px-20">
            {{-- Header Atas: Brand & Tombol Kembali --}}
            <div class="flex items-center justify-between">
                <a href="{{ route('beranda') }}" class="inline-flex items-center gap-3 transition-opacity hover:opacity-85" title="Kembali ke Beranda">
                    <img src="{{ asset('images/Logo.png') }}" alt="Logo MNC Library" class="h-9 max-w-[120px] w-auto object-contain" style="max-height: 36px; max-width: 120px;" width="120" height="36">
                    <div class="flex flex-col">
                        <span class="text-base font-semibold tracking-tight text-primary">MNC Library</span>
                        <span class="text-[11px] text-muted">Perpustakaan MNC University</span>
                    </div>
                </a>

                <a href="{{ route('beranda') }}" class="inline-flex items-center gap-1.5 text-xs font-medium text-muted transition-colors hover:text-primary">
                    <i class="fa-solid fa-arrow-left text-[11px]"></i>
                    <span>Beranda</span>
                </a>
            </div>

            {{-- Konten Utama Formulir --}}
            <div class="mx-auto my-auto w-full max-w-[420px] py-8">
                <div class="text-center sm:text-left">
                    <h1 class="text-2xl font-bold tracking-tight text-ink sm:text-3xl">
                        Masuk ke Akun
                    </h1>
                    <p class="mt-2 text-sm text-ink-secondary">
                        Selamat datang kembali! Silakan masukkan email dan kata sandi Anda untuk melanjutkan.
                    </p>
                </div>

                {{-- Alert Status / Notifikasi --}}
                @if (session('status'))
                    <div class="mt-5 flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-xs font-medium text-emerald-800">
                        <i class="fa-solid fa-circle-check mt-0.5 text-sm text-emerald-600"></i>
                        <div class="flex-1 leading-relaxed">{{ session('status') }}</div>
                    </div>
                @endif

                @if (session('info'))
                    <div class="mt-5 flex items-start gap-3 rounded-xl border border-blue-200 bg-blue-50 p-4 text-xs font-medium text-blue-800">
                        <i class="fa-solid fa-circle-info mt-0.5 text-sm text-blue-600"></i>
                        <div class="flex-1 leading-relaxed">{{ session('info') }}</div>
                    </div>
                @endif

                {{-- Flash Bantuan Khusus Password Baru dari Reset Email --}}
                @if (session('demo_new_password'))
                    <div class="mt-5 rounded-xl border-2 border-dashed border-[#EA580C] bg-[#FFF7ED] p-4 text-xs">
                        <div class="flex items-center justify-between font-semibold text-[#C2410C]">
                            <span class="inline-flex items-center gap-1.5">
                                <i class="fa-solid fa-key"></i>
                                Kata Sandi Baru Dihasilkan!
                            </span>
                            <span class="rounded bg-[#EA580C] px-1.5 py-0.5 text-[10px] text-white">Email Terkirim</span>
                        </div>
                        <p class="mt-1.5 text-ink-secondary leading-relaxed">
                            Email reset telah dikirim ke <strong>{{ session('demo_email') }}</strong>. Kata sandi baru Anda:
                        </p>
                        <div class="mt-2 flex items-center justify-between rounded-lg bg-white p-2.5 border border-[#FDBA74]">
                            <code id="new-password-display" class="font-mono text-sm font-bold text-primary">{{ session('demo_new_password') }}</code>
                            <button
                                type="button"
                                id="btn-use-reset-pass"
                                class="rounded bg-primary px-2.5 py-1 text-[11px] font-semibold text-white transition-colors hover:bg-primary-dark"
                            >
                                Pasang & Masuk
                            </button>
                        </div>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mt-5 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 p-4 text-xs font-medium text-red-800">
                        <i class="fa-solid fa-triangle-exclamation mt-0.5 text-sm text-red-600"></i>
                        <div class="flex-1 leading-relaxed">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Pilihan Peran & Tombol Auto-Fill Super Admin (Sesuai Referensi Gambar) --}}
                <div class="mt-6 flex flex-col gap-3">
                    <div class="flex items-center justify-center gap-6 text-xs font-medium text-ink-secondary">
                        <label class="inline-flex cursor-pointer items-center gap-2">
                            <input type="radio" name="role_selector" value="user" id="role-user" class="size-4 text-primary accent-primary" checked>
                            <span>Sebagai Pemustaka</span>
                        </label>
                        <label class="inline-flex cursor-pointer items-center gap-2">
                            <input type="radio" name="role_selector" value="superadmin" id="role-superadmin" class="size-4 text-primary accent-primary">
                            <span>Sebagai Super Admin</span>
                        </label>
                    </div>

                    {{-- Tombol Cepat Otomatis Isi Akun Super Admin --}}
                    <div class="rounded-xl border border-line bg-[#F8FAFC] p-3 text-center">
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-xs text-muted">Demo Kredensial Cepat:</span>
                            <button
                                type="button"
                                id="btn-autofill-admin"
                                class="inline-flex items-center gap-1.5 rounded-lg bg-primary/10 px-3 py-1.5 text-xs font-semibold text-primary transition-all hover:bg-primary hover:text-white"
                                title="Klik untuk otomatis mengisi email & kata sandi Kepala Perpustakaan"
                            >
                                <i class="fa-solid fa-bolt text-[11px]"></i>
                                <span>Isi Otomatis Super Admin</span>
                            </button>
                        </div>
                        <div class="mt-2 flex items-center justify-center gap-2 text-[11px] text-muted">
                            <span>Atau:</span>
                            <button type="button" class="btn-demo-fill text-primary underline hover:text-primary-dark" data-email="sarah.amalia@mncu.ac.id" data-pass="password123">
                                Pustakawan
                            </button>
                            <span>•</span>
                            <button type="button" class="btn-demo-fill text-primary underline hover:text-primary-dark" data-email="fauzi.mhs@mncu.ac.id" data-pass="password123">
                                Mahasiswa
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Formulir Utama --}}
                <form action="{{ route('login.post') }}" method="POST" class="mt-6 space-y-4" id="login-form">
                    @csrf

                    {{-- Input Email --}}
                    <div>
                        <label for="email" class="block text-xs font-semibold text-ink">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <div class="relative mt-1.5">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted">
                                <i class="fa-regular fa-envelope text-xs"></i>
                            </span>
                            <input
                                type="email"
                                name="email"
                                id="email"
                                value="{{ old('email', session('demo_email', '')) }}"
                                required
                                autocomplete="email"
                                placeholder="nama@mncu.ac.id"
                                class="block h-11 w-full rounded-xl border border-line bg-white pr-4 pl-10 text-xs text-ink placeholder:text-muted/60 transition-colors focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none"
                            >
                        </div>
                    </div>

                    {{-- Input Kata Sandi --}}
                    <div>
                        <div class="flex items-center justify-between">
                            <label for="kata_sandi" class="block text-xs font-semibold text-ink">
                                Kata Sandi <span class="text-red-500">*</span>
                            </label>
                            <a href="{{ route('password.request') }}" class="text-xs font-medium text-emerald-800 hover:text-emerald-900 hover:underline">
                                Lupa kata sandi?
                            </a>
                        </div>
                        <div class="relative mt-1.5">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted">
                                <i class="fa-solid fa-lock text-xs"></i>
                            </span>
                            <input
                                type="password"
                                name="kata_sandi"
                                id="kata_sandi"
                                required
                                autocomplete="current-password"
                                placeholder="Masukkan kata sandi akun Anda"
                                class="block h-11 w-full rounded-xl border border-line bg-white pr-10 pl-10 text-xs text-ink placeholder:text-muted/60 transition-colors focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none"
                            >
                            <button
                                type="button"
                                id="toggle-password"
                                class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-muted hover:text-ink focus:outline-none"
                                aria-label="Lihat atau sembunyikan kata sandi"
                            >
                                <i class="fa-regular fa-eye text-xs" id="toggle-icon"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Checkbox Ingat Saya --}}
                    <div class="flex items-center justify-between pt-1">
                        <label class="flex cursor-pointer items-center gap-2">
                            <input
                                type="checkbox"
                                name="remember"
                                id="remember"
                                class="size-4 rounded border-line text-primary accent-primary focus:ring-primary"
                                {{ old('remember') ? 'checked' : '' }}
                            >
                            <span class="text-xs text-ink-secondary">Ingat saya di perangkat ini</span>
                        </label>
                    </div>

                    {{-- Tombol Submit Masuk --}}
                    <div class="pt-2">
                        <button
                            type="submit"
                            id="btn-submit-login"
                            class="flex h-11 w-full items-center justify-center rounded-xl bg-primary text-sm font-semibold text-white shadow-md transition-all hover:bg-primary-dark btn-press btn-sheen focus:ring-2 focus:ring-primary focus:ring-offset-2 focus:outline-none"
                        >
                            <span>Masuk Sekarang</span>
                        </button>
                    </div>
                </form>

                {{-- Tautan Bawah ke Registrasi & Bantuan --}}
                <div class="mt-6 flex flex-col items-center justify-center gap-2 text-center text-xs text-ink-secondary">
                    <div>
                        <span>Belum memiliki akun terdaftar?</span>
                        <a href="{{ route('register') }}" class="font-semibold text-primary hover:underline">
                            Daftar Akun Baru
                        </a>
                    </div>
                    <div>
                        <span>Butuh bantuan akses?</span>
                        <a href="{{ route('chat') }}" class="text-muted hover:text-ink hover:underline">
                            Tanya Pustakawan
                        </a>
                    </div>
                </div>
            </div>

            {{-- Footer Bawah Sisi Kiri --}}
            <div class="text-center text-[11px] text-muted lg:text-left">
                © {{ date('Y') }} Perpustakaan MNC University. Hak Cipta Dilindungi.
            </div>
        </div>

        {{-- Sisi Kanan: Quotes Inspiratif & Ilustrasi Buku-Buku Estetik (Pengganti Gedung) --}}
        <div class="relative hidden flex-col justify-between overflow-hidden bg-[#F6F4ED] p-10 lg:flex lg:p-14 xl:p-16">
            {{-- Elemen Dekorasi Latar Belakang --}}
            <div class="pointer-events-none absolute -top-24 -right-24 size-96 rounded-full bg-amber-100/50 blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-24 -left-24 size-96 rounded-full bg-blue-100/40 blur-3xl"></div>

            {{-- Bagian Atas: Quotes Inspiratif Literasi & Profil Kepala Perpustakaan --}}
            <div class="relative z-10 max-w-lg">
                {{-- Simbol Kutipan Oranye Besar (Sesuai Referensi Gambar) --}}
                <div class="text-[#EA580C]">
                    <svg class="size-10 fill-current opacity-90" viewBox="0 0 24 24">
                        <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
                    </svg>
                </div>

                <blockquote class="mt-4 text-xl font-medium tracking-tight text-ink sm:text-2xl lg:text-[23px] lg:leading-snug">
                    "Membaca adalah jembatan emas menuju peradaban dan kemandirian berpikir. Buku-buku di perpustakaan ini adalah lentera yang siap menerangi setiap langkah riset, inovasi, dan impian sivitas akademika MNC University."
                </blockquote>

                {{-- Profil Penulis Quote --}}
                <div class="mt-6 flex items-center gap-3.5">
                    <img
                        src="{{ asset('images/pustakawan/pustakawan-01.jpg') }}"
                        alt="Dr. H. Muhammad Arifin, M.Hum."
                        class="size-11 rounded-full border-2 border-white object-cover shadow-sm"
                        onerror="this.onerror=null; this.src='{{ asset('images/Logo.png') }}';"
                    >
                    <div>
                        <div class="text-sm font-semibold text-ink">Dr. H. Muhammad Arifin, M.Hum.</div>
                        <div class="text-xs text-muted">Kepala Perpustakaan MNC University</div>
                    </div>
                </div>
            </div>

            {{-- Bagian Bawah: Ilustrasi Buku-Buku & Rak Perpustakaan (Menggantikan Ilustrasi Gedung) --}}
            <div class="relative z-10 mt-auto pt-8">
                <svg
                    class="w-full max-h-[380px] drop-shadow-sm"
                    viewBox="0 0 760 480"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                    aria-label="Ilustrasi Buku-buku dan Rak Perpustakaan Modern"
                >
                    <!-- Garis Lantai & Perspektif Ruang Baca -->
                    <line x1="20" y1="440" x2="740" y2="440" stroke="#0F2A6B" stroke-width="3" stroke-linecap="round"/>
                    <line x1="60" y1="455" x2="700" y2="455" stroke="#CBD5E1" stroke-width="1.5" stroke-dasharray="6 6"/>

                    <!-- POHON / TANAMAN HIAS ESTETIK RUANG BACA (KIRI) -->
                    <path d="M70 440 Q70 410 70 380" stroke="#0F2A6B" stroke-width="2.5"/>
                    <path d="M70 380 Q45 350 70 320 Q95 350 70 380" fill="#E2E8F0" stroke="#0F2A6B" stroke-width="2.5"/>
                    <path d="M55 440 L85 440 L80 410 L60 410 Z" fill="#FED7AA" stroke="#0F2A6B" stroke-width="2.5"/>

                    <!-- TOWER RAK BUKU 1 (KIRI TINGGI BERGARIS SEPERTI GEDUNG BUKU) -->
                    <g transform="translate(110, 80)">
                        <!-- Badan Rak Vertikal Bersegi -->
                        <rect x="0" y="0" width="130" height="360" rx="6" fill="#FFFFFF" stroke="#0F2A6B" stroke-width="3"/>
                        <!-- Atap Kanopi Rak -->
                        <polygon points="0,0 65,-25 130,0" fill="#E2E8F0" stroke="#0F2A6B" stroke-width="3"/>

                        <!-- Jajaran Buku-Buku Berdiri di Rak 1 -->
                        <!-- Baris 1: Buku Vertikal -->
                        <rect x="15" y="25" width="14" height="65" rx="2" fill="#0F2A6B"/>
                        <rect x="33" y="20" width="16" height="70" rx="2" fill="#FED7AA" stroke="#0F2A6B" stroke-width="1.5"/>
                        <rect x="53" y="30" width="12" height="60" rx="2" fill="#94A3B8"/>
                        <rect x="69" y="15" width="18" height="75" rx="2" fill="#FDBA74" stroke="#0F2A6B" stroke-width="1.5"/>
                        <rect x="91" y="22" width="15" height="68" rx="2" fill="#0F2A6B"/>
                        <line x1="10" y1="95" x2="120" y2="95" stroke="#0F2A6B" stroke-width="2.5"/>

                        <!-- Baris 2: Buku Vertikal & Miring -->
                        <rect x="15" y="120" width="15" height="70" rx="2" fill="#93C5FD" stroke="#0F2A6B" stroke-width="1.5"/>
                        <rect x="34" y="115" width="18" height="75" rx="2" fill="#0F2A6B"/>
                        <rect x="56" y="125" width="14" height="65" rx="2" fill="#FED7AA"/>
                        <!-- Buku Miring -->
                        <g transform="translate(80, 125) rotate(15)">
                            <rect x="0" y="0" width="15" height="70" rx="2" fill="#EA580C" stroke="#0F2A6B" stroke-width="1.5"/>
                        </g>
                        <line x1="10" y1="195" x2="120" y2="195" stroke="#0F2A6B" stroke-width="2.5"/>

                        <!-- Baris 3: Tumpukan Buku Horizontal -->
                        <rect x="25" y="255" width="80" height="15" rx="2" fill="#F8FAFC" stroke="#0F2A6B" stroke-width="2"/>
                        <rect x="20" y="240" width="90" height="15" rx="2" fill="#FED7AA" stroke="#0F2A6B" stroke-width="2"/>
                        <rect x="28" y="225" width="75" height="15" rx="2" fill="#0F2A6B" stroke="#0F2A6B" stroke-width="2"/>
                        <line x1="10" y1="275" x2="120" y2="275" stroke="#0F2A6B" stroke-width="2.5"/>

                        <!-- Baris 4: Arsip & Dokumen Jurnal -->
                        <rect x="18" y="295" width="18" height="55" rx="2" fill="#0F2A6B"/>
                        <rect x="40" y="290" width="18" height="60" rx="2" fill="#CBD5E1" stroke="#0F2A6B" stroke-width="1.5"/>
                        <rect x="62" y="298" width="18" height="52" rx="2" fill="#FED7AA" stroke="#0F2A6B" stroke-width="1.5"/>
                        <rect x="84" y="292" width="22" height="58" rx="2" fill="#38BDF8" stroke="#0F2A6B" stroke-width="1.5"/>
                        <line x1="10" y1="355" x2="120" y2="355" stroke="#0F2A6B" stroke-width="2.5"/>
                    </g>

                    <!-- TOWER RAK BUKU 2 (TENGAH TINGGI MONUMENTAL) -->
                    <g transform="translate(265, 30)">
                        <!-- Struktur Menara Buku Utama -->
                        <rect x="0" y="0" width="170" height="410" rx="8" fill="#FFFFFF" stroke="#0F2A6B" stroke-width="3"/>
                        <polygon points="0,0 85,-30 170,0" fill="#FED7AA" stroke="#0F2A6B" stroke-width="3"/>

                        <!-- Garis Pembagi Rak Buku Modern -->
                        <line x1="12" y1="80" x2="158" y2="80" stroke="#0F2A6B" stroke-width="2.5"/>
                        <line x1="12" y1="165" x2="158" y2="165" stroke="#0F2A6B" stroke-width="2.5"/>
                        <line x1="12" y1="250" x2="158" y2="250" stroke="#0F2A6B" stroke-width="2.5"/>
                        <line x1="12" y1="335" x2="158" y2="335" stroke="#0F2A6B" stroke-width="2.5"/>

                        <!-- Koleksi Ensiklopedia & Buku Tebal (Lantai 1) -->
                        <rect x="20" y="15" width="20" height="60" rx="2" fill="#0F2A6B"/>
                        <rect x="44" y="10" width="22" height="65" rx="2" fill="#EA580C" stroke="#0F2A6B" stroke-width="1.5"/>
                        <rect x="70" y="18" width="18" height="57" rx="2" fill="#F8FAFC" stroke="#0F2A6B" stroke-width="1.5"/>
                        <rect x="92" y="12" width="24" height="63" rx="2" fill="#38BDF8" stroke="#0F2A6B" stroke-width="1.5"/>
                        <rect x="120" y="20" width="20" height="55" rx="2" fill="#0F2A6B"/>

                        <!-- Koleksi Jurnal Digital & Monograf (Lantai 2) -->
                        <rect x="22" y="100" width="16" height="60" rx="2" fill="#CBD5E1" stroke="#0F2A6B" stroke-width="1.5"/>
                        <rect x="42" y="95" width="20" height="65" rx="2" fill="#FED7AA" stroke="#0F2A6B" stroke-width="1.5"/>
                        <rect x="66" y="102" width="18" height="58" rx="2" fill="#0F2A6B"/>
                        <rect x="88" y="92" width="22" height="68" rx="2" fill="#EA580C"/>
                        <rect x="114" y="98" width="24" height="62" rx="2" fill="#93C5FD" stroke="#0F2A6B" stroke-width="1.5"/>

                        <!-- Jajaran Buku Pengetahuan (Lantai 3) -->
                        <rect x="25" y="185" width="22" height="60" rx="2" fill="#0F2A6B"/>
                        <rect x="51" y="178" width="20" height="67" rx="2" fill="#FED7AA" stroke="#0F2A6B" stroke-width="1.5"/>
                        <rect x="75" y="190" width="16" height="55" rx="2" fill="#F8FAFC" stroke="#0F2A6B" stroke-width="1.5"/>
                        <g transform="translate(100, 190) rotate(12)">
                            <rect x="0" y="0" width="18" height="62" rx="2" fill="#0F2A6B" stroke="#0F2A6B" stroke-width="1.5"/>
                        </g>

                        <!-- Baris Buku Bawah (Lantai 4) -->
                        <rect x="24" y="270" width="18" height="60" rx="2" fill="#EA580C" stroke="#0F2A6B" stroke-width="1.5"/>
                        <rect x="46" y="265" width="22" height="65" rx="2" fill="#0F2A6B"/>
                        <rect x="72" y="272" width="18" height="58" rx="2" fill="#CBD5E1" stroke="#0F2A6B" stroke-width="1.5"/>
                        <rect x="94" y="268" width="20" height="62" rx="2" fill="#FED7AA" stroke="#0F2A6B" stroke-width="1.5"/>
                        <rect x="118" y="275" width="18" height="55" rx="2" fill="#0F2A6B"/>
                    </g>

                    <!-- TOWER RAK BUKU 3 (KANAN BESAR BERPOLA GEOMETRIS KREATIF) -->
                    <g transform="translate(465, 110)">
                        <!-- Badan Rak Kanan -->
                        <rect x="0" y="0" width="140" height="330" rx="6" fill="#FFFFFF" stroke="#0F2A6B" stroke-width="3"/>
                        <polygon points="0,0 70,-22 140,0" fill="#93C5FD" stroke="#0F2A6B" stroke-width="3"/>

                        <!-- Garis Rak Pembagi -->
                        <line x1="12" y1="85" x2="128" y2="85" stroke="#0F2A6B" stroke-width="2.5"/>
                        <line x1="12" y1="170" x2="128" y2="170" stroke="#0F2A6B" stroke-width="2.5"/>
                        <line x1="12" y1="255" x2="128" y2="255" stroke="#0F2A6B" stroke-width="2.5"/>

                        <!-- Buku Baris 1 -->
                        <rect x="20" y="22" width="18" height="58" rx="2" fill="#0F2A6B"/>
                        <rect x="42" y="16" width="20" height="64" rx="2" fill="#FED7AA" stroke="#0F2A6B" stroke-width="1.5"/>
                        <rect x="66" y="25" width="16" height="55" rx="2" fill="#EA580C"/>
                        <rect x="86" y="18" width="22" height="62" rx="2" fill="#CBD5E1" stroke="#0F2A6B" stroke-width="1.5"/>

                        <!-- Buku Baris 2 -->
                        <rect x="22" y="105" width="20" height="60" rx="2" fill="#FED7AA" stroke="#0F2A6B" stroke-width="1.5"/>
                        <rect x="46" y="100" width="18" height="65" rx="2" fill="#0F2A6B"/>
                        <rect x="68" y="108" width="22" height="57" rx="2" fill="#38BDF8" stroke="#0F2A6B" stroke-width="1.5"/>
                        <rect x="94" y="102" width="16" height="63" rx="2" fill="#F8FAFC" stroke="#0F2A6B" stroke-width="1.5"/>

                        <!-- Buku Baris 3: Tumpukan Horisontal Cantik -->
                        <rect x="25" y="235" width="85" height="16" rx="2" fill="#0F2A6B"/>
                        <rect x="20" y="218" width="95" height="17" rx="2" fill="#FED7AA" stroke="#0F2A6B" stroke-width="2"/>
                        <rect x="30" y="201" width="75" height="17" rx="2" fill="#EA580C" stroke="#0F2A6B" stroke-width="2"/>
                    </g>

                    <!-- BUKU TERBUKA (OPEN BOOK) EMAS DI TENGAH DEPAN SEBAGAI PUSAT LITERASI -->
                    <g transform="translate(290, 370)">
                        <!-- Bayangan Buku Terbuka -->
                        <ellipse cx="60" cy="55" rx="75" ry="10" fill="#0F2A6B" opacity="0.12"/>
                        <!-- Lembar Kiri Buku Terbuka -->
                        <path d="M60 45 C40 38 10 38 0 45 L0 25 C10 18 40 18 60 25 Z" fill="#FFFFFF" stroke="#0F2A6B" stroke-width="2.5"/>
                        <!-- Lembar Kanan Buku Terbuka -->
                        <path d="M60 45 C80 38 110 38 120 45 L120 25 C110 18 80 18 60 25 Z" fill="#FED7AA" stroke="#0F2A6B" stroke-width="2.5"/>
                        <!-- Tulang Tengah Buku & Pita Pembatas Merah -->
                        <line x1="60" y1="24" x2="60" y2="46" stroke="#0F2A6B" stroke-width="3"/>
                        <path d="M60 40 Q65 52 58 60" stroke="#EA580C" stroke-width="3" stroke-linecap="round"/>
                        <!-- Garis Teks Halus di Halaman Buku -->
                        <line x1="12" y1="28" x2="48" y2="28" stroke="#94A3B8" stroke-width="1.5"/>
                        <line x1="12" y1="34" x2="44" y2="34" stroke="#94A3B8" stroke-width="1.5"/>
                        <line x1="72" y1="28" x2="108" y2="28" stroke="#94A3B8" stroke-width="1.5"/>
                        <line x1="72" y1="34" x2="104" y2="34" stroke="#94A3B8" stroke-width="1.5"/>
                    </g>

                    <!-- POHON / TANAMAN HIAS ESTETIK RUANG BACA (KANAN) -->
                    <g transform="translate(640, 360)">
                        <path d="M35 80 L35 45" stroke="#0F2A6B" stroke-width="2.5"/>
                        <circle cx="35" cy="30" r="28" fill="#FED7AA" stroke="#0F2A6B" stroke-width="2.5"/>
                        <circle cx="24" cy="22" r="12" fill="#E2E8F0" stroke="#0F2A6B" stroke-width="1.5"/>
                        <polygon points="20,80 50,80 45,55 25,55" fill="#FFFFFF" stroke="#0F2A6B" stroke-width="2.5"/>
                    </g>
                </svg>
            </div>
        </div>
    </div>

    {{-- Script Interaktivitas: Show/Hide Password, Auto-Fill Super Admin, dan Demo Kredensial --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('kata_sandi');
            const togglePasswordBtn = document.getElementById('toggle-password');
            const toggleIcon = document.getElementById('toggle-icon');
            const autoFillAdminBtn = document.getElementById('btn-autofill-admin');
            const roleSuperAdminRadio = document.getElementById('role-superadmin');
            const roleUserRadio = document.getElementById('role-user');
            const demoFillButtons = document.querySelectorAll('.btn-demo-fill');
            const useResetPassBtn = document.getElementById('btn-use-reset-pass');
            const newPasswordDisplay = document.getElementById('new-password-display');

            // 1. Toggle Show / Hide Password
            if (togglePasswordBtn && passwordInput && toggleIcon) {
                togglePasswordBtn.addEventListener('click', () => {
                    const isPassword = passwordInput.getAttribute('type') === 'password';
                    passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
                    toggleIcon.classList.toggle('fa-eye', !isPassword);
                    toggleIcon.classList.toggle('fa-eye-slash', isPassword);
                });
            }

            // Fungsi Pembantu: Isi dan beri efek sorotan visual
            const applyCredentials = (email, password) => {
                emailInput.value = email;
                passwordInput.value = password;

                [emailInput, passwordInput].forEach((el) => {
                    el.classList.add('ring-2', 'ring-emerald-500', 'bg-emerald-50/50');
                    setTimeout(() => {
                        el.classList.remove('ring-2', 'ring-emerald-500', 'bg-emerald-50/50');
                    }, 1200);
                });
            };

            // 2. Tombol Otomatis Isi Akun Super Admin
            if (autoFillAdminBtn) {
                autoFillAdminBtn.addEventListener('click', () => {
                    applyCredentials('arifin@mncu.ac.id', 'password123');
                    if (roleSuperAdminRadio) roleSuperAdminRadio.checked = true;
                });
            }

            // 3. Switcher Radio Button: User vs Super Admin
            if (roleSuperAdminRadio) {
                roleSuperAdminRadio.addEventListener('change', () => {
                    if (roleSuperAdminRadio.checked) {
                        applyCredentials('arifin@mncu.ac.id', 'password123');
                    }
                });
            }
            if (roleUserRadio) {
                roleUserRadio.addEventListener('change', () => {
                    if (roleUserRadio.checked) {
                        applyCredentials('fauzi.mhs@mncu.ac.id', 'password123');
                    }
                });
            }

            // 4. Tombol Demo Kredensial Lainnya (Pustakawan & Mahasiswa)
            demoFillButtons.forEach((btn) => {
                btn.addEventListener('click', () => {
                    const email = btn.getAttribute('data-email');
                    const pass = btn.getAttribute('data-pass');
                    applyCredentials(email, pass);
                });
            });



            // 6. Jika ada kata sandi hasil reset email, pasang ke input
            if (useResetPassBtn && newPasswordDisplay) {
                useResetPassBtn.addEventListener('click', () => {
                    const pass = newPasswordDisplay.textContent.trim();
                    passwordInput.value = pass;
                    passwordInput.focus();
                    passwordInput.classList.add('ring-2', 'ring-primary');
                    setTimeout(() => passwordInput.classList.remove('ring-2', 'ring-primary'), 1000);
                });
            }
        });
    </script>
</body>
</html>
