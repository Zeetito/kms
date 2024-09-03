<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Role;
use App\Models\Semester;
use App\Models\UserRole;
use App\Models\Residence;
use App\Models\UserSemester;
use Illuminate\Http\Request;
use App\Models\UserResidence;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'firstname',
        'othername',
        'lastname',
        'gender',
        'email',
        'is_alumni',
        'is_member',
        'is_worker',
        'is_student',
        'is_knust_affiliate',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'user_roles',
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


    protected $appends = [
        'status',
        'account_status',
        'fullname',
        'role',
        'role_level',
    ];

    // CUSTOM ATTRIBUTES
        // Get Status of user
        public function getStatusAttribute(){
            // Check for student
            if($this->is_student){
                return "student";
    
            // Check for NsPersonelle or Worker
            }elseif($this->is_worker != 0){
                // Check if Ns or Worker
                if($this->is_worker == 1){
                    return "Worker";
                }else{
                    return "Ns Personnel";
                }
            }elseif($this->is_member == 0 && $this->is_knust_affiliate == 1){
                return "Alumni";
            }else{
                return "Other";
            }
        }

        // Get fullname attribute
        public function getFullnameAttribute(){
            return $this->firstname." ".$this->lastname; 
        }
        
        // Get account Status attribute
        public function getAccountStatusAttribute(){
            if($this->is_active == 0){
                return "Inactive";
            }elseif($this->is_active == 2){
                return "Deactivated";
            }else{
                return "Active";
            }
        }

        // Get Role Attribute
        public function getRoleAttribute(){
            if($this->roles()->count() > 0){
                return $this->roles()->first()->slug;
                
            }else{
                return "none";
            }
        }

        // Get Role Level Attribute
        public function getRoleLevelAttribute(){
            if($this->roles()->count() > 0){
                return $this->roles()->first()->level;
                
            }else{
                return 0;
            }
        }


    // RELATIONSHIPS

    // User Role instances
    public function user_roles(){
        return $this->hasMany(UserRole::class,'user_id')->where('academic_year_id',Semester::active_semester()->academic_year_id);
    }

    // User Roles
    public function roles(){
        return Role::whereIn('id',$this->user_roles->pluck('role_id'));
    }

    // User Semester
    public function user_semester(){
        return $this->hasOne(UserSemester::class);
    }

    // User Residences
    public function user_residences(){
        return $this->hasMany(UserResidence::class);
    }

    // Get related residence
    public function residence()
    {
        // $id = $this->user_residences->sortByDesc('created_at')->first()->residence_id;

        // return Residence::find($id);

        return $this->belongsTo(Residence::class, UserResidence::class, 'user_id', 'residence_id');
    }

    // FUNCTION


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
        return self::where('is_student',1);
    }

    // Get affiliate students
    public static function affiliate_students(){
        return self::where('is_knust_affiliate','1')->where('is_student',1);
    }

    // Get non affiliate students
    public static function non_affiliate_students(){
        return self::where('is_knust_affiliate','0')->where('is_student',1);
    }  

    // Get workers
    public static function workers_members(){
        return self::where('is_worker',1);
    }

    // Account Status

    // Get inactive users
    public static function unverified(){
        return self::where('is_active',0);
    }
    
    // Get deactivated user accounts
    public static function deactivated(){
        return self::where('is_active',2);
    } 

    // Get all inactive user account
    public static function inactive(){
        return self::where('is_active','<>',1);
    } 

    // Get all active users
    public static function active(){
        return self::where('is_active',1);
    }


    // ROLES
    // Get Ministry Members for a specific year
    public static function ministry_members(){
        $users = User::all()->filter(function ($user) {
            return $user->role_level >= 2 && $user->role_level != 8;
        });

        return $users->values();
    }
}
