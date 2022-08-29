<?php

use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use function Pest\Faker\faker;

test('users can register an account', function () {
    $response = $this->postJson('/api/register', [
        'name' => faker()->name,
        'email' => faker()->email,
        'password' => $password = faker()->password,
        'password_confirmation' => $password
    ]);

    $response->assertJson(fn(AssertableJson $json) => $json->hasAll('data.user', 'data.type', 'data.token'));
});

test('users can authenticate', function () {
    $user = User::factory()->create();

    $response = $this->postJson('/api/login', [
        'username' => $user->email,
        'password' => 'password',
    ]);

    $response->assertJson(fn(AssertableJson $json) => $json->hasAll('data.type', 'data.token'));
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    $response = $this->postJson('/api/login', [
        'username' => $user->email,
        'password' => 'wrong-password',
    ]);

    $response->assertJson([
        'message' => 'The given credentials are invalid'
    ]);
});
