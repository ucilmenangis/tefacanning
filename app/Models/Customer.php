<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Customer extends Authenticatable implements FilamentUser
{
    use HasFactory, SoftDeletes, LogsActivity, Notifiable;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'password',
        'address',
        'organization',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        return $panel->getId() === 'customer';
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnly(['name', 'phone', 'email']);
    }
}
