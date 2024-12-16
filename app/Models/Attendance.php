<?php

namespace App\Models;

use App\Models\User;
// use App\Models\Scopes\SemesterScope;
use App\Models\Meeting;
use App\Models\AttendanceUser;
use App\Models\Scopes\SemesterScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

// #[ScopedBy([SemesterScope::class])]
class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'meeting_id',
        'is_active',
        'user_id',//created_by
    ];

    // RELATIONSHIPS
    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class,'attendance_users');
    }

    // AttendanceUsers 
    public function attendance_users()
    {
        return $this->hasMany(AttendanceUser::class);
    }

    // Attendees
    public function attendees(){
        return $this->users()->where('is_present', true);
    }

    // Absentees
    public function absentees(){
        return $this->users()->where('is_present', false);
    }

    // Unmarked
    public function unmarked(){
        return User::members()->get()->diff($this->users);
    }

    // Guests
    public function guests(){
        return $this->attendance_users()->where('user_id', null)->get();
    }

    // FUNCTIONS
    // REgister absentees
    public function registerAbsentees(){
        $absentees = $this->unmarked();
        foreach($absentees as $absentee){
            $attendance_user = new AttendanceUser;
            $attendance_user->user_id = $absentee->id;
            $attendance_user->is_present = false;
            $attendance_user->attendance_id = $this->id;
            $attendance_user->save();
        }
    }

}
