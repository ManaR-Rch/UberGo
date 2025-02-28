<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = [
        'driver_id',
        'departure_city',
        'arrival_city',
        'departure_time',
        'available_seats',
        'price',
        'is_active'
    ];

    protected $casts = [
        'departure_time' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}