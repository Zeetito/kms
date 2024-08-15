<?php

namespace Database\Seeders;

use App\Models\Zone;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ZoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $zones = [
            ['name' => 'BOADI'],
            ['name' => 'BOMSO'],
            ['name' => 'BRUNEI'],
            ['name' => 'FORD-NYBERG-SPLENDOR'],
            ['name' => 'GAZA-DEDUAKO'],
            ['name' => 'KOTEI'],
            ['name' => 'NEWSITE'],
            ['name' => 'SHALOM'],
            ['name' => 'WESTEND'],
            ['name' => 'AFRICA'],
            ['name' => 'REPUBLIC'],
            ['name' => 'INDEPENDENCE'],
            ['name' => 'QUEEN ELIZABETH II'],
            ['name' => 'UNITY'],
            ['name' => 'UNIVERSITY'],
            ['name' => 'SRC,IMPACT,HALL 7'],
        ];

        foreach ($zones as $zone) {
            Zone::create($zone);
        }
    }
}
