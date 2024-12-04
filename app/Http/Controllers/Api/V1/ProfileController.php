<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
 
    // Update Use profile
    public function update(Request $request, User $user){

        if($request->has('firstname')) $user->firstname = $request->firstname;
        if($request->has('othername')) $user->othername = $request->othername;
        if($request->has('lastname')) $user->lastname = $request->lastname;
        if($request->has('gender')) $user->gender = $request->gender;
        if($request->has('phone')) $user->contacts = $request->phone; // has to be json or something
        if($request->has('email')) $user->email = $request->email;
        if($request->has('dob')) $user->dob = $request->dob;
        if($request->has('local_congregation')) $user->local_congregation = $request->local_congregation;

        if($request->has('residence')){
            $user_residence = $user->user_residences->first();
            // If the residence coming is a registered one, do this
            if($request->residence['is_custom'] == false){
                $user_residence->residence_id = $request->residence['id'];
                    // Clear off any custom stuff if they existed for the instance
                    if($user_residence->custom_zone_id != null) $user_residence->custom_zone_id = null;
                    if($user_residence->custom_name != null) $user_residence->custom_name = null;
                    if($user_residence->custom_description != null) $user_residence->custom_description = null;

                
            }else{

                // else if it's a custom relationship,
                $user_residence->residence_id = null;
                $user_residence->custom_name = $request->residence['custom_name'];
                $user_residence->custom_description = $request->residence['custom_description'];
                $user_residence->custom_zone_id = $request->residence['custom_zone_id'];



            }
            // After all the checks and conditions, save the instance
            if (array_key_exists("room", $request->residence))  $user_residence->room = $request->residence['room'];
            if (array_key_exists("floor", $request->residence))  $user_residence->floor = $request->residence['floor'];
            if (array_key_exists("block", $request->residence))  $user_residence->block = $request->residence['block'];

            $user_residence->save();

        }

        if($request->has('program')){
            $user_program = $user->user_programs->first();
            // If the program coming is a registered one, do this
            if($request->program['is_custom'] == false){
                $user_program->program_id = $request->program['id'];
                if($user_program->custom_name != null) $user_program->custom_name = null;
                if($user_program->custom_college_id != null) $user_program->custom_college_id = null;
                if($user_program->custom_span != null) $user_program->custom_span = null;
            }else{
                // else if it's a custom relationship,
                $user_program->program_id = null;
                if (array_key_exists("custom_name", $request->program)) $user_program->custom_name =  $request->program['custom_name'];
                if (array_key_exists("custom_span", $request->program)) $user_program->custom_span =  $request->program['custom_span'];
                if (array_key_exists("custom_college_id", $request->program)) $user_program->custom_college_id =  $request->program['custom_college_id'];
            }
            if(array_key_exists("year", $request->program))  $user_program->year = $request->program['year'];

            $user_program->save();

        }

        $user->save();
        return response()->json([
            'status' => 'success',
            'user_profile' => $user->profile(),
        ], 200);

    }
}
