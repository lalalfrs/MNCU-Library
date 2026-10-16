<?php

namespace App\Models;

use App\Models\Concerns\HasLibraryId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriBuku extends Model
{
    use HasFactory, HasLibraryId;

    protected $table = 'kategori_buku';

    protected $primaryKey = 'id_kategori_buku';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_kategori_buku',
        'uuid',
        'nama_kategori',
        'deskripsi_kategori',
    ];

    public function getIdPrefix(): string
    {
        return 'MNCU';
    }

    public function buku(): HasMany
    {
        return $this->hasMany(Buku::class, 'id_kategori_buku', 'id_kategori_buku');
    }
}
