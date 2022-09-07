<?php

namespace App\Actions;

use App\Models\Wallet;
use App\Queries\ApproveMoneyTransferQuery;
use App\Services\CurrencyConverter\CurrencyConverter;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class MakePayment
{
    private ApproveMoneyTransferQuery $approveTransferQuery;
    private CurrencyConverter $converter;

    public function __construct(
        CurrencyConverter $converter, ApproveMoneyTransferQuery $approveTransferQuery
    )
    {
        $this->converter = $converter;
        $this->approveTransferQuery = $approveTransferQuery;
    }

    public function execute($sender, $fromWalletId, $toWalletId, $amount, $note = null): \Illuminate\Database\Eloquent\Model
    {
        $senderWallet = Wallet::query()->findOrFail($fromWalletId);
        $receiverWallet = Wallet::query()->findOrFail($toWalletId);

        $receiver = $receiverWallet->owner;

        if ($sender->isNot($senderWallet->owner)) {
            throw new AccessDeniedHttpException('Oops! This wallet does not belongs to you.');
        }

        if ($sender->is($receiver)) {
            throw new AccessDeniedHttpException('Oops! You selected yourself as the receiver.');
        }

        if ($senderWallet->balance < $amount) {
            throw new AccessDeniedHttpException('Oops! Your account balance is insufficient.');
        }

        if ($senderWallet->currency_code !== $receiverWallet->currency_code) {
            $convertedAmountArr = $this->converter->convert($amount, $senderWallet->currency_code, $receiverWallet->currency_code);
        }

        return $this->approveTransferQuery->execute(
            $fromWalletId,
            $toWalletId,
            $amount,
            $note,
            $convertedAmountArr ?? null
        );
    }
}
