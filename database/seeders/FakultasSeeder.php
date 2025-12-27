<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Faculty;

class FakultasSeeder extends Seeder
{
    public function run(): void
    {
        Faculty::factory()->count(100)->create();
    }
}

