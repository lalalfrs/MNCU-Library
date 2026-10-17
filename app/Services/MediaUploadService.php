<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaUploadService
{
    /**
     * Format file gambar yang diizinkan (PNG, JPG/JPEG, dan WebP).
     */
    public const ALLOWED_EXTENSIONS = ['png', 'jpg', 'jpeg', 'webp'];

    /**
     * Validasi aturan umum upload media (PNG, JPG, WEBP maks 5MB).
     */
    public const VALIDATION_RULES = ['nullable', 'file', 'mimes:png,jpg,jpeg,webp', 'max:5120'];

    /**
     * Pesan validasi upload media.
     *
     * @var array<string, string>
     */
    public const VALIDATION_MESSAGES = [
        'mimes' => 'Format file media harus berupa PNG, JPG, JPEG, atau WEBP.',
        'max' => 'Ukuran file media maksimal 5 MB.',
        'file' => 'File yang diunggah tidak valid.',
    ];

    /**
     * Unggah file media ke storage public disk dan hapus file lama jika ada.
     *
     * @param  string  $directory  Folder tujuan di storage public (cth: 'acara', 'berita', 'buku')
     * @param  string|null  $oldPath  Path file lama untuk dibersihkan
     * @return string Path relatif file yang tersimpan di disk public
     */
    public function uploadImage(UploadedFile $file, string $directory = 'media', ?string $oldPath = null): string
    {
        $this->deleteImage($oldPath);

        $extension = strtolower($file->getClientOriginalExtension());
        $filename = Str::random(24).'.'.$extension;

        return $file->storeAs($directory, $filename, 'public');
    }

    /**
     * Hapus file media dari storage jika tersimpan lokal.
     */
    public function deleteImage(?string $path): bool
    {
        if (empty($path)) {
            return false;
        }

        // Jangan hapus jika merupakan tautan eksternal (http/https)
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return false;
        }

        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }

        return false;
    }

    /**
     * Konversi path media ke URL publik lengkap.
     */
    public function getUrl(?string $path, ?string $fallbackUrl = null): ?string
    {
        if (empty($path)) {
            return $fallbackUrl;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        return asset('storage/'.$path);
    }
}
