<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Enrollment;
use App\Models\FinancialTransaction;
use App\Models\Plan;
use App\Models\StudentProfile;
use App\Models\Unit;
use App\Models\User;
use App\Models\WorkoutPlan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StudentDemoAccountSeeder extends Seeder
{
    public function run(): void
    {
        $unit = Unit::query()->where('active', true)->orderBy('id')->firstOrFail();
        $user = User::updateOrCreate(
            ['email' => 'aluno@upsoluctions.com.br'],
            ['name' => 'Aluno Teste', 'password' => Hash::make('P@ssw0rd'), 'unit_id' => $unit->id, 'role' => UserRole::STUDENT, 'active' => true, 'email_verified_at' => now()],
        );
        $student = StudentProfile::updateOrCreate(
            ['user_id' => $user->id],
            ['cpf' => '12345678909', 'birth_date' => '1995-05-15', 'phone' => '85999999999', 'gender' => 'M', 'address' => 'Rua da Academia', 'number' => '100', 'neighborhood' => 'Centro', 'city' => 'Fortaleza', 'state' => 'CE', 'zip_code' => '60000000', 'notes' => 'Usuário demonstrativo para testes.'],
        );
        $plan = Plan::query()->where('name', 'Plano Mensal')->firstOrFail();
        $enrollment = Enrollment::firstOrCreate(
            ['student_id' => $student->id],
            ['plan_id' => $plan->id, 'unit_id' => $unit->id, 'start_date' => today()->startOfMonth(), 'end_date' => today()->addMonth()->endOfMonth(), 'price' => $plan->price, 'status' => 'active', 'payment_day' => 10, 'notes' => 'Matrícula do usuário demonstrativo.'],
        );
        FinancialTransaction::firstOrCreate(
            ['enrollment_id' => $enrollment->id, 'due_date' => today()->addDays(10)],
            ['student_id' => $student->id, 'unit_id' => $unit->id, 'description' => 'Próxima mensalidade - '.$plan->name, 'amount' => $plan->price, 'status' => 'pending', 'transaction_type' => 'income'],
        );
        $teacher = User::query()->where('role', UserRole::TEACHER)->where('active', true)->first();
        if ($teacher) {
            WorkoutPlan::firstOrCreate(['student_id' => $student->id, 'name' => 'Treino do Aluno Teste'], ['teacher_id' => $teacher->id, 'start_date' => today(), 'status' => 'active', 'description' => 'Treino demonstrativo.']);
        }
    }
}
