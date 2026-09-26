<?php

use App\Enums\UserRole;
use App\Models\Enrollment;
use App\Models\FinancialTransaction;
use App\Models\Plan;
use App\Models\StudentProfile;
use App\Models\Unit;
use App\Models\User;

it('shows only the authenticated student financial transactions', function () {
    $unit = Unit::create(['name' => 'Unidade Centro', 'code' => 'CENTRO', 'active' => true]);
    $studentUser = User::factory()->create(['role' => UserRole::STUDENT, 'unit_id' => $unit->id]);
    $student = StudentProfile::create(['user_id' => $studentUser->id, 'cpf' => '52998224725', 'birth_date' => '1990-01-01', 'phone' => '11999999999', 'number' => '10']);
    $otherStudent = User::factory()->create(['role' => UserRole::STUDENT, 'unit_id' => $unit->id]);
    $otherProfile = StudentProfile::create(['user_id' => $otherStudent->id, 'cpf' => '11144477735', 'birth_date' => '1990-01-01', 'phone' => '11999999998', 'number' => '11']);
    $plan = Plan::create(['name' => 'Plano Mensal', 'duration_months' => 1, 'price' => 89.90, 'active' => true]);
    $enrollment = Enrollment::create(['student_id' => $student->id, 'plan_id' => $plan->id, 'unit_id' => $unit->id, 'start_date' => today(), 'end_date' => today()->addMonth()->subDay(), 'price' => 89.90, 'status' => 'active', 'payment_day' => 10]);
    $otherEnrollment = Enrollment::create(['student_id' => $otherProfile->id, 'plan_id' => $plan->id, 'unit_id' => $unit->id, 'start_date' => today(), 'end_date' => today()->addMonth()->subDay(), 'price' => 89.90, 'status' => 'active', 'payment_day' => 10]);
    FinancialTransaction::create(['enrollment_id' => $enrollment->id, 'student_id' => $student->id, 'unit_id' => $unit->id, 'description' => 'Mensalidade do aluno', 'amount' => 89.90, 'due_date' => today()->addDays(5), 'status' => 'pending', 'transaction_type' => 'income']);
    FinancialTransaction::create(['enrollment_id' => $otherEnrollment->id, 'student_id' => $otherProfile->id, 'unit_id' => $unit->id, 'description' => 'Mensalidade de outro aluno', 'amount' => 89.90, 'due_date' => today()->addDays(5), 'status' => 'pending', 'transaction_type' => 'income']);

    $response = $this->actingAs($studentUser)->get(route('student-financial.index'));

    $response->assertOk()->assertSee('Mensalidade do aluno')->assertDontSee('Mensalidade de outro aluno');
});
