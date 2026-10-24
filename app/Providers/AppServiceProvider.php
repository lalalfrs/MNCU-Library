<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->bound('request')) {
            $request = $this->app->make('request');
            if ($request->server('HTTP_X_FORWARDED_PROTO') === 'https' || $request->isSecure()) {
                URL::forceScheme('https');
            }
        }

        // Jika session driver diset ke database, pastikan DB dapat diakses.
        // Jika DB offline, alihkan ke file agar middleware StartSession tidak crash.
        if (config('session.driver') === 'database') {
            try {
                DB::connection()->getPdo();
            } catch (\Throwable) {
                config(['session.driver' => 'file']);
            }
        }
    }
}
