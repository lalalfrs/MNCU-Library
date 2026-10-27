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

class Berita extends Model
{
    use HasFactory, HasLibraryId;

    protected $table = 'berita';

    protected $primaryKey = 'id_berita';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_berita',
        'uuid',
        'id_pengguna',
        'judul_berita',
        'slug',
        'kategori_berita',
        'deskripsi_berita',
        'isi_berita',
        'gambar_berita',
        'tanggal_publikasi',
        'views_count',
        // Backward-compatibility attributes
        'title',
        'excerpt',
        'content',
        'image',
        'category',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_publikasi' => 'date',
            'views_count' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (self $model): void {
            if (empty($model->slug) && ! empty($model->judul_berita)) {
                $baseSlug = Str::slug($model->judul_berita);
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
            get: fn () => $this->id_berita,
            set: fn ($value) => ['id_berita' => $value],
        );
    }

    protected function title(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->judul_berita,
            set: fn ($value) => ['judul_berita' => $value],
        );
    }

    protected function excerpt(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->deskripsi_berita,
            set: fn ($value) => ['deskripsi_berita' => $value],
        );
    }

    protected function content(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->isi_berita,
            set: fn ($value) => ['isi_berita' => $value],
        );
    }

    protected function image(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (empty($this->gambar_berita)) {
                    return null;
                }
                if (str_starts_with($this->gambar_berita, 'http://') || str_starts_with($this->gambar_berita, 'https://')) {
                    return $this->gambar_berita;
                }
                if (str_starts_with($this->gambar_berita, 'images/') || file_exists(public_path($this->gambar_berita))) {
                    return asset($this->gambar_berita);
                }
                if (str_starts_with($this->gambar_berita, 'storage/')) {
                    return asset($this->gambar_berita);
                }

                return asset('storage/'.$this->gambar_berita);
            },
            set: fn ($value) => ['gambar_berita' => $value],
        );
    }

    protected function category(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->kategori_berita ?? 'Pengumuman',
            set: fn ($value) => ['kategori_berita' => $value],
        );
    }

    protected function author(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->pengguna?->nama_lengkap ?? 'Pustakawan MNC University',
        );
    }

    protected function publishedAt(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->tanggal_publikasi ? Carbon::parse($this->tanggal_publikasi) : null,
            set: fn ($value) => ['tanggal_publikasi' => $value ? Carbon::parse($value)->toDateString() : null],
        );
    }

    /**
     * Scope a query to only include published news.
     *
     * @param  Builder<Berita>  $query
     * @return Builder<Berita>
     */
    public function scopePublished($query)
    {
        return $query->whereNotNull('tanggal_publikasi')
            ->where('tanggal_publikasi', '<=', now()->toDateString())
            ->orderByDesc('tanggal_publikasi');
    }
}
