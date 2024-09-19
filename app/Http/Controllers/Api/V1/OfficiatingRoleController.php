<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Models\OfficiatingRole;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;

class OfficiatingRoleController extends Controller
{

    // Index
    public function index()
    {
        return response()->json(
            OfficiatingRole::all()
        );
    }

    // Show
    public function show(OfficiatingRole $officiating_role)
    {
        return response()->json($officiating_role);
    }

    // Store
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:officiating_roles,name',
            'slug' => 'nullable|string|max:255|',
            'description' => 'nullable|string',
            'takes_question' => 'required|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $officiating_role = OfficiatingRole::create($request->all());
            return response()->json(['message' => 'Officiating role created successfully', 'officiatingRole' => $officiating_role], 201);
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1062) {
                return response()->json(['error' => 'Duplicate entry detected for the officiating role.'], 409);
            }
            return response()->json(['error' => 'Failed to create officiating role due to database error: ' . $e->getMessage()], 500);
        }
    }

    // Update
    public function update(Request $request, OfficiatingRole $officiating_role)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:officiating_roles,name,' . $officiating_role->id,
            'slug' => 'nullable|string|max:255|',
            'description' => 'nullable|string',
            'takes_question' => 'required|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $officiating_role->update($request->all());
            return response()->json(['message' => 'Officiating role updated successfully', 'officiatingRole' => $officiating_role], 200);
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1062) {
                return response()->json(['error' => 'Duplicate entry detected for the officiating role.'], 409);
            }
            return response()->json(['error' => 'Failed to update officiating role due to database error: ' . $e->getMessage()], 500);
        }
    }

    // Delete
    public function destroy(OfficiatingRole $officiating_role)
    {
        try {
            $officiating_role->delete();
            return response()->json(['message' => 'Officiating role deleted successfully'], 200);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Failed to delete officiating role due to a database error: ' . $e->getMessage()], 500);
        }
    }



}
