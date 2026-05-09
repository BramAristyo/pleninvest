<?php

namespace Database\Factories;

use App\Models\Benefit;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BenefitFactory extends Factory
{
    protected $model = Benefit::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => fake()->word(),
            'type' => fake()->randomElement(['transportasi', 'makan', 'perumahan', 'pendidikan', 'kesehatan', 'lainnya']),
            'amount' => fake()->numberBetween(500000, 2000000),
            'note' => fake()->sentence(),
        ];
    }
}
