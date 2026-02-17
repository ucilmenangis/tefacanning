<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        // Create 50 customers with realistic Indonesian data
        for ($i = 1; $i <= 50; $i++) {
            Customer::firstOrCreate(
                ['email' => "customer_{$i}@customer.com"],
                [
                    'name' => "Customer {$i}",
                    'phone' => '628' . str_pad((string) (1234567890 + $i), 10, '0', STR_PAD_LEFT),
                    'password' => Hash::make('password'),
                    'address' => "Jl. Contoh No. {$i}, Jember, Jawa Timur",
                    'organization' => $this->getOrganization($i),
                ]
            );
        }
    }

    private function getOrganization(int $index): string
    {
        $organizations = [
            'PT. Sumber Rejeki',
            'CV. Maju Jaya',
            'UD. Berkah Sentosa',
            'Koperasi Nelayan Sejahtera',
            'PT. Samudra Perkasa',
            'CV. Laut Biru',
            'UD. Ikan Mas',
            'PT. Nusantara Seafood',
            'Koperasi Mina Bahari',
            'CV. Hasil Laut',
            'PT. Perikanan Mandiri',
            'UD. Segar Makmur',
            'Koperasi Tani Nelayan',
            'CV. Bumi Lestari',
            'PT. Indo Marine',
            'UD. Pangan Laut',
            'CV. Bahari Jaya',
            'PT. Citra Samudera',
            'Koperasi Pesisir Mandiri',
            'UD. Laut Sejahtera',
        ];

        return $organizations[($index - 1) % count($organizations)];
    }
}
