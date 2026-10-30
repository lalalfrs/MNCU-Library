<?php

namespace App\Models;

use App\Models\Concerns\HasLibraryId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class EksemplarBuku extends Model
{
    use HasFactory, HasLibraryId;

    protected $table = 'eksemplar_buku';

    protected $primaryKey = 'id_eksemplar';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_eksemplar',
        'uuid',
        'id_buku',
        'kode_inventaris',
        'nomor_panggil',
        'lokasi_rak',
        'status',
    ];

    public function getIdPrefix(): string
    {
        return 'MNCU';
    }

    public function buku(): BelongsTo
    {
        return $this->belongsTo(Buku::class, 'id_buku', 'id_buku');
    }

    public function peminjaman(): HasMany
    {
        return $this->hasMany(PeminjamanBuku::class, 'id_eksemplar', 'id_eksemplar');
    }

    /**
     * Relasi ke transaksi peminjaman aktif yang sedang berjalan untuk eksemplar ini.
     */
    public function peminjamanAktif(): HasOne
    {
        return $this->hasOne(PeminjamanBuku::class, 'id_eksemplar', 'id_eksemplar')
            ->whereNull('tanggal_kembali')
            ->whereIn('status', ['Dipinjam', 'Terlambat', 'Menunggu Verifikasi'])
            ->latest('id_peminjaman');
    }
}
