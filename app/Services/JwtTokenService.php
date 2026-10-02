<?php

namespace App\Services;

use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

class JwtTokenService
{
    public function issue(User $user): string
    {
        $issuedAt = time();

        return JWT::encode([
            'iss' => config('jwt.issuer'),
            'sub' => $user->getKey(),
            'iat' => $issuedAt,
            'exp' => $issuedAt + (config('jwt.ttl') * 60),
            'jti' => (string) Str::uuid(),
            'role' => $user->role?->value,
        ], $this->secret(), 'HS256');
    }

    public function userFromToken(string $token): ?User
    {
        try {
            $payload = JWT::decode($token, new Key($this->secret(), 'HS256'));
            $userId = (int) ($payload->sub ?? 0);
        } catch (Throwable) {
            return null;
        }

        return User::query()->whereKey($userId)->where('active', true)->first();
    }

    private function secret(): string
    {
        $secret = (string) config('jwt.secret');

        if (mb_strlen($secret) < 32) {
            throw new RuntimeException('JWT_SECRET deve possuir pelo menos 32 caracteres.');
        }

        return $secret;
    }
}
