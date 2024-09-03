<?php

namespace App\Models;

use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'level'
    ];

    // RELATIONSHIPS

    // User Roles
    public function user_roles(){
        return $this->hasMany(UserRole::class,'role_id');
    }

    public function users(Request $request)
    {
        return User::whereIn('id',$this->user_roles->where('academic_year_id', getAcademicYearId($request))->pluck('user_id'))->get();
        // return $this->hasManyThrough(User::class, UserRole::class)->where('academic_year_id', getAcademicYearId($request));
    }
}
