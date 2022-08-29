<?php

use App\Http\Controllers\Api\AuthenticationController;
use App\Http\Controllers\Api\RegisteredUserController;
use Illuminate\Http\Request;
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
        'success' => 'self-hosted',
    ]);
})->name('ping');

Route::post('login', [AuthenticationController::class, 'store']);
Route::post('register', RegisteredUserController::class);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthenticationController::class, 'destroy']);
});
