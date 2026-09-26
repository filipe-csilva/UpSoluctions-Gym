<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Enrollment;
use App\Models\FinancialTransaction;
use App\Models\StudentProfile;
use App\Models\TeacherProfile;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = request()->user();
        $isManager = $user->role?->value === 'manager';
        $unitIds = $isManager ? $user->accessibleUnitIds() : [];
        $periodOptions = ['week', 'month', '3m', '6m', 'year'];
        $studentPeriod = $request->string('student_period', '6m')->toString();
        $studentPeriod = in_array($studentPeriod, $periodOptions, true) ? $studentPeriod : '6m';
        $financialPeriod = $request->string('financial_period', '6m')->toString();
        $financialPeriod = in_array($financialPeriod, $periodOptions, true) ? $financialPeriod : '6m';
        $studentInterval = match ($studentPeriod) {
            'week' => ['step' => 'day', 'count' => 7],
            'month' => ['step' => 'day', 'count' => 30],
            '3m' => ['step' => 'month', 'count' => 3],
            'year' => ['step' => 'month', 'count' => 12],
            default => ['step' => 'month', 'count' => 6],
        };
        $financialInterval = match ($financialPeriod) {
            'week' => ['step' => 'day', 'count' => 7],
            'month' => ['step' => 'day', 'count' => 30],
            '3m' => ['step' => 'month', 'count' => 3],
            'year' => ['step' => 'month', 'count' => 12],
            default => ['step' => 'month', 'count' => 6],
        };

        $evolution = collect(range($studentInterval['count'] - 1, 0))->map(function (int $stepsAgo) use ($studentInterval, $isManager, $unitIds): array {
            $date = $studentInterval['step'] === 'day' ? today()->subDays($stepsAgo) : now()->subMonths($stepsAgo);
            $start = $studentInterval['step'] === 'day' ? $date->copy()->startOfDay() : $date->copy()->startOfMonth();
            $end = $studentInterval['step'] === 'day' ? $date->copy()->endOfDay() : $date->copy()->endOfMonth();

            return [
                'label' => $studentInterval['step'] === 'day' ? $date->format('d/m') : $date->translatedFormat('M/y'),
                'total' => StudentProfile::query()
                    ->whereBetween('created_at', [$start, $end])
                    ->when($isManager, function ($query) use ($unitIds): void {
                        $query->whereHas('user', function ($userQuery) use ($unitIds): void {
                            $userQuery->whereIn('unit_id', $unitIds);
                        });
                    })
                    ->count(),
            ];
        });

        $financialEvolution = collect(range($financialInterval['count'] - 1, 0))->map(function (int $stepsAgo) use ($financialInterval, $isManager, $unitIds): array {
            $date = $financialInterval['step'] === 'day' ? today()->subDays($stepsAgo) : now()->subMonths($stepsAgo);
            $start = $financialInterval['step'] === 'day' ? $date->copy()->startOfDay() : $date->copy()->startOfMonth();
            $end = $financialInterval['step'] === 'day' ? $date->copy()->endOfDay() : $date->copy()->endOfMonth();
            $query = FinancialTransaction::query()
                ->where('status', 'paid')
                ->whereBetween('paid_at', [$start, $end])
                ->when($isManager, fn ($builder) => $builder->whereIn('unit_id', $unitIds));

            return [
                'label' => $financialInterval['step'] === 'day' ? $date->format('d/m') : $date->translatedFormat('M/y'),
                'revenue' => (float) (clone $query)->where('transaction_type', 'income')->sum('amount'),
                'expenses' => (float) (clone $query)->where('transaction_type', 'expense')->sum('amount'),
            ];
        });

        $units = Unit::query()
            ->withCount(['users as students_count' => function ($query): void {
                $query->whereHas('studentProfile');
            }])
            ->where('active', true)
            ->when($isManager, function ($query) use ($unitIds): void {
                $query->whereIn('id', $unitIds);
            })
            ->orderByDesc('students_count')
            ->get();

        $unitColors = ['#2385f5', '#28b979', '#8751e7', '#f0a500', '#ef5364'];
        $unitTotal = max(1, (int) $units->sum('students_count'));
        $unitOffset = 0;
        $unitGradientSegments = [];

        foreach ($units as $index => $unit) {
            $start = $unitOffset;
            $unitOffset += ((int) $unit->students_count / $unitTotal) * 100;
            $unitGradientSegments[] = $unitColors[$index % count($unitColors)].' '.$start.'% '.$unitOffset.'%';
        }

        $unitGradient = $unitGradientSegments !== [] ? implode(', ', $unitGradientSegments) : '#718096 0% 100%';

        $activeEnrollments = Enrollment::query()
            ->where('status', 'active')
            ->when($isManager, fn ($query) => $query->whereIn('unit_id', $unitIds))
            ->count();

        $monthlyRevenue = FinancialTransaction::query()
            ->where('transaction_type', 'income')
            ->where('status', 'paid')
            ->whereBetween('paid_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->when($isManager, fn ($query) => $query->whereIn('unit_id', $unitIds))
            ->sum('amount');

        $monthlyExpenses = FinancialTransaction::query()
            ->where('transaction_type', 'expense')
            ->where('status', 'paid')
            ->whereBetween('paid_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->when($isManager, fn ($query) => $query->whereIn('unit_id', $unitIds))
            ->sum('amount');

        $financialPeriodStart = $financialInterval['step'] === 'day'
            ? today()->subDays($financialInterval['count'] - 1)->startOfDay()
            : now()->subMonths($financialInterval['count'] - 1)->startOfMonth();
        $financialPeriodEnd = now()->endOfDay();
        $financialPeriodQuery = FinancialTransaction::query()
            ->where('status', 'paid')
            ->whereBetween('paid_at', [$financialPeriodStart, $financialPeriodEnd])
            ->when($isManager, fn ($query) => $query->whereIn('unit_id', $unitIds));
        $financialPeriodRevenue = (clone $financialPeriodQuery)->where('transaction_type', 'income')->sum('amount');
        $financialPeriodExpenses = (clone $financialPeriodQuery)->where('transaction_type', 'expense')->sum('amount');
        $financialPeriodVariableExpenses = 0.0;
        $financialContributionMarginRatio = $financialPeriodRevenue > 0
            ? max(0, ($financialPeriodRevenue - $financialPeriodVariableExpenses) / $financialPeriodRevenue)
            : 1.0;
        $financialBreakEven = $financialContributionMarginRatio > 0
            ? $financialPeriodExpenses / $financialContributionMarginRatio
            : $financialPeriodExpenses;

        $upcomingDueTransactions = FinancialTransaction::query()
            ->with('student.user')
            ->where('status', 'pending')
            ->whereDate('due_date', '>=', today())
            ->when($isManager, fn ($query) => $query->whereIn('unit_id', $unitIds))
            ->orderBy('due_date')
            ->limit(4)
            ->get();

        $upcomingPayables = FinancialTransaction::query()
            ->with('student.user')
            ->where('transaction_type', 'expense')
            ->where('status', 'pending')
            ->whereDate('due_date', '>=', today())
            ->when($isManager, fn ($query) => $query->whereIn('unit_id', $unitIds))
            ->orderBy('due_date')
            ->limit(6)
            ->get();

        $upcomingReceivables = FinancialTransaction::query()
            ->with('student.user')
            ->where('transaction_type', 'income')
            ->where('status', 'pending')
            ->whereDate('due_date', '>=', today())
            ->when($isManager, fn ($query) => $query->whereIn('unit_id', $unitIds))
            ->orderBy('due_date')
            ->limit(6)
            ->get();

        $upcomingBirthdays = StudentProfile::query()
            ->with('user')
            ->whereNotNull('birth_date')
            ->whereHas('user', fn ($query) => $query->where('active', true))
            ->when($isManager, fn ($query) => $query->whereHas('user', fn ($userQuery) => $userQuery->whereIn('unit_id', $unitIds)))
            ->get()
            ->map(function (StudentProfile $student): array {
                $birthday = Carbon::create(today()->year, $student->birth_date->month, $student->birth_date->day);
                if ($birthday->isBefore(today())) {
                    $birthday->addYear();
                }

                return ['name' => $student->user->name, 'date' => $birthday, 'age' => $student->birth_date->diffInYears($birthday)];
            })
            ->sortBy('date')
            ->take(5)
            ->values();

        return view('dashboard', [
            'activeStudents' => StudentProfile::query()
                ->whereHas('user', function ($query): void {
                    $query->where('active', true);
                })
                ->when($isManager, function ($query) use ($unitIds): void {
                    $query->whereHas('user', function ($userQuery) use ($unitIds): void {
                        $userQuery->whereIn('unit_id', $unitIds);
                    });
                })
                ->count(),
            'inactiveStudents' => StudentProfile::query()
                ->whereHas('user', function ($query): void {
                    $query->where('active', false);
                })
                ->when($isManager, function ($query) use ($unitIds): void {
                    $query->whereHas('user', function ($userQuery) use ($unitIds): void {
                        $userQuery->whereIn('unit_id', $unitIds);
                    });
                })
                ->count(),
            'activeEnrollments' => $activeEnrollments,
            'monthlyRevenue' => $monthlyRevenue,
            'monthlyExpenses' => $monthlyExpenses,
            'financialPeriodRevenue' => $financialPeriodRevenue,
            'financialPeriodExpenses' => $financialPeriodExpenses,
            'financialPeriodVariableExpenses' => $financialPeriodVariableExpenses,
            'financialContributionMarginRatio' => $financialContributionMarginRatio,
            'financialBreakEven' => $financialBreakEven,
            'upcomingDueTransactions' => $upcomingDueTransactions,
            'upcomingPayables' => $upcomingPayables,
            'upcomingReceivables' => $upcomingReceivables,
            'upcomingBirthdays' => $upcomingBirthdays,
            'todayStudentsCount' => StudentProfile::query()
                ->whereDate('created_at', today())
                ->when($isManager, function ($query) use ($unitIds): void {
                    $query->whereHas('user', function ($userQuery) use ($unitIds): void {
                        $userQuery->whereIn('unit_id', $unitIds);
                    });
                })
                ->count(),
            'todayStudents' => StudentProfile::query()
                ->with('user.unit')
                ->whereDate('created_at', today())
                ->when($isManager, function ($query) use ($unitIds): void {
                    $query->whereHas('user', function ($userQuery) use ($unitIds): void {
                        $userQuery->whereIn('unit_id', $unitIds);
                    });
                })
                ->latest()
                ->limit(6)
                ->get(),
            'totalTeachers' => TeacherProfile::query()
                ->whereHas('user', function ($query): void {
                    $query->where('active', true);
                })
                ->when($isManager, function ($query) use ($unitIds): void {
                    $query->whereHas('user', function ($userQuery) use ($unitIds): void {
                        $userQuery->whereIn('unit_id', $unitIds);
                    });
                })
                ->count(),
            'latestTeachers' => TeacherProfile::query()
                ->with('user.unit')
                ->when($isManager, function ($query) use ($unitIds): void {
                    $query->whereHas('user', function ($userQuery) use ($unitIds): void {
                        $userQuery->whereIn('unit_id', $unitIds);
                    });
                })
                ->latest()
                ->limit(6)
                ->get(),
            'studentEvolution' => $evolution,
            'financialEvolution' => $financialEvolution,
            'selectedStudentPeriod' => $studentPeriod,
            'selectedFinancialPeriod' => $financialPeriod,
            'studentsByUnit' => $units,
            'unitGradient' => $unitGradient,
            'recentActivities' => ActivityLog::query()
                ->with('user')
                ->when($isManager, function ($query) use ($unitIds): void {
                    $query->whereHas('user', function ($userQuery) use ($unitIds): void {
                        $userQuery->whereIn('unit_id', $unitIds);
                    });
                })
                ->latest()
                ->limit(5)
                ->get(),
        ]);
    }
}
