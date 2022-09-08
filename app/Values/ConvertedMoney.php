<?php

namespace App\Values;

class ConvertedMoney
{
    private function __construct(private ?float $amount, private ?float $conversionRate)
    {
    }

    public static function make(?float $amount = null, ?float $conversionRate = null): ConvertedMoney
    {
        return new self($amount, $conversionRate);
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function getConversionRate(): ?float
    {
        return $this->conversionRate;
    }
}
