<?php

namespace App\Providers;

use App\Models\PeminjamanBuku;
use App\Services\PeminjamanService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
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

        // View Composer untuk membagikan data Inbox Notifikasi (Admin & Seluruh User) ke Navbar & Sidebar
        View::composer(['partials.navbar', 'partials.sidebar', 'admin.inbox.*', 'inbox.*'], function ($view): void {
            $inboxPendingCount = 0;
            $inboxPendingBorrowCount = 0;
            $inboxPendingReturnCount = 0;
            $inboxPendingItems = collect();

            $userInboxAlertCount = 0;
            $userInboxAlerts = collect();
            $userActiveLoansCount = 0;

            if (Auth::check()) {
                $user = Auth::user();

                try {
                    // Sinkronisasi otomatis notifikasi H-1 (24 jam sebelum deadline) & keterlambatan untuk user yang sedang login
                    app(PeminjamanService::class)->sinkronisasiStatusKeterlambatan($user->id_pengguna);

                    // Notifikasi pengingat H-1 (<= 24 jam sebelum deadline) dan keterlambatan milik user
                    $userInboxAlerts = PeminjamanBuku::with(['buku', 'eksemplar'])
                        ->where('id_pengguna', $user->id_pengguna)
                        ->whereNull('tanggal_kembali')
                        ->whereIn('status', ['Dipinjam', 'Terlambat', 'Menunggu Verifikasi'])
                        ->whereDate('tanggal_jatuh_tempo', '<=', Carbon::now()->addDay()->toDateString())
                        ->orderBy('tanggal_jatuh_tempo')
                        ->get();

                    $userInboxAlertCount = $userInboxAlerts->count();

                    $userActiveLoansCount = PeminjamanBuku::where('id_pengguna', $user->id_pengguna)
                        ->whereNull('tanggal_kembali')
                        ->whereIn('status', ['Dipinjam', 'Terlambat', 'Menunggu Verifikasi'])
                        ->count();

                    if ($user->isSuperAdmin() || $user->isPustakawan()) {
                        $inboxPendingBorrowCount = PeminjamanBuku::where('status', 'Menunggu Persetujuan')->count();
                        $inboxPendingReturnCount = PeminjamanBuku::where('status', 'Menunggu Verifikasi')
                            ->whereNull('tanggal_kembali')
                            ->count();
                        $inboxPendingCount = $inboxPendingBorrowCount + $inboxPendingReturnCount;

                        $inboxPendingItems = PeminjamanBuku::with(['pengguna', 'buku', 'eksemplar'])
                            ->whereIn('status', ['Menunggu Persetujuan', 'Menunggu Verifikasi'])
                            ->latest()
                            ->take(6)
                            ->get();
                    }
                } catch (\Throwable) {
                    // Diamkan jika tabel belum siap atau koneksi terganggu
                }
            }

            $view->with('inboxPendingCount', $inboxPendingCount)
                ->with('inboxPendingBorrowCount', $inboxPendingBorrowCount)
                ->with('inboxPendingReturnCount', $inboxPendingReturnCount)
                ->with('inboxPendingItems', $inboxPendingItems)
                ->with('userInboxAlertCount', $userInboxAlertCount)
                ->with('userInboxAlerts', $userInboxAlerts)
                ->with('userActiveLoansCount', $userActiveLoansCount);
        });
    }
}
