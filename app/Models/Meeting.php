<?php

namespace App\Models;

use App\Models\Attendance;
use App\Models\MeetingType;
use App\Models\Announcement;
use App\Models\Scopes\SemesterScope;
use App\Http\Resources\MeetingResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[ScopedBy([SemesterScope::class])]
class Meeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'meeting_type_id',
        'program_name',
        'description',
        'start_date',
        'end_date',
        'start_time',
        'is_special',
        'end_time',
        'venue',
        'location',
        'semester_id',
        'allows_question',

    ];

    
    // Override the toArray method
    public function toArray()
    {
        // Use MeetingResource to transform the model's array
        return (new MeetingResource($this))->resolve();
    }
    
    // Override the toJson method
    public function toJson($options = 0)
    {
        // Use MeetingResource to transform the model's JSON representation
        return (new MeetingResource($this))->toJson($options);
    }
    
    // ATTRIBUTES
    // getMeetingType Attribute
    public function getMeetingTypeSlugAttribute()
    {
        return $this->meeting_type ? $this->meeting_type->slug: null;
    }

    // RELATIONSHIOPS
        // Get related meeting_type
        public function meeting_type()
        {
            return $this->belongsTo(MeetingType::class);
        }

        // Annoucements
        public function announcements()
        {
            return $this->hasMany(Announcement::class);
        }

        // Attendance
        public function attendance(){
            return $this->hasOne(Attendance::class);
        }

    
    // FUNCTIONS
    // Get visible annoucments
    public function visible_announcements()
    {
        return $this->announcements->visible;
    }

    // Get announcements
    public function announcement_requests()
    {
        return $this->announcements->request;
    }

}
