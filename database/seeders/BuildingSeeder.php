<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Building;

class BuildingSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 8; $i++) {
            Building::create([
                'kode_gedung'   => 'G-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'nama_gedung'   => 'Gedung ' . chr(64 + $i),
                'jumlah_lantai' => rand(2, 8),
                'keterangan'    => 'Deskripsi untuk Gedung ' . chr(64 + $i) . '. ' .
                                  'Gedung ini memiliki fasilitas lengkap untuk mendukung kegiatan akademik.',
                'gambar'        => null,
            ]);
        }
    }
}

