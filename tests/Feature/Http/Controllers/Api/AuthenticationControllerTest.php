<?php

use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;

test('users can get token to login', function () {
    $user = User::factory()->create();

    $response = $this->postJson('/api/login', [
        'username' => $user->email,
        'password' => 'password',
    ]);

    $response->assertOk()
        ->assertJson(fn (AssertableJson $json) => $json->hasAll('data.type', 'data.token'));
});

test('users can revoke their token', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->postJson('/api/logout', [
        'username' => $user->email,
        'password' => 'password',
    ]);

    $response->assertNoContent();
});

test('users can not login with invalid password', function () {
    $user = User::factory()->create();

    $response = $this->postJson('/api/login', [
        'username' => $user->email,
        'password' => 'wrong-password',
    ]);

    $response->assertUnauthorized()
        ->assertJson([
            'message' => 'The given credentials are invalid',
        ]);
});
