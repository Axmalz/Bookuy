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
        // Terapkan CartComposer ke semua view ('*')
        // Ini membuat variabel $cartCount tersedia di semua halaman (header)
        View::composer('*', CartComposer::class);

        // Paksa HTTPS di lingkungan Production (Railway)
        //if ($this->app->environment('production')) {
        //    URL::forceScheme('https');
        //}
    }
}
