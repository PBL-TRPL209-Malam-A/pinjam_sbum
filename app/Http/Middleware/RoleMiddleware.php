<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        $hasRole = false;
        switch ($role) {
            case 'admin':
                $hasRole = $user->isAdmin();
                break;
            case 'dosen':
                $hasRole = $user->isDosen();
                break;
            case 'kepalasbum':
                $hasRole = $user->isKepalaSbum();
                break;
            case 'pic':
                $hasRole = $user->isPic();
                break;
            case 'pamdal':
                $hasRole = $user->isPamdal();
                break;
            case 'peminjam':
                $hasRole = $user->isPeminjam();
                break;
        }

        if (!$hasRole) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk halaman ini.');
        }

        return $next($request);
    }
}
