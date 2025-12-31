<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Facility;

class FacilitySeeder extends Seeder
{

    public function run(): void
    {
        $data = [
            'Proyektor',
            'AC',
            'WiFi',
            'Papan Tulis',
            'Sound System',
            'TV',
            'Stop Kontak'
        ];

        foreach ($data as $item) {
            Facility::create([
                'nama_fasilitas' => $item,
                'keterangan' => 'Fasilitas ' . $item
            ]);
        }
    }
};
