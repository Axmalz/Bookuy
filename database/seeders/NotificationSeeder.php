<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        if (!$user) return;

        // 1. Hari Ini (Today)
        Notification::create([
            'user_id' => $user->id,
            'title' => '20% Special Discount!',
            'message' => 'Promo spesial khusus hari ini untukmu, cek sekarang!',
            'type' => 'promo',
            'icon' => 'icon-notif-discount.png',
            'created_at' => Carbon::now()->subHours(2)
        ]);

        Notification::create([
            'user_id' => $user->id,
            'title' => 'Order Delivered!',
            'message' => 'Paket buku "Sistem Enterprise" telah sampai di alamat tujuan.',
            'type' => 'transaction',
            'icon' => 'icon-shopping-bag.png',
            'created_at' => Carbon::now()->subHours(5)
        ]);

        // 2. Kemarin (Yesterday)
        Notification::create([
            'user_id' => $user->id,
            'title' => 'Top Up E-wallet Successfully!',
            'message' => 'Saldo e-wallet kamu berhasil ditambahkan sebesar Rp 100.000.',
            'type' => 'transaction',
            'icon' => 'icon-notif-wallet.png',
            'created_at' => Carbon::yesterday()->setHour(14)
        ]);

        Notification::create([
            'user_id' => $user->id,
            'title' => 'New Service Available!',
            'message' => 'Sekarang kamu bisa melacak posisi kurir secara real-time.',
            'type' => 'system',
            'icon' => 'icon-notif-map-pin.png',
            'created_at' => Carbon::yesterday()->setHour(10)
        ]);

        // 3. Tanggal Lama (Old)
        Notification::create([
            'user_id' => $user->id,
            'title' => 'Credit Card Connected!',
            'message' => 'Kartu kredit Visa berakhiran 2512 berhasil dihubungkan.',
            'type' => 'account',
            'icon' => 'icon-notif-credit-card.png',
            'created_at' => Carbon::now()->subDays(5)
        ]);
    }
}
