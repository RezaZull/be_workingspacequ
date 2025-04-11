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
        $DataMenu = MMenu::get();
        $GrupDetailCreate = [];
        foreach ($DataMenu as $data) {
            $GrupDetailCreate[] = [
                'id_m_menu_groups' => 1,
                'id_m_menus' => $data->id,
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
        MMenuGroupDetail::insert($GrupDetailCreate);
    }
}
