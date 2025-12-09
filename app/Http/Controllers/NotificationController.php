<?php

namespace App\Http\Controllers;

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
}
