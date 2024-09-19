<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Zone;
use Illuminate\Http\Request;
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


}
