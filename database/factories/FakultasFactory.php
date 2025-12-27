<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FakultasFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nama_fakultas' => fake()->unique()->company(),
            'kode_fakultas' => strtoupper(fake()->lexify('FK???')),
        ];
    }
}
