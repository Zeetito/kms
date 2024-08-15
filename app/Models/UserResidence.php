<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserResidence extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'residence_id',
        'room',
        'floor',
        'block',
        'custom_name',
        'custom_description',
        'academic_year_id',
    ];
}
