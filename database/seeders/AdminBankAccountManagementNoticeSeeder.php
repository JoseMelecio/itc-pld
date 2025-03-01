<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\PLDNotice;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminBankAccountManagementNoticeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!PLDNotice::where('route_param', 'bank_account_management')->exists()) {
            PLDNotice::create([
                'route_param' => 'bank_account_management',
                'name' => 'bank account management',
                'spanish_name' => 'administración de cuentas bancarias',
                'template' => 'plantillaAdministracionCuentasBancarias.xlsx',
                'is_active' => true,
            ]);
        }

        $parentPermission = Permission::where('name', 'notification_pld')->first();
        if (!Permission::where('name', 'bank_account_management')->exists()) {
            Permission::create([
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
