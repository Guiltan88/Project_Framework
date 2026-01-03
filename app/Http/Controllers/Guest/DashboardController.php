<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $today = Carbon::today();

        // Statistics
        $stats = [
            'total_bookings' => $user->bookings()->count(),
            'approved_bookings' => $user->bookings()->where('status', Booking::STATUS_APPROVED)->count(),
            'pending_bookings' => $user->bookings()->where('status', Booking::STATUS_PENDING)->count(),
            'today_bookings' => $user->bookings()
                ->where('status', Booking::STATUS_APPROVED)
                ->whereDate('tanggal_mulai', $today)
                ->count(),
            'available_rooms' => Room::where('status', 'tersedia')->count(),
        ];

        // Upcoming Bookings (approved & future dates)
        $upcomingBookings = $user->bookings()
            ->where('status', Booking::STATUS_APPROVED)
            ->where('tanggal_mulai', '>=', $today)
            ->with(['room', 'room.building'])
            ->orderBy('tanggal_mulai')
            ->orderBy('waktu_mulai')
            ->limit(5)
            ->get();

        // Recent Bookings (all status, latest)
        $recentBookings = $user->bookings()
            ->with(['room', 'room.building'])
            ->latest()
            ->limit(5)
            ->get();

        // Available Rooms for quick booking
        $availableRooms = Room::where('status', 'tersedia')
            ->with('building')
            ->latest() // Ambil yang terbaru
            ->take(4)   // Hanya 4 data
            ->get();

        return view('guest.dashboard.index', compact(
            'stats',
            'recentBookings',
            'availableRooms'
            ));
    }


}
