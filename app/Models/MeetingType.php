<?php

namespace App\Models;

use App\Models\Meeting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MeetingType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'is_special',
        'description',
    ];

    public function meetings()
    {
        return $this->hasMany(Meeting::class);
    }
}
