<?php

use App\Enums\UserRole;
use App\Models\EmployeeProfile;
use App\Models\Unit;
use App\Models\User;

it('prevents a manager from editing an admin employee', function () {
    $unit = Unit::create(['name' => 'Unidade Centro', 'code' => 'CENTRO', 'active' => true]);
    $manager = User::factory()->create(['role' => UserRole::MANAGER, 'unit_id' => $unit->id]);
    $manager->managedUnits()->attach($unit);
    $admin = User::factory()->create(['role' => UserRole::ADMIN, 'unit_id' => $unit->id]);
    $employee = EmployeeProfile::create(['user_id' => $admin->id]);

    $response = $this->actingAs($manager)->get(route('employees.edit', $employee));

    $response->assertForbidden();
    expect($admin->fresh()->role)->toBe(UserRole::ADMIN);
});
