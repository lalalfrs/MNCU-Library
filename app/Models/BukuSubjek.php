<?php

namespace App\Models;

use App\Models\Concerns\HasLibraryId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BukuSubjek extends Model
{
    use HasFactory, HasLibraryId;

    protected $table = 'buku_subjek';

    protected $primaryKey = 'id_buku_subjek';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_buku_subjek',
        'uuid',
        'id_buku',
        'id_subjek_buku',
    ];

    public function getIdPrefix(): string
    {
        return 'MNCU';
    }

    public function buku(): BelongsTo
    {
        return $this->belongsTo(Buku::class, 'id_buku', 'id_buku');
    }

    public function subjek(): BelongsTo
    {
        return $this->belongsTo(SubjekBuku::class, 'id_subjek_buku', 'id_subjek_buku');
    }
}
