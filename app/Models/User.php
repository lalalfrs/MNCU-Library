<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'name',
    'nama_lengkap',
    'role',
    'is_member',
    'member_sejak',
    'nomor_anggota',
    'email',
    'password',
    'alamat_lengkap',
    'no_wa',
    'tempat_lahir',
    'tanggal_lahir',
    'nik',
    'nim',
    'nidn',
    'foto_profil',
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'tanggal_lahir' => 'date',
            'is_member' => 'boolean',
            'member_sejak' => 'datetime',
        ];
    }

    public function isMember(): bool
    {
        return (bool) $this->is_member;
    }

    public function getNamaLengkapAttribute(?string $value): string
    {
        return $value ?: ($this->attributes['name'] ?? '');
    }

    public function getFotoUrlAttribute(): ?string
    {
        return $this->foto_profil ? asset('storage/'.$this->foto_profil) : null;
    }
}
