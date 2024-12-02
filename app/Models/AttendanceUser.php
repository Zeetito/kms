<?php

namespace App\Models;

use App\Models\User;
use App\Models\Attendance;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AttendanceUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendance_id',
        'user_id',
        'marked_by',
        'is_present',
        'guest_details',
        'info',
    ];

    protected $casts = [
        'guest_details' => 'json',
    ];

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
