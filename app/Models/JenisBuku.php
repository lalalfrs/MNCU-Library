<?php

namespace App\Models;

use App\Models\Concerns\HasLibraryId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisBuku extends Model
{
    use HasFactory, HasLibraryId;

    protected $table = 'jenis_buku';

    protected $primaryKey = 'id_jenis_buku';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_jenis_buku',
        'uuid',
        'nama_jenis_buku',
        'deskripsi_jenis',
    ];

    public function getIdPrefix(): string
    {
        return 'MNCU';
    }

    public function buku(): HasMany
    {
        return $this->hasMany(Buku::class, 'id_jenis_buku', 'id_jenis_buku');
    }

    public function getNamaJenisAttribute(): ?string
    {
        return $this->nama_jenis_buku;
    }
}
