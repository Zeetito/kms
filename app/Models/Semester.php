<?php

namespace App\Models;

use App\Models\Semester;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Semester extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_year_id',
        'name',
        'start_date',
        'end_date',
        'is_active',
    ];


    // STATIC FUCNTION
    // Get active semester
    public static function getActiveSemester(){
        return Semester::where('is_active',1)->first();
    }

    public static function active_semester(){
        return Semester::where('is_active',1)->first();
    }
}
