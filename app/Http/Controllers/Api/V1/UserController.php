<?php

namespace App\Http\Controllers\api\V1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    //
    // Get all users
    public function index(){
        $users = User::orderByDesc('firstname')->get();
        return response()->json($users);
    }

    // Show User
    public function show(Request $request, User $user){
        return response()->json($user);
    }
    
    // Register New User
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'othername' => 'nullable|string|max:255',
            'gender' => 'required|in:m,f',
            'email' => 'required|string|email|max:255|unique:users,email',
            'phone' => 'required|string|max:255',
            'is_member' => 'required|boolean',
            'is_worker' => 'required|boolean',
            'is_student' => 'required|boolean',
            'is_knust_affiliate' => 'required|boolean',
            'is_alumni' => 'required|boolean',
            'dob' => 'required|date',
            'password' => 'required|string|min:6|confirmed'
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        try {
            $user = User::create($request->all());
            return response()->json(['message' => 'User created successfully', 'user' => $user], 201);
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1062) {
                return response()->json(['error' => 'Duplicate entry detected for the user.'], 409);
            }
            return response()->json(['error' => 'Failed to create user due to database error: ' . $e->getMessage()], 500);
        }
    }

    // Update User
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'othername' => 'nullable|string|max:255',
            'gender' => 'required|in:m,f',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:255',
            'is_member' => 'required|boolean',
            'is_worker' => 'required|boolean',
            'is_student' => 'required|boolean',
            'is_knust_affiliate' => 'required|boolean',
            'is_alumni' => 'required|boolean',
            'dob' => 'required|date',
            'password' => 'sometimes|string|min:6|confirmed'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $user->update($request->all());
            return response()->json(['message' => 'User updated successfully', 'user' => $user], 200);
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1062) {
                return response()->json(['error' => 'Duplicate entry detected for the user.'], 409);
            }
            return response()->json(['error' => 'Failed to update user due to database error: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(User $user)
    {
        try {
            $user->delete();
            return response()->json(['message' => 'User deleted successfully'], 200);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Failed to delete user due to a database error: ' . $e->getMessage()], 500);
        }
    }



    // Update User Account
    public function user_account(User $user, Request $request){
        $user->is_active = $request->input('is_active');
        $user->save();
        return response()->json($user);
    }



}
