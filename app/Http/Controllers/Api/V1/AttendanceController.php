<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Models\AttendanceUser;
use App\Http\Controllers\Controller;

class AttendanceController extends Controller
{
    

    // Get all attendances for the semester
    public function index(Request $request) {
        return response()->json(Attendance::all());
    }

    // Show
    public function show(Request $request, Attendance $attendance){
        return response()->json($attendance);
    }

    // Check attendance for ative session
    public function check_for_active(Request $request){
        $attendance = Attendance::where('is_active', true)->first();
        if($attendance){
            $instance = $request->all();
            $instance['marked_by'] = auth()->id();
            $instance['attendance_id'] = $attendance->id;


            $save = new AttendanceUser($instance);

            if(AttendanceUser::where('attendance_id', $save->attendance_id)->where('user_id', '!=', null)->where('user_id', $save->user_id)->exists()){
                return response()->json(['message' => 'User already marked'], 500);
            }
            
            if($save->save()){
                return response()->json(['message' => 'Attendance Checked Successfully', 'attendanceUser' => $save], 201);
            }else{
                return response()->json(['error' => 'Failed to create attendance user session'], 500);
            }


        }else{
            return response()->json(['message' => 'No active attendance session found'], 404);
        }
    }

    //Storing Attendance is done in the meeting controller

}
