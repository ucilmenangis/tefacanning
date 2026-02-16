<?php

namespace App\Filament\Customer\Widgets;

use App\Models\Product;
use Filament\Widgets\Widget;

class AvailableProductsWidget extends Widget
{
    protected static string $view = 'filament.customer.widgets.available-products-widget';

    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 1;

    public function getProducts(): \Illuminate\Database\Eloquent\Collection
    {
        return Product::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'sku', 'price', 'stock', 'unit']);
    }
}
