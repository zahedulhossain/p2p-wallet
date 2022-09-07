<?php

namespace App\Http\Controllers\Api;

use App\Actions\CreateNewUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisteredUserController extends Controller
{
    public function __invoke(RegisterRequest $request): \Illuminate\Http\JsonResponse
    {
        $user = User::query()->create([
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'password' => Hash::make($request->validated('password')),
        ]);

        event(new Registered($user));

        return response()->json([
            'data' => [
                'user' => $user,
                'type' => 'Bearer',
                'token' => $user->createToken($user)->plainTextToken,
            ]
        ]);
    }
}
