<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Menampilkan halaman keranjang
    public function index(Request $request)
    {
        $user = Auth::user();

        // Ambil atau buat keranjang user
        $cart = Cart::firstOrCreate(['user_id' => $user->id]);

        // Ambil tab aktif dari request, default 'beli'
        $activeTab = $request->query('tab', 'beli');

        // Ambil item sesuai tab
        $items = $cart->items()->where('type', $activeTab)->with('book')->get();

        // Hitung total (hanya item yang dicentang)
        $selectedItems = $items->where('is_selected', true);
        $subtotal = $selectedItems->sum(function($item) {
            return $item->subtotal;
        });

        // Biaya admin & shipping fix
        $adminFee = $selectedItems->count() > 0 ? 1000 : 0;
        $shippingFee = $selectedItems->count() > 0 ? 5000 : 0;
        $total = $subtotal + $adminFee + $shippingFee;

        return view('cart.index', [
            'items' => $items,
            'activeTab' => $activeTab,
            'subtotal' => $subtotal,
            'adminFee' => $adminFee,
            'shippingFee' => $shippingFee,
            'total' => $total,
            'hasSelected' => $selectedItems->count() > 0
        ]);
    }

    // Menambah item ke keranjang (AJAX / Form)
    public function add(Request $request)
    {
        $user = Auth::user();
        $cart = Cart::firstOrCreate(['user_id' => $user->id]);

        $bookId = $request->input('book_id');
        $type = $request->input('type'); // 'beli' atau 'sewa'
        $quantity = $request->input('quantity', 1);

        // Cek apakah item sudah ada
        $existingItem = $cart->items()->where('book_id', $bookId)->where('type', $type)->first();

        if ($existingItem) {
            // Update quantity
            $existingItem->quantity += $quantity;
            $existingItem->save();
        } else {
            // Buat item baru
            $cart->items()->create([
                'book_id' => $bookId,
                'type' => $type,
                'quantity' => $quantity,
                'is_selected' => true // Default checked saat baru masuk
            ]);
        }

        // Redirect balik dengan pesan sukses (bisa diganti JSON jika pakai AJAX murni)
        return redirect()->route('cart.index', ['tab' => $type])->with('success', 'Berhasil ditambahkan ke keranjang');
    }

    // Update item (qty atau selection)
    public function update(Request $request, $id)
    {
        $item = CartItem::findOrFail($id);

        if ($request->has('quantity')) {
            $item->quantity = max(1, $request->input('quantity')); // Minimal 1
        }

        if ($request->has('is_selected')) {
            $item->is_selected = $request->input('is_selected');
        }

        $item->save();

        return response()->json(['success' => true]);
    }

    // Hapus item
    public function remove($id)
    {
        $item = CartItem::findOrFail($id);
        $type = $item->type;
        $item->delete();

        return redirect()->route('cart.index', ['tab' => $type]);
    }

    // Hapus item berdasarkan selection (opsional, buat di masa depan)
}
