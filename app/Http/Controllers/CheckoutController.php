<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Address;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\Notification;

class CheckoutController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $cart = Cart::where('user_id', $user->id)->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index');
        }

        // Ambil item yang dicentang saja
        $cartItems = $cart->items()->where('is_selected', true)->with('book')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Pilih item terlebih dahulu.');
        }

        $subtotal = $cartItems->sum(fn($item) => $item->subtotal);
        $adminFee = 1000;
        $shippingFee = 5000;

        $defaultAddress = $user->addresses()->where('is_default', true)->first();
        $defaultCard = $user->payments()->where('is_default', true)->first();

        return view('checkout.index', compact('cartItems', 'subtotal', 'adminFee', 'shippingFee', 'defaultAddress', 'defaultCard'));
    }

    public function process(Request $request)
    {
        $user = Auth::user();
        $cart = Cart::where('user_id', $user->id)->first();
        $cartItems = $cart->items()->where('is_selected', true)->get();

        if ($cartItems->isEmpty()) return redirect()->back();

        // Hitung Diskon
        $promoCode = strtoupper($request->input('promo_code'));
        $subtotal = $cartItems->sum(fn($item) => $item->subtotal);
        $discountPercent = 0;

        if ($promoCode == 'PPPLBOOKUY') $discountPercent = 0.30;
        elseif ($promoCode == 'DESIGNBYOCID') $discountPercent = 0.50;
        elseif ($promoCode == 'BOOKUY') $discountPercent = 0.10;

        $discountAmount = $subtotal * $discountPercent;

        // Random Courier
        $couriers = ['Reksy', 'Adit', 'Rama', 'Abi', 'Budi'];
        $randomCourier = $couriers[array_rand($couriers)];

        DB::beginTransaction();
        try {
            // Buat Order untuk setiap item (karena struktur table order kita per buku)
            // Di aplikasi real, biasanya 1 Order punya banyak OrderItem.
            // Tapi kita ikuti struktur existing agar Sales History per buku tetap jalan.
            $orderIds = [];

            foreach ($cartItems as $item) {
                // Harga per item setelah diskon proporsional (opsional, disini kita simpan harga asli dan diskon global dibagi rata atau dihandle di view)
                // Untuk simplisitas, kita simpan harga asli di 'price', dan nanti diskon dihitung di total saja atau simpan per item
                // Mari simpan harga deal per item

                $order = Order::create([
                    'buyer_id' => $user->id,
                    'seller_id' => $item->book->user_id,
                    'book_id' => $item->book_id,
                    'type' => $item->type,
                    'price' => ($item->type == 'sewa' ? $item->book->harga_sewa : $item->book->harga_beli) * $item->quantity, // Total harga item ini
                    'status' => 'Packing',
                    'courier_name' => $randomCourier,
                    'courier_message' => 'Sedang dikemas oleh penjual.',
                    'payment_method' => $request->input('payment_method', 'Card'),
                    'promo_code' => $promoCode,
                    'discount_amount' => ($item->subtotal / $subtotal) * $discountAmount, // Diskon proporsional
                ]);

                $orderIds[] = $order->id;
            }

            // Hapus item dari keranjang
            $cart->items()->where('is_selected', true)->delete();

            DB::commit();

            Notification::create([
                'user_id' => $user->id,
                'title'   => 'Order Placed!',
                'message' => 'Checkout berhasil! Pesananmu sedang diproses oleh penjual.',
                'type'    => 'transaction',
                'icon'    => 'icon-notif-shopping-bag.png'
            ]);

            // Redirect ke halaman sukses dengan membawa ID order (ambil salah satu saja untuk link tracking contoh)
            return redirect()->route('checkout.success', ['orderId' => $orderIds[0]]);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses pesanan.');
        }
    }

    public function success($orderId)
    {
        return view('checkout.success', compact('orderId'));
    }

    public function track($id)
    {
        $order = Order::with(['book', 'seller'])->findOrFail($id);

        // Tentukan state aktif untuk UI timeline
        $statuses = ['Packing', 'Picked', 'In Transit', 'Delivered'];
        $currentStatusIndex = array_search($order->status, $statuses);

        return view('checkout.track', compact('order', 'statuses', 'currentStatusIndex'));
    }
}
