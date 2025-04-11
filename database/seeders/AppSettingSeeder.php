<?php

namespace Database\Seeders;

use App\Models\AppSetting;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AppSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AppSetting::insert([
            [
                'code' => "S01",
                'name' => "Default Register Role",
                'value' => "1",
                'obj_type' => '0',
                'created_by' => "SYSTEM",
                'flag_active' => true,
                'created_at' => Carbon::now()
            ],
        ]);
    }
}
