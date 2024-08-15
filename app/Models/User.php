<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'password',
        'firstname',
        'othername',
        'lastname',
        'gender',
        'email',
        'is_member',
        'is_alumni',
        'is_knust_affiliate',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];



    // STATIC FUNCTIONS

    // Get all members
    public static function members(){
        return self::where('is_member','1');
    }

    // Get affiliate members
    public static function affiliate_members(){
        return self::where('is_knust_affiliate','1')->where('is_member','1');
    }

    // non affiliate members
    public static function non_affiliate_members(){
        return self::where('is_knust_affiliate','0')->where('is_member','1');
    }

    // Get all non_members
    public static function non_members(){
        return self::where('is_member','0');
    }

    // Geta all Alumni
    public static function alumni(){
        return self::where('is_alumni','1');
    } 

    // Get Alumni Members
    public static function alumni_members(){
        return self::where('is_alumni','1')->where('is_member','1');
    }

    // Get students
    public static function students(){
        return self::where('status','student');
    }

    // Get affiliate students
    public static function affiliate_students(){
        return self::where('is_knust_affiliate','1')->where('status','student');
    }

    // Get non affiliate students
    public static function non_affiliate_students(){
        return self::where('is_knust_affiliate','0')->where('status','student');
    }  





    // Get non students members
    public static function workers_members(){
        return self::where('status','Worker/Ns');
    }

}
