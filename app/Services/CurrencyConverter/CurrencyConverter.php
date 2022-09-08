<?php

namespace App\Services\CurrencyConverter;

use App\Values\ConvertedMoney;

interface CurrencyConverter
{
    public function convert($amount, $from, $to): ConvertedMoney;
}
