<?php

namespace Database\Factories;

use App\Models\Enums\TransactionAction;
use App\Models\Wallet;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'wallet_id' => Wallet::factory(),
            'amount' => $this->faker->randomFloat(2, 10, 1000),
            'action' => $this->faker->randomElement(Arr::pluck(TransactionAction::cases(), 'value')),
        ];
    }

    public function deposit()
    {
        return $this->state(function (array $attributes) {
           return [
               'action' => TransactionAction::Deposit
           ];
        });
    }

    public function withdraw()
    {
        return $this->state(function (array $attributes) {
           return [
               'action' => TransactionAction::Withdraw
           ];
        });
    }
}
