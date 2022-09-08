<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthenticationController extends Controller
{
    public function store(LoginRequest $request): \Illuminate\Http\JsonResponse
    {
        $user = User::query()->where('email', $request->input('username'))->first();

        if (!$user || !Hash::check($request->input('password'), $user->password)) {
            return response()->json(
                ['message' => 'The given credentials are invalid'],
                Response::HTTP_UNAUTHORIZED
            );
        }

        return response()->json([
            'data' => [
                'type' => 'Bearer',
                'token' => $user->createToken($user)->plainTextToken,
            ]
        ]);
    }

    public function destroy(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->user()->tokens()->delete();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
