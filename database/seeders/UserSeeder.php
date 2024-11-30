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
        if (!User::where('user_name', 'jmelecio')->exists()) {
            User::create([
                'user_name' => 'jmelecio',
                'name' => 'Jose Refugio',
                'last_name' => 'Melecio Soto',
                'email' => 'jrr.melecio@gmail.com',
                'tax_id' => 'XAXX010101000',
                'phone' => '+523334531396',
                'password' => Hash::make('admin1234'),
            ]);
        }

        if (!User::where('user_name', 'oayaquica')->exists()) {
            User::create([
                'user_name' => 'oayaquica',
                'name' => 'Omar Gustavo',
                'last_name' => 'Ayaquica Ruiz',
                'email' => 'omargustavoa@gmail.com',
                'tax_id' => 'XAXX010101000',
                'phone' => '+523339557571',
                'password' => Hash::make('admin1234'),
            ]);
        }

        if (!User::where('user_name', 'admin')->exists()) {
            User::create([
                'user_name' => 'admin',
                'name' => 'Usuario',
                'last_name' => 'Usuario',
                'email' => 'sack9511@gmail.com',
                'tax_id' => 'BMA140428KU4',
                'phone' => '+523333333333',
                'password' => Hash::make('PLD23'),
            ]);
        }
    }
}
