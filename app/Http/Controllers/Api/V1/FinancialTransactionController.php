<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\FinancialTransaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FinancialTransactionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $transactions = FinancialTransaction::query()->with(['student.user:id,name', 'unit:id,name'])->latest('due_date')->paginate(30);

        return response()->json($transactions);
    }
}
