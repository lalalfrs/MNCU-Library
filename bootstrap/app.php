<?php

use App\Http\Middleware\EnsureSuperAdmin;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');
        $middleware->statefulApi();
        $middleware->redirectTo(
            guests: '/login',
            users: '/',
        );
        $middleware->alias([
            'superadmin' => EnsureSuperAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );

        $isConnectionError = function (Throwable $e): bool {
            if ($e instanceof QueryException || $e instanceof PDOException) {
                $code = (int) $e->getCode();
                if (in_array($code, [2002, 2003, 1045, 1049, 2006], true)) {
                    return true;
                }

                $msg = strtolower($e->getMessage());
                $keywords = [
                    'actively refused',
                    'connection refused',
                    'no connection could be made',
                    'access denied for user',
                    'unknown database',
                    'server has gone away',
                    'failed to connect',
                    'connection timed out',
                    'could not find driver',
                    'getaddrinfo failed',
                    'network is unreachable',
                ];

                foreach ($keywords as $keyword) {
                    if (str_contains($msg, $keyword)) {
                        return true;
                    }
                }
            }

            return false;
        };

        $handleConnectionException = function (Throwable $e, Request $request) use ($isConnectionError) {
            if (! $isConnectionError($e)) {
                return null;
            }

            $errorMessage = 'Database belum terhubung. Pastikan layanan database (MySQL / XAMPP) sudah dijalankan.';

            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage,
                ], 503);
            }

            if ($request->is('/') || $request->routeIs('beranda')) {
                return response()->view('beranda', [
                    'latestNews' => [],
                    'upcomingEvents' => [],
                    'visitorStats' => ['today' => 0, 'month' => 0, 'total' => 0],
                    'counts' => [
                        'total_books' => 0,
                        'total_events' => 0,
                        'total_users' => 0,
                        'total_news' => 0,
                    ],
                    'isDbConnected' => false,
                    'dbErrorMessage' => $errorMessage,
                ]);
            }

            if ($request->hasSession()) {
                return redirect()->route('beranda')->with('database_error', $errorMessage);
            }

            return redirect()->to('/?db_error=1');
        };

        $exceptions->render(function (QueryException $e, Request $request) use ($handleConnectionException) {
            return $handleConnectionException($e, $request);
        });

        $exceptions->render(function (PDOException $e, Request $request) use ($handleConnectionException) {
            return $handleConnectionException($e, $request);
        });
    })->create();
