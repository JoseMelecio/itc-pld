<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tenants = Tenant::all();
        foreach ($tenants as $tenant) {
            if (! User::where('user_name', 'admin')->where('tenant_id', $tenant->id)->exists()) {
                $user = User::create([
                    'tenant_id' => $tenant->id,
                    'user_name' => 'admin',
                    'name' => 'Administrador ' . $tenant->name,
                    'last_name' => 'Administrador ' . $tenant->name,
                    'email' => 'admin@admin.com',
                    'tax_id' => 'XAXX010101000',
                    'phone' => '+523333333333',
                    'password' => Hash::make('admin1234'),
                    'user_type' => 'admin',
                ]);

                $allPermissions = Permission::where('tenant_id', $tenant->id)->select('id')->get()->toArray();
                $user->syncPermissions($allPermissions);
            }
        }
    }
}
