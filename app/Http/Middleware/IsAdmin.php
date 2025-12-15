<?php

// Kode ditulis oleh :
// Nama  : Fadhiil Akmal Hamizan
// Github: Axmalz
// NRP   : 5026231128
// Kelas : PPPL B

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect('/login');
        }

        // 2. LOGIKA KUNCI: Cek apakah ID user adalah 1
        // Anda bisa mengganti angka 1 dengan ID admin yang sebenarnya
        if (Auth::user()->id !== 1) {
            // Jika bukan ID 1, batalkan akses (403 Forbidden)
            abort(403, 'ANDA TIDAK MEMILIKI AKSES KE HALAMAN INI.');
        }

        // 3. Jika lolos (ID == 1), silakan lanjut
        return $next($request);
    }
}
