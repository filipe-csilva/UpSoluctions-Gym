<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\StudentResource;
use App\Models\StudentProfile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $students = StudentProfile::query()
            ->with('user:id,name,email,unit_id')
            ->whereHas('user', fn ($query) => $query->where('active', true))
            ->when($user->role === UserRole::STUDENT, fn ($query) => $query->where('id', $user->studentProfile?->id))
            ->when($user->role === UserRole::MANAGER, fn ($query) => $query->whereHas('user', fn ($userQuery) => $userQuery->whereIn('unit_id', $user->accessibleUnitIds())))
            ->when($request->filled('search'), fn ($query) => $query->whereHas('user', fn ($userQuery) => $userQuery->where('name', 'like', '%'.$request->string('search')->toString().'%')))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return StudentResource::collection($students)->response();
    }

    public function show(Request $request, StudentProfile $student): JsonResponse
    {
        $user = $request->user();
        abort_unless(
            $user->role === UserRole::ADMIN
            || ($user->role === UserRole::STUDENT && $user->studentProfile?->is($student))
            || ($user->role === UserRole::MANAGER && in_array((int) $student->user?->unit_id, $user->accessibleUnitIds(), true)),
            403,
        );

        return (new StudentResource($student->load(['user:id,name,email,unit_id', 'enrollments', 'workoutPlans', 'attendances'])))->response();
    }
}
