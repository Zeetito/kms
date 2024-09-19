<?php

namespace App\Models;

use App\Models\Meeting;
use App\Models\OfficiatingRole;
use Illuminate\Database\Eloquent\Model;
use App\Http\Resources\OfficiatorResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Officiator extends Model
{
    use HasFactory;

    protected $fillable = [
        'meeting_id',
        'officiating_role_id',
        'gender',
        'fullname',
        'email',
        'phone',
        'residence',
    ];

    // Override the toArray method
    public function toArray()
    {
        // Use OfficiatorResource to transform the model's array
        return (new OfficiatorResource($this))->resolve();
    }

    // Override the toJson method
    public function toJson($options = 0)
    {
        // Use OfficiatorResource to transform the model's JSON representation
        return (new OfficiatorResource($this))->toJson($options);
    }

    // RELATIONSHIPS
    // Officiating Role
    public function officiating_role()
    {
        return $this->belongsTo(OfficiatingRole::class);
    }

    // Meeting
    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }
}
