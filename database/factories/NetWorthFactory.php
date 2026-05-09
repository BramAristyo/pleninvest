<?php

namespace Database\Factories;

use App\Models\NetWorth;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class NetWorthFactory extends Factory
{
    protected $model = NetWorth::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'savings' => fake()->numberBetween(1000000, 20000000),
            'emergency_fund' => fake()->numberBetween(5000000, 50000000),
            'investments' => fake()->numberBetween(10000000, 100000000),
            'other_assets' => fake()->numberBetween(0, 10000000),
            'debt' => fake()->numberBetween(0, 5000000),
        ];
    }
}
