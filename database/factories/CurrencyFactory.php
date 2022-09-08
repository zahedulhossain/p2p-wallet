<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Currency>
 */
class CurrencyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'code' => $this->faker->unique()->currencyCode(),
            'name' => $this->faker->word,
        ];
    }

    public function usd()
    {
        return $this->state(function (array $attributes) {
            return [
                'code' => 'USD',
                'name' => 'United States dollar',
                'symbol' => '$',
            ];
        });
    }

    public function eur()
    {
        return $this->state(function (array $attributes) {
            return [
                'code' => 'EUR',
                'name' => 'Euro',
                'symbol' => '€',
            ];
        });
    }

    public function bdt()
    {
        return $this->state(function (array $attributes) {
            return [
                'code' => 'BDT',
                'name' => 'Bangladeshi taka',
                'symbol' => '৳',
            ];
        });
    }
}
