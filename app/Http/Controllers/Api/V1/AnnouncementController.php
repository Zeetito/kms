<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Announcement;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;

class AnnouncementController extends Controller
{
    // Index
    public function index(Request $request)
    {
        return response()->json(Announcement::all());
    }

    // Show
    public function show(Request $request, Announcement $announcement)
    {
        return response()->json($announcement);
    }

    // Store
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'meeting_id' => 'required|exists:meetings,id',
            'body' => 'required|string',
            'createable_type' => 'required|string|max:255',
            'createable_id' => 'required|integer',
            'user_id' => 'required|exists:users,id',
            'is_request' => 'required|boolean',
            'is_public' => 'required|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $announcement = Announcement::create($request->all());
            return response()->json(['message' => 'Announcement created successfully', 'announcement' => $announcement], 201);
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1062) {
                return response()->json(['error' => 'Duplicate entry detected for the announcement.'], 409);
            }
            return response()->json(['error' => 'Failed to create announcement due to database error: ' . $e->getMessage()], 500);
        }
    }

    // Update
    public function update(Request $request, Announcement $announcement)
    {
        $validator = Validator::make($request->all(), [
            'meeting_id' => 'required|exists:meetings,id',
            'body' => 'required|string',
            'createable_type' => 'required|string|max:255',
            'createable_id' => 'required|integer',
            'user_id' => 'required|exists:users,id',
            'is_request' => 'required|boolean',
            'is_public' => 'required|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $announcement->update($request->all());
            return response()->json(['message' => 'Announcement updated successfully', 'announcement' => $announcement], 200);
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1062) {
                return response()->json(['error' => 'Duplicate entry detected for the announcement.'], 409);
            }
            return response()->json(['error' => 'Failed to update announcement due to database error: ' . $e->getMessage()], 500);
        }
    }

    // Destroy
    public function destroy(Announcement $announcement)
    {
        try {
            $announcement->delete();
            return response()->json(['message' => 'Announcement deleted successfully'], 200);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Failed to delete announcement due to a database error: ' . $e->getMessage()], 500);
        }
    }

}
