<?php

namespace Database\Seeders;

use App\Models\Tenant;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class EBRMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tenants = Tenant::all();
        foreach ($tenants as $tenant) {
            if (! Permission::where('name', 'EBR')->where('tenant_id', $tenant->id)->exists()) {
                $parentPermission = Permission::where('name', 'menu')->where('tenant_id', $tenant->id)->first();
                Permission::create([
                    'tenant_id' => $tenant->id,
                    'name' => 'ebr',
                    'guard_name' => 'web',
                    'to' => '/',
                    'icon' => 'fa fa-file-code',
                    'heading' => false,
                    'menu_label' => 'EBR',
                    'order_to_show' => null,
                    'permission_id' => $parentPermission->id,
                ]);
            }

            if (! Permission::where('name', 'ebr_generate')->where('tenant_id', $tenant->id)->exists()) {
                $parentPermission = Permission::where('name', 'EBR')->where('tenant_id', $tenant->id)->first();
                Permission::create([
                    'tenant_id' => $tenant->id,
                    'name' => 'ebr_generate',
                    'guard_name' => 'web',
                    'to' => '/ebr',
                    'icon' => 'fa fa-circle',
                    'heading' => false,
                    'menu_label' => 'Generar',
                    'order_to_show' => null,
                    'permission_id' => $parentPermission->id,
                ]);
            }

            if (! Permission::where('name', 'ebr_config')->where('tenant_id', $tenant->id)->exists()) {
                $parentPermission = Permission::where('name', 'EBR')->where('tenant_id', $tenant->id)->first();
                Permission::create([
                    'tenant_id' => $tenant->id,
                    'name' => 'ebr_config',
                    'guard_name' => 'web',
                    'to' => '/ebr-configuration',
                    'icon' => 'fa fa-circle',
                    'heading' => false,
                    'menu_label' => 'Configuracion',
                    'order_to_show' => null,
                    'permission_id' => $parentPermission->id,
                ]);
            }

            if (! Permission::where('name', 'ebr_inherent_risk_catalog')->where('tenant_id', $tenant->id)->exists()) {
                $parentPermission = Permission::where('name', 'EBR')->where('tenant_id', $tenant->id)->first();
                Permission::create([
                    'tenant_id' => $tenant->id,
                    'name' => 'ebr_inherent_risk_catalog',
                    'guard_name' => 'web',
                    'to' => '/ebr_inherent_risk_catalog',
                    'icon' => 'fa fa-circle',
                    'heading' => false,
                    'menu_label' => 'Cat. Riesgos Inherentes',
                    'order_to_show' => null,
                    'permission_id' => $parentPermission->id,
                ]);
            }
        }

    }
}
