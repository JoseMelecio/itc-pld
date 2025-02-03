<?php

namespace Database\Seeders;

use App\Models\PLDNotice;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RealEstateSaleNoticeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!PLDNotice::where('route_param', 'real_estate_sale')->exists()) {
            PLDNotice::create([
                'route_param' => 'real_estate_sale',
                'name' => 'real estate sale',
                'spanish_name' => 'venta de inmuebles',
                'template' => 'plantillaVentaInmuebles.xlsx',
                'is_active' => true,
            ]);
        }
    }
}
