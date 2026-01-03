<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Ensure roles exist
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $staffRole = Role::firstOrCreate(['name' => 'staff', 'guard_name' => 'web']);
        $guestRole = Role::firstOrCreate(['name' => 'guest', 'guard_name' => 'web']);

        // Admin
        $admin = User::updateOrCreate([
            'email' => 'admin@kampus.ac.id'
        ], [
            'name' => 'Admin Utama',
            'password' => Hash::make('admin123'),
            'phone' => '081111111111',
            'department' => 'Administration',
            // HAPUS: 'role' => 'admin'
        ]);
        $admin->assignRole($adminRole);

        // Staff (5)
        for ($i = 1; $i <= 5; $i++) {
            $staff = User::updateOrCreate([
                'email' => "staff$i@kampus.ac.id"
            ], [
                'name' => "Staff $i",
                'password' => Hash::make('password'),
                'phone' => '0812' . rand(10000000, 99999999),
                'department' => ['IT', 'Operations', 'Academic', 'Finance', 'HR'][$i-1],
                // HAPUS: 'role' => 'staff'
            ]);
            $staff->assignRole($staffRole);
        }

        // Guest (24)
        for ($i = 1; $i <= 24; $i++) {
            $guest = User::updateOrCreate([
                'email' => "guest$i@kampus.ac.id"
            ], [
                'name' => "Mahasiswa $i",
                'password' => Hash::make('password'),
                'phone' => '0813' . rand(10000000, 99999999),
                'department' => 'Student',
                // HAPUS: 'role' => 'guest'
            ]);
            $guest->assignRole($guestRole);
        }
    }
}
