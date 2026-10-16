<?php

namespace App\Models;

use App\Models\Concerns\HasLibraryId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Peran extends Model
{
    use HasFactory, HasLibraryId;

    protected $table = 'peran';

    protected $primaryKey = 'id_peran';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_peran',
        'uuid',
        'nama_peran',
        'deskripsi_peran',
    ];

    public function getIdPrefix(): string
    {
        return 'MNCU';
    }

    public function pengguna(): HasMany
    {
        return $this->hasMany(Pengguna::class, 'id_peran', 'id_peran');
    }
}
