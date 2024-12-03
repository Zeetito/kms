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
        
        if($save->save()){
            return response()->json(['message' => 'Attendance Checked Successfully', 'attendanceUser' => $save], 201);
        }else{
            return response()->json(['error' => 'Failed to create attendance user session'], 500);
        }

    }

    // Update attendance user instance
    public function update(Request $request, AttendanceUser $attendance_user){
       
        return response()->json(['message' => 'This route is a work in progress'], 200);
        // return response()->json(['message' => 'Attendance user session updated successfully', 'attendanceUser' => $attendanceUser], 200);
    }

}
