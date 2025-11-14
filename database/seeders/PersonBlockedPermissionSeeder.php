<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PersonBlockedPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (! Permission::where('name', 'person_blocked')->exists()) {
            $parentPermission = Permission::where('name', 'menu')->first();
            Permission::create([
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

        $parentPermission = Permission::where('name', 'person_blocked')->first();
        if (! Permission::where('name', 'person_blocked_form_finder')->exists()) {
            Permission::create([
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

        if (! Permission::where('name', 'person_blocked_form_finder_massive')->exists()) {
            Permission::create([
                'name' => 'person_blocked_form_finder_massive',
                'guard_name' => 'web',
                'to' => '/person-blocked-form-finder-massive    ',
                'icon' => 'fa fa-circle',
                'heading' => 0,
                'menu_label' => 'Busqueda Masiva',
                'order_to_show' => null,
                'permission_id' => $parentPermission->id,
            ]);
        }

        if (! Permission::where('name', 'person_blocked_form_create')->exists()) {
            Permission::create([
                'name' => 'person_blocked_form_create',
                'guard_name' => 'web',
                'to' => '/person-blocked-form-create',
                'icon' => 'fa fa-circle',
                'heading' => 0,
                'menu_label' => 'Agregar',
                'order_to_show' => null,
                'permission_id' => $parentPermission->id,
            ]);
        }

        if (! Permission::where('name', 'person_blocked_list')->exists()) {
            Permission::create([
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
