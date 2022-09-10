<?php

namespace App\Http\Controllers\Api;

use App\Actions\CreateWallet;
use App\Http\Controllers\Controller;
use App\Http\Requests\WalletRequest;
use App\Http\Resources\WalletResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class WalletController extends Controller
{
    public function store(WalletRequest $request, CreateWallet $createWallet): JsonResponse
    {
        $wallet = $createWallet($request->user()->id, $request->validated('currency_code'));

        return response()->json([
            'data' => [
                'wallet' => new WalletResource($wallet),
            ],
        ], Response::HTTP_CREATED);
    }
}
