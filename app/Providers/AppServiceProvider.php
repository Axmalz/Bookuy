<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\URL;
use App\Http\View\Composers\CartComposer;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // FIX 502: Hapus tanda '*' agar tidak loop saat error/splash screen.
        // Hanya jalankan CartComposer di view yang memiliki Navbar.
        // Sesuaikan 'layouts.app-main' dengan nama file layout utama Anda.
        View::composer(
            ['layouts.app-main', 'home', 'cart.index', 'product.*', 'profile.*'],
            CartComposer::class
        );

        // PENTING: Aktifkan ini untuk Railway agar gambar/CSS tidak broken
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
