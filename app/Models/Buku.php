<?php

namespace App\Models;

use App\Models\Concerns\HasLibraryId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Buku extends Model
{
    use HasFactory, HasLibraryId;

    protected $table = 'buku';

    protected $primaryKey = 'id_buku';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_buku',
        'uuid',
        'id_jenis_buku',
        'id_kategori_buku',
        'id_penerbit',
        'judul_buku',
        'isbn',
        'tahun_terbit',
        'edisi',
        'deskripsi',
        'sampul_buku',
        'jumlah_halaman',
    ];

    protected function casts(): array
    {
        return [
            'tahun_terbit' => 'integer',
            'jumlah_halaman' => 'integer',
        ];
    }

    public function getIdPrefix(): string
    {
        return 'MNCU';
    }

    public function jenisBuku(): BelongsTo
    {
        return $this->belongsTo(JenisBuku::class, 'id_jenis_buku', 'id_jenis_buku');
    }

    public function kategoriBuku(): BelongsTo
    {
        return $this->belongsTo(KategoriBuku::class, 'id_kategori_buku', 'id_kategori_buku');
    }

    public function penerbit(): BelongsTo
    {
        return $this->belongsTo(Penerbit::class, 'id_penerbit', 'id_penerbit');
    }

    public function penulis(): BelongsToMany
    {
        return $this->belongsToMany(
            Penulis::class,
            'buku_penulis',
            'id_buku',
            'id_penulis'
        )->withPivot('id_buku_penulis', 'uuid')->withTimestamps();
    }

    public function subjek(): BelongsToMany
    {
        return $this->belongsToMany(
            SubjekBuku::class,
            'buku_subjek',
            'id_buku',
            'id_subjek_buku'
        )->withPivot('id_buku_subjek', 'uuid')->withTimestamps();
    }

    public function eksemplar(): HasMany
    {
        return $this->hasMany(EksemplarBuku::class, 'id_buku', 'id_buku');
    }

    public function kunjungan(): HasOne
    {
        return $this->hasOne(KunjunganBuku::class, 'id_buku', 'id_buku');
    }

    public function peminjaman(): HasMany
    {
        return $this->hasMany(PeminjamanBuku::class, 'id_buku', 'id_buku');
    }
}
