<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\UserResource;
use App\Models\User;
use App\Services\JwtTokenService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request, JwtTokenService $tokens): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);
        $user = User::query()->where('email', $credentials['email'])->first();

        if ($user === null || ! $user->active || ! Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages(['email' => 'Credenciais inválidas.']);
        }

        return response()->json([
            'token' => $tokens->issue($user),
            'token_type' => 'Bearer',
            'expires_in' => config('jwt.ttl') * 60,
            'user' => new UserResource($user->load('unit')),
        ]);
    }

    public function me(Request $request): UserResource
    {
        return new UserResource($request->user()->load('unit'));
    }

    public function logout(): JsonResponse
    {
        return response()->json(['message' => 'Token invalidado no cliente.']);
    }
}
