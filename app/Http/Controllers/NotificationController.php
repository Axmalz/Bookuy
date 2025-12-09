<?php

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
        $groups = [
            'Today' => [],
            'Yesterday' => [],
            'Older' => [] // Akan diganti tanggal spesifik di View logic jika mau, atau dikelompokkan per tanggal
        ];

        foreach ($allNotifications as $notif) {
            if ($notif->created_at->isToday()) {
                $groups['Today'][] = $notif;
            } elseif ($notif->created_at->isYesterday()) {
                $groups['Yesterday'][] = $notif;
            } else {
                // Kelompokkan berdasarkan format tanggal 'May 7, 2025'
                $dateKey = $notif->created_at->format('F j, Y');
                $groups[$dateKey][] = $notif;
            }
        }

        // Hapus array kosong di 'Older' jika kita menggunakan struktur dinamis di atas
        // Logika di atas sudah otomatis membuat key baru untuk tanggal lama.
        // Hapus key 'Older' inisial jika tidak dipakai
        unset($groups['Older']);

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
