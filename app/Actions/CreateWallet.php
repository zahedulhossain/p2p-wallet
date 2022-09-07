<?php

namespace App\Actions;

use App\Models\Wallet;
use App\Queries\ApproveMoneyTransferQuery;
use App\Services\CurrencyConverter\CurrencyConverter;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class CreateWallet
{
    public function execute($userId, $currencyCode, ): \Illuminate\Database\Eloquent\Model
    {
        $wallet = Wallet::query()
            ->where('currency_code', $currencyCode)
            ->where('user_id', $userId)
            ->first();

        if ($wallet) {
            throw new AccessDeniedHttpException('You can have only one wallet for ' . $currencyCode . ' currency!');
        }

        return Wallet::query()->create([
            'currency_code' => $currencyCode,
            'user_id' => $userId
        ]);
    }
}
