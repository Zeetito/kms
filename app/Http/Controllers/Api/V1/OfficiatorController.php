<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Officiator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;

class OfficiatorController extends Controller
{
    // Index
    public function index()
    {
        return response()->json([
            'data' => Officiator::all()
        ]);
    }

    // Show
    public function show(Officiator $officiator)
    {
        return response()->json([
            'data' => $officiator
        ]);
    }

    // Store
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'meeting_id' => 'required|exists:meetings,id',
            'officiating_role_id' => 'nullable|exists:officiating_roles,id',
            'gender' => 'required|in:M,F',
            'fullname' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'residence' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $officiator = Officiator::create($request->all());
            return response()->json(['message' => 'Officiator created successfully', 'officiator' => $officiator], 201);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Failed to create officiator due to database error: ' . $e->getMessage()], 500);
        }
    }

    // Update
    public function update(Request $request, Officiator $officiator)
    {
        $validator = Validator::make($request->all(), [
            // 'meeting_id' => 'required|exists:meetings,id',
            'officiating_role_id' => 'nullable|exists:officiating_roles,id',
            'gender' => 'required|in:M,F',
            'fullname' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'residence' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $officiator->update($request->all());
            return response()->json(['message' => 'Officiator updated successfully', 'officiator' => $officiator], 200);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Failed to update officiator due to database error: ' . $e->getMessage()], 500);
        }
    }

    // Delete
    public function destroy(Officiator $officiator)
    {
        try {
            $officiator->delete();
            return response()->json(['message' => 'Officiator deleted successfully'], 200);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Failed to delete officiator due to a database error: ' . $e->getMessage()], 500);
        }
    }


}
