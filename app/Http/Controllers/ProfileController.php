<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class ProfileController extends Controller
{
    // Menampilkan halaman profil utama
    public function index()
    {
        $user = Auth::user();
        return view('profile.index', compact('user'));
    }

    // Menampilkan form edit profil
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    // Menyimpan perubahan profil
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:3072', // Max 3MB
            'gender' => 'nullable|in:Male,Female,Prefer not to say',
            'semester' => 'nullable|in:1,2,3,4,5,6,7,8,Tidak ada',
            'description' => 'nullable|string|max:500',
        ]);

        // Update nama & info lain (jika kolom ada di DB, nanti kita tambahkan migrasi)
        $user->name = $request->name;

        // Simpan data tambahan (asumsi kita akan buat kolom baru atau pakai JSON column 'profile_data')
        // Untuk simplisitas saat ini, kita simpan di kolom terpisah yang akan kita buat migrasinya.
        $user->gender = $request->gender;
        $user->semester = $request->semester;
        $user->description = $request->description;

        // Handle Upload Foto
        if ($request->hasFile('profile_photo')) {
            // Hapus foto lama jika ada (opsional)
            // if ($user->profile_photo_path) Storage::delete($user->profile_photo_path);

            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $user->profile_photo_path = $path;
        }

        $user->save();

        return redirect()->route('profile.index')->with('success', 'Profil berhasil diperbarui!');
    }
}
