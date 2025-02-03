<?php

namespace Database\Seeders;

use App\Models\PLDNotice;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VehicleSaleNoticeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!PLDNotice::where('route_param', 'vehicle_sale')->exists()) {
            PLDNotice::create([
                'route_param' => 'vehicle_sale',
                'name' => 'vehicle sale',
                'spanish_name' => 'venta de vehículos',
                'template' => 'plantillaVentaVehiculos.xlsx',
                'is_active' => true,
            ]);
        }
    }
}
