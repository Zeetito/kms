<?php

namespace App\Models;

use App\Models\User;
use App\Models\UserProgram;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Program extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'college_id',
        'faculty_id',
        'department_id',
        'type',
        'span',
    ];


    // ATTRIBUTES

    // RELATIONSHIPS
    // UserPrograms instances
    public function user_programs(){
        return $this->hasMany(UserProgram::class);
    }

    // Get Users
    public function users(){
        return User::whereIn('id',$this->user_programs->pluck('user_id'))->get();
    }

}
