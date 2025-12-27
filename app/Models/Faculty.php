<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    use HasFactory;

    protected $table = 'fakultas';

    protected $fillable = [
        'nama_fakultas',
        'kode_fakultas',
    ];

    /**
     * RELATION (kalau nanti ada)
     * contoh:
     * fakultas -> ruangan
     */
    // public function ruangans()
    // {
    //     return $this->hasMany(Ruangan::class);
    // }
}
