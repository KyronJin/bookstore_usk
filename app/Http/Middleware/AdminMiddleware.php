<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Belum login sama sekali → ke halaman login ADMIN
        if (!Auth::check()) {
            return redirect()->route('admin.login')
                ->with('error', 'Silakan login sebagai administrator terlebih dahulu.');
        }

        // Sudah login tapi bukan admin → logout paksa & ke login admin
        if (!Auth::user()->isAdmin()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('admin.login')
                ->with('error', 'Akun Anda tidak memiliki hak akses ke panel administrator.');
        }

        return $next($request);
    }
}
