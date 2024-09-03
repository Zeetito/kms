<?php

namespace App\Models;

use App\Models\Role;
use App\Models\User;
use App\Models\AcademicYear;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserRole extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'role_id', 'academic_year_id'];
    
    // RELATIONSHIPS
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    

}
