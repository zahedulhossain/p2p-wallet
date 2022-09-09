<?php

namespace App\Queries;

use App\Data\ConvertedMoney;
use App\Models\Enums\PaymentStatus;
use App\Models\Payment;
use App\Models\Transaction;
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
                'conversion_rate' => $convertedMoney->conversionRate,
                'converted_amount' => $convertedMoney->amount,
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
                'amount' => $convertedMoney->amount ?? $amount,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }

        return $payment;
    }
}
