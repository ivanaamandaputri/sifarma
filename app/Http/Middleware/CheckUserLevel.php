<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserLevel
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Memastikan pengguna sudah login
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu.');
        }

        // Mengambil role user yang sedang login
        $userRole = Auth::user()->role;

        // Cek apakah role user termasuk dalam array role yang diperbolehkan
        if (!in_array($userRole, $roles)) {
            return abort(403, 'Unauthorized action. Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        return $next($request);
    }
}
