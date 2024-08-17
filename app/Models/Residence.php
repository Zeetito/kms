<?php

namespace App\Models;

use App\Models\Zone;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Residence extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'landmark',
        'description',
        'zone_id',
        'location',

    ];

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }
}
