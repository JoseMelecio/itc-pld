<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\PLDNotice;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class PersonBlockedListPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $parentPermission = Permission::where('name', 'person_blocked')->first();
        if (!Permission::where('name', 'person_blocked_list')->exists()) {
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
