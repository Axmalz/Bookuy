<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;

class CartComposer
{
    public function compose(View $view)
    {
        $cartCount = 0;

        try {
            if (Auth::check()) {
                $cart = Cart::where('user_id', Auth::id())->first();
                if ($cart) {
                    $cartCount = $cart->items()->count();
                }
            }
        } catch (\Exception $e) {
            // Silent fail: Jika DB error, jangan crash halaman, cukup set cart 0
            // Log::error("CartComposer Error: " . $e->getMessage());
        }

        $view->with('cartCount', $cartCount);
    }
}
