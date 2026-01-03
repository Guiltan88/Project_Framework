<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Room;
use App\Models\Booking;
use App\Models\Building;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $selectedYear = $request->get('year', date('Y'));

        $stats = [
            'active_bookings' => Booking::where('status', 'approved')
                ->whereDate('tanggal_mulai', '<=', now())
                ->whereDate('tanggal_selesai', '>=', now())
                ->count(),
            'total_rooms' => Room::count(),
            'total_users' => User::count(),
        ];

        // Data untuk chart (jika masih ingin ada chart, tapi optional)
        $monthlyBookings = [];
        for ($month = 1; $month <= 12; $month++) {
            $count = Booking::whereYear('created_at', $selectedYear)
                ->whereMonth('created_at', $month)
                ->count();
            $monthlyBookings[] = $count;
        }

        $bookingStats = [
            'total' => Booking::whereYear('created_at', $selectedYear)->count(),
            'approved' => Booking::whereYear('created_at', $selectedYear)
                ->where('status', 'approved')->count(),
            'pending' => Booking::whereYear('created_at', $selectedYear)
                ->where('status', 'pending')->count(),
            'rejected' => Booking::whereYear('created_at', $selectedYear)
                ->where('status', 'rejected')->count(),
        ];

        $recentBookings = Booking::with(['room.building', 'user'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard.index', compact(
            'stats',
            'monthlyBookings',
            'bookingStats',
            'recentBookings',
            'selectedYear'
        ));
    }
}
