<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Order extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

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
}
