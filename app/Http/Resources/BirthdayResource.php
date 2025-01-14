<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BirthdayResource extends JsonResource
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
            'name_of_user' => $this->fullname,
            'birthday' => date('M d', strtotime($this->dob)),
            'contacts' => $this->contacts ?? "None",
            'zone' => $this->zone_note(),
            'program' => $this->program_note(),
            'days_left' => Carbon::today()->diffInDays(Carbon::parse($this->dob)),
    
        ];
    }
}
