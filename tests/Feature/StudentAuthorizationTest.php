<?php

use App\Enums\UserRole;
use App\Models\StudentProfile;
use App\Models\Unit;
use App\Models\User;

function studentUpdatePayload(int $unitId, string $role): array
{
    return [
        'name' => 'Aluno Atualizado',
        'email' => 'aluno-atualizado@example.com',
        'unit_id' => $unitId,
        'role' => $role,
        'active' => '1',
        'cpf' => '52998224725',
        'birth_date' => '1990-01-01',
        'phone' => '11999999999',
        'number' => '10',
    ];
}

function createStudentForAuthorization(Unit $unit): StudentProfile
{
    $student = User::factory()->create([
        'role' => UserRole::STUDENT,
        'unit_id' => $unit->id,
    ]);

    return StudentProfile::create([
        'user_id' => $student->id,
        'cpf' => '52998224725',
        'birth_date' => '1990-01-01',
        'phone' => '11999999999',
        'number' => '10',
    ]);
}

it('allows an admin to change a student to any role', function () {
    $unit = Unit::create(['name' => 'Unidade Centro', 'code' => 'CENTRO', 'active' => true]);
    $student = createStudentForAuthorization($unit);
    $admin = User::factory()->create(['role' => UserRole::ADMIN]);

    $response = $this->actingAs($admin)->put(route('students.update', $student), studentUpdatePayload($unit->id, UserRole::FINANCIAL->value));

    $response->assertRedirect(route('students.show', $student, absolute: false));
    expect($student->user->fresh()->role)->toBe(UserRole::FINANCIAL);
});

it('allows a manager to change a student to teacher', function () {
    $unit = Unit::create(['name' => 'Unidade Centro', 'code' => 'CENTRO', 'active' => true]);
    $student = createStudentForAuthorization($unit);
    $manager = User::factory()->create(['role' => UserRole::MANAGER, 'unit_id' => $unit->id]);

    $response = $this->actingAs($manager)->put(route('students.update', $student), studentUpdatePayload($unit->id, UserRole::TEACHER->value));

    $response->assertRedirect(route('students.show', $student, absolute: false));
    expect($student->user->fresh()->role)->toBe(UserRole::TEACHER);
});

it('prevents a manager from assigning an administrative role', function () {
    $unit = Unit::create(['name' => 'Unidade Centro', 'code' => 'CENTRO', 'active' => true]);
    $student = createStudentForAuthorization($unit);
    $manager = User::factory()->create(['role' => UserRole::MANAGER, 'unit_id' => $unit->id]);

    $response = $this->actingAs($manager)->put(route('students.update', $student), studentUpdatePayload($unit->id, UserRole::ADMIN->value));

    $response->assertSessionHasErrors('role');
    expect($student->user->fresh()->role)->toBe(UserRole::STUDENT);
});
