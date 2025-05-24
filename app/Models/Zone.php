<?php

namespace App\Models;
use App\Models\User;
use App\Models\Residence;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Zone extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'boundaries',
    ];

    // users
    // public function users()
    // {
    //     return User::all()->filter(function ($user) {
    //         return $user->zone_id == $this->id; 
    //     })->values();

    // }

    public function users()
    {
        $users = User::all()->filter(function ($user) {
            $zoneNote = $user->zone_note();

            return isset($zoneNote['id']) && $zoneNote['id'] == $this->id; 
        });

        return $users->values();
    }


    // Get Other Zone Members
    public function other_zone_members()
    {
        $users = User::members()->get()->filter(function ($user) {
            return $user->zone_note() ? $user->zone_note()["id"] == null : true;
        });
    
        return $users->values();
    }
    

    // residence
    // In Zone model
    public function residences()
    {
        return $this->hasMany(Residence::class);
    }

    // Get Absentees for the Active Attendance Session
    public function absentees(){
        #Get attendance Session
        $attendance = Attendance::active_sessions();
        # Get attedees
        $attendees = $attendance->attendees()->get();
        // Log::info($attendees);

        # Get zone attendees - Intersection of zone memebrs
        $zone_attendees = $attendees->intersect($this->users());


        # Get the diff between that and th zone members
        return $this->users()->diff($zone_attendees);
    }
}
