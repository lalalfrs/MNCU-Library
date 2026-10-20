@extends('layouts.app')

@section('title', 'Manajemen User & Pengguna')
@section('description', 'Kelola data pengguna, peran, hak akses, dan informasi sivitas akademika perpustakaan MNC University.')

@section('content')
    <div class="mx-auto flex w-full max-w-[1570px] flex-1 flex-col gap-6 px-3 py-6 lg:px-6 lg:py-8">
        {{-- Breadcrumb & Header --}}
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <nav aria-label="Breadcrumb" class="flex items-center gap-2 text-xs text-muted sm:text-sm">
                    <a href="{{ route('beranda') }}" class="hover:text-primary">Beranda</a>
                    <svg class="size-3.5 text-muted/60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
                    <span class="text-ink-secondary">Panel Manajemen</span>
                    <svg class="size-3.5 text-muted/60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
                    <span class="font-medium text-ink">Manajemen User</span>
                </nav>
                <h1 class="mt-1 text-2xl font-bold tracking-tight text-primary sm:text-3xl">
                    Manajemen Pengguna Perpustakaan
                </h1>
                <p class="mt-0.5 text-xs text-ink-secondary sm:text-sm">
                    Kelola data pustakawan, mahasiswa, dosen, dan hak akses otentikasi.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a
                    href="{{ route('admin.users.create') }}"
                    class="inline-flex h-10 items-center gap-2 rounded-xl bg-primary px-4 text-xs font-semibold text-white shadow-sm transition-all hover:bg-primary-dark active:scale-[0.98] sm:h-11 sm:text-sm"
                >
                    <i class="fa-solid fa-user-plus text-xs"></i>
                    <span>Tambah Pengguna</span>
                </a>
            </div>
        </div>

        {{-- Alert Notifikasi Sukses / Feedback Reset Password --}}
        @if (session('success'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50/90 p-4 text-xs sm:text-sm text-emerald-900 shadow-xs">
                <div class="flex items-start gap-3">
                    <div class="flex size-7 shrink-0 items-center justify-center rounded-lg bg-emerald-600 text-white">
                        <i class="fa-solid fa-circle-check text-xs"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-emerald-950">{{ session('success') }}</p>
                        @if (session('reset_password_preview'))
                            <div class="mt-2 flex flex-wrap items-center gap-2 rounded-lg border border-emerald-300 bg-white px-3 py-2 text-xs">
                                <span class="font-medium text-emerald-800">Password Baru Akun:</span>
                                <code class="rounded bg-emerald-100 px-2 py-0.5 font-mono font-bold text-emerald-900 text-sm select-all">{{ session('reset_password_preview') }}</code>
                                <span class="text-[11px] text-muted">(Salin dan informasikan kepada pengguna bersangkutan)</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-xl border border-rose-200 bg-rose-50/90 p-4 text-xs sm:text-sm text-rose-900 shadow-xs">
                <div class="flex items-start gap-3">
                    <div class="flex size-7 shrink-0 items-center justify-center rounded-lg bg-rose-600 text-white">
                        <i class="fa-solid fa-triangle-exclamation text-xs"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-rose-950">Gagal Memproses Permintaan</p>
                        <ul class="mt-1 list-disc list-inside space-y-0.5 text-xs text-rose-800">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        {{-- Filter & Pencarian Bar --}}
        <div class="rounded-2xl border border-line bg-white p-4 shadow-card">
            <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div class="relative flex-1">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </span>
                    <input
                        type="text"
                        name="q"
                        value="{{ $q }}"
                        placeholder="Cari nama, email, atau nomor identitas..."
                        class="h-10 w-full rounded-xl border border-line bg-subtle/50 pr-4 pl-10 text-xs text-ink placeholder:text-muted focus:border-primary focus:bg-white focus:outline-none sm:text-sm"
                    >
                </div>

                <div class="flex items-center gap-2">
                    <select
                        name="peran"
                        onchange="this.form.submit()"
                        class="h-10 rounded-xl border border-line bg-white px-3 text-xs text-ink focus:border-primary focus:outline-none sm:text-sm"
                    >
                        <option value="">Semua Peran</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id_peran }}" {{ $peranId === $role->id_peran ? 'selected' : '' }}>
                                {{ $role->nama_peran }}
                            </option>
                        @endforeach
                    </select>

                    <button
                        type="submit"
                        class="inline-flex h-10 items-center justify-center rounded-xl bg-primary/10 px-4 text-xs font-semibold text-primary transition-colors hover:bg-primary hover:text-white sm:text-sm"
                    >
                        Filter
                    </button>

                    @if ($q !== '' || $peranId !== '')
                        <a
                            href="{{ route('admin.users.index') }}"
                            class="inline-flex h-10 items-center justify-center rounded-xl border border-line px-3 text-xs font-medium text-muted transition-colors hover:bg-subtle hover:text-ink sm:text-sm"
                            title="Reset Filter"
                        >
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Tabel Pengguna --}}
        <div class="overflow-hidden rounded-2xl border border-line bg-white shadow-card">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm">
                    <thead class="border-b border-line bg-[#F8FAFC] text-[11px] font-semibold uppercase tracking-wider text-muted">
                        <tr>
                            <th scope="col" class="px-5 py-3.5">Pengguna</th>
                            <th scope="col" class="px-5 py-3.5">Peran</th>
                            <th scope="col" class="px-5 py-3.5">Program Studi</th>
                            <th scope="col" class="px-5 py-3.5">Nomor Identitas</th>
                            <th scope="col" class="px-5 py-3.5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line text-ink">
                        @forelse ($users as $user)
                            <tr class="transition-colors hover:bg-[#F9FAFB]">
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex size-9 shrink-0 items-center justify-center rounded-full bg-primary/10 font-bold text-primary">
                                            {{ strtoupper(substr($user->nama_lengkap, 0, 1)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-semibold text-ink">{{ $user->nama_lengkap }}</p>
                                            <p class="truncate text-xs text-muted">{{ $user->email }}</p>
                                            <span class="font-mono text-[10px] text-muted">{{ $user->id_pengguna }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap">
                                    @php
                                        $badgeColor = match ($user->peran?->nama_peran) {
                                            'Kepala Perpustakaan' => 'bg-amber-100 text-amber-800 border-amber-200',
                                            'Pustakawan' => 'bg-blue-100 text-blue-800 border-blue-200',
                                            'Mahasiswa' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                            'Dosen' => 'bg-purple-100 text-purple-800 border-purple-200',
                                            default => 'bg-gray-100 text-gray-800 border-gray-200',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-medium {{ $badgeColor }}">
                                        {{ $user->peran?->nama_peran ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap text-ink-secondary">
                                    {{ $user->prodi?->nama_prodi ?? '-' }}
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap font-mono text-xs text-muted">
                                    {{ $user->nomor_identitas ?? '-' }}
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap text-center">
                                    <div class="inline-flex items-center gap-1.5">
                                        <a
                                            href="{{ route('admin.users.edit', $user->id_pengguna) }}"
                                            class="inline-flex size-8 items-center justify-center rounded-lg border border-line text-muted transition-colors hover:border-primary hover:bg-primary/5 hover:text-primary"
                                            title="Ubah Data"
                                        >
                                            <i class="fa-regular fa-pen-to-square text-xs"></i>
                                        </a>

                                        {{-- Skenario 2: Tombol Reset Password oleh Admin --}}
                                        <button
                                            type="button"
                                            onclick="openResetPasswordModal('{{ $user->id_pengguna }}', '{{ addslashes($user->nama_lengkap) }}', '{{ $user->email }}', '{{ $user->peran?->nama_peran ?? 'Pengguna' }}', '{{ $user->nomor_identitas ?? '-' }}', 'Mncu#{{ $user->nomor_identitas ?: substr($user->id_pengguna, -5) }}')"
                                            class="inline-flex size-8 items-center justify-center rounded-lg border border-amber-200 text-amber-700 transition-colors hover:bg-amber-50 hover:border-amber-300"
                                            title="Reset Password Pengguna"
                                            aria-label="Reset Password Pengguna {{ $user->nama_lengkap }}"
                                        >
                                            <i class="fa-solid fa-key text-xs"></i>
                                        </button>

                                        @if (Auth::id() !== $user->id_pengguna)
                                            <form
                                                action="{{ route('admin.users.destroy', $user->id_pengguna) }}"
                                                method="POST"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna {{ $user->nama_lengkap }}?')"
                                                class="inline-block"
                                            >
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    type="submit"
                                                    class="inline-flex size-8 items-center justify-center rounded-lg border border-red-200 text-red-600 transition-colors hover:bg-red-50"
                                                    title="Hapus Pengguna"
                                                >
                                                    <i class="fa-regular fa-trash-can text-xs"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        <i class="fa-solid fa-users-slash text-3xl text-muted/40"></i>
                                        <p class="font-medium text-ink">Tidak ada data pengguna ditemukan.</p>
                                        <p class="text-xs text-muted">Coba ubah kata kunci pencarian atau filter peran.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($users->hasPages())
                <div class="border-t border-line px-5 py-4">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- ========================================================================= --}}
    {{-- MODAL RESET PASSWORD OLEH ADMIN (SKENARIO 2)                             --}}
    {{-- ========================================================================= --}}
    <div
        id="admin-reset-password-modal"
        class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-xs transition-opacity duration-200"
        role="dialog"
        aria-modal="true"
        aria-labelledby="modal-reset-title"
    >
        <div class="flex min-h-screen items-center justify-center p-3 sm:p-6">
            <div class="relative w-full max-w-lg overflow-hidden rounded-2xl border border-line bg-white shadow-2xl transition-all">
                
                {{-- Header Modal --}}
                <div class="flex items-center justify-between border-b border-line bg-slate-50/80 px-6 py-4">
                    <div class="flex items-center gap-2.5">
                        <span class="flex size-9 items-center justify-center rounded-xl bg-amber-500/10 text-amber-700">
                            <i class="fa-solid fa-key text-sm"></i>
                        </span>
                        <div>
                            <h3 id="modal-reset-title" class="text-sm font-bold text-ink sm:text-base">Reset Password Pengguna</h3>
                            <p class="text-[11px] text-muted">Panel Administrator Perpustakaan MNC University</p>
                        </div>
                    </div>
                    <button
                        type="button"
                        onclick="closeResetPasswordModal()"
                        class="inline-flex size-8 items-center justify-center rounded-lg text-muted hover:bg-slate-200 hover:text-ink transition-colors"
                        aria-label="Tutup dialog"
                    >
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </button>
                </div>

                {{-- Target User Preview --}}
                <div class="bg-subtle/50 px-6 py-3.5 border-b border-line/80">
                    <div class="flex items-center justify-between">
                        <div>
                            <p id="modal-user-name" class="font-bold text-xs sm:text-sm text-ink">-</p>
                            <p id="modal-user-email" class="text-[11px] text-muted">-</p>
                        </div>
                        <div class="text-right">
                            <span id="modal-user-role" class="inline-flex items-center rounded-full border px-2 py-0.5 text-[10px] font-semibold bg-white border-line text-ink-secondary">-</span>
                            <p id="modal-user-id" class="font-mono text-[10px] text-muted mt-0.5">-</p>
                        </div>
                    </div>
                </div>

                {{-- Body Modal --}}
                <div class="p-6">
                    <form id="admin-reset-form" method="POST" action="" class="space-y-5">
                        @csrf

                        {{-- Pilihan Mode Reset --}}
                        <div class="grid grid-cols-2 gap-2 rounded-xl border border-line bg-subtle p-1 text-xs font-semibold">
                            <button
                                type="button"
                                id="tab-btn-default"
                                onclick="switchResetTab('default')"
                                class="flex items-center justify-center gap-1.5 rounded-lg py-2 transition-all bg-white text-primary shadow-xs"
                            >
                                <i class="fa-solid fa-wand-magic-sparkles text-[11px]"></i>
                                <span>Reset ke Default</span>
                            </button>
                            <button
                                type="button"
                                id="tab-btn-custom"
                                onclick="switchResetTab('custom')"
                                class="flex items-center justify-center gap-1.5 rounded-lg py-2 transition-all text-muted hover:text-ink"
                            >
                                <i class="fa-solid fa-pen text-[11px]"></i>
                                <span>Password Manual</span>
                            </button>
                        </div>

                        {{-- Section 1: Mode Default Password --}}
                        <div id="section-default-mode" class="space-y-3">
                            <input type="hidden" name="reset_to_default" id="input-reset-to-default" value="1">

                            <div class="rounded-xl border border-amber-200 bg-amber-50/70 p-4 text-xs text-amber-950">
                                <div class="flex items-start gap-2.5">
                                    <i class="fa-solid fa-circle-info mt-0.5 text-amber-600"></i>
                                    <div class="leading-relaxed">
                                        <p class="font-semibold">Reset ke Password Standar Cepat</p>
                                        <p class="mt-0.5 text-[11px] text-amber-900">
                                            Password akun akan disetel secara otomatis ke format standar yang aman tanpa perlu memasukkan password lama pengguna.
                                        </p>
                                    </div>
                                </div>
                                <div class="mt-3 flex items-center justify-between rounded-lg border border-amber-300/80 bg-white p-2.5">
                                    <span class="text-[11px] text-muted">Pratinjau Password:</span>
                                    <code id="preview-default-pass" class="font-mono font-bold text-amber-900 text-xs sm:text-sm">Mncu#123456</code>
                                </div>
                            </div>
                        </div>

                        {{-- Section 2: Mode Custom Password --}}
                        <div id="section-custom-mode" class="hidden space-y-4">
                            <div>
                                <label for="admin_password_baru" class="block text-xs font-semibold text-ink">
                                    Password Baru untuk Pengguna <span class="text-rose-600">*</span>
                                </label>
                                <div class="relative mt-1.5">
                                    <input
                                        type="password"
                                        name="password_baru"
                                        id="admin_password_baru"
                                        minlength="6"
                                        placeholder="Ketik password baru (min. 6 karakter)..."
                                        class="h-10 w-full rounded-xl border border-line bg-subtle/40 pr-10 pl-3 text-xs text-ink placeholder:text-muted/70 focus:border-primary focus:bg-white focus:outline-none sm:text-sm"
                                    >
                                    <button
                                        type="button"
                                        onclick="togglePasswordVisibility('admin_password_baru', 'admin-icon-pass')"
                                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-muted hover:text-ink"
                                        aria-label="Tampilkan atau sembunyikan password baru"
                                    >
                                        <i id="admin-icon-pass" class="fa-regular fa-eye text-xs"></i>
                                    </button>
                                </div>
                            </div>

                            <div>
                                <label for="admin_password_baru_confirmation" class="block text-xs font-semibold text-ink">
                                    Ulangi Konfirmasi Password Baru <span class="text-rose-600">*</span>
                                </label>
                                <div class="relative mt-1.5">
                                    <input
                                        type="password"
                                        name="password_baru_confirmation"
                                        id="admin_password_baru_confirmation"
                                        minlength="6"
                                        placeholder="Ketik ulang password baru..."
                                        class="h-10 w-full rounded-xl border border-line bg-subtle/40 pr-10 pl-3 text-xs text-ink placeholder:text-muted/70 focus:border-primary focus:bg-white focus:outline-none sm:text-sm"
                                    >
                                    <button
                                        type="button"
                                        onclick="togglePasswordVisibility('admin_password_baru_confirmation', 'admin-icon-confirm')"
                                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-muted hover:text-ink"
                                        aria-label="Tampilkan atau sembunyikan konfirmasi password"
                                    >
                                        <i id="admin-icon-confirm" class="fa-regular fa-eye text-xs"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Tombol Aksi Modal --}}
                        <div class="flex items-center justify-end gap-2.5 pt-4 border-t border-line">
                            <button
                                type="button"
                                onclick="closeResetPasswordModal()"
                                class="inline-flex h-10 items-center justify-center rounded-xl border border-line bg-white px-4 text-xs font-semibold text-ink-secondary hover:bg-subtle transition-colors"
                            >
                                Batal
                            </button>
                            <button
                                type="submit"
                                id="btn-submit-reset"
                                class="inline-flex h-10 items-center justify-center gap-1.5 rounded-xl bg-amber-600 px-5 text-xs font-semibold text-white shadow-sm hover:bg-amber-700 transition-colors"
                            >
                                <i class="fa-solid fa-check text-xs"></i>
                                <span>Reset Password Sekarang</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Script Modal Reset Password Admin --}}
    <script>
        function openResetPasswordModal(id, name, email, role, identity, defaultPass) {
            const modal = document.getElementById('admin-reset-password-modal');
            const form = document.getElementById('admin-reset-form');
            if (!modal || !form) return;

            form.action = `/admin/users/${encodeURIComponent(id)}/reset-password`;

            document.getElementById('modal-user-name').textContent = name;
            document.getElementById('modal-user-email').textContent = email;
            document.getElementById('modal-user-role').textContent = role;
            document.getElementById('modal-user-id').textContent = id + (identity !== '-' ? ' • ' + identity : '');
            document.getElementById('preview-default-pass').textContent = defaultPass;

            // Reset tabs to default
            switchResetTab('default');

            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeResetPasswordModal() {
            const modal = document.getElementById('admin-reset-password-modal');
            if (modal) {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        }

        function switchResetTab(mode) {
            const btnDefault = document.getElementById('tab-btn-default');
            const btnCustom = document.getElementById('tab-btn-custom');
            const secDefault = document.getElementById('section-default-mode');
            const secCustom = document.getElementById('section-custom-mode');
            const inputResetDefault = document.getElementById('input-reset-to-default');
            const inputCustomPass = document.getElementById('admin_password_baru');
            const inputCustomConfirm = document.getElementById('admin_password_baru_confirmation');
            const btnSubmit = document.getElementById('btn-submit-reset');

            if (mode === 'default') {
                btnDefault.className = 'flex items-center justify-center gap-1.5 rounded-lg py-2 transition-all bg-white text-primary shadow-xs';
                btnCustom.className = 'flex items-center justify-center gap-1.5 rounded-lg py-2 transition-all text-muted hover:text-ink';
                secDefault.classList.remove('hidden');
                secCustom.classList.add('hidden');
                inputResetDefault.value = '1';
                inputCustomPass.removeAttribute('required');
                inputCustomConfirm.removeAttribute('required');
                btnSubmit.textContent = 'Reset ke Default Sekarang';
                btnSubmit.className = 'inline-flex h-10 items-center justify-center gap-1.5 rounded-xl bg-amber-600 px-5 text-xs font-semibold text-white shadow-sm hover:bg-amber-700 transition-colors';
            } else {
                btnCustom.className = 'flex items-center justify-center gap-1.5 rounded-lg py-2 transition-all bg-white text-primary shadow-xs';
                btnDefault.className = 'flex items-center justify-center gap-1.5 rounded-lg py-2 transition-all text-muted hover:text-ink';
                secCustom.classList.remove('hidden');
                secDefault.classList.add('hidden');
                inputResetDefault.value = '0';
                inputCustomPass.setAttribute('required', 'required');
                inputCustomConfirm.setAttribute('required', 'required');
                btnSubmit.textContent = 'Simpan Password Baru';
                btnSubmit.className = 'inline-flex h-10 items-center justify-center gap-1.5 rounded-xl bg-primary px-5 text-xs font-semibold text-white shadow-sm hover:bg-primary-dark transition-colors';
            }
        }

        function togglePasswordVisibility(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (!input || !icon) return;

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeResetPasswordModal();
            }
        });

        document.getElementById('admin-reset-password-modal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeResetPasswordModal();
            }
        });
    </script>
@endsection
