<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Room;
use App\Models\Building;
use App\Models\Facility;

class RuanganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua gedung
        $buildings = Building::all();

        // Ambil beberapa fasilitas
        $facilities = Facility::inRandomOrder()->limit(8)->get();

        $roomTypes = [
            'Ruang Kelas', 'Laboratorium', 'Ruang Rapat', 'Auditorium',
            'Perpustakaan', 'Kantin', 'Ruang Dosen', 'Ruang Administrasi',
            'Studio', 'Ruang Serbaguna', 'Ruang Olahraga', 'Asrama'
        ];

        $statuses = ['tersedia', 'tidak tersedia', 'dalam perbaikan'];

        // Counter untuk kode ruangan (R-101, R-102, dst)
        $roomCounter = 101;

        foreach ($buildings as $building) {
            // Buat beberapa ruangan untuk setiap gedung
            $roomsPerBuilding = rand(3, 8);

            for ($i = 1; $i <= $roomsPerBuilding; $i++) {
                $lantai = rand(1, $building->jumlah_lantai);

                $room = Room::create([
                    'kode_ruangan' => 'R-' . $roomCounter, // Format: R-101, R-102, dst
                    'nama_ruangan' => $roomTypes[array_rand($roomTypes)] . ' ' . $building->nama_gedung . ' ' . $lantai . '0' . $i,
                    'gedung_id'    => $building->id,
                    'lantai'       => $lantai,
                    'kapasitas'    => rand(20, 100),
                    'status'       => $statuses[array_rand($statuses)],
                    'gambar'       => null,
                ]);

                // Attach random facilities
                $randomFacilities = $facilities->random(rand(2, 5))->pluck('id')->toArray();
                $room->facilities()->sync($randomFacilities);

                // Increment counter untuk kode ruangan berikutnya
                $roomCounter++;
            }
        }
    }
}
