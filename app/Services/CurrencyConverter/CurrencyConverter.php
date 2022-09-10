<?php

namespace App\Services\CurrencyConverter;

use App\Data\ConvertedMoney;

interface CurrencyConverter
{
    public function convert(float $amount, string $from, string $to): ConvertedMoney;
}
