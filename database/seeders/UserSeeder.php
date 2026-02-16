<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create Superadmin
        $superadmin = User::firstOrCreate(
            ['email' => 'superadmin@tefa.polije.ac.id'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
            ]
        );
        $superadmin->assignRole('super_admin');

        // Create Teknisi
        $teknisi = User::firstOrCreate(
            ['email' => 'teknisi@tefa.polije.ac.id'],
            [
                'name' => 'Teknisi',
                'password' => Hash::make('password'),
            ]
        );
        $teknisi->assignRole('teknisi');
    }
}
