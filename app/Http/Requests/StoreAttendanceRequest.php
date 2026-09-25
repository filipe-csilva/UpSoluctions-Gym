<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAttendanceRequest extends FormRequest
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
        return ['student_id' => ['required', 'exists:student_profiles,id'], 'unit_id' => ['required', 'exists:units,id'], 'date' => ['required', 'date'], 'entry_time' => ['required', 'date_format:H:i'], 'exit_time' => ['nullable', 'date_format:H:i', 'after:entry_time'], 'type' => ['required', 'in:regular,trial,visitor'], 'notes' => ['nullable', 'string']];
    }
}
