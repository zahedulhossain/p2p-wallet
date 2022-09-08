<?php

namespace App\Http\Controllers\Api;

use App\Actions\MakePayment;
use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentRequest;
use App\Http\Resources\PaymentResource;
use Illuminate\Http\Response;

class PaymentController extends Controller
{
    public function index()
    {
        //
    }

    public function store(PaymentRequest $request, MakePayment $makePayment): \Illuminate\Http\JsonResponse
    {
        $payment = $makePayment->execute(
            $request->user(),
            $request->validated('from_wallet_id'),
            $request->validated('to_wallet_id'),
            $request->validated('amount'),
            $request->validated('note'),
        );

        return response()->json([
            'data' => [
                'payment' => new PaymentResource($payment),
            ],
        ], Response::HTTP_CREATED);
    }
}
