<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User; 
use Illuminate\Support\Facades\Hash; 

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate([
            'name' => 'Faseeh',
            'email' => 'faseehraza1279@gmail.com',
            'password' => Hash::make('ntsh1234'),
        ]);

        User::updateOrCreate([
            'name' => 'Eripmav',
            'email' => 'eripmav12345@gmail.com',
            'password' => Hash::make('ntsh1234'),
        ]);

        User::updateOrCreate([
            'name' => 'Administrator', 
            'email' => 'admin2@admin2.com', 
            'password' => 'admin123'
        ]);
    }
}
