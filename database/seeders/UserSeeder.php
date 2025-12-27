<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;


class UserSeeder extends Seeder
{
    public function run()
    {
        // Admin
        User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@kampus.ac.id',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        // Staff (5)
        for ($i = 1; $i <= 5; $i++) {
            User::create([
                'name' => "Staff $i",
                'email' => "staff$i@kampus.ac.id",
                'password' => Hash::make('password'),
                'role' => 'staff'
            ]);
        }

        // Guest (24)
        for ($i = 1; $i <= 24; $i++) {
            User::create([
                'name' => "Mahasiswa $i",
                'email' => "guest$i@kampus.ac.id",
                'password' => Hash::make('password'),
                'role' => 'guest'
            ]);
        }
    }
}

