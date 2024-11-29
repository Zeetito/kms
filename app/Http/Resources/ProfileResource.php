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
            'user_id' => $this->id,
            'firstname' => $this->firstname,
            'othername' => $this->othername,
            'lastname' => $this->lastname,
            'gender' => $this->gender,
            'email' => $this->email,

            'status' => $this->status,
            'role' => $this->role,
            'residence' => $this->residence() ? $this->residence()->name : null,
            'zone' => $this->zone() ? $this->zone()->name : null,

            'program' => $this->program() ? $this->program()->name : null,
            'college' => $this->college() ? $this->college()->name : null,
        ];
    }
}
