<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Attendance;
use App\Models\FinancialTransaction;
use App\Models\PhysicalAssessment;
use App\Models\User;
use App\Models\WorkoutPlan;
use App\Notifications\FinancialDueSoon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PanelController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();
        $role = $user->role?->value;

        $announcements = Announcement::query()
            ->with('unit')
            ->where('active', true)
            ->where(function ($query): void {
                $query->whereNull('start_at')->orWhere('start_at', '<=', now());
            })
            ->where(function ($query): void {
                $query->whereNull('end_at')->orWhere('end_at', '>=', now());
            })
            ->where(function ($query) use ($role): void {
                $query->whereNull('target_role')->orWhere('target_role', 'all')->orWhere('target_role', $role);
            })
            ->where(function ($query) use ($user): void {
                $query->whereNull('unit_id')->orWhere('unit_id', $user->unit_id);
            })
            ->when($role === 'student', fn ($query) => $query->where(function ($scope) use ($user): void {
                $scope->where('is_default', true)->orWhere('announcements.created_at', '>=', $user->created_at);
            }))
            ->latest()
            ->paginate(9);

        $presentStudents = collect();
        $workoutPlan = null;

        if ($role === 'teacher') {
            $presentStudents = Attendance::query()
                ->with('student.user')
                ->whereDate('date', today())
                ->where('unit_id', $user->unit_id)
                ->latest('entry_time')
                ->get()
                ->unique('student_id')
                ->values();
        }

        if ($role === 'student' && $user->studentProfile) {
            $this->notifyUpcomingFinancialDue($user);

            $workoutPlan = WorkoutPlan::query()
                ->with(['teacher', 'exercises.exercise'])
                ->where('student_id', $user->studentProfile->id)
                ->where('status', 'active')
                ->whereDate('start_date', '<=', today())
                ->where(function ($query): void {
                    $query->whereNull('end_date')->orWhereDate('end_date', '>=', today());
                })
                ->latest('start_date')
                ->first();
        }

        if ($role === 'teacher' && $request->routeIs('dashboard')) {
            $teacherStudentCount = WorkoutPlan::query()->where('teacher_id', $user->id)->distinct('student_id')->count('student_id');
            $teacherWorkoutCount = WorkoutPlan::query()->where('teacher_id', $user->id)->where('status', 'active')->count();
            $teacherAssessmentCount = PhysicalAssessment::query()->where('teacher_id', $user->id)->count();
            $teacherAttendanceCount = Attendance::query()->where('unit_id', $user->unit_id)->whereDate('date', today())->count();

            return view('teacher-dashboard', compact('announcements', 'presentStudents', 'teacherStudentCount', 'teacherWorkoutCount', 'teacherAssessmentCount', 'teacherAttendanceCount'));
        }

        if ($role === 'student' && $request->routeIs('dashboard')) {
            $student = $user->studentProfile;
            $studentEnrollment = $student?->enrollments()->with('plan')->where('status', 'active')->latest('end_date')->first();
            $studentPendingTransactions = $student?->financialTransactions()->where('transaction_type', 'income')->whereIn('status', ['pending', 'overdue'])->orderBy('due_date')->get() ?? collect();
            $studentAttendanceCount = $student?->attendances()->whereBetween('date', [today()->startOfMonth(), today()->endOfMonth()])->count() ?? 0;
            $studentWorkoutCount = $student?->workoutPlans()->where('status', 'active')->count() ?? 0;

            return view('student-dashboard', compact('announcements', 'workoutPlan', 'studentEnrollment', 'studentPendingTransactions', 'studentAttendanceCount', 'studentWorkoutCount'));
        }

        return view('panel', compact('announcements', 'presentStudents', 'workoutPlan'));
    }

    private function notifyUpcomingFinancialDue(User $user): void
    {
        $transactions = FinancialTransaction::query()
            ->where('student_id', $user->studentProfile->id)
            ->where('transaction_type', 'income')
            ->where('status', 'pending')
            ->whereDate('due_date', today()->addDays(5))
            ->get();
        $existingNotifications = $user->notifications()
            ->where('type', FinancialDueSoon::class)
            ->get()
            ->pluck('data')
            ->pluck('transaction_id')
            ->map(static fn ($id): int => (int) $id)
            ->all();

        foreach ($transactions as $transaction) {
            if (! in_array($transaction->id, $existingNotifications, true)) {
                $user->notify(new FinancialDueSoon($transaction));
            }
        }
    }
}
