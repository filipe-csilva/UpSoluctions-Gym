<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Enrollment;
use App\Models\Exercise;
use App\Models\PhysicalAssessment;
use App\Models\Plan;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function units(Request $request): JsonResponse
    {
        abort_unless(in_array($request->user()->role, [UserRole::ADMIN, UserRole::MANAGER], true), 403);
        $units = Unit::query()
            ->where('active', true)
            ->when($request->user()->role === UserRole::MANAGER, fn ($query) => $query->whereIn('id', $request->user()->accessibleUnitIds()))
            ->orderBy('name')
            ->paginate(30)
            ->withQueryString();

        return response()->json($units);
    }

    public function plans(): JsonResponse
    {
        return response()->json(Plan::query()->where('active', true)->orderBy('name')->paginate(30));
    }

    public function enrollments(Request $request): JsonResponse
    {
        $user = $request->user();
        $items = Enrollment::query()
            ->with(['student.user:id,name', 'plan:id,name', 'unit:id,name'])
            ->when($user->role === UserRole::STUDENT, fn ($query) => $query->where('student_id', $user->studentProfile?->id))
            ->when($user->role === UserRole::MANAGER, fn ($query) => $query->whereIn('unit_id', $user->accessibleUnitIds()))
            ->latest()
            ->paginate(30)
            ->withQueryString();

        return response()->json($items);
    }

    public function attendances(Request $request): JsonResponse
    {
        $user = $request->user();
        $items = Attendance::query()
            ->with(['student.user:id,name', 'unit:id,name'])
            ->when($user->role === UserRole::STUDENT, fn ($query) => $query->where('student_id', $user->studentProfile?->id))
            ->when(in_array($user->role, [UserRole::MANAGER, UserRole::TEACHER], true), fn ($query) => $query->whereIn('unit_id', $user->accessibleUnitIds()))
            ->latest('date')
            ->paginate(30)
            ->withQueryString();

        return response()->json($items);
    }

    public function assessments(Request $request): JsonResponse
    {
        $user = $request->user();
        $items = PhysicalAssessment::query()
            ->with(['student.user:id,name', 'teacher:id,name'])
            ->when($user->role === UserRole::STUDENT, fn ($query) => $query->where('student_id', $user->studentProfile?->id))
            ->when(in_array($user->role, [UserRole::MANAGER, UserRole::TEACHER], true), fn ($query) => $query->whereHas('student.user', fn ($userQuery) => $userQuery->whereIn('unit_id', $user->accessibleUnitIds())))
            ->latest('assessment_date')
            ->paginate(30)
            ->withQueryString();

        return response()->json($items);
    }

    public function exercises(): JsonResponse
    {
        return response()->json(Exercise::query()->where('active', true)->orderBy('name')->paginate(30));
    }

    public function users(Request $request): JsonResponse
    {
        abort_unless($request->user()->role === UserRole::ADMIN, 403);

        return response()->json(User::query()->with('unit:id,name')->select(['id', 'name', 'email', 'role', 'unit_id', 'active'])->latest()->paginate(30));
    }
}
