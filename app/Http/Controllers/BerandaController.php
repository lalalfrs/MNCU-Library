<?php

namespace App\Http\Controllers;

use App\Models\Acara;
use App\Models\Berita;
use App\Models\Buku;
use App\Models\Pengguna;
use App\Models\VisitorLog;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BerandaController extends Controller
{
    /**
     * Display the library homepage with dynamic news, events, and visitor stats.
     */
    public function index(Request $request): View
    {
        $isDbConnected = true;
        $dbErrorMessage = null;

        try {
            DB::connection()->getPdo();
        } catch (\Throwable $e) {
            $isDbConnected = false;
            $dbErrorMessage = 'Database belum terhubung. Pastikan layanan database (MySQL / XAMPP) sudah dijalankan.';
        }

        if ($isDbConnected) {
            try {
                VisitorLog::recordVisit();

                $latestNews = Berita::published()
                    ->take(3)
                    ->get()
                    ->map(function (Berita $news): array {
                        return [
                            'id' => $news->id,
                            'title' => $news->title,
                            'slug' => $news->slug,
                            'excerpt' => $news->excerpt,
                            'category' => $news->category,
                            'date' => $news->published_at->translatedFormat('d F Y'),
                            'image' => $news->image,
                        ];
                    })
                    ->all();

                $upcomingEvents = Acara::upcoming()
                    ->take(3)
                    ->get()
                    ->map(function (Acara $event): array {
                        return [
                            'id' => $event->id,
                            'title' => $event->title,
                            'slug' => $event->slug,
                            'image' => $event->image,
                            'excerpt' => $event->excerpt,
                            'day' => $event->event_date?->format('d') ?? '--',
                            'month' => $event->event_date?->translatedFormat('M') ?? 'Bln',
                            'date' => $event->event_date?->translatedFormat('d F Y') ?? '',
                            'time' => "{$event->start_time} - {$event->end_time} WIB",
                            'place' => $event->location,
                            'category' => $event->category,
                        ];
                    })
                    ->all();

                $visitorStats = VisitorLog::getStatistics();

                $counts = [
                    'total_books' => Buku::count(),
                    'total_events' => Acara::where('is_active', true)->count(),
                    'total_users' => Pengguna::count(),
                    'total_news' => Berita::count(),
                ];
            } catch (\Throwable $e) {
                $isDbConnected = false;
                $dbErrorMessage = 'Gagal memuat data dari database. Pastikan server database (MySQL) telah aktif.';
                $latestNews = [];
                $upcomingEvents = [];
                $visitorStats = [
                    'today' => 0,
                    'month' => 0,
                    'total' => 0,
                ];
                $counts = [
                    'total_books' => 0,
                    'total_events' => 0,
                    'total_users' => 0,
                    'total_news' => 0,
                ];
            }
        } else {
            $latestNews = [];
            $upcomingEvents = [];
            $visitorStats = [
                'today' => 0,
                'month' => 0,
                'total' => 0,
            ];
            $counts = [
                'total_books' => 0,
                'total_events' => 0,
                'total_users' => 0,
                'total_news' => 0,
            ];
        }

        return view('beranda', compact('latestNews', 'upcomingEvents', 'visitorStats', 'counts', 'isDbConnected', 'dbErrorMessage'));
    }
}
