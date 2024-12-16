<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Meeting;
use App\Models\Semester;
use App\Models\Attendance;
use App\Models\MeetingType;
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
             'description' => 'nullable|string',
             'start_date' => 'required|date',
             'end_date' => 'nullable|date',
             'start_time' => 'nullable|date_format:H:i',
             'end_time' => 'nullable|date_format:H:i',
             'venue' => 'nullable|string|max:255',
             'location' => 'nullable|string',
         ]);
 
         if ($validator->fails()) {
             return response()->json(['errors' => $validator->errors()], 422);
         }
         
         $instance = $request->all();

        //  If program name is null, put there the name of the meeting type
         if ($instance['program_name'] == null) {
             $instance['program_name'] = MeetingType::find($request->meeting_type_id)->name;
         }

         $instance['semester_id'] = Semester::getActiveSemester()->id;
 
         try {
             $meeting = Meeting::create($instance);
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

    //  Start or End attendance
    public function startOrEndAttendance(Request $request, Meeting $meeting) {
        if($meeting->attendance){
            $attendance = $meeting->attendance;
        }else{
            $attendance = new Attendance;
            $attendance->meeting_id = $meeting->id;
            $attendance->user_id = auth()->id();
            $attendance->save();

        }

        $attendance->is_active = !$attendance->is_active;

        // Set Every other attendance to inactive
        Attendance::where('id', '!=', $attendance->id)->update(['is_active' => false]);

        // if the attedance is just being ended, then, register or absentees
        // Check if the meeting day is same as today, if it is, then, register or absentees
        if (!$attendance->is_active) {
            $attendance->registerAbsentees();
        }


        $attendance->save();

        return response()->json(
            [
                'message' => ($attendance->is_active ? 'Attendance is in session now' : 'Attendance session has ended'), 
                'attendance' => $attendance
            ]
        );
    }

     


}
