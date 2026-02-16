<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Order $order): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Order $order): bool
    {
        return true;
    }

    public function delete(User $user, Order $order): bool
    {
        return $user->hasRole('super_admin');
    }

    public function restore(User $user, Order $order): bool
    {
        return $user->hasRole('super_admin');
    }

    public function forceDelete(User $user, Order $order): bool
    {
        return $user->hasRole('super_admin');
    }
}
