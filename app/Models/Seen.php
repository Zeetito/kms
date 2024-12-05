<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seen extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'seenable_id',
        'seenable_type',
        'seen_at',
    ];

    public function seenable()
    {
        return $this->morphTo();
    }
}
