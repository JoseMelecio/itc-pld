<?php

namespace Database\Seeders;

use App\Models\EBRType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EBRTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EBRType::create([
            'type' => 'sofom',
            'active' => true,
        ]);
    }
}
