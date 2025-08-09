<?php

namespace Database\Seeders;

use App\Models\MMenuGroupDetail;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class MMenuGroupDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $DataMenu = MMenu::get();
        $dataMenuList = [
            //admin
            'dashboard' => [1],
            'userRolePermission' => [2, 3, 4, 5, 6],
            'masterSensor' => [7, 8],
            'masterRoom' => [9, 10],
            'productList' => [11],
            'cart' => [12],
            'booking' => [13],
            'history' => [15],
            //user
            'dashboardUser' => [1],
            'productListUser' => [11],
            'cartUser' => [12],
            'bookingUser' => [13],
            'historyUser' => [15],
        ];
        $keysDataMenuList = array_keys($dataMenuList);
        $GrupDetailCreate = [];
        foreach ($keysDataMenuList as $idx => $key) {
            foreach ($dataMenuList[$key] as $id_m_menus) {
                $GrupDetailCreate[] = [
                    'id_m_menu_groups' => $idx + 1,
                    'id_m_menus' => $id_m_menus,
                    'flag_create' => true,
                    'flag_read' => true,
                    'flag_update' => true,
                    'flag_delete' => true,
                    'flag_active' => true,
                    'created_by' => 'SYSTEM',
                    'obj_type' => '4',
                    'created_at' => Carbon::now()
                ];
            }
        }
        $GrupDetailCreate[] = [
            'id_m_menu_groups' => 14,
            'id_m_menus' => 9,
            'flag_create' => false,
            'flag_read' => true,
            'flag_update' => false,
            'flag_delete' => false,
            'flag_active' => true,
            'created_by' => 'SYSTEM',
            'obj_type' => '4',
            'created_at' => Carbon::now()
        ];
        MMenuGroupDetail::insert($GrupDetailCreate);
    }
}
