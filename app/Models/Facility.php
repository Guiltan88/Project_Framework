<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Facility extends Model
{
    protected $fillable = ['nama_fasilitas', 'keterangan'];

    public function rooms()
    {
        return $this->belongsToMany(Room::class);
    }
}
