<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Models\Semester;
use Illuminate\Http\Request;
use App\Models\UserResidence;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;

class UserResidenceController extends Controller
{

    // Index
    public function index(){
        return response()->json([
            'data' => UserResidence::all()
        ]);
    }

    // Store UserResidence

    public function store(User $user,Request $request){
        $validator = Validator::make($request->all(), [
            'residence_id' => 'required',
            'room' => 'nullable',
            'floor' => 'nullable',
            'block' => 'nullable',
            'custom_name' => 'nullable',
            'custom_description' => 'nullable',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
                'status' => 'failure'
            ], 422);
        }
    
        $instance = $request->all();
        $instance['user_id'] = $user->id;
        $instance['academic_year_id'] = Semester::active_semester()->academic_year_id;
    
        try {
            $userResidence = UserResidence::create($instance);
            return response()->json($userResidence->load('user','residence'));
        } catch (QueryException $e) {
            // Check for a duplicate key error code (23000 is common for SQL)
            if ($e->getCode() == 23000) {
                return response()->json([
                    'error' => 'This data already exists.'
                ], 409); // HTTP status code 409 Conflict
            } else {
                // Log other database errors
                Log::error('Database insertion error: ' . $e->getMessage());
                return response()->json([
                    'error' => 'Failed to store the data due to an unexpected error.'
                ], 500); // HTTP status code 500 Internal Server Error
            }
        }
    }

    // Update UserResidence
    public function update(Request $request, User $user){
        $user_residence = UserResidence::where('user_id', $user->id)->first();
        $validator = Validator::make($request->all(), [
            'residence_id' => 'required',
            'room' => 'nullable',
            'floor' => 'nullable',
            'block' => 'nullable',
            'custom_name' => 'nullable',
            'custom_description' => 'nullable',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
                'status' => 'failure'
            ], 422);
        }
    
        $instance = $request->all();
        $instance['academic_year_id'] = Semester::active_semester()->academic_year_id; // Assuming you want to update this field during update too
    
        try {
            $user_residence->update($instance);
            return response()->json($user_residence->load('user', 'residence'));
        } catch (QueryException $e) {
            // Check for a duplicate key error code (23000 is common for SQL)
            if ($e->getCode() == 23000) {
                return response()->json([
                    'error' => 'A unique constraint violation has occurred. Please ensure the data is unique.'
                ], 409); // HTTP status code 409 Conflict
            } else {
                // Log other database errors
                Log::error('Database update error: ' . $e->getMessage());
                return response()->json([
                    'error' => 'Failed to update the data due to an unexpected error.'
                ], 500); // HTTP status code 500 Internal Server Error
            }
        }
    }

    // Delete UserResidence
    public function destroy(User $user){
        $user_residence = UserResidence::where('user_id', $user->id)->first();
        $user_residence->delete();
        return response()->json([
            'message' => 'Delete successful',
            'status' => 'success'
        ], 200);
    }
}
