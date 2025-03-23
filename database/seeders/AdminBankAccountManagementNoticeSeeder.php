<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\PLDNotice;
use App\Models\Tenant;
use Illuminate\Database\Seeder;

class AdminBankAccountManagementNoticeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tenants = Tenant::all();
        foreach ($tenants as $tenant) {
            if (! PLDNotice::where('route_param', 'bank_account_management')->where('tenant_id', $tenant->id)->exists()) {
                PLDNotice::create([
                    'tenant_id' => $tenant->id,
                    'route_param' => 'bank_account_management',
                    'name' => 'bank account management',
                    'spanish_name' => 'administración de cuentas bancarias',
                    'template' => 'plantillaAdministracionCuentasBancarias.xlsx',
                    'is_active' => true,
                ]);
            }

            $parentPermission = Permission::where('name', 'notification_pld')->where('tenant_id', $tenant->id)->first();
            if (! Permission::where('name', 'bank_account_management')->where('tenant_id', $tenant->id)->exists()) {
                Permission::create([
                    'tenant_id' => $tenant->id,
                    'name' => 'bank_account_management',
                    'guard_name' => 'web',
                    'to' => '/pld-notices/bank_account_management',
                    'icon' => 'fa fa-circle',
                    'heading' => 0,
                    'menu_label' => 'Administración de cuentas bancarias',
                    'order_to_show' => null,
                    'permission_id' => $parentPermission->id,
                ]);
            }
        }

    }
}
