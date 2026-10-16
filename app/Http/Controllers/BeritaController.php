<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\VisitorLog;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class BeritaController extends Controller
{
    /**
     * Display a listing of library news.
     */
    public function index(Request $request): View
    {
        VisitorLog::recordVisit();

        $q = trim((string) $request->query('q', ''));
        $kategori = trim((string) $request->query('kategori', ''));

        $query = Berita::published();

        if ($q !== '') {
            $query->where(function ($sub) use ($q): void {
                $sub->where('judul_berita', 'like', "%{$q}%")
                    ->orWhere('deskripsi_berita', 'like', "%{$q}%")
                    ->orWhere('isi_berita', 'like', "%{$q}%");
            });
        }

        if ($kategori !== '') {
            $query->where('kategori_berita', $kategori);
        }

        $beritas = $query->paginate(9)->withQueryString();
        $categories = Berita::published()->reorder()->distinct()->pluck('kategori_berita');

        return view('berita.index', compact('beritas', 'categories', 'q', 'kategori'));
    }

    /**
     * Display the specified news article.
     */
    public function show(string $slug): View
    {
        VisitorLog::recordVisit();

        $berita = Berita::where('slug', $slug)->firstOrFail();
        $berita->increment('views_count');

        $relatedNews = Berita::published()
            ->where('id_berita', '!=', $berita->id_berita)
            ->take(3)
            ->get();

        return view('berita.show', compact('berita', 'relatedNews'));
    }
}
