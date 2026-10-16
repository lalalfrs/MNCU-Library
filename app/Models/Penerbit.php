<?php

namespace App\Models;

use App\Models\Concerns\HasLibraryId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Penerbit extends Model
{
    use HasFactory, HasLibraryId;

    protected $table = 'penerbit';

    protected $primaryKey = 'id_penerbit';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_penerbit',
        'uuid',
        'nama_penerbit',
        'deskripsi_penerbit',
    ];

    public function getIdPrefix(): string
    {
        return 'MNCU';
    }

    public function buku(): HasMany
    {
        return $this->hasMany(Buku::class, 'id_penerbit', 'id_penerbit');
    }
}
