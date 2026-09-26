<?php

use App\Enums\UserRole;
use App\Models\Unit;
use App\Models\User;

it('allows a teacher to open their students page without a teacher profile record', function () {
    $unit = Unit::create(['name' => 'Unidade Centro', 'code' => 'CENTRO', 'active' => true]);
    $teacher = User::factory()->create(['role' => UserRole::TEACHER, 'unit_id' => $unit->id]);

    $response = $this->actingAs($teacher)->get(route('teachers.my-students'));

    $response->assertOk()->assertViewIs('teachers.my-students');
});

it('opens the panel when a teacher accesses the dashboard route', function () {
    $unit = Unit::create(['name' => 'Unidade Centro', 'code' => 'CENTRO', 'active' => true]);
    $teacher = User::factory()->create(['role' => UserRole::TEACHER, 'unit_id' => $unit->id]);

    $response = $this->actingAs($teacher)->get(route('dashboard'));

    $response->assertOk()->assertViewIs('panel');
});
