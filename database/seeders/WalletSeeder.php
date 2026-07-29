<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Seeder;

class WalletSeeder extends Seeder
{
    public function run(): void
    {
        $testUser = User::where('email', 'test@example.com')->first();
        if ($testUser) {
            Wallet::create([
                'user_id' => $testUser->id,
                'name' => 'Dompet Tunai',
                'type' => 'cash',
                'balance' => 5000000,
                'icon' => 'currency-dollar',
                'color' => '#10B981',
                'is_default' => true,
            ]);

            Wallet::create([
                'user_id' => $testUser->id,
                'name' => 'Rekening BCA',
                'type' => 'bank',
                'balance' => 15000000,
                'icon' => 'credit-card',
                'color' => '#3B82F6',
                'is_default' => false,
            ]);
        }
    }
}
