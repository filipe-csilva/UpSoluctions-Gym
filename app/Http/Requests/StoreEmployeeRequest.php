<?php

namespace App\Http\Requests;

use App\Rules\ValidCpf;
use App\Rules\ValidPhone;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $roles = $this->user()?->role?->value === 'admin'
            ? ['required', Rule::in(['admin', 'manager', 'financial'])]
            : ['required', Rule::in(['financial'])];

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email:rfc', 'max:255', 'unique:users,email'],
            'role' => $roles,
            'unit_id' => ['required', 'exists:units,id'],
            'cpf' => ['nullable', 'string', 'max:14', new ValidCpf, 'unique:employee_profiles,cpf'],
            'phone' => ['nullable', 'string', 'max:20', new ValidPhone],
            'gender' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'], 'number' => ['nullable', 'string', 'max:20'],
            'neighborhood' => ['nullable', 'string', 'max:100'], 'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'alpha', 'size:2'], 'zip_code' => ['nullable', 'regex:/^[0-9]{8}$/'],
            'active' => ['boolean'],
        ];
    }
}
