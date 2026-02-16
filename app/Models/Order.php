<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Order extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    public const PICKUP_CODE_LENGTH = 8;
    public const ORDER_NUMBER_PREFIX = 'ORD-';

    protected $fillable = [
        'customer_id',
        'batch_id',
        'order_number',
        'pickup_code',
        'status',
        'total_amount',
        'profit',
        'notes',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'profit' => 'decimal:2',
        'picked_up_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_product')
            ->withPivot(['quantity', 'unit_price', 'subtotal'])
            ->withTimestamps();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnly(['status', 'total_amount', 'profit']);
    }

    public static function generateOrderNumber(): string
    {
        return self::ORDER_NUMBER_PREFIX . strtoupper(Str::random(8));
    }

    public static function generatePickupCode(): string
    {
        return strtoupper(Str::random(self::PICKUP_CODE_LENGTH));
    }

    public function getStatusLabel(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu',
            'processing' => 'Diproses',
            'ready' => 'Siap Ambil',
            'picked_up' => 'Sudah Diambil',
            default => $this->status,
        };
    }

    public static function getStatusColor(string $status): string
    {
        return match ($status) {
            'pending' => 'warning',
            'processing' => 'info',
            'ready' => 'success',
            'picked_up' => 'gray',
            default => 'secondary',
        };
    }

    public static function getStatusIcon(string $status): string
    {
        return match ($status) {
            'pending' => 'heroicon-o-clock',
            'processing' => 'heroicon-o-cog',
            'ready' => 'heroicon-o-check-circle',
            'picked_up' => 'heroicon-o-truck',
            default => 'heroicon-o-question-mark-circle',
        };
    }
}
