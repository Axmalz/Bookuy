<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request; // PENTING: Jangan lupa baris ini ditambah
use App\Http\Middleware\IsAdmin;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // 1. Konfigurasi Alias Middleware (Milikmu sebelumnya)
        $middleware->alias([
            'admin' => IsAdmin::class,
        ]);

        // 2. Konfigurasi Trust Proxies (WAJIB untuk Railway)
        // Mengizinkan Laravel menerima request dari Load Balancer Railway
        $middleware->trustProxies(at: '*');

        // 3. Konfigurasi Headers (WAJIB agar HTTPS terdeteksi)
        // Tanpa ini, CSS/Gambar sering broken (mixed content) atau terjadi redirect loop
        $middleware->trustProxies(headers: Request::HEADER_X_FORWARDED_FOR |
            Request::HEADER_X_FORWARDED_HOST |
            Request::HEADER_X_FORWARDED_PORT |
            Request::HEADER_X_FORWARDED_PROTO |
            Request::HEADER_X_FORWARDED_AWS_ELB
        );
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
