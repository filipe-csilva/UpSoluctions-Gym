<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePlanRequest extends FormRequest
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
        return [
            'name' => ['required', 'string', 'max:100', 'unique:plans,name'],
            'description' => ['nullable', 'string'],
            'duration_months' => ['required', 'integer', 'min:1', 'max:120'],
            'installments' => ['required', 'integer', 'min:1', 'max:120'],
            'price' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'promotion_type' => ['nullable', 'in:percentage,fixed'],
            'promotion_value' => ['nullable', 'numeric', 'min:0', 'required_with:promotion_type', Rule::when($this->input('promotion_type') === 'percentage', ['max:100'])],
            'promotion_start_date' => ['nullable', 'date'],
            'promotion_end_date' => ['nullable', 'date', 'after_or_equal:promotion_start_date'],
            'active' => ['boolean'],
        ];
    }
}
