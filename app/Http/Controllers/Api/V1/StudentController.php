<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\StudentProfile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $students = StudentProfile::query()->with('user:id,name,email,unit_id')->whereHas('user', fn ($query) => $query->where('active', true))->latest()->paginate(20);

        return response()->json($students);
    }

    public function show(StudentProfile $student): JsonResponse
    {
        return response()->json($student->load(['user:id,name,email,unit_id', 'workoutPlans', 'attendances']));
    }
}
