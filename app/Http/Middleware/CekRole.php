<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CekRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, $role)
    {
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();

        // Cek apakah role sesuai
        if ($role === 'admin' && !$user->is_admin) {
            return redirect()->route('home')->with('error', 'Kamu tidak punya akses sebagai admin.');
        }

        if ($role === 'user' && $user->is_admin) {
            return redirect()->route('dashboard')->with('error', 'Kamu tidak punya akses sebagai user.');
        }

        return $next($request);
    }
}
