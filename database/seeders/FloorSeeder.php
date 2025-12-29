<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Building;
use App\Models\Floor;

class FloorSeeder extends Seeder
{
    public function run(): void
    {
        $gedungA = Building::where('nama_gedung', 'Gedung A')->first();
        $gedungB = Building::where('nama_gedung', 'Gedung B')->first();

        foreach (['Lantai 1', 'Lantai 2', 'Lantai 3'] as $lantai) {
            Floor::create([
                'building_id' => $gedungA->id,
                'name'        => $lantai,
            ]);
        }

        foreach (['Lantai 1', 'Lantai 2'] as $lantai) {
            Floor::create([
                'building_id' => $gedungB->id,
                'name'        => $lantai,
            ]);
        }
    }
}

