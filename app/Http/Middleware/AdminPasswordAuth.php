<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminPasswordAuth
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->session()->get('admin_authenticated')) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sesi admin telah berakhir atau belum terautentikasi.',
                    'redirect' => route('ppdb.login'),
                ], 401);
            }

            return redirect()->route('ppdb.login')->with('warning', 'Silakan masukkan sandi admin untuk mengakses Dashboard PPDB.');
        }

        return $next($request);
    }
}
