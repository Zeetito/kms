<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'meeting_type_id',
        'program_name',
        'description',
        'start_date',
        'end_date',
        'start_time',
        'is_special',
        'end_time',
        'venue',
        'location',
        'allows_question',

    ];
}
