<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\WalletRequest;
use App\Http\Resources\WalletResource;
use App\Models\Wallet;
use Illuminate\Http\Response;

class WalletController extends Controller
{
    public function store(WalletRequest $request)
    {
        $wallet = Wallet::query()
            ->where('currency_code', $request->input('currency_code'))
            ->where('user_id', $request->user()->id)
            ->first();

        if ($wallet) {
            return response()->json([
                'message' => 'You can have only one wallet for ' . $request->input('currency_code') . ' currency!',
            ], Response::HTTP_FORBIDDEN);
        }

        $wallet = Wallet::query()->create([
            'currency_code' => $request->input('currency_code'),
            'user_id' => $request->user()->id
        ]);

        return response()->json([
            'data' => [
                'wallet' => new WalletResource($wallet)
            ]
        ], Response::HTTP_CREATED);
    }
}
