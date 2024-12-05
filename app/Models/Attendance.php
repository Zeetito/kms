<?php

namespace App\Models;

use App\Models\User;
// use App\Models\Scopes\SemesterScope;
use App\Models\Meeting;
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

}
