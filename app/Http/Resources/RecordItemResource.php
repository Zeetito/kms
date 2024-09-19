<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RecordItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'record_id' => $this->record_id,
            'user_id' => $this->user_id,
    
            // Include 'unit_cost' only if it's not null and the record's type is 'financial'
            'unit_cost' => $this->when($this->unit_cost !== null && $this->record->type == "financial", $this->unit_cost),
            
            // Include 'quantity' only if it's not null and the record's type is 'financial'
            'quantity' => $this->when($this->quantity !== null && $this->record->type == "financial", $this->quantity),
    
            // Always include 'info'
            'info' => $this->info,
            
            // Include 'total' only if 'value' is not null and the record's type is 'counting' or 'financial'
            'total' => $this->when($this->value !== null && ($this->record->type == "counting" || $this->record->type == "financial"), $this->value),
        ];
    }
    
}
