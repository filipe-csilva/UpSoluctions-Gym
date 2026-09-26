<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Http\Requests\StoreWorkoutPlanRequest;
use App\Models\ActivityLog;
use App\Models\StudentProfile;
use App\Models\User;
use App\Models\WorkoutPlan;
use App\Notifications\WorkoutPlanCreated;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WorkoutPlanController extends Controller
{
    public function index(Request $request): View
    {
        $plans = WorkoutPlan::with(['student.user', 'teacher'])->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')->toString()))->latest()->paginate(15)->withQueryString();

        return view('workout-plans.index', compact('plans'));
    }

    public function create(Request $request): View
    {
        $user = $request->user();
        $students = StudentProfile::with('user')
            ->whereHas('user', function ($query) use ($user): void {
                $query->where('active', true)
                    ->when($user->role?->value === UserRole::TEACHER->value, fn ($scope) => $scope->where('unit_id', $user->unit_id))
                    ->when($user->role?->value === UserRole::MANAGER->value, fn ($scope) => $scope->whereIn('unit_id', $user->accessibleUnitIds()));
            })
            ->get();
        $teachers = User::query()
            ->where('role', UserRole::TEACHER->value)
            ->where('active', true)
            ->when($user->role?->value === UserRole::MANAGER->value, fn ($scope) => $scope->whereIn('unit_id', $user->accessibleUnitIds()))
            ->get();

        return view('workout-plans.create', compact('students', 'teachers'));
    }

    public function store(StoreWorkoutPlanRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $user = $request->user();
        $student = StudentProfile::with('user')->findOrFail($data['student_id']);

        abort_unless(
            $user->role?->value === UserRole::ADMIN->value
                || ($user->role?->value === UserRole::TEACHER->value && (int) $student->user?->unit_id === (int) $user->unit_id)
                || ($user->role?->value === UserRole::MANAGER->value && in_array((int) $student->user?->unit_id, $user->accessibleUnitIds(), true)),
            403,
        );

        if ($user->role?->value === UserRole::TEACHER->value) {
            $data['teacher_id'] = $user->id;
        } else {
            $teacher = User::query()
                ->whereKey($data['teacher_id'])
                ->where('role', UserRole::TEACHER->value)
                ->where('active', true)
                ->firstOrFail();
            abort_unless(
                $user->role?->value === UserRole::ADMIN->value
                    || in_array((int) $teacher->unit_id, $user->accessibleUnitIds(), true),
                403,
            );
        }

        $plan = WorkoutPlan::create($data);
        ActivityLog::record('created', $plan, 'Ficha de treino criada.');
        $student->user?->notify(new WorkoutPlanCreated($plan));

        return redirect()->route('workout-plans.show', $plan)->with('success', 'Ficha de treino criada com sucesso.');
    }

    public function show(WorkoutPlan $workout_plan): View
    {
        $workout_plan->load(['student.user', 'teacher', 'exercises.exercise']);

        return view('workout-plans.show', ['plan' => $workout_plan]);
    }

    public function studentHistory(StudentProfile $student): View
    {
        $student->load('user');
        $plans = WorkoutPlan::query()->with(['teacher', 'exercises.exercise'])->whereBelongsTo($student)->latest('start_date')->get();

        return view('workout-plans.student-history', compact('student', 'plans'));
    }

    public function destroy(WorkoutPlan $workout_plan): RedirectResponse
    {
        $workout_plan->update(['status' => 'cancelled']);
        ActivityLog::record('updated', $workout_plan, 'Ficha de treino cancelada.');

        return redirect()->route('workout-plans.index')->with('success', 'Ficha de treino cancelada.');
    }
}
