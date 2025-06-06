<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'contacts' => $this->contacts,
            'active_contact' => $this->active_contact,
            
            'is_alumni' => $this->is_alumni,
            'is_member' => $this->is_member,
            'is_baptised' => $this->is_baptised,
            'is_worker' => $this->is_worker,
            'is_student' => $this->is_student,
            'is_knust_affiliate' => $this->is_knust_affiliate,
            'local_congregation' => $this->local_congregation,

            // 'zone' => $this->zone_note(),

            // -----

            
            // 'created_at' => $this->created_at,
            // 'updated_at' => $this->updated_at,

            'status' => $this->status,
            'account_status' => $this->account_status,
            'fullname' => $this->fullname,
            // 'role' => $this->role,
            'role' => $this->all_roles,

            'role_level' => $this->role_level,
            'semester_id' => $this->semester_id,

            // 'zone_id' => $this->zone_id,

        ];
    }
}
