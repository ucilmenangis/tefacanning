<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition(): array
    {
        return [
            'name' => fake('id_ID')->name(),
            'phone' => '628' . fake()->numerify('##########'),
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'address' => fake('id_ID')->address(),
            'organization' => fake()->randomElement([
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
            ]),
        ];
    }
}
