<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RoomLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'action',
        'description',
        'action_time',
    ];

    protected $dates = ['action_time'];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
