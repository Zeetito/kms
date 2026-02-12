<?php

namespace Database\Seeders;

use App\Http\Controllers\SystemConfigController;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Http\Request;

class UserSycnSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // make Illuminate request
        $request = new Request();

        $syncController = new SystemConfigController();

        // push to the getMemberDataSheetLink method (no response)
        $syncController->syncMembersData($request);
    }
}
