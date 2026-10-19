<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Akun Baru | Perpustakaan MNC University</title>
    <meta name="description" content="Daftarkan akun pemustaka baru di Perpustakaan MNC University untuk mengakses peminjaman buku, katalog OPAC, e-journal, skripsi, dan koleksi digital.">
    <link rel="icon" type="image/png" href="{{ asset('images/Logo.png') }}">

    @fonts
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#FDFBF7] font-sans text-ink antialiased">
    {{-- Loading Screen & Top Progress Bar --}}
    @include('partials.loading-screen')

    <div class="grid min-h-screen lg:grid-cols-2 page-transition-wrapper">
        {{-- Sisi Kiri: Formulir Registrasi Pemustaka --}}
        <div class="flex flex-col justify-between bg-white px-6 py-8 sm:px-12 md:px-16 lg:px-14 xl:px-20">
            {{-- Header Atas: Brand & Tombol Kembali --}}
            <div class="flex items-center justify-between">
                <a href="{{ route('beranda') }}" class="inline-flex items-center gap-3 transition-opacity hover:opacity-85" title="Kembali ke Beranda">
                    <img src="{{ asset('images/Logo.png') }}" alt="Logo MNC Library" class="h-9 w-auto">
                    <div class="flex flex-col">
                        <span class="text-base font-semibold tracking-tight text-primary">MNC Library</span>
                        <span class="text-[11px] text-muted">Perpustakaan MNC University</span>
                    </div>
                </a>

                <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-primary transition-colors hover:text-primary-dark">
                    <i class="fa-solid fa-arrow-right-to-bracket text-[11px]"></i>
                    <span>Masuk</span>
                </a>
            </div>

            {{-- Konten Utama Formulir --}}
            <div class="mx-auto my-auto w-full max-w-[460px] py-8">
                <div class="text-center sm:text-left">
                    <div class="inline-flex items-center gap-2 rounded-full bg-primary/10 px-3 py-1 text-xs font-semibold text-primary">
                        <i class="fa-solid fa-user-plus text-[11px]"></i>
                        <span>Keanggotaan Pemustaka</span>
                    </div>
                    <h1 class="mt-3 text-2xl font-bold tracking-tight text-ink sm:text-3xl">
                        Daftar Akun Baru
                    </h1>
                    <p class="mt-2 text-sm text-ink-secondary">
                        Lengkapi identitas Anda untuk mendapatkan kartu keanggotaan digital dan ID resmi Perpustakaan MNC University.
                    </p>
                </div>

                {{-- Alert Pesan Validasi / Error --}}
                @if ($errors->any())
                    <div class="mt-5 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 p-4 text-xs font-medium text-red-800">
                        <i class="fa-solid fa-triangle-exclamation mt-0.5 text-sm text-red-600"></i>
                        <div class="flex-1 leading-relaxed">
                            <p class="font-semibold text-red-900 mb-1">Terdapat beberapa kesalahan input:</p>
                            <ul class="list-disc pl-4 space-y-0.5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                {{-- Formulir Registrasi --}}
                <form action="{{ route('register.post') }}" method="POST" class="mt-6 space-y-4" id="register-form">
                    @csrf

                    {{-- Nama Lengkap --}}
                    <div>
                        <label for="nama_lengkap" class="block text-xs font-semibold text-ink">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <div class="relative mt-1.5">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted">
                                <i class="fa-regular fa-user text-xs"></i>
                            </span>
                            <input
                                type="text"
                                name="nama_lengkap"
                                id="nama_lengkap"
                                value="{{ old('nama_lengkap') }}"
                                required
                                autocomplete="name"
                                placeholder="cth: Muhammad Faris"
                                class="block h-11 w-full rounded-xl border border-line bg-white pr-4 pl-10 text-xs text-ink placeholder:text-muted/60 transition-colors focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none @error('nama_lengkap') border-red-500 @enderror"
                            >
                        </div>
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-xs font-semibold text-ink">
                            Alamat Email <span class="text-red-500">*</span>
                        </label>
                        <div class="relative mt-1.5">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted">
                                <i class="fa-regular fa-envelope text-xs"></i>
                            </span>
                            <input
                                type="email"
                                name="email"
                                id="email"
                                value="{{ old('email') }}"
                                required
                                autocomplete="email"
                                placeholder="cth: mahasiswa@mncu.ac.id"
                                class="block h-11 w-full rounded-xl border border-line bg-white pr-4 pl-10 text-xs text-ink placeholder:text-muted/60 transition-colors focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none @error('email') border-red-500 @enderror"
                            >
                        </div>
                        <p class="mt-1 text-[11px] text-muted">Disarankan menggunakan email kampus MNC University jika tersedia.</p>
                    </div>

                    {{-- Grid 2 Kolom: Nomor Identitas (NIM) & Program Studi --}}
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label for="nomor_identitas" class="block text-xs font-semibold text-ink">
                                NIM / NIDN <span class="text-muted font-normal">(Opsional)</span>
                            </label>
                            <div class="relative mt-1.5">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted">
                                    <i class="fa-solid fa-id-card text-xs"></i>
                                </span>
                                <input
                                    type="text"
                                    name="nomor_identitas"
                                    id="nomor_identitas"
                                    value="{{ old('nomor_identitas') }}"
                                    placeholder="cth: 202401001"
                                    class="block h-11 w-full rounded-xl border border-line bg-white pr-4 pl-10 text-xs text-ink placeholder:text-muted/60 transition-colors focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none @error('nomor_identitas') border-red-500 @enderror"
                                >
                            </div>
                        </div>

                        <div>
                            <label for="id_prodi" class="block text-xs font-semibold text-ink">
                                Program Studi <span class="text-muted font-normal">(Opsional)</span>
                            </label>
                            <div class="relative mt-1.5">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted">
                                    <i class="fa-solid fa-graduation-cap text-xs"></i>
                                </span>
                                <select
                                    name="id_prodi"
                                    id="id_prodi"
                                    class="block h-11 w-full rounded-xl border border-line bg-white pr-8 pl-10 text-xs text-ink transition-colors focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none"
                                >
                                    <option value="">Pilih Program Studi...</option>
                                    @foreach ($prodis as $prodi)
                                        <option value="{{ $prodi->id_prodi }}" {{ old('id_prodi') === $prodi->id_prodi ? 'selected' : '' }}>
                                            {{ $prodi->nama_prodi }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Grid 2 Kolom: Kata Sandi & Konfirmasi Kata Sandi --}}
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label for="kata_sandi" class="block text-xs font-semibold text-ink">
                                Kata Sandi <span class="text-red-500">*</span>
                            </label>
                            <div class="relative mt-1.5">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted">
                                    <i class="fa-solid fa-lock text-xs"></i>
                                </span>
                                <input
                                    type="password"
                                    name="kata_sandi"
                                    id="kata_sandi"
                                    required
                                    placeholder="Min. 6 karakter"
                                    class="block h-11 w-full rounded-xl border border-line bg-white pr-10 pl-10 text-xs text-ink placeholder:text-muted/60 transition-colors focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none @error('kata_sandi') border-red-500 @enderror"
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

                        <div>
                            <label for="kata_sandi_confirmation" class="block text-xs font-semibold text-ink">
                                Konfirmasi Kata Sandi <span class="text-red-500">*</span>
                            </label>
                            <div class="relative mt-1.5">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted">
                                    <i class="fa-solid fa-shield-halved text-xs"></i>
                                </span>
                                <input
                                    type="password"
                                    name="kata_sandi_confirmation"
                                    id="kata_sandi_confirmation"
                                    required
                                    placeholder="Ulangi kata sandi"
                                    class="block h-11 w-full rounded-xl border border-line bg-white pr-4 pl-10 text-xs text-ink placeholder:text-muted/60 transition-colors focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none"
                                >
                            </div>
                        </div>
                    </div>

                    {{-- Persetujuan Syarat & Ketentuan --}}
                    <div class="pt-2">
                        <label class="flex cursor-pointer items-start gap-2.5">
                            <input
                                type="checkbox"
                                name="terms"
                                id="terms"
                                value="1"
                                class="mt-0.5 size-4 rounded border-line text-primary accent-primary focus:ring-primary"
                                {{ old('terms') ? 'checked' : '' }}
                                required
                            >
                            <span class="text-xs text-ink-secondary leading-relaxed">
                                Saya menyatakan data di atas benar dan menyetujui seluruh <a href="{{ route('tentang-kami') }}" target="_blank" class="font-semibold text-primary hover:underline">tata tertib perpustakaan</a> MNC University.
                            </span>
                        </label>
                    </div>

                    {{-- Tombol Submit Registrasi --}}
                    <div class="pt-3">
                        <button
                            type="submit"
                            id="btn-submit-register"
                            class="flex h-11 w-full items-center justify-center gap-2 rounded-xl bg-primary text-sm font-semibold text-white shadow-md transition-all hover:bg-primary-dark btn-press btn-sheen focus:ring-2 focus:ring-primary focus:ring-offset-2 focus:outline-none"
                        >
                            <i class="fa-solid fa-user-plus text-xs"></i>
                            <span>Daftar Akun Sekarang</span>
                        </button>
                    </div>
                </form>

                {{-- Tautan Bawah ke Login --}}
                <div class="mt-6 flex flex-col items-center justify-center gap-2 text-center text-xs text-ink-secondary">
                    <div>
                        <span>Sudah memiliki akun terdaftar?</span>
                        <a href="{{ route('login') }}" class="font-semibold text-primary hover:underline">
                            Masuk Sekarang
                        </a>
                    </div>
                    <div>
                        <span>Kendala pendaftaran?</span>
                        <a href="{{ route('chat') }}" class="text-muted hover:text-ink hover:underline">
                            Hubungi Pustakawan
                        </a>
                    </div>
                </div>
            </div>

            {{-- Footer Bawah Sisi Kiri --}}
            <div class="text-center text-[11px] text-muted lg:text-left">
                © {{ date('Y') }} Perpustakaan MNC University. Hak Cipta Dilindungi.
            </div>
        </div>

        {{-- Sisi Kanan: Quotes Inspiratif Literasi & Ilustrasi Ruang Baca Perpustakaan --}}
        <div class="relative hidden flex-col justify-between overflow-hidden bg-[#F6F4ED] p-10 lg:flex lg:p-14 xl:p-16">
            {{-- Elemen Dekorasi Latar Belakang --}}
            <div class="pointer-events-none absolute -top-24 -right-24 size-96 rounded-full bg-blue-100/40 blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-24 -left-24 size-96 rounded-full bg-amber-100/50 blur-3xl"></div>

            {{-- Bagian Atas: Quotes Inspiratif & Profil Pustaka --}}
            <div class="relative z-10 max-w-lg">
                <span class="inline-flex items-center gap-2 rounded-full bg-white/80 px-3 py-1 text-xs font-semibold text-primary backdrop-blur-sm shadow-sm border border-line/60">
                    <i class="fa-solid fa-book-open-reader text-xs"></i>
                    <span>Gerakan Literasi MNCU</span>
                </span>

                <blockquote class="mt-6 font-serif text-xl font-medium leading-relaxed text-ink md:text-2xl">
                    "Membaca adalah jembatan emas menuju masa depan. Perpustakaan bukan sekadar gudang buku, melainkan ruang bertumbuh bagi lahirnya pemikiran-pemikiran cemerlang."
                </blockquote>

                <div class="mt-6 flex items-center gap-4">
                    <div class="flex size-11 items-center justify-center rounded-full bg-primary font-bold text-white shadow-sm ring-2 ring-white">
                        <i class="fa-solid fa-award text-sm"></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-ink">Keanggotaan Terintegrasi</p>
                        <p class="text-xs text-muted">ID MNCU Otomatis • Akses OPAC, E-Journal & Repository</p>
                    </div>
                </div>
            </div>

            {{-- Bagian Tengah: Kartu Keuntungan Anggota Perpustakaan --}}
            <div class="relative z-10 my-6 grid grid-cols-2 gap-3 max-w-lg">
                <div class="rounded-xl border border-line/80 bg-white/85 p-3.5 shadow-sm backdrop-blur-sm">
                    <div class="flex items-center gap-2.5">
                        <div class="flex size-8 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                            <i class="fa-solid fa-book-bookmark text-xs"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-ink">Akses Koleksi</p>
                            <p class="text-[11px] text-muted">Bebas pinjam buku fisik & digital</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-line/80 bg-white/85 p-3.5 shadow-sm backdrop-blur-sm">
                    <div class="flex items-center gap-2.5">
                        <div class="flex size-8 shrink-0 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600">
                            <i class="fa-solid fa-newspaper text-xs"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-ink">Jurnal & Karya Ilmiah</p>
                            <p class="text-[11px] text-muted">Unduh ribuan e-journal & skripsi</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bagian Bawah: Ilustrasi Estetik Rak Buku MNC Library --}}
            <div class="relative z-10 mt-auto flex items-center justify-center">
                <svg viewBox="0 0 800 500" class="w-full max-w-[620px] drop-shadow-md" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <!-- LANTAI RUANG PERPUSTAKAAN -->
                    <rect x="40" y="440" width="720" height="12" rx="6" fill="#0F2A6B"/>
                    <rect x="60" y="448" width="680" height="4" rx="2" fill="#CBD5E1"/>

                    <!-- MEJA BACA BELAJAR SIVITAS AKADEMIKA -->
                    <rect x="180" y="370" width="440" height="14" rx="4" fill="#0F2A6B"/>
                    <rect x="220" y="384" width="16" height="56" fill="#0F2A6B"/>
                    <rect x="564" y="384" width="16" height="56" fill="#0F2A6B"/>

                    <!-- LAPTOP MAHASISWA & BUKU TERBUKA -->
                    <path d="M280 370 L340 370 L335 340 L285 340 Z" fill="#E2E8F0" stroke="#0F2A6B" stroke-width="2"/>
                    <rect x="270" y="367" width="80" height="5" rx="2" fill="#0F2A6B"/>
                    <circle cx="310" cy="355" r="5" fill="#3B82F6"/>

                    <!-- BUKU TERBUKA DI MEJA -->
                    <path d="M400 370 Q430 355 460 370 Q490 355 520 370 L515 365 Q490 350 460 365 Q430 350 405 365 Z" fill="#FFFFFF" stroke="#0F2A6B" stroke-width="2"/>

                    <!-- LAMPU BACA ESTETIK DI MEJA -->
                    <path d="M480 370 L480 300 Q480 280 460 280 L445 280" stroke="#0F2A6B" stroke-width="3" fill="none"/>
                    <path d="M435 272 L455 288 L430 295 Z" fill="#F59E0B" stroke="#0F2A6B" stroke-width="2"/>
                    <circle cx="435" cy="295" r="14" fill="#FEF3C7" fill-opacity="0.6"/>

                    <!-- RAK BUKU BESAR DI BELAKANG (KIRI) -->
                    <rect x="80" y="120" width="150" height="320" rx="6" fill="#FFFFFF" stroke="#0F2A6B" stroke-width="3"/>
                    <line x1="80" y1="190" x2="230" y2="190" stroke="#0F2A6B" stroke-width="3"/>
                    <line x1="80" y1="260" x2="230" y2="260" stroke="#0F2A6B" stroke-width="3"/>
                    <line x1="80" y1="330" x2="230" y2="330" stroke="#0F2A6B" stroke-width="3"/>
                    <line x1="80" y1="400" x2="230" y2="400" stroke="#0F2A6B" stroke-width="3"/>

                    <!-- BUKU-BUKU DI RAK KIRI -->
                    <rect x="92" y="135" width="16" height="55" rx="2" fill="#0F2A6B"/>
                    <rect x="112" y="142" width="14" height="48" rx="2" fill="#F59E0B"/>
                    <rect x="130" y="130" width="18" height="60" rx="2" fill="#10B981"/>
                    <rect x="152" y="146" width="14" height="44" rx="2" fill="#E2E8F0" stroke="#0F2A6B" stroke-width="1.5"/>
                    <rect x="170" y="138" width="16" height="52" rx="2" fill="#6366F1"/>
                    <path d="M190 190 L212 148 L224 154 L202 190 Z" fill="#EC4899"/>

                    <rect x="92" y="205" width="18" height="55" rx="2" fill="#3B82F6"/>
                    <rect x="114" y="212" width="16" height="48" rx="2" fill="#EF4444"/>
                    <rect x="134" y="202" width="20" height="58" rx="2" fill="#FBBF24"/>
                    <rect x="158" y="215" width="14" height="45" rx="2" fill="#0F2A6B"/>
                    <rect x="176" y="208" width="16" height="52" rx="2" fill="#14B8A6"/>

                    <!-- RAK BUKU BESAR DI BELAKANG (KANAN) -->
                    <rect x="570" y="120" width="150" height="320" rx="6" fill="#FFFFFF" stroke="#0F2A6B" stroke-width="3"/>
                    <line x1="570" y1="190" x2="720" y2="190" stroke="#0F2A6B" stroke-width="3"/>
                    <line x1="570" y1="260" x2="720" y2="260" stroke="#0F2A6B" stroke-width="3"/>
                    <line x1="570" y1="330" x2="720" y2="330" stroke="#0F2A6B" stroke-width="3"/>
                    <line x1="570" y1="400" x2="720" y2="400" stroke="#0F2A6B" stroke-width="3"/>

                    <!-- BUKU-BUKU DI RAK KANAN -->
                    <rect x="582" y="132" width="18" height="58" rx="2" fill="#10B981"/>
                    <rect x="604" y="140" width="14" height="50" rx="2" fill="#3B82F6"/>
                    <rect x="622" y="130" width="16" height="60" rx="2" fill="#0F2A6B"/>
                    <rect x="642" y="145" width="18" height="45" rx="2" fill="#F59E0B"/>
                    <rect x="664" y="136" width="16" height="54" rx="2" fill="#8B5CF6"/>

                    <!-- TANAMAN HIAS ESTETIK -->
                    <g transform="translate(650, 360)">
                        <path d="M35 80 L35 45" stroke="#0F2A6B" stroke-width="2.5"/>
                        <circle cx="35" cy="30" r="28" fill="#FED7AA" stroke="#0F2A6B" stroke-width="2.5"/>
                        <circle cx="24" cy="22" r="12" fill="#E2E8F0" stroke="#0F2A6B" stroke-width="1.5"/>
                        <polygon points="20,80 50,80 45,55 25,55" fill="#FFFFFF" stroke="#0F2A6B" stroke-width="2.5"/>
                    </g>
                </svg>
            </div>
        </div>
    </div>

    {{-- Script Interaktivitas: Show/Hide Password --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const passwordInput = document.getElementById('kata_sandi');
            const togglePasswordBtn = document.getElementById('toggle-password');
            const toggleIcon = document.getElementById('toggle-icon');

            if (togglePasswordBtn && passwordInput && toggleIcon) {
                togglePasswordBtn.addEventListener('click', () => {
                    const isPassword = passwordInput.getAttribute('type') === 'password';
                    passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
                    toggleIcon.classList.toggle('fa-eye', !isPassword);
                    toggleIcon.classList.toggle('fa-eye-slash', isPassword);
                });
            }
        });
    </script>
</body>
</html>
