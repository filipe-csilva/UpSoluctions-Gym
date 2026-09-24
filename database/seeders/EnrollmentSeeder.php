<?php

namespace Database\Seeders;

use App\Models\Enrollment;
use App\Models\FinancialTransaction;
use App\Models\Plan;
use App\Models\StudentProfile;
use App\Models\Unit;
use Illuminate\Database\Seeder;

class EnrollmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = collect([
            ['name' => 'Plano Mensal', 'description' => 'Acesso mensal à academia.', 'duration_months' => 1, 'price' => 89.90],
            ['name' => 'Plano Trimestral', 'description' => 'Acesso por três meses.', 'duration_months' => 3, 'price' => 239.90],
            ['name' => 'Plano Anual', 'description' => 'Acesso anual à academia.', 'duration_months' => 12, 'price' => 799.90],
        ])->map(fn (array $data): Plan => Plan::firstOrCreate(['name' => $data['name']], $data));

        $students = StudentProfile::with('user')->limit(3)->get();
        $unit = Unit::query()->where('active', true)->firstOrFail();
        $plan = $plans->first();

        foreach ($students as $index => $student) {
            $enrollment = Enrollment::firstOrCreate(
                ['student_id' => $student->id],
                ['plan_id' => $plan->id, 'unit_id' => $student->user->unit_id ?: $unit->id, 'start_date' => today()->subDays($index * 4), 'end_date' => today()->addMonth()->subDays($index * 4), 'price' => $plan->price, 'status' => 'active', 'payment_day' => 10 + $index, 'notes' => 'Matrícula de demonstração.'],
            );

            FinancialTransaction::firstOrCreate(
                ['enrollment_id' => $enrollment->id],
                ['student_id' => $student->id, 'unit_id' => $enrollment->unit_id, 'description' => 'Mensalidade - '.$plan->name, 'amount' => $plan->price, 'due_date' => today()->addDays(10 + $index), 'status' => $index === 0 ? 'paid' : 'pending', 'paid_at' => $index === 0 ? now() : null, 'payment_method' => $index === 0 ? 'pix' : null, 'transaction_type' => 'income'],
            );
        }
    }
}
