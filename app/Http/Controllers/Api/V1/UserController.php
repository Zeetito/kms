<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Models\Semester;
use App\Models\UserProgram;
use Illuminate\Http\Request;
use App\Models\UserResidence;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use App\Jobs\UserRegisteredNotificationJob;
use App\Notifications\UserRegistrationNotification;

class UserController extends Controller
{
    //
    // Get all users
    public function index(){
        $users = User::orderByDesc('firstname')->get();
        return response()->json($users);
    }

    // Show User
    public function show(Request $request, User $user){
        return response()->json($user);
    }
    
    // Register New User
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'othername' => 'nullable|string|max:255',
            'gender' => 'required|in:m,f',
            'email' => 'required|string|email|max:255|unique:users,email',
            'contacts' => 'required|array',
            'is_member' => 'required|boolean',
            'is_worker' => 'required|boolean',
            'is_student' => 'required|boolean',
            'is_knust_affiliate' => 'required|boolean',
            'is_alumni' => 'required|boolean',
            'dob' => 'required|date',
            'password' => 'required|string|min:6|confirmed'
        ]);

    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $instance = $request->all();

        $instance['local_congregation'] == $request->local_congregation ?? null;

        // Create the Primary User Instance
        $user = User::create($instance);

        if($user){
            // disptach the notification job
            dispatch(new UserRegisteredNotificationJob($user))->onQueue('user_registered_notification');
            // $user->notify(new UserRegisteredNotification($user));

        }
        
        // Get the residence details for the member
        if($instance['is_member'] == true){

            // Check residence details were provieded
            if($request->has('residence')){

                $residence_details = $request->residence;

                $user_residence =  new UserResidence();
                $user_residence->user_id = $user->id; // user residence
                $user_residence->academic_year_id = Semester::active_semester()->academic_year_id;


                // if The residence is a registered one, treat it like so
                if($residence_details['is_custom'] == false){
                    $user_residence->residence_id = $residence_details['id'];
                }else{
                    // If it's a custom one treat it like so
                    $user_residence->custom_name = $residence_details['custom_name'] ?? null;
                    $user_residence->custom_description = $residence_details['custom_description'] ?? null;
                    $user_residence->custom_zone_id = $residence_details['custom_zone_id'] ?? null;
                }

                 // After all the checks and conditions, save the instance
                if (array_key_exists("room", $residence_details))  $user_residence->room = $residence_details['room'];
                if (array_key_exists("floor", $residence_details))  $user_residence->floor = $residence_details['floor'];
                if (array_key_exists("block", $residence_details))  $user_residence->block = $residence_details['block'];

            }

            // handle Program details if provided
            if($request->has('program')){
                
                $program_details = $request->program;

                $user_program = new UserProgram();
                $user_program->user_id = $user->id;
                $user_program->academic_year_id = Semester::active_semester()->academic_year_id;

                // Check if the Program is registered on the system
                if($program_details['is_custom'] == false){
                    $user_program->program_id = $program_details['id'];
                }else{
                    // If not, then it's a custom one
                    $user_program->custom_name = $program_details['custom_name'] ?? null;
                    $user_program->custom_college_id = $program_details['custom_college_id'] ?? null;
                    $user_program->custom_span = $program_details['custom_span'] ?? null;
                }

                $user_program->year = $program_details['year'] ?? null;
            }

            // Save Residence and Program
            $user_residence->save();
            $user_program->save();

        }else{
            // If the user is not a member, do this

        }




        // Return response with appropriate messages
        return response()->json([
            'message' => 'User Accounted Created successfully',
            'user' => $user,
            'member_note' => ($user->is_member && !(isset($user_residence))) ? "no_residence_info" : "complete_residence_info",
            'student_note' => ($user->is_member && $user->is_student && !(isset($user_program))) ? "no_program_info" : "complete_program_info",
        
        ], 200);
    
       
    }

    // Update User
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'is_member' => 'required|boolean',
            'is_worker' => 'required|boolean',
            'is_student' => 'required|boolean',
            'is_knust_affiliate' => 'required|boolean',
            'is_alumni' => 'required|boolean',
            'password' => 'string|min:6|confirmed'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $user->update($request->all());
            return response()->json(['message' => 'User updated successfully', 'user' => $user], 200);
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1062) {
                return response()->json(['error' => 'Duplicate entry detected for the user.'], 409);
            }
            return response()->json(['error' => 'Failed to update user due to database error: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(User $user)
    {
        try {
            $user->delete();
            return response()->json(['message' => 'User deleted successfully'], 200);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Failed to delete user due to a database error: ' . $e->getMessage()], 500);
        }
    }



    // Update User Account
    public function user_account(User $user, Request $request){
        $user->is_active = $request->input('is_active');
        $user->save();
        return response()->json($user);
    }



}
