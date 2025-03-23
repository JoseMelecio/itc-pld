<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\PLDNotice;
use App\Models\Tenant;
use Illuminate\Database\Seeder;

class RealEstateDevelopmentNoticeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tenants = Tenant::all();
        foreach ($tenants as $tenant) {
            if (! PLDNotice::where('route_param', 'real_estate_development')->where('tenant_id', $tenant->id)->exists()) {
                PLDNotice::create([
                    'tenant_id' => $tenant->id,
                    'route_param' => 'real_estate_development',
                    'name' => 'real estate development',
                    'spanish_name' => 'desarrollo de inmuebles',
                    'template' => 'plantillaDesarrolloInmuebles.xlsx',
                    'is_active' => true,
                ]);
            }

            $parentPermission = Permission::where('name', 'notification_pld')->where('tenant_id', $tenant->id)->first();
            if (! Permission::where('name', 'real_estate_development')->where('tenant_id', $tenant->id)->exists()) {
                Permission::create([
                    'tenant_id' => $tenant->id,
                    'name' => 'real_estate_development',
                    'guard_name' => 'web',
                    'to' => '/pld-notices/real_estate_development',
                    'icon' => 'fa fa-circle',
                    'heading' => 0,
                    'menu_label' => 'Desarrollo de inmuebles',
                    'order_to_show' => null,
                    'permission_id' => $parentPermission->id,
                ]);
            }
        }
    }
}
