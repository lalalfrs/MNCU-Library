<?php

namespace App\Models;

use App\Models\Concerns\HasLibraryId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SubjekBuku extends Model
{
    use HasFactory, HasLibraryId;

    protected $table = 'subjek_buku';

    protected $primaryKey = 'id_subjek_buku';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_subjek_buku',
        'uuid',
        'nama_subjek',
        'deskripsi_subjek',
    ];

    public function getIdPrefix(): string
    {
        return 'MNCU';
    }

    public function buku(): BelongsToMany
    {
        return $this->belongsToMany(
            Buku::class,
            'buku_subjek',
            'id_subjek_buku',
            'id_buku'
        )->withPivot('id_buku_subjek', 'uuid')->withTimestamps();
    }
}
