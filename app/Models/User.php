<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name', 
        'email', 
        'password', 
        'phone_number', 
        'profile_picture', 
        'role_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function vehicle()
    {
        return $this->hasOne(Vehicle::class);
    }

    public function tripsAsDriver()
    {
        return $this->hasMany(Trip::class, 'driver_id');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'passenger_id');
    }

    public function isDriver()
    {
        return $this->role->name === 'driver';
    }

    public function isPassenger()
    {
        return $this->role->name === 'passenger';
    }
}