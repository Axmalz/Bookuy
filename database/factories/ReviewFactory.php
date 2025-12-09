<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    public function definition(): array
    {
        return [
            'rating' => $this->faker->numberBetween(1, 5),
            'comment' => $this->faker->paragraph(rand(1, 3)), // 1-3 paragraf
            'created_at' => $this->faker->dateTimeBetween('-3 months', 'now'),
            // Pastikan user_id dan book_id dihandle oleh Seeder,
            // atau beri default factory jika dipanggil manual
            'user_id' => \App\Models\User::factory(),
            'book_id' => \App\Models\Book::factory(),
        ];
    }
}
