<?php

namespace App\Models;

use App\Models\User;
use App\Models\Zone;
use App\Models\UserResidence;
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

    // Get userresidence instances
    public function user_residence()
    {
        return $this->hasMany(UserResidence::class);
    }

    // Get users
    public function users(){
        return User::whereIn('id',$this->user_residence->pluck('user_id'))->get();
    }
}
