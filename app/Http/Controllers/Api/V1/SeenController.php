<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Seen;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SeenController extends Controller
{
    
    // Store
    public function Store(Request $request, $type, $id)
    {
        $model_path = "App\\Models\\" . ucfirst($type);
        $instance = $model_path::find($id);
        $user = $request->user();

        $seen = new Seen();
        $seen->user_id = $user->id;
        $seen->seenable_type = get_class($instance);
        $seen->seenable_id = $instance->id;
        
        // check for double entry try catch
        try {
            $seen->save();
            return response()->json(['message' => 'Marked as Seen'], 200);


        } catch (\Exception $e) {
            if ($e->getCode() == 23000) {
                return response()->json(['message' => 'Already marked as seen'], 200);
            }

            return response()->json(['message' => 'seen'], 200);
        }

    }

    // Delete
    public function destroy(Request $request, $type, $id)
    {
        $model_path = "App\\Models\\" . ucfirst($type);
        $instance = $model_path::find($id);
        $user = $request->user();

        $seen = Seen::where('user_id', $user->id)->where('seenable_type', get_class($instance))->where('seenable_id', $instance->id)->first();
        $seen->delete();

        return response()->json(['message' => 'Mark sa unseen'], 200);
    }
}
