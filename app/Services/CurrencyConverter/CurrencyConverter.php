<?php

namespace App\Services\CurrencyConverter;

use App\Data\ConvertedMoney;

interface CurrencyConverter
{
    public function convert($amount, $from, $to): ConvertedMoney;
}
