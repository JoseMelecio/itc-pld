<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\PLDNotice;
use App\Models\Tenant;
use Illuminate\Database\Seeder;

class VehicleSaleNoticeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tenants = Tenant::all();
        foreach ($tenants as $tenant) {
            if (! PLDNotice::where('route_param', 'vehicle_sale')->where('tenant_id', $tenant->id)->exists()) {
                PLDNotice::create([
                    'tenant_id' => $tenant->id,
                    'route_param' => 'vehicle_sale',
                    'name' => 'vehicle sale',
                    'spanish_name' => 'venta de vehículos',
                    'template' => 'plantillaVentaVehiculos.xlsx',
                    'is_active' => true,
                ]);
            }

            $parentPermission = Permission::where('name', 'notification_pld')->where('tenant_id', $tenant->id)->first();
            if (! Permission::where('name', 'vehicle_sale')->where('tenant_id', $tenant->id)->exists()) {
                Permission::create([
                    'tenant_id' => $tenant->id,
                    'name' => 'vehicle_sale',
                    'guard_name' => 'web',
                    'to' => '/pld-notices/vehicle_sale',
                    'icon' => 'fa fa-circle',
                    'heading' => 0,
                    'menu_label' => 'Venta de vehículos',
                    'order_to_show' => null,
                    'permission_id' => $parentPermission->id,
                ]);
            }
        }
    }
}
