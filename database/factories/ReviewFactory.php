<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    public function definition(): array
    {
        $faker = \Faker\Factory::create();

        return [
            'rating' => $faker->numberBetween(1, 5),
            'comment' => $faker->paragraph(rand(1, 3)),
            'created_at' => $faker->dateTimeBetween('-3 months', 'now'),
            'user_id' => \App\Models\User::factory(),
            'book_id' => \App\Models\Book::factory(),
        ];
    }
}
