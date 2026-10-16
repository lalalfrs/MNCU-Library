<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSuperAdmin
{
    /**
     * Pastikan hanya pengguna dengan hak akses Super Admin (Kepala Perpustakaan) yang dapat melanjutkan.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if (! $user->isSuperAdmin()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Akses ditolak: Hanya Super Admin / Kepala Perpustakaan yang memiliki wewenang mengelola data perpustakaan.',
                ], 403);
            }

            abort(403, 'Akses terbatas: Anda tidak memiliki wewenang administrator untuk mengubah data. Fitur ini hanya dapat diakses oleh Super Admin (Kepala Perpustakaan).');
        }

        return $next($request);
    }
}
