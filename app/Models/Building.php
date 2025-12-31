<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    protected $fillable = [
        'kode_gedung',
        'nama_gedung',
        'jumlah_lantai',
        'keterangan'
    ];

    public function rooms()
    {
        return $this->hasMany(Room::class, 'gedung_id');
    }
}

