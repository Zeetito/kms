<?php

namespace App\Models;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Residence;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Zone extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'boundaries',
    ];

    // users
    public function users()
    {
        return User::all()->filter(function ($user) {
            return $user->zone_id == $this->id; 
        })->values();

    }

    // Get Other Zone Members
    public function other_zone_members()
    {
        $users = User::members()->get()->filter(function ($user) {
            return $user->zone_note() ? $user->zone_note()["id"] == null : true;
        });
    
        return $users->values();
    }
    

    // residence
    // In Zone model
    public function residences()
    {
        return $this->hasMany(Residence::class);
    }
}
