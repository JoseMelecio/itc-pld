<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\PLDNotice;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class VehicleSalePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $parentPermission = Permission::where('name', 'notification_pld')->first();
        $p = Permission::where('name', 'vehicle_sale')->first();
        Log::info($p);
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
