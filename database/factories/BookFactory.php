<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Category;

class BookFactory extends Factory
{
    public function definition(): array
    {
        $faker = \Faker\Factory::create();

        $images = [];
        $count = rand(1, 3);
        $color = $faker->hexColor();
        $colorClean = str_replace('#', '', $color);

        for($i=0; $i<$count; $i++) {
            $images[] = "https://placehold.co/270x480/{$colorClean}/white?text=Img+" . ($i+1);
        }

        return [
            'judul_buku' => $faker->sentence(3),
            'nama_penulis' => $faker->name(),
            'harga_beli' => $faker->numberBetween(50000, 200000),
            'harga_sewa' => $faker->numberBetween(10000, 50000),
            'stok_beli' => $faker->numberBetween(0, 15),
            'stok_sewa' => $faker->numberBetween(0, 5),
            'gambar_buku' => $images,
            'deskripsi_buku' => $faker->paragraph(3),
            'user_id' => User::first()?->id ?? User::factory(),
            'kondisi_buku' => $faker->randomElement(['baru', 'bekas premium', 'bekas usang']),
            'alamat_buku' => $faker->city() . ', ' . $faker->state(),
            'category_id' => Category::inRandomOrder()->first()?->id ?? Category::factory(),
            'jumlah_halaman' => $faker->numberBetween(100, 500),
            'semester' => $faker->randomElement(['1', '2', '3', '4', '5', '6', '7', '8', 'tidak ada']),
        ];
    }
}
