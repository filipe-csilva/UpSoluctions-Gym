<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePlanRequest;
use App\Http\Requests\UpdatePlanRequest;
use App\Models\ActivityLog;
use App\Models\Plan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PlanController extends Controller
{
    public function index(Request $request): View
    {
        $plans = Plan::query()
            ->when($request->filled('search'), fn ($query) => $query->where('name', 'like', '%'.$request->string('search')->toString().'%'))
            ->when($request->filled('active'), fn ($query) => $query->where('active', $request->boolean('active')))
            ->orderBy('name')->paginate(15)->withQueryString();

        return view('plans.index', compact('plans'));
    }

    public function create(): View
    {
        return view('plans.create', ['plan' => new Plan(['active' => true, 'installments' => 1])]);
    }

    public function store(StorePlanRequest $request): RedirectResponse
    {
        $plan = Plan::create($request->validated());
        ActivityLog::record('created', $plan, 'Plano criado.');

        return redirect()->route('plans.show', $plan)->with('success', 'Plano cadastrado com sucesso.');
    }

    public function show(Plan $plan): View
    {
        return view('plans.show', compact('plan'));
    }

    public function edit(Plan $plan): View
    {
        return view('plans.edit', compact('plan'));
    }

    public function update(UpdatePlanRequest $request, Plan $plan): RedirectResponse
    {
        $before = $plan->only(['name', 'duration_months', 'price', 'active']);
        $plan->update($request->validated());
        ActivityLog::record('updated', $plan, 'Plano atualizado.', ['before' => $before, 'after' => $plan->only(['name', 'duration_months', 'price', 'active'])]);

        return redirect()->route('plans.show', $plan)->with('success', 'Plano atualizado com sucesso.');
    }

    public function destroy(Plan $plan): RedirectResponse
    {
        if ($plan->enrollments()->exists()) {
            return redirect()->route('plans.index')->with('error', 'Não é possível excluir um plano vinculado a matrículas.');
        }

        $plan->delete();
        ActivityLog::record('deleted', $plan, 'Plano excluído.');

        return redirect()->route('plans.index')->with('success', 'Plano excluído com sucesso.');
    }
}
