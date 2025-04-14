<?php

namespace Database\Seeders;

use App\Models\MMenu;
use App\Models\MMenuGroupDetail;
use App\Models\MRole;
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
            'dashboard' => [1],
            'userRolePermission' => [2, 3, 4, 5, 6],
            'masterSensor' => [7, 8]
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
                    'flag_export' => true,
                    'flag_import' => true,
                    'flag_active' => true,
                    'created_by' => 'SYSTEM',
                    'obj_type' => '4',
                    'created_at' => Carbon::now()
                ];
            }
        }
        // $GrupDetailCreate[] = [
        //     'id_m_menu_groups' => 2,
        //     'id_m_menus' => 7,
        //     'flag_create' => true,
        //     'flag_read' => true,
        //     'flag_update' => true,
        //     'flag_delete' => true,
        //     'flag_export' => true,
        //     'flag_import' => true,
        //     'flag_active' => true,
        //     'created_by' => 'SYSTEM',
        //     'obj_type' => '4',
        //     'created_at' => Carbon::now()
        // ];
        // $GrupDetailCreate[] = [
        //     'id_m_menu_groups' => 2,
        //     'id_m_menus' => 8,
        //     'flag_create' => true,
        //     'flag_read' => true,
        //     'flag_update' => true,
        //     'flag_delete' => true,
        //     'flag_export' => true,
        //     'flag_import' => true,
        //     'flag_active' => true,
        //     'created_by' => 'SYSTEM',
        //     'obj_type' => '4',
        //     'created_at' => Carbon::now()
        // ];
        MMenuGroupDetail::insert($GrupDetailCreate);
    }
}
