<?php

use App\Http\Controllers\Api\AuthenticationController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\RegisteredUserController;
use App\Http\Controllers\Api\WalletController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('ping', function () {
    return response()->json([
        'success' => true,
    ]);
})->name('ping');

Route::post('login', [AuthenticationController::class, 'store']);
Route::post('register', RegisteredUserController::class);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthenticationController::class, 'destroy']);

    Route::post('wallets', [WalletController::class, 'store']);

    Route::post('payments', [PaymentController::class, 'store']);
});
