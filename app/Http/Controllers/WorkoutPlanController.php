<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWorkoutPlanRequest;
use App\Models\ActivityLog;
use App\Models\Exercise;
use App\Models\StudentProfile;
use App\Models\User;
use App\Models\WorkoutPlan;
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

    public function create(): View
    {
        return view('workout-plans.create', ['students' => StudentProfile::with('user')->get(), 'teachers' => User::where('role', 'teacher')->where('active', true)->get(), 'exercises' => Exercise::where('active', true)->orderBy('name')->get()]);
    }

    public function store(StoreWorkoutPlanRequest $request): RedirectResponse
    {
        $plan = WorkoutPlan::create($request->validated());
        ActivityLog::record('created', $plan, 'Ficha de treino criada.');

        return redirect()->route('workout-plans.show', $plan)->with('success', 'Ficha de treino criada com sucesso.');
    }

    public function show(WorkoutPlan $workout_plan): View
    {
        $workout_plan->load(['student.user', 'teacher', 'exercises.exercise']);

        return view('workout-plans.show', ['plan' => $workout_plan]);
    }

    public function destroy(WorkoutPlan $workout_plan): RedirectResponse
    {
        $workout_plan->update(['status' => 'cancelled']);
        ActivityLog::record('updated', $workout_plan, 'Ficha de treino cancelada.');

        return redirect()->route('workout-plans.index')->with('success', 'Ficha de treino cancelada.');
    }
}
