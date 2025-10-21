<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class InitialMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (! Permission::where('name', 'dashboard')->exists()) {
            Permission::create([
                'name' => 'dashboard',
                'guard_name' => 'web',
                'to' => '/dashboard',
                'icon' => 'fa fa-rocket',
                'heading' => true,
                'menu_label' => 'Dashboard',
                'order_to_show' => 1,
                'permission_id' => null,
            ]);
        }

        if (! Permission::where('name', 'administration')->exists()) {
            Permission::create([
                'name' => 'administration',
                'guard_name' => 'web',
                'to' => null,
                'icon' => null,
                'heading' => true,
                'menu_label' => 'Administración',
                'order_to_show' => 2,
                'permission_id' => null,
            ]);
        }

        if (! Permission::where('name', 'users')->exists()) {
            $parentPermission = Permission::where('name', 'administration')->first();
            Permission::create([
                'name' => 'users',
                'guard_name' => 'web',
                'to' => '/users',
                'icon' => 'fa fa-users-gear',
                'heading' => false,
                'menu_label' => 'Usuarios',
                'order_to_show' => null,
                'permission_id' => $parentPermission->id,
            ]);
        }

        if (! Permission::where('name', 'logs')->exists()) {
            $parentPermission = Permission::where('name', 'administration')->first();
            Permission::create([
                'name' => 'logs',
                'guard_name' => 'web',
                'to' => '/',
                'icon' => 'fa fa-rectangle-list',
                'heading' => false,
                'menu_label' => 'Bitacoras',
                'order_to_show' => null,
                'permission_id' => $parentPermission->id,
            ]);
        }

        if (! Permission::where('name', 'pld_notice_logs')->exists()) {
            $parentPermission = Permission::where('name', 'logs')->first();
            Permission::create([
                'name' => 'pld_notice_logs',
                'guard_name' => 'web',
                'to' => '/logs/pld_notice',
                'icon' => 'fa fa-circle',
                'heading' => false,
                'menu_label' => 'Notificaciones',
                'order_to_show' => null,
                'permission_id' => $parentPermission->id,
            ]);
        }

        if (! Permission::where('name', 'account')->exists()) {
            Permission::create([
                'name' => 'account',
                'guard_name' => 'web',
                'to' => null,
                'icon' => null,
                'heading' => true,
                'menu_label' => 'Cuenta',
                'order_to_show' => 3,
                'permission_id' => null,
            ]);
        }

        if (! Permission::where('name', 'profile')->exists()) {
            $parentPermission = Permission::where('name', 'account')->first();
            Permission::create([
                'name' => 'profile',
                'guard_name' => 'web',
                'to' => '/profile',
                'icon' => 'fa fa-user-circle',
                'heading' => false,
                'menu_label' => 'Perfil',
                'order_to_show' => null,
                'permission_id' => $parentPermission->id,
            ]);
        }

        if (! Permission::where('name', 'menu')->exists()) {
            Permission::create([
                'name' => 'menu',
                'guard_name' => 'web',
                'to' => null,
                'icon' => null,
                'heading' => true,
                'menu_label' => 'Menú',
                'order_to_show' => 4,
                'permission_id' => null,
            ]);
        }

        if (! Permission::where('name', 'notification_pld')->exists()) {
            $parentPermission = Permission::where('name', 'menu')->first();
            Permission::create([
                'name' => 'notification_pld',
                'guard_name' => 'web',
                'to' => '/',
                'icon' => 'fa fa-file-code',
                'heading' => false,
                'menu_label' => 'Notificaciones PLD',
                'order_to_show' => null,
                'permission_id' => $parentPermission->id,
            ]);
        }

        if (! Permission::where('name', 'notification_pld_massive')->exists()) {
            $parentPermission = Permission::where('name', 'menu')->first();
            Permission::create([
                'name' => 'notification_pld_massive',
                'guard_name' => 'web',
                'to' => '/notification-pld-massive',
                'icon' => 'fa fa-copy',
                'heading' => false,
                'menu_label' => 'Notificaciones PLD Masivas',
                'order_to_show' => null,
                'permission_id' => $parentPermission->id,
            ]);
        }
    }
}
