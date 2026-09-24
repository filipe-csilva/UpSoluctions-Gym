<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFinancialTransactionRequest;
use App\Models\ActivityLog;
use App\Models\Enrollment;
use App\Models\FinancialTransaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FinancialTransactionController extends Controller
{
    public function index(Request $request): View
    {
        $transactions = FinancialTransaction::with(['student.user', 'unit', 'enrollment'])->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')->toString()))->latest('due_date')->paginate(15)->withQueryString();

        return view('financial.index', compact('transactions'));
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

    public function update(Request $request, FinancialTransaction $financial): RedirectResponse
    {
        $financial->update(['status' => $request->string('status')->toString(), 'payment_method' => $request->input('payment_method'), 'paid_at' => $request->input('status') === 'paid' ? now() : null]);
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
