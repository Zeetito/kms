<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicYear extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_year',
        'end_year',
        'is_active'
    ];


    // FUNCTIONS
    public function getActiveAcademicYear(){
        return Semester::getActiveSemester()->academic_year;
    }
}
