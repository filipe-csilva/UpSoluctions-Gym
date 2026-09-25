<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePhysicalAssessmentRequest extends FormRequest
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
        return ['student_id' => ['required', 'exists:student_profiles,id'], 'teacher_id' => ['required', 'exists:users,id'], 'assessment_date' => ['required', 'date'], 'height' => ['required', 'numeric', 'min:0.5', 'max:3'], 'weight' => ['required', 'numeric', 'min:10', 'max:500'], 'body_fat' => ['nullable', 'numeric', 'min:0', 'max:100'], 'muscle_mass' => ['nullable', 'numeric', 'min:0'], 'bmi' => ['nullable', 'numeric', 'min:1'], 'waist' => ['nullable', 'numeric'], 'abdomen' => ['nullable', 'numeric'], 'hip' => ['nullable', 'numeric'], 'chest' => ['nullable', 'numeric'], 'arm' => ['nullable', 'numeric'], 'thigh' => ['nullable', 'numeric'], 'notes' => ['nullable', 'string']];
    }
}
