<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\PLDNotice;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class RealEstateSalePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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
