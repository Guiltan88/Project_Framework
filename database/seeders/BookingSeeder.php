<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Room;
use App\Models\User;
use App\Models\Booking;

class BookingSeeder extends Seeder
{
    public function run()
    {
        $guests = User::where('role', 'guest')->pluck('id');
        $rooms = Room::pluck('id');
        $staff = User::whereIn('role', ['admin', 'staff'])->pluck('id');

        for ($i = 1; $i <= 50; $i++) {
            Booking::create([
                'user_id' => $guests->random(),
                'room_id' => $rooms->random(),
                'tanggal' => now()->addDays(rand(0, 30)),
                'jam_mulai' => '08:00',
                'jam_selesai' => '10:00',
                'keperluan' => 'Kegiatan Akademik',
                'status' => ['pending', 'approved', 'rejected'][rand(0,2)],
                'approved_by' => $staff->random()
            ]);
        }
    }
}
