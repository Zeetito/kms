<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AnnouncementResource extends JsonResource
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
            'type' => $this->type,
            'meeting_id' => $this->meeting_id,
            'body' => $this->body,
            'seen_count' => $this->seens->count(),
            'from' => $this->createable->name ?? $createable->fullname ?? null,
            'user_id' => $this->user_id,
            'is_public' => $this->is_public,
            'is_request' => $this->is_request,
            'status' => $this->is_public && $this->is_request == 0? 'Visible' : ($this->is_request == 0 ? 'Hidden' : 'Request'),
            'creatable_type' => $this->createable_type,
            'creatable_id' => $this->createable_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
