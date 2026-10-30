<?php

namespace App\Models;

use App\Models\Concerns\HasLibraryId;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class Acara extends Model
{
    use HasFactory, HasLibraryId;

    protected $table = 'acara_perpustakaan';

    protected $primaryKey = 'id_acara';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_acara',
        'uuid',
        'id_pengguna',
        'nama_acara',
        'slug',
        'kategori_acara',
        'ringkasan_acara',
        'deskripsi_acara',
        'gambar_acara',
        'narasumber',
        'tanggal_mulai',
        'tanggal_selesai',
        'lokasi_acara',
        'tautan_pendaftaran',
        'kuota',
        'is_active',
        // Backward-compatibility attributes
        'title',
        'excerpt',
        'description',
        'speaker',
        'event_date',
        'start_time',
        'end_time',
        'location',
        'registration_link',
        'category',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'datetime',
            'tanggal_selesai' => 'datetime',
            'is_active' => 'boolean',
            'kuota' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (self $model): void {
            if (empty($model->slug) && ! empty($model->nama_acara)) {
                $baseSlug = Str::slug($model->nama_acara);
                $model->slug = $baseSlug ?: Str::random(10);
            }
        });
    }

    public function getIdPrefix(): string
    {
        return 'MNCU';
    }

    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }

    /**
     * Backward-compatibility accessors & mutators for views and controllers.
     */
    protected function id(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->id_acara,
            set: fn ($value) => ['id_acara' => $value],
        );
    }

    protected function title(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->nama_acara,
            set: fn ($value) => ['nama_acara' => $value],
        );
    }

    protected function excerpt(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->ringkasan_acara ?? Str::limit(strip_tags((string) $this->deskripsi_acara), 150),
            set: fn ($value) => ['ringkasan_acara' => $value],
        );
    }

    protected function description(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->deskripsi_acara,
            set: fn ($value) => ['deskripsi_acara' => $value],
        );
    }

    protected function speaker(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->narasumber,
            set: fn ($value) => ['narasumber' => $value],
        );
    }

    protected function image(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (empty($this->gambar_acara)) {
                    return asset('images/acara/acara-01.jpg');
                }
                if (str_starts_with($this->gambar_acara, 'http://') || str_starts_with($this->gambar_acara, 'https://')) {
                    return $this->gambar_acara;
                }
                if (str_starts_with($this->gambar_acara, 'images/') || file_exists(public_path($this->gambar_acara))) {
                    return asset($this->gambar_acara);
                }
                if (str_starts_with($this->gambar_acara, 'storage/')) {
                    return asset($this->gambar_acara);
                }
                if (file_exists(storage_path('app/public/'.$this->gambar_acara))) {
                    return asset('storage/'.$this->gambar_acara);
                }

                return asset('images/acara/acara-01.jpg');
            },
            set: fn ($value) => ['gambar_acara' => $value],
        );
    }

    protected function posterUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->image,
            set: fn ($value) => ['gambar_acara' => $value],
        );
    }

    protected function eventDate(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->tanggal_mulai ? Carbon::parse($this->tanggal_mulai) : null,
            set: fn ($value) => ['tanggal_mulai' => $value ? Carbon::parse($value) : null],
        );
    }

    protected function startTime(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->tanggal_mulai ? Carbon::parse($this->tanggal_mulai)->format('H:i') : '09:00',
        );
    }

    protected function endTime(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->tanggal_selesai ? Carbon::parse($this->tanggal_selesai)->format('H:i') : '12:00',
        );
    }

    protected function location(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->lokasi_acara,
            set: fn ($value) => ['lokasi_acara' => $value],
        );
    }

    protected function category(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->kategori_acara ?? 'Acara Perpustakaan',
            set: fn ($value) => ['kategori_acara' => $value],
        );
    }

    protected function registrationLink(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->tautan_pendaftaran,
            set: fn ($value) => ['tautan_pendaftaran' => $value],
        );
    }

    /**
     * Scope a query to only include upcoming active events.
     *
     * @param  Builder<Acara>  $query
     * @return Builder<Acara>
     */
    public function scopeUpcoming($query)
    {
        return $query->where('is_active', true)
            ->where('tanggal_mulai', '>=', now()->startOfDay())
            ->orderBy('tanggal_mulai', 'asc');
    }
}
