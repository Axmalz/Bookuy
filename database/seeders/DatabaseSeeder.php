<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
{
    // 1. Buat User Admin
    if (User::count() == 0) {
        User::factory()->create([
            'name' => 'Fadhiil',
            'email' => 'fadhiilhamizan2004@gmail.com',
            'password' => bcrypt('12345678'), // Password harus di-hash
        ]);
    }

        // 2. Buat User Lain
        User::factory(100)->create();

        // 3. Panggil Seeder
        $this->call([
            CategorySeeder::class,
            BookSeeder::class,
            OrderSeeder::class, // <-- PASTIKAN INI ADA
            NotificationSeeder::class,
        ]);
    }
}
