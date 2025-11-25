<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
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
        // Terapkan CartComposer ke layout utama dan halaman produk
        // '*' berarti semua view, tapi lebih efisien jika spesifik
        // Kita gunakan '*' agar aman di semua halaman yang mungkin punya header keranjang
        View::composer('*', CartComposer::class);
    }
}
