<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUnitRequest;
use App\Http\Requests\UpdateUnitRequest;
use App\Models\ActivityLog;
use App\Models\Unit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UnitController extends Controller
{
    public function index(Request $request): View
    {
        $units = Unit::query()
            ->withCount('users')
            ->when($request->filled('search'), function ($query) use ($request): void {
                $search = $request->string('search')->toString();
                $query->where(function ($unitQuery) use ($search): void {
                    $unitQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%")
                        ->orWhere('city', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('active'), function ($query) use ($request): void {
                $query->where('active', $request->boolean('active'));
            })
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('units.index', compact('units'));
    }

    public function create(): View
    {
        return view('units.create', ['unit' => new Unit]);
    }

    public function store(StoreUnitRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $unit = Unit::create($validated);
        ActivityLog::record('created', $unit, 'Unidade criada.', ['attributes' => $unit->only(['name', 'code'])]);

        return redirect()->route('units.index')->with('success', 'Unidade cadastrada com sucesso.');
    }

    public function show(Unit $unit): View
    {
        $unit->loadCount('users');

        return view('units.show', compact('unit'));
    }

    public function edit(Unit $unit): View
    {
        return view('units.edit', compact('unit'));
    }

    public function update(UpdateUnitRequest $request, Unit $unit): RedirectResponse
    {
        $before = $unit->only(['name', 'code', 'active']);
        $unit->update($request->validated());
        ActivityLog::record('updated', $unit, 'Unidade atualizada.', ['before' => $before, 'after' => $unit->only(['name', 'code', 'active'])]);

        return redirect()->route('units.show', $unit)->with('success', 'Unidade atualizada com sucesso.');
    }

    public function destroy(Unit $unit): RedirectResponse
    {
        if ($unit->users()->exists() || $unit->managers()->exists()) {
            return redirect()->route('units.index')->with('error', 'Não é possível excluir uma unidade vinculada a usuários ou managers.');
        }

        $attributes = $unit->only(['name', 'code']);
        $unit->delete();
        ActivityLog::record('deleted', $unit, 'Unidade excluída.', ['attributes' => $attributes]);

        return redirect()->route('units.index')->with('success', 'Unidade excluída com sucesso.');
    }
}
