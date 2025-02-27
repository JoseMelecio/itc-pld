<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\PLDNotice;
use Illuminate\Database\Seeder;

class RealEstateDevelopmentNoticeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!PLDNotice::where('route_param', 'real_estate_development')->exists()) {
            PLDNotice::create([
                'route_param' => 'real_estate_development',
                'name' => 'real estate development',
                'spanish_name' => 'desarrollo de inmuebles',
                'template' => 'plantillaDesarrolloInmuebles.xlsx',
                'is_active' => true,
            ]);
        }

        $parentPermission = Permission::where('name', 'notification_pld')->first();
        if (!Permission::where('name', 'real_estate_development')->exists()) {
            Permission::create([
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
