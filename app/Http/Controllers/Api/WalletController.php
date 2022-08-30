<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\WalletRequest;
use App\Models\Currency;
use App\Models\Wallet;
use Illuminate\Http\Response;

class WalletController extends Controller
{
    public function store(WalletRequest $request)
    {
        $currency = Currency::query()->findOrFail($request->input('currency_code'));
        $wallet = Wallet::query()
            ->where('currency_code', $currency->code)
            ->where('user_id', $request->user()->id)
            ->first();

        if ($wallet) {
            return response()->json([
                'message' => 'You can have only one wallet for ' . $currency->code . ' currency!',
            ], Response::HTTP_FORBIDDEN);
        }

        $wallet = Wallet::query()->create([
            'currency_code' => $currency->code,
            'user_id' => $request->user()->id
        ]);

        return response()->json([
            'data' => [
                'wallet' => $wallet
            ]
        ], Response::HTTP_CREATED);
    }
}
