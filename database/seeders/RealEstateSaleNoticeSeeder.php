<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\PLDNotice;
use Illuminate\Database\Seeder;

class RealEstateSaleNoticeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!PLDNotice::where('route_param', 'real_estate_sale')->exists()) {
            PLDNotice::create([
                'route_param' => 'real_estate_sale',
                'name' => 'real estate sale',
                'spanish_name' => 'venta de inmuebles',
                'template' => 'plantillaVentaInmuebles.xlsx',
                'is_active' => true,
            ]);
        }

        $parentPermission = Permission::where('name', 'notification_pld')->first();
        if (!Permission::where('name', 'real_estate_sale')->exists()) {
            Permission::create([
                'name' => 'real_estate_sale',
                'guard_name' => 'web',
                'to' => '/pld-notices/real_estate_sale',
                'icon' => 'fa fa-circle',
                'heading' => 0,
                'menu_label' => 'Venta de inmuebles',
                'order_to_show' => null,
                'permission_id' => $parentPermission->id,
            ]);
        }
    }
}
