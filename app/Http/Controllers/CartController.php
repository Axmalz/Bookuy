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

        // Logika Back Button (sama seperti sebelumnya)
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

        // Hitung Total hanya dari item yang dicentang (is_selected = 1)
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

    // Menambah item ke keranjang (Sama seperti sebelumnya dengan validasi stok awal)
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
                $book->stok_beli -= $quantity;
            } else {
                if ($book->stok_sewa < 1) {
                     throw new \Exception('Books ordered exceed stock!');
                }
                $book->stok_sewa -= 1;
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
                $cart->items()->create([
                    'book_id' => $bookId, 'type' => 'sewa', 'quantity' => $quantity, 'is_selected' => true
                ]);
            }

            DB::commit();

            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Berhasil ditambahkan']);
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

    // UPDATE QUANTITY (Logika Baru: Cek Stok sebelum tambah)
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $item = CartItem::with('book')->findOrFail($id);
            $book = $item->book; // Lock row if needed

            // Jika update quantity
            if ($request->has('quantity')) {
                $newQty = (int) $request->input('quantity');
                $diff = $newQty - $item->quantity;

                if ($item->type == 'beli') {
                    // Jika menambah jumlah beli, cek stok beli buku
                    if ($diff > 0) {
                        if ($book->stok_beli < $diff) {
                            throw new \Exception('Stok tidak mencukupi!');
                        }
                        $book->stok_beli -= $diff; // Kurangi stok buku
                    } else {
                        // Jika mengurangi jumlah beli, kembalikan stok ke buku
                        $book->stok_beli += abs($diff);
                    }
                }
                // Untuk sewa, quantity adalah durasi, jadi tidak mempengaruhi stok fisik (stok sewa buku tetap -1 per item)
                // Jadi kita bisa langsung update quantity (durasi) tanpa ubah stok buku

                $book->save();
                $item->quantity = $newQty;
            }

            // Jika update selection (checkbox)
            if ($request->has('is_selected')) {
                $item->is_selected = $request->input('is_selected');
            }

            $item->save();
            DB::commit();

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    // REMOVE ITEM (Logika Baru: Kembalikan Stok)
    public function remove($id)
    {
        DB::beginTransaction();
        try {
            $item = CartItem::with('book')->findOrFail($id);
            $book = $item->book;
            $type = $item->type;

            // Kembalikan stok ke buku
            if ($type == 'beli') {
                $book->stok_beli += $item->quantity;
            } else {
                // Sewa: Kembalikan 1 stok unit fisik
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
