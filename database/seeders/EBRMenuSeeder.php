<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class EBRMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (! Permission::where('name', 'EBR')->exists()) {
            $parentPermission = Permission::where('name', 'menu')->first();
            Permission::create([
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

        if (! Permission::where('name', 'ebr_generate')->exists()) {
            $parentPermission = Permission::where('name', 'EBR')->first();
            Permission::create([
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

        if (! Permission::where('name', 'ebr_config')->exists()) {
            $parentPermission = Permission::where('name', 'EBR')->first();
            Permission::create([
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

        if (! Permission::where('name', 'ebr_inherent_risk_catalog')->exists()) {
            $parentPermission = Permission::where('name', 'EBR')->first();
            Permission::create([
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

        if (! Permission::where('name', 'ebr_indicators_risk_catalog')->exists()) {
            $parentPermission = Permission::where('name', 'EBR')->first();
            Permission::create([
                'name' => 'ebr_indicators_risk_catalog',
                'guard_name' => 'web',
                'to' => '/ebr_indicators_risk_catalog',
                'icon' => 'fa fa-circle',
                'heading' => false,
                'menu_label' => 'Cat. Indicadores de Riesgo',
                'order_to_show' => null,
                'permission_id' => $parentPermission->id,
            ]);
        }


        if (! Permission::where('name', 'ebr_risk_zones_catalog')->exists()) {
            $parentPermission = Permission::where('name', 'EBR')->first();
            Permission::create([
                'name' => 'ebr_risk_zones_catalog',
                'guard_name' => 'web',
                'to' => '/ebr-risk-zones-catalog',
                'icon' => 'fa fa-circle',
                'heading' => false,
                'menu_label' => 'Cat. Zonas de Riesgo',
                'order_to_show' => null,
                'permission_id' => $parentPermission->id,
            ]);
        }
    }
}
