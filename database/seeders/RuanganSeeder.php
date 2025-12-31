<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Room;
use App\Models\Building;
use App\Models\Facility;
class RuanganSeeder extends Seeder
{
    public function run(): void
    {
        $buildings = Building::pluck('id');
        $facilities = Facility::pluck('id');

        for ($i = 1; $i <= 100; $i++) {

            $room = Room::create([
                'kode_ruangan' => 'R-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'nama_ruangan' => 'Ruang Kelas ' . $i,
                'gedung_id'    => $buildings->random(),
                'kapasitas'    => rand(20, 60),
                'status'       => rand(0, 1) ? 'tersedia' : 'tidak tersedia',
            ]);

            // attach 1–3 fasilitas random
            $room->facilities()->attach(
                $facilities->random(rand(1, 3))->toArray()
            );
        }
    }
}


