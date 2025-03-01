<?php

namespace Database\Seeders;

use App\Models\Permission;
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
        if (! User::where('user_name', 'admin')->exists()) {
            $user = User::create([
                'user_name' => 'admin',
                'name' => 'Administrador',
                'last_name' => 'Administrador',
                'email' => 'admin@admin.com',
                'tax_id' => 'XAXX010101000',
                'phone' => '+523333333333',
                'password' => Hash::make('admin1234'),
                'user_type' => 'admin',
            ]);

            $allPermissions = Permission::all()->select('id')->toArray();
            $user->syncPermissions($allPermissions);
        }
    }
}
