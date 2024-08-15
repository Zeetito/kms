<?php

namespace App\Http\Controllers\api\V1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    //
    // Get all users
    public function index(){
        $users = User::orderByDesc('status')->get();
        return response()->json($users);
    }

    // Get all students
    public function students(){
        $students = User::students()->get();
        return response()->json($students);
    }
}
