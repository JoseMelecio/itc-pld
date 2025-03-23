<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\PLDNotice;
use App\Models\Tenant;
use Illuminate\Database\Seeder;

class MutualLoanNoticeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tenants = Tenant::all();
        foreach ($tenants as $tenant) {
            if (! PLDNotice::where('route_param', 'mutual_loan_credit')->where('tenant_id', $tenant->id)->exists()) {
                PLDNotice::create([
                    'tenant_id' => $tenant->id,
                    'route_param' => 'mutual_loan_credit',
                    'name' => 'mutual loan credit',
                    'spanish_name' => 'mutuo prestamo y credito',
                    'template' => 'plantillaMutuoPrestamoCredito.xlsx',
                    'is_active' => true,
                ]);
            }

            $parentPermission = Permission::where('name', 'notification_pld')->where('tenant_id', $tenant->id)->first();
            if (! Permission::where('name', 'mutual_loan_credit')->where('tenant_id', $tenant->id)->exists()) {
                Permission::create([
                    'tenant_id' => $tenant->id,
                    'name' => 'mutual_loan_credit',
                    'guard_name' => 'web',
                    'to' => '/pld-notices/mutual_loan_credit',
                    'icon' => 'fa fa-circle',
                    'heading' => 0,
                    'menu_label' => 'Mutuo préstamo y crédito',
                    'order_to_show' => null,
                    'permission_id' => $parentPermission->id,
                ]);
            }
        }
    }
}
