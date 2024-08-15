<?php

namespace Database\Seeders;

use App\Models\OfficiatingRole;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OfficiatingRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $OfficiatingRoles = [
            ['name' => 'Songs Leader', 'slug'=>'songs_leader', 'takes_question'=>0],
            ['name' => 'Bible Class Teacher', 'slug'=>'bible_class_teacher', 'takes_question'=>1],
            ['name' => 'Sermonist', 'slug'=>'sermonist', 'takes_question'=>0],
            ['name' => 'Lord\'s Supper', 'slug'=>'lords_supper', 'takes_question'=>0],
            ['name' => 'Bible Reader', 'slug'=>'bible_reader', 'takes_question'=>0],
            ['name' => 'Scripture Reader', 'slug'=>'scripture_reader', 'takes_question'=>0],
            ['name' => 'Panelist', 'slug'=>'panelist', 'takes_question'=>1],
            ['name' => 'Prayer', 'slug'=>'prayer', 'takes_question'=>0],

        ];

        foreach ($OfficiatingRoles as $OfficiatingRole) {
            OfficiatingRole::create($OfficiatingRole);
        }

    }
}
