<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'passenger_id',
        'trip_id',
        'seats_reserved',
        'status'
    ];

    public function passenger()
    {
        return $this->belongsTo(User::class, 'passenger_id');
    }

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }
}