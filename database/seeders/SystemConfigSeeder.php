<?php

namespace Database\Seeders;

use App\Models\SystemConfig;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SystemConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $SysemConfigs = [
            [
                "name" => "Members Data Sheet Link",
                "key" => "members_data_sheet_link",
                "value" => "https://docs.google.com/spreadsheets/d/1x5slxrqq0NVsNAhvRR1oiNYMdLAMFmCLPvpcnTClDNU/edit?usp=sharing",
            ]
        ];

        foreach ($SysemConfigs as $SysemConfig) {
            SystemConfig::create($SysemConfig);
        }
    }
}
