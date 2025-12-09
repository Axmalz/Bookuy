<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Address;
use Illuminate\Support\Facades\Auth;

use App\Models\Notification;

class AddressController extends Controller
{
    // Halaman Daftar Alamat
    public function index()
    {
        $user = Auth::user();
        // Urutkan: Default paling atas, lalu terbaru
        $addresses = $user->addresses()->orderByDesc('is_default')->latest()->get();

        return view('address.index', compact('addresses'));
    }

    // Halaman Tambah Alamat Baru
    public function create()
    {
        $user = Auth::user();
        if ($user->addresses()->count() >= 5) {
            return redirect()->route('address.index')->with('error', 'Maksimal 5 alamat.');
        }
        return view('address.create');
    }

    // Simpan Alamat Baru
    public function store(Request $request)
    {
        $request->validate([
            'nickname' => 'required|string|max:50',
            'full_address' => 'required|string|max:500',
        ]);

        $user = Auth::user();

        // Cek apakah ini alamat pertama? Jika ya, otomatis default.
        // Atau jika user mencentang "Make default"
        $isDefault = $request->boolean('is_default') || $user->addresses()->count() == 0;

        if ($isDefault) {
            // Reset default lama
            $user->addresses()->update(['is_default' => false]);
        }

        $user->addresses()->create([
            'nickname' => $request->nickname,
            'full_address' => $request->full_address,
            'is_default' => $isDefault,
        ]);

        Notification::create([
            'user_id' => $user->id,
            'title'   => 'New Address Added!',
            'message' => "Alamat '{$request->nickname}' berhasil ditambahkan ke daftar alamatmu.",
            'type'    => 'account',
            'icon'    => 'icon-notif-location-pin.png'
        ]);

        // Kita akan handle redirect via JS di view untuk menampilkan modal sukses dulu
        return response()->json(['success' => true]);
    }

    // Set Alamat Default (via Radio Button)
    public function setDefault($id)
    {
        $user = Auth::user();

        // Reset semua
        $user->addresses()->update(['is_default' => false]);

        // Set yang dipilih
        $address = $user->addresses()->findOrFail($id);
        $address->update(['is_default' => true]);

        return redirect()->back()->with('success', 'Alamat default diubah.');
    }

    // Halaman Edit
    public function edit($id)
    {
        $address = Auth::user()->addresses()->findOrFail($id);
        return view('address.edit', compact('address'));
    }

    // Update Alamat
    public function update(Request $request, $id)
    {
        $address = Auth::user()->addresses()->findOrFail($id);

        $request->validate([
            'nickname' => 'required|string|max:50',
            'full_address' => 'required|string|max:500',
        ]);

        $isDefault = $request->boolean('is_default');

        if ($isDefault) {
            Auth::user()->addresses()->where('id', '!=', $id)->update(['is_default' => false]);
        }
        // Jika address ini sebelumnya default dan user uncheck default,
        // kita harus tetap punya satu default.
        // Logic sederhana: jika satu-satunya alamat, tetap default.
        elseif ($address->is_default && Auth::user()->addresses()->count() > 1) {
             // Biarkan false, user harus pilih default lain manual
        } elseif ($address->is_default) {
             $isDefault = true; // Force true jika satu-satunya
        }

        $address->update([
            'nickname' => $request->nickname,
            'full_address' => $request->full_address,
            'is_default' => $isDefault,
        ]);

        return response()->json(['success' => true]);
    }

    // Hapus Alamat
    public function destroy($id)
    {
        $address = Auth::user()->addresses()->findOrFail($id);

        if ($address->is_default) {
            return back()->with('error', 'Tidak bisa menghapus alamat default. Ubah default terlebih dahulu.');
        }

        $address->delete();
        return back()->with('success', 'Alamat dihapus.');
    }
}
