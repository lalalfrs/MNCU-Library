{{-- Section Buku Tamu / Absensi Pengunjung Perpustakaan --}}
<section id="buku-tamu" aria-labelledby="judul-buku-tamu" class="scroll-mt-28 rounded-2xl border border-line bg-white p-5 sm:p-6 lg:p-8 shadow-xs">
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-12 lg:gap-8 items-start">
        {{-- Kolom Kiri: Informasi Buku Tamu & Tata Tertib --}}
        <div class="lg:col-span-5 flex flex-col justify-between h-full space-y-5">
            <div>
                <div class="inline-flex items-center gap-2 rounded-full border border-primary/20 bg-primary/5 px-3 py-1 text-xs font-semibold text-primary">
                    <i class="fa-solid fa-clipboard-user text-xs" aria-hidden="true"></i>
                    <span>Buku Tamu Digital</span>
                </div>
                <h2 id="judul-buku-tamu" class="mt-3 text-xl font-bold text-ink sm:text-2xl lg:text-3xl">
                    Absensi Pengunjung Perpus
                </h2>
                <p class="mt-2 text-xs sm:text-sm leading-relaxed text-ink-secondary">
                    Selamat datang di Perpustakaan MNC University. Mohon mengisi buku tamu digital di bawah ini setiap kali berkunjung demi ketertiban serta peningkatan mutu fasilitas dan layanan kami.
                </p>
            </div>

            <div class="rounded-xl border border-line bg-subtle p-4 sm:p-5 space-y-3.5">
                <h3 class="text-xs sm:text-sm font-semibold text-ink flex items-center gap-2">
                    <i class="fa-solid fa-circle-info text-primary text-sm" aria-hidden="true"></i>
                    Panduan Kunjungan Pemustaka
                </h3>
                <ul class="space-y-2 text-xs sm:text-[13px] text-ink-secondary">
                    <li class="flex items-start gap-2">
                        <i class="fa-solid fa-check text-emerald-600 mt-0.5 text-xs shrink-0" aria-hidden="true"></i>
                        <span>Isi nama dan identitas Anda secara benar pada formulir absensi.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="fa-solid fa-check text-emerald-600 mt-0.5 text-xs shrink-0" aria-hidden="true"></i>
                        <span>Pilih kategori pengunjung (Mahasiswa, Dosen / Staf, atau Umum).</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="fa-solid fa-check text-emerald-600 mt-0.5 text-xs shrink-0" aria-hidden="true"></i>
                        <span>Peminjaman buku koleksi fisik dapat diajukan mandiri via katalog OPAC.</span>
                    </li>
                </ul>
                <div class="pt-2 border-t border-line/60 flex items-center justify-between text-[11px] text-muted">
                    <span class="flex items-center gap-1.5">
                        <i class="fa-regular fa-clock text-primary"></i>
                        Layanan: 08:00 - 17:00 WIB
                    </span>
                    <span class="font-medium text-primary">MNC University</span>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Formulir Absensi Masuk --}}
        <div class="lg:col-span-7">
            <div class="rounded-xl border border-line bg-white p-5 sm:p-6 lg:p-7 shadow-xs">
                {{-- Banner Notifikasi Sukses --}}
                <div id="guest-log-alert" class="hidden mb-5 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-xs sm:text-sm text-emerald-900 shadow-xs" role="alert" aria-live="polite">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-2.5">
                            <span class="flex size-7 shrink-0 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600">
                                <i class="fa-solid fa-circle-check text-sm" aria-hidden="true"></i>
                            </span>
                            <span id="guest-log-alert-message" class="font-medium"></span>
                        </div>
                        <button type="button" id="guest-log-alert-close" class="text-emerald-700 hover:text-emerald-900 transition-colors" aria-label="Tutup notifikasi">
                            <i class="fa-solid fa-xmark text-sm"></i>
                        </button>
                    </div>
                </div>

                {{-- Banner Notifikasi Error --}}
                <div id="guest-log-error-alert" class="hidden mb-5 rounded-xl border border-rose-200 bg-rose-50 p-4 text-xs sm:text-sm text-rose-900 shadow-xs" role="alert" aria-live="assertive">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-2.5">
                            <span class="flex size-7 shrink-0 items-center justify-center rounded-lg bg-rose-100 text-rose-600">
                                <i class="fa-solid fa-circle-exclamation text-sm" aria-hidden="true"></i>
                            </span>
                            <span id="guest-log-error-message" class="font-medium"></span>
                        </div>
                        <button type="button" id="guest-log-error-close" class="text-rose-700 hover:text-rose-900 transition-colors" aria-label="Tutup pemberitahuan">
                            <i class="fa-solid fa-xmark text-sm"></i>
                        </button>
                    </div>
                </div>

                <form id="guest-log-form" action="{{ route('guest-log.store') }}" method="POST" class="space-y-4 sm:space-y-5" novalidate>
                    @csrf

                    {{-- 1. Nama Lengkap --}}
                    <div>
                        <label for="guest_nama" class="block text-xs sm:text-sm font-semibold text-ink">
                            Nama Lengkap <span class="text-rose-500" aria-hidden="true">*</span>
                        </label>
                        <div class="relative mt-1.5">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted">
                                <i class="fa-regular fa-user text-sm" aria-hidden="true"></i>
                            </span>
                            <input
                                type="text"
                                name="nama"
                                id="guest_nama"
                                required
                                value="{{ old('nama') }}"
                                placeholder="Masukkan nama lengkap Anda"
                                class="block w-full h-11 sm:h-12 rounded-xl border border-line bg-white pl-10 pr-3.5 text-xs sm:text-sm text-ink placeholder:text-muted transition-colors focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none"
                            >
                        </div>
                        <p id="guest_nama_error" class="hidden text-xs text-rose-600 mt-1"></p>
                    </div>

                    {{-- 2. Kategori Pengunjung --}}
                    <div>
                        <fieldset>
                            <legend class="block text-xs sm:text-sm font-semibold text-ink">
                                Kategori Pengunjung <span class="text-rose-500" aria-hidden="true">*</span>
                            </legend>
                            <div class="mt-2 grid grid-cols-1 gap-2.5 sm:grid-cols-3">
                                {{-- Mahasiswa --}}
                                <label class="relative flex cursor-pointer items-center gap-3 rounded-xl border border-line bg-white p-3 transition-all hover:border-primary/40 has-[:checked]:border-primary has-[:checked]:bg-primary/5 has-[:checked]:ring-1 has-[:checked]:ring-primary">
                                    <input
                                        type="radio"
                                        name="kategori"
                                        value="Mahasiswa"
                                        class="size-4 text-primary focus:ring-primary"
                                        checked
                                    >
                                    <div class="flex items-center gap-2">
                                        <i class="fa-solid fa-graduation-cap text-primary text-sm" aria-hidden="true"></i>
                                        <span class="text-xs sm:text-sm font-medium text-ink">Mahasiswa</span>
                                    </div>
                                </label>

                                {{-- Dosen / Staf --}}
                                <label class="relative flex cursor-pointer items-center gap-3 rounded-xl border border-line bg-white p-3 transition-all hover:border-primary/40 has-[:checked]:border-primary has-[:checked]:bg-primary/5 has-[:checked]:ring-1 has-[:checked]:ring-primary">
                                    <input
                                        type="radio"
                                        name="kategori"
                                        value="Dosen / Staf"
                                        class="size-4 text-primary focus:ring-primary"
                                    >
                                    <div class="flex items-center gap-2">
                                        <i class="fa-solid fa-user-tie text-primary text-sm" aria-hidden="true"></i>
                                        <span class="text-xs sm:text-sm font-medium text-ink">Dosen / Staf</span>
                                    </div>
                                </label>

                                {{-- Umum --}}
                                <label class="relative flex cursor-pointer items-center gap-3 rounded-xl border border-line bg-white p-3 transition-all hover:border-primary/40 has-[:checked]:border-primary has-[:checked]:bg-primary/5 has-[:checked]:ring-1 has-[:checked]:ring-primary">
                                    <input
                                        type="radio"
                                        name="kategori"
                                        value="Umum"
                                        class="size-4 text-primary focus:ring-primary"
                                    >
                                    <div class="flex items-center gap-2">
                                        <i class="fa-solid fa-users text-primary text-sm" aria-hidden="true"></i>
                                        <span class="text-xs sm:text-sm font-medium text-ink">Umum</span>
                                    </div>
                                </label>
                            </div>
                        </fieldset>
                    </div>

                    {{-- 3. Identitas / Detail (Dynamic Input) --}}
                    <div>
                        <label for="guest_identitas" id="identitas-label" class="block text-xs sm:text-sm font-semibold text-ink">
                            Nomor Induk Mahasiswa (NIM) <span class="text-rose-500" aria-hidden="true">*</span>
                        </label>
                        <div class="relative mt-1.5">
                            <span id="identitas-icon" class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted">
                                <i class="fa-regular fa-id-card text-sm" aria-hidden="true"></i>
                            </span>
                            <input
                                type="text"
                                name="identitas"
                                id="guest_identitas"
                                required
                                value="{{ old('identitas') }}"
                                placeholder="Contoh: 2023010001"
                                class="block w-full h-11 sm:h-12 rounded-xl border border-line bg-white pl-10 pr-3.5 text-xs sm:text-sm text-ink placeholder:text-muted transition-colors focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none"
                            >
                        </div>
                        <p id="identitas-hint" class="mt-1.5 text-xs text-muted">
                            Masukkan NIM mahasiswa aktif MNC University.
                        </p>
                        <p id="guest_identitas_error" class="hidden text-xs text-rose-600 mt-1"></p>
                    </div>

                    {{-- Tombol Submit --}}
                    <div class="pt-2">
                        <button
                            type="submit"
                            id="guest-log-submit-btn"
                            class="btn-press btn-sheen inline-flex w-full h-11 sm:h-12 items-center justify-center gap-2 rounded-xl bg-primary px-5 text-xs sm:text-sm font-semibold text-white shadow-xs transition-all hover:bg-primary-dark focus-visible:outline-2 focus-visible:outline-primary disabled:opacity-60 disabled:cursor-not-allowed"
                        >
                            <span id="guest-log-btn-spinner" class="hidden">
                                <i class="fa-solid fa-circle-notch fa-spin text-sm" aria-hidden="true"></i>
                            </span>
                            <i id="guest-log-btn-icon" class="fa-solid fa-clipboard-check text-sm" aria-hidden="true"></i>
                            <span id="guest-log-btn-text">Submit / Absen Masuk</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

{{-- Script Interaktif: Dinamisasi Input & Submit Absensi --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('guest-log-form');
        if (!form) return;

        const namaInput = document.getElementById('guest_nama');
        const identitasInput = document.getElementById('guest_identitas');
        const identitasLabel = document.getElementById('identitas-label');
        const identitasHint = document.getElementById('identitas-hint');
        const identitasIcon = document.getElementById('identitas-icon');
        const namaError = document.getElementById('guest_nama_error');
        const identitasError = document.getElementById('guest_identitas_error');
        const submitBtn = document.getElementById('guest-log-submit-btn');
        const btnSpinner = document.getElementById('guest-log-btn-spinner');
        const btnIcon = document.getElementById('guest-log-btn-icon');
        const btnText = document.getElementById('guest-log-btn-text');
        const alertBox = document.getElementById('guest-log-alert');
        const alertMessage = document.getElementById('guest-log-alert-message');
        const alertClose = document.getElementById('guest-log-alert-close');
        const errorBox = document.getElementById('guest-log-error-alert');
        const errorMessage = document.getElementById('guest-log-error-message');
        const errorClose = document.getElementById('guest-log-error-close');

        const kategoriRadios = form.querySelectorAll('input[name="kategori"]');

        const configPerKategori = {
            'Mahasiswa': {
                label: 'Nomor Induk Mahasiswa (NIM)',
                placeholder: 'Contoh: 2023010001',
                hint: 'Masukkan NIM mahasiswa aktif MNC University.',
                icon: '<i class="fa-regular fa-id-card text-sm" aria-hidden="true"></i>'
            },
            'Dosen / Staf': {
                label: 'Nomor Induk Pegawai (NIP)',
                placeholder: 'Contoh: 198501012010121001',
                hint: 'Masukkan NIP dosen atau staf MNC University.',
                icon: '<i class="fa-solid fa-id-badge text-sm" aria-hidden="true"></i>'
            },
            'Umum': {
                label: 'Alamat / Asal Instansi',
                placeholder: 'Contoh: Jl. Merdeka No. 10 / Universitas Indonesia',
                hint: 'Tuliskan alamat domisili atau instansi/lembaga asal Anda.',
                icon: '<i class="fa-solid fa-location-dot text-sm" aria-hidden="true"></i>'
            }
        };

        const updateDynamicInput = (kategori) => {
            const cfg = configPerKategori[kategori] || configPerKategori['Mahasiswa'];
            if (identitasLabel) {
                identitasLabel.innerHTML = `${cfg.label} <span class="text-rose-500" aria-hidden="true">*</span>`;
            }
            if (identitasInput) {
                identitasInput.placeholder = cfg.placeholder;
            }
            if (identitasHint) {
                identitasHint.textContent = cfg.hint;
            }
            if (identitasIcon) {
                identitasIcon.innerHTML = cfg.icon;
            }
        };

        kategoriRadios.forEach((radio) => {
            radio.addEventListener('change', () => {
                if (radio.checked) {
                    updateDynamicInput(radio.value);
                }
            });
        });

        if (alertClose && alertBox) {
            alertClose.addEventListener('click', () => {
                alertBox.classList.add('hidden');
            });
        }

        if (errorClose && errorBox) {
            errorClose.addEventListener('click', () => {
                errorBox.classList.add('hidden');
            });
        }

        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            // Clear error messages
            namaError.classList.add('hidden');
            namaError.textContent = '';
            identitasError.classList.add('hidden');
            identitasError.textContent = '';
            alertBox.classList.add('hidden');
            errorBox.classList.add('hidden');

            const namaVal = namaInput ? namaInput.value.trim() : '';
            const identitasVal = identitasInput ? identitasInput.value.trim() : '';
            let selectedKategori = 'Mahasiswa';
            kategoriRadios.forEach((r) => {
                if (r.checked) selectedKategori = r.value;
            });

            let isValid = true;

            if (!namaVal) {
                namaError.textContent = 'Nama lengkap wajib diisi.';
                namaError.classList.remove('hidden');
                namaInput.classList.add('border-rose-400');
                isValid = false;
            } else {
                namaInput.classList.remove('border-rose-400');
            }

            if (!identitasVal) {
                const cfg = configPerKategori[selectedKategori] || configPerKategori['Mahasiswa'];
                identitasError.textContent = `${cfg.label} wajib diisi.`;
                identitasError.classList.remove('hidden');
                identitasInput.classList.add('border-rose-400');
                isValid = false;
            } else {
                identitasInput.classList.remove('border-rose-400');
            }

            if (!isValid) return;

            // Loading state
            submitBtn.disabled = true;
            btnSpinner.classList.remove('hidden');
            btnIcon.classList.add('hidden');
            btnText.textContent = 'Menyimpan Absensi...';

            const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
            const csrfTokenInput = form.querySelector('input[name="_token"]');
            const token = csrfTokenMeta ? csrfTokenMeta.content : (csrfTokenInput ? csrfTokenInput.value : '');

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify({
                        nama: namaVal,
                        kategori: selectedKategori,
                        identitas: identitasVal
                    })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    // Show success feedback
                    alertMessage.textContent = data.message || `Selamat datang, ${namaVal}! Absensi berhasil dicatat.`;
                    alertBox.classList.remove('hidden');

                    // Reset form fields
                    form.reset();

                    // Restore default radio selection
                    const defaultRadio = form.querySelector('input[name="kategori"][value="Mahasiswa"]');
                    if (defaultRadio) {
                        defaultRadio.checked = true;
                        updateDynamicInput('Mahasiswa');
                    }

                    // Auto dismiss after 7 seconds
                    setTimeout(() => {
                        alertBox.classList.add('hidden');
                    }, 7000);
                } else {
                    let errMsg = data.message || 'Terjadi kesalahan saat mencatat absensi. Silakan coba lagi.';
                    if (data.errors) {
                        const firstKey = Object.keys(data.errors)[0];
                        if (firstKey && data.errors[firstKey][0]) {
                            errMsg = data.errors[firstKey][0];
                        }
                    }
                    errorMessage.textContent = errMsg;
                    errorBox.classList.remove('hidden');
                }
            } catch (err) {
                // Fallback to regular POST submit if fetch fails completely
                form.submit();
                return;
            } finally {
                submitBtn.disabled = false;
                btnSpinner.classList.add('hidden');
                btnIcon.classList.remove('hidden');
                btnText.textContent = 'Submit / Absen Masuk';
            }
        });
    });
</script>

