<?php

namespace App\Policies;

use App\Models\Vehicle;
use App\Models\User;

class VehiclePolicy
{
    public function update(User $user, Vehicle $vehicle)
    {
        return $user->id === $vehicle->user_id;
    }
}