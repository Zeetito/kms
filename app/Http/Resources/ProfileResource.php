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
            'id' => $this->id,
            'firstname' => $this->firstname,
            'othername' => $this->othername,
            'lastname' => $this->lastname,
            'gender' => $this->gender,
            'email' => $this->email,
            'profile_pic_path' => $this->profile_pic()->path ?? null,
            'is_baptised' => $this->is_baptised == 1 ? true : false,

            'dob' => $this->dob,

            'contacts' => $this->contacts,
            'active_contact' => $this->active_contact,

            

            'status' => $this->status,
            // 'role' => $this->role,
            'role' => $this->all_roles,


            'residence' => $this->residence_note(),
            'zone' => $this->zone_note(),

            'status' => $this->status,

            'program' => $this->program_note(),
            'college' => $this->college_note(),

            'local_congregation' => $this->local_congregation,
        ];
    }
}
