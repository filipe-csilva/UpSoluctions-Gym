<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\FinancialTransaction;
use App\Models\StudentProfile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function summary(Request $request): JsonResponse
    {
        $user = $request->user();
        $from = $request->date('from')?->startOfDay() ?? now()->startOfMonth();
        $to = $request->date('to')?->endOfDay() ?? now()->endOfMonth();
        $transactions = FinancialTransaction::query()
            ->whereBetween('due_date', [$from->toDateString(), $to->toDateString()])
            ->when($user->role?->value === 'manager', fn ($query) => $query->whereIn('unit_id', $user->accessibleUnitIds()))
            ->when($user->role?->value === 'student', fn ($query) => $query->where('student_id', $user->studentProfile?->id));

        return response()->json([
            'period' => ['from' => $from->toDateString(), 'to' => $to->toDateString()],
            'students' => StudentProfile::query()->when($user->role?->value === 'manager', fn ($query) => $query->whereHas('user', fn ($userQuery) => $userQuery->whereIn('unit_id', $user->accessibleUnitIds())))->count(),
            'income_paid' => (clone $transactions)->where('transaction_type', 'income')->where('status', 'paid')->sum('amount'),
            'expenses_paid' => (clone $transactions)->where('transaction_type', 'expense')->where('status', 'paid')->sum('amount'),
            'overdue' => (clone $transactions)->whereIn('status', ['pending', 'overdue'])->whereDate('due_date', '<', today())->sum('amount'),
        ]);
    }
}
