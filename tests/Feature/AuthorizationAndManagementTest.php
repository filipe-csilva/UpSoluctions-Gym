<?php

use App\Enums\UserRole;
use App\Models\GeneralSetting;
use App\Models\Unit;
use App\Models\User;

it('restricts general settings and user management to administrators', function () {
    $manager = User::factory()->create(['role' => UserRole::MANAGER]);
    $admin = User::factory()->create(['role' => UserRole::ADMIN]);

    $this->actingAs($manager)->get(route('settings.index'))->assertForbidden();
    $this->actingAs($manager)->get(route('users.index'))->assertForbidden();
    $this->actingAs($admin)->get(route('settings.index'))->assertOk();
});

it('allows an administrator to create and update users', function () {
    $admin = User::factory()->create(['role' => UserRole::ADMIN]);
    $unit = Unit::create(['name' => 'Unidade Centro', 'code' => 'CENTRO', 'active' => true]);

    $response = $this->actingAs($admin)->post(route('users.store'), [
        'name' => 'Usuário de teste',
        'email' => 'usuario.teste@example.com',
        'role' => 'teacher',
        'unit_id' => $unit->id,
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'active' => '1',
    ]);

    $user = User::where('email', 'usuario.teste@example.com')->firstOrFail();
    $response->assertRedirect(route('users.index', absolute: false));
    expect($user->role)->toBe(UserRole::TEACHER);

    $this->actingAs($admin)->put(route('settings.update'), [
        'company_name' => 'GymControl Centro',
        'company_document' => '',
        'company_phone' => '',
        'company_email' => '',
        'company_address' => '',
        'timezone' => 'America/Fortaleza',
    ])->assertRedirect();

    expect(GeneralSetting::valueFor('company_name'))->toBe('GymControl Centro');
});
test('example', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});
