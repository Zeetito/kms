<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Report;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{

    // Index
    public function index(Request $request)
    {
        return response()->json(Report::all());
    }

    // Show
    public function show(Request $request, Report $report)
    {
        return response()->json($report);
    }

    // Store
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'type' => 'nullable|string',
            'semester_id' => 'nullable|exists:semesters,id',
            'user_id' => 'nullable|exists:users,id',
            'createable_type' => 'required|string|max:255',
            'createable_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $report = Report::create($request->all());
            return response()->json(['message' => 'Report created successfully', 'report' => $report], 201);
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1062) {
                return response()->json(['error' => 'Duplicate entry detected for the report.'], 409);
            }
            return response()->json(['error' => 'Failed to create report due to database error: ' . $e->getMessage()], 500);
        }
    }

    // Update
    public function update(Request $request, Report $report)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'type' => 'nullable|string',
            'semester_id' => 'nullable|exists:semesters,id',
            'user_id' => 'nullable|exists:users,id',
            'createable_type' => 'required|string|max:255',
            'createable_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $report->update($request->all());
            return response()->json(['message' => 'Report updated successfully', 'report' => $report], 200);
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1062) {
                return response()->json(['error' => 'Duplicate entry detected for the report.'], 409);
            }
            return response()->json(['error' => 'Failed to update report due to database error: ' . $e->getMessage()], 500);
        }
    }

    // Delete
    public function destroy(Report $report)
    {
        try {
            $report->delete();
            return response()->json(['message' => 'Report deleted successfully'], 200);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Failed to delete report due to a database error: ' . $e->getMessage()], 500);
        }
    }


}
