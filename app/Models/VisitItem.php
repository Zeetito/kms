<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'visit_id',
        'user_id',
        'body',

    ];
    
    protected $casts = [
        'body' => 'array',
    ];

    // Define relationships if needed
    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
}
