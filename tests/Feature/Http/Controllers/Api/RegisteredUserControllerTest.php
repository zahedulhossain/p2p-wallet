<?php

use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use function Pest\Faker\faker;

test('users can register an account', function () {
    $response = $this->postJson('/api/register', [
        'name' => faker()->name,
        'email' => faker()->email,
        'password' => $password = faker()->password(8) . faker()->toUpper(faker()->randomLetter) . faker()->toLower(faker()->randomLetter),
        'password_confirmation' => $password
    ]);

    $response->assertOk()
        ->assertJson(fn(AssertableJson $json) => $json->hasAll('data.user', 'data.type', 'data.token'));
});

test('users can not register multiple accounts with an email', function () {
    User::factory()->create([
        'email' => $email = faker()->email
    ]);

    $response = $this->postJson('/api/register', [
        'name' => faker()->name,
        'email' => $email,
        'password' => $password = faker()->password,
        'password_confirmation' => $password
    ]);

    $response->assertUnprocessable()
        ->assertJson(fn(AssertableJson $json) => $json->hasAll('message', 'errors.email'));
});
