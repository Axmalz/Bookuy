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
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // FIX 502: Jangan gunakan '*' (wildcard) karena akan memicu query di view error/splash
        // Hanya inject $cartCount ke layout utama yang dipakai user login
        View::composer(['layouts.app-main', 'home', 'cart.index', 'product.*'], CartComposer::class);

        // Paksa HTTPS di Production (Railway)
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
