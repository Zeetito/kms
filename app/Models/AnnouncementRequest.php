<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnnouncementRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'announcement_id',
        'requestable_type',
        'requestable_id',
        'body',
        'is_noted',
    ];
}
