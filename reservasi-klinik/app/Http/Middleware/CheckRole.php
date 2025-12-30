<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Cek apakah user sudah login?
        if (!Auth::check()) {
            return response()->json(['msg' => 'Anda belum login'], 401);
        }

        // 2. Ambil user yang sedang login
        $user = Auth::user();

        // 3. Cek apakah role user ada di dalam daftar yang diizinkan?
        // Contoh pemakaian nanti: middleware('role:dokter,admin')
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // 4. Jika role tidak cocok
        return response()->json(['msg' => 'Akses Ditolak. Anda bukan ' . implode(' atau ', $roles)], 403);
    }
}