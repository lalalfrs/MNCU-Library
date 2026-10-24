<?php

namespace App\Models;

use App\Models\Concerns\HasLibraryId;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PeminjamanBuku extends Model
{
    use HasFactory, HasLibraryId;

    protected $table = 'peminjaman_buku';

    protected $primaryKey = 'id_peminjaman';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_peminjaman',
        'uuid',
        'id_pengguna',
        'id_buku',
        'id_eksemplar',
        'tanggal_pinjam',
        'tanggal_jatuh_tempo',
        'tanggal_kembali',
        'status',
        'hari_keterlambatan',
        'catatan_keterlambatan',
        'notifikasi_peringatan_dikirim',
        'tanggal_notifikasi',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_pinjam' => 'date',
            'tanggal_jatuh_tempo' => 'date',
            'tanggal_kembali' => 'date',
            'hari_keterlambatan' => 'integer',
            'notifikasi_peringatan_dikirim' => 'boolean',
            'tanggal_notifikasi' => 'datetime',
        ];
    }

    public function getIdPrefix(): string
    {
        return 'PJ';
    }

    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }

    public function buku(): BelongsTo
    {
        return $this->belongsTo(Buku::class, 'id_buku', 'id_buku');
    }

    public function eksemplar(): BelongsTo
    {
        return $this->belongsTo(EksemplarBuku::class, 'id_eksemplar', 'id_eksemplar');
    }

    /**
     * Hitung selisih hari keterlambatan terhadap batas pengembalian.
     * Tidak menyertakan sanksi denda (hanya metrik durasi keterlambatan).
     */
    public function hitungHariTerlambat(?Carbon $tanggalCek = null): int
    {
        $targetDate = $this->tanggal_kembali ?? ($tanggalCek ?? Carbon::now());
        $tempo = Carbon::parse($this->tanggal_jatuh_tempo)->startOfDay();
        $target = Carbon::parse($targetDate)->startOfDay();

        if ($target->gt($tempo)) {
            return (int) $tempo->diffInDays($target);
        }

        return 0;
    }

    /**
     * Apakah peminjaman ini tergolong terlambat?
     */
    public function isTerlambat(): bool
    {
        if ($this->status === 'Terlambat') {
            return true;
        }

        if ($this->tanggal_kembali) {
            return $this->hari_keterlambatan > 0;
        }

        return Carbon::now()->startOfDay()->gt(Carbon::parse($this->tanggal_jatuh_tempo)->startOfDay());
    }

    /**
     * Scope untuk peminjaman aktif yang belum dikembalikan.
     */
    public function scopeAktif(Builder $query): Builder
    {
        return $query->whereNull('tanggal_kembali')
            ->whereIn('status', ['Dipinjam', 'Terlambat']);
    }

    /**
     * Scope untuk peminjaman yang telah melewati jatuh tempo namun belum dikembalikan.
     */
    public function scopeTerlambat(Builder $query): Builder
    {
        return $query->whereNull('tanggal_kembali')
            ->where(function (Builder $sub): void {
                $sub->where('status', 'Terlambat')
                    ->orWhereDate('tanggal_jatuh_tempo', '<', Carbon::now()->toDateString());
            });
    }
}
