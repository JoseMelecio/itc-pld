<?php

namespace Database\Seeders;

use App\Models\Tenant;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class InitialMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tenants = Tenant::all();
        foreach ($tenants as $tenant) {
            if (! Permission::where('name', 'dashboard')->where('tenant_id', $tenant->id)->exists()) {
                Permission::create([
                    'tenant_id' => $tenant->id,
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

            if (! Permission::where('name', 'administration')->where('tenant_id', $tenant->id)->exists()) {
                Permission::create([
                    'tenant_id' => $tenant->id,
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

            if (! Permission::where('name', 'users')->where('tenant_id', $tenant->id)->exists()) {
                $parentPermission = Permission::where('name', 'administration')->where('tenant_id', $tenant->id)->first();
                Permission::create([
                    'tenant_id' => $tenant->id,
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

            if (! Permission::where('name', 'logs')->where('tenant_id', $tenant->id)->exists()) {
                $parentPermission = Permission::where('name', 'administration')->where('tenant_id', $tenant->id)->first();
                Permission::create([
                    'tenant_id' => $tenant->id,
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

            if (! Permission::where('name', 'pld_notice_logs')->where('tenant_id', $tenant->id)->exists()) {
                $parentPermission = Permission::where('name', 'logs')->where('tenant_id', $tenant->id)->first();
                Permission::create([
                    'tenant_id' => $tenant->id,
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

            if (! Permission::where('name', 'account')->where('tenant_id', $tenant->id)->exists()) {
                Permission::create([
                    'tenant_id' => $tenant->id,
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

            if (! Permission::where('name', 'profile')->where('tenant_id', $tenant->id)->exists()) {
                $parentPermission = Permission::where('name', 'account')->where('tenant_id', $tenant->id)->first();
                Permission::create([
                    'tenant_id' => $tenant->id,
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

            if (! Permission::where('name', 'menu')->where('tenant_id', $tenant->id)->exists()) {
                Permission::create([
                    'tenant_id' => $tenant->id,
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

            if (! Permission::where('name', 'notification_pld')->where('tenant_id', $tenant->id)->exists()) {
                $parentPermission = Permission::where('name', 'menu')->where('tenant_id', $tenant->id)->first();
                Permission::create([
                    'tenant_id' => $tenant->id,
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

            if (! Permission::where('name', 'notification_pld_massive')->where('tenant_id', $tenant->id)->exists()) {
                $parentPermission = Permission::where('name', 'menu')->where('tenant_id', $tenant->id)->first();
                Permission::create([
                    'tenant_id' => $tenant->id,
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
}
