<?php

namespace App\Models;

use App\Models\Concerns\HasLibraryId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Prodi extends Model
{
    use HasFactory, HasLibraryId;

    protected $table = 'prodi';

    protected $primaryKey = 'id_prodi';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_prodi',
        'uuid',
        'nama_prodi',
        'jenjang',
    ];

    public function getIdPrefix(): string
    {
        return 'MNCU';
    }

    public function pengguna(): HasMany
    {
        return $this->hasMany(Pengguna::class, 'id_prodi', 'id_prodi');
    }
}
