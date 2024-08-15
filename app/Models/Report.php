<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',//(ministry,visitation,feedback,munite)
        'semester_id',
        'createable_type',
        'createable_id',
        'user_id',
    ];
}
