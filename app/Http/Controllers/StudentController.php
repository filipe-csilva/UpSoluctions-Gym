```php
<?php

namespace App\Http\Controllers;

use App\Models\StudentProfile;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class StudentController extends Controller
{
    /**
     * Lista os alunos.
     */
    public function index(): View
    {
        $students = StudentProfile::query()
            ->with(['user.unit'])
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
            ->orderBy('name')
            ->get();

        return view('students.create', compact('units'));
    }

    /**
     * Salva o aluno.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            // User
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'unit_id' => ['required', 'exists:units,id'],

            // StudentProfile
            'cpf' => ['required', 'string', 'max:14', 'unique:student_profiles,cpf'],
            'birth_date' => ['required', 'date', 'before:today'],
            'phone' => ['required', 'string', 'max:20'],
            'gender' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
            'number' => ['nullable', 'string', 'max:20'],
            'neighborhood' => ['nullable', 'string', 'max:100'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'size:2'],
            'zip_code' => ['nullable', 'string', 'max:10'],
            'emergency_contact' => ['nullable', 'string', 'max:150'],
            'emergency_phone' => ['nullable', 'string', 'max:20'],
            'notes' => ['nullable', 'string'],
        ]);

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

        return redirect()
            ->route('students.index')
            ->with('success', 'Aluno cadastrado com sucesso.');
    }
}
```
