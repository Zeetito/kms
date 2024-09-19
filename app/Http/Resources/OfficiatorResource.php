<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OfficiatorResource extends JsonResource
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
            'meeting_id' => $this->meeting_id,
            'officiating_role_id' => $this->officiating_role_id,
            'gender' => $this->gender,
            'fullname' => $this->fullname,
            'email' => $this->email,
            'phone' => $this->phone,
            'residence' => $this->residence,
            'role_name' => $this->officiating_role->slug,
            'created_at'   => $this->created_at,
            'updated_at'   => $this->updated_at,
        ];
    }
}
