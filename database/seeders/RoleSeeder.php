<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'Zonal Rep', 'slug' => 'zonal_rep', 'level' => 1],
            ['name' => 'Edification Ministry', 'slug' => 'edification', 'level' => 2],
            ['name' => 'Evangelism Ministry', 'slug' => 'evangelism', 'level' => 2],
            ['name' => 'Finance Ministry', 'slug' => 'finance', 'level' => 2],
            ['name' => 'Organising And Assets Ministry', 'slug' => 'organising', 'level' => 2],
            ['name' => 'Welfare And Benevolence Ministry', 'slug' => 'welfare', 'level' => 2],
            ['name' => 'Preacher', 'slug' => 'preacher', 'level' => 3],
            ['name' => 'Admin', 'slug' => 'admin', 'level' => 4],
           
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
