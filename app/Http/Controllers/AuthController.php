<?php

// Kode ditulis oleh :
// Nama  : Fadhiil Akmal Hamizan
// Github: Axmalz
// NRP   : 5026231128
// Kelas : PPPL B

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

use App\Models\Notification;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman formulir registrasi.
     */
    public function showRegisterForm()
    {
        return view('auth.signup');
    }

    /**
     * Memproses data registrasi dari formulir.
     */
    public function register(Request $request)
    {
        // 1. Validasi Backend
        // Ini adalah aturan validasi yang Anda minta
        $request->validate([
            'fullname' => 'required|string|min:4',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        // 2. Buat User Baru
        // Kita menggunakan Hash::make() untuk mengenkripsi password
        try {
            $user = User::create([
                'name'     => $request->fullname,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
            ]);

            Notification::create([
                'user_id' => $user->id,
                'title'   => 'Welcome Aboard!',
                'message' => 'Akun Anda berhasil dibuat. Selamat menjelajahi Bookuy!',
                'type'    => 'system',
                'icon'    => 'icon-notif-profile.png'
            ]);

            // 3. Login User secara otomatis setelah registrasi
            Auth::login($user);

            // 4. Arahkan ke halaman '/home' (yang akan kita buat placeholder-nya)
            return redirect()->route('home');

        } catch (\Exception $e) {
            // Jika terjadi error (misalnya database), kembali dengan pesan error
            return back()->withInput()->withErrors(['msg' => 'Registrasi gagal, silakan coba lagi.']);
        }
    }

    /**
     * Menampilkan halaman formulir login.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Menangani proses login.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        // 1. Validasi input
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // 2. Coba autentikasi pengguna
        // Auth::attempt() akan otomatis memeriksa email
        // dan membandingkan password yang di-hash di database
        if (Auth::attempt($credentials)) {
            // 3. Jika berhasil, regenerasi session
            $request->session()->regenerate();

            // Arahkan ke halaman home
            return redirect()->intended(route('home'));
        }

        // 4. Jika gagal, kembali ke login dengan pesan error
        // Kita melempar error validasi ke field 'email'
        throw ValidationException::withMessages([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ]);
    }

    /**
     * Menangani proses logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
