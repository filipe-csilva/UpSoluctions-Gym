<?php

use App\Enums\UserRole;
use App\Models\StudentProfile;
use App\Models\Unit;
use App\Models\User;
use App\Models\WorkoutPlan;

function createWorkoutStudent(Unit $unit, string $email): StudentProfile
{
    $user = User::factory()->create([
        'email' => $email,
        'role' => UserRole::STUDENT,
        'unit_id' => $unit->id,
    ]);

    return StudentProfile::create([
        'user_id' => $user->id,
        'cpf' => '52998224725',
        'birth_date' => '1990-01-01',
        'phone' => '11999999999',
        'number' => '10',
    ]);
}

function workoutPlanPayload(int $studentId, int $teacherId): array
{
    return [
        'student_id' => $studentId,
        'teacher_id' => $teacherId,
        'name' => 'Treino de adaptação',
        'start_date' => now()->toDateString(),
        'status' => 'active',
    ];
}

it('allows a teacher to create a workout plan for a student in the same unit', function () {
    $unit = Unit::create(['name' => 'Unidade Centro', 'code' => 'CENTRO', 'active' => true]);
    $teacher = User::factory()->create(['role' => UserRole::TEACHER, 'unit_id' => $unit->id]);
    $otherTeacher = User::factory()->create(['role' => UserRole::TEACHER, 'unit_id' => $unit->id]);
    $student = createWorkoutStudent($unit, 'aluno-treino@example.com');

    $response = $this->actingAs($teacher)->post(route('workout-plans.store'), workoutPlanPayload($student->id, $otherTeacher->id));

    $response->assertRedirect();
    expect(WorkoutPlan::first()->teacher_id)->toBe($teacher->id);
});

it('prevents a teacher from creating a workout plan for another unit', function () {
    $teacherUnit = Unit::create(['name' => 'Unidade Centro', 'code' => 'CENTRO', 'active' => true]);
    $studentUnit = Unit::create(['name' => 'Unidade Norte', 'code' => 'NORTE', 'active' => true]);
    $teacher = User::factory()->create(['role' => UserRole::TEACHER, 'unit_id' => $teacherUnit->id]);
    $student = createWorkoutStudent($studentUnit, 'aluno-outra-unidade@example.com');

    $response = $this->actingAs($teacher)->post(route('workout-plans.store'), workoutPlanPayload($student->id, $teacher->id));

    $response->assertForbidden();
    expect(WorkoutPlan::count())->toBe(0);
});
