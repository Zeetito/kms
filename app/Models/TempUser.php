<?php

namespace App\Models;

use App\Models\Zone;
use App\Models\Attendance;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TempUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'contact',
        'info',
        'zone_id',
        'attendance_id',
    ];

    // protected $casts = [
    //     'info' => 'array',
    // ];

    // RELATIONSHIPS
    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }
    

}
