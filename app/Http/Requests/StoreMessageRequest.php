<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return in_array($this->user()?->role?->value, ['admin', 'manager', 'teacher', 'student'], true);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'audience' => ['required', 'in:all,unit,direct'],
            'recipient_id' => ['nullable', 'exists:users,id'],
            'unit_id' => ['nullable', 'exists:units,id'],
            'subject' => ['required', 'string', 'max:150'],
            'body' => ['required', 'string', 'max:10000'],
        ];
    }
}
