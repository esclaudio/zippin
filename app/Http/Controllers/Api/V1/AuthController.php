<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\Auth\LoginRequest;

class AuthController
{
    public function login(LoginRequest $request)
    {
        $token = auth('api')->attempt($request->only(['email', 'password']));

        if ($token) {
            return $this->respondWithToken($token);
        }

        return response()->json([
            'error' => 'Unauthorized'
        ], 401);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    private function respondWithToken(string $token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
        ]);
    }
}
