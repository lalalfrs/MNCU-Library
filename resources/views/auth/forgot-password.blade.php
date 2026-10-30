<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lupa Kata Sandi | Perpustakaan MNC University</title>
    <meta name="description" content="Atur ulang kata sandi akun perpustakaan MNC University Anda secara otomatis melalui email resmi.">
    <link rel="icon" type="image/png" href="{{ asset('images/Logo.png') }}">

    @fonts
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#FDFBF7] font-sans text-ink antialiased">
    {{-- Loading Screen & Top Progress Bar --}}
    @include('partials.loading-screen')

    <div class="grid min-h-screen lg:grid-cols-2 page-transition-wrapper">
        {{-- Sisi Kiri: Formulir Lupa Kata Sandi --}}
        <div class="flex flex-col justify-between bg-white px-6 py-8 sm:px-12 md:px-16 lg:px-14 xl:px-20">
            {{-- Header Atas --}}
            <div class="flex items-center justify-between">
                <a href="{{ route('login') }}" class="inline-flex items-center gap-3 transition-opacity hover:opacity-85">
                    <img src="{{ asset('images/Logo.png') }}" alt="Logo MNC Library" class="h-9 max-w-[120px] w-auto object-contain" style="max-height: 36px; max-width: 120px;" width="120" height="36">
                    <div class="flex flex-col">
                        <span class="text-base font-semibold tracking-tight text-primary">MNC Library</span>
                        <span class="text-[11px] text-muted">Perpustakaan MNC University</span>
                    </div>
                </a>

                <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-primary transition-colors hover:text-primary-dark">
                    <i class="fa-solid fa-arrow-left text-[11px]"></i>
                    <span>Kembali ke Login</span>
                </a>
            </div>

            {{-- Konten Utama Formulir --}}
            <div class="mx-auto my-auto w-full max-w-[420px] py-8">
                <div class="size-12 rounded-2xl bg-amber-100/80 text-[#EA580C] flex items-center justify-center mb-5 shadow-sm">
                    <i class="fa-solid fa-key text-xl"></i>
                </div>

                <h1 class="text-2xl font-bold tracking-tight text-ink sm:text-3xl">
                    Lupa Kata Sandi?
                </h1>
                <p class="mt-2 text-xs leading-relaxed text-ink-secondary sm:text-sm">
                    Jangan khawatir! Masukkan alamat email yang terdaftar di perpustakaan. Sistem akan membuatkan kata sandi baru secara otomatis dan mengirimkannya langsung ke kotak masuk email Anda.
                </p>

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

                {{-- Formulir Kirim Email Reset --}}
                <form action="{{ route('password.email') }}" method="POST" class="mt-6 space-y-4">
                    @csrf

                    <div>
                        <label for="email" class="block text-xs font-semibold text-ink">
                            Alamat Email Terdaftar <span class="text-red-500">*</span>
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
                                placeholder="nama@mncu.ac.id"
                                class="block h-11 w-full rounded-xl border border-line bg-white pr-4 pl-10 text-xs text-ink placeholder:text-muted/60 transition-colors focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none"
                            >
                        </div>
                    </div>

                    {{-- Chip Pilihan Cepat Email Terdaftar --}}
                    <div class="rounded-xl border border-line bg-[#F8FAFC] p-3 text-xs">
                        <span class="text-muted text-[11px] block mb-1.5 font-medium">Pilih Cepat Akun Terdaftar:</span>
                        <div class="flex flex-wrap gap-1.5">
                            <button type="button" class="btn-quick-email rounded-lg border border-line bg-white px-2 py-1 text-[11px] font-medium text-ink hover:border-primary hover:text-primary transition-colors" data-email="arifin@mncu.ac.id">
                                arifin@mncu.ac.id (Kepala)
                            </button>
                            <button type="button" class="btn-quick-email rounded-lg border border-line bg-white px-2 py-1 text-[11px] font-medium text-ink hover:border-primary hover:text-primary transition-colors" data-email="sarah.amalia@mncu.ac.id">
                                sarah.amalia@mncu.ac.id (Pustakawan)
                            </button>
                            <button type="button" class="btn-quick-email rounded-lg border border-line bg-white px-2 py-1 text-[11px] font-medium text-ink hover:border-primary hover:text-primary transition-colors" data-email="fauzi.mhs@mncu.ac.id">
                                fauzi.mhs@mncu.ac.id (Mahasiswa)
                            </button>
                        </div>
                    </div>

                    <div class="pt-2">
                        <button
                            type="submit"
                            class="flex h-11 w-full items-center justify-center gap-2 rounded-xl bg-primary text-sm font-semibold text-white shadow-md transition-all hover:bg-primary-dark btn-press btn-sheen focus:ring-2 focus:ring-primary focus:ring-offset-2 focus:outline-none"
                        >
                            <i class="fa-regular fa-paper-plane text-xs"></i>
                            <span>Kirim Kata Sandi Baru ke Email</span>
                        </button>
                    </div>
                </form>

                <div class="mt-6 text-center text-xs text-ink-secondary">
                    <span>Ingat kata sandi Anda?</span>
                    <a href="{{ route('login') }}" class="font-semibold text-primary hover:underline">
                        Masuk ke Akun
                    </a>
                </div>
            </div>

            {{-- Footer Bawah Sisi Kiri --}}
            <div class="text-center text-[11px] text-muted lg:text-left">
                © {{ date('Y') }} Perpustakaan MNC University. Hak Cipta Dilindungi.
            </div>
        </div>

        {{-- Sisi Kanan: Informasi Keamanan & Quotes Literasi --}}
        <div class="relative hidden flex-col justify-between overflow-hidden bg-[#F6F4ED] p-10 lg:flex lg:p-14 xl:p-16">
            <div class="relative z-10 max-w-lg">
                <div class="text-[#EA580C]">
                    <svg class="size-10 fill-current opacity-90" viewBox="0 0 24 24">
                        <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
                    </svg>
                </div>

                <blockquote class="mt-4 text-xl font-medium tracking-tight text-ink sm:text-2xl lg:text-[23px] lg:leading-snug">
                    "Keamanan akses pemustaka adalah prioritas kami. Pastikan akun email Anda aktif untuk menerima konfirmasi peminjaman buku, akses jurnal internasional, dan pemulihan kredensial."
                </blockquote>

                <div class="mt-6 flex items-center gap-3.5">
                    <img
                        src="{{ asset('images/pustakawan/pustakawan-02.jpg') }}"
                        alt="Sarah Amalia, S.I.Pust."
                        class="size-11 rounded-full border-2 border-white object-cover shadow-sm"
                        onerror="this.onerror=null; this.src='{{ asset('images/Logo.png') }}';"
                    >
                    <div>
                        <div class="text-sm font-semibold text-ink">Sarah Amalia, S.I.Pust.</div>
                        <div class="text-xs text-muted">Koordinator Layanan Pemustaka MNC University</div>
                    </div>
                </div>
            </div>

            {{-- Ilustrasi Minimalis Buku-Buku --}}
            <div class="relative z-10 mt-auto pt-8 flex justify-center">
                <div class="rounded-2xl border border-line bg-white/80 backdrop-blur-sm p-6 shadow-sm max-w-md text-xs leading-relaxed text-ink-secondary">
                    <div class="flex items-center gap-2.5 font-semibold text-primary mb-2">
                        <i class="fa-solid fa-shield-halved text-sm"></i>
                        <span>Prosedur Reset Kata Sandi Otomatis</span>
                    </div>
                    <ul class="space-y-1.5 list-disc list-inside text-muted">
                        <li>Kata sandi baru dibuat secara acak dengan standar keamanan tinggi.</li>
                        <li>Kata sandi langsung di-hash menggunakan algoritma Bcrypt/Argon2 di database.</li>
                        <li>Sistem segera mengirimkan pesan ke email resmi Anda beserta instruksi login.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const emailInput = document.getElementById('email');
            const quickBtns = document.querySelectorAll('.btn-quick-email');

            quickBtns.forEach((btn) => {
                btn.addEventListener('click', () => {
                    const email = btn.getAttribute('data-email');
                    if (emailInput && email) {
                        emailInput.value = email;
                        emailInput.focus();
                        emailInput.classList.add('ring-2', 'ring-primary');
                        setTimeout(() => emailInput.classList.remove('ring-2', 'ring-primary'), 800);
                    }
                });
            });
        });
    </script>
</body>
</html>
