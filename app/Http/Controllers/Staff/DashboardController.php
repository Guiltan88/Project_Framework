<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $stats = [
            'pending_bookings' => Booking::where('status', 'pending')->count(),
            'today_bookings' => Booking::whereDate('tanggal_mulai', today())
                ->where('status', 'approved')
                ->count(),
            'available_rooms' => Room::where('status', 'tersedia')->count(),
            'my_approvals' => Booking::where('approved_by', $user->id)
                ->whereDate('approved_at', today())
                ->count(),
        ];

        $pendingBookings = Booking::with(['user', 'room'])
            ->where('status', 'pending')
            ->latest()
            ->limit(5)
            ->get();

        return view('staff.dashboard.index', compact('stats', 'pendingBookings'));
    }
}
