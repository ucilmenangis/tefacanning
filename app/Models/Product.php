<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Product extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'name',
        'sku',
        'description',
        'price',
        'stock',
        'unit',
        'image',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    /**
     * Core product SKUs that cannot be deleted.
     */
    public const PROTECTED_SKUS = [
        'TEFA-SST-001',
        'TEFA-ASN-001',
        'TEFA-SSC-001',
    ];

    protected static function booted(): void
    {
        static::deleting(function (Product $product) {
            if (in_array($product->sku, self::PROTECTED_SKUS)) {
                throw new \Exception('Produk inti TEFA tidak dapat dihapus.');
            }
        });
    }

    public function isProtected(): bool
    {
        return in_array($this->sku, self::PROTECTED_SKUS);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_product')
            ->withPivot(['quantity', 'unit_price', 'subtotal'])
            ->withTimestamps();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnly(['name', 'sku', 'price', 'stock']);
    }
}
