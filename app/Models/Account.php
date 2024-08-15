<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',// (expenditure,budget,income,counting)
        'createable_type',
        'createable_id',
        'user_id',
        'semester_id',
    ];
}
