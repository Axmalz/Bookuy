<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Buat User Admin (Fadhiil) jika belum ada
        if (User::count() == 0) {
            User::factory()->create([
            'name' => 'Fadhiil',
            'email' => 'fadhiil@example.com'
            ]);
        }

        // 2. Buat 30 User Tambahan (Penjual & Reviewer)
        User::factory(30)->create();

        // 3. Panggil Seeder Lain (Kategori & Buku)
        // Seeder Buku akan menggunakan user-user yang baru saja dibuat di atas
        $this->call([
            CategorySeeder::class,
            BookSeeder::class,
        ]);
    }
}
