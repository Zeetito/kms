<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VisitResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            // Always ensure integers are integers
            'id' => (int) $this->id,
            'name' => (string) $this->name,
            'type' => (string) $this->type, // 'visitation','evangelism','fishing_out','others'
            'user_id' => (int) $this->user_id,
            'semester_id' => (int) $this->semester_id,

            // Optional metadata
            'created_at' => $this->created_at ? $this->created_at->toDateTimeString() : null,
            'updated_at' => $this->updated_at ? $this->updated_at->toDateTimeString() : null,

            // Optionally include related resources if loaded
            'user' => $this->whenLoaded('user', function () {
                return [
                    'id' => (int) $this->user->id,
                    'name' => $this->user->name ?? null,
                    // add more user fields if required
                ];
            }),

            'semester' => $this->whenLoaded('semester', function () {
                return [
                    'id' => (int) $this->semester->id,
                    'name' => $this->semester->name ?? null,
                ];
            }),
        ];
    }
}
