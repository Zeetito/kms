<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Zone;
use App\Models\TempUser;
use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class TempUserController extends Controller
{
    // Store
    public function Store(Request $request) {
        // Validate Requests
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'contact' => 'nullable|string|max:255',
            'info' => 'nullable|string|max:255',
            'zone_id' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $tempUser = TempUser::create([
            'name' => $request->name,
            'contact' => $request->contact,
            'info' => $request->info,
            'zone_id' => $request->zone_id ?? 17,
            'attendance_id' => Attendance::active_sessions()->first()->id ?? null
        ]);

        // Check if attendance session is active
        if(Attendance::active_sessions()->first()){
            return redirect()->route('active_attendance_session')->with('success', 'Temp User added successfully');
        }

        return redirect()->route('add.zone.user.view', ['zone' => Zone::find($request->zone_id)]) ->with('success', 'Temp User added successfully');

    }

    public function index() {
        return view('user.temp-user.temp-user', ['users' => TempUser::all()]);
        //  return redirect()->route('temp.users', ['users' => TempUser::all()]);
    }
}
