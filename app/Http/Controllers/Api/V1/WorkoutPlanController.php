<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\WorkoutPlanResource;
use App\Models\WorkoutPlan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WorkoutPlanController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $plans = WorkoutPlan::query()
            ->with(['student.user:id,name', 'teacher:id,name'])
            ->when($user->role === UserRole::STUDENT, fn ($query) => $query->where('student_id', $user->studentProfile?->id))
            ->when($user->role === UserRole::TEACHER, fn ($query) => $query->where('teacher_id', $user->id))
            ->when($user->role === UserRole::MANAGER, fn ($query) => $query->whereHas('student.user', fn ($userQuery) => $userQuery->whereIn('unit_id', $user->accessibleUnitIds())))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return WorkoutPlanResource::collection($plans)->response();
    }
}
