<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cache permission (Wajib agar tidak error)
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Buat Roles (sesuai kebutuhan Anda: admin, staff, guest)
        $roleAdmin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $roleStaff = Role::firstOrCreate(['name' => 'staff', 'guard_name' => 'web']);
        $roleGuest = Role::firstOrCreate(['name' => 'guest', 'guard_name' => 'web']);

        // 2. Buat Permissions untuk Room Management
        $permissions = [
            // Room Management
            'view rooms',
            'create rooms',
            'edit rooms',
            'delete rooms',

            // Booking Management
            'view bookings',
            'create bookings',
            'edit bookings',
            'delete bookings',
            'approve bookings',
            'cancel bookings',

            // User Management (admin only)
            'view users',
            'create users',
            'edit users',
            'delete users',

            // Reports
            'view reports',
            'generate reports',

            // Dashboard
            'view dashboard',

            // Profile
            'view profile',
            'edit profile',

            // Facilities & Buildings
            'view facilities',
            'manage facilities',
            'view buildings',
            'manage buildings',

            // Statistics
            'view statistics',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }

        // 3. Assign Permissions ke Roles

        // Admin: Semua permissions
        $roleAdmin->syncPermissions(Permission::all());

        // Staff: Bisa lihat, buat, edit ruangan dan booking
        $staffPermissions = [
            'view dashboard',
            'view rooms',
            'view bookings',
            'create bookings',
            'edit bookings',
            'approve bookings',
            'cancel bookings',
            'view reports',
            'view profile',
            'edit profile',
        ];
        $roleStaff->syncPermissions($staffPermissions);

        // Guest: Hanya bisa lihat dan booking
        $guestPermissions = [
            'view dashboard',
            'view rooms',
            'view bookings',
            'create bookings',
            'cancel bookings', // Hanya booking milik sendiri
            'view profile',
            'edit profile',
        ];
        $roleGuest->syncPermissions($guestPermissions);

        // 4. Buat Users untuk setiap role (HAPUS KOLOM 'role')

        // Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                // HAPUS: 'role' => 'admin',
                'phone' => '081234567890',
                'department' => 'IT',
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole($roleAdmin);

        // Staff User
        $staff = User::firstOrCreate(
            ['email' => 'staff@gmail.com'],
            [
                'name' => 'Staff Member',
                'password' => Hash::make('password'),
                // HAPUS: 'role' => 'staff',
                'phone' => '081234567891',
                'department' => 'Operations',
                'email_verified_at' => now(),
            ]
        );
        $staff->assignRole($roleStaff);

        // Guest User
        $guest = User::firstOrCreate(
            ['email' => 'guest@gmail.com'],
            [
                'name' => 'Guest User',
                'password' => Hash::make('password'),
                // HAPUS: 'role' => 'guest',
                'phone' => '081234567892',
                'department' => 'General',
                'email_verified_at' => now(),
            ]
        );
        $guest->assignRole($roleGuest);

        // 5. Output informasi
        $this->command->info('==========================================');
        $this->command->info('ROLE SEEDER BERHASIL DIEKSEKUSI!');
        $this->command->info('==========================================');
        $this->command->info('Default users created:');
        $this->command->info('Admin: admin@gmail.com / password');
        $this->command->info('Staff: staff@gmail.com / password');
        $this->command->info('Guest: guest@gmail.com / password');
        $this->command->info('==========================================');
    }
}
