<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Label Stiker - {{ $book->judul_buku }} ({{ $barcodeText }})</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800|jetbrains-mono:500,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Pengaturan Cetak Khusus Label Stiker 8cm x 5cm (80mm x 50mm) */
        @page {
            size: 80mm 50mm;
            margin: 0;
        }

        @media print {
            html, body {
                width: 80mm !important;
                height: 50mm !important;
                margin: 0 !important;
                padding: 0 !important;
                background: #ffffff !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .no-print {
                display: none !important;
            }

            .print-area {
                padding: 0 !important;
                margin: 0 !important;
                display: block !important;
                background: transparent !important;
            }

            .sticker-card {
                width: 80mm !important;
                height: 50mm !important;
                max-width: 80mm !important;
                max-height: 50mm !important;
                border: none !important;
                border-radius: 0 !important;
                box-shadow: none !important;
                padding: 1.6mm 2.2mm !important;
                margin: 0 !important;
                page-break-after: avoid;
                page-break-inside: avoid;
            }
        }

        /* Ukuran layar preview agar persis proporsional 80mm x 50mm */
        .sticker-card {
            width: 80mm;
            height: 50mm;
            box-sizing: border-box;
            background: #ffffff;
            color: #0f172a;
            font-family: 'Inter', sans-serif;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .barcode-svg svg {
            width: 54mm !important;
            max-width: 90% !important;
            height: 6.2mm !important;
            display: block;
            margin: 0 auto;
        }
    </style>
</head>
<body class="bg-slate-100 min-h-screen text-slate-800 antialiased font-sans flex flex-col">

    {{-- Top Toolbar (Hanya Tampil di Layar, Tersembunyi Saat Dicetak / Disimpan ke PDF) --}}
    <header class="no-print bg-white border-b border-slate-200 sticky top-0 z-30 shadow-xs">
        <div class="max-w-5xl mx-auto px-4 py-3 flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <a
                    href="{{ route('admin.books.show', $book->id_buku) }}"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 hover:text-slate-900 rounded-lg transition-colors"
                >
                    <i class="fa-solid fa-arrow-left"></i> Kembali ke Buku
                </a>
                <div class="hidden sm:block h-4 w-px bg-slate-300"></div>
                <div>
                    <h1 class="text-sm font-bold text-slate-900 leading-tight">
                        Cetak Label Stiker &amp; Barcode Koleksi
                    </h1>
                    <p class="text-[11px] text-slate-500">
                        Format Standar Perpustakaan: <span class="font-semibold text-primary">8 cm × 5 cm (80mm × 50mm)</span>
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                {{-- Selector Eksemplar Jika Koleksi Memiliki Banyak Eksemplar --}}
                @if ($book->eksemplar->count() > 1)
                    <div class="relative">
                        <select
                            onchange="window.location.href = this.value;"
                            class="text-xs bg-slate-50 border border-slate-300 rounded-lg px-2.5 py-1.5 pr-7 font-mono font-medium text-slate-700 focus:outline-none focus:border-primary"
                            title="Pilih eksemplar fisik yang ingin dicetak stikernya"
                        >
                            @foreach ($book->eksemplar as $ekItem)
                                <option
                                    value="{{ route('admin.books.cetak-label', ['buku' => $book->id_buku, 'eksemplar' => $ekItem->id_eksemplar]) }}"
                                    {{ $eksemplar?->id_eksemplar === $ekItem->id_eksemplar ? 'selected' : '' }}
                                >
                                    {{ $ekItem->kode_inventaris }} ({{ $ekItem->nomor_panggil ?? 'Salinan' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <button
                    type="button"
                    onclick="window.print()"
                    class="inline-flex items-center gap-2 bg-primary hover:bg-primary-dark text-white text-xs font-semibold px-4 py-2 rounded-xl shadow-sm transition-all active:scale-95 cursor-pointer"
                >
                    <i class="fa-solid fa-print text-sm"></i>
                    <span>Cetak Stiker / Simpan PDF</span>
                </button>
            </div>
        </div>
    </header>

    {{-- Petunjuk Penggunaan Cetak / Simpan PDF --}}
    <main class="flex-1 max-w-5xl w-full mx-auto p-4 sm:p-6 flex flex-col items-center justify-center">
        <div class="no-print w-full max-w-xl mb-6 bg-blue-50/80 border border-blue-200 rounded-2xl p-4 text-xs text-blue-950">
            <div class="flex items-start gap-3">
                <div class="size-8 rounded-lg bg-blue-600 text-white flex items-center justify-center shrink-0 mt-0.5">
                    <i class="fa-solid fa-circle-info"></i>
                </div>
                <div class="space-y-1">
                    <p class="font-bold">Panduan Mencetak Stiker atau Menyimpan PDF:</p>
                    <ul class="list-disc list-inside space-y-0.5 text-blue-900/90 text-[11px] leading-relaxed">
                        <li>Ukuran dokumen otomatis disesuaikan ke <strong class="font-semibold">80 mm × 50 mm (8 × 5 cm)</strong>.</li>
                        <li>Di jendela cetak browser: Pilih <strong class="font-semibold">Destination: 'Save as PDF'</strong> untuk mengunduh dokumen PDF atau pilih printer stiker termal.</li>
                        <li>Atur <strong class="font-semibold">Margins: None</strong> dan centang opsi <strong class="font-semibold">Background graphics</strong> agar sampul buku, garis, dan barcode tercetak lengkap.</li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- AREA PRATINJAU FISIK STIKER (80mm x 50mm) --}}
        <div class="print-area flex flex-col items-center">
            <div class="no-print text-center mb-2">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-200/80 px-3 py-1 text-[11px] font-semibold text-slate-700">
                    <i class="fa-solid fa-ruler-combined text-primary"></i>
                    Pratinjau Stiker Fisik: 80 mm (Panjang) × 50 mm (Tinggi)
                </span>
            </div>

            {{-- THE PHYSICAL LABEL STICKER (8cm x 5cm) --}}
            <div class="sticker-card rounded-xl border-2 border-dashed border-slate-300 p-2 shadow-lg relative select-none">

                {{-- Baris 1: Header Perpustakaan MNC University --}}
                <div class="border-b border-slate-900 pb-0.5 flex items-center justify-between shrink-0">
                    <div class="flex items-center gap-1">
                        <div class="size-3.5 rounded bg-primary flex items-center justify-center text-white text-[7px] font-black">
                            M
                        </div>
                        <span class="text-[8px] font-black uppercase tracking-wider text-slate-900">
                            PERPUSTAKAAN MNC UNIVERSITY
                        </span>
                    </div>
                    <div class="flex items-center gap-1">
                        <span class="text-[6.5px] font-mono font-bold text-slate-700">
                            ID: {{ $book->id_buku }}
                        </span>
                        <span class="text-[6.5px] font-bold uppercase tracking-wider text-slate-700 border border-slate-300 rounded px-1 py-0.2 bg-slate-50">
                            {{ $book->jenisBuku?->nama_jenis_buku ?? 'Koleksi' }}
                        </span>
                    </div>
                </div>

                {{-- Baris 2: Bagian Kiri (Cover Buku + Nomor Panggil) & Bagian Kanan (Seluruh Rincian & Deskripsi Input Koleksi) --}}
                <div class="flex-1 flex items-stretch gap-1.5 py-1 min-h-0 overflow-hidden">

                    {{-- Sisi Kiri Stiker: Cover Buku + Nomor Panggil --}}
                    <div class="w-[18mm] shrink-0 flex flex-col justify-between gap-0.5">
                        {{-- Gambar Sampul / Cover Buku --}}
                        <div class="w-full h-[21mm] rounded border border-slate-300 bg-slate-100 overflow-hidden shrink-0">
                            <img
                                src="{{ $book->sampul_url }}"
                                alt="Cover {{ $book->judul_buku }}"
                                class="w-full h-full object-cover"
                                onerror="this.onerror=null; this.src='{{ asset('images/buku/default-cover.jpg') }}';"
                            >
                        </div>

                        {{-- Kotak Nomor Panggil (Call Number) di Bawah Cover --}}
                        <div class="w-full border border-slate-800 rounded bg-slate-50 px-0.5 py-0.5 text-center font-mono leading-none">
                            <span class="block text-[4.5px] font-bold text-slate-500 uppercase tracking-tight">NO. PANGGIL</span>
                            <div class="mt-0.5 text-[6px] font-extrabold text-slate-900 truncate" title="{{ $nomorPanggil }}">
                                {{ $nomorPanggil ?: ($book->isbn ?: $book->id_buku) }}
                            </div>
                        </div>
                    </div>

                    {{-- Sisi Kanan Stiker: Seluruh Metadata & Deskripsi Koleksi yang Diinput --}}
                    <div class="flex-1 min-w-0 flex flex-col justify-between text-left overflow-hidden leading-tight">
                        {{-- Judul Buku --}}
                        <div>
                            <p class="text-[7.5px] font-extrabold text-slate-900 leading-[1.15] line-clamp-2" title="{{ $book->judul_buku }}">
                                {{ $book->judul_buku }}
                            </p>

                            {{-- Pengarang & Penerbit --}}
                            <p class="mt-0.5 text-[6px] text-slate-700 truncate">
                                <span class="font-bold text-slate-900">Penulis:</span> {{ $book->penulis->pluck('nama_penulis')->join(', ') ?: '-' }}
                                <span class="mx-0.5 text-slate-300">|</span>
                                <span class="font-bold text-slate-900">Penerbit:</span> {{ $book->penerbit?->nama_penerbit ?? '-' }}
                            </p>
                        </div>

                        {{-- Grid Spesifikasi Lengkap (Kategori, Tahun/Edisi, Dimensi Fisik, Halaman, ISBN, Lokasi Rak) --}}
                        <div class="grid grid-cols-2 gap-x-1.5 gap-y-0.5 border-y border-slate-200 py-0.5 my-0.5 text-[5.6px] text-slate-700">
                            <div class="truncate">
                                <span class="font-bold text-slate-900">Kategori:</span> {{ $book->kategoriBuku?->nama_kategori ?? '-' }}
                            </div>
                            <div class="truncate">
                                <span class="font-bold text-slate-900">Dimensi:</span> {{ $book->dimensi ?: '-' }}
                            </div>
                            <div class="truncate">
                                <span class="font-bold text-slate-900">Thn/Edisi:</span> {{ $book->tahun_terbit ?: '-' }}{{ $book->edisi ? ' / ' . $book->edisi : '' }}
                            </div>
                            <div class="truncate">
                                <span class="font-bold text-slate-900">Fisik:</span> {{ $book->jumlah_halaman ? $book->jumlah_halaman . ' hlm.' : '-' }} &bull; {{ $eksemplar?->lokasi_rak ?: 'Lantai 2' }}
                            </div>
                            <div class="truncate">
                                <span class="font-bold text-slate-900">ISBN:</span> <span class="font-mono">{{ $book->isbn ?: '-' }}</span>
                            </div>
                            <div class="truncate">
                                <span class="font-bold text-slate-900">No. Barcode:</span> <span class="font-mono font-bold text-slate-900">{{ $barcodeText }}</span>
                            </div>
                        </div>

                        {{-- Deskripsi / Sinopsis & Kata Kunci Koleksi --}}
                        <div class="space-y-0.5 overflow-hidden">
                            <p class="text-[5.4px] text-slate-600 leading-[1.15] line-clamp-2">
                                <span class="font-bold text-slate-900">Deskripsi:</span>
                                {{ $book->deskripsi ?: 'Koleksi resmi Perpustakaan MNC University.' }}
                            </p>
                            @if (! empty($book->kata_kunci))
                                <p class="text-[5px] text-slate-500 truncate">
                                    <span class="font-bold text-slate-700">Kata Kunci:</span> {{ $book->kata_kunci }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Baris 3: Generated SVG Barcode & Nomor Registrasi Inventaris --}}
                <div class="border-t border-slate-800 pt-0.5 flex flex-col items-center justify-center shrink-0">
                    <div class="barcode-svg w-full flex items-center justify-center overflow-hidden">
                        {!! $barcodeSvg !!}
                    </div>
                    <span class="mt-0.5 font-mono text-[6.5px] font-bold tracking-[0.18em] text-slate-900 leading-none">
                        {{ $barcodeText }}
                    </span>
                </div>
            </div>

            {{-- Tombol Tindakan Cepat di Bawah Stiker --}}
            <div class="no-print mt-6 flex items-center gap-3">
                <button
                    type="button"
                    onclick="window.print()"
                    class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-2.5 text-xs font-bold text-white shadow-md hover:bg-emerald-700 transition-all active:scale-95 cursor-pointer"
                >
                    <i class="fa-solid fa-print"></i> Cetak Dokumen / Simpan PDF
                </button>
                <a
                    href="{{ route('admin.books.show', $book->id_buku) }}"
                    class="inline-flex items-center gap-1.5 rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors"
                >
                    Kembali ke Detail Buku
                </a>
            </div>
        </div>
    </main>

    <footer class="no-print mt-auto py-4 text-center text-xs text-slate-400 border-t border-slate-200 bg-white">
        Perpustakaan MNC University &bull; Generator Label Stiker Koleksi &amp; Barcode Code 128
    </footer>

</body>
</html>
