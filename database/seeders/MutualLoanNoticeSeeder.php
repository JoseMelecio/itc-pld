<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\PLDNotice;
use Illuminate\Database\Seeder;

class MutualLoanNoticeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (! PLDNotice::where('route_param', 'mutual_loan_credit')->exists()) {
            PLDNotice::create([
                'route_param' => 'mutual_loan_credit',
                'name' => 'mutual loan credit',
                'spanish_name' => 'mutuo prestamo y credito',
                'template' => 'plantillaMutuoPrestamoCredito.xlsx',
                'is_active' => true,
            ]);
        }

        $parentPermission = Permission::where('name', 'notification_pld')->first();
        if (! Permission::where('name', 'mutual_loan_credit')->exists()) {
            Permission::create([
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
