<?php

namespace App\Models\Enums;

enum TransactionAction: string
{
    case Deposit = 'deposit';
    case Withdraw = 'withdraw';
}
