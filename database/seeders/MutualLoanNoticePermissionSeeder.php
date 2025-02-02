<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\PLDNotice;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MutualLoanNoticePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $parentPermission = Permission::where('name', 'notification_pld')->first();
        if (!Permission::where('name', 'mutual_loan_credit')->exists()) {
            Permission::create([
                'name' => 'mutual_loan_credit',
                'guard_name' => 'web',
                'to' => null,
                'icon' => 'fa fa-circle',
                'heading' => 0,
                'menu_label' => 'Mutuo Préstamo y Crédito',
                'order_to_show' => null,
                'permission_id' => $parentPermission->id,
            ]);
        }
    }
}
