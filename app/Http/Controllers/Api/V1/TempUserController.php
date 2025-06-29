<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Models\Zone;
use App\Models\Semester;
use App\Models\TempUser;
use App\Models\Attendance;
use App\Models\UserProgram;
use Illuminate\Http\Request;
use App\Models\UserResidence;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProfileResource;
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
            'attendance_id' => Attendance::active_sessions()->id ?? null
        ]);

        // Check if attendance session is active
        if(Attendance::active_sessions()->first()){
            return redirect()->route('active_attendance_session')->with('success', 'Temp User added successfully');
        }

        return redirect()->route('add.zone.user.view', ['zone' => Zone::find($request->zone_id)]) ->with('success', 'Temp User added successfully');

    }
    public function tempStore(Request $request) {

        // Validate Requests
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'contact' => 'nullable|string|max:255',
            'info' => 'nullable|json|',
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
            'attendance_id' => Attendance::active_sessions()->id ?? null
        ]);



        if($tempUser){

            // Check if the user is a visitor or not
            // Decode the info
            $userInfo = json_decode($tempUser->info);

            // Log::info([
            //     'info' => $userInfo,
            //     'status' => $userInfo->status
            // ]);

            if($userInfo->status == 'visitor'){
               'do some addition of visitors stuff here';
            }else{
                // Create New user
                $user = new User;
                // Get first and last names
                if (str_word_count($tempUser->name) == 1) {
                    $user->firstname = $tempUser->name;
                }else{
                    // Split the full name
                    $parts = explode(' ', trim($request->name));

                    // At least two parts?
                    if (count($parts) >= 2) {
                        $user->firstname = $parts[0];                     // First word
                        $user->lastname = $parts[count($parts) - 1];      // Last word
                    }
                }   
                
                $user->password = bcrypt('password');
                $user->active_contact  = $request->contact;
                $user->save();

                // Check Gender
                if($userInfo->gender == 'm'){
                    $user->gender = 'm';
                }else{
                    $user->gender = 'f';
                }

                // IsStudent
                if($userInfo->is_student == 'true'){
                    $user->is_student = 1;

                    // check if year is not null
                    if($userInfo->year != null){
                        $userProgram = new UserProgram;
                        $userProgram->user_id = $user->id;
                        $userProgram->year = $userInfo->year;
                        $userProgram->academic_year_id = Semester::active_semester()->academic_year_id;
                        $userProgram->save();
                    }   

                }else{
                    $user->is_student = 0;

                    // Check occupation type
                    if($userInfo->occupation_type == 'worker'){
                        $user->is_worker = 1;
                    }
                    if($userInfo->occupation_type == 'ns'){
                        $user->is_worker = 2;
                    }
                    if($userInfo->occupation_type == 'other'){
                        $user->is_worker = 3;
                    }
                }

                // Check for the Residence Stuff ong
                // if the Residence was left null... do nothing else... do somethign
                if($userInfo->residence!= null){
                    $userResidence = new UserResidence;
                    $userResidence->user_id = $user->id;
                    $userResidence->custom_name = $userInfo->residence;
                    $userResidence->custom_zone_id = 17;

                    $userResidence->academic_year_id = Semester::active_semester()->academic_year_id;
                    $userResidence->save();
                }else{

                }

                $user->save();                  
            }

            return response()->json([
                'status'=>true,
                'user_details' => new ProfileResource($user),
            ],200);
        }else{
            return response()->json([
                'status'=>false
            ],400);
        }

       

    }

    public function index() {
        return view('user.temp-user.temp-user', ['users' => TempUser::all()]);
        //  return redirect()->route('temp.users', ['users' => TempUser::all()]);
    }
}
