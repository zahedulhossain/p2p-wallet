<?php

namespace App\Queries;

use App\Models\Enums\PaymentStatus;
use App\Models\Payment;
use App\Models\Transaction;
use App\Values\ConvertedMoney;
use Illuminate\Support\Facades\DB;

class ApproveMoneyTransferQuery
{
    public function execute($senderWalletId, $receiverWalletId, $amount, ConvertedMoney $convertedMoney, $note = null): \Illuminate\Database\Eloquent\Model
    {
        DB::beginTransaction();
        try {
            $payment = Payment::query()->create([
                'status' => PaymentStatus::Approved,
                'from_wallet_id' => $senderWalletId,
                'to_wallet_id' => $receiverWalletId,
                'amount' => $amount,
                'conversion_rate' => $convertedMoney->getConversionRate(),
                'converted_amount' => $convertedMoney->getAmount(),
                'note' => $note,
            ]);

            Transaction::query()->create([
                'wallet_id' => $senderWalletId,
                'action' => 'withdraw',
                'amount' => $amount,
            ]);

            Transaction::query()->create([
                'wallet_id' => $receiverWalletId,
                'action' => 'deposit',
                'amount' => $convertedMoney->getAmount() ?? $amount,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }

        return $payment;
    }
}
