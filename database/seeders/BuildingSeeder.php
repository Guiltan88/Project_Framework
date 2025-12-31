<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Building;

class BuildingSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 5; $i++) {
            Building::create([
                'kode_gedung'   => 'G-0' . $i,
                'nama_gedung'   => 'Gedung ' . chr(64 + $i),
                'jumlah_lantai' => rand(2, 5),
                'keterangan'    => 'Gedung otomatis'
            ]);
        }
    }
}
