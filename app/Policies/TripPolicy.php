<?php

namespace App\Policies;

use App\Models\Trip;
use App\Models\User;

class TripPolicy
{
    public function view(User $user, Trip $trip)
    {
        return $user->id === $trip->driver_id;
    }

    public function update(User $user, Trip $trip)
    {
        return $user->id === $trip->driver_id;
    }

    public function delete(User $user, Trip $trip)
    {
        return $user->id === $trip->driver_id;
    }
}