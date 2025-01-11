<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Jobs\ActivateAccountNotificationJob;

class AccountController extends Controller
{
    public function activation_notificatoin(Request $request){
        // Validate the emails input to be an array
        $validator = Validator::make($request->all(), [
            'emails' => 'required|array',
            'emails.*' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $emails = $request->input('emails');

        // Send the emails
        foreach ($emails as $email) {
            $user = User::where('email', $email)->first();
            if ($user) {
                // dispatch job
                dispatch(new ActivateAccountNotificationJob($user))->onQueue('activation_notification');;
            }
        }

        return response()->json([
            'message' => 'Emails sent successfully',
            'data' => collect([
            'emails' => $emails
            ])
        ]);
        
    }
}
