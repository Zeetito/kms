<?php

namespace App\Models;

use App\Models\User;
use App\Models\Semester;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserSemester extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'semester_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }
}
