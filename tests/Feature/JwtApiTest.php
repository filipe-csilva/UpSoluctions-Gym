<?php

use App\Enums\UserRole;
use App\Models\User;

beforeEach(function (): void {
    config([
        'jwt.secret' => str_repeat('test-secret-', 8),
        'jwt.ttl' => 60,
    ]);
});

it('rejects API requests without a JWT', function () {
    $response = $this->getJson('/api/v1/students');

    $response->assertUnauthorized();
});

it('authenticates with JWT and accesses a protected endpoint', function () {
    $user = User::factory()->create([
        'role' => UserRole::ADMIN,
        'email' => 'api@example.com',
        'password' => 'password',
    ]);

    $login = $this->postJson('/api/v1/auth/login', [
        'email' => 'api@example.com',
        'password' => 'password',
    ]);

    $login->assertOk()->assertJsonStructure(['token', 'token_type', 'expires_in', 'user' => ['id', 'email', 'role']]);
    $token = $login->json('token');

    $this->withHeader('Authorization', 'Bearer '.$token)
        ->getJson('/api/v1/auth/me')
        ->assertOk()
        ->assertJsonPath('data.email', $user->email);

    $this->withHeader('Authorization', 'Bearer '.$token)
        ->getJson('/api/v1/students')
        ->assertOk()
        ->assertJsonStructure(['data', 'links', 'meta']);
});
