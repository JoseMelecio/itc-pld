<?php

namespace Database\Seeders;

use App\Models\PLDNotice;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MutualLoanNoticeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!PLDNotice::where('route_param', 'mutual_loan_credit')->exists()) {
            PLDNotice::create([
                'route_param' => 'mutual_loan_credit',
                'name' => 'mutual loan credit',
                'spanish_name' => 'mutuo prestamo y credito',
                'template' => 'plantillaMutuoPrestamoCredito.xlsx',
                'is_active' => true,
            ]);
        }
    }
}
