<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Category;

class BookFactory extends Factory
{
    public function definition(): array
    {
        $images = [];
        $count = rand(1, 3);
        $color = $this->faker->hexColor(false);
        for($i=0; $i<$count; $i++) {
            $images[] = "https://placehold.co/270x480/{$color}/white?text=Img+" . ($i+1);
        }

        return [
            'judul_buku' => $this->faker->sentence(3),
            'nama_penulis' => $this->faker->name(),
            'harga_beli' => $this->faker->numberBetween(50000, 200000),
            'harga_sewa' => $this->faker->numberBetween(10000, 50000),

            // Stok Random (bisa 0)
            'stok_beli' => $this->faker->numberBetween(0, 15),
            'stok_sewa' => $this->faker->numberBetween(0, 5),

            'gambar_buku' => $images,
            'deskripsi_buku' => $this->faker->paragraph(3),
            'user_id' => User::first()->id,
            'kondisi_buku' => $this->faker->randomElement(['baru', 'bekas premium', 'bekas usang']),
            'alamat_buku' => $this->faker->city() . ', ' . $this->faker->state(),
            'category_id' => Category::all()->random()->id,
            'jumlah_halaman' => $this->faker->numberBetween(100, 500),
            'semester' => $this->faker->randomElement(['1', '2', '3', '4', '5', '6', '7', '8', 'tidak ada']),
        ];
    }
}
