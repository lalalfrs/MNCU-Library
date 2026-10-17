<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Acara;
use App\Services\MediaUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AcaraAdminController extends Controller
{
    public function __construct(
        protected MediaUploadService $mediaUploadService
    ) {}

    /**
     * Tampilkan daftar acara dan agenda kegiatan perpustakaan.
     */
    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));
        $status = trim((string) $request->query('status', ''));

        $query = Acara::latest('tanggal_mulai');

        if ($q !== '') {
            $query->where(function ($sub) use ($q): void {
                $sub->where('nama_acara', 'like', "%{$q}%")
                    ->orWhere('narasumber', 'like', "%{$q}%")
                    ->orWhere('lokasi_acara', 'like', "%{$q}%")
                    ->orWhere('kategori_acara', 'like', "%{$q}%");
            });
        }

        if ($status === 'aktif') {
            $query->where('is_active', true);
        } elseif ($status === 'nonaktif') {
            $query->where('is_active', false);
        }

        $events = $query->paginate(10)->withQueryString();

        return view('admin.acara.index', compact('events', 'q', 'status'));
    }

    /**
     * Tampilkan formulir tambah acara perpustakaan baru.
     */
    public function create(): View
    {
        return view('admin.acara.create');
    }

    /**
     * Simpan acara baru ke dalam database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_acara' => ['required', 'string', 'max:255'],
            'kategori_acara' => ['required', 'string', 'max:100'],
            'ringkasan_acara' => ['nullable', 'string', 'max:255'],
            'deskripsi_acara' => ['nullable', 'string'],
            'narasumber' => ['nullable', 'string', 'max:100'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
            'lokasi_acara' => ['nullable', 'string', 'max:255'],
            'tautan_pendaftaran' => ['nullable', 'url', 'max:255'],
            'kuota' => ['nullable', 'integer', 'min:1'],
            'gambar_acara' => ['nullable', 'string', 'max:255'],
            'gambar_acara_file' => ['nullable', 'file', 'mimes:png,jpg,jpeg,webp', 'max:5120'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'nama_acara.required' => 'Nama acara wajib diisi.',
            'kategori_acara.required' => 'Kategori acara wajib diisi.',
            'tanggal_mulai.required' => 'Waktu pelaksanaan acara wajib diisi.',
            'gambar_acara_file.mimes' => 'Format file poster/foto harus berupa PNG, JPG, JPEG, atau WEBP.',
            'gambar_acara_file.max' => 'Ukuran file poster/foto maksimal 5 MB.',
        ]);

        if ($request->hasFile('gambar_acara_file')) {
            $validated['gambar_acara'] = $this->mediaUploadService->uploadImage(
                $request->file('gambar_acara_file'),
                'acara'
            );
        }
        unset($validated['gambar_acara_file']);

        $validated['id_pengguna'] = Auth::id() ?? 'MNCU00001';
        $validated['slug'] = Str::slug($validated['nama_acara']).'-'.strtolower(Str::random(5));
        $validated['is_active'] = $request->boolean('is_active', true);

        $event = Acara::create($validated);

        return redirect()->route('admin.acara.index')
            ->with('success', "Acara \"{$event->nama_acara}\" berhasil dipublikasikan!");
    }

    /**
     * Tampilkan formulir edit acara perpustakaan.
     */
    public function edit(string $id): View
    {
        $event = Acara::findOrFail($id);

        return view('admin.acara.edit', compact('event'));
    }

    /**
     * Perbarui data acara perpustakaan.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $event = Acara::findOrFail($id);

        $validated = $request->validate([
            'nama_acara' => ['required', 'string', 'max:255'],
            'kategori_acara' => ['required', 'string', 'max:100'],
            'ringkasan_acara' => ['nullable', 'string', 'max:255'],
            'deskripsi_acara' => ['nullable', 'string'],
            'narasumber' => ['nullable', 'string', 'max:100'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
            'lokasi_acara' => ['nullable', 'string', 'max:255'],
            'tautan_pendaftaran' => ['nullable', 'url', 'max:255'],
            'kuota' => ['nullable', 'integer', 'min:1'],
            'gambar_acara' => ['nullable', 'string', 'max:255'],
            'gambar_acara_file' => ['nullable', 'file', 'mimes:png,jpg,jpeg,webp', 'max:5120'],
            'hapus_gambar' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'nama_acara.required' => 'Nama acara wajib diisi.',
            'kategori_acara.required' => 'Kategori acara wajib diisi.',
            'tanggal_mulai.required' => 'Waktu pelaksanaan acara wajib diisi.',
            'gambar_acara_file.mimes' => 'Format file poster/foto harus berupa PNG, JPG, JPEG, atau WEBP.',
            'gambar_acara_file.max' => 'Ukuran file poster/foto maksimal 5 MB.',
        ]);

        if ($request->boolean('hapus_gambar')) {
            $this->mediaUploadService->deleteImage($event->gambar_acara);
            $validated['gambar_acara'] = null;
        }

        if ($request->hasFile('gambar_acara_file')) {
            $validated['gambar_acara'] = $this->mediaUploadService->uploadImage(
                $request->file('gambar_acara_file'),
                'acara',
                $event->gambar_acara
            );
        }
        unset($validated['gambar_acara_file'], $validated['hapus_gambar']);

        $validated['is_active'] = $request->boolean('is_active');

        $event->update($validated);

        return redirect()->route('admin.acara.index')
            ->with('success', "Acara \"{$event->nama_acara}\" berhasil diperbarui!");
    }

    /**
     * Hapus acara dari database perpustakaan.
     */
    public function destroy(string $id): RedirectResponse
    {
        $event = Acara::findOrFail($id);
        $nama = $event->nama_acara;

        $this->mediaUploadService->deleteImage($event->gambar_acara);
        $event->delete();

        return redirect()->route('admin.acara.index')
            ->with('success', "Acara \"{$nama}\" berhasil dihapus.");
    }
}
