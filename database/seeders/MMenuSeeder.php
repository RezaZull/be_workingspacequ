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
            [//1
                'name' => "Dashboard",
                'route' => "dashboard",
                'description' => "Dashboard User",
                'obj_type' => '1',
                'created_by' => "SYSTEM",
                'flag_active' => true,
                'created_at' => Carbon::now()
            ],
            [//2
                'name' => "Master Menu",
                'route' => "mastermenu",
                'description' => "Master Menu",
                'obj_type' => '1',
                'created_by' => "SYSTEM",
                'flag_active' => true,
                'created_at' => Carbon::now()
            ],
            [//3
                'name' => "Master User",
                'route' => "masteruser",
                'description' => "Master User",
                'obj_type' => '1',
                'created_by' => "SYSTEM",
                'flag_active' => true,
                'created_at' => Carbon::now()
            ],
            [//4
                'name' => "Master Role",
                'route' => "masterrole",
                'description' => "Master Role User",
                'obj_type' => '1',
                'created_by' => "SYSTEM",
                'flag_active' => true,
                'created_at' => Carbon::now()
            ],
            [//5
                'name' => "Master Menu Group",
                'route' => "mastermenugroup",
                'description' => "Master Menu group",
                'obj_type' => '1',
                'created_by' => "SYSTEM",
                'flag_active' => true,
                'created_at' => Carbon::now()
            ],
            [//6
                'name' => "APP Setting",
                'route' => "appsetting",
                'description' => "App Setting",
                'obj_type' => '1',
                'created_by' => "SYSTEM",
                'flag_active' => true,
                'created_at' => Carbon::now()
            ],
            [//7
                'name' => "Sensor",
                'route' => "sensor",
                'description' => "master sensor",
                'obj_type' => '1',
                'created_by' => "SYSTEM",
                'flag_active' => true,
                'created_at' => Carbon::now()
            ],
            [//8
                'name' => "Unit",
                'route' => "unit",
                'description' => "master unit",
                'obj_type' => '1',
                'created_by' => "SYSTEM",
                'flag_active' => true,
                'created_at' => Carbon::now()
            ],
            [//9
                'name' => "Room",
                'route' => "room",
                'description' => "master room",
                'obj_type' => '1',
                'created_by' => "SYSTEM",
                'flag_active' => true,
                'created_at' => Carbon::now()
            ],
            [//10
                'name' => "Room Type",
                'route' => "roomType",
                'description' => "master room type",
                'obj_type' => '1',
                'created_by' => "SYSTEM",
                'flag_active' => true,
                'created_at' => Carbon::now()
            ],
            [//11
                'name' => "Product List",
                'route' => "productlist",
                'description' => "Cart",
                'obj_type' => '1',
                'created_by' => "SYSTEM",
                'flag_active' => true,
                'created_at' => Carbon::now()
            ],
            [//12
                'name' => "Cart",
                'route' => "cart",
                'description' => "Cart",
                'obj_type' => '1',
                'created_by' => "SYSTEM",
                'flag_active' => true,
                'created_at' => Carbon::now()
            ],
            [//13
                'name' => "Booking",
                'route' => "booking",
                'description' => "Booking",
                'obj_type' => '1',
                'created_by' => "SYSTEM",
                'flag_active' => true,
                'created_at' => Carbon::now()
            ],
            [//14
                'name' => "Feedback",
                'route' => "feedback",
                'description' => "Feedback",
                'obj_type' => '1',
                'created_by' => "SYSTEM",
                'flag_active' => true,
                'created_at' => Carbon::now()
            ],
        ]);
    }
}
