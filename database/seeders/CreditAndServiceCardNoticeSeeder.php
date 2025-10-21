<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\PLDNotice;
use Illuminate\Database\Seeder;

class CreditAndServiceCardNoticeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (! PLDNotice::where('route_param', 'credit_and_service_card')->exists()) {
            PLDNotice::create([
                'route_param' => 'credit_and_service_card',
                'name' => 'credit and service card',
                'spanish_name' => 'tarjetas de credito y servicio',
                'template' => 'plantillaMutuoPrestamoCredito.xlsx',
                'is_active' => true,
            ]);
        }

        $parentPermission = Permission::where('name', 'notification_pld')->first();
        if (! Permission::where('name', 'credit_and_service_card')->exists()) {
            Permission::create([
                'name' => 'credit_and_service_card',
                'guard_name' => 'web',
                'to' => '/pld-notices/credit_and_service_card',
                'icon' => 'fa fa-circle',
                'heading' => 0,
                'menu_label' => 'Tarjetas Credito y Servicio',
                'order_to_show' => null,
                'permission_id' => $parentPermission->id,
            ]);
        }
    }
}
