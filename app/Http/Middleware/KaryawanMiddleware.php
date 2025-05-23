<?php

// app/Http/Middleware/KaryawanMiddleware.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class KaryawanMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && (Auth::user()->isKaryawan() || Auth::user()->isAdmin()) ) { // Admin juga bisa akses halaman karyawan jika diperlukan
            return $next($request);
        }
        // abort(403, 'Unauthorized action.'); // Atau redirect
        return redirect('/login')->with('error', 'Akses hanya untuk karyawan.');
    }
}