<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Report;
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
    public function store(Request $request, Report $report)
    {
        $validator = Validator::make($request->all(), [
            'body' => 'required|string',
            'path' => 'nullable|string',
        ]);


        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        // Create an instnace
        $instance = $request->all();
        $instance['report_id'] = $report->id;
        $instance['user_id'] = auth()->id();
        $instance['position'] = ReportRecord::where('report_id', $report->id)->count() + 1;

        try {
            $reportRecord = ReportRecord::create($instance);
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
    public function update(Request $request, ReportRecord $report_record)
    {
        $validator = Validator::make($request->all(), [
            'body' => 'nullable|string',
            'path' => 'nullable|string',
            'position' => 'nullable|integer'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

       // Check if the position is altered
        if ($request->position != $report_record->position) {
            $newPosition = $request->position;
            $oldPosition = $report_record->position;

            if ($newPosition > $oldPosition) {
                // Reduce the position of all report_records between the old and new position
                ReportRecord::where('report_id', $report_record->report_id)
                            ->where('position', '>', $oldPosition)
                            ->where('position', '<=', $newPosition)
                            ->decrement('position');
            } else {
                // Increase the position of all report_records between the new and old position
                ReportRecord::where('report_id', $report_record->report_id)
                            ->where('position', '<', $oldPosition)
                            ->where('position', '>=', $newPosition)
                            ->increment('position');
            }

            // Update the position of the current report_record
            $report_record->position = $newPosition;
            $report_record->save();
        }


        try {
            $report_record->update($request->all());
            return response()->json(['message' => 'Report record updated successfully', 'reportRecord' => $report_record], 200);
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
