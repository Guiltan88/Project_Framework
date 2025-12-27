<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Room;

class RuanganSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 20; $i++) {
            Room::create([
                'kode_ruangan' => "R-$i",
                'nama_ruangan' => "Ruang Kelas $i",
                'lokasi' => "Gedung A - Lantai " . rand(1,5),
                'kapasitas' => rand(20, 100),
                'fasilitas' => 'Proyektor, AC, Whiteboard'
            ]);
        }
    }
}


