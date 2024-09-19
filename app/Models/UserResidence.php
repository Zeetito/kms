<?php

namespace App\Models;

use App\Models\User;
use App\Models\Residence;
use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\AcademicYearScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[ScopedBy([AcademicYearScope::class])]
class UserResidence extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'residence_id',
        'room',
        'floor',
        'block',
        'custom_name',
        'custom_description',
        'academic_year_id',
    ];

    protected $appends = [
        // 'username',
        // 'residencename',
    ];

    // RELATIONSHIOPS
        // Get related user
        public function user()
        {
            return $this->belongsTo(User::class);
        }
    
        // Get related residence
        public function residence()
        {
            return $this->belongsTo(Residence::class);
        }

    // CUSTOM ATTRIBUTES
    // get name of user
    public function getUsernameAttribute(){
        return $this->user->fullname;
    }

    // get residence
    public function getResidencenameAttribute(){
        return $this->residence->name;
    }

}


