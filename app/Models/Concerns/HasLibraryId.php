<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

trait HasLibraryId
{
    /**
     * Boot the trait to automatically assign UUID and formatted library ID if not set.
     */
    public static function bootHasLibraryId(): void
    {
        static::creating(function ($model): void {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }

            $prefix = $model->getIdPrefix();
            $keyName = $model->getKeyName();

            if (empty($model->{$keyName})) {
                $latest = $model->newQuery()
                    ->where($keyName, 'like', "{$prefix}%")
                    ->orderByRaw("LENGTH({$keyName}) DESC, {$keyName} DESC")
                    ->value($keyName);

                $number = 1;
                if ($latest && preg_match('/^'.preg_quote($prefix, '/').'(\d+)$/', $latest, $matches)) {
                    $number = ((int) $matches[1]) + 1;
                }

                $model->{$keyName} = sprintf('%s%05d', $prefix, $number);
            }
        });
    }

    /**
     * Get the ID prefix for this model (defaults to 'MNCU' for 'MNCUXXXXX').
     */
    public function getIdPrefix(): string
    {
        return 'MNCU';
    }
}
