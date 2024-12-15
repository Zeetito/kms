<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportRecordResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"=> $this->id,
            // "user_id"=> $this->user_id,
            "report_id"=> $this->report_id,
            "body"=> $this->body,
            // "path"=> $this->path,
            "position"=> $this->position,
            // "created_at"=> $this->created_at,
            "updated_at"=> $this->updated_at,
        ];
    }
}
