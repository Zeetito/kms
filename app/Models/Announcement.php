<?php

namespace App\Models;

use App\Models\User;
use App\Models\Meeting;
use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\SemesterRangeScope;
use App\Http\Resources\AnnouncementResource;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[ScopedBy([SemesterRangeScope::class])]
class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'meeting_id',
        'body',
        'user_id',
        'is_public',
        'is_request',
        'createable_type',
        'createable_id',
    ];

    // Override the toArray method
    public function toArray()
    {
        // Use AnnouncementResource to transform the model's array
        return (new AnnouncementResource($this))->resolve();
    }

    // Override the toJson method
    public function toJson($options = 0)
    {
        // Use AnnouncementResource to transform the model's JSON representation
        return (new AnnouncementResource($this))->toJson($options);
    }

    // REALATIONSHIPS
    // Createable
    public function createable()
    {
        return $this->morphTo();
    }

    // Meeting
    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }

    // User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Get the type attribute
    public function getTypeAttribute(){
        if($this->meeting_id == null){
            return "General";
        }else{
            return "Meeting";
        }
    }

    // // STATIC QUERIES

    // public static function visible()
    // {
    //     // return $query->where('is_public', 1)->where('is_request', 0);
    //     self::where('is_public', 1)->where('is_request', 0)->get();
    // }

    // // Get Announcement Requests
    // public static function requests()
    // {
    //     // return $query->where('is_request', 1);
    //     self::where('is_request',true)->get();
    // }

    // // Get nonvisible announcements
    // public static function nonvisible()
    // {
    //     self::where('is_public', 0)->where('is_request', false)->get();
    // }





}
