<?php

namespace App\Models;

use App\Models\Concerns\HasLibraryId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kontak extends Model
{
    use HasFactory, HasLibraryId;

    protected $table = 'kontak';

    protected $primaryKey = 'id_kontak';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_kontak',
        'uuid',
        'id_pengguna',
        'nama',
        'email_kontak',
        'nomor_telepon',
        'isi_pesan',
        'waktu_kirim',
    ];

    protected function casts(): array
    {
        return [
            'waktu_kirim' => 'datetime',
        ];
    }

    public function getIdPrefix(): string
    {
        return 'MNCU';
    }

    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }
}
