<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Models\Visit;
use App\Models\Semester;
use App\Models\UserVisit;
use App\Models\VisitItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\VisitResource;
use App\Http\Resources\VisitItemResource;
use Illuminate\Support\Facades\Validator;

class VisitController extends Controller
{
    // Index
    public function index(Request $request){
        
        $user = $request->user();

        $visits = Visit::all()->where('semester_id',$user->semester_id);

        // return throught the VisitResource

        return VisitResource::collection($visits);



       
    }
    
    // Store
    public function store(Request $request){

        // Validate the reqeuest
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255' 
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        try {
            $visit = new Visit();
            $visit->name = $request->name;
            $visit->type = $request->type;
            $visit->user_id = auth()->id();
            $visit->semester_id = Semester::active_semester()->id;
            $visit->save();

            // if that worked.. Let's Create a UserVisit INstnace
            $userVisit = new UserVisit();
            $userVisit->user_id = auth()->id();
            $userVisit->visit_id = $visit->id;
            $userVisit->role = "owner";
            $userVisit->accepted = true;
            $userVisit->save();

            return response()->json(['message' => 'Visit created successfully!', 'data' => $visit], 201);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to create visit: ' . $e->getMessage()], 500);
        }
    }

    // Invite User
    public function inviteUser(Request $request,Visit $visit){

        $user = User::where('id', $request->user_id)->first();

        // If user not found
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        try {
            $userVisit = new UserVisit();
            $userVisit->user_id = $user->id;
            $userVisit->visit_id = $visit->id;
            $userVisit->role = "member";
            $userVisit->accepted = false;
            $userVisit->save();

            return response()->json(['message' => 'User invited successfully!', 'data' => $userVisit], 201);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to invite user: ' . $e->getMessage()], 500);
        }

    }

    // Respond
    public function respond(Request $request){

        $userVisit = UserVisit::where('user_id', $request->user()->id)->where('accepted', false)->first();

        // If userVisit not found
        if (!$userVisit) {
            return response()->json(['message' => 'You do not have an active invite'], 404);
        }

        $response = $request->response;

        try {
            $userVisit->accepted = $response;
            $userVisit->save();

            return response()->json(['message' => 'Invite responded successfully!', 'data' => $userVisit], 201);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to respond to invite: ' . $e->getMessage()], 500);
        }
               
        
    }

    // Add VisitItem
    public function addVisitItem(Request $request, Visit $visit){


        $validator = Validator::make($request->all(), [
            // body is an array
            'body' => 'required|array',           
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }


        try {
            $visitItem = new VisitItem();
            $visitItem->visit_id = $visit->id;
            $visitItem->user_id = auth()->id();
            $visitItem->body = json_encode($request->body); //$visitItem->body = $request->body;
            $visitItem->save();

            return response()->json(['message' => 'Item added successfully!', 'data' => $visitItem], 201);
        } catch (Exception $e) {
            Log::info('Error updating Visit Item: ' . $e->getMessage());

            return response()->json(['error' => 'Failed to add visit item: ' . $e->getMessage()], 500);
        }
    }

    // updateVisitItem
    public function updateVisitItem(Request $request, VisitItem $visitItem){


        $validator = Validator::make($request->all(), [
            // body is an array
            'body' => 'required|array',           
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        try {
            $visitItem->body = json_encode($request->body); //$visitItem->body = $request->body;
            $visitItem->save();

            return response()->json(['message' => 'Item updated successfully!', 'data' => $visitItem], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to update visit item: ' . $e->getMessage()], 500);
        
        }
    }

    // Delete Visit Item
    public function deleteVisitItem(VisitItem $visitItem){
        try {
            $visitItem->delete();

            return response()->json(['message' => 'Item deleted successfully!'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to delete visit item: ' . $e->getMessage()], 500);
        
        }
    }

    // Get Visit Items
    public function getItems(Visit $visit){
        return VisitItemResource::collection($visit->items);
    }

}
