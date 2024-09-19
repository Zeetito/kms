<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MeetingResource extends JsonResource
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
            'meeting_type_id' => $this->meeting_type_id,
            'program_name' => $this->program_name,
            'is_special' => $this->is_special,
            'allows_question' => $this->allows_question,
            'description' => $this->description,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'venue' => $this->venue,
            'location' => $this->location,
            'semester_id' => $this->semester_id,
            'meeting_type' => $this->meeting_type_slug,
        ];
    }
}
