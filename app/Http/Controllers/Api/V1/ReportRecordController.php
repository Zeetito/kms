<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\ReportRecord;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;

class ReportRecordController extends Controller
{

    // Index
    public function index(Request $request)
    {
        return response()->json(ReportRecord::all());
    }

    // Show
    public function show(Request $request, ReportRecord $report_record)
    {
        return response()->json($report_record);
    }

    // Store
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'nullable|exists:users,id',
            'report_id' => 'required|exists:reports,id',
            'body' => 'required|string',
            'path' => 'nullable|string',
            'position' => 'nullable|integer'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $reportRecord = ReportRecord::create($request->all());
            return response()->json(['message' => 'Report record created successfully', 'reportRecord' => $reportRecord], 201);
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1062) {
                return response()->json(['error' => 'Duplicate entry detected for the report record.'], 409);
            }
            return response()->json(['error' => 'Failed to create report record due to database error: ' . $e->getMessage()], 500);
        }
    }

    // Update
    public function update(Request $request, ReportRecord $reportRecord)
    {
        $validator = Validator::make($request->all(), [
            'body' => 'required|string',
            'path' => 'nullable|string',
            'position' => 'nullable|integer'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $reportRecord->update($request->all());
            return response()->json(['message' => 'Report record updated successfully', 'reportRecord' => $reportRecord], 200);
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1062) {
                return response()->json(['error' => 'Duplicate entry detected for the report record.'], 409);
            }
            return response()->json(['error' => 'Failed to update report record due to database error: ' . $e->getMessage()], 500);
        }
    }

    // Destroy
    public function destroy(ReportRecord $reportRecord)
    {
        try {
            $reportRecord->delete();
            return response()->json(['message' => 'Report record deleted successfully'], 200);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Failed to delete report record due to a database error: ' . $e->getMessage()], 500);
        }
    }


}
