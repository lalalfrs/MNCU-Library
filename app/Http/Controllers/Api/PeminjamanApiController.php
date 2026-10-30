<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\EksemplarBuku;
use App\Models\Pengguna;
use App\Services\PeminjamanService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PeminjamanApiController extends Controller
{
    public function __construct(
        protected PeminjamanService $peminjamanService
    ) {}

    /**
     * Endpoint untuk membuat Sanctum Personal Access Token (untuk pengujian API atau integrasi sistem).
     */
    public function generateToken(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
            'token_name' => 'nullable|string',
        ]);

        $user = Pengguna::where('email', $request->email)->first();

        if (! $user || ! password_verify($request->password, $user->kata_sandi)) {
            return response()->json([
                'success' => false,
                'message' => 'Kredensial login tidak valid.',
            ], 401);
        }

        $tokenName = $request->input('token_name', 'peminjaman-api-token');
        $token = $user->createToken($tokenName)->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Token Sanctum berhasil diterbitkan.',
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => [
                'id_pengguna' => $user->id_pengguna,
                'nama_lengkap' => $user->nama_lengkap,
                'email' => $user->email,
                'role' => $user->role,
                'is_superadmin' => $user->isSuperAdmin(),
            ],
        ]);
    }

    /**
     * Validasi eligibilitas mahasiswa melalui Sanctum:
     * - Jika ada buku yang belum dikembalikan -> User tidak bisa meminjam buku.
     * - Tidak ada buku yang belum dikembalikan -> Memenuhi syarat.
     */
    public function cekEligibilitas(string $idPengguna): JsonResponse
    {
        $hasil = $this->peminjamanService->cekEligibilitas($idPengguna);

        return response()->json([
            'success' => true,
            'data' => $hasil,
        ], $hasil['eligible'] ? 200 : 422);
    }

    /**
     * Daftar eksemplar buku yang berstatus Tersedia untuk dipinjam.
     */
    public function eksemplarTersedia(string $idBuku): JsonResponse
    {
        $buku = Buku::findOrFail($idBuku);
        $eksemplar = EksemplarBuku::where('id_buku', $buku->id_buku)
            ->where('status', 'Tersedia')
            ->orderBy('kode_inventaris')
            ->get();

        return response()->json([
            'success' => true,
            'buku' => [
                'id_buku' => $buku->id_buku,
                'judul_buku' => $buku->judul_buku,
                'isbn' => $buku->isbn,
            ],
            'total_tersedia' => $eksemplar->count(),
            'eksemplar' => $eksemplar,
        ]);
    }

    /**
     * Validasi status pengembalian buku (cek apakah valid, terlambat, dan berapa hari keterlambatannya).
     */
    public function validasiPengembalian(Request $request): JsonResponse
    {
        $request->validate([
            'id_peminjaman' => 'required|string',
        ]);

        $hasil = $this->peminjamanService->validasiPengembalian($request->id_peminjaman);

        if (! $hasil['sukses']) {
            return response()->json([
                'success' => false,
                'message' => $hasil['message'],
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => $hasil['message'],
            'data' => [
                'id_peminjaman' => $hasil['peminjaman']->id_peminjaman,
                'nama_peminjam' => $hasil['peminjaman']->pengguna->nama_lengkap,
                'judul_buku' => $hasil['peminjaman']->buku->judul_buku,
                'kode_inventaris' => $hasil['peminjaman']->eksemplar->kode_inventaris,
                'tanggal_pinjam' => $hasil['peminjaman']->tanggal_pinjam->format('Y-m-d'),
                'tanggal_jatuh_tempo' => $hasil['peminjaman']->tanggal_jatuh_tempo->format('Y-m-d'),
                'terlambat' => $hasil['terlambat'],
                'hari_terlambat' => $hasil['hari_terlambat'],
            ],
        ]);
    }

    /**
     * Buat peminjaman buku baru melalui Sanctum API.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'id_pengguna' => 'nullable|string|exists:pengguna,id_pengguna',
            'id_buku' => 'nullable|string|exists:buku,id_buku',
            'id_eksemplar' => 'nullable|string|exists:eksemplar_buku,id_eksemplar',
            'tanggal_pinjam' => 'nullable|date',
            'tanggal_jatuh_tempo' => 'nullable|date|after_or_equal:tanggal_pinjam',
            'catatan' => 'nullable|string|max:500',
        ]);

        $idPengguna = $validated['id_pengguna'] ?? $request->user()?->id_pengguna;
        if (! $idPengguna) {
            return response()->json([
                'success' => false,
                'message' => 'ID pengguna tidak terautentikasi.',
            ], 401);
        }

        $idEksemplar = $validated['id_eksemplar'] ?? null;
        if (! $idEksemplar && ! empty($validated['id_buku'])) {
            $availableExemplar = EksemplarBuku::where('id_buku', $validated['id_buku'])
                ->where('status', 'Tersedia')
                ->first();

            if (! $availableExemplar) {
                return response()->json([
                    'success' => false,
                    'message' => 'Maaf, seluruh eksemplar fisik buku ini sedang dipinjam atau tidak tersedia di rak.',
                ], 422);
            }
            $idEksemplar = $availableExemplar->id_eksemplar;
        }

        if (! $idEksemplar) {
            return response()->json([
                'success' => false,
                'message' => 'Parameter id_eksemplar atau id_buku wajib disertakan.',
            ], 422);
        }

        $tanggalPinjam = $validated['tanggal_pinjam'] ?? Carbon::now()->toDateString();
        $tanggalJatuhTempo = $validated['tanggal_jatuh_tempo'] ?? Carbon::now()->addDays(14)->toDateString();

        try {
            $peminjaman = $this->peminjamanService->buatPeminjaman(
                $idPengguna,
                $idEksemplar,
                $tanggalPinjam,
                $tanggalJatuhTempo,
                $validated['catatan'] ?? null
            );

            return response()->json([
                'success' => true,
                'message' => 'Peminjaman buku berhasil dicatat. Status eksemplar buku telah dikurangi menjadi Dipinjam.',
                'data' => $peminjaman,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'errors' => $e->errors(),
            ], 422);
        }
    }

    /**
     * Proses pengembalian buku melalui Sanctum API.
     */
    public function kembalikan(Request $request, string $id): JsonResponse
    {
        $request->validate([
            'catatan' => 'nullable|string|max:500',
        ]);

        try {
            $peminjaman = $this->peminjamanService->prosesPengembalian(
                $id,
                $request->input('catatan')
            );

            return response()->json([
                'success' => true,
                'message' => 'Buku berhasil dikembalikan oleh user. Status eksemplar buku kembali bertambah menjadi Tersedia.',
                'data' => [
                    'id_peminjaman' => $peminjaman->id_peminjaman,
                    'status' => $peminjaman->status,
                    'tanggal_kembali' => $peminjaman->tanggal_kembali?->format('Y-m-d'),
                    'hari_keterlambatan' => $peminjaman->hari_keterlambatan,
                    'catatan_keterlambatan' => $peminjaman->catatan_keterlambatan,
                    'status_eksemplar' => $peminjaman->eksemplar->status,
                ],
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'errors' => $e->errors(),
            ], 422);
        }
    }

    /**
     * Kirim notifikasi peringatan lewat batas waktu peminjaman.
     */
    public function kirimPeringatan(string $id): JsonResponse
    {
        try {
            $peminjaman = $this->peminjamanService->kirimPeringatanKeterlambatan($id);

            return response()->json([
                'success' => true,
                'message' => 'Notifikasi peringatan keterlambatan berhasil dikirimkan ke mahasiswa.',
                'data' => [
                    'id_peminjaman' => $peminjaman->id_peminjaman,
                    'hari_keterlambatan' => $peminjaman->hari_keterlambatan,
                    'notifikasi_peringatan_dikirim' => $peminjaman->notifikasi_peringatan_dikirim,
                    'tanggal_notifikasi' => $peminjaman->tanggal_notifikasi?->toIso8601String(),
                ],
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'errors' => $e->errors(),
            ], 422);
        }
    }
}
