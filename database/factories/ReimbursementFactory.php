<?php

namespace Database\Factories;

use App\Models\Reimbursement;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReimbursementFactory extends Factory
{
    protected $model = Reimbursement::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'date' => fake()->dateTimeBetween('-3 months', 'now')->format('Y-m-d'),
            'category' => fake()->randomElement(['bensin', 'buku', 'seminar', 'listrik', 'air', 'listrik_air', 'ipl', 'parkir', 'kesehatan', 'lainnya']),
            'note' => fake()->sentence(),
            'amount' => fake()->numberBetween(50000, 500000),
            'status' => fake()->randomElement(['pending', 'approved', 'received']),
        ];
    }
}
