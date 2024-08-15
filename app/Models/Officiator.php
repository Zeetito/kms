<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Officiator extends Model
{
    use HasFactory;

    protected $fillable = [
        'meeting_id',
        'officiating_role_id',
        'gender',
        'fullname',
        'email',
        'phone',
        'residence',
    ];
}
