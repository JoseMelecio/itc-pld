<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\PLDNotice;
use Illuminate\Database\Seeder;

class VehicleSaleNoticeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!PLDNotice::where('route_param', 'vehicle_sale')->exists()) {
            PLDNotice::create([
                'route_param' => 'vehicle_sale',
                'name' => 'vehicle sale',
                'spanish_name' => 'venta de vehículos',
                'template' => 'plantillaVentaVehiculos.xlsx',
                'is_active' => true,
            ]);
        }

        $parentPermission = Permission::where('name', 'notification_pld')->first();
        if (!Permission::where('name', 'vehicle_sale')->exists()) {
            Permission::create([
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
