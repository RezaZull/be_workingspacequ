<?php

namespace Database\Seeders;

use App\Models\MUser;
use Hash;
use Illuminate\Database\Seeder;

class MUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MUser::factory(3)->create();
        MUser::insert([
            [
                'first_name' => 'admin',
                'last_name' => 'galang',
                'username' => 'admin',
                'email' => 'admin@galang.com',
                'password' => Hash::make('admin'),
                'id_m_roles' => 1,
                'obj_type' => 5,
                'created_by' => "SYSTEM",
                'img_path' => "storage/images/profile/images.webp",
                'flag_active' => true,
            ],
            [
                'first_name' => 'user',
                'last_name' => 'baek',
                'username' => 'user',
                'email' => 'user@example.com',
                'password' => Hash::make('user'),
                'id_m_roles' => 2,
                'obj_type' => 5,
                'created_by' => "SYSTEM",
                'img_path' => "storage/images/profile/images.webp",
                'flag_active' => true,
            ],
        ]);
    }
}
