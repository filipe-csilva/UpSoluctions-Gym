<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFinancialTransactionRequest;
use App\Http\Requests\UpdateFinancialTransactionRequest;
use App\Models\ActivityLog;
use App\Models\Enrollment;
use App\Models\FinancialTransaction;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FinancialTransactionController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $isManager = $user->role?->value === 'manager';
        $unitIds = $isManager ? $user->accessibleUnitIds() : [];
        $hasPeriodFilter = $request->filled('from') || $request->filled('to');
        $from = $request->filled('from') ? Carbon::parse($request->string('from')->toString())->startOfDay() : now()->startOfMonth();
        $to = $request->filled('to') ? Carbon::parse($request->string('to')->toString())->endOfDay() : now()->endOfMonth();
        $baseQuery = FinancialTransaction::query()
            ->with(['student.user', 'unit', 'enrollment'])
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')->toString()))
            ->when($request->filled('transaction_type'), fn ($query) => $query->where('transaction_type', $request->string('transaction_type')->toString()))
            ->when($request->filled('search'), function ($query) use ($request): void {
                $search = $request->string('search')->toString();
                $query->where(function ($scope) use ($search): void {
                    $scope->where('description', 'like', "%{$search}%")
                        ->orWhereHas('student.user', fn ($userQuery) => $userQuery->where('name', 'like', "%{$search}%"));
                });
            })
            ->when($hasPeriodFilter, fn ($query) => $query->whereBetween('due_date', [$from->toDateString(), $to->toDateString()]))
            ->when($isManager, fn ($query) => $query->whereIn('unit_id', $unitIds));

        $transactions = (clone $baseQuery)->latest('due_date')->paginate(15)->withQueryString();
        $summary = [
            'income' => (clone $baseQuery)->where('transaction_type', 'income')->sum('amount'),
            'expenses' => (clone $baseQuery)->where('transaction_type', 'expense')->sum('amount'),
            'paid' => (clone $baseQuery)->where('status', 'paid')->sum('amount'),
            'overdue' => (clone $baseQuery)->where(function ($query): void {
                $query->where('status', 'overdue')->orWhere(function ($pending): void {
                    $pending->where('status', 'pending')->whereDate('due_date', '<', today());
                });
            })->sum('amount'),
        ];

        return view('financial.index', compact('transactions', 'summary', 'from', 'to'));
    }

    public function create(): View
    {
        return view('financial.create', ['enrollments' => Enrollment::with(['student.user', 'plan'])->where('status', 'active')->get()]);
    }

    public function store(StoreFinancialTransactionRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $enrollment = Enrollment::findOrFail($data['enrollment_id']);
        $transaction = FinancialTransaction::create($data + ['student_id' => $enrollment->student_id, 'unit_id' => $enrollment->unit_id]);
        ActivityLog::record('created', $transaction, 'Lançamento financeiro criado.');

        return redirect()->route('financial.index')->with('success', 'Lançamento financeiro criado com sucesso.');
    }

    public function show(FinancialTransaction $financial): View
    {
        $financial->load(['student.user', 'unit', 'enrollment.plan']);

        return view('financial.show', ['transaction' => $financial]);
    }

    public function edit(FinancialTransaction $financial): View
    {
        return view('financial.edit', ['transaction' => $financial]);
    }

    public function update(UpdateFinancialTransactionRequest $request, FinancialTransaction $financial): RedirectResponse
    {
        $data = $request->validated();
        $financial->update($data + ['paid_at' => $data['status'] === 'paid' ? now() : null]);
        ActivityLog::record('updated', $financial, 'Lançamento financeiro atualizado.');

        return redirect()->route('financial.show', $financial)->with('success', 'Lançamento atualizado com sucesso.');
    }

    public function destroy(FinancialTransaction $financial): RedirectResponse
    {
        $financial->update(['status' => 'cancelled']);
        ActivityLog::record('updated', $financial, 'Lançamento financeiro cancelado.');

        return redirect()->route('financial.index')->with('success', 'Lançamento cancelado com sucesso.');
    }
}
