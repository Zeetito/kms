<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Role;
use App\Models\Report;
use App\Models\Semester;
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
            'name' => 'nullable|string|max:255',
            'type' => 'nullable|string',
            'user_id' => 'nullable|exists:users,id',
            'reportable_id' => 'nullable|integer',
            'reportable_type' => 'nullable|string',
            'role' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
            $role = Role::where('slug', $request->role)->first();

            if(!$role){
                return response()->json(['error' => 'Sorry. You Cannot Create this report'], 404);
            }else{

                $createable_type = "App\\Models\\Role";
                $createable_id = $role->id;
            }
            // Create a new instnace to be saved using the request
            $instance = $request->all();

            $reportable = ucfirst("App\\Models\\".$instance['reportable_type'])::find($instance['reportable_id']);
            if($reportable){
                $instance['reportable_id'] = $reportable->id;
                $instance['reportable_type'] = get_class($reportable);
            }
            // If reportable does not exist, it means it's a general report            

            $instance['createable_type'] = $createable_type;
            $instance['createable_id'] = $createable_id;
            $instance['semester_id'] = Semester::getActiveSemester()->id;
            unset($instance['role']);

        try {
            $report = Report::create($instance);
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
