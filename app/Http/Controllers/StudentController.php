<?php

namespace App\Http\Controllers;

use App\Models\StudentProfile;
use App\Models\Unit;
use App\Models\User;
use App\Rules\ValidCpf;
use App\Rules\ValidPhone;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class StudentController extends Controller
{
    /**
     * Lista os alunos.
     */
    public function index(): View
    {
        $user = request()->user();

        $students = StudentProfile::query()
            ->with(['user.unit'])
            ->when($user->role?->value === 'manager', function ($query) use ($user): void {
                $query->whereHas('user', function ($userQuery) use ($user): void {
                    $userQuery->whereIn('unit_id', $user->accessibleUnitIds());
                });
            })
            ->latest()
            ->paginate(15);

        return view('students.index', compact('students'));
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
        $this->authorizeStudentAccess($student);
        $student->load('user.unit');
        $units = Unit::query()
            ->where('active', true)
            ->when(request()->user()->role?->value === 'manager', function ($query): void {
                $query->whereIn('id', request()->user()->accessibleUnitIds());
            })
            ->orderBy('name')
            ->get();

        return view('students.edit', compact('student', 'units'));
    }

    public function update(Request $request, StudentProfile $student): RedirectResponse
    {
        $this->authorizeStudentAccess($student);
        $student->load('user');
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email:rfc', 'max:255', Rule::unique('users', 'email')->ignore($student->user_id)],
            'unit_id' => ['required', 'exists:units,id'],
            'cpf' => ['required', 'string', 'max:14', new ValidCpf, Rule::unique('student_profiles', 'cpf')->ignore($student->id)],
            'birth_date' => ['required', 'date', 'before:today'],
            'phone' => ['required', 'string', 'max:20', new ValidPhone],
            'gender' => ['nullable', 'string', 'max:20'],
            'andress' => ['nullable', 'string', 'max:255'],
            'number' => ['nullable', 'string', 'max:20'],
            'neighborhood' => ['nullable', 'string', 'max:100'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'size:2'],
            'zip_code' => ['nullable', 'string', 'max:8'],
            'emergency_contact' => ['nullable', 'string', 'max:150'],
            'emergency_phone' => ['nullable', 'string', 'max:20', new ValidPhone],
            'notes' => ['nullable', 'string'],
        ]);

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
            ]);
            $student->update(collect($validated)->except(['name', 'email', 'unit_id'])->all());
        });

        return redirect()->route('students.show', $student)->with('success', 'Aluno atualizado com sucesso.');
    }

    /**
     * Salva o aluno.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            // User
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email:rfc', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'unit_id' => ['required', 'exists:units,id'],

            // StudentProfile
            'cpf' => ['required', 'string', 'max:14', new ValidCpf, 'unique:student_profiles,cpf'],
            'birth_date' => ['required', 'date', 'before:today'],
            'phone' => ['required', 'string', 'max:20', new ValidPhone],
            'gender' => ['nullable', 'string', 'max:20'],
            'andress' => ['nullable', 'string', 'max:255'],
            'number' => ['nullable', 'string', 'max:20'],
            'neighborhood' => ['nullable', 'string', 'max:100'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'size:2'],
            'zip_code' => ['nullable', 'string', 'max:10'],
            'emergency_contact' => ['nullable', 'string', 'max:150'],
            'emergency_phone' => ['nullable', 'string', 'max:20', new ValidPhone],
            'notes' => ['nullable', 'string'],
        ]);

        abort_unless(
            $request->user()->role?->value === 'admin'
                || in_array((int) $validated['unit_id'], $request->user()->accessibleUnitIds(), true),
            403
        );

        DB::transaction(function () use ($validated) {

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'unit_id' => $validated['unit_id'],
                'role' => 'student',
            ]);

            StudentProfile::create([
                'user_id' => $user->id,
                'cpf' => $validated['cpf'],
                'birth_date' => $validated['birth_date'],
                'phone' => $validated['phone'],
                'gender' => $validated['gender'] ?? null,
                'andress' => $validated['andress'] ?? null,
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

        return redirect()
            ->route('students.index')
            ->with('success', 'Aluno cadastrado com sucesso.');
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
