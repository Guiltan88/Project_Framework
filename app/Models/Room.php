<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'kode_ruangan',
        'nama_ruangan',
        'gedung_id',
        'lantai', // Tetap ada, tapi default value
        'kapasitas',
        'status',
        'gambar',
    ];

    protected $attributes = [
        'lantai' => 1, // Default value
        'status' => 'tersedia', // Default status juga
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

