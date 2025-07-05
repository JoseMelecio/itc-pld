<?php

namespace Database\Seeders;

use App\Models\EBRType;
use App\Models\Tenant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AddInitialEBRTypesUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ebrTypes = EBRType::where('active', true)->pluck('id')->toArray();
        $tenants = Tenant::all();
        foreach ($tenants as $tenant) {
            $user = $tenant->users()->where('user_name', 'admin')->first();
            $user->ebrTypes()->sync($ebrTypes);
        }
    }
}
