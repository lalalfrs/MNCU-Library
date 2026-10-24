<?php

namespace App\Models;

use App\Models\Concerns\HasLibraryId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Pengguna extends Authenticatable
{
    use HasApiTokens, HasFactory, HasLibraryId, Notifiable;

    protected $table = 'pengguna';

    protected $primaryKey = 'id_pengguna';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_pengguna',
        'uuid',
        'id_peran',
        'id_prodi',
        'nama_lengkap',
        'email',
        'kata_sandi',
        'nomor_identitas',
        'alamat_lengkap',
        'no_wa',
        'tempat_lahir',
        'tanggal_lahir',
        'nik',
        'nim',
        'nidn',
        'foto_profil',
    ];

    protected $hidden = [
        'kata_sandi',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'kata_sandi' => 'hashed',
            'tanggal_lahir' => 'date',
        ];
    }

    public function getRoleAttribute(): string
    {
        if ($this->isMahasiswa()) {
            return 'mahasiswa';
        }
        if ($this->isDosen()) {
            return 'dosen';
        }

        return 'admin';
    }

    public function getFotoUrlAttribute(): ?string
    {
        return $this->foto_profil ? asset('storage/'.$this->foto_profil) : null;
    }

    public function getAuthPassword(): string
    {
        return $this->kata_sandi;
    }

    public function getIdPrefix(): string
    {
        return 'MNCU';
    }

    public function peran(): BelongsTo
    {
        return $this->belongsTo(Peran::class, 'id_peran', 'id_peran');
    }

    /**
     * Cek apakah pengguna memiliki hak akses sebagai Super Admin / Kepala Perpustakaan.
     */
    public function isSuperAdmin(): bool
    {
        return in_array($this->peran?->nama_peran, ['Kepala Perpustakaan', 'Super Admin'], true);
    }

    /**
     * Cek apakah pengguna berstatus sebagai Mahasiswa / Pemustaka umum.
     */
    public function isMahasiswa(): bool
    {
        return $this->peran?->nama_peran === 'Mahasiswa';
    }

    /**
     * Cek apakah pengguna berstatus sebagai Dosen / Peneliti.
     */
    public function isDosen(): bool
    {
        return $this->peran?->nama_peran === 'Dosen';
    }

    /**
     * Cek apakah pengguna berstatus sebagai Pustakawan.
     */
    public function isPustakawan(): bool
    {
        return $this->peran?->nama_peran === 'Pustakawan';
    }

    public function prodi(): BelongsTo
    {
        return $this->belongsTo(Prodi::class, 'id_prodi', 'id_prodi');
    }

    public function profilPustakawan(): HasOne
    {
        return $this->hasOne(ProfilPustakawan::class, 'id_pengguna', 'id_pengguna');
    }

    public function berita(): HasMany
    {
        return $this->hasMany(Berita::class, 'id_pengguna', 'id_pengguna');
    }

    public function acara(): HasMany
    {
        return $this->hasMany(Acara::class, 'id_pengguna', 'id_pengguna');
    }

    public function kontak(): HasMany
    {
        return $this->hasMany(Kontak::class, 'id_pengguna', 'id_pengguna');
    }

    public function logKunjungan(): HasMany
    {
        return $this->hasMany(VisitorLog::class, 'id_pengguna', 'id_pengguna');
    }

    public function peminjaman(): HasMany
    {
        return $this->hasMany(PeminjamanBuku::class, 'id_pengguna', 'id_pengguna');
    }

    /**
     * Cek apakah pengguna memiliki tanggungan peminjaman buku yang belum dikembalikan.
     */
    public function memilikiTanggunganPinjaman(): bool
    {
        return $this->peminjaman()
            ->whereNull('tanggal_kembali')
            ->whereIn('status', ['Dipinjam', 'Terlambat'])
            ->exists();
    }
}
