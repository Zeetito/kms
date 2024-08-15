<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'meeting_id',
        'officiator_id',
        'body',
        'asked_by',
        'hidden',
        'is_answered',
    ];
}
