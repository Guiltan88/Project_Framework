<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    protected $fillable = [
        'kode_gedung',
        'nama_gedung',
        'jumlah_lantai',
        'keterangan',
        'gambar',
    ];

    public function rooms()
    {
        return $this->hasMany(Room::class, 'gedung_id');
    }

    // 🔥 METHOD BARU: Ambil array lantai berdasarkan jumlah_lantai
    public function getFloorsAttribute()
    {
        $floors = [];
        for ($i = 1; $i <= $this->jumlah_lantai; $i++) {
            $floors[$i] = "Lantai $i";
        }
        return $floors;
    }
}
