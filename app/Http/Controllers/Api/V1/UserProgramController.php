<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\UserProgram;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;

class UserProgramController extends Controller
{

    // Index
    public function index()
    {
        return response()->json([
            'data' => UserProgram::all()
        ]);
    }

    // Show
    public function show(UserProgram $user_program)
    {
        return response()->json([
            'data' => $user_program
        ]);
    }

    // Store
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
            // 'academic_year_id' => 'required|integer|exists:academic_years,id',
            'program_id' => 'nullable|integer|exists:programs,id',
            'custom_name' => 'nullable|string|max:255',
            'year' => 'nullable|integer'
        ]);
    
        // Check if program Id is null
        if($request->program_id == null){
            // validate custom name
            $validator = Validator::make($request->all(), [
                'custom_name' => 'required|string|max:255',
            ]);
        }elseif($request->custom_name == null){
            // validate program id
            $validator = Validator::make($request->all(), [
                'program_id' => 'required|integer|exists:programs,id',
            ]);
        }
    
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }
    
        try {
            $user_program = UserProgram::create($request->all());
            return response()->json([
                'message' => 'User program created successfully',
                'userProgram' => $user_program
            ], 201);
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];  // Accessing the specific SQL error code from the error info array
            if ($errorCode == 1062) {
                return response()->json(['error' => 'Duplicate entry, the instance already exists.'], 409);
            }
            return response()->json(['error' => 'Failed to update instance due to database error: ' . $e->getMessage()], 500);
        }
        
    }

    // Update
    public function update(Request $request, UserProgram $user_program){

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
            // 'academic_year_id' => 'required|integer|exists:academic_years,id',
            'program_id' => 'nullable|integer|exists:programs,id',
            'custom_name' => 'nullable|string|max:255',
            'year' => 'nullable|integer'
        ]);

        $request->academic_year = AcademicYear::getActiveAcademicYear()->id;

        return $request;
    
        // Check if program Id is null
        if($request->program_id == null){
            // validate custom name
            $validator = Validator::make($request->all(), [
                'custom_name' => 'required|string|max:255',
            ], [
                'custom_name.required' => 'The custom name and Program field cannot be both empty. At least one field is required.',
                'custom_name.string' => 'The custom name must be a valid string.',
                'custom_name.max' => 'The custom name may not be greater than 255 characters.',
            ]);
        }elseif($request->custom_name == null){
            // validate program id
            $validator = Validator::make($request->all(), [
                'program_id' => 'required|integer|exists:programs,id',
            ]);
        }
    
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }
    
        try {
            $user_program->update($request->all());
            return response()->json([
                'message' => 'User program updated successfully',
                'userProgram' => $user_program
            ], 200);
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];  // Accessing the specific SQL error code from the error info array
            if ($errorCode == 1062) {
                return response()->json(['error' => 'Duplicate entry, the course already exists.'], 409);
            }
            return response()->json(['error' => 'Failed to update course due to database error: ' . $e->getMessage()], 500);
        }
    }

    // Delete
    public function destroy(UserProgram $user_program)
    {
        try {
            $user_program->delete();
            return response()->json([
                'message' => 'User program deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to delete the user program due to an internal error.'
            ], 500);
        }
    }
    

}
