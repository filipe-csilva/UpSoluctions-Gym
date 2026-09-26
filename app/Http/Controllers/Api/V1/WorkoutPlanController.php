<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\WorkoutPlan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WorkoutPlanController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $plans = WorkoutPlan::query()->with(['student.user:id,name', 'teacher:id,name'])->latest()->paginate(20);

        return response()->json($plans);
    }
}
