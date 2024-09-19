<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\RecordItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class RecordItemController extends Controller
{
    // Index
    public function index(Request $request)
    {
        return response()->json(RecordItem::all());
    }

    // Show
    public function show(Request $request, RecordItem $record_item)
    {
        return response()->json($record_item);
    }

    // Store
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'record_id' => 'required|exists:records,id',
            'user_id' => 'nullable|exists:users,id',
            'unit_cost' => 'nullable|numeric',
            'quantity' => 'nullable|numeric',
            'info' => 'nullable|string|max:255',
            'value' => 'nullable|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $record_item = RecordItem::create($request->all());
            return response()->json(['message' => 'Record item created successfully', 'record_item' => $record_item], 201);
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1062) {
                return response()->json(['error' => 'Duplicate entry detected for the record item.'], 409);
            }
            return response()->json(['error' => 'Failed to create record item due to database error: ' . $e->getMessage()], 500);
        }
    }

    // Update
    public function update(Request $request, RecordItem $record_item)
    {
        $validator = Validator::make($request->all(), [
            'record_id' => 'required|exists:records,id',
            'user_id' => 'nullable|exists:users,id',
            'unit_cost' => 'nullable|numeric',
            'quantity' => 'nullable|numeric',
            'info' => 'nullable|string|max:255',
            'value' => 'nullable|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $record_item->update($request->all());
            return response()->json(['message' => 'Record item updated successfully', 'record_item' => $record_item], 200);
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1062) {
                return response()->json(['error' => 'Duplicate entry detected for the record item.'], 409);
            }
            return response()->json(['error' => 'Failed to update record item due to database error: ' . $e->getMessage()], 500);
        }
    }

    // Delete
    public function destroy(RecordItem $record_item)
    {
        try {
            $record_item->delete();
            return response()->json(['message' => 'Record item deleted successfully'], 200);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Failed to delete record item due to a database error: ' . $e->getMessage()], 500);
        }
    }


}
