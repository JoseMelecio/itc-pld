<?php

namespace Database\Seeders;

use App\Models\PLDNotice;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RealEstateDevelopmentNoticeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!PLDNotice::where('route_param', 'real_estate_development')->exists()) {
            PLDNotice::create([
                'route_param' => 'real_estate_development',
                'name' => 'real estate development',
                'spanish_name' => 'desarrollo de inmuebles',
                'template' => 'plantillaDesarrolloInmuebles.xlsx',
                'is_active' => true,
            ]);
        }
    }
}
