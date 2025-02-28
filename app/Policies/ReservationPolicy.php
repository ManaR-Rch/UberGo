<?php

namespace App\Policies;

use App\Models\Reservation;
use App\Models\User;

class ReservationPolicy
{
    public function view(User $user, Reservation $reservation)
    {
        return $user->id === $reservation->passenger_id || 
               $user->id === $reservation->trip->driver_id;
    }

    public function cancel(User $user, Reservation $reservation)
    {
        return $user->id === $reservation->passenger_id && 
               in_array($reservation->status, ['pending', 'accepted']);
    }

    public function manage(User $user, Reservation $reservation)
    {
        return $user->isDriver() && 
               $user->id === $reservation->trip->driver_id && 
               $reservation->status === 'pending';
    }
}