<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rep extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'repable_type',
        'repable_id',
        'academic_year_id',
    ];
}
