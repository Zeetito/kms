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
        return response()->json(
            [                
                "report" =>   $report,
                "report_records" => $report->report_records
            ]
        );
    }

    // Store
    public function store(Request $request)
    {
        //object type not specified? name required
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'type' => 'nullable|string',
            'object_id' => 'nullable|integer',
            'object_type' => 'nullable|string',
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
        
        if($request->object_type || $request->object_id){
            $reportable = ucfirst("App\\Models\\".$instance['object_type'])::find($instance['object_id']);
        }else{
            $reportable = null;
        }

        $instance['createable_type'] = $createable_type;
        $instance['createable_id'] = $createable_id;
        
        if($reportable){
            $instance['reportable_id'] = $reportable->id;
            $instance['reportable_type'] = get_class($reportable);

            // Check for existence
            if(Report::where('reportable_id', $instance['reportable_id']) ->where('reportable_type', $instance['reportable_type'])->where('createable_id', $instance['createable_id'])->where('createable_type', $instance['createable_type'])->exists()){
                return response()->json(['error' => 'Report already exists for this '.$request->object_type], 409);
            }
        }
        
        // if the name input is present
        $instance['name'] = $request->name ?? null;
        
        $instance['type'] = $request->object_type ? ucfirst($request->object_type) : "General";

        $instance['semester_id'] = Semester::getActiveSemester()->id;
        $instance['user_id'] = auth()->id();
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

    //////////////////
    // Get repots by role
    public function report_by_role(Request $request, $type, $id, $role_slug){
        $object_path = "App\\Models\\".ucfirst($type);
        $object = $object_path::find($id);

        // $role = Role::where('slug', $role_slug)->first();

        return $object->reports_by($role_slug) ?? null;
    }


}
