<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Currency;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $usd = Currency::factory()->usd()->create();
        $bdt = Currency::factory()->bdt()->create();

        $user = User::factory()->create([
            'email' => 'user@example.com',
        ]);

        $user2 = User::factory()->create([
            'email' => 'user2@example.com',
        ]);

        $wallet = Wallet::factory()->create([
            'currency_code' => $usd->code,
            'user_id' => $user->id,
        ]);

        Wallet::factory()->create([
            'currency_code' => $bdt->code,
            'user_id' => $user2->id,
        ]);

        Transaction::factory()->deposit()->create([
            'wallet_id' => $wallet->id
        ]);
    }
}
