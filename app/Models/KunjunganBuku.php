<?php

namespace App\Models;

use App\Models\Concerns\HasLibraryId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KunjunganBuku extends Model
{
    use HasFactory, HasLibraryId;

    protected $table = 'kunjungan_buku';

    protected $primaryKey = 'id_kunjungan_buku';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_kunjungan_buku',
        'uuid',
        'id_buku',
        'jumlah_kunjungan',
    ];

    protected function casts(): array
    {
        return [
            'jumlah_kunjungan' => 'integer',
        ];
    }

    public function getIdPrefix(): string
    {
        return 'MNCU';
    }

    public function buku(): BelongsTo
    {
        return $this->belongsTo(Buku::class, 'id_buku', 'id_buku');
    }
}
