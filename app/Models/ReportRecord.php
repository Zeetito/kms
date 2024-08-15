<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'body',
        'path',//(local path) (nullable)
        'position',
    ];
}
