<?php

// Kode ditulis oleh :
// Nama  : Fadhiil Akmal Hamizan
// Github: Axmalz
// NRP   : 5026231128
// Kelas : PPPL B

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Message;
use App\Models\User;
use Carbon\Carbon;

class ChatSeeder extends Seeder
{
    public function run(): void
    {
        $mainUser = User::first();
        if (!$mainUser) return;

        $otherUsers = User::where('id', '!=', $mainUser->id)->take(3)->get();

        if ($otherUsers->count() < 3) return;

        // Skenario 1: User sebagai PENJUAL (Orang lain chat duluan)
        // User A chat Main User
        $buyer = $otherUsers[0];
        Message::create([
            'sender_id' => $buyer->id,
            'receiver_id' => $mainUser->id,
            'message' => 'Halo kak, buku Kalkulus masih ada?',
            'created_at' => Carbon::yesterday()->setHour(10)
        ]);
        Message::create([
            'sender_id' => $mainUser->id,
            'receiver_id' => $buyer->id,
            'message' => 'Masih ada kok kak, kondisinya mulus.',
            'created_at' => Carbon::yesterday()->setHour(10)->addMinutes(5)
        ]);
        Message::create([
            'sender_id' => $buyer->id,
            'receiver_id' => $mainUser->id,
            'message' => 'Bisa COD di perpus pusat gak?',
            'is_read' => false, // Belum dibaca
            'created_at' => Carbon::now()->subMinutes(15)
        ]);

        // Skenario 2: User sebagai PEMBELI (User chat duluan)
        // Main User chat User B
        $seller = $otherUsers[1];
        Message::create([
            'sender_id' => $mainUser->id,
            'receiver_id' => $seller->id,
            'message' => 'Permisi, buku Fisika Dasarnya edisi tahun berapa ya?',
            'created_at' => Carbon::now()->subDays(2)
        ]);
        Message::create([
            'sender_id' => $seller->id,
            'receiver_id' => $mainUser->id,
            'message' => 'Edisi 2022 kak.',
            'is_read' => true,
            'created_at' => Carbon::now()->subDays(2)->addHours(1)
        ]);

        // Skenario 3: Penjual Lain
        $buyer2 = $otherUsers[2];
        Message::create([
            'sender_id' => $buyer2->id,
            'receiver_id' => $mainUser->id,
            'message' => 'Kak ini harganya bisa nego tipis gak?',
            'created_at' => Carbon::now()->subHour()
        ]);
    }
}
