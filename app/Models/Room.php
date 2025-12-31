<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'kode_ruangan',
        'nama_ruangan',
        'gedung_id',
        'kapasitas',
        'status',
        'gambar',
    ];

    public function building()
    {
        return $this->belongsTo(Building::class, 'gedung_id');
    }

    public function facilities()
    {
        return $this->belongsToMany(Facility::class);
    }
}

