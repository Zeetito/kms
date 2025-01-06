<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Models\Image;
use Intervention\Image\Facades\Image as FacadeImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

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
        if($request->has('is_baptised')) $user->is_baptised = $request->is_baptised;

        if($request->has('residence')  && empty(array_filter($request->residence)) == false){
            $user_residence = $user->user_residences->first();
            // If the residence coming is a registered one, do this
            if($request->residence['is_custom'] == false){
                $user_residence->residence_id = $request->residence['id'] ;
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

        // User is student? check for program
        // if($user->is_student == true){
            // Check if program input exists and has atleast one non_null value
            if($request->has('program') && empty(array_filter($request->program)) == false){
                $user_program = $user->user_programs->first();
                // If the program coming is a registered one, do this
                if($request->program['is_custom'] == false){
                    $user_program->program_id = $request->program['id'];
                    // if($user_program->custom_name != null) $user_program->custom_name = null;
                    // if($user_program->custom_college_id != null) $user_program->custom_college_id = null;
                    // if($user_program->custom_span != null) $user_program->custom_span = null;
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
        // }


        $user->save();
        return response()->json([
            'status' => 'success',
            'user_profile' => $user->profile(),
        ], 200);

    }

    // Update profile pic
    public function update_profile_pic(Request $request, User $user)
    {
        $request->validate([
            'profile_pic' => 'required|image',
        ]);
    
        // Handle the file upload
        if ($request->hasFile('profile_pic')) {
            $file = $request->file('profile_pic');
            $filename = time() . '.' . $file->getClientOriginalExtension();
    
            // Use the public disk (or configure as necessary)
            $disk = Storage::disk('public');
    
            // Temporary path within the "temp" directory in "storage/app/public"
            $tempPath = 'temp/' . $filename;
    
            // Start processing with Intervention Image
            $image = FacadeImage::make($file);
    
            // Save the image to the temporary location on the disk
            $image->save($disk->path($tempPath), 100);  // Save with best quality
    
            // Reduce size if greater than 2MB
            $quality = 100;  // Start with best quality
            while ($disk->size($tempPath) > 2048 * 1024 && $quality > 10) {
                $quality -= 10;  // Decrease quality by 10
                $image->save($disk->path($tempPath), $quality);
            }
    
            // Check file size and move to final destination
            if ($disk->size($tempPath) <= 2048 * 1024) {
                $finalPath = $disk->putFileAs('images', new \Illuminate\Http\File($disk->path($tempPath)), $filename);
    
                // Update or create new image record
                $profile_pic = $user->profile_pic() ?? new Image([
                    'imageable_id' => $user->id,
                    'imageable_type' => User::class,
                    'is_profile_pic' => true
                ]);
    
                // Delete existing profile pic if any
                if ($user->profile_pic()) {
                    $disk->delete($user->profile_pic()->path);
                }
    
                $profile_pic->path = $finalPath;
                $profile_pic->save();
    
                // Clean up the temporary file
                $disk->delete($tempPath);
    
                return response()->json(['message' => 'Updated profile picture successfully', 'path' => $finalPath]);
            } else {
                $disk->delete($tempPath);  // Clean up temporary file
                return response()->json(['message' => 'Unable to reduce file size sufficiently'], 500);
            }
        }
    
        return response()->json(['message' => 'File upload failed'], 500);
    }
    
    
    
}
