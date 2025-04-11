<?php

namespace Database\Seeders;

use App\Models\MUser;
use Illuminate\Database\Seeder;

class MUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MUser::factory(3)->create();
    }
}
