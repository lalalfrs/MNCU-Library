<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\EksemplarBuku;
use App\Models\PeminjamanBuku;
use App\Models\Pengguna;
use App\Services\PeminjamanService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PeminjamanAdminController extends Controller
{
    public function __construct(
        protected PeminjamanService $peminjamanService
    ) {}

    /**
     * Tampilkan semua data pinjaman buku beserta metrik keterlambatan.
     */
    public function index(Request $request): View
    {
        // 1. Sinkronisasi otomatis data keterlambatan yang melewati jatuh tempo
        $this->peminjamanService->sinkronisasiStatusKeterlambatan();

        $q = trim((string) $request->query('q', ''));
        $status = trim((string) $request->query('status', ''));

        // 2. Metrik Sirkulasi & Keterlambatan Global (tanpa sanksi denda)
        $totalPinjaman = PeminjamanBuku::count();
        $totalAktif = PeminjamanBuku::where('status', 'Dipinjam')->whereNull('tanggal_kembali')->count();
        $totalTerlambat = PeminjamanBuku::terlambat()->count();
        $totalMenungguVerifikasi = PeminjamanBuku::where('status', 'Menunggu Verifikasi')->whereNull('tanggal_kembali')->count();
        $totalKembali = PeminjamanBuku::whereNotNull('tanggal_kembali')->count();

        // 3. Query daftar peminjaman
        $query = PeminjamanBuku::with(['pengguna.peran', 'buku', 'eksemplar'])->latest();

        if ($q !== '') {
            $query->where(function ($sub) use ($q): void {
                $sub->where('id_peminjaman', 'like', "%{$q}%")
                    ->orWhereHas('pengguna', function ($userSub) use ($q): void {
                        $userSub->where('nama_lengkap', 'like', "%{$q}%")
                            ->orWhere('nim', 'like', "%{$q}%")
                            ->orWhere('email', 'like', "%{$q}%");
                    })
                    ->orWhereHas('buku', function ($bookSub) use ($q): void {
                        $bookSub->where('judul_buku', 'like', "%{$q}%")
                            ->orWhere('isbn', 'like', "%{$q}%");
                    })
                    ->orWhereHas('eksemplar', function ($ekSub) use ($q): void {
                        $ekSub->where('kode_inventaris', 'like', "%{$q}%")
                            ->orWhere('nomor_panggil', 'like', "%{$q}%");
                    });
            });
        }

        if ($status === 'dipinjam') {
            $query->where('status', 'Dipinjam')->whereNull('tanggal_kembali');
        } elseif ($status === 'terlambat') {
            $query->terlambat();
        } elseif ($status === 'menunggu-verifikasi') {
            $query->where('status', 'Menunggu Verifikasi')->whereNull('tanggal_kembali');
        } elseif ($status === 'kembali') {
            $query->whereNotNull('tanggal_kembali');
        }

        $loans = $query->paginate(15)->withQueryString();

        return view('admin.peminjaman.index', compact(
            'loans',
            'q',
            'status',
            'totalPinjaman',
            'totalAktif',
            'totalTerlambat',
            'totalMenungguVerifikasi',
            'totalKembali'
        ));
    }

    /**
     * Tampilkan formulir input peminjaman buku baru.
     */
    public function create(Request $request): View
    {
        $selectedUserId = $request->query('user_id');
        $selectedBookId = $request->query('book_id');

        // Daftar mahasiswa & pemustaka
        $users = Pengguna::with('peran')
            ->orderBy('nama_lengkap')
            ->get();

        // Daftar koleksi buku yang memiliki eksemplar tersedia
        $books = Buku::withCount([
            'eksemplar as eksemplar_tersedia_count' => function ($sq): void {
                $sq->where('status', 'Tersedia');
            },
        ])
            ->orderBy('judul_buku')
            ->get();

        // Eksemplar buku yang tersedia untuk buku terpilih
        $availableCopies = collect();
        if ($selectedBookId) {
            $availableCopies = EksemplarBuku::where('id_buku', $selectedBookId)
                ->where('status', 'Tersedia')
                ->orderBy('kode_inventaris')
                ->get();
        }

        // Info eligibilitas jika user sudah dipilih sebelumnya
        $userEligibility = null;
        if ($selectedUserId) {
            $userEligibility = $this->peminjamanService->cekEligibilitas($selectedUserId);
        }

        return view('admin.peminjaman.create', compact(
            'users',
            'books',
            'selectedUserId',
            'selectedBookId',
            'availableCopies',
            'userEligibility'
        ));
    }

    /**
     * Simpan data transaksi peminjaman buku baru (dengan validasi eligibilitas dan stok berkurang).
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'id_pengguna' => 'required|string|exists:pengguna,id_pengguna',
            'id_eksemplar' => 'required|string|exists:eksemplar_buku,id_eksemplar',
            'tanggal_pinjam' => 'required|date',
            'tanggal_jatuh_tempo' => 'required|date|after_or_equal:tanggal_pinjam',
            'catatan' => 'nullable|string|max:500',
        ], [
            'id_pengguna.required' => 'Pilih mahasiswa atau pemustaka peminjam buku.',
            'id_eksemplar.required' => 'Pilih eksemplar fisik buku yang tersedia.',
            'tanggal_pinjam.required' => 'Tanggal peminjaman buku wajib diisi.',
            'tanggal_jatuh_tempo.required' => 'Tanggal batas pengembalian wajib diisi.',
            'tanggal_jatuh_tempo.after_or_equal' => 'Tanggal pengembalian tidak boleh sebelum tanggal peminjaman.',
        ]);

        try {
            $peminjaman = $this->peminjamanService->buatPeminjaman(
                $validated['id_pengguna'],
                $validated['id_eksemplar'],
                $validated['tanggal_pinjam'],
                $validated['tanggal_jatuh_tempo'],
                $validated['catatan'] ?? null
            );

            return redirect()->route('admin.peminjaman.index')
                ->with('success', "Peminjaman buku \"{$peminjaman->buku->judul_buku}\" berhasil dicatat untuk {$peminjaman->pengguna->nama_lengkap}. Status eksemplar buku telah dikurangi.");
        } catch (ValidationException $e) {
            return back()->withInput()->withErrors($e->errors());
        }
    }

    /**
     * Tampilkan detail transaksi peminjaman, durasi keterlambatan, dan riwayat sirkulasi.
     */
    public function show(string $id): View
    {
        $loan = PeminjamanBuku::with(['pengguna.peran', 'pengguna.prodi', 'buku.penulis', 'buku.penerbit', 'eksemplar'])
            ->findOrFail($id);

        $hariTerlambat = $loan->hitungHariTerlambat(Carbon::now());

        return view('admin.peminjaman.show', compact('loan', 'hariTerlambat'));
    }

    /**
     * Tampilkan Inbox Pengajuan Peminjaman & Pengembalian Buku yang memerlukan validasi admin.
     * Mendukung multi-user request untuk buku yang sama, di mana admin berhak menentukan prioritas persetujuan.
     */
    public function inbox(Request $request): View
    {
        $this->peminjamanService->sinkronisasiStatusKeterlambatan();

        $q = trim((string) $request->query('q', ''));

        // Hitung total pengajuan peminjaman baru yang menunggu persetujuan
        $totalMenungguPinjam = PeminjamanBuku::where('status', 'Menunggu Persetujuan')->count();

        // Hitung total pengajuan pengembalian yang menunggu verifikasi admin
        $totalMenungguVerifikasi = PeminjamanBuku::where('status', 'Menunggu Verifikasi')
            ->whereNull('tanggal_kembali')
            ->count();

        // Hitung total pengajuan yang sudah diverifikasi (history)
        $totalRiwayatVerifikasi = PeminjamanBuku::where(function ($sq): void {
            $sq->whereIn('status', ['Kembali', 'Dipinjam', 'Ditolak'])
                ->where(function ($sub): void {
                    $sub->whereNotNull('tanggal_pengajuan_kembali')
                        ->orWhereNotNull('tanggal_pengajuan_pinjam');
                });
        })->count();

        // Tentukan tab aktif
        $rawTab = trim((string) $request->query('tab', ''));
        if ($rawTab === '' || $rawTab === 'menunggu') {
            $tab = $totalMenungguPinjam > 0 ? 'pinjam' : ($totalMenungguVerifikasi > 0 ? 'kembali' : 'pinjam');
            if ($rawTab === 'menunggu') {
                $tab = 'kembali';
            }
        } else {
            $tab = $rawTab;
        }

        // Query data berdasarkan tab
        $query = PeminjamanBuku::with(['pengguna.peran', 'pengguna.prodi', 'buku.eksemplar', 'eksemplar']);

        if ($tab === 'pinjam') {
            $query->where('status', 'Menunggu Persetujuan')
                ->latest('tanggal_pengajuan_pinjam');
        } elseif ($tab === 'kembali') {
            $query->where('status', 'Menunggu Verifikasi')
                ->whereNull('tanggal_kembali')
                ->latest('tanggal_pengajuan_kembali');
        } else {
            // Tab riwayat
            $query->where(function ($sq): void {
                $sq->whereIn('status', ['Kembali', 'Dipinjam', 'Ditolak'])
                    ->where(function ($sub): void {
                        $sub->whereNotNull('tanggal_pengajuan_kembali')
                            ->orWhereNotNull('tanggal_pengajuan_pinjam');
                    });
            })->latest();
        }

        if ($q !== '') {
            $query->where(function ($sub) use ($q): void {
                $sub->where('id_peminjaman', 'like', "%{$q}%")
                    ->orWhereHas('pengguna', function ($userSub) use ($q): void {
                        $userSub->where('nama_lengkap', 'like', "%{$q}%")
                            ->orWhere('nim', 'like', "%{$q}%")
                            ->orWhere('email', 'like', "%{$q}%");
                    })
                    ->orWhereHas('buku', function ($bookSub) use ($q): void {
                        $bookSub->where('judul_buku', 'like', "%{$q}%")
                            ->orWhere('isbn', 'like', "%{$q}%");
                    })
                    ->orWhereHas('eksemplar', function ($ekSub) use ($q): void {
                        $ekSub->where('kode_inventaris', 'like', "%{$q}%")
                            ->orWhere('nomor_panggil', 'like', "%{$q}%");
                    });
            });
        }

        $requests = $query->paginate(15)->withQueryString();

        // Hitung persaingan permohonan multi-user untuk buku yang sama pada tab peminjaman
        $competingCounts = PeminjamanBuku::where('status', 'Menunggu Persetujuan')
            ->select('id_buku', DB::raw('count(*) as total'))
            ->groupBy('id_buku')
            ->pluck('total', 'id_buku')
            ->all();

        return view('admin.inbox.index', compact(
            'requests',
            'q',
            'tab',
            'totalMenungguPinjam',
            'totalMenungguVerifikasi',
            'totalRiwayatVerifikasi',
            'competingCounts'
        ));
    }

    /**
     * Admin menyetujui pengajuan peminjaman buku dari pemustaka.
     * Admin berhak memilih eksemplar fisik mana yang dialokasikan atau mendahulukan pemohon tertentu.
     */
    public function setujuiPeminjaman(Request $request, string $id): RedirectResponse
    {
        $request->validate([
            'id_eksemplar' => 'nullable|string|exists:eksemplar_buku,id_eksemplar',
            'catatan' => 'nullable|string|max:500',
        ]);

        try {
            $loan = $this->peminjamanService->setujuiPeminjaman(
                $id,
                $request->input('id_eksemplar'),
                $request->input('catatan')
            );

            $pesan = "Pengajuan peminjaman buku \"{$loan->buku->judul_buku}\" oleh {$loan->pengguna->nama_lengkap} berhasil disetujui! Eksemplar ({$loan->eksemplar->kode_inventaris}) telah dialokasikan.";

            return redirect()->route('admin.inbox', ['tab' => 'pinjam'])->with('success', $pesan);
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors());
        }
    }

    /**
     * Admin menolak pengajuan peminjaman buku dari pemustaka (misal karena stok terbatas atau dialokasikan ke orang lain).
     */
    public function tolakPeminjaman(Request $request, string $id): RedirectResponse
    {
        $request->validate([
            'alasan' => 'nullable|string|max:500',
        ]);

        try {
            $loan = $this->peminjamanService->tolakPeminjaman(
                $id,
                $request->input('alasan')
            );

            $pesan = "Pengajuan peminjaman buku \"{$loan->buku->judul_buku}\" oleh {$loan->pengguna->nama_lengkap} telah ditolak.";

            return redirect()->route('admin.inbox', ['tab' => 'pinjam'])->with('success', $pesan);
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors());
        }
    }

    /**
     * Proses pengembalian buku (status eksemplar kembali bertambah menjadi 'Tersedia').
     */
    public function kembalikan(Request $request, string $id): RedirectResponse
    {
        $request->validate([
            'catatan' => 'nullable|string|max:500',
            'redirect_to' => 'nullable|string',
        ]);

        try {
            $loan = $this->peminjamanService->prosesPengembalian(
                $id,
                $request->input('catatan')
            );

            $pesan = "Pengembalian buku \"{$loan->buku->judul_buku}\" oleh {$loan->pengguna->nama_lengkap} berhasil divalidasi dan disetujui. Status eksemplar telah kembali Tersedia.";
            if ($loan->hari_keterlambatan > 0) {
                $pesan .= " Tercatat keterlambatan selama {$loan->hari_keterlambatan} hari.";
            }

            if ($request->input('redirect_to') === 'inbox' || str_contains((string) $request->headers->get('referer'), '/admin/inbox')) {
                return redirect()->route('admin.inbox')->with('success', $pesan);
            }

            return redirect()->route('admin.peminjaman.index')->with('success', $pesan);
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors());
        }
    }

    /**
     * Tolak pengajuan pengembalian buku dari mahasiswa (misal fisik belum diserahkan ke meja sirkulasi).
     */
    public function tolakPengembalian(Request $request, string $id): RedirectResponse
    {
        $request->validate([
            'alasan' => 'nullable|string|max:500',
            'redirect_to' => 'nullable|string',
        ]);

        try {
            $loan = $this->peminjamanService->tolakPengajuanPengembalian(
                $id,
                $request->input('alasan')
            );

            $pesan = "Pengajuan pengembalian buku \"{$loan->buku->judul_buku}\" oleh {$loan->pengguna->nama_lengkap} telah ditolak. Status transaksi dikembalikan menjadi {$loan->status}.";

            if ($request->input('redirect_to') === 'inbox' || str_contains((string) $request->headers->get('referer'), '/admin/inbox')) {
                return redirect()->route('admin.inbox')->with('success', $pesan);
            }

            return redirect()->route('admin.peminjaman.index')->with('success', $pesan);
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors());
        }
    }

    /**
     * Kirim notifikasi peringatan bahwa batas waktu peminjaman telah lewat.
     */
    public function kirimPeringatan(string $id): RedirectResponse
    {
        try {
            $loan = $this->peminjamanService->kirimPeringatanKeterlambatan($id);

            return back()->with('success', "Notifikasi peringatan keterlambatan berhasil dikirimkan ke {$loan->pengguna->nama_lengkap} (keterlambatan {$loan->hari_keterlambatan} hari).");
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors());
        }
    }

    /**
     * Admin menginput / mencatat data keterlambatan dan sanksi untuk peminjam yang belum mengembalikan buku (sesuai flowchart).
     */
    public function inputKeterlambatan(Request $request, string $id): RedirectResponse
    {
        $validated = $request->validate([
            'sanksi' => 'required|string|max:100',
            'catatan_keterlambatan' => 'nullable|string|max:500',
        ], [
            'sanksi.required' => 'Pilih atau tentukan bentuk sanksi keterlambatan bagi peminjam.',
        ]);

        try {
            $loan = $this->peminjamanService->inputKeterlambatanDanSanksi(
                $id,
                $validated['sanksi'],
                $validated['catatan_keterlambatan'] ?? null
            );

            return back()->with('success', "Data keterlambatan & sanksi \"{$loan->sanksi}\" berhasil dicatat ke sistem untuk peminjam {$loan->pengguna->nama_lengkap}.");
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors());
        }
    }

    /**
     * Cek eligibilitas mahasiswa / pemustaka via AJAX untuk modal peminjaman buku.
     */
    public function cekEligibilitasUser(string $idPengguna): JsonResponse
    {
        $hasil = $this->peminjamanService->cekEligibilitas($idPengguna);

        return response()->json([
            'success' => true,
            'data' => $hasil,
        ], 200);
    }
}
