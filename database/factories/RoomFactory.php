<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RoomFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nama_ruangan' => 'Ruang ' . fake()->bothify('??-###'),
            'lokasi' => fake()->randomElement(['Gedung A', 'Gedung B', 'Gedung C']),
            'kapasitas' => fake()->numberBetween(20, 200),
            'status' => fake()->randomElement(['available', 'unavailable']),
        ];
    }
}
