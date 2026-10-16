<?php

namespace App\Models;

use App\Models\Concerns\HasLibraryId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfilPustakawan extends Model
{
    use HasFactory, HasLibraryId;

    protected $table = 'profil_pustakawan';

    protected $primaryKey = 'id_profil_pustakawan';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_profil_pustakawan',
        'uuid',
        'id_pengguna',
        'nama_pustakawan',
        'jabatan',
        'foto_pustakawan',
        'deskripsi_pustakawan',
    ];

    public function getIdPrefix(): string
    {
        return 'MNCU';
    }

    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }
}
