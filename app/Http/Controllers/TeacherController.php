<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Http\Requests\StoreTeacherRequest;
use App\Http\Requests\UpdateTeacherRequest;
use App\Models\ActivityLog;
use App\Models\TeacherProfile;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TeacherController extends Controller
{
    /**
     * Lista os instrutores.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $teachers = TeacherProfile::query()
            ->with(['user.unit'])
            ->whereHas('user')
            ->when($request->filled('search'), function ($query) use ($request): void {
                $search = $request->string('search')->toString();
                $query->where(function ($teacherQuery) use ($search): void {
                    $teacherQuery
                        ->where('cpf', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($userQuery) use ($search): void {
                            $userQuery->where(function ($userSearch) use ($search): void {
                                $userSearch->where('name', 'like', "%{$search}%")
                                    ->orWhere('email', 'like', "%{$search}%");
                            });
                        });
                });
            })
            ->when($request->filled('unit_id'), function ($query) use ($request): void {
                $query->whereHas('user', function ($userQuery) use ($request): void {
                    $userQuery->where('unit_id', $request->integer('unit_id'));
                });
            })
            ->when($request->filled('active'), function ($query) use ($request): void {
                $query->whereHas('user', function ($userQuery) use ($request): void {
                    $userQuery->where('active', $request->boolean('active'));
                });
            })
            ->when($user->role?->value === 'manager', function ($query) use ($user): void {
                $query->whereHas('user', function ($userQuery) use ($user): void {
                    $userQuery->whereIn('unit_id', $user->accessibleUnitIds());
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $units = Unit::query()
            ->where('active', true)
            ->when($user->role?->value === 'manager', function ($query) use ($user): void {
                $query->whereIn('id', $user->accessibleUnitIds());
            })
            ->orderBy('name')
            ->get();

        return view('teachers.index', compact('teachers', 'units'));
    }

    /**
     * Formulário de cadastro.
     */
    public function create(): View
    {
        $units = Unit::query()
            ->where('active', true)
            ->when(request()->user()->role?->value === 'manager', function ($query): void {
                $query->whereIn('id', request()->user()->accessibleUnitIds());
            })
            ->orderBy('name')
            ->get();

        return view('teachers.create', compact('units'));
    }

    public function show(TeacherProfile $teacher): View
    {
        $teacher->load('user.unit');

        abort_unless(
            request()->user()->role?->value === 'admin'
                || in_array((int) $teacher->user?->unit_id, request()->user()->accessibleUnitIds(), true),
            403,
        );

        return view('teachers.show', compact('teacher'));
    }

    public function store(StoreTeacherRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        abort_unless(
            $request->user()->role?->value === 'admin'
                || in_array((int) $validated['unit_id'], $request->user()->accessibleUnitIds(), true),
            403,
        );

        $teacher = DB::transaction(function () use ($validated): TeacherProfile {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Str::random(64),
                'unit_id' => $validated['unit_id'],
                'role' => UserRole::TEACHER,
            ]);

            return TeacherProfile::create([
                'user_id' => $user->id,
                'cpf' => $validated['cpf'],
                'birth_date' => $validated['birth_date'],
                'phone' => $validated['phone'],
                'active' => true,
                'gender' => $validated['gender'] ?? null,
                'address' => $validated['address'] ?? null,
                'number' => $validated['number'] ?? null,
                'neighborhood' => $validated['neighborhood'] ?? null,
                'city' => $validated['city'] ?? null,
                'state' => $validated['state'] ?? null,
                'zip_code' => $validated['zip_code'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);
        });

        ActivityLog::record('created', $teacher, 'Instrutor cadastrado.');
        $resetStatus = Password::sendResetLink(['email' => $validated['email']]);

        return redirect()->route('teachers.index')->with(
            $resetStatus === Password::RESET_LINK_SENT ? 'success' : 'warning',
            $resetStatus === Password::RESET_LINK_SENT
                ? 'Instrutor cadastrado. Um e-mail foi enviado para definição da senha.'
                : 'Instrutor cadastrado, mas não foi possível enviar o e-mail para definição da senha.',
        );
    }

    public function destroy(TeacherProfile $teacher): RedirectResponse
    {
        $teacher->load('user');
        $this->authorizeTeacherAccess($teacher);

        DB::transaction(function () use ($teacher): void {
            $teacher->user?->update(['active' => false]);
            $teacher->user?->delete();
        });

        ActivityLog::record('deleted', $teacher, 'Instrutor excluído logicamente.');

        return redirect()->route('teachers.index')->with('success', 'Instrutor excluído com sucesso.');
    }

    public function edit(TeacherProfile $teacher): View
    {
        $teacher->load('user');
        $this->authorizeTeacherAccess($teacher);
        $units = Unit::query()
            ->where('active', true)
            ->when(request()->user()->role?->value === 'manager', fn ($query) => $query->whereIn('id', request()->user()->accessibleUnitIds()))
            ->orderBy('name')
            ->get();

        return view('teachers.edit', compact('teacher', 'units'));
    }

    public function update(UpdateTeacherRequest $request, TeacherProfile $teacher): RedirectResponse
    {
        $validated = $request->validated();
        $teacher->load('user');
        $this->authorizeTeacherAccess($teacher);
        abort_unless(
            $request->user()->role?->value === 'admin'
                || in_array((int) $validated['unit_id'], $request->user()->accessibleUnitIds(), true),
            403,
        );

        DB::transaction(function () use ($teacher, $validated): void {
            $teacher->user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'unit_id' => $validated['unit_id'],
                'active' => (bool) ($validated['active'] ?? false),
            ]);
            $teacher->update(collect($validated)->except(['name', 'email', 'unit_id', 'active'])->all());
        });

        ActivityLog::record('updated', $teacher, 'Instrutor atualizado.');

        return redirect()->route('teachers.show', $teacher)->with('success', 'Instrutor atualizado com sucesso.');
    }

    private function authorizeTeacherAccess(TeacherProfile $teacher): void
    {
        abort_unless(
            request()->user()->role?->value === 'admin'
                || in_array((int) $teacher->user?->unit_id, request()->user()->accessibleUnitIds(), true),
            403,
        );
    }
}
