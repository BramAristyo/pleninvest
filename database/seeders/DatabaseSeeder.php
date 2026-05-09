<?php

namespace Database\Seeders;

use App\Models\Benefit;
use App\Models\Insurance;
use App\Models\Investment;
use App\Models\NetWorth;
use App\Models\Reimbursement;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create exactly one user: Camelia White with ID 1
        $camelia = User::factory()->create([
            'id' => 1,
            'name' => 'Camelia White',
            'username' => 'camelia',
            'pin' => Hash::make('1111'),
        ]);

        // Seed healthy financial data for Camelia

        // 1. Transactions
        Transaction::factory(15)->create([
            'user_id' => $camelia->id,
            'type' => 'income',
            'category' => 'Gaji Pastoral',
            'amount' => 15000000,
        ]);

        Transaction::factory(40)->create([
            'user_id' => $camelia->id,
            'type' => 'expense',
            'amount' => function() { return fake()->numberBetween(50000, 1000000); },
        ]);

        // 2. Net Worth
        NetWorth::factory()->create([
            'user_id' => $camelia->id,
            'savings' => 50000000,
            'emergency_fund' => 100000000,
            'investments' => 250000000,
            'other_assets' => 50000000,
            'debt' => 0,
        ]);

        // 3. Investments
        Investment::factory()->create([
            'user_id' => $camelia->id,
            'type' => 'emas',
            'name' => 'Antam 10g',
            'quantity' => 10,
            'buy_price' => 1000000,
            'current_price' => 1100000,
            'purchase_date' => '2024-01-15'
        ]);
        
        Investment::factory()->create([
            'user_id' => $camelia->id,
            'type' => 'saham',
            'name' => 'BBCA',
            'ticker' => 'BBCA.JK',
            'quantity' => 1000,
            'buy_price' => 8500,
            'current_price' => 10000,
            'purchase_date' => '2024-02-10'
        ]);
        
        Investment::factory(8)->create(['user_id' => $camelia->id]);

        // 4. Benefits & Insurances
        Benefit::factory()->create(['user_id' => $camelia->id, 'name' => 'Tunjangan Transportasi', 'type' => 'transportasi', 'amount' => 2500000]);
        Benefit::factory()->create(['user_id' => $camelia->id, 'name' => 'Tunjangan Makan', 'type' => 'makan', 'amount' => 1500000]);
        
        Insurance::factory()->create(['user_id' => $camelia->id, 'name' => 'BPJS Kesehatan', 'type' => 'kesehatan', 'premium' => 150000]);
        Insurance::factory()->create(['user_id' => $camelia->id, 'name' => 'Manulife Protection', 'type' => 'jiwa', 'premium' => 750000]);

        // 5. Reimbursements
        Reimbursement::factory(12)->create(['user_id' => $camelia->id, 'status' => 'received']);
        Reimbursement::factory(4)->create(['user_id' => $camelia->id, 'status' => 'pending']);
    }
}
