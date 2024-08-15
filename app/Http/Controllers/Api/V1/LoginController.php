<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    //
    // Login Function
    public function login(Request $request){
        // Validate the request
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
                'status' => 'failure'
            ], 422);
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Get the authenticated user
            $user = Auth::user();

            // Generate a token (assuming you are using Laravel Passport or Sanctum)
            $token = $user->createToken('API Token')->plainTextToken;

            return response()->json([
                'message' => 'Login successful',
                'status' => 'success',
                'token' => $token,
                'user' => $user
            ], 200);
        } else {
            return response()->json([
                'message' => 'Invalid Credentials',
                'status' => 'failure'
            ], 401);
        }
    }

     // Logout function
     public function logout(Request $request){
        // Get the current user's token
        $token = $request->user()->currentAccessToken();
         $token->delete();

         return response()->json([
             'message' => 'Logged out successfully',
             'status' => 'success'
         ], 200);
 }

}
