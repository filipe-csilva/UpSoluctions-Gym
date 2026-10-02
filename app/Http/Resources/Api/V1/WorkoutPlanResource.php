<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkoutPlanResource extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'start_date' => $this->start_date?->toDateString(),
            'end_date' => $this->end_date?->toDateString(),
            'status' => $this->status,
            'student' => $this->whenLoaded('student', fn (): ?array => $this->student === null ? null : [
                'id' => $this->student->id,
                'name' => $this->student->user?->name,
            ]),
            'teacher' => $this->whenLoaded('teacher', fn (): ?array => $this->teacher === null ? null : [
                'id' => $this->teacher->id,
                'name' => $this->teacher->name,
            ]),
            'exercises' => $this->whenLoaded('exercises'),
        ];
    }
}
