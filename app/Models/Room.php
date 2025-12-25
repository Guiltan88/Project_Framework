<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_code',
        'room_name',
        'capacity',
        'location',
        'status',
    ];

    // One room has many bookings
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
