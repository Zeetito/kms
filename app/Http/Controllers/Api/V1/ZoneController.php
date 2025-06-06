<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Models\Zone;
use App\Models\Semester;
use App\Models\Residence;
use App\Models\UserProgram;
use Illuminate\Http\Request;
use App\Models\UserResidence;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;

class ZoneController extends Controller
{
    
    // Index
    public function index()
    {
        return response()->json([
            'data' => Zone::all()
        ]);
    }

    // Show
    public function show(Zone $zone)
    {
        return response()->json([
            'data' => $zone
        ]);
    }

    // Store
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:zones,slug',
            'boundaries' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $zone = Zone::create($request->all());
            return response()->json(['message' => 'Zone created successfully', 'zone' => $zone], 201);
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1062) {
                return response()->json(['error' => 'Duplicate entry detected for the zone.'], 409);
            }
            return response()->json(['error' => 'Failed to create zone due to database error: ' . $e->getMessage()], 500);
        }
    }

    // Update
    public function update(Request $request, Zone $zone)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'boundaries' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $zone->update($request->all());
            return response()->json(['message' => 'Zone updated successfully', 'zone' => $zone], 200);
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1062) {
                return response()->json(['error' => 'Duplicate entry detected for the zone.'], 409);
            }
            return response()->json(['error' => 'Failed to update zone due to database error: ' . $e->getMessage()], 500);
        }
    }

    // Delete
    public function destroy(Zone $zone)
    {
        try {
            $zone->delete();
            return response()->json(['message' => 'Zone deleted successfully'], 200);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Failed to delete the zone due to a database error: ' . $e->getMessage()], 500);
        }
    }

    // Add Zone User
    public function addZoneUser(Request $request)
    {  
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string|max:255',
            'lastname' => 'nullable|string|max:255',
            'gender' => 'required|in:m,f',
            'is_baptised' => 'int',
            'active_contact' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:users,email',
            'residence' => 'nullable|exists:residences,id',
            'password' => 'required|string|confirmed|min:8',
            // 'password_confirmation' => 'required|string|confirmed|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }



        $user =  new User;
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->gender = $request->gender;
        $user->active_contact = $request->active_contact;
        $user->email = $request->email;
        $user->is_baptised = $request->is_baptised == 1 ? true : false;
        $user->password = bcrypt($request->password);
        $user->is_active =  true;
        $user->is_member = true;
        $user->save();

        #Retrieve Residence

        // Check if the user has a Custom residence
        if($request->custom_residence_name != null){
            $user_residence = new UserResidence;
            $user_residence->user_id = $user->id;
            $user_residence->residence_id = null;
            $user_residence->custom_name = $request->custom_residence_name;
            $user_residence->custom_description = $request->custom_residence_description;
            $user_residence->custom_zone_id = $request->custom_residence_zone ?? 17; // Default is Others Zone...
            $user_residence->academic_year_id = Semester::active_semester()->academic_year_id;
            $user_residence->save();
        }else{
            $residence = Residence::find($request->residence);
    
            // Save User Residence Instance
            $user_residence = new UserResidence;
            $user_residence->user_id = $user->id;
            $user_residence->residence_id = $residence->id;
            $user_residence->academic_year_id = Semester::active_semester()->academic_year_id;
            $user_residence->save();

        }


        // Add Room and other details later...

        # Return to the add user view
        return redirect()->route('add.zone.user.view', ['zone' => $residence->zone ?? Zone::find($request->custom_residence_zone) ?? Zone::find(17)]) ->with('success', 'User added successfully');
        
    }

    // Edit Zone User
    public function editZoneUser(Request $request, User $user)
    {
        // return $request;
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string|max:255',
            'lastname' => 'nullable|string|max:255',
            'gender' => 'required|in:m,f',
            'is_baptised' => 'int',
            'active_contact' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:users,email,'.$user->id,
            'residence' => 'nullable|exists:residences,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->gender = $request->gender;
        $user->active_contact = $request->active_contact;
        $user->email = $request->email;
        $user->is_baptised = $request->is_baptised == 1 ? true : false;
        $user->save();

        #Retrieve Residence
        $residence = Residence::find($request-> input('edit-residence'));


        //     // Save User Residence Instance
            $user_residence = UserResidence::where('user_id', $user->id)->where('academic_year_id', Semester::active_semester()->academic_year_id)->first();

        //    // Check if the user has a Custom residence
        if($request->filled('edit-is_custom_residence')) {
            $user_residence = $user_residence ?? new UserResidence;
            $user_residence->user_id = $user->id;
            $user_residence->residence_id = null;
            $user_residence->custom_name = $request->input('edit-custom_residence_name');
            $user_residence->custom_description = $request->input('edit-custom_residence_description');
            $user_residence->custom_zone_id = $request-> input('edit-custom_residence_zone') ??17; // Default is Others Zone...
            $user_residence->academic_year_id = Semester::active_semester()->academic_year_id;
            $user_residence->save();
        }else{
            $residence = Residence::find($request->input('edit-residence'));
    
            // Save User Residence Instance
            $user_residence = $user_residence ?? new UserResidence;
            $user_residence->user_id = $user->id;
            $user_residence->residence_id = $residence->id;
            $user_residence->academic_year_id = Semester::active_semester()->academic_year_id;
            $user_residence->save();

        }

        // Update Room Number if Provided
        if($request->input('edit-room') != null){
            $user_residence->room = $request->input('edit-room');
            $user_residence->save();
        }

        // Update User Staus if Provided
        if($request->input('status') != null){
            if($request->input('status') == 'student'){
                $user->is_student = true;
                $user->is_knust_affiliate = true;

                // Update the Student Year And Program
                if($request->input('edit-year')){
                    // Check if Program was provided...
                    if($request->input('edit-program') != null){

                    }else{
                        // Update or Create A Custom User Program Relation with null name desc.
                        UserProgram::updateOrCreate(
                            [
                                'user_id' => $user->id,
                                'academic_year_id' => Semester::active_semester()->academic_year_id,
                            ],
                            [
                                'year' => $request->input('edit-year'),
                            ]
                        );

                    }
                }else{
                    // Set Year To Null
                    UserProgram::updateOrCreate(
                        [
                            'user_id' => $user->id,
                            'academic_year_id' => Semester::active_semester()->academic_year_id,
                        ],
                        [
                            'year' => null,
                        ]
                    );
                }


            }else{
                $user->is_student = false;
                $user->is_knust_affiliate = false;

                // Check working status
                if($request->input('occupation') != null){
                    if($request->input('occupation') == 'worker'){
                        $user->is_worker = 1;
                    }

                    if($request->input('occupation') == 'ns'){
                        $user->is_worker = 2;
                    }

                    if($request->input('occupation') == 'other'){
                        $user->is_worker = 3;
                    }

                }

                // Delete User Program is it exists
                UserProgram::where('user_id', $user->id)->where('academic_year_id', Semester::active_semester()->academic_year_id)->delete();
            }
            $user->save();
        }


        # Return to the add user view
        return redirect()->route('add.zone.user.view', ['zone' => $residence->zone ?? $user->zone_note()['id'] ?? Zone::find(17)]) ->with('success', 'User updated successfully');
        
    }

}
