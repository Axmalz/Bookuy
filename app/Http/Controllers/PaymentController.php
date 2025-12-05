<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    // Halaman Daftar Payment
    public function index()
    {
        $user = Auth::user();
        // Urutkan: Default paling atas, lalu terbaru
        $cards = $user->payments()->orderByDesc('is_default')->latest()->get();

        return view('payment.index', compact('cards'));
    }

    // Halaman Tambah Kartu Baru
    public function create()
    {
        $user = Auth::user();
        if ($user->payments()->count() >= 5) {
            return redirect()->route('payment.index')->with('error', 'Maksimal 5 kartu.');
        }
        return view('payment.create');
    }

    // Simpan Kartu Baru
    public function store(Request $request)
    {
        $request->validate([
            'card_number' => 'required|numeric|digits:16',
            'expiry_date' => 'required|string|max:5', // Simple validation for MM/YY
            'cvc' => 'required|numeric|digits:3',
        ]);

        $user = Auth::user();

        // Logic Default: Jika kartu pertama, otomatis default.
        $isDefault = $user->payments()->count() == 0;

        // Random Card Type (Visa / Mastercard)
        $type = rand(0, 1) ? 'Visa' : 'Mastercard';

        $user->payments()->create([
            'card_number' => $request->card_number,
            'expiry_date' => $request->expiry_date,
            'cvc' => $request->cvc,
            'card_type' => $type,
            'is_default' => $isDefault,
        ]);

        // Response JSON untuk handle popup sukses di frontend
        return response()->json(['success' => true]);
    }

    // Halaman Edit Kartu
    public function edit($id)
    {
        $card = Auth::user()->payments()->findOrFail($id);
        return view('payment.edit', compact('card'));
    }

    // Update Kartu
    public function update(Request $request, $id)
    {
        $card = Auth::user()->payments()->findOrFail($id);

        $request->validate([
            'card_number' => 'required|numeric|digits:16',
            'expiry_date' => 'required|string|max:5',
            'cvc' => 'required|numeric|digits:3',
        ]);

        $card->update([
            'card_number' => $request->card_number,
            'expiry_date' => $request->expiry_date,
            'cvc' => $request->cvc,
        ]);

        return response()->json(['success' => true]);
    }

    // Set Default (via Radio Button)
    public function setDefault($id)
    {
        $user = Auth::user();
        $user->payments()->update(['is_default' => false]); // Reset semua

        $user->payments()->where('id', $id)->update(['is_default' => true]); // Set baru

        return redirect()->route('payment.index');
    }

    // Hapus Kartu
    public function destroy($id)
    {
        $card = Auth::user()->payments()->findOrFail($id);
        $card->delete();

        // Jika yang dihapus adalah default, set kartu lain jadi default (opsional)
        $latest = Auth::user()->payments()->latest()->first();
        if ($latest) {
            $latest->update(['is_default' => true]);
        }

        return back();
    }
}
