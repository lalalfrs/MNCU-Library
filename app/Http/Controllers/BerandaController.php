<?php

namespace App\Http\Controllers;

use App\Models\Acara;
use App\Models\Berita;
use App\Models\VisitorLog;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class BerandaController extends Controller
{
    /**
     * Display the library homepage with dynamic news, events, and visitor stats.
     */
    public function index(Request $request): View
    {
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
                    'day' => $event->event_date->format('d'),
                    'month' => $event->event_date->translatedFormat('M'),
                    'time' => "{$event->start_time} - {$event->end_time} WIB",
                    'place' => $event->location,
                    'category' => $event->category,
                ];
            })
            ->all();

        $visitorStats = VisitorLog::getStatistics();

        return view('beranda', compact('latestNews', 'upcomingEvents', 'visitorStats'));
    }
}
