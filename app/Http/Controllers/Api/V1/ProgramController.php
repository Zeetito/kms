<?php

namespace App\Http\Controllers\Api\V1;

use Exception;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ProgramController extends Controller
{

    // Index
    public function index()
    {
        return response()->json([
            'data' => Program::all()
        ]);
    }

    // Show
    public function show($program)
    {
        $program = Program::where('id', $program)->orWhere('name', $program)->first();

        if (!$program) {
            return response()->json([
                'message' => 'Program not found'
            ], 404);
        }
        
        return response()->json([
            'data' => $program
        ]);
    }

    // Store
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'department_id' => 'required|integer|exists:departments,id',
            'description' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        $program = Program::create($request->all());
        return response()->json([
            'message' => 'Program created successfully',
            'program' => $program
        ], 201);
    }
    

    // Update
    public function update(Request $request, Program $program){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'department_id' => 'required|integer|exists:departments,id',
            'description' => 'nullable|string'
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }
    
        $program->update($request->all());
        return response()->json([
            'message' => 'Program updated successfully',
            'program' => $program
        ], 200);
    }


    // Delete
    public function destroy(Program $program)
    {
        try {
            $program->delete();
            return response()->json([
                'message' => 'Program deleted successfully'
            ], 200);
        } catch (Exception $e) {
            Log::error("Error deleting program: " . $e->getMessage());
            return response()->json([
                'error' => 'Failed to delete the program due to an internal error.'
            ], 500);
        }
    }


}
