<?php

namespace App\Models;

use App\Models\Concerns\HasLibraryId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Penulis extends Model
{
    use HasFactory, HasLibraryId;

    protected $table = 'penulis';

    protected $primaryKey = 'id_penulis';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_penulis',
        'uuid',
        'nama_penulis',
        'deskripsi_penulis',
    ];

    public function getIdPrefix(): string
    {
        return 'MNCU';
    }

    public function buku(): BelongsToMany
    {
        return $this->belongsToMany(
            Buku::class,
            'buku_penulis',
            'id_penulis',
            'id_buku'
        )->withPivot('id_buku_penulis', 'uuid')->withTimestamps();
    }
}
