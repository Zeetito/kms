<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class College extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    // User
    public function users()
    {
        // filter students having college_id as $this->id
        $response = User::students()->get()->filter(function ($user) {
            return $user->program() && $user->program()->college_id == $this->id;
        });

        return response()->json($response);
    }
}
