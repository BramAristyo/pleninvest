<?php

namespace Database\Factories;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition(): array
    {
        $type = fake()->randomElement(['income', 'expense']);
        $categories = [
            'income' => ['Gaji Pastoral', 'Honorarium', 'Persembahan / Natura', 'Tunjangan', 'Klaim Asuransi', 'Investasi Return', 'Lain-lain Pemasukan'],
            'expense' => ['Kebutuhan Pokok', 'Transportasi', 'Kesehatan', 'Pendidikan / Buku', 'Tabungan', 'Dana Darurat', 'Persepuluhan', 'Pelayanan', 'Pakaian', 'Hiburan', 'Lain-lain']
        ];

        return [
            'user_id' => User::factory(),
            'date' => fake()->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'type' => $type,
            'category' => fake()->randomElement($categories[$type]),
            'note' => fake()->sentence(),
            'amount' => $type === 'income' ? fake()->numberBetween(1000000, 10000000) : fake()->numberBetween(10000, 1000000),
            'benefit_type' => fake()->optional()->randomElement(['tunjangan', 'klaim_asuransi']),
            'benefit_note' => fake()->optional()->sentence(),
        ];
    }
}
