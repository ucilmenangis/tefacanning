<?php

namespace App\Providers;

use App\Models\Batch;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Policies\BatchPolicy;
use App\Policies\CustomerPolicy;
use App\Policies\OrderPolicy;
use App\Policies\ProductPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Product::class => ProductPolicy::class,
        Order::class => OrderPolicy::class,
        Batch::class => BatchPolicy::class,
        Customer::class => CustomerPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
