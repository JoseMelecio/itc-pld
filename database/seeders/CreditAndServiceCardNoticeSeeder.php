<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\PLDNotice;
use App\Models\Tenant;
use Illuminate\Database\Seeder;

class CreditAndServiceCardNoticeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tenants = Tenant::all();
        foreach ($tenants as $tenant) {
            if (! PLDNotice::where('route_param', 'credit_and_service_card')->where('tenant_id', $tenant->id)->exists()) {
                PLDNotice::create([
                    'tenant_id' => $tenant->id,
                    'route_param' => 'credit_and_service_card',
                    'name' => 'credit and service card',
                    'spanish_name' => 'tarjetas de credito y servicio',
                    'template' => 'plantillaMutuoPrestamoCredito.xlsx',
                    'is_active' => true,
                ]);
            }

            $parentPermission = Permission::where('name', 'notification_pld')->where('tenant_id', $tenant->id)->first();
            if (! Permission::where('name', 'credit_and_service_card')->where('tenant_id', $tenant->id)->exists()) {
                Permission::create([
                    'tenant_id' => $tenant->id,
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
}
