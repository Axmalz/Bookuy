<?php

// Kode ditulis oleh :
// Nama  : Fadhiil Akmal Hamizan
// Github: Axmalz
// NRP   : 5026231128
// Kelas : PPPL B

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Ambil notifikasi urut dari yang terbaru
        $allNotifications = $user->notifications()->latest()->get();

        // Kelompokkan data
        // Kita inisialisasi agar urutannya tetap: Today, lalu Yesterday, lalu Tanggal Lain
        $groups = [
            'Today' => [],
            'Yesterday' => [],
        ];

        foreach ($allNotifications as $notif) {
            if ($notif->created_at->isToday()) {
                $groups['Today'][] = $notif;
            } elseif ($notif->created_at->isYesterday()) {
                $groups['Yesterday'][] = $notif;
            } else {
                // Kelompokkan berdasarkan format tanggal, misal 'May 7, 2025'
                $dateKey = $notif->created_at->format('F j, Y');
                $groups[$dateKey][] = $notif;
            }
        }

        // --- BAGIAN INI DIPERBAIKI ---
        // Hapus key 'Today' dan 'Yesterday' jika kosong agar judulnya tidak muncul di view
        if (empty($groups['Today'])) {
            unset($groups['Today']);
        }

        if (empty($groups['Yesterday'])) {
            unset($groups['Yesterday']);
        }
        // -----------------------------

        return view('notification.index', compact('groups'));
    }

    // 1. Menampilkan Form Buat Notifikasi Global
    public function createGlobal()
    {
        return view('notification.create_global');
    }

    // 2. Proses Kirim ke SEMUA User
    public function storeGlobal(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required',
            'icon' => 'required',
        ]);

        // Ambil semua user
        $users = User::all();

        // Loop setiap user dan buatkan notifikasi
        foreach ($users as $user) {
            Notification::create([
                'user_id' => $user->id,
                'title' => $request->title,
                'message' => $request->message,
                'type' => $request->type, // system, promo, info
                'icon' => $request->icon,
                'is_read' => false,
            ]);
        }

        return redirect()->route('notification.index')->with('success', 'Notifikasi global berhasil dikirim ke ' . $users->count() . ' pengguna!');
    }
}
