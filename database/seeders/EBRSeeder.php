<?php

namespace Database\Seeders;

use App\Models\Tenant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class EBRSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tenants = Tenant::all();
        foreach ($tenants as $tenant) {

            if (! Permission::where('name', 'ebr')->where('tenant_id', $tenant->id)->exists()) {
                $parentPermission = Permission::where('name', 'menu')->where('tenant_id', $tenant->id)->first();
                Permission::create([
                    'tenant_id' => $tenant->id,
                    'name' => 'ebr',
                    'guard_name' => 'web',
                    'to' => '/ebr',
                    'icon' => 'fa fa-file-code',
                    'heading' => false,
                    'menu_label' => 'EBR',
                    'order_to_show' => null,
                    'permission_id' => $parentPermission->id,
                ]);
            }

        }
    }
}
