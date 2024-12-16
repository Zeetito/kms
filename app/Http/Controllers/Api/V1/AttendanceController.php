<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AttendanceController extends Controller
{
    

    // Get all attendances for the semester
    public function index(Request $request) {
        return response()->json(Attendance::all());
    }

    //Storing Attendance is done in the meeting controller

}
