<?php

namespace App\Models;

use App\Models\User;
use App\Models\Record;
use App\Models\Report;
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

    // Report with morph relation
    public function reports()
    {
        return $this->morphMany(Report::class, 'createable');
    }

    // Records
    public function records()
    {
        return $this->morphMany(Record::class, 'createable');
    }


    // FUNCTIONS
    // Check if role has report for an instance whether user or meeting
    public function hasReport($instance, $type){
        return $this->reports()->where('reportable_type',  "App\\Models\\".ucfirst($type))->where('reportable_id', $instance->id)->exists();
    }

    // Return report for instance
    public function getReport($instance, $type){
        return $this->reports()->where('reportable_type',  "App\\Models\\".ucfirst($type))->where('reportable_id', $instance->id)->first();
    }

}
