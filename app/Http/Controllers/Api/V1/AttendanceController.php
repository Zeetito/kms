<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Models\Meeting;
use App\Models\Semester;
use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Models\AttendanceUser;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\AttendanceResource;

class AttendanceController extends Controller
{
    

    // Get all attendances for the semester
    public function index(Request $request) {
        return response()->json([
            'data' => AttendanceResource::collection(Attendance::all()),
            'status' => 'success'
        ],200);
    }

    // Show
    public function show(Request $request, Attendance $attendance){
        return response()->json($attendance);
    }

    // Store
    public function store(Request $request) {
        // Get Meeting Title
        $title = $request->input('title');
        $venue = $request->input('venue') ?? 'Unity Hall Basement';
        
        # Create Meeting
        $meeting = Meeting::create([
            'meeting_type_id' => 1,
            'program_name' => $title,
            'start_date' => now(),
            'venue' => $venue,
            'location' => $venue,
            'semester_id' => Semester::getActiveSemester()->id
        ]);

        Attendance::where('is_active', true)->update(['is_active' => false]);

        # Create Attendance
        $attendance = Attendance::create([
            'meeting_id' => $meeting->id,
            'is_active' => true,
            'user_id' => 1,
        ]);

        return response()->json([
            'data' => $attendance,
            'status' => 'success'
        ],200);

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

    // Submit Attendance
    public function submit_attendance(Request $request)
    {
        $userIds = json_decode($request->input('user_ids'), true);

        if (!is_array($userIds)) {
            return back()->with('error', 'Invalid data submitted.');
        }

        $attendance = Attendance::where('is_active', true)->first();
        if (!$attendance) {
            return back()->with('error', 'No active attendance session found.');
        }

        $existing = AttendanceUser::where('attendance_id', $attendance->id)
            ->whereIn('user_id', $userIds)
            ->pluck('user_id')
            ->toArray();

        $newIds = array_diff($userIds, $existing); // Remove duplicates

        foreach ($newIds as $id) {
            $instance = new AttendanceUser;
            $instance->user_id = $id;
            $instance->is_present = true;
            $instance->attendance_id = $attendance->id;

            try {
                $instance->save();
            } catch (\Exception $e) {
                return back()->with('error', 'Error submitting attendance: ' . $e->getMessage());
            }
        }

        return back()->with('success', 'Attendance submitted successfully.');
    }

    // Submit Attendance Record
    public function submit_attendance_record(Request $request){
        
        // Set max execution time to 5 mins
        set_time_limit(300);
        
        
        $userIds =  is_array($request->input('user_ids')) ? $request->input('user_ids') : json_decode($request->input('user_ids'), true);
        
        if (!is_array($userIds)) {
            return back()->with('error', 'Invalid data submitted.');
        }
        
        $attendance = Attendance::where('is_active', true)->first();
        if (!$attendance) {
            return back()->with('error', 'No active attendance session found.');
        }
        // Log::info([
        //     'attendance' => $attendance
        // ]);
        // return "heyyeyey";

        $existing = AttendanceUser::where('attendance_id', $attendance->id)
            ->whereIn('user_id', $userIds)
            ->pluck('user_id')
            ->toArray();

        $newIds = array_diff($userIds, $existing); // Remove duplicates

        foreach ($newIds as $id) {
            $instance = new AttendanceUser;
            $instance->user_id = $id;
            $instance->is_present = true;
            $instance->attendance_id = $attendance->id;

            try {
                $instance->save();
            } catch (\Exception $e) {
                return back()->with('error', 'Error submitting attendance: ' . $e->getMessage());
            }
        }

        $attendees_id = $attendance->attendees()->pluck('user_id')->toArray();

        return response()->json([
            'attendees_id' => $attendees_id,  
            'message' => 'Attendance submitted successfully.',
            'status' => 'success'
        ],200);
    }

    Public function fetch_active_attendance_attendees(Request $request){
        $attendance = Attendance::active_sessions()->first();
        if (!$attendance) {
            return back()->with('error', 'No active attendance session found.');
        }
        $attendees_id = $attendance->attendees()->pluck('user_id')->toArray();
        return response()->json([
            'attendees_id' => $attendees_id,  
            // 'message' => 'Attendance submitted successfully.',
            'status' => 'success'
        ],200);
    }

    Public function get_attendance_details(Request $request, Attendance $attendance){
        // $attendance = Attendance::find(5);
        $attendees_id = $attendance->attendees()->pluck('user_id')->toArray();
        $target_users_id = User::where('created_at', '<=', $attendance->created_at)->pluck('id')->toArray();
        $absentees_id = array_diff($target_users_id, $attendees_id);
         $infos = AttendanceUser::where('attendance_id', $attendance->id)
            // ->whereIn('user_id', $attendees_id)
            ->pluck('info', 'user_id')
            ->toArray();
        return response()->json([
            'attendees_ids' => $attendees_id,  
            'absentees_ids' => $absentees_id,  
            'infos' => $infos,

            // 'message' => 'Attendance submitted successfully.',
            'status' => 'success'
        ],200);
    }

    public function update_attendance_details(Request $request, Attendance $attendance)
    {
        $updates = $request->input('updates');

        foreach ($updates as $update) {
            AttendanceUser::updateOrCreate(
                [
                    'attendance_id' => $attendance->id,
                    'user_id' => $update['user_id']
                ],
                [
                    'is_present' => $update['status'] == 'present' ? true : false,
                    'info' => $update['info'] ?? null,
                    // 'marked_by' => auth()->id(),
                ]
            );
        }

        $attendees_id = $attendance->attendees()->pluck('user_id')->toArray();
        $target_users_id = User::where('created_at', '<=', $attendance->created_at)->pluck('id')->toArray();
        $absentees_id = array_diff($target_users_id, $attendees_id);

        $infos = AttendanceUser::where('attendance_id', $attendance->id)
            // ->whereIn('user_id', $attendees_id)
            ->pluck('info', 'user_id')
            ->toArray();

        return response()->json([
            'attendees_ids' => array_values($attendees_id),
            'absentees_ids' => array_values($absentees_id),
            'infos' => $infos,
            'message' => 'Attendance updated successfully.',
            'status' => 'success'
        ], 200);
    }



}
