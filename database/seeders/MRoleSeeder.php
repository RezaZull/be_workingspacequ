<?php

namespace Database\Seeders;

use App\Models\MRole;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MRole::insert([
            [
                'name' => "Admin",
                'obj_type' => '2',
                'created_by' => "SYSTEM",
                'flag_active' => true,
                'created_at' => Carbon::now()
            ],
            [
                'name' => "User",
                'obj_type' => '2',
                'created_by' => "SYSTEM",
                'flag_active' => true,
                'created_at' => Carbon::now()
            ],
        ]);
    }
}
