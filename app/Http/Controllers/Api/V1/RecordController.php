<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Record;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Scopes\SemesterScope;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;

#[ScopedBy([SemesterScope::class])]
class RecordController extends Controller
{

    // Index
    public function index(Request $request)
    {
        return response()->json(Record::all());
    }

    // Show
    public function show(Request $request, Record $record)
    {
        return response()->json($record);
    }

    // Store
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'createable_type' => 'required|string|max:255',
            'createable_id' => 'required|integer',
            'user_id' => 'nullable|exists:users,id',
            'semester_id' => 'nullable|exists:semesters,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $record = Record::create([
                'name' => $request->name,
                'type' => $request->type,
                'createable_type' => $request->createable_type,
                'createable_id' => $request->createable_id,
                'user_id' => $request->user_id,
                'semester_id' => $request->semester_id,
            ]);
            return response()->json(['message' => 'Record created successfully', 'record' => $record], 201);
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1062) {
                return response()->json(['error' => 'Duplicate entry detected for the record.'], 409);
            }
            return response()->json(['error' => 'Failed to create record due to database error: ' . $e->getMessage()], 500);
        }
    }

    // Update
    public function update(Request $request, Record $record)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'createable_type' => 'required|string|max:255',
            'createable_id' => 'required|integer',
            'user_id' => 'nullable|exists:users,id',
            'semester_id' => 'nullable|exists:semesters,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $record->update($request->all());
            return response()->json(['message' => 'Record updated successfully', 'record' => $record], 200);
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1062) {
                return response()->json(['error' => 'Duplicate entry detected for the record.'], 409);
            }
            return response()->json(['error' => 'Failed to update record due to database error: ' . $e->getMessage()], 500);
        }
    }

    // Destroy
    public function destroy(Record $record)
    {
        try {
            $record->delete();
            return response()->json(['message' => 'Record deleted successfully'], 200);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Failed to delete record due to a database error: ' . $e->getMessage()], 500);
        }
    }


}
