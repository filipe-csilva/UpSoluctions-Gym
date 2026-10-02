<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
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
            'cpf' => $this->cpf,
            'birth_date' => $this->birth_date?->toDateString(),
            'phone' => $this->phone,
            'gender' => $this->gender,
            'user' => new UserResource($this->whenLoaded('user')),
            'enrollments' => $this->whenLoaded('enrollments'),
            'workout_plans' => $this->whenLoaded('workoutPlans'),
            'attendances' => $this->whenLoaded('attendances'),
        ];
    }
}
