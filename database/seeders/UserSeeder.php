<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ── Original default accounts ──
        $superadmin = User::firstOrCreate(
            ['email' => 'superadmin@tefa.polije.ac.id'],
            [
                'name' => 'Super Admin',
                'phone' => '6281234567890',
                'password' => Hash::make('password'),
            ]
        );
        $superadmin->assignRole('super_admin');

        $teknisi = User::firstOrCreate(
            ['email' => 'teknisi@tefa.polije.ac.id'],
            [
                'name' => 'Teknisi',
                'phone' => '6281234567891',
                'password' => Hash::make('password'),
            ]
        );
        $teknisi->assignRole('teknisi');

        // ── 9 additional super_admin (total 10) ──
        for ($i = 2; $i <= 10; $i++) {
            $user = User::firstOrCreate(
                ['email' => "superadmin_{$i}@tefa.polije.ac.id"],
                [
                    'name' => "Super Admin {$i}",
                    'phone' => '628' . str_pad((string) (2000000000 + $i), 10, '0', STR_PAD_LEFT),
                    'password' => Hash::make('password'),
                ]
            );
            $user->assignRole('super_admin');
        }

        // ── 39 additional teknisi (total 40) ──
        for ($i = 2; $i <= 40; $i++) {
            $user = User::firstOrCreate(
                ['email' => "teknisi_{$i}@tefa.polije.ac.id"],
                [
                    'name' => "Teknisi {$i}",
                    'phone' => '628' . str_pad((string) (3000000000 + $i), 10, '0', STR_PAD_LEFT),
                    'password' => Hash::make('password'),
                ]
            );
            $user->assignRole('teknisi');
        }
    }
}
