<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\PLDNotice;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RealEstateAdministrationNoticeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (! PLDNotice::where('route_param', 'real_estate_administration')->exists()) {
            PLDNotice::create([
                'route_param' => 'real_estate_administration',
                'name' => 'real estate administration',
                'spanish_name' => 'adminitracion de inmuebles',
                'template' => 'plantillaAdministracionInmuebles.xlsx',
                'is_active' => true,
            ]);
        }

        $parentPermission = Permission::where('name', 'notification_pld')->first();
        if (! Permission::where('name', 'real_estate_administration')->exists()) {
            Permission::create([
                'name' => 'real_estate_administration',
                'guard_name' => 'web',
                'to' => '/pld-notices/real_estate_administration',
                'icon' => 'fa fa-circle',
                'heading' => 0,
                'menu_label' => 'Administración de Inmuebles',
                'order_to_show' => null,
                'permission_id' => $parentPermission->id,
            ]);
        }
    }
}
