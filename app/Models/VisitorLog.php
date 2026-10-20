<?php

namespace App\Models;

use App\Models\Concerns\HasLibraryId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VisitorLog extends Model
{
    use HasFactory, HasLibraryId;

    protected $table = 'log_kunjungan';

    protected $primaryKey = 'id_log_kunjungan';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_log_kunjungan',
        'uuid',
        'id_pengguna',
        'waktu_kunjungan',
        'session_id',
        'ip_address',
        'user_agent',
        // Backward compatibility
        'visited_date',
    ];

    protected function casts(): array
    {
        return [
            'waktu_kunjungan' => 'datetime',
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

    /**
     * Record a unique visit for the current session today.
     */
    public static function recordVisit(): void
    {
        try {
            $sessionId = session()->getId();

            if (empty($sessionId)) {
                return;
            }

            $todayStart = now()->startOfDay();
            $todayEnd = now()->endOfDay();

            $alreadyLogged = static::query()
                ->where('session_id', $sessionId)
                ->whereBetween('waktu_kunjungan', [$todayStart, $todayEnd])
                ->exists();

            if (! $alreadyLogged) {
                static::create([
                    'id_pengguna' => auth()->id(),
                    'waktu_kunjungan' => now(),
                    'session_id' => $sessionId,
                    'ip_address' => request()->ip(),
                    'user_agent' => substr((string) request()->userAgent(), 0, 500),
                ]);
            }
        } catch (\Throwable $e) {
            report($e);
        }
    }

    /**
     * Get visitor statistics: today, this month, total.
     *
     * @return array{today: int, month: int, total: int}
     */
    public static function getStatistics(): array
    {
        try {
            $todayStart = now()->startOfDay();
            $todayEnd = now()->endOfDay();
            $monthStart = now()->startOfMonth();

            $today = static::query()
                ->whereBetween('waktu_kunjungan', [$todayStart, $todayEnd])
                ->count();

            $month = static::query()
                ->where('waktu_kunjungan', '>=', $monthStart)
                ->count();

            $total = static::query()->count();

            return [
                'today' => max($today, 0),
                'month' => max($month, 0),
                'total' => max($total, 0),
            ];
        } catch (\Throwable $e) {
            report($e);

            return [
                'today' => 0,
                'month' => 0,
                'total' => 0,
            ];
        }
    }
}
