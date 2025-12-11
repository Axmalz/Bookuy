<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // Import Log
use App\Models\Cart;

class CartComposer
{
    public function compose(View $view)
    {
        $cartCount = 0;

        try {
            // Cek Auth dulu untuk hemat resource
            if (Auth::check()) {
                $cart = Cart::where('user_id', Auth::id())->first();

                if ($cart) {
                    $cartCount = $cart->items()->count();
                }
            }
        } catch (\Exception $e) {
            // SILENT FAIL: Jika DB error, biarkan $cartCount = 0.
            // Jangan biarkan aplikasi crash (502) gara-gara ikon keranjang.
            // Log::error('CartComposer Error: ' . $e->getMessage());
        }

        $view->with('cartCount', $cartCount);
    }
}
