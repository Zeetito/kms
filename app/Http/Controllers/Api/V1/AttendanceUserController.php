<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Meeting;
use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Models\AttendanceUser;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;

class AttendanceUserController extends Controller
{
    // Store an attendance user sesssion
    public function store(Request $request, Meeting $meeting){
        $instance = $request->all();
        $instance['marked_by'] = auth()->id();
        $instance['attendance_id'] = $meeting->attendance->id;


        $save = new AttendanceUser($instance);

        if(AttendanceUser::where('attendance_id', $save->attendance_id)->where('user_id', '!=', null)->where('user_id', $save->user_id)->exists()){
            return response()->json(['message' => 'User already marked'], 500);
        }
        
        if($save->save()){
            return response()->json(['message' => 'Attendance Checked Successfully', 'attendanceUser' => $save], 201);
        }else{
            return response()->json(['error' => 'Failed to create attendance user session'], 500);
        }

    }

    // Update attendance user instance
    public function update(Request $request, AttendanceUser $attendance_user){
       
        $attendance_user->update($request->all());
        return response()->json(['message' => 'Attendance user session updated successfully', 'attendanceUser' => $attendance_user], 200);
    }

    // Attendees
    public function attendees(Request $request, Attendance $attendance){
        return response()->json($attendance->attendees, 200);
    }

    // Absentees
    public function absentees(Request $request, Attendance $attendance){
        return response()->json($attendance->absentees, 200);
    }

    // Unmarked
    public function unmarked(Request $request, Attendance $attendance){
        return response()->json($attendance->unmarked(), 200);
    }

    // Guests
    public function guests(Request $request, Attendance $attendance){
        return response()->json($attendance->guests(), 200);
    }

}
