<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    protected $fillable = [
        'kode_gedung',
        'nama_gedung'
    ];

    public function floors()
    {
        return $this->hasMany(Floor::class);
    }
}
