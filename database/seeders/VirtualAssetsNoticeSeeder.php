<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\PLDNotice;
use Illuminate\Database\Seeder;

class VirtualAssetsNoticeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (! PLDNotice::where('route_param', 'virtual_assets')->exists()) {
            PLDNotice::create([
                'route_param' => 'virtual_assets',
                'name' => 'virtual assets',
                'spanish_name' => 'activos virtuales',
                'template' => 'plantillaActivosVirtuales.xlsx',
                'is_active' => true,
            ]);
        }

        $parentPermission = Permission::where('name', 'notification_pld')->first();
        if (! Permission::where('name', 'virtual_assets')->exists()) {
            Permission::create([
                'name' => 'virtual_assets',
                'guard_name' => 'web',
                'to' => '/pld-notices/virtual_assets',
                'icon' => 'fa fa-circle',
                'heading' => 0,
                'menu_label' => 'Activos Virtuales',
                'order_to_show' => null,
                'permission_id' => $parentPermission->id,
            ]);
        }
    }
}
