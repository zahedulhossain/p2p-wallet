<?php

namespace App\Services\CurrencyConverter;

interface CurrencyConverter
{
    public function convert($amount, $from, $to);
}
