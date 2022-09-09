<?php

namespace App\Data;

class ConvertedMoney
{
    public function __construct(
        public readonly ?float $amount = null,
        public readonly ?float $conversionRate = null
    ) {
    }
}
