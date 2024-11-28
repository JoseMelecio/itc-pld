<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!User::where('email', 'jrr.melecio@gmail.com')->exists()) {
            User::create([
                'name' => 'Jose Refugio',
                'last_name' => 'Melecio Soto',
                'email' => 'jrr.melecio@gmail.com',
                'tax_id' => 'XAXX010101000',
                'phone' => '+523334531396',
                'password' => Hash::make('admin1234'),
            ]);
        }

        if (!User::where('email', 'omargustavoa@gmail.com')->exists()) {
            User::create([
                'name' => 'Omar Gustavo',
                'last_name' => 'Ayaquica Ruiz',
                'email' => 'omargustavoa@gmail.com',
                'tax_id' => 'XAXX010101000',
                'phone' => '+523339557571',
                'password' => Hash::make('admin1234'),
            ]);
        }
    }
}
