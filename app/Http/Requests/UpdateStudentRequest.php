<?php

namespace App\Http\Requests;

use App\Enums\UserRole;
use App\Rules\ValidCpf;
use App\Rules\ValidPhone;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $student = $this->route('student');

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email:rfc', 'max:255', Rule::unique('users', 'email')->ignore($student?->user_id)],
            'unit_id' => ['required', 'exists:units,id'],
            'role' => [
                'required',
                Rule::in($this->user()?->role?->value === UserRole::ADMIN->value
                    ? array_map(static fn (UserRole $role): string => $role->value, UserRole::cases())
                    : [UserRole::STUDENT->value, UserRole::TEACHER->value]),
            ],
            'active' => ['boolean'],
            'cpf' => ['required', 'string', 'max:14', new ValidCpf, Rule::unique('student_profiles', 'cpf')->ignore($student?->id)],
            'birth_date' => ['required', 'date', 'before:today'],
            'phone' => ['required', 'string', 'max:20', new ValidPhone],
            'gender' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
            'number' => ['nullable', 'string', 'max:20'],
            'neighborhood' => ['nullable', 'string', 'max:100'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'alpha', 'size:2'],
            'zip_code' => ['nullable', 'regex:/^[0-9]{8}$/'],
            'emergency_contact' => ['nullable', 'string', 'max:150'],
            'emergency_phone' => ['nullable', 'string', 'max:20', new ValidPhone],
            'notes' => ['nullable', 'string'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'cpf' => $this->filled('cpf') ? preg_replace('/\D/', '', $this->string('cpf')->toString()) : null,
            'phone' => $this->filled('phone') ? preg_replace('/\D/', '', $this->string('phone')->toString()) : null,
            'emergency_phone' => $this->filled('emergency_phone') ? preg_replace('/\D/', '', $this->string('emergency_phone')->toString()) : null,
            'state' => $this->filled('state') ? strtoupper(trim($this->string('state')->toString())) : null,
            'zip_code' => $this->filled('zip_code') ? preg_replace('/\D/', '', $this->string('zip_code')->toString()) : null,
            'active' => $this->boolean('active'),
        ]);
    }
}
