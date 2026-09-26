<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentAttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->studentProfile !== null;
    }

    public function rules(): array
    {
        return [
            'unit_id' => ['required', 'exists:units,id'],
        ];
    }
}
