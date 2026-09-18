<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\ActivityLog;
use App\Models\EmployeeProfile;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    public function index(Request $request): View
    {
        $employees = User::query()
            ->with(['unit', 'employeeProfile'])
            ->whereIn('role', ['admin', 'manager', 'financial'])
            ->when($request->filled('search'), function ($query) use ($request): void {
                $search = $request->string('search')->toString();
                $query->where(function ($userQuery) use ($search): void {
                    $userQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('active'), fn ($query) => $query->where('active', $request->boolean('active')))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('employees.index', compact('employees'));
    }

    public function create(Request $request): View
    {
        $units = Unit::where('active', true)->orderBy('name')->get();

        return view('employees.create', compact('units'));
    }

    public function store(StoreEmployeeRequest $request): RedirectResponse
    {
        $data = $request->validated();
        abort_unless($request->user()->role?->value === 'admin' || in_array((int) $data['unit_id'], $request->user()->accessibleUnitIds(), true), 403);
        $employee = DB::transaction(function () use ($data): EmployeeProfile {
            $user = User::create(['name' => $data['name'], 'email' => $data['email'], 'password' => Str::random(64), 'unit_id' => $data['unit_id'], 'role' => UserRole::from($data['role']), 'active' => true]);

            return EmployeeProfile::create(['user_id' => $user->id, ...collect($data)->except(['name', 'email', 'role', 'unit_id', 'active'])->all()]);
        });
        ActivityLog::record('created', $employee, 'Funcionário cadastrado.');
        Password::sendResetLink(['email' => $data['email']]);

        return redirect()->route('employees.index')->with('success', 'Funcionário cadastrado. O link de senha foi enviado por e-mail.');
    }

    public function show(EmployeeProfile $employee): View
    {
        $employee->load('user.unit');

        return view('employees.show', compact('employee'));
    }

    public function edit(EmployeeProfile $employee): View
    {
        $employee->load('user');
        $units = Unit::where('active', true)->orderBy('name')->get();

        return view('employees.edit', compact('employee', 'units'));
    }

    public function update(UpdateEmployeeRequest $request, EmployeeProfile $employee): RedirectResponse
    {
        $data = $request->validated();
        abort_unless($request->user()->role?->value === 'admin' || in_array((int) $data['unit_id'], $request->user()->accessibleUnitIds(), true), 403);
        DB::transaction(function () use ($employee, $data): void {
            $employee->user->update(['name' => $data['name'], 'email' => $data['email'], 'role' => UserRole::from($data['role']), 'unit_id' => $data['unit_id'], 'active' => (bool) ($data['active'] ?? false)]);
            $employee->update(collect($data)->except(['name', 'email', 'role', 'unit_id', 'active'])->all());
        });
        ActivityLog::record('updated', $employee, 'Funcionário atualizado.');

        return redirect()->route('employees.show', $employee)->with('success', 'Funcionário atualizado com sucesso.');
    }

    public function destroy(EmployeeProfile $employee): RedirectResponse
    {
        abort_unless(request()->user()->role?->value === 'admin', 403);
        $employee->load('user');
        $employee->user->update(['active' => false]);
        $employee->user->delete();
        ActivityLog::record('deleted', $employee, 'Funcionário excluído logicamente.');

        return redirect()->route('employees.index')->with('success', 'Funcionário excluído com sucesso.');
    }
}
