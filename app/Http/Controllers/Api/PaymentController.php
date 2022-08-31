<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentRequest;
use App\Http\Resources\PaymentResource;
use App\Models\Wallet;
use App\Queries\ApproveMoneyTransferQuery;
use App\Services\CurrencyConverter\CurrencyConverter;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class PaymentController extends Controller
{
    public function index()
    {
        //
    }

    public function store(PaymentRequest $request, CurrencyConverter $converter, ApproveMoneyTransferQuery $approveTransferQuery)
    {
        $senderWallet = Wallet::query()->findOrFail($request->validated('from_wallet_id'));
        $receiverWallet = Wallet::query()->findOrFail($request->validated('to_wallet_id'));

        $sender = $request->user();
        $receiver = $receiverWallet->owner;

        if ($sender->isNot($senderWallet->owner)) {
            throw new AccessDeniedHttpException('Oops! This wallet does not belongs to you.');
        }

        if ($sender->is($receiver)) {
            throw new AccessDeniedHttpException('Oops! You selected yourself as the receiver.');
        }

        if ($senderWallet->balance < $request->input('amount')) {
            throw new AccessDeniedHttpException('Oops! Your account balance is insufficient.');
        }

        if ($senderWallet->currency_code !== $receiverWallet->currency_code) {
            $convertedAmountArr = $converter->convert($request->validated('amount'), $senderWallet->currency_code, $receiverWallet->currency_code);
        }

        $payment = $approveTransferQuery->execute(
            $request->validated('from_wallet_id'),
            $request->validated('to_wallet_id'),
            $request->validated('amount'),
            $request->validated('note'),
            $convertedAmountArr ?? null
        );

        return response()->json([
            'data' => [
                'payment' => new PaymentResource($payment)
            ]
        ], Response::HTTP_CREATED);
    }
}
