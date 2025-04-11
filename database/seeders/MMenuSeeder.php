<?php

namespace Database\Seeders;

use App\Models\MMenu;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MMenu::insert([
            [
                'name' => "Dashboard",
                'route' => "dashboard",
                'description' => "Dashboard User",
                'obj_type' => '1',
                'created_by' => "SYSTEM",
                'flag_active' => true,
                'created_at' => Carbon::now()
            ],
            [
                'name' => "Master Menu",
                'route' => "mastermenu",
                'description' => "Master Menu",
                'obj_type' => '1',
                'created_by' => "SYSTEM",
                'flag_active' => true,
                'created_at' => Carbon::now()
            ],
            [
                'name' => "Master User",
                'route' => "masteruser",
                'description' => "Master User",
                'obj_type' => '1',
                'created_by' => "SYSTEM",
                'flag_active' => true,
                'created_at' => Carbon::now()
            ],
            [
                'name' => "Master Role",
                'route' => "masterrole",
                'description' => "Master Role User",
                'obj_type' => '1',
                'created_by' => "SYSTEM",
                'flag_active' => true,
                'created_at' => Carbon::now()
            ],
            [
                'name' => "Master Menu Group",
                'route' => "mastermenugroup",
                'description' => "Master Menu group",
                'obj_type' => '1',
                'created_by' => "SYSTEM",
                'flag_active' => true,
                'created_at' => Carbon::now()
            ],
            [
                'name' => "APP Setting",
                'route' => "appsetting",
                'description' => "App Setting",
                'obj_type' => '1',
                'created_by' => "SYSTEM",
                'flag_active' => true,
                'created_at' => Carbon::now()
            ],
        ]);
    }
}
