<?php

namespace App\Http\Requests;

use App\Rules\ValidCpf;
use App\Rules\ValidPhone;
use Illuminate\Foundation\Http\FormRequest;

class StoreTeacherRequest extends FormRequest
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
            'cpf' => ['required', 'string', 'max:14', new ValidCpf, 'unique:teacher_profiles,cpf'],
            'birth_date' => ['required', 'date', 'before:today'],
            'phone' => ['required', 'string', 'max:20', new ValidPhone],
            'gender' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
            'number' => ['nullable', 'string', 'max:20'],
            'neighborhood' => ['nullable', 'string', 'max:100'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'alpha', 'size:2'],
            'zip_code' => ['nullable', 'regex:/^[0-9]{8}$/'],
            'notes' => ['nullable', 'string'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'cpf' => $this->filled('cpf') ? preg_replace('/\D/', '', $this->string('cpf')->toString()) : null,
            'phone' => $this->filled('phone') ? preg_replace('/\D/', '', $this->string('phone')->toString()) : null,
            'state' => $this->filled('state') ? strtoupper(trim($this->string('state')->toString())) : null,
            'zip_code' => $this->filled('zip_code') ? preg_replace('/\D/', '', $this->string('zip_code')->toString()) : null,
        ]);
    }
}
