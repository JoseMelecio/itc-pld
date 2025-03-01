<?php

namespace Database\Seeders;

use App\Models\PLDNotice;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class RealEstateLeasingNoticeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!PLDNotice::where('route_param', 'real_estate_leasing')->exists()) {
            PLDNotice::create([
                'route_param' => 'real_estate_leasing',
                'name' => 'real estate leasing',
                'spanish_name' => 'arrendamiento de inmuebles',
                'template' => 'plantillaArrendamientoInmuebles.xlsx',
                'is_active' => true,
            ]);
        }

        if (!Permission::where('name', 'real_estate_leasing')->exists()) {
            $parentPermission = Permission::where('name', 'notification_pld')->first();
            Permission::create([
                'name' => 'real_estate_leasing',
                'guard_name' => 'web',
                'to' => '/pld-notices/real_estate_leasing',
                'icon' => 'fa fa-circle',
                'heading' => false,
                'menu_label' => 'Arrendamiento de inmuebles',
                'order_to_show' => null,
                'permission_id' => $parentPermission->id,
            ]);
        }
    }
}
