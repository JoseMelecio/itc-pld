<?php

namespace Database\Seeders;

use App\Models\User;
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
            InitialMenuSeeder::class,
            RealEstateLeasingNoticeSeeder::class,
            MutualLoanNoticeSeeder::class,
            MutualLoanNoticePermissionSeeder::class,
            //Last seeder to sync permissions
            UserSeeder::class,
        ]);
    }
}
