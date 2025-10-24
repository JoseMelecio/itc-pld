<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class EBRPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (! Permission::where('name', 'ebr')->exists()) {
            $parentPermission = Permission::where('name', 'menu')->first();
            Permission::create([
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
