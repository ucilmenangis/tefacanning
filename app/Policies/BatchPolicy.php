<?php

namespace App\Policies;

use App\Models\Batch;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BatchPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Batch $batch): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('super_admin');
    }

    public function update(User $user, Batch $batch): bool
    {
        return true;
    }

    public function delete(User $user, Batch $batch): bool
    {
        return $user->hasRole('super_admin');
    }

    public function restore(User $user, Batch $batch): bool
    {
        return $user->hasRole('super_admin');
    }

    public function forceDelete(User $user, Batch $batch): bool
    {
        return $user->hasRole('super_admin');
    }
}
