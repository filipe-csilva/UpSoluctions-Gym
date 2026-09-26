<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreExerciseRequest extends FormRequest
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
        return ['name' => ['required', 'string', 'max:150', 'unique:exercises,name'], 'description' => ['nullable', 'string'], 'muscle_group' => ['required', 'string', 'max:100'], 'equipment' => ['nullable', 'string', 'max:100'], 'instructions' => ['nullable', 'string'], 'image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'], 'video_url' => ['nullable', 'url', 'max:500'], 'active' => ['boolean']];
    }
}
