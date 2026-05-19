<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Pastikan user sudah login
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // 2. Cek role: Izinkan Admin ATAU Manager
        $allowedRoles = ['admin', 'manager'];
        
        if (!in_array(auth()->user()->role, $allowedRoles)) {
            abort(403, 'Akses ditolak. Halaman ini khusus untuk Admin & Manager.');
        }

        return $next($request);
    }
}
