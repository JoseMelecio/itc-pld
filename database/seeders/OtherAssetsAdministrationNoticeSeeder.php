<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\PLDNotice;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OtherAssetsAdministrationNoticeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (! PLDNotice::where('route_param', 'other_assets_administration')->exists()) {
            PLDNotice::create([
                'route_param' => 'other_assets_administration',
                'name' => 'other assets administration',
                'spanish_name' => 'adminitracion de otros activos',
                'template' => 'plantillaAdministracionOtrosActivos.xlsx',
                'is_active' => true,
            ]);
        }

        $parentPermission = Permission::where('name', 'notification_pld')->first();
        if (! Permission::where('name', 'other_assets_administration')->exists()) {
            Permission::create([
                'name' => 'other_assets_administration',
                'guard_name' => 'web',
                'to' => '/pld-notices/other_assets_administration',
                'icon' => 'fa fa-circle',
                'heading' => 0,
                'menu_label' => 'Administración de Otros Activos',
                'order_to_show' => null,
                'permission_id' => $parentPermission->id,
            ]);
        }
    }
}
