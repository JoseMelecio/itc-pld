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
        if (!Permission::where('name', 'person_bloqued')->exists()) {
            $parentPermission = Permission::where('name', 'menu')->first();
            Permission::create([
                'name' => 'person_bloqued',
                'guard_name' => 'web',
                'to' => '/',
                'icon' => 'fa fa-file-code',
                'heading' => false,
                'menu_label' => 'Pesonas Bloqueadas',
                'order_to_show' => null,
                'permission_id' => $parentPermission->id,
            ]);
        }
    }
}
