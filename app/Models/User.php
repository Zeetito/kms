<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Role;
use App\Models\Image;
use App\Models\Record;
use App\Models\Report;
use App\Models\College;
// use App\Models\UserSemester;
use App\Models\Semester;
use App\Models\UserRole;
use App\Models\Residence;
use App\Models\UserProgram;
use App\Models\SemesterUser;
use Illuminate\Http\Request;
use App\Models\UserResidence;
use Laravel\Sanctum\HasApiTokens;
use App\Http\Resources\UserResource;
use App\Http\Resources\ProfileResource;
use App\Http\Resources\BirthdayResource;
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
        'dob',
        'contacts',
        'is_alumni',
        'is_member',
        'is_baptised',
        'is_worker',
        'is_student',
        'is_knust_affiliate',
        'local_congregation',
        'password'
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
        // 'email' => 'email',
        'contacts' => 'json',
        'local_congregation' => 'json',
        'email_verified_at' => 'datetime',
        // 'dob' => 'date',
        'password' => 'hashed',
    ];


    protected $appends = [
        'status',
        'account_status',
        'fullname',
        'role',
        'role_level',
        'semester_id',
        // 'zone_id'
    ];

    // Override the toArray method
    public function toArray()
    {
        // Use UserResource to transform the model's array
        return (new UserResource($this))->resolve();
    }

    // Override the toJson method
    public function toJson($options = 0)
    {
        // Use UserResource to transform the model's JSON representation
        return (new UserResource($this))->toJson($options);
    }


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

        // Get name attribute
        public function getNameAttribute(){
            return $this->firstname." ".$this->othername." ".$this->lastname;
        }

        // Get Role Attribute
        public function getRoleAttribute(){
            if($this->roles()->count() > 0){
                return $this->roles()->first()->slug;
                
            }else{
                return "none";
            }
        }

        // get All Roles Attribute
        public function getAllRolesAttribute(){
            if($this->roles()->count() > 0){
                $roles = [];
                foreach($this->roles()->get() as $role){
                   $roles[] = $role->slug; 
                }
                return $roles;
                
            }else{
                return "none";
            }
        }

        // Get Role Level Attribute
        public function getRoleLevelAttribute(){
            if($this->roles()->count() > 0){
                return $this->roles()->pluck('level')->max();
                
            }else{
                return 0;
            }
        }

        // Get User Semester Id
        public function getSemesterIdAttribute()
        {
            return $this->user_semester()->id;
        }

        // Get zoneId Atttribute
        public function getZoneIdAttribute()
        {
            return $this->zone() ? $this->zone()->id : null;
        }


    // RELATIONSHIPS

    // Images
    public function images(){
        return $this->morphMany(Image::class,'imageable');
    }

    // Get profile picture
    public function profile_pic(){
        return $this->images()->where('is_profile_pic',1)->first();
    }

    // User Role instances
    public function user_roles(){
        return $this->hasMany(UserRole::class,'user_id')->where('academic_year_id',Semester::active_semester()->academic_year_id);
    }

    // User Roles
    public function roles(){
        return Role::whereIn('id',$this->user_roles->pluck('role_id'));
    }

    // // User Semester
    public function semester_user(){
        return $this->hasOne(SemesterUser::class);
    }

    // User Residences
    public function user_residences(){
        return $this->hasMany(UserResidence::class);
    }

    // Get User Residence
    public function user_residence(){
        return $this->user_residences->first();
    }

    // Get related residence
    public function residence()
    {
       
        return $this->user_residences->first() ? $this->user_residences->first()->residence : null;
    }

    // Get user Residence Note
    public function residence_note(){
        // Check if the user has a registered instance for residence for the sem
        $registered = $this->user_residences->first();

        if($registered){
            // Check if the instance has a registered residence
            if($registered->residence){
                $data = [
                    "id" => $registered->residence->id,
                    "name" => $registered->residence->name,
                    "is_custom" => false,
                    // "custom_id" => null,
                    // "custom_name" => null,
                    // "custom_description" => null
                ];
                // return json_decode(json_encode($data),true);
                
                
            }else{

                $data = [
                    // "id" => null,
                    // "name" => null,
                    "is_custom" => true,
                    "custom_id" => $registered->id,
                    "custom_name" => $registered->custom_name,
                    "custom_description" => $registered->custom_description
                ];
                // return json_decode(json_encode($data),true);
                
                
            }

            // add the room of the individual
            $data["room"] = $registered->room;
            $data["floor"] = $registered->floor;
            $data["block"] = $registered->block;

            return json_decode(json_encode($data),true);
            

        }else{
            return null;
        }


    }

    // User Programs
    public function user_programs(){
        return $this->hasMany(UserProgram::class);
    }

    // Get zone
    public function zone()
    {
        return $this->residence() ? $this->residence()->zone : null;
    }
    // Get Zone Note
    public function zone_note(){
        // Check if the user has a registered instance for residence for the sem
        $registered = $this->user_residences->first();

        if($registered){
            // Check if the instance has a registered residence
            if($registered->residence){
                $data = [
                    "id" => $registered->residence->zone_id,
                    "name" => $registered->residence->zone->name,
                ];
                
                
            }else{

                $data = [
                    "id" => $registered->custom_zone_id ? $registered->custom_zone_id : null,
                    "name" => $registered->custom_zone_id ? Zone::find($registered->custom_zone_id)->name : "Others",
                ];
                
                
            }



            return json_decode(json_encode($data),true);
            

        }else{
            return null;
        }


    }

    

    // Get Program
    public function program()
    {
    
        $instance = $this->user_programs->first() ? $this->user_programs->first()->program : null;

        if($instance){
            return $instance;
        }
        // else{

        //     // Check if the user has a custom program
        //     $custom = $this->user_programs->first();
        //     if($custom && $custom->custom_name != null){
        //         $program = new Program;
        //         $program->id = "none";
        //         $program->name = $custom->custom_name;
        //         $program->college_id = null;

        //         return $program;
        //     }else{
        //         return null;
        //     }
            
        // }
    }

    // Get program note
    public function program_note(){
        // Check if the user has a registered instance for Program for the sem
        $registered = $this->user_programs->first();

        if($registered){
            // Check if the instance has a registered program
            if($registered->program){
                $data = [
                    "is_custom" => false,
                    "id" => $registered->program->id,
                    "name" => $registered->program->name,
                ];
                
                
            }else{

                $data = [
                    "is_custom" => true,
                    "custom_id" => $registered->id,
                    "custom_name" => $registered->custom_name,
                    "custom_span" => $registered->custom_span
                ];
                
                
            }

            $data['student_year'] = $registered->year;

            return json_decode(json_encode($data),true);
            

        }else{
            return null;
        }


    }


    // Get College
    public function college()
    {
        return $this->program() ? $this->program()->college : null;
    }

    // Get College Note
    public function college_note(){
        // Check if the user has a registered instance for College for the sem
        $registered = $this->user_programs->first();

        if($registered){
            // Check if the instance has a registered program
            if($registered->program){
                $data = [
                    "id" => $registered->program->college_id,
                    "name" => $registered->program->college ? $registered->program->college->name : null,
                ];
                
            }else{
                $data = [
                    "id" => $registered->custom_college_id ? $registered->custom_college_id : null,
                    "custom_name" => $registered->custom_college_id ? College::find($registered->custom_college_id)->name : Null,
                ];
                
            }
            return json_decode(json_encode($data),true);
        }else{
            return null;
            }

    }

    // Report with morph relation
    public function reports()
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    // Get reports by a role
    public function reports_by($role_slug){
        $role = Role::where('slug',$role_slug)->first();
        return $this->reports()->where('createable_id',$role->id)->first();

    }

    // Records
    public function records()
    {
        return $this->morphMany(Record::class, 'createable');
    }
    

    // FUNCTION
    // User Semester - Current semester of user
    public function user_semester(){
        $instance = SemesterUser::where('user_id',$this->id)->first();

        if($instance){
            return $instance->semester;
        }else{
            return Semester::getActiveSemester();
        }
    }


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

    // Get male users
    public static function male_users(){
        return User::where('gender','m')->get();
    }
    
    // Get female users
    public static function female_users(){
        return User::where('gender','f')->get();
    }

    // Get male members
    public static function male_members(){
        return User::male_users()->where('is_member',1);
    }
    // Get female members
    public static function female_members(){
        return User::female_users()->where('is_member',1);
    }

    // Get female members


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

    // Get all baptised members
    public static function baptised_members(){
        return self::members()->where('is_baptised',1)->where('is_member',1);
    }

    // Get all unbaptised members
    public static function unbaptised_members(){
        return self::members()->where('is_baptised',0)->where('is_member',1);
    }


    // ROLES
    // Get Ministry Members for a specific year
    public static function ministry_members(){
        $users = User::all()->filter(function ($user) {
            return $user->role_level >= 2 && $user->role_level < 4;
        });

        return $users->values();
    }



    // PROFILE
    // Get user profile
    public function profile(){
        return (new ProfileResource($this)) ?? response->json(['message' => 'User has no profile'], 404); ; 
    }

    // Upcoming birthdays
    public static function upcoming_birthdays()
    {
        $today = now();  // Get current date as a Carbon instance
        $monthDayToday = $today->format('m-d'); // Get the month-day part of today's date
    
        // Query users whose birthday is after today in terms of month-day (ignoring year)
        $birthdays = User::whereNotNull('dob') // Ensure 'dob' is not null
                         ->whereRaw('DATE_FORMAT(dob, "%m-%d") >= ?', [$monthDayToday]) // Compare month-day part
                         ->get();
    
        // Return the resource collection
        return BirthdayResource::collection($birthdays);
    }

    // Get birthdays today
    public static function birthdays_today()
    {
        $today = now();  // Get current date as a Carbon instance
        $monthDayToday = $today->format('m-d'); // Get the month-day part of today's date
    
        // Query users whose birthday is today in terms of month-day (ignoring year)
        $birthdays = User::whereNotNull('dob') // Ensure 'dob' is not null
                         ->whereRaw('DATE_FORMAT(dob, "%m-%d") = ?', [$monthDayToday]) // Compare month-day part
                         ->get();
    
        // Return the resource collection    
        return BirthdayResource::collection($birthdays);
    }
    
    
}
