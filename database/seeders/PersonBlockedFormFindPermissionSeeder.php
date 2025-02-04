<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\PLDNotice;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class PersonBlockedFormFinderPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $parentPermission = Permission::where('name', 'person_blocked')->first();
        if (!Permission::where('name', 'person_bloqued_form_finder')->exists()) {
            Permission::create([
                'name' => 'person_blocked_form_finder',
                'guard_name' => 'web',
                'to' => '/person_blocked_form_finder',
                'icon' => 'fa fa-circle',
                'heading' => 0,
                'menu_label' => 'Busqueda',
                'order_to_show' => null,
                'permission_id' => $parentPermission->id,
            ]);
        }
    }
}
