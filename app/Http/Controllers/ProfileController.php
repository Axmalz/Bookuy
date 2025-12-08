<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Order;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('profile.index', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'name' => 'required|string|max:255',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:3072',
            'gender' => 'nullable',
            'semester' => 'nullable',
            'description' => 'nullable',
        ]);

        $user->name = $request->name;
        $user->gender = $request->gender;
        $user->semester = $request->semester;
        $user->description = $request->description;

        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo_path) {
                Storage::delete($user->profile_photo_path);
            }
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $user->profile_photo_path = $path;
        }

        $user->save();
        return redirect()->route('profile.index')->with('success', 'Profil berhasil diperbarui!');
    }

    // Method Sales History (Penjualan - Referensi)
    public function salesHistory(Request $request)
    {
        $user = Auth::user();
        $tab = $request->query('tab', 'ongoing');

        $query = Order::with('book')
                      ->where('seller_id', $user->id);

        if ($tab == 'completed') {
            $query->where('status', 'Delivered');
        } else {
            $query->whereIn('status', ['Packing', 'Picked', 'In Transit']);
        }

        $orders = $query->latest()->get();

        return view('profile.sales_history', compact('orders', 'tab'));
    }

    // == BARU: Method Purchase History (Pembelian) ==
    public function purchaseHistory(Request $request)
    {
        $user = Auth::user();
        $tab = $request->query('tab', 'ongoing'); // ongoing | completed

        // Ambil order di mana user adalah PEMBELI (buyer_id)
        $query = Order::with(['book', 'seller'])
                      ->where('buyer_id', $user->id);

        if ($tab == 'completed') {
            // Tab Completed: Status Delivered
            $query->where('status', 'Delivered');
        } else {
            // Tab Ongoing: Status Packing, Picked, In Transit
            $query->whereIn('status', ['Packing', 'Picked', 'In Transit']);
        }

        $orders = $query->latest()->get();

        return view('profile.purchase_history', compact('orders', 'tab'));
    }
}
