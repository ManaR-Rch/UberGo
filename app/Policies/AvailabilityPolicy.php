<?php

namespace App\Policies;

use App\Models\Availability;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AvailabilityPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->isDriver();
    }

    public function view(User $user, Availability $availability)
    {
        return $user->isDriver() && $user->driver->id === $availability->driver_id;
    }

    public function create(User $user)
    {
        return $user->isDriver();
    }

    public function update(User $user, Availability $availability)
    {
        return $user->isDriver() && $user->driver->id === $availability->driver_id;
    }

    public function delete(User $user, Availability $availability)
    {
        return $user->isDriver() && $user->driver->id === $availability->driver_id;
    }
}