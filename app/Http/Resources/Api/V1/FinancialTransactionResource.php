<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FinancialTransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'description' => $this->description,
            'amount' => (string) $this->amount,
            'due_date' => $this->due_date?->toDateString(),
            'paid_at' => $this->paid_at?->toISOString(),
            'status' => $this->status,
            'payment_method' => $this->payment_method,
            'transaction_type' => $this->transaction_type,
            'cost_classification' => $this->cost_classification,
            'student' => $this->whenLoaded('student', fn (): ?array => $this->student === null ? null : [
                'id' => $this->student->id,
                'name' => $this->student->user?->name,
            ]),
            'unit' => $this->whenLoaded('unit', fn (): ?array => $this->unit === null ? null : [
                'id' => $this->unit->id,
                'name' => $this->unit->name,
            ]),
        ];
    }
}
