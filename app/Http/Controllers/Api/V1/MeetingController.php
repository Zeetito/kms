<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Meeting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class MeetingController extends Controller
{
    // Index
    public function index(){
        $meetings = Meeting::all();
        return response()->json($meetings);
    }

    // Show
    public function show(Meeting $meeting){
        return response()->json($meeting);
    }

     // Store new meeting
     public function store(Request $request)
     {
         $validator = Validator::make($request->all(), [
             'meeting_type_id' => 'nullable|exists:meeting_types,id',
             'program_name' => 'nullable|string|max:255',
             'is_special' => 'boolean',
             'allows_question' => 'boolean',
             'description' => 'nullable|string',
             'start_date' => 'required|date',
             'end_date' => 'nullable|date',
             'start_time' => 'nullable|date_format:H:i',
             'end_time' => 'nullable|date_format:H:i',
             'venue' => 'nullable|string|max:255',
             'location' => 'nullable|string',
             'semester_id' => 'required|exists:semesters,id',
         ]);
 
         if ($validator->fails()) {
             return response()->json(['errors' => $validator->errors()], 422);
         }
 
         try {
             $meeting = Meeting::create($request->all());
             return response()->json(['message' => 'Meeting created successfully', 'meeting' => $meeting], 201);
         } catch (QueryException $e) {
             $errorCode = $e->errorInfo[1];
             if ($errorCode == 1062) {
                 return response()->json(['error' => 'Duplicate entry detected for the meeting.'], 409);
             }
             return response()->json(['error' => 'Failed to create meeting due to database error: ' . $e->getMessage()], 500);
         }
     }
 
     // Update existing meeting
     public function update(Request $request, Meeting $meeting)
     {
         $validator = Validator::make($request->all(), [
             'meeting_type_id' => 'nullable|exists:meeting_types,id',
             'program_name' => 'nullable|string|max:255',
             'is_special' => 'boolean',
             'allows_question' => 'boolean',
             'description' => 'nullable|string',
             'start_date' => 'required|date',
             'end_date' => 'nullable|date',
             'start_time' => 'nullable|date_format:H:i',
             'end_time' => 'nullable|date_format:H:i',
             'venue' => 'nullable|string|max:255',
             'location' => 'nullable|string',
             'semester_id' => 'required|exists:semesters,id',
         ]);
 
         if ($validator->fails()) {
             return response()->json(['errors' => $validator->errors()], 422);
         }
 
         try {
             $meeting->update($request->all());
             return response()->json(['message' => 'Meeting updated successfully', 'meeting' => $meeting], 200);
         } catch (QueryException $e) {
             $errorCode = $e->errorInfo[1];
             if ($errorCode == 1062) {
                 return response()->json(['error' => 'Duplicate entry detected for the meeting.'], 409);
             }
             return response()->json(['error' => 'Failed to update meeting due to database error: ' . $e->getMessage()], 500);
         }
     }
 
     // Delete existing meeting
     public function destroy(Meeting $meeting)
     {
         try {
             $meeting->delete();
             return response()->json(['message' => 'Meeting deleted successfully'], 200);
         } catch (QueryException $e) {
             return response()->json(['error' => 'Failed to delete meeting due to a database error: ' . $e->getMessage()], 500);
         }
     }

     


}
