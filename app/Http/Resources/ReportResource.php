<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
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
            'type' => $this->type,
            'author' => $this->createable ? $this->createable->name : null,
            'user_id' => $this->user_id,
            'report_records' => $this->report_records,
            // 'semester_id' => $this->semester_id,
            // 'creatable_type' => $this->createable_type,
            // 'creatable_id' => $this->createable_id,
            'created_at' => $this->created_at,
            // 'updated_at' => $this->updated_at,
        ];
    }
}
