<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEnrollmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return ['plan_id' => ['required', 'exists:plans,id'], 'start_date' => ['required', 'date'], 'end_date' => ['required', 'date', 'after_or_equal:start_date'], 'price' => ['required', 'numeric', 'min:0'], 'status' => ['required', 'in:active,suspended,cancelled,expired'], 'payment_day' => ['required', 'integer', 'between:1,31'], 'notes' => ['nullable', 'string']];
    }
}
