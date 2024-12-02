<?php

namespace Database\Seeders;

use App\Models\PLDNotice;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RealEstateLeasingNoticeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!PLDNotice::where('route_param', 'real_estate_leasing')->exists()) {
            PLDNotice::create([
                'route_param' => 'real_estate_leasing',
                'name' => 'real estate leasing',
                'spanish_name' => 'arrendamiento de inmuebles',
                'template' => 'plantillaArrendamientoInmuebles.xlsx',
                'is_active' => true,
            ]);
        }
    }
}
