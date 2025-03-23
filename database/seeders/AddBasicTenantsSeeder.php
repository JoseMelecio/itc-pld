<?php

namespace Database\Seeders;

use App\Models\Tenant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AddBasicTenantsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminTenant = Tenant::where('name', 'admin')->first();
        if (!$adminTenant) {
            Tenant::create([
                'name' => 'admin',
                'description' => 'Tenant administrador',
                'status' => 'active',
            ]);
        }

        $pldTenant = Tenant::where('name', 'pld')->first();
        if (!$pldTenant) {
        Tenant::create([
            'name' => 'pld',
            'description' => 'Sistema basico de rentas',
            'status' => 'active',
        ]);
        }
    }
}
