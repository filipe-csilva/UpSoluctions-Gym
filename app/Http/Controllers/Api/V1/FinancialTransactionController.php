<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\FinancialTransactionResource;
use App\Models\FinancialTransaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FinancialTransactionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $transactions = FinancialTransaction::query()
            ->with(['student.user:id,name', 'unit:id,name'])
            ->when($user->role === UserRole::STUDENT, fn ($query) => $query->where('student_id', $user->studentProfile?->id))
            ->when($user->role === UserRole::MANAGER, fn ($query) => $query->whereIn('unit_id', $user->accessibleUnitIds()))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')->toString()))
            ->when($request->filled('type'), fn ($query) => $query->where('transaction_type', $request->string('type')->toString()))
            ->latest('due_date')
            ->paginate(30)
            ->withQueryString();

        return FinancialTransactionResource::collection($transactions)->response();
    }
}
