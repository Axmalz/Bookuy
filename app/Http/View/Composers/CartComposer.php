<?php

// Kode ditulis oleh :
// Nama  : Fadhiil Akmal Hamizan
// Github: Axmalz
// NRP   : 5026231128
// Kelas : PPPL B

namespace App\Http\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;

class CartComposer
{
    public function compose(View $view)
    {
        $cartCount = 0;

        if (Auth::check()) {
            // Ambil keranjang user
            $cart = Cart::where('user_id', Auth::id())->first();

            if ($cart) {
                // Hitung total item (beli + sewa)
                // Jika ingin menghitung 'jenis' buku, gunakan count().
                // Jika ingin menghitung total 'qty', gunakan sum('quantity').
                // Biasanya badge keranjang menghitung jumlah jenis item.
                $cartCount = $cart->items()->count();
            }
        }

        $view->with('cartCount', $cartCount);
    }
}
