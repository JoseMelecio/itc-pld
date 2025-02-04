<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\PLDNotice;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class RealEstateDevelopmentPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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
