<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\StudentProfile;
use App\Models\TeacherProfile;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = request()->user();
        $isManager = $user->role?->value === 'manager';
        $unitIds = $isManager ? $user->accessibleUnitIds() : [];
        $period = $request->string('period', '6m')->toString();
        $period = in_array($period, ['week', 'month', '3m', '6m', 'year'], true) ? $period : '6m';
        $interval = match ($period) {
            'week' => ['step' => 'day', 'count' => 7],
            'month' => ['step' => 'day', 'count' => 30],
            '3m' => ['step' => 'month', 'count' => 3],
            'year' => ['step' => 'month', 'count' => 12],
            default => ['step' => 'month', 'count' => 6],
        };

        $evolution = collect(range($interval['count'] - 1, 0))->map(function (int $stepsAgo) use ($interval, $isManager, $unitIds): array {
            $date = $interval['step'] === 'day' ? today()->subDays($stepsAgo) : now()->subMonths($stepsAgo);
            $start = $interval['step'] === 'day' ? $date->copy()->startOfDay() : $date->copy()->startOfMonth();
            $end = $interval['step'] === 'day' ? $date->copy()->endOfDay() : $date->copy()->endOfMonth();

            return [
                'label' => $interval['step'] === 'day' ? $date->format('d/m') : $date->translatedFormat('M/y'),
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
            'selectedPeriod' => $period,
            'studentsByUnit' => $units,
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
