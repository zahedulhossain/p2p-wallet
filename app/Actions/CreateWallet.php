<?php

namespace App\Actions;

use App\Models\Wallet;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class CreateWallet
{
    public function __invoke(int $userId, string $currencyCode): \Illuminate\Database\Eloquent\Model
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
            'user_id' => $userId,
        ]);
    }
}
