<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Technician>
 */
class TechnicianFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // 'name' => fake()->name(),
            // 'phone' => fake()->unique()->phoneNumber(),
            // 'designation' => fake()->text(),
            // 'salary' => fake()->randomElement(10000, 100000),
            // 'age' => fake()->randomElement(3, 100),
            // 'doj' => fake()->dateTime($max = 'now', $timezone = null),
            // 'email' => fake()->unique()->safeEmail(),
            // 'email_verified_at' => now(),
            // 'password' => Hash::make('password'),
            // 'remember_token' => Str::random(10),
        ];
    }
}
