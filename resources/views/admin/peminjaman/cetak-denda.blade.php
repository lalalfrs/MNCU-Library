<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Rincian Denda Keterlambatan - {{ $loan->id_peminjaman }} - {{ $loan->pengguna->nama_lengkap }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800|jetbrains-mono:500,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @page {
            size: A4 portrait;
            margin: 12mm 15mm 15mm 15mm;
        }

        @media print {
            body {
                background: #ffffff !important;
                color: #0f172a !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .no-print {
                display: none !important;
            }

            .print-document {
                border: none !important;
                box-shadow: none !important;
                margin: 0 !important;
                padding: 0 !important;
                max-width: 100% !important;
                width: 100% !important;
            }

            .page-break {
                page-break-before: always;
            }
        }

        .font-kop {
            font-family: 'Times New Roman', Times, serif;
        }
    </style>
</head>
<body class="bg-slate-100 min-h-screen text-slate-900 antialiased font-sans">
    @php
        $hariTerlambat = $loan->hari_keterlambatan > 0 ? $loan->hari_keterlambatan : $loan->hitungHariTerlambat();
        $nominalDenda = $loan->nominal_denda > 0 ? $loan->nominal_denda : ($hariTerlambat * \App\Models\PeminjamanBuku::TARIF_DENDA_PER_HARI);
        $statusDenda = $loan->status_pembayaran_denda ?: 'Belum Lunas';
        $isLunas = $statusDenda === 'Lunas';
        $nomorDokumen = 'FD/' . ($loan->tanggal_sanksi ? $loan->tanggal_sanksi->format('Y/m') : date('Y/m')) . '/' . $loan->id_peminjaman;
    @endphp

    {{-- Toolbar Cetak (Hanya tampil di layar monitor, disembunyikan saat dicetak) --}}
    <aside class="no-print sticky top-0 z-50 border-b border-slate-200 bg-white/95 backdrop-blur-md px-4 py-3 shadow-xs">
        <div class="mx-auto flex max-w-4xl items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <a
                    href="{{ url()->previous() ?: route('admin.peminjaman.index') }}"
                    class="inline-flex h-9 items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 text-xs font-semibold text-slate-700 transition hover:bg-slate-50"
                >
                    <i class="fa-solid fa-arrow-left text-xs"></i>
                    <span>Kembali</span>
                </a>
                <div class="hidden sm:block">
                    <p class="text-xs font-bold text-slate-800">Pratinjau Dokumen Formulir Denda Keterlambatan</p>
                    <p class="text-[11px] text-slate-500">Format resmi A4 portrait &bull; Tanda tangan peminjam &amp; pustakawan</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <button
                    type="button"
                    onclick="window.print()"
                    class="inline-flex h-9 items-center gap-2 rounded-xl bg-primary px-4 text-xs font-bold text-white shadow-sm transition hover:bg-primary-dark"
                >
                    <i class="fa-solid fa-print text-xs"></i>
                    <span>Cetak Dokumen (Ctrl+P)</span>
                </button>
            </div>
        </div>
    </aside>

    {{-- Kertas Dokumen Formal A4 --}}
    <main class="mx-auto my-4 max-w-4xl px-3 sm:my-8 print:my-0 print:px-0">
        <div class="print-document rounded-2xl border border-slate-200 bg-white p-8 shadow-xl sm:p-12 print:rounded-none print:border-none print:p-0 print:shadow-none">
            
            {{-- KOP SURAT RESMI PERPUSTAKAAN MNC UNIVERSITY --}}
            <header class="border-b-4 border-double border-slate-900 pb-4">
                <div class="flex items-center justify-between gap-4">
                    {{-- Logo MNC University --}}
                    <div class="flex size-20 shrink-0 items-center justify-center">
                        <img
                            src="{{ asset('images/logo/logo-mncu.png') }}"
                            alt="Logo MNC University"
                            class="max-h-20 w-auto object-contain"
                            onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'size-16 rounded-xl bg-slate-900 text-white flex items-center justify-center font-bold text-xl\'>MNC</div>';"
                        >
                    </div>

                    {{-- Teks Institusi Resmi --}}
                    <div class="flex-1 text-center font-kop leading-tight">
                        <h3 class="text-sm font-semibold tracking-wider text-slate-800 uppercase">Yayasan Hary Tanoesoedibjo</h3>
                        <h1 class="text-xl font-bold tracking-tight text-slate-950 sm:text-2xl uppercase">Universitas MNC (MNC University)</h1>
                        <h2 class="text-sm font-bold tracking-normal text-slate-800 uppercase">Unit Pelaksana Teknis (UPT) Perpustakaan Digital &amp; Koleksi Riset</h2>
                        <p class="mt-1 text-[11px] text-slate-700">
                            Gedung Perpustakaan MNC University, Jl. Panjang Blok A8, Jl. Green Garden Pintu Utara, RT.1/RW.3, Kedoya Utara, Kebon Jeruk, Jakarta Barat 11520
                        </p>
                        <p class="text-[10px] text-slate-600">
                            Laman: <span class="underline">https://perpustakaan.mncuniversity.ac.id</span> &bull; Surel: perpustakaan@mncuniversity.ac.id &bull; Telp: (021) 58300050
                        </p>
                    </div>

                    {{-- QR / Barcode Cap --}}
                    <div class="hidden shrink-0 text-right sm:block font-mono text-[10px] text-slate-500">
                        <span class="inline-block rounded-md border border-slate-300 p-1.5 text-center">
                            <i class="fa-solid fa-qrcode text-2xl text-slate-700"></i>
                            <span class="block mt-0.5 font-bold text-[9px]">{{ $loan->id_peminjaman }}</span>
                        </span>
                    </div>
                </div>
            </header>

            {{-- Judul Surat & Status Stempel --}}
            <div class="mt-6 flex flex-col items-center justify-between gap-4 sm:flex-row sm:items-start">
                <div>
                    <h2 class="text-lg font-extrabold text-slate-950 uppercase tracking-wide sm:text-xl">
                        Surat Rincian Denda Keterlambatan Pengembalian Buku
                    </h2>
                    <div class="mt-1 flex flex-wrap items-center gap-3 text-xs text-slate-600">
                        <span>Nomor Dokumen: <strong class="font-mono text-slate-900">{{ $nomorDokumen }}</strong></span>
                        <span>&bull;</span>
                        <span>Tanggal Terbit: <strong>{{ now()->translatedFormat('d F Y') }}</strong></span>
                    </div>
                </div>

                {{-- Stempel Status Pembayaran --}}
                <div class="shrink-0 text-center">
                    @if ($isLunas)
                        <div class="inline-block rounded-lg border-2 border-dashed border-emerald-600 bg-emerald-50/70 px-4 py-2 font-mono text-emerald-800 shadow-2xs">
                            <span class="block text-xs font-extrabold tracking-widest uppercase">LUNAS (CASH)</span>
                            <span class="block text-[10px] font-semibold text-emerald-700">
                                Diterima Tunai: {{ $loan->tanggal_pembayaran_denda ? $loan->tanggal_pembayaran_denda->translatedFormat('d M Y H:i') : now()->translatedFormat('d M Y') }}
                            </span>
                        </div>
                    @else
                        <div class="inline-block rounded-lg border-2 border-dashed border-rose-600 bg-rose-50/80 px-4 py-2 font-mono text-rose-800 shadow-2xs">
                            <span class="block text-xs font-extrabold tracking-widest uppercase">BELUM LUNAS</span>
                            <span class="block text-[10px] font-semibold text-rose-700">Wajib Dibayar Tunai di Meja Sirkulasi</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- BAGIAN 1: Rincian Data Pemustaka (Peminjam yang Terlambat) --}}
            <section class="mt-6">
                <h3 class="flex items-center gap-2 border-b border-slate-300 pb-1.5 text-xs font-bold uppercase tracking-wider text-slate-800">
                    <span class="flex size-5 items-center justify-center rounded-full bg-slate-900 text-[10px] font-bold text-white">1</span>
                    <span>Identitas Pemustaka / Peminjam</span>
                </h3>
                <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-2 text-xs">
                    <div class="flex border-b border-slate-100 py-1">
                        <span class="w-36 text-slate-500">Nama Lengkap:</span>
                        <strong class="flex-1 text-slate-900">{{ $loan->pengguna->nama_lengkap }}</strong>
                    </div>
                    <div class="flex border-b border-slate-100 py-1">
                        <span class="w-36 text-slate-500">NIM / No. Identitas:</span>
                        <strong class="flex-1 font-mono text-slate-900">{{ $loan->pengguna->nim ?: ($loan->pengguna->nidn ?: ($loan->pengguna->nomor_identitas ?: '-')) }}</strong>
                    </div>
                    <div class="flex border-b border-slate-100 py-1">
                        <span class="w-36 text-slate-500">Program Studi / Unit:</span>
                        <span class="flex-1 text-slate-800">{{ $loan->pengguna->prodi?->nama_prodi ?: ($loan->pengguna->peran?->nama_peran ?: 'Sivitas Akademika') }}</span>
                    </div>
                    <div class="flex border-b border-slate-100 py-1">
                        <span class="w-36 text-slate-500">Kategori Pemustaka:</span>
                        <span class="flex-1 font-semibold text-slate-800">{{ $loan->pengguna->peran?->nama_peran ?? 'Mahasiswa' }}</span>
                    </div>
                    <div class="flex border-b border-slate-100 py-1">
                        <span class="w-36 text-slate-500">Email Sivitas:</span>
                        <span class="flex-1 text-slate-800">{{ $loan->pengguna->email }}</span>
                    </div>
                    <div class="flex border-b border-slate-100 py-1">
                        <span class="w-36 text-slate-500">No. WhatsApp / HP:</span>
                        <span class="flex-1 font-mono text-slate-800">{{ $loan->pengguna->no_telepon ?: '-' }}</span>
                    </div>
                </div>
            </section>

            {{-- BAGIAN 2: Rincian Koleksi Buku & Transaksi Peminjaman --}}
            <section class="mt-6">
                <h3 class="flex items-center gap-2 border-b border-slate-300 pb-1.5 text-xs font-bold uppercase tracking-wider text-slate-800">
                    <span class="flex size-5 items-center justify-center rounded-full bg-slate-900 text-[10px] font-bold text-white">2</span>
                    <span>Rincian Koleksi Buku &amp; Sirkulasi</span>
                </h3>
                <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-2 text-xs">
                    <div class="flex border-b border-slate-100 py-1 sm:col-span-2">
                        <span class="w-36 text-slate-500">Judul Koleksi Buku:</span>
                        <strong class="flex-1 text-slate-950 font-bold">{{ $loan->buku->judul_buku }}</strong>
                    </div>
                    <div class="flex border-b border-slate-100 py-1">
                        <span class="w-36 text-slate-500">ID Transaksi Peminjaman:</span>
                        <strong class="flex-1 font-mono text-slate-900">{{ $loan->id_peminjaman }}</strong>
                    </div>
                    <div class="flex border-b border-slate-100 py-1">
                        <span class="w-36 text-slate-500">Kode Eksemplar / Barcode:</span>
                        <strong class="flex-1 font-mono text-slate-900">{{ $loan->eksemplar?->kode_inventaris ?: ($loan->id_eksemplar ?: '-') }}</strong>
                    </div>
                    <div class="flex border-b border-slate-100 py-1">
                        <span class="w-36 text-slate-500">Tanggal Peminjaman:</span>
                        <span class="flex-1 text-slate-800">{{ $loan->tanggal_pinjam ? $loan->tanggal_pinjam->translatedFormat('d F Y') : '-' }}</span>
                    </div>
                    <div class="flex border-b border-slate-100 py-1">
                        <span class="w-36 text-slate-500 font-semibold text-rose-700">Jatuh Tempo (Batas):</span>
                        <strong class="flex-1 text-rose-800">{{ $loan->tanggal_jatuh_tempo ? $loan->tanggal_jatuh_tempo->translatedFormat('d F Y') : '-' }}</strong>
                    </div>
                    <div class="flex border-b border-slate-100 py-1">
                        <span class="w-36 text-slate-500">Tanggal Pengembalian:</span>
                        <span class="flex-1 font-medium {{ $loan->tanggal_kembali ? 'text-slate-800' : 'text-rose-700 italic' }}">
                            {{ $loan->tanggal_kembali ? $loan->tanggal_kembali->translatedFormat('d F Y') : 'Belum Dikembalikan (Fisik Buku Masih Dipinjam)' }}
                        </span>
                    </div>
                    <div class="flex border-b border-slate-100 py-1">
                        <span class="w-36 text-slate-500">Status Transaksi:</span>
                        <span class="flex-1 font-semibold text-slate-900">{{ $loan->status }}</span>
                    </div>
                </div>
            </section>

            {{-- BAGIAN 3: Perhitungan Denda Keterlambatan --}}
            <section class="mt-6">
                <h3 class="flex items-center gap-2 border-b border-slate-300 pb-1.5 text-xs font-bold uppercase tracking-wider text-slate-800">
                    <span class="flex size-5 items-center justify-center rounded-full bg-slate-900 text-[10px] font-bold text-white">3</span>
                    <span>Kalkulasi Tagihan Denda Keterlambatan (Cash / Tunai)</span>
                </h3>

                <div class="mt-3 overflow-hidden rounded-xl border border-slate-200">
                    <table class="w-full text-left text-xs text-slate-800">
                        <thead class="border-b border-slate-200 bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-700">
                            <tr>
                                <th class="px-4 py-2.5">Keterangan Komponen Denda</th>
                                <th class="px-4 py-2.5 text-center">Durasi Terlambat</th>
                                <th class="px-4 py-2.5 text-right">Tarif Satuan Harian</th>
                                <th class="px-4 py-2.5 text-right">Subtotal Denda</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr>
                                <td class="px-4 py-3">
                                    <strong class="text-slate-950 font-semibold block">Denda Keterlambatan Buku Sirkulasi</strong>
                                    <span class="text-[11px] text-slate-500">Dikenakan secara otomatis atas keterlambatan melewati tanggal jatuh tempo</span>
                                </td>
                                <td class="px-4 py-3 text-center font-bold text-rose-700">
                                    {{ $hariTerlambat }} Hari
                                </td>
                                <td class="px-4 py-3 text-right font-mono text-slate-700">
                                    Rp 500 / hari
                                </td>
                                <td class="px-4 py-3 text-right font-mono font-bold text-slate-950">
                                    Rp {{ number_format($nominalDenda, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tbody>
                        <tfoot class="border-t-2 border-slate-300 bg-slate-50/70 font-semibold">
                            <tr>
                                <th colspan="3" class="px-4 py-2.5 text-right text-xs uppercase tracking-wider text-slate-700">
                                    Metode Pembayaran Resmi:
                                </th>
                                <td class="px-4 py-2.5 text-right font-bold text-slate-900">
                                    Cash / Tunai
                                </td>
                            </tr>
                            <tr class="border-t border-slate-200 bg-slate-100/90 text-sm">
                                <th colspan="3" class="px-4 py-3 text-right font-extrabold uppercase tracking-wide text-slate-950">
                                    Total Kewajiban Pembayaran Denda:
                                </th>
                                <td class="px-4 py-3 text-right font-mono text-base font-extrabold text-slate-950">
                                    Rp {{ number_format($nominalDenda, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                {{-- Ketentuan Pembayaran --}}
                <div class="mt-3 rounded-lg border border-slate-200 bg-slate-50/80 p-3 text-[11px] leading-relaxed text-slate-700">
                    <p class="font-bold text-slate-900 mb-1">Ketentuan &amp; Tata Cara Pembayaran:</p>
                    <ul class="list-disc pl-4 space-y-0.5">
                        <li>Opsi pembayaran denda hanya dilayani secara <strong>CASH (TUNAI)</strong> langsung di Meja Sirkulasi Perpustakaan MNC University Lantai 2.</li>
                        <li>Pembayaran denda dilakukan bersamaan dengan penyerahan fisik buku yang dipinjam.</li>
                        <li>Dokumen ini merupakan bukti rincian denda resmi perpustakaan yang sah dan mengikat.</li>
                    </ul>
                </div>
            </section>

            {{-- BAGIAN 4: Area Pengesahan & Tanda Tangan (Peminjam & Pustakawan) --}}
            <section class="mt-8 pt-4 border-t border-slate-200">
                <div class="grid grid-cols-2 gap-8 text-xs text-slate-800">
                    {{-- Kolom Tanda Tangan Peminjam yang Terlambat --}}
                    <div class="flex flex-col justify-between text-center">
                        <div>
                            <p class="font-semibold text-slate-700">Mengetahui &amp; Menyetujui,</p>
                            <p class="font-bold text-slate-950 uppercase mt-0.5">Peminjam / Pemustaka Yang Terlambat</p>
                        </div>

                        {{-- Tempat Tanda Tangan --}}
                        <div class="my-6 flex h-20 items-center justify-center">
                            <span class="text-[10px] text-slate-400 italic print:text-transparent">
                                [ Tanda Tangan Pemustaka ]
                            </span>
                        </div>

                        <div>
                            <p class="font-bold text-slate-950 underline decoration-slate-900 decoration-1 underline-offset-4 text-sm">
                                {{ $loan->pengguna->nama_lengkap }}
                            </p>
                            <p class="font-mono text-[11px] text-slate-600 mt-0.5">
                                NIM / Identitas: {{ $loan->pengguna->nim ?: ($loan->pengguna->nidn ?: ($loan->pengguna->nomor_identitas ?: '-')) }}
                            </p>
                        </div>
                    </div>

                    {{-- Kolom Tanda Tangan Pustakawan Perpustakaan --}}
                    <div class="flex flex-col justify-between text-center">
                        <div>
                            <p class="font-semibold text-slate-700">
                                Jakarta Barat, {{ now()->translatedFormat('d F Y') }}
                            </p>
                            <p class="font-bold text-slate-950 uppercase mt-0.5">Petugas Pustakawan / Administrasi Sirkulasi</p>
                        </div>

                        {{-- Tempat Tanda Tangan & Cap Perpustakaan --}}
                        <div class="my-6 flex h-20 items-center justify-center">
                            <span class="text-[10px] text-slate-400 italic print:text-transparent">
                                [ Tanda Tangan &amp; Cap Sirkulasi Perpustakaan ]
                            </span>
                        </div>

                        <div>
                            <p class="font-bold text-slate-950 underline decoration-slate-900 decoration-1 underline-offset-4 text-sm">
                                {{ $pustakawan->nama_lengkap ?? (Auth::user()?->nama_lengkap ?? 'Petugas Perpustakaan MNCU') }}
                            </p>
                            <p class="text-[11px] text-slate-600 mt-0.5">
                                UPT Perpustakaan MNC University
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Footer Catatan Cetak Dokumen --}}
            <footer class="mt-8 border-t border-slate-200 pt-3 text-[10px] text-slate-500 flex items-center justify-between">
                <span>Dicetak otomatis melalui Sistem Sirkulasi Perpustakaan MNC University &bull; {{ now()->format('d/m/Y H:i:s') }}</span>
                <span class="font-mono">ID: {{ $loan->id_peminjaman }} | UPT-PERPUS-MNCU</span>
            </footer>

        </div>
    </main>
</body>
</html>

