<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Coba gunakan helper fake() bawaan Laravel dulu
        // Jika gagal, gunakan instance manual atau fallback data statis
        try {
            $name = fake()->name();
            $email = fake()->unique()->safeEmail();
            $gender = fake()->randomElement(['Male', 'Female', 'Prefer not to say']);
            $semester = fake()->randomElement(['1', '2', '3', '4', '5', '6', '7', '8', 'Tidak ada']);
            $desc = fake()->paragraph(2);
        } catch (\Throwable $e) {
            // Fallback jika Faker tidak tersedia di production
            $name = 'User ' . Str::random(5);
            $email = 'user_' . Str::random(5) . '@example.com';
            $gender = 'Male';
            $semester = '1';
            $desc = 'Default description because Faker is missing.';
        }

        return [
            'name' => $name,
            'email' => $email,
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),

            // Data Dummy Tambahan
            'gender' => $gender,
            'semester' => $semester,
            'description' => $desc,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
