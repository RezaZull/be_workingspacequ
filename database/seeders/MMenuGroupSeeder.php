<?php

namespace Database\Seeders;

use App\Models\MMenuGroup;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class MMenuGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MMenuGroup::insert([
            [
                'id_m_roles' => 1,
                'name' => "Account",
                'obj_type' => '3',
                'flag_active' => true,
                'created_by' => "SYSTEM",
                'created_at' => Carbon::now()
            ]
        ]);
    }
}
