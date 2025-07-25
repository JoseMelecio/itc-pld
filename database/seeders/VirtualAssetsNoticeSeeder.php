<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\PLDNotice;
use App\Models\Tenant;
use Illuminate\Database\Seeder;

class VirtualAssetsNoticeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tenants = Tenant::all();
        foreach ($tenants as $tenant) {
            if (! PLDNotice::where('route_param', 'virtual_assets')->where('tenant_id', $tenant->id)->exists()) {
                PLDNotice::create([
                    'tenant_id' => $tenant->id,
                    'route_param' => 'virtual_assets',
                    'name' => 'virtual assets',
                    'spanish_name' => 'activos virtuales',
                    'template' => 'plantillaActivosVirtuales.xlsx',
                    'is_active' => true,
                ]);
            }

            $parentPermission = Permission::where('name', 'notification_pld')->where('tenant_id', $tenant->id)->first();
            if (! Permission::where('name', 'virtual_assets')->where('tenant_id', $tenant->id)->exists()) {
                Permission::create([
                    'tenant_id' => $tenant->id,
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
}
