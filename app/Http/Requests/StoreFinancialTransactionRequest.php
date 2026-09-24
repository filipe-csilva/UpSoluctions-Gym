<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFinancialTransactionRequest extends FormRequest
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
        return ['enrollment_id' => ['required', 'exists:enrollments,id'], 'description' => ['required', 'string', 'max:255'], 'amount' => ['required', 'numeric', 'min:0'], 'due_date' => ['required', 'date'], 'status' => ['required', 'in:pending,paid,overdue,cancelled'], 'payment_method' => ['nullable', 'string', 'max:30'], 'transaction_type' => ['required', 'in:income,expense'], 'notes' => ['nullable', 'string']];
    }
}
