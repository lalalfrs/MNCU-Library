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
            $query->where('tanggal_mulai', '>=', now()->startOfDay())->orderBy('tanggal_mulai', 'asc');
        } elseif ($waktu === 'selesai') {
            $query->where('tanggal_mulai', '<', now()->startOfDay())->orderBy('tanggal_mulai', 'desc');
        } else {
            $query->orderBy('tanggal_mulai', 'desc');
        }

        if ($q !== '') {
            $query->where(function ($sub) use ($q): void {
                $sub->where('nama_acara', 'like', "%{$q}%")
                    ->orWhere('ringkasan_acara', 'like', "%{$q}%")
                    ->orWhere('deskripsi_acara', 'like', "%{$q}%")
                    ->orWhere('narasumber', 'like', "%{$q}%")
                    ->orWhere('lokasi_acara', 'like', "%{$q}%");
            });
        }

        if ($kategori !== '') {
            $query->where('kategori_acara', $kategori);
        }

        $acaras = $query->paginate(8)->withQueryString();
        $categories = Acara::where('is_active', true)->distinct()->pluck('kategori_acara');

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
