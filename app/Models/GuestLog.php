<?php

namespace App\Models;

use Database\Factories\GuestLogFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuestLog extends Model
{
    /** @use HasFactory<GuestLogFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nama',
        'kategori',
        'identitas',
        'waktu_masuk',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'waktu_masuk' => 'datetime',
        ];
    }
}
