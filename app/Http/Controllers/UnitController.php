<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Unit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UnitController extends Controller
{
    public function index(): View
    {
        $units = Unit::query()
            ->withCount('users')
            ->orderBy('name')
            ->paginate(15);

        return view('units.index', compact('units'));
    }

    public function create(): View
    {
        return view('units.create', ['unit' => new Unit]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatedData($request);
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

    public function update(Request $request, Unit $unit): RedirectResponse
    {
        $before = $unit->only(['name', 'code', 'active']);
        $unit->update($this->validatedData($request, $unit));
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

    /**
     * @return array<string, mixed>
     */
    private function validatedData(Request $request, ?Unit $unit = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'code' => ['required', 'string', 'max:20', Rule::unique('units', 'code')->ignore($unit)],
            'phone' => ['nullable', 'string', 'max:11'],
            'email' => ['nullable', 'email:rfc', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'number' => ['nullable', 'string', 'max:10'],
            'neighborhood' => ['nullable', 'string', 'max:100'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'size:2'],
            'zip_code' => ['nullable', 'string', 'max:8'],
            'active' => ['boolean'],
        ]) + ['active' => $request->boolean('active')];
    }
}
