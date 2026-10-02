<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
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
            'parent_id' => $this->parent_id,
            'subject' => $this->subject,
            'body' => $this->body,
            'audience' => $this->audience,
            'unit_id' => $this->unit_id,
            'sender' => $this->whenLoaded('sender', fn (): ?array => $this->sender === null ? null : ['id' => $this->sender->id, 'name' => $this->sender->name]),
            'recipient' => $this->whenLoaded('recipient', fn (): ?array => $this->recipient === null ? null : ['id' => $this->recipient->id, 'name' => $this->recipient->name]),
            'created_at' => $this->created_at?->toISOString(),
            'replies' => MessageResource::collection($this->whenLoaded('replies')),
        ];
    }
}
