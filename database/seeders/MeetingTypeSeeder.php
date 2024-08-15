<?php

namespace Database\Seeders;

use App\Models\MeetingType;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MeetingTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $meetingtypes = [
            ['name' => 'Sunday Morning Service', 'description' => 'Sunday Meeting for Worship', 'slug'=>'sunday_service'],
            ['name' => 'Tuesday Evening', 'description' => 'A meeting for worship', 'slug'=>'evening_service'],
            ['name' => 'Friday Evening', 'description' => 'A meeting for worship', 'slug'=>'friday_service'],
            ['name' => 'Gents Training', 'description' => 'A training session for the gents', 'slug'=>'gents_training'],
            ['name' => 'Ladies Training', 'description' => 'A training session for the ladies', 'slug'=>'ladies_training'],
            ['name' => 'Dawn Broadcast', 'description' => 'Preaching the gosple at some designated zones at dawn.', 'slug'=>'dawn_broadcast'],
            ['name' => 'Morning Devotion', 'description' => 'A dawn meeting for worship', 'slug'=>'morning_devotion'],
            
            // -------
            // Other
            ['name' => 'None of The Above', 'description' => '', 'slug'=>'none'],

            // ['name' => 'Joses Project', 'description' => 'Joeses Week', 'slug'=>'joeses'],
            // ['name' => 'Time Of Concecration', 'description' => 'A meeting for worship', 'slug'=>''],
            // ['name' => 'Youth Awakening Lectureship', 'description' => 'Lectureship', 'slug'=>''],
        ];

        foreach ($meetingtypes as $meetingtype) {
            MeetingType::create($meetingtype);
        }
    }
}
