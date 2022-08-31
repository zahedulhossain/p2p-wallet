<?php

use App\Models\Currency;
use App\Models\Enums\PaymentStatus;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\Fluent\AssertableJson;
use function Pest\Faker\faker;

test('users can transfer money to other users wallet in same currency', function () {
    $user = User::factory()->create();
    $currency = Currency::factory()->usd()->create();
    $wallet = Wallet::factory()->create([
        'currency_code' => $currency->code,
        'user_id' => $user->id,
    ]);
    $user2 = User::factory()->create();
    $wallet2 = Wallet::factory()->create([
        'currency_code' => $currency->code,
        'user_id' => $user2->id,
    ]);
    Transaction::factory()->deposit()->create([
        'wallet_id' => $wallet->id,
        'amount' => $amount = faker()->randomFloat(2, 10, 100)
    ]);

    $response = $this->actingAs($user)->postJson('/api/payments', [
        'from_wallet_id' => $wallet->id,
        'to_wallet_id' => $wallet2->id,
        'amount' => $amount,
        'note' => $note = faker()->sentence,
    ]);

    $response->assertCreated()->assertJson(fn(AssertableJson $json) => $json->has('data.payment'));

    $this->assertDatabaseHas('payments', [
        'status' => PaymentStatus::Approved,
        'from_wallet_id' => $wallet->id,
        'to_wallet_id' => $wallet2->id,
        'amount' => $amount,
        'conversion_rate' => null,
        'converted_amount' => null,
        'note' => $note,
    ]);
});

test('users can transfer money to other users having different currency based wallet', function () {
    Http::fake([
        'openexchangerates.org/*' => Http::response([
            'disclaimer' => "https://openexchangerates.org/terms/",
            'license' => "https://openexchangerates.org/license/",
            'timestamp' => 1449877801,
            'base' => 'USD',
            'rates' => ['EUR' => $rate = '0.96'],
        ], 200, []),
    ]);
    $user = User::factory()->create();
    $usd = Currency::factory()->usd()->create();
    $euro = Currency::factory()->eur()->create();
    $wallet = Wallet::factory()->create([
        'currency_code' => $usd->code,
        'user_id' => $user->id,
    ]);
    $user2 = User::factory()->create();
    $wallet2 = Wallet::factory()->create([
        'currency_code' => $euro->code,
        'user_id' => $user2->id,
    ]);
    Transaction::factory()->deposit()->create([
        'wallet_id' => $wallet->id,
        'amount' => $amount = faker()->randomFloat(2, 10, 100)
    ]);

    $response = $this->actingAs($user)->postJson('/api/payments', [
        'from_wallet_id' => $wallet->id,
        'to_wallet_id' => $wallet2->id,
        'amount' => $amount,
        'note' => $note = faker()->sentence,
    ]);

    $response->assertCreated()->assertJson(fn(AssertableJson $json) => $json->has('data.payment'));

    $this->assertDatabaseHas('payments', [
        'status' => PaymentStatus::Approved,
        'from_wallet_id' => $wallet->id,
        'to_wallet_id' => $wallet2->id,
        'amount' => $amount,
        'conversion_rate' => $rate,
        'converted_amount' => $amount * $rate,
        'note' => $note,
    ]);
});

test('users cannot transfer money without sufficient balance', function () {
    $user = User::factory()->create();
    $usd = Currency::factory()->usd()->create();
    $euro = Currency::factory()->eur()->create();
    $wallet = Wallet::factory()->create([
        'currency_code' => $usd->code,
        'user_id' => $user->id,
    ]);
    $user2 = User::factory()->create();
    $wallet2 = Wallet::factory()->create([
        'currency_code' => $euro->code,
        'user_id' => $user2->id,
    ]);

    $response = $this->actingAs($user)->postJson('/api/payments', [
        'from_wallet_id' => $wallet->id,
        'to_wallet_id' => $wallet2->id,
        'amount' => faker()->randomFloat(2, 10, 100),
        'note' => faker()->sentence,
    ]);

    $response->assertForbidden()
        ->assertJson(fn(AssertableJson $json) =>
            $json->where('message', 'Oops! Your account balance is insufficient.')
                ->etc()
        );
});

test('users cannot transfer money to their own wallet', function () {
    $user = User::factory()->create();
    $currency = Currency::factory()->usd()->create();
    $wallet = Wallet::factory()->create([
        'currency_code' => $currency->code,
        'user_id' => $user->id,
    ]);

    $response = $this->actingAs($user)->postJson('/api/payments', [
        'from_wallet_id' => $wallet->id,
        'to_wallet_id' => $wallet->id,
        'amount' => faker()->randomFloat(2, 10, 100),
        'note' => faker()->sentence,
    ]);

    $response->assertForbidden()
        ->assertJson(fn(AssertableJson $json) =>
        $json->where('message', 'Oops! You selected yourself as the receiver.')
            ->etc()
        );
});
