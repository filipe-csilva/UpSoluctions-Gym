<?php

use App\Enums\UserRole;
use App\Jobs\SyncStudentStatuses;
use App\Models\Enrollment;
use App\Models\FinancialTransaction;
use App\Models\Plan;
use App\Models\StudentProfile;
use App\Models\Unit;
use App\Models\User;
use App\Services\StudentAccessService;

function studentWithActiveEnrollment(string $email, string $cpf): array
{
    $unit = Unit::create(['name' => 'Unidade Centro', 'code' => strtoupper(substr($cpf, -6)), 'active' => true]);
    $user = User::factory()->create(['email' => $email, 'role' => UserRole::STUDENT, 'unit_id' => $unit->id, 'active' => true]);
    $student = StudentProfile::create(['user_id' => $user->id, 'cpf' => $cpf, 'birth_date' => '1990-01-01', 'phone' => '11999999999', 'number' => '10']);
    $plan = Plan::create(['name' => 'Plano '.$cpf, 'duration_months' => 1, 'price' => 89.90, 'active' => true]);
    $enrollment = Enrollment::create(['student_id' => $student->id, 'plan_id' => $plan->id, 'unit_id' => $unit->id, 'start_date' => today()->subMonth(), 'end_date' => today()->addMonth(), 'price' => 89.90, 'status' => 'active', 'payment_day' => 10]);

    return [$user, $student, $enrollment, $unit];
}

it('deactivates a student with an overdue monthly payment', function () {
    [$user, $student, $enrollment, $unit] = studentWithActiveEnrollment('atrasado@example.com', '11144477735');
    FinancialTransaction::create(['enrollment_id' => $enrollment->id, 'student_id' => $student->id, 'unit_id' => $unit->id, 'description' => 'Mensalidade vencida', 'amount' => 89.90, 'due_date' => today()->subDay(), 'status' => 'pending', 'transaction_type' => 'income']);

    SyncStudentStatuses::dispatchSync();

    expect($user->fresh()->active)->toBeFalse();
});

it('deactivates a student whose last payment is older than thirty days', function () {
    [$user, $student, $enrollment, $unit] = studentWithActiveEnrollment('antigo@example.com', '22233344405');
    FinancialTransaction::create(['enrollment_id' => $enrollment->id, 'student_id' => $student->id, 'unit_id' => $unit->id, 'description' => 'Última mensalidade paga', 'amount' => 89.90, 'due_date' => today()->subDays(35), 'paid_at' => now()->subDays(31), 'status' => 'paid', 'transaction_type' => 'income']);

    expect(app(StudentAccessService::class)->deactivateOverdueStudents())->toBe(1)
        ->and($user->fresh()->active)->toBeFalse();
});

it('deactivates a student without an active enrollment', function () {
    $unit = Unit::create(['name' => 'Unidade Centro', 'code' => 'SEMPLANO', 'active' => true]);
    $user = User::factory()->create(['email' => 'sem-plano@example.com', 'role' => UserRole::STUDENT, 'unit_id' => $unit->id, 'active' => true]);
    StudentProfile::create(['user_id' => $user->id, 'cpf' => '33344455506', 'birth_date' => '1990-01-01', 'phone' => '11999999999', 'number' => '10']);

    expect(app(StudentAccessService::class)->syncStudentStatuses())->toBe(1)
        ->and($user->fresh()->active)->toBeFalse();
});

it('reactivates a student after an active enrollment is linked', function () {
    [$user] = studentWithActiveEnrollment('reativado@example.com', '44455566607');
    $user->update(['active' => false]);

    expect(app(StudentAccessService::class)->syncStudentStatuses())->toBe(1)
        ->and($user->fresh()->active)->toBeTrue();
});

it('uses the latest payment when checking the thirty day rule', function () {
    [$user, $student, $enrollment, $unit] = studentWithActiveEnrollment('recente@example.com', '55566677708');
    FinancialTransaction::create(['enrollment_id' => $enrollment->id, 'student_id' => $student->id, 'unit_id' => $unit->id, 'description' => 'Pagamento antigo', 'amount' => 89.90, 'due_date' => today()->subDays(60), 'paid_at' => now()->subDays(45), 'status' => 'paid', 'transaction_type' => 'income']);
    FinancialTransaction::create(['enrollment_id' => $enrollment->id, 'student_id' => $student->id, 'unit_id' => $unit->id, 'description' => 'Pagamento recente', 'amount' => 89.90, 'due_date' => today(), 'paid_at' => now()->subDays(5), 'status' => 'paid', 'transaction_type' => 'income']);

    expect(app(StudentAccessService::class)->syncStudentStatuses())->toBe(0)
        ->and($user->fresh()->active)->toBeTrue();
});
