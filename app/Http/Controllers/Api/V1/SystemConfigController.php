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
            $client = new Client();
            // Reference your .env path
            $credentialsPath = base_path(env('GOOGLE_APPLICATION_CREDENTIALS'));
            $client->setAuthConfig($credentialsPath);
            $client->addScope(Sheets::SPREADSHEETS_READONLY);

            $service = new Sheets($client);
            $spreadsheetId = env('GOOGLE_SHEET_ID');
            
            // Adjust 'Sheet1!A:Z' to match your actual Sheet Name and Column range
            $range = "'Form Responses 1'!A:V";
            $response = $service->spreadsheets_values->get($spreadsheetId, $range);
            $rows = $response->getValues();

            if (empty($rows)) {
                return response()->json([
                    'status' => 'success',
                    'unregistered' => 0,
                    'lastSync' => 'No data found'
                ], 200);
            }

            // 1. Find the index of the "synced" column from the header row (row 0)
                $normalizedHeaders = array_map(function($h) { 
                    return strtolower(trim($h)); 
                }, $rows[0]);

                $syncedIndex = array_search('synced', $normalizedHeaders);

            if ($syncedColumnIndex === false) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Column "synced" not found in the sheet.'
                ], 400);
            }

            $unregisteredCount = 0;

            // 2. Loop through data rows (starting from index 1 to skip headers)
            for ($i = 1; $i < count($rows); $i++) {
                $row = $rows[$i];
                
                // Get the value of the synced column for this row
                // We check if the index exists in case a row is partially empty
                $syncedValue = isset($row[$syncedColumnIndex]) ? strtolower(trim($row[$syncedColumnIndex])) : '';

                if ($syncedValue != 'yes') {
                    $unregisteredCount++;
                }
            }

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
        $key = "members_data_sheet_link";
        $systemConfig = SystemConfig::where('key', $key)->first();

        try {
            $client = new Client();
            $credentialsPath = base_path(env('GOOGLE_APPLICATION_CREDENTIALS'));
            $client->setAuthConfig($credentialsPath);
            $client->addScope(Sheets::SPREADSHEETS);

            $service = new Sheets($client);
            $spreadsheetId = env('GOOGLE_SHEET_ID');
            
            $range = "'Form Responses 1'!A:V";
            $response = $service->spreadsheets_values->get($spreadsheetId, $range);
            $rows = $response->getValues();

            if (empty($rows)) {
                return response()->json(['status' => 'error', 'message' => 'Sheet is empty'], 400);
            }

            $headers = $rows[0];
            $syncedIndex = array_search('synced', $headers);

            if ($syncedIndex === false) {
                return response()->json(['status' => 'error', 'message' => 'Column "synced" missing'], 400);
            }

            $pendingData = [];
            $updateValues = [];
            $passed_count = 0;

            for ($i = 1; $i < count($rows); $i++) {
                if ($passed_count >= 50) break;

                $row = $rows[$i];
                $syncedValue = isset($row[$syncedIndex]) ? strtolower(trim($row[$syncedIndex])) : '';

                if ($syncedValue !== 'yes') {
                    $rowData = [];
                    foreach ($headers as $colIndex => $headerName) {
                        $rowData[$headerName] = $row[$colIndex] ?? null;
                    }
                    
                    $rowNumber = $i + 1;
                    $rowData['sheet_row_index'] = $rowNumber;

                    $user = User::where('email', $rowData['Email Address'])->first();
                    $year = substr($rowData['Current Year (2025/2026)'], 5);

                    if (!$user) {
                        $status = $rowData['Status'];
                        $isStudent = strpos($status, 'student') !== false || strpos($status, 'PostGraduate') !== false;
                        $isKnustAffiliate = false;
                        $isWorker = 0;

                        if ($status == 'Worker') {
                            $isWorker = 1;
                        } else if (strpos($status, 'Personnel') !== false) {
                            $isWorker = 2;
                            $isKnustAffiliate = true;
                        }

                        $residence = [
                            "is_custom" => true,
                            "custom_name" => $rowData['Residence (Hostel / Homestel)'],
                            "room" => $rowData['Room Number'],
                            "custom_zone_id" => Zone::where('name', 'LIKE', '%' . $rowData['Zone'] . '%')->first()->id ?? null,
                        ];

                        $program = [
                            "is_custom" => true,
                            "custom_name" => $rowData['Program Of Study'],
                            "year" => $year,
                            "custom_college_id" => College::where('name', 'LIKE', '%' . $rowData['College '] . '%')->first()->id ?? null,
                        ];

                        $requestData = [
                            "firstname" => $rowData['First Name'],
                            "lastname" => $rowData['Last Name'],
                            "othername" => $rowData['Other Name(s)'],
                            "gender" => $rowData['Gender'] == 'Male' ? 'm' : 'f',
                            "email" => $rowData['Email Address'],
                            "contacts" => [
                                "phone" => $rowData['Phone Contact (Call)'],
                                "whatsapp" => $rowData['Phone Contact (WhatsApp)'],
                                "school_voda" => $rowData['Phone Contact (School Voda)'],
                            ],
                            "is_member" => true,
                            "is_student" => $isStudent,
                            "active_contact" => $rowData['Phone Contact (Call)'],
                            "is_worker" => $isWorker,
                            "is_knust_affiliate" => $isKnustAffiliate,
                            "is_alumni" => false,
                            "password" => 'password',
                            "password_confirmation" => 'password',
                            "local_congregation" => $rowData['Local Congregation (Name & Location)'],
                            "residence" => $residence,
                            "program" => $program,
                            "is_sheet_request" => true,
                        ];

                        $newRequest = new Request();
                        $newRequest->replace($requestData);

                        $userController = new UserController;
                        $regResponse = $userController->register($newRequest);

                        if ($regResponse->getStatusCode() == 200) {
                            $updateValues[] = new \Google\Service\Sheets\ValueRange([
                                'range' => "'Form Responses 1'!V{$rowNumber}",
                                'values' => [['yes']]
                            ]);
                        }
                    }
                    
                    $pendingData[] = $rowData;
                    $passed_count++;
                }
            }

            if (!empty($updateValues)) {
                $batchBody = new BatchUpdateValuesRequest([
                    'valueInputOption' => 'RAW',
                    'data' => $updateValues
                ]);
                $service->spreadsheets_values->batchUpdate($spreadsheetId, $batchBody);
            }

            $systemConfig->updated_at = now();
            $systemConfig->save();

            return response()->json([
                'status' => 'success',
                'count' => count($pendingData),
                'data' => $pendingData
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
