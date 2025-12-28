<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Facility;

class FacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */


    public function run(): void
    {
        $data = [
            ['nama_fasilitas' => 'AC'],
            ['nama_fasilitas' => 'Proyektor'],
            ['nama_fasilitas' => 'Whiteboard'],
            ['nama_fasilitas' => 'Sound System'],
            ['nama_fasilitas' => 'WiFi'],
            ['nama_fasilitas' => 'Kursi'],
            ['nama_fasilitas' => 'Meja'],
        ];

        Facility::insert($data);
    }
};
