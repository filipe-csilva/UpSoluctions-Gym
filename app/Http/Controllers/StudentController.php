<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\ActivityLog;
use App\Models\StudentProfile;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\View\View;

class StudentController extends Controller
{
    /**
     * Lista os alunos.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $students = StudentProfile::query()
            ->with(['user.unit'])
            ->whereHas('user')
            ->when($request->filled('search'), function ($query) use ($request): void {
                $search = $request->string('search')->toString();
                $query->where(function ($studentQuery) use ($search): void {
                    $studentQuery
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

        return view('students.index', compact('students', 'units'));
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

        return view('students.create', compact('units'));
    }

    public function show(StudentProfile $student): View
    {
        Gate::forUser(request()->user())->authorize('view', $student);
        $this->authorizeStudentAccess($student);
        $student->load('user.unit');

        return view('students.show', compact('student'));
    }

    public function myData(Request $request): View
    {
        $student = StudentProfile::query()
            ->with('user.unit')
            ->where('user_id', $request->user()->id)
            ->first();

        return view('students.show', [
            'student' => $student,
            'user' => $request->user()->load('unit'),
        ]);
    }

    public function edit(StudentProfile $student): View
    {
        Gate::forUser(request()->user())->authorize('update', $student);
        $this->authorizeStudentAccess($student);
        $student->load('user.unit');
        $roles = request()->user()->role?->value === UserRole::ADMIN->value
            ? UserRole::cases()
            : [UserRole::STUDENT, UserRole::TEACHER];
        $units = Unit::query()
            ->where('active', true)
            ->when(request()->user()->role?->value === 'manager', function ($query): void {
                $query->whereIn('id', request()->user()->accessibleUnitIds());
            })
            ->orderBy('name')
            ->get();

        return view('students.edit', compact('student', 'units', 'roles'));
    }

    public function update(UpdateStudentRequest $request, StudentProfile $student): RedirectResponse
    {
        Gate::forUser(request()->user())->authorize('update', $student);
        $this->authorizeStudentAccess($student);
        $student->load('user');
        $validated = $request->validated();
        abort_unless(
            $request->user()->role?->value === 'admin'
                || in_array((int) $validated['unit_id'], $request->user()->accessibleUnitIds(), true),
            403
        );

        DB::transaction(function () use ($student, $validated): void {
            $student->user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'unit_id' => $validated['unit_id'],
                'role' => UserRole::from($validated['role']),
                'active' => (bool) ($validated['active'] ?? false),
            ]);
            $student->update(collect($validated)->except(['name', 'email', 'unit_id', 'active'])->all());
        });
        ActivityLog::record('updated', $student, 'Aluno atualizado.', ['attributes' => $student->only(['user_id', 'cpf', 'phone'])]);

        return redirect()->route('students.show', $student)->with('success', 'Aluno atualizado com sucesso.');
    }

    /**
     * Salva o aluno.
     */
    public function store(StoreStudentRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        abort_unless(
            $request->user()->role?->value === 'admin'
                || in_array((int) $validated['unit_id'], $request->user()->accessibleUnitIds(), true),
            403
        );

        $student = DB::transaction(function () use ($validated): StudentProfile {

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Str::random(64),
                'unit_id' => $validated['unit_id'],
                'role' => UserRole::STUDENT,
            ]);

            return StudentProfile::create([
                'user_id' => $user->id,
                'cpf' => $validated['cpf'],
                'birth_date' => $validated['birth_date'],
                'phone' => $validated['phone'],
                'gender' => $validated['gender'] ?? null,
                'address' => $validated['address'] ?? null,
                'number' => $validated['number'] ?? null,
                'neighborhood' => $validated['neighborhood'] ?? null,
                'city' => $validated['city'] ?? null,
                'state' => isset($validated['state'])
                    ? strtoupper($validated['state'])
                    : null,
                'zip_code' => $validated['zip_code'] ?? null,
                'emergency_contact' => $validated['emergency_contact'] ?? null,
                'emergency_phone' => $validated['emergency_phone'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);
        });
        ActivityLog::record('created', $student, 'Aluno cadastrado.', ['attributes' => $student->only(['user_id', 'cpf'])]);

        $resetStatus = Password::sendResetLink(['email' => $validated['email']]);

        if ($resetStatus !== Password::RESET_LINK_SENT) {
            return redirect()
                ->route('students.index')
                ->with('warning', 'Aluno cadastrado, mas não foi possível enviar o e-mail para definição da senha.');
        }

        return redirect()
            ->route('students.index')
            ->with('success', 'Aluno cadastrado. Um e-mail foi enviado para definição da senha.');
    }

    public function destroy(StudentProfile $student): RedirectResponse
    {
        Gate::forUser(request()->user())->authorize('delete', $student);
        $this->authorizeStudentAccess($student);

        DB::transaction(function () use ($student): void {
            $student->load('user');
            $student->user?->update(['active' => false]);
            $student->user?->delete();
        });

        ActivityLog::record('deleted', $student, 'Aluno excluído logicamente.', ['deleted_at' => now()->toDateTimeString()]);

        return redirect()->route('students.index')->with('success', 'Aluno excluído com sucesso.');
    }

    private function authorizeStudentAccess(StudentProfile $student): void
    {
        $user = request()->user();

        abort_unless(
            $user->role?->value === 'admin'
                || in_array((int) $student->user()->value('unit_id'), $user->accessibleUnitIds(), true),
            403
        );
    }
}
