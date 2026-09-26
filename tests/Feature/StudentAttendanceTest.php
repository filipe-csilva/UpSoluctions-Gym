<?php

use App\Enums\UserRole;
use App\Models\Attendance;
use App\Models\StudentProfile;
use App\Models\Unit;
use App\Models\User;

function createStudentForAttendance(Unit $homeUnit): User
{
    $student = User::factory()->create([
        'role' => UserRole::STUDENT,
        'unit_id' => $homeUnit->id,
    ]);

    StudentProfile::create([
        'user_id' => $student->id,
        'cpf' => '52998224725',
        'birth_date' => '1990-01-01',
        'phone' => '11999999999',
        'number' => '10',
    ]);

    return $student;
}

it('allows a student to register training at any active unit', function () {
    $homeUnit = Unit::create(['name' => 'Unidade Centro', 'code' => 'CENTRO', 'active' => true]);
    $trainingUnit = Unit::create(['name' => 'Unidade Norte', 'code' => 'NORTE', 'active' => true]);
    $student = createStudentForAttendance($homeUnit);

    $response = $this->actingAs($student)->post(route('student-attendance.store'), [
        'unit_id' => $trainingUnit->id,
    ]);

    $response->assertRedirect(route('panel', absolute: false));
    expect(Attendance::first()->unit_id)->toBe($trainingUnit->id)
        ->and(Attendance::first()->student_id)->toBe($student->studentProfile->id);
});

it('does not allow a second open attendance on the same day', function () {
    $homeUnit = Unit::create(['name' => 'Unidade Centro', 'code' => 'CENTRO', 'active' => true]);
    $trainingUnit = Unit::create(['name' => 'Unidade Norte', 'code' => 'NORTE', 'active' => true]);
    $student = createStudentForAttendance($homeUnit);

    Attendance::create([
        'student_id' => $student->studentProfile->id,
        'unit_id' => $homeUnit->id,
        'registered_by' => $student->id,
        'date' => today(),
        'entry_time' => '08:00',
        'type' => 'regular',
    ]);

    $response = $this->actingAs($student)->post(route('student-attendance.store'), [
        'unit_id' => $trainingUnit->id,
    ]);

    $response->assertRedirect(route('panel', absolute: false));
    $response->assertSessionHas('warning');
    expect(Attendance::count())->toBe(1);
});

test('example', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});
