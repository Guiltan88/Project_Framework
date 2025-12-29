<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Building;

class BuildingSeeder extends Seeder
{
    public function run(): void
    {
        Building::insert([
            [
                'kode_gedung'   => 'GD-A',
                'nama_gedung'   => 'Gedung A',
                'jumlah_lantai' => 4,
            ],
            [
                'kode_gedung'   => 'GD-B',
                'nama_gedung'   => 'Gedung B',
                'jumlah_lantai' => 3,
            ],
            [
                'kode_gedung'   => 'GD-C',
                'nama_gedung'   => 'Gedung C',
                'jumlah_lantai' => 5,
            ],
        ]);
    }
}
