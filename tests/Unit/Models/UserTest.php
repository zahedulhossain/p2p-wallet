<?php

use App\Models\User;
use App\Models\Wallet;

test('users have a wallet', function () {
    $user = User::factory()->has(Wallet::factory())->create();

    expect($user->wallets)->not()->toBeEmpty();
});
