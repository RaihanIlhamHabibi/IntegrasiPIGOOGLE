<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureGoogleConnected
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()?->googleToken) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akun Google belum terhubung. Silakan login dengan Google terlebih dahulu.',
                ], 403);
            }

            return redirect()
                ->guest(route('auth.google'))
                ->with('error', 'Hubungkan akun Google Anda untuk mengakses fitur ini.');
        }

        return $next($request);
    }
}
