<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\Book;
use App\Models\User;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil user admin kita (biasanya ID 1)
        $myUser = User::first();

        if (!$myUser) return;

        // 1. Buat Data "Ongoing" (Packing, Picked, In Transit)
        // Di mana user kita adalah PENJUAL (seller_id = $myUser->id)
        $books = Book::inRandomOrder()->take(5)->get();

        foreach($books as $book) {
            Order::create([
                'buyer_id' => User::where('id', '!=', $myUser->id)->inRandomOrder()->first()->id ?? User::factory()->create()->id,
                'seller_id' => $myUser->id, // PENTING: Kita sebagai penjual
                'book_id' => $book->id,
                'type' => rand(0,1) ? 'beli' : 'sewa',
                'price' => $book->harga_beli,
                'status' => collect(['Packing', 'Picked', 'In Transit'])->random(),
                'created_at' => now()->subDays(rand(1, 5)),
            ]);
        }

        // 2. Buat Data "Completed" (Delivered)
        $booksCompleted = Book::inRandomOrder()->take(5)->get();

        foreach($booksCompleted as $book) {
            Order::create([
                'buyer_id' => User::where('id', '!=', $myUser->id)->inRandomOrder()->first()->id ?? User::factory()->create()->id,
                'seller_id' => $myUser->id, // PENTING: Kita sebagai penjual
                'book_id' => $book->id,
                'type' => rand(0,1) ? 'beli' : 'sewa',
                'price' => $book->harga_beli,
                'status' => 'Delivered',
                'rating' => rand(3, 5), // Rating dari pembeli
                'created_at' => now()->subDays(rand(10, 30)),
            ]);
        }
    }
}
