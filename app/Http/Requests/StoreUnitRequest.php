<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUnitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'code' => ['required', 'string', 'max:20', 'unique:units,code'],
            'phone' => ['nullable', 'string', 'regex:/^[0-9]{10,11}$/'],
            'email' => ['nullable', 'email:rfc', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'number' => ['nullable', 'string', 'max:10'],
            'neighborhood' => ['nullable', 'string', 'max:100'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'alpha', 'size:2'],
            'zip_code' => ['nullable', 'regex:/^[0-9]{8}$/'],
            'active' => ['boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'code' => $this->filled('code') ? strtoupper(trim($this->string('code')->toString())) : null,
            'state' => $this->filled('state') ? strtoupper(trim($this->string('state')->toString())) : null,
            'phone' => $this->filled('phone') ? preg_replace('/\D/', '', $this->string('phone')->toString()) : null,
            'zip_code' => $this->filled('zip_code') ? preg_replace('/\D/', '', $this->string('zip_code')->toString()) : null,
            'active' => $this->boolean('active'),
        ]);
    }
}
