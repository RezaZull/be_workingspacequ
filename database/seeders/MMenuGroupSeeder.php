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
            //ADMIN
            [//1
                'id_m_roles' => 1,
                'name' => "Dashboard",
                'obj_type' => '3',
                'flag_active' => true,
                'created_by' => "SYSTEM",
                'created_at' => Carbon::now()
            ],
            [//2
                'id_m_roles' => 1,
                'name' => "User & Role Permission",
                'obj_type' => '3',
                'flag_active' => true,
                'created_by' => "SYSTEM",
                'created_at' => Carbon::now()
            ],
            [//3
                'id_m_roles' => 1,
                'name' => "Master Sensor",
                'obj_type' => '3',
                'flag_active' => true,
                'created_by' => "SYSTEM",
                'created_at' => Carbon::now()
            ],
            [//3
                'id_m_roles' => 1,
                'name' => "Master Room",
                'obj_type' => '3',
                'flag_active' => true,
                'created_by' => "SYSTEM",
                'created_at' => Carbon::now()
            ],
            [//4
                'id_m_roles' => 1,
                'name' => "Product List",
                'obj_type' => '3',
                'flag_active' => true,
                'created_by' => "SYSTEM",
                'created_at' => Carbon::now()
            ],
            [//5
                'id_m_roles' => 1,
                'name' => "Cart",
                'obj_type' => '3',
                'flag_active' => true,
                'created_by' => "SYSTEM",
                'created_at' => Carbon::now()
            ],
            [//6
                'id_m_roles' => 1,
                'name' => "Booking",
                'obj_type' => '3',
                'flag_active' => true,
                'created_by' => "SYSTEM",
                'created_at' => Carbon::now()
            ],
            [//7
                'id_m_roles' => 1,
                'name' => "History",
                'obj_type' => '3',
                'flag_active' => true,
                'created_by' => "SYSTEM",
                'created_at' => Carbon::now()
            ],
            //USER
            [//8
                'id_m_roles' => 2,
                'name' => "Dashboard",
                'obj_type' => '3',
                'flag_active' => true,
                'created_by' => "SYSTEM",
                'created_at' => Carbon::now()
            ],
            [//9
                'id_m_roles' => 2,
                'name' => "Product List",
                'obj_type' => '3',
                'flag_active' => true,
                'created_by' => "SYSTEM",
                'created_at' => Carbon::now()
            ],
            [//10
                'id_m_roles' => 2,
                'name' => "Cart",
                'obj_type' => '3',
                'flag_active' => true,
                'created_by' => "SYSTEM",
                'created_at' => Carbon::now()
            ],
            [//11
                'id_m_roles' => 2,
                'name' => "Booking",
                'obj_type' => '3',
                'flag_active' => true,
                'created_by' => "SYSTEM",
                'created_at' => Carbon::now()
            ],
            [//12
                'id_m_roles' => 2,
                'name' => "History",
                'obj_type' => '3',
                'flag_active' => true,
                'created_by' => "SYSTEM",
                'created_at' => Carbon::now()
            ],
            [//13
                'id_m_roles' => 2,
                'name' => "Product",
                'obj_type' => '3',
                'flag_active' => true,
                'created_by' => "SYSTEM",
                'created_at' => Carbon::now()
            ],
        ]);
    }
}
