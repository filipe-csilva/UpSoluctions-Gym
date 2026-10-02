<?php

namespace App\Http\Requests;

use App\Rules\ValidCpf;
use App\Rules\ValidPhone;
use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email:rfc', 'max:255', 'unique:users,email'],
            'unit_id' => ['required', 'exists:units,id'],
            'active' => ['boolean'],
            'cpf' => ['required', 'string', 'max:14', new ValidCpf, 'unique:student_profiles,cpf'],
            'birth_date' => ['required', 'date', 'before:today'],
            'phone' => ['required', 'string', 'max:20', new ValidPhone],
            'gender' => ['nullable', 'string', 'in:male,female,other'],
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
            'gender' => $this->normalizeGender(),
            'active' => $this->boolean('active'),
        ]);
    }

    private function normalizeGender(): ?string
    {
        if (! $this->filled('gender')) {
            return null;
        }

        return match (mb_strtolower(trim($this->string('gender')->toString()))) {
            'm', 'masculino', 'male' => 'male',
            'f', 'feminino', 'female' => 'female',
            'outro', 'other' => 'other',
            default => $this->string('gender')->toString(),
        };
    }
}
