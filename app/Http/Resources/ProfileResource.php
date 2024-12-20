<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            // 'user_id' => $this->id,
            'firstname' => $this->firstname,
            'othername' => $this->othername,
            'lastname' => $this->lastname,
            'gender' => $this->gender,
            'email' => $this->email,
            'profile_pic_path' => $this->profile_pic()->path ?? null,
            'is_baptised' => $this->is_baptised,

            'dob' => $this->dob,

            'contacts' => $this->contacts,

            'status' => $this->status,
            'role' => $this->role,

            'residence' => $this->residence_note(),
            'zone' => $this->zone_note(),

            'program' => $this->program_note(),
            'college' => $this->college_note(),

            'local_congregation' => $this->local_congregation,
        ];
    }
}
