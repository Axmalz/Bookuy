<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Book;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Logika Back Button
        $previousUrl = url()->previous();
        $currentUrl = url()->current();
        if ($previousUrl !== $currentUrl && !str_contains($previousUrl, '/cart')) {
            Session::put('cart_back_url', $previousUrl);
        }
        $backUrl = Session::get('cart_back_url', route('home'));

        $cart = Cart::firstOrCreate(['user_id' => $user->id]);
        $activeTab = $request->query('tab', 'beli');

        // Ambil item sesuai tab
        $items = $cart->items()->where('type', $activeTab)->with('book')->get();

        // Hitung Total (Hanya yang dicentang)
        $selectedItems = $items->where('is_selected', true);
        $subtotal = $selectedItems->sum(function($item) {
            return $item->subtotal;
        });

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
            'hasSelected' => $selectedItems->count() > 0,
            'backUrl' => $backUrl
        ]);
    }

    public function add(Request $request)
    {
        $user = Auth::user();
        $bookId = $request->input('book_id');
        $type = $request->input('type');
        $quantity = $request->input('quantity', 1);

        DB::beginTransaction();
        try {
            $book = Book::lockForUpdate()->find($bookId);

            // Validasi Stok Awal
            if ($type == 'beli') {
                if ($book->stok_beli < $quantity) {
                    throw new \Exception('Books ordered exceed stock!');
                }
                $book->stok_beli -= $quantity; // Kurangi stok fisik
            } else {
                // Sewa: Cek stok fisik > 0
                if ($book->stok_sewa < 1) {
                     throw new \Exception('Books ordered exceed stock!');
                }
                $book->stok_sewa -= 1; // Kurangi 1 unit fisik
            }
            $book->save();

            $cart = Cart::firstOrCreate(['user_id' => $user->id]);

            if ($type == 'beli') {
                $existingItem = $cart->items()->where('book_id', $bookId)->where('type', 'beli')->first();
                if ($existingItem) {
                    $existingItem->quantity += $quantity;
                    $existingItem->save();
                } else {
                    $cart->items()->create([
                        'book_id' => $bookId, 'type' => 'beli', 'quantity' => $quantity, 'is_selected' => true
                    ]);
                }
            } else {
                // Sewa selalu item baru
                $cart->items()->create([
                    'book_id' => $bookId, 'type' => 'sewa', 'quantity' => $quantity, 'is_selected' => true
                ]);
            }

            DB::commit();

            if ($request->wantsJson()) {
                return response()->json(['success' => true]);
            }
            return redirect()->route('cart.index', ['tab' => $type])->with('success', 'Berhasil');

        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
            }
            return back()->withErrors($e->getMessage());
        }
    }

    // UPDATE (Logic Server Side tetap menjaga konsistensi stok)
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $item = CartItem::with('book')->findOrFail($id);
            $book = $item->book;

            if ($request->has('quantity')) {
                $newQty = (int) $request->input('quantity');
                $diff = $newQty - $item->quantity;

                if ($diff != 0) {
                    if ($item->type == 'beli') {
                        // Cek stok lagi di server untuk keamanan
                        if ($diff > 0) {
                            if ($book->stok_beli < $diff) {
                                // Jangan lempar error 500, tapi kembalikan status sukses false
                                // agar JS bisa handle (misal revert UI)
                                return response()->json(['success' => false, 'message' => 'Stok habis'], 200);
                            }
                            $book->stok_beli -= $diff;
                        } else {
                            $book->stok_beli += abs($diff);
                        }
                        $book->save();
                    }
                    $item->quantity = $newQty;
                }
            }

            if ($request->has('is_selected')) {
                $item->is_selected = filter_var($request->input('is_selected'), FILTER_VALIDATE_BOOLEAN);
            }

            $item->save();
            DB::commit();

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    // REMOVE ITEM
    public function remove($id)
    {
        DB::beginTransaction();
        try {
            $item = CartItem::with('book')->findOrFail($id);
            $book = $item->book;
            $type = $item->type;

            // Kembalikan stok ke buku saat dihapus dari keranjang
            if ($type == 'beli') {
                $book->stok_beli += $item->quantity;
            } else {
                // Sewa: Kembalikan 1 unit stok fisik
                $book->stok_sewa += 1;
            }
            $book->save();

            $item->delete();
            DB::commit();

            return redirect()->route('cart.index', ['tab' => $type]);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors('Gagal menghapus item.');
        }
    }
}
