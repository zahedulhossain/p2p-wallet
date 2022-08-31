<?php

use App\Models\Currency;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Testing\Fluent\AssertableJson;

test('users can create wallet', function () {
    $user = User::factory()->create();
    $currency = Currency::factory()->create();

    $response = $this->actingAs($user)->postJson('/api/wallets', [
        'currency_code' => $currency->code,
    ]);

    $response->assertCreated()
        ->assertJson(fn(AssertableJson $json) => $json->has('data.wallet'));
});

test('a user can have only one wallet for a currency', function () {
    $user = User::factory()->create();
    $currency = Currency::factory()->create();
    Wallet::factory()->create([
        'currency_code' => $currency->code,
        'user_id' => $user->id,
    ]);

    $response = $this->actingAs($user)->postJson('/api/wallets', [
        'currency_code' => $currency->code,
    ]);

    $response->assertForbidden()
        ->assertJson(fn(AssertableJson $json) =>
            $json->has('message')->missing('data')
        );
});
