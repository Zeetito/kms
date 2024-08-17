<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Residence;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ResidenceController extends Controller
{
    // Store Residence
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'nullable',
            'zone_id' => 'nullable',
            'location' => 'nullable',
            'landmark' => 'nullable',
            'description' => 'nullable',
          
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
                'status' => 'failure'
            ], 422);
        }
        $residence = Residence::create($request->all());

        return response()->json($residence);
    
    }

    // Update Residence
    public function update(Request $request, Residence $residence)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'nullable',
            'zone_id' => 'nullable',
            'location' => 'nullable',
            'landmark' => 'nullable',
            'description' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
                'status' => 'failure'
            ], 422);
        }

        $residence->update($request->all());
        return response()->json($residence);
    }

    // Delete Residence
    public function destroy(Residence $residence)
    {
        $residence->delete();
        return response()->json([
            'message' => 'Delete successful',
            'status' => 'success'
        ], 200);
    }
}
