<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Room;
use App\Models\Floor;

class RuanganSeeder extends Seeder
{
    public function run(): void
    {
        $floor = Floor::first(); // ambil lantai pertama

        Room::create([
            'floor_id'     => $floor->id,
            'kode_ruangan' => 'R-101',
            'nama_ruangan' => 'Ruang Kelas 1',
            'kapasitas'    => 40,
            'status'       => 'tersedia'
        ]);
    }
}


