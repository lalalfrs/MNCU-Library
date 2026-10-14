<?php

namespace App\Models;

use Database\Factories\AcaraFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Acara extends Model
{
    /** @use HasFactory<AcaraFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'slug',
        'category',
        'excerpt',
        'description',
        'speaker',
        'event_date',
        'start_time',
        'end_time',
        'location',
        'registration_link',
        'quota',
        'is_active',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'event_date' => 'date',
            'is_active' => 'boolean',
            'quota' => 'integer',
        ];
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
            ->where('event_date', '>=', now()->toDateString())
            ->orderBy('event_date', 'asc');
    }
}
