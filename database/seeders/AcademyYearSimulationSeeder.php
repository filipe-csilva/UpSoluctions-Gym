<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Enrollment;
use App\Models\FinancialTransaction;
use App\Models\PhysicalAssessment;
use App\Models\Plan;
use App\Models\StudentProfile;
use App\Models\TeacherProfile;
use App\Models\WorkoutPlan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AcademyYearSimulationSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $unitIds = DB::table('units')->orderBy('id')->pluck('id')->values();
            $teachers = TeacherProfile::query()->with('user')->get();
            $students = StudentProfile::query()->with('user')->orderBy('id')->get();
            $simulationStart = today()->subMonths(11)->startOfMonth();
            $plans = collect([
                ['name' => 'Plano Mensal', 'description' => 'Acesso mensal à academia.', 'duration_months' => 1, 'installments' => 1, 'price' => 89.90, 'active' => true],
                ['name' => 'Plano Trimestral', 'description' => 'Acesso por três meses, parcelado em três vezes.', 'duration_months' => 3, 'installments' => 3, 'price' => 239.90, 'active' => true],
                ['name' => 'Plano Anual', 'description' => 'Acesso anual, parcelado em doze vezes.', 'duration_months' => 12, 'installments' => 12, 'price' => 799.90, 'active' => true],
            ])->map(fn (array $data): Plan => Plan::updateOrCreate(['name' => $data['name']], $data));

            foreach ($students as $index => $student) {
                $plan = $plans[$index % $plans->count()];
                $teacher = $teachers->isNotEmpty() ? $teachers[$index % $teachers->count()]->user : null;
                $unitId = $student->user->unit_id ?: $unitIds[$index % $unitIds->count()];
                $discountRate = [0, 0.03, 0.05, 0.08, 0.02][$index % 5];
                $enrollmentPrice = round((float) $plan->price * (1 - $discountRate), 2);
                $enrollment = Enrollment::create([
                    'student_id' => $student->id, 'plan_id' => $plan->id, 'unit_id' => $unitId,
                    'start_date' => $simulationStart, 'end_date' => today()->addMonth(),
                    'price' => $enrollmentPrice, 'status' => 'active', 'payment_day' => 10 + ($index % 10),
                    'notes' => 'Matrícula criada pela simulação anual da academia.',
                ]);

                for ($month = 0; $month < 12; $month++) {
                    $dueDate = $simulationStart->copy()->addMonths($month)->day(10 + ($index % 10));
                    $isOpen = ($month === 11 && $index >= 7)
                        || ($month === 6 && $index === 4)
                        || ($month === 9 && $index === 1);
                    $installmentNumber = ($month % $plan->installments) + 1;
                    $installmentAmount = round($enrollmentPrice / $plan->installments, 2);
                    $amount = $installmentNumber === $plan->installments
                        ? round($enrollmentPrice - ($installmentAmount * ($plan->installments - 1)), 2)
                        : $installmentAmount;
                    FinancialTransaction::create([
                        'enrollment_id' => $enrollment->id, 'student_id' => $student->id, 'unit_id' => $unitId,
                        'description' => $plan->installments > 1 ? 'Mensalidade - '.$plan->name.' - Parcela '.$installmentNumber.'/'.$plan->installments : 'Mensalidade - '.$plan->name,
                        'amount' => $amount, 'due_date' => $dueDate,
                        'paid_at' => $isOpen ? null : $dueDate->copy()->addDay()->setTime(12, 0),
                        'status' => $isOpen ? 'overdue' : 'paid',
                        'payment_method' => $isOpen ? null : ['pix', 'cash', 'credit_card'][$month % 3],
                        'transaction_type' => 'income', 'notes' => $isOpen ? 'Parcela vencida na simulação.' : null,
                    ]);

                    if ($month === 0 && $index % 3 === 0) {
                        FinancialTransaction::create([
                            'enrollment_id' => $enrollment->id, 'student_id' => $student->id, 'unit_id' => $unitId,
                            'description' => 'Taxa de matrícula', 'amount' => 49.90,
                            'due_date' => $dueDate, 'paid_at' => $dueDate->copy()->addDay()->setTime(12, 0),
                            'status' => 'paid', 'payment_method' => 'pix', 'transaction_type' => 'income',
                            'notes' => 'Taxa inicial incluída na simulação anual.',
                        ]);
                    }
                }

                FinancialTransaction::create([
                    'enrollment_id' => $enrollment->id, 'student_id' => $student->id, 'unit_id' => $unitId,
                    'description' => 'Próxima mensalidade - '.$plan->name,
                    'amount' => round($enrollmentPrice / $plan->installments, 2),
                    'due_date' => today()->addDays(10 + ($index % 10)), 'status' => 'pending', 'transaction_type' => 'income',
                ]);

                if ($teacher) {
                    WorkoutPlan::create([
                        'student_id' => $student->id, 'teacher_id' => $teacher->id,
                        'name' => 'Ficha anual - '.$student->user->name, 'description' => 'Treino criado durante a simulação anual.',
                        'start_date' => $simulationStart, 'end_date' => today()->addMonths(2), 'status' => 'active',
                    ]);
                }

                for ($month = 0; $month < 12; $month++) {
                    Attendance::create([
                        'student_id' => $student->id, 'unit_id' => $unitId, 'registered_by' => $teacher?->id,
                        'date' => $simulationStart->copy()->addMonths($month)->day(15),
                        'entry_time' => '07:'.str_pad((string) ($index % 5), 2, '0', STR_PAD_LEFT).':00',
                        'exit_time' => '08:00:00', 'type' => 'regular',
                    ]);
                }

                for ($month = 0; $month < 12; $month += 3) {
                    $assessmentDate = $simulationStart->copy()->addMonths($month)->day(20);
                    $height = 1.60 + (($index % 8) * 0.02);
                    $weight = 78 - ($index * 0.4) - ($month * 0.2);
                    PhysicalAssessment::create([
                        'student_id' => $student->id, 'teacher_id' => $teacher?->id, 'assessment_date' => $assessmentDate,
                        'height' => $height, 'weight' => round($weight, 2), 'bmi' => round($weight / pow($height, 2), 2),
                        'body_fat' => 18 + ($index % 7), 'muscle_mass' => 32 + ($index % 6), 'notes' => 'Avaliação registrada na simulação anual.',
                    ]);
                }
            }

            $firstEnrollment = Enrollment::query()->firstOrFail();
            $firstStudent = $students->firstOrFail();
            for ($month = 0; $month < 12; $month++) {
                $dueDate = $simulationStart->copy()->addMonths($month)->day(5);
                $utilities = [85, 92, 110, 120, 115, 105, 90, 88, 96, 102, 118, 125];
                $cleaning = [55, 60, 65, 58, 70, 62, 68, 60, 75, 64, 72, 80];
                $monthlyExpenses = [
                    ['Aluguel da unidade', 390],
                    ['Energia elétrica', $utilities[$month]],
                    ['Limpeza e manutenção', $cleaning[$month]],
                    ['Sistema e serviços', 49.90],
                ];

                if ($month === 5) {
                    $monthlyExpenses[] = ['Manutenção de equipamentos', 140];
                }

                foreach ($monthlyExpenses as [$description, $amount]) {
                    FinancialTransaction::create([
                        'enrollment_id' => $firstEnrollment->id, 'student_id' => $firstStudent->id, 'unit_id' => $firstEnrollment->unit_id,
                        'description' => $description, 'amount' => $amount, 'due_date' => $dueDate,
                        'paid_at' => $dueDate->copy()->addDay()->setTime(16, 0), 'status' => 'paid',
                        'payment_method' => 'bank_transfer', 'transaction_type' => 'expense',
                    ]);
                }
            }

            $futureExpenses = [
                ['Aluguel da unidade', 390, 7],
                ['Energia elétrica estimada', 105, 20],
                ['Limpeza e manutenção programada', 70, 35],
                ['Sistema e serviços', 49.90, 50],
                ['Manutenção preventiva', 180, 80],
            ];

            foreach ($futureExpenses as [$description, $amount, $daysUntilDue]) {
                FinancialTransaction::create([
                    'enrollment_id' => $firstEnrollment->id, 'student_id' => $firstStudent->id,
                    'unit_id' => $firstEnrollment->unit_id, 'description' => $description,
                    'amount' => $amount, 'due_date' => today()->addDays($daysUntilDue),
                    'status' => 'pending', 'transaction_type' => 'expense',
                    'notes' => 'Conta futura incluída na simulação financeira.',
                ]);
            }
        });
    }
}
