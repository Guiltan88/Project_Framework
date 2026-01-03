<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class BookingSeeder extends Seeder
{
    public function run()
    {
        $rooms = Room::all();
        $users = User::byRole('guest')->get();
        $staff = User::byRole('staff')->first();

        $statuses = ['pending', 'approved', 'rejected', 'cancelled'];
        $purposes = [
            'Meeting Tim Marketing',
            'Presentasi Client',
            'Workshop Internal',
            'Rapat Divisi IT',
            'Training Karyawan Baru',
            'Interview Kandidat',
            'Diskusi Proyek',
            'Brainstorming Ide',
            'Sosialisasi Kebijakan',
            'Koordinasi Tim'
        ];

        $bookings = [];

        // Create bookings for the next 30 days
        for ($i = 0; $i < 50; $i++) {
            $room = $rooms->random();
            $user = $users->random();
            $status = $statuses[array_rand($statuses)];
            $purpose = $purposes[array_rand($purposes)];

            $startDate = Carbon::today()->addDays(rand(-10, 30));
            $endDate = (clone $startDate)->addDays(rand(0, 2));

            // Generate time between 08:00 and 17:00
            $startHour = rand(8, 16);
            $endHour = min($startHour + rand(1, 3), 17);

            $startTime = sprintf('%02d:00', $startHour);
            $endTime = sprintf('%02d:00', $endHour);

            $bookingData = [
                'kode_booking' => 'BK-' . date('Ymd') . '-' . strtoupper(Str::random(4)) . $i,
                'user_id' => $user->id,
                'room_id' => $room->id,
                'tujuan' => $purpose,
                'tanggal_mulai' => $startDate,
                'tanggal_selesai' => $endDate,
                'waktu_mulai' => $startTime,
                'waktu_selesai' => $endTime,
                'jumlah_peserta' => rand(1, $room->kapasitas),
                'kebutuhan_khusus' => rand(0, 1) ? 'Butuh proyektor dan whiteboard' : null,
                'catatan' => rand(0, 1) ? 'Mohon disediakan air mineral dan snack' : null,
                'status' => $status,
                'alasan_penolakan' => null,
                'approved_by' => null,
                'approved_at' => null,
                'cancelled_by' => null,
                'cancelled_at' => null,
                'created_at' => Carbon::now()->subDays(rand(0, 60)),
                'updated_at' => Carbon::now()->subDays(rand(0, 60)),
            ];

            // Jika approved atau rejected, tambah info approval
            if (in_array($status, ['approved', 'rejected'])) {
                $bookingData['approved_by'] = $staff->id;
                $bookingData['approved_at'] = (clone $bookingData['created_at'])->addHours(rand(1, 24));

                if ($status == 'rejected') {
                    $bookingData['alasan_penolakan'] = 'Ruangan sudah dipesan untuk meeting penting';
                }
            }

            // Jika cancelled, tambah info cancel
            if ($status == 'cancelled') {
                $bookingData['cancelled_by'] = rand(0, 1) ? $user->id : $staff->id;
                $bookingData['cancelled_at'] = (clone $bookingData['created_at'])->addHours(rand(1, 12));
            }

            $bookings[] = $bookingData;
        }

        Booking::insert($bookings);

        $this->command->info('✅ 50 bookings created successfully!');
    }
}
