<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Resources\VisitResource;
use Illuminate\Http\Resources\Json\JsonResource;

class VisitItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'visit_id' => $this->visit_id,
            'user_id' => $this->user_id,
            'body' => $this->body,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
            // Optional: include relations
            'visit' => new VisitResource($this->whenLoaded('visit')),
            'user' => new UserResource($this->whenLoaded('user')),
        ];
    }
}
