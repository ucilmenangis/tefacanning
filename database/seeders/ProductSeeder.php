<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'Sarden SIP Saus Tomat',
                'sku' => 'TEFA-SST-001',
                'description' => 'Ikan lemuru dengan saus tomat rempah alami Indonesia. Gurih dan asam yang seimbang, tanpa pengawet. Berat bersih 425gr.',
                'price' => 25000,
                'stock' => 500,
                'unit' => 'kaleng',
                'is_active' => true,
            ],
            [
                'name' => 'Sarden SIP Asin',
                'sku' => 'TEFA-ASN-001',
                'description' => 'Varian paling fleksibel — ikan lemuru dan larutan garam. Cocok sebagai bahan dasar masakan atau langsung dinikmati. Berat bersih 425gr.',
                'price' => 22000,
                'stock' => 500,
                'unit' => 'kaleng',
                'is_active' => true,
            ],
            [
                'name' => 'Sarden SIP Saus Cabai',
                'sku' => 'TEFA-SSC-001',
                'description' => 'Ikan lemuru dengan saus cabai khas Nusantara. Pedasnya pas, tidak mengalahkan rasa alami ikan. Berat bersih 425gr.',
                'price' => 25000,
                'stock' => 500,
                'unit' => 'kaleng',
                'is_active' => true,
            ],
        ];

        foreach ($products as $product) {
            Product::firstOrCreate(
                ['sku' => $product['sku']],
                $product
            );
        }
    }
}
