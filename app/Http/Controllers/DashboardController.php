<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Room;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ===== CARD =====
        $totalUsers = User::count();
        $totalRooms = Room::count();
        $activeBookings = Booking::where('status', 'approved')->count();

        // ===== CHART (PER BULAN) =====
        $bookingPerMonth = Booking::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // ===== RECENT ACTIVITY =====
        $recentBookings = Booking::with(['user', 'ruangan'])
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.Dashboard.dashboard', compact(
            'totalUsers',
            'totalRooms',
            'activeBookings',
            'bookingPerMonth',
            'recentBookings'
        ));
    }
}
