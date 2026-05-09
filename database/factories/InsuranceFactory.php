<?php

namespace Database\Factories;

use App\Models\Insurance;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class InsuranceFactory extends Factory
{
    protected $model = Insurance::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => fake()->company(),
            'type' => fake()->randomElement(['jiwa', 'kesehatan', 'kendaraan', 'properti', 'lainnya']),
            'premium' => fake()->numberBetween(200000, 1000000),
            'due_date' => fake()->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
            'coverage' => fake()->sentence(),
        ];
    }
}
