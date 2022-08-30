<?php

use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;

test('users can authenticate', function () {
    $user = User::factory()->create();

    $response = $this->postJson('/api/login', [
        'username' => $user->email,
        'password' => 'password',
    ]);

    $response->assertOk()
        ->assertJson(fn(AssertableJson $json) => $json->hasAll('data.type', 'data.token'));
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    $response = $this->postJson('/api/login', [
        'username' => $user->email,
        'password' => 'wrong-password',
    ]);

    $response->assertUnauthorized()
        ->assertJson([
            'message' => 'The given credentials are invalid'
        ]);
});
