<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Controller;
use App\Models\College;
use App\Models\SystemConfig;
use App\Models\User;
use App\Models\Zone;
use Exception;
use Google\Client;
use Google\Service\Sheets;
use Google\Service\Sheets\BatchUpdateValuesRequest;
use Google\Service\Sheets\ValueRange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SystemConfigController extends Controller
{
    // MEMBERS SHEET STUFF
    // Get members data sheet link
    public function getMembersDataSheetLink(Request $request)
    {
       $key = "members_data_sheet_link";

       $systemConfig = SystemConfig::where('key', $key)->first();

       return response()->json([
           'data' => $systemConfig->value,
           'status' => 'success'
       ],200);

    }

    // Get Members data Status
    public function getMembersDataStatus(Request $request)
    {

        $key = "members_data_sheet_link";

        $systemConfig = SystemConfig::where('key', $key)->first();

        try {
            // $client = new Client();
            // // Reference your .env path
            // $credentialsPath = base_path(env('GOOGLE_APPLICATION_CREDENTIALS'));
            // $client->setAuthConfig($credentialsPath);
            // $client->addScope(Sheets::SPREADSHEETS_READONLY);

            // $service = new Sheets($client);
            // $spreadsheetId = env('GOOGLE_SHEET_ID');
            
            // // Adjust 'Sheet1!A:Z' to match your actual Sheet Name and Column range
            // $range = "'Form Responses 1'!A:V";
            // $response = $service->spreadsheets_values->get($spreadsheetId, $range);
            // $rows = $response->getValues();

            // if (empty($rows)) {
            //     return response()->json([
            //         'status' => 'success',
            //         'unregistered' => 0,
            //         'lastSync' => 'No data found'
            //     ], 200);
            // }

            // // 1. Find the index of the "synced" column from the header row (row 0)
            //     $normalizedHeaders = array_map(function($h) { 
            //         return strtolower(trim($h)); 
            //     }, $rows[0]);

            //     $syncedIndex = array_search('synced', $normalizedHeaders);

            // if ($syncedColumnIndex === false) {
            //     return response()->json([
            //         'status' => 'error',
            //         'message' => 'Column "synced" not found in the sheet.'
            //     ], 400);
            // }

            $unregisteredCount = 0;

            // 2. Loop through data rows (starting from index 1 to skip headers)
            // for ($i = 1; $i < count($rows); $i++) {
            //     $row = $rows[$i];
                
            //     // Get the value of the synced column for this row
            //     // We check if the index exists in case a row is partially empty
            //     $syncedValue = isset($row[$syncedColumnIndex]) ? strtolower(trim($row[$syncedColumnIndex])) : '';

            //     if ($syncedValue != 'yes') {
            //         $unregisteredCount++;
            //     }
            // }

            $unregisteredCount = DB::table('members_sheet_data')    
                                    ->where('synced', '!=','yes')
                                    ->orWhereNull('synced')
                                    ->count();

            // // 3. For Last Sync, you could fetch the last timestamp from your local 'audit_logs' table
            // $lastSyncTime = \DB::table('audit_logs')
            //     ->where('action', 'data_sync')
            //     ->latest()
            //     ->value('created_at');

            return response()->json([
                'status' => 'success',
                'unregistered' => $unregisteredCount,
                'lastSync' => $systemConfig->updated_at,
                // 'sheetLink' => "https://docs.google.com/spreadsheets/d/$spreadsheetId/edit"
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Sync Members Data - Fetching the Delta
    public function syncMembersData(Request $request)
    {
        // Set limit to 3 mins
        $limit = 180;
        $key = "members_data_sheet_link";
        $systemConfig = SystemConfig::where('key', $key)->first();

        try {
            // 1. Fetch unsynced records from the local table
            $pendingRecords = DB::table('members_sheet_data')
                ->where('synced', '!=', 'yes')
                ->orWhereNull('synced')
                ->limit(50)
                ->get();

            if ($pendingRecords->isEmpty()) {
                return response()->json(['status' => 'success', 'message' => 'No pending records to sync'], 200);
            }

            $syncedIds = [];
            $processedData = [];

            foreach ($pendingRecords as $row) {
                // Check if user already exists
                $user = User::where('email', $row->email)->first();
                
                // Clean up year logic - assuming 'year' column has the string "2025/2026" etc.
                // Adjust the substring index if the format is different
                $year = strlen($row->year) > 5 ? substr($row->year, 5) : $row->year;

                if (!$user) {
                    // Logic for Student/Worker status
                    $status = $row->status;
                    $isStudent = str_contains(strtolower($status), 'student') || str_contains(strtolower($status), 'postgraduate');
                    $isKnustAffiliate = false;
                    $isWorker = 0;

                    if ($status == 'Worker') {
                        $isWorker = 1;
                    } else if (str_contains(strtolower($status), 'person')) {
                        $isWorker = 2;
                        $isKnustAffiliate = true;
                    }

                    // Map Residence and Zone
                    $residence = [
                        "is_custom" => true,
                        "custom_name" => $row->residence,
                        "room" => $row->room,
                        "custom_zone_id" => Zone::where('name', 'LIKE', '%' . $row->zone . '%')->first()->id ?? null,
                    ];

                    // Map Program and College
                    $program = [
                        "is_custom" => true,
                        "custom_name" => $row->program,
                        "year" => $year,
                        "custom_college_id" => College::where('name', 'LIKE', '%' . $row->college . '%')->first()->id ?? null,
                    ];

                    $requestData = [
                        "firstname"         => $row->first_name,
                        "lastname"          => $row->last_name,
                        "othername"         => $row->other_name,
                        "gender"            => strtolower($row->gender) == 'male' ? 'm' : 'f',
                        "email"             => $row->email,
                        "contacts"          => [
                            "phone"       => $row->phone,
                            "whatsapp"    => $row->phone_whatsappp,
                            "school_voda" => $row->phone_school_voda,
                        ],
                        "is_member"          => true,
                        "is_student"         => $isStudent,
                        "active_contact"     => $row->phone,
                        "is_worker"          => $isWorker,
                        "is_knust_affiliate" => $isKnustAffiliate,
                        "is_alumni"          => false,
                        "password"           => 'password',
                        "password_confirmation" => 'password',
                        "local_congregation" => $row->local_congregation,
                        "residence"          => $residence,
                        "program"            => $program,
                        "is_sheet_request"   => true,
                    ];

                    $newRequest = new Request();
                    $newRequest->replace($requestData);

                    $userController = new UserController;
                    $regResponse = $userController->register($newRequest);

                    if ($regResponse->getStatusCode() == 200) {
                        // Mark for update in our local database
                        $syncedIds[] = $row->id; 
                    }else{
                        Log::info($regResponse);
                    }
                } else {
                    // If user exists, we might still want to mark as synced to stop processing it
                    $syncedIds[] = $row->id;
                }

                $processedData[] = $row;
            }

            // 2. Bulk update the 'synced' status for successful registrations
            if (!empty($syncedIds)) {
                DB::table('members_sheet_data')
                    ->whereIn('id', $syncedIds)
                    ->update(['synced' => 'yes']);
            }

            // 3. Update the last sync timestamp in config
            if ($systemConfig) {
                $systemConfig->touch(); // Updates updated_at
            }

            return response()->json([
                'status' => 'success',
                'count' => count($syncedIds),
                'data' => $processedData
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error', 
                'message' => $e->getMessage(),
                'line' => $e->getLine()
            ], 500);
        }
    }
}
