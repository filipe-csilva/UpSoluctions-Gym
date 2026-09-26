<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\ActivityLog;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $users = User::query()->with('unit')
            ->when($request->filled('search'), function ($query) use ($request): void {
                $search = $request->string('search')->toString();
                $query->where(fn ($userQuery) => $userQuery->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"));
            })
            ->when($request->filled('role'), fn ($query) => $query->where('role', $request->string('role')->toString()))
            ->latest()->paginate(20)->withQueryString();

        return view('users.index', compact('users'));
    }

    public function create(): View
    {
        return view('users.create', ['units' => Unit::query()->where('active', true)->orderBy('name')->get(), 'roles' => UserRole::cases()]);
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $user = User::create([
            ...collect($data)->except('password_confirmation')->all(),
            'password' => Hash::make($data['password']),
            'role' => UserRole::from($data['role']),
            'active' => (bool) ($data['active'] ?? true),
        ]);
        ActivityLog::record('created', $user, 'Usuário cadastrado pelo administrador.');

        return redirect()->route('users.index')->with('success', 'Usuário cadastrado com sucesso.');
    }

    public function show(User $user): View
    {
        return view('users.show', compact('user'));
    }

    public function edit(User $user): View
    {
        return view('users.edit', ['user' => $user, 'units' => Unit::query()->where('active', true)->orderBy('name')->get(), 'roles' => UserRole::cases()]);
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $data = $request->validated();
        $attributes = collect($data)->except(['password', 'password_confirmation'])->all();
        if (! empty($data['password'])) {
            $attributes['password'] = Hash::make($data['password']);
        }
        $attributes['role'] = UserRole::from($data['role']);
        $attributes['active'] = (bool) ($data['active'] ?? false);
        $user->update($attributes);
        ActivityLog::record('updated', $user, 'Usuário atualizado pelo administrador.');

        return redirect()->route('users.show', $user)->with('success', 'Usuário atualizado com sucesso.');
    }

    public function destroy(User $user): RedirectResponse
    {
        abort_if(request()->user()->is($user), 403, 'O administrador atual não pode excluir o próprio usuário.');
        $user->update(['active' => false]);
        $user->delete();
        ActivityLog::record('deleted', $user, 'Usuário excluído logicamente.');

        return redirect()->route('users.index')->with('success', 'Usuário excluído com sucesso.');
    }
}
