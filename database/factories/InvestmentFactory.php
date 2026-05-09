<?php

namespace Database\Factories;

use App\Models\Investment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvestmentFactory extends Factory
{
    protected $model = Investment::class;

    public function definition(): array
    {
        $type = fake()->randomElement(['cash', 'deposito', 'emas', 'vallas', 'saham', 'lainnya']);
        $buyPrice = fake()->numberBetween(100000, 10000000);
        return [
            'user_id' => User::factory(),
            'name' => fake()->word(),
            'type' => $type,
            'purchase_date' => fake()->dateTimeBetween('-2 years', 'now')->format('Y-m-d'),
            'buy_price' => $buyPrice,
            'quantity' => fake()->randomFloat(4, 1, 100),
            'ticker' => fake()->optional()->lexify('????').'.JK',
            'note' => fake()->sentence(),
            'current_price' => $buyPrice * fake()->randomFloat(2, 0.8, 1.5),
        ];
    }
}
