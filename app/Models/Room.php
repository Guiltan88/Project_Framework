<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_ruangan',
        'nama_ruangan',
        'lokasi',
        'kapasitas',
        'fasilitas',
        'status',
        'gambar',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}

