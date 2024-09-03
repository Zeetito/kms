<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Semester;
use Illuminate\Http\Request;
use App\Models\UserResidence;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class UserResidenceController extends Controller
{
    // Store UserResidence
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'residence_id' => 'required',
            'room' => 'nullable',
            'floor' => 'nullable',
            'block' => 'nullable',
            'custom_name' => 'nullable',
            'custom_description' => 'nullable',
            'academic_year_id' => 'nullable',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
                'status' => 'failure'
            ], 422);
        }
        $instance = $request->all();
        $instance['academic_year_id'] = Semester::active_semester()->academic_year_id;
        // return $instance;
        $userResidence = UserResidence::create($instance);

        return response()->json($userResidence->with('user','residence'));
    }

    // Update UserResidence
    public function update(Request $request, UserResidence $user_residence){
        $user_residence->update($request->all());
        return response()->json($user_residence);
    }

    // Delete UserResidence
    public function destroy(UserResidence $user_residence){
        $user_residence->delete();
        return response()->json([
            'message' => 'Delete successful',
            'status' => 'success'
        ], 200);
    }
}
