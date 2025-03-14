<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'registration_number',
        'model',
        'capacity',
        'color'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}