<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Tenant;
use Illuminate\Database\Seeder;

class PersonBlockedPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tenants = Tenant::all();
        foreach ($tenants as $tenant) {
            if (! Permission::where('name', 'person_blocked')->where('tenant_id', $tenant->id)->exists()) {
                $parentPermission = Permission::where('name', 'menu')->where('tenant_id', $tenant->id)->first();
                Permission::create([
                    'tenant_id' => $tenant->id,
                    'name' => 'person_blocked',
                    'guard_name' => 'web',
                    'to' => '/',
                    'icon' => 'fa fa-file-code',
                    'heading' => false,
                    'menu_label' => 'Pesonas Bloqueadas',
                    'order_to_show' => null,
                    'permission_id' => $parentPermission->id,
                ]);
            }

            $parentPermission = Permission::where('name', 'person_blocked')->where('tenant_id', $tenant->id)->first();
            if (! Permission::where('name', 'person_blocked_form_finder')->where('tenant_id', $tenant->id)->exists()) {
                Permission::create([
                    'tenant_id' => $tenant->id,
                    'name' => 'person_blocked_form_finder',
                    'guard_name' => 'web',
                    'to' => '/person-blocked-form-finder',
                    'icon' => 'fa fa-circle',
                    'heading' => 0,
                    'menu_label' => 'Busqueda',
                    'order_to_show' => null,
                    'permission_id' => $parentPermission->id,
                ]);
            }

            if (! Permission::where('name', 'person_blocked_list')->where('tenant_id', $tenant->id)->exists()) {
                Permission::create([
                    'tenant_id' => $tenant->id,
                    'name' => 'person_blocked_list',
                    'guard_name' => 'web',
                    'to' => '/person-blocked-list',
                    'icon' => 'fa fa-circle',
                    'heading' => 0,
                    'menu_label' => 'Listado',
                    'order_to_show' => null,
                    'permission_id' => $parentPermission->id,
                ]);
            }
        }
    }
}
