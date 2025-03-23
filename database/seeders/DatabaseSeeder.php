<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AddBasicTenantsSeeder::class,
            InitialMenuSeeder::class,
            RealEstateLeasingNoticeSeeder::class,
            MutualLoanNoticeSeeder::class,
            RealEstateDevelopmentNoticeSeeder::class,
            RealEstateLeasingNoticeSeeder::class,
            RealEstateSaleNoticeSeeder::class,
            VehicleSaleNoticeSeeder::class,
            PersonBlockedPermissionSeeder::class,
            AdminBankAccountManagementNoticeSeeder::class,
            AdminBankAccountCustomFieldsSeeder::class,
            //Last seeder to sync permissions
            UserSeeder::class,
        ]);
    }
}
