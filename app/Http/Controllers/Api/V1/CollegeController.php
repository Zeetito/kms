<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\College;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CollegeController extends Controller
{
    // Get users
    public function users(College $college){
        return $college->users();
    }
}
