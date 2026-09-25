<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWorkoutPlanRequest extends FormRequest
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
        return ['student_id' => ['required', 'exists:student_profiles,id'], 'teacher_id' => ['required', 'exists:users,id'], 'name' => ['required', 'string', 'max:150'], 'description' => ['nullable', 'string'], 'start_date' => ['required', 'date'], 'end_date' => ['nullable', 'date', 'after_or_equal:start_date'], 'status' => ['required', 'in:active,completed,cancelled']];
    }
}
