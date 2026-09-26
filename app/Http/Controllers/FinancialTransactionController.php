<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Http\Requests\MarkFinancialTransactionPaidRequest;
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
        $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'from' => ['nullable', 'date_format:Y-m-d'],
            'to' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:from'],
            'transaction_type' => ['nullable', 'in:income,expense'],
            'status' => ['nullable', 'in:pending,paid,overdue,cancelled'],
        ]);
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

    public function cashFlow(Request $request): View
    {
        $request->validate([
            'from' => ['nullable', 'date_format:Y-m-d'],
            'to' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:from'],
        ]);
        $user = $request->user();
        $isManager = $user->role?->value === 'manager';
        $unitIds = $isManager ? $user->accessibleUnitIds() : [];
        $from = $request->filled('from') ? Carbon::parse($request->string('from')->toString())->startOfDay() : today()->startOfDay();
        $to = $request->filled('to') ? Carbon::parse($request->string('to')->toString())->endOfDay() : today()->endOfDay();
        $movements = FinancialTransaction::query()
            ->with(['student.user', 'unit'])
            ->where('status', 'paid')
            ->whereNotNull('paid_at')
            ->whereBetween('paid_at', [$from, $to])
            ->when($isManager, fn ($query) => $query->whereIn('unit_id', $unitIds))
            ->latest('paid_at')
            ->get();
        $income = (float) $movements->where('transaction_type', 'income')->sum('amount');
        $expenses = (float) $movements->where('transaction_type', 'expense')->sum('amount');
        $dailySummary = [];
        $cursor = $from->copy()->startOfDay();

        while ($cursor->lte($to)) {
            $date = $cursor->toDateString();
            $dayMovements = $movements->filter(fn (FinancialTransaction $transaction): bool => $transaction->paid_at?->toDateString() === $date);
            $dayIncome = (float) $dayMovements->where('transaction_type', 'income')->sum('amount');
            $dayExpenses = (float) $dayMovements->where('transaction_type', 'expense')->sum('amount');
            $dailySummary[] = [
                'date' => $cursor->copy(),
                'income' => $dayIncome,
                'expenses' => $dayExpenses,
                'balance' => $dayIncome - $dayExpenses,
            ];
            $cursor->addDay();
        }

        $summary = [
            'income' => $income,
            'expenses' => $expenses,
            'balance' => $income - $expenses,
            'movements' => $movements->count(),
        ];

        return view('financial.cash-flow', compact('movements', 'summary', 'dailySummary', 'from', 'to'));
    }

    public function studentIndex(Request $request): View
    {
        $student = $request->user()->studentProfile;
        abort_unless($student !== null, 403);

        $transactions = FinancialTransaction::query()->with(['unit', 'enrollment.plan'])->where('student_id', $student->id)->latest('due_date')->paginate(15)->withQueryString();
        $summary = [
            'total' => (clone $transactions->getCollection())->sum('amount'),
            'paid' => (clone $transactions->getCollection())->where('status', 'paid')->sum('amount'),
            'pending' => (clone $transactions->getCollection())->where('status', 'pending')->sum('amount'),
            'overdue' => (clone $transactions->getCollection())->filter(fn (FinancialTransaction $transaction): bool => $transaction->isOverdue())->sum('amount'),
        ];

        return view('financial.student-index', compact('transactions', 'summary'));
    }

    public function studentShow(Request $request, FinancialTransaction $financial): View
    {
        abort_unless($request->user()->studentProfile?->id === $financial->student_id, 403);
        $financial->load(['unit', 'enrollment.plan']);

        return view('financial.student-show', ['transaction' => $financial]);
    }

    public function create(): View
    {
        return view('financial.create', ['enrollments' => Enrollment::with(['student.user', 'plan'])->where('status', 'active')->get()]);
    }

    public function store(StoreFinancialTransactionRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $enrollment = Enrollment::findOrFail($data['enrollment_id']);
        $transaction = FinancialTransaction::create($data + [
            'student_id' => $enrollment->student_id,
            'unit_id' => $enrollment->unit_id,
            'paid_at' => $data['status'] === 'paid' ? now() : null,
        ]);
        ActivityLog::record('created', $transaction, 'Lançamento financeiro criado.');

        return redirect()->route('financial.index')->with('success', 'Lançamento financeiro criado com sucesso.');
    }

    public function show(FinancialTransaction $financial): View
    {
        $financial->load(['student.user', 'unit', 'enrollment.plan']);

        return view('financial.show', ['transaction' => $financial]);
    }

    public function receive(FinancialTransaction $financial): View
    {
        return view('financial.receive', ['transaction' => $financial]);
    }

    public function edit(FinancialTransaction $financial): View
    {
        abort_if($financial->enrollment_id !== null, 404);

        return view('financial.edit', ['transaction' => $financial]);
    }

    public function update(UpdateFinancialTransactionRequest $request, FinancialTransaction $financial): RedirectResponse
    {
        if ($financial->enrollment_id !== null) {
            return redirect()->route('financial.show', $financial)->with('error', 'Lançamento gerado pela matrícula não pode ser editado manualmente.');
        }

        $data = $request->validated();
        $financial->update($data + ['paid_at' => $data['status'] === 'paid' ? now() : null]);
        ActivityLog::record('updated', $financial, 'Lançamento financeiro atualizado.');

        return redirect()->route('financial.show', $financial)->with('success', 'Lançamento atualizado com sucesso.');
    }

    public function markPaid(MarkFinancialTransactionPaidRequest $request, FinancialTransaction $financial): RedirectResponse
    {
        $data = $request->validated();
        $financial->update([
            'status' => 'paid',
            'paid_at' => now(),
            'payment_method' => $data['payment_method'] ?? $financial->payment_method,
        ]);
        ActivityLog::record('updated', $financial, 'Pagamento registrado.');

        return redirect()->route('financial.show', $financial)->with('success', 'Pagamento registrado com sucesso.');
    }

    public function reversePayment(Request $request, FinancialTransaction $financial): RedirectResponse
    {
        abort_unless($request->user()->role === UserRole::ADMIN, 403);
        abort_unless($financial->status === 'paid', 422, 'Somente pagamentos confirmados podem ser estornados.');
        $data = $request->validate(['reason' => ['nullable', 'string', 'max:255']]);
        $financial->update([
            'status' => 'pending',
            'paid_at' => null,
            'notes' => trim(($financial->notes ? $financial->notes.' | ' : '').'Estorno: '.($data['reason'] ?? 'Sem motivo informado')),
        ]);
        ActivityLog::record('updated', $financial, 'Pagamento estornado pelo administrador.');

        return redirect()->route('financial.show', $financial)->with('success', 'Pagamento estornado com sucesso.');
    }

    public function destroy(FinancialTransaction $financial): RedirectResponse
    {
        $financial->update(['status' => 'cancelled']);
        ActivityLog::record('updated', $financial, 'Lançamento financeiro cancelado.');

        return redirect()->route('financial.index')->with('success', 'Lançamento cancelado com sucesso.');
    }
}
