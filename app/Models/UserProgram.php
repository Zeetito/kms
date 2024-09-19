<?php

namespace App\Models;

use App\Models\User;
use App\Models\Program;
use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\AcademicYearScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[ScopedBy([AcademicYearScope::class])]
class UserProgram extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'program_id',
        'custom_name',
        'year',
        'academic_year_id',
    ];

    // RELATIONSHIPS

    // User
    public function user(){
        return $this->belongsTo(User::class);
    }

    // Program
    public function program(){
        return $this->belongsTo(Program::class);
    }

    
}
