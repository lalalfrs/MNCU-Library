<?php

namespace App\Models;

use App\Models\Concerns\HasLibraryId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BukuPenulis extends Model
{
    use HasFactory, HasLibraryId;

    protected $table = 'buku_penulis';

    protected $primaryKey = 'id_buku_penulis';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_buku_penulis',
        'uuid',
        'id_buku',
        'id_penulis',
    ];

    public function getIdPrefix(): string
    {
        return 'MNCU';
    }

    public function buku(): BelongsTo
    {
        return $this->belongsTo(Buku::class, 'id_buku', 'id_buku');
    }

    public function penulis(): BelongsTo
    {
        return $this->belongsTo(Penulis::class, 'id_penulis', 'id_penulis');
    }
}
