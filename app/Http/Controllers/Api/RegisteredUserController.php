<?php

namespace App\Http\Controllers\Api;

use App\Actions\CreateNewUser;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;

class RegisteredUserController extends Controller
{
    public function __invoke(Request $request, CreateNewUser $creator): \Illuminate\Http\JsonResponse
    {
        event(new Registered($user = $creator->create($request->all())));

        return response()->json([
            'data' => [
                'user' => $user,
                'type' => 'Bearer',
                'token' => $user->createToken($user)->plainTextToken,
            ]
        ]);
    }
}
