<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\MeetingType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;

class MeetingTypeController extends Controller
{

    // Index
    public function index()
    {
        return response()->json(
            MeetingType::all()
        );
    }

    // Show
    public function show(MeetingType $meeting_type)
    {
        return response()->json($meeting_type);
    }

    // Store
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:meeting_types,name',
            'slug' => 'nullable|string|max:255|unique:meeting_types,slug',
            'description' => 'nullable|string'
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        try {
            $meetingType = MeetingType::create($request->all());
            return response()->json(['message' => 'Meeting type created successfully', 'meetingType' => $meetingType], 201);
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1062) {
                return response()->json(['error' => 'Duplicate entry detected for the meeting type.'], 409);
            }
            return response()->json(['error' => 'Failed to create meeting type due to database error: ' . $e->getMessage()], 500);
        }
    }

    // Update
    public function update(Request $request, MeetingType $meetingType)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:meeting_types,name,' . $meetingType->id,
            'slug' => 'nullable|string|max:255|unique:meeting_types,slug,' . $meetingType->id,
            'description' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $meetingType->update($request->all());
            return response()->json(['message' => 'Meeting type updated successfully', 'meetingType' => $meetingType], 200);
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1062) {
                return response()->json(['error' => 'Duplicate entry detected for the meeting type.'], 409);
            }
            return response()->json(['error' => 'Failed to update meeting type due to database error: ' . $e->getMessage()], 500);
        }
    }

    // Delete
    public function destroy(MeetingType $meeting_type)
    {
        try {
            $meeting_type->delete();
            return response()->json(['message' => 'Meeting type deleted successfully'], 200);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Failed to delete meeting type due to a database error: ' . $e->getMessage()], 500);
        }
    }

}
