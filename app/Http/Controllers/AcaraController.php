<?php

namespace App\Http\Controllers;

use App\Models\Acara;
use App\Models\VisitorLog;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class AcaraController extends Controller
{
    /**
     * Display a listing of library events.
     */
    public function index(Request $request): View
    {
        VisitorLog::recordVisit();

        $q = trim((string) $request->query('q', ''));
        $kategori = trim((string) $request->query('kategori', ''));
        $waktu = (string) $request->query('waktu', 'mendatang');

        $query = Acara::where('is_active', true);

        if ($waktu === 'mendatang') {
            $query->where('event_date', '>=', now()->toDateString())->orderBy('event_date', 'asc');
        } elseif ($waktu === 'selesai') {
            $query->where('event_date', '<', now()->toDateString())->orderBy('event_date', 'desc');
        } else {
            $query->orderBy('event_date', 'desc');
        }

        if ($q !== '') {
            $query->where(function ($sub) use ($q): void {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('excerpt', 'like', "%{$q}%")
                    ->orWhere('speaker', 'like', "%{$q}%")
                    ->orWhere('location', 'like', "%{$q}%");
            });
        }

        if ($kategori !== '') {
            $query->where('category', $kategori);
        }

        $acaras = $query->paginate(8)->withQueryString();
        $categories = Acara::where('is_active', true)->distinct()->pluck('category');

        return view('acara.index', compact('acaras', 'categories', 'q', 'kategori', 'waktu'));
    }

    /**
     * Display the specified event.
     */
    public function show(string $slug): View
    {
        VisitorLog::recordVisit();

        $acara = Acara::where('slug', $slug)->firstOrFail();

        $otherEvents = Acara::upcoming()
            ->where('id', '!=', $acara->id)
            ->take(3)
            ->get();

        return view('acara.show', compact('acara', 'otherEvents'));
    }
}
