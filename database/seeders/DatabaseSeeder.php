<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Seeders\eliminar\EBRTemplateCompositionSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // PLD Seeders
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
            // EBR Seeders
            EBRPermissionSeeder::class,
            //EBRTemplateCompositionSeeder::class,
            EBRTypeSeeder::class,
            //EBRSofomRiskElementsSeeder::class,
            EBRMatrixQualitativeRiskSeeder::class,
            //Last seeder to sync permissions
            UserSeeder::class,
            EBRPermissionSeeder::class,
            EBRMenuSeeder::class,
        ]);
    }
}
