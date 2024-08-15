<?php

namespace App\Http\Controllers\api\V1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    //
    // Get all users
    public function index(){
        $users = User::orderByDesc('status')->get();
        return response()->json($users);
    }
    
    // Register New User
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'firstname' => 'required',
            'lastname' => 'required',
            'othername' => 'nullable',
            'gender' => 'required',
            'is_member'=> 'required',
            'email' => 'required|email|unique:users',
            'is_alumni' => 'required',
            'is_knust_affiliate' => 'required',
            'status' => 'required',
            'dob'  => 'required',
            'password' => 'required',
          
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
                'status' => 'failure'
            ], 422);
        }
        $user = User::create($request->all());

        return response()->json($user);
    }

    // Update User
    public function update(Request $request, User $user){
        // $user = User::find($id);
        $user->update($request->all());
        return response()->json($user);
    }

}
