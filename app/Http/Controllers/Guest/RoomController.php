<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Building;
use App\Models\Booking;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $query = Room::query()->with(['building', 'facilities']);

        // Filter by building
        if ($request->filled('building')) {
            $query->where('gedung_id', $request->building);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            // Default: show available rooms first
            $query->orderByRaw("FIELD(status, 'tersedia', 'terisi', 'maintenance')");
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_ruangan', 'like', "%{$search}%")
                  ->orWhere('kode_ruangan', 'like', "%{$search}%")
                  ->orWhereHas('building', function($q2) use ($search) {
                      $q2->where('nama_gedung', 'like', "%{$search}%");
                  });
            });
        }

        // Sorting
        $sort = $request->get('sort', 'available');
        switch($sort) {
            case 'name':
                $query->orderBy('nama_ruangan');
                break;
            case 'capacity':
                $query->orderBy('kapasitas', 'desc');
                break;
            case 'latest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'available':
            default:
                $query->orderByRaw("FIELD(status, 'tersedia', 'terisi', 'maintenance')")
                      ->orderBy('nama_ruangan');
                break;
        }

        $perPage = $request->get('per_page', 12);
        $rooms = $query->paginate($perPage);

        // Get all buildings for filter dropdown
        $buildings = Building::all();

        // Statistics
        $totalRooms = Room::count();
        $availableRooms = Room::where('status', 'tersedia')->count();
        $occupiedRooms = Room::where('status', 'terisi')->count();
        $maintenanceRooms = Room::where('status', 'maintenance')->count();

        return view('guest.rooms.index', compact(
            'rooms',
            'buildings',
            'totalRooms',
            'availableRooms',
            'occupiedRooms',
            'maintenanceRooms'
        ));
    }


    public function show(Room $room)
    {
        $room->load(['building', 'facilities']);

        // Check if room is booked today
        $bookedToday = Booking::where('room_id', $room->id)
            ->whereDate('tanggal_mulai', Carbon::today())
            ->whereIn('status', ['approved', 'pending'])
            ->exists();

        // Get booking statistics
        $todayBookings = Booking::where('room_id', $room->id)
            ->whereDate('tanggal_mulai', Carbon::today())
            ->where('status', 'approved')
            ->count();

        $weekBookings = Booking::where('room_id', $room->id)
            ->whereBetween('tanggal_mulai', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ])
            ->where('status', 'approved')
            ->count();

        $monthBookings = Booking::where('room_id', $room->id)
            ->whereMonth('tanggal_mulai', Carbon::now()->month)
            ->where('status', 'approved')
            ->count();

        $totalBookings = Booking::where('room_id', $room->id)
            ->where('status', 'approved')
            ->count();

        // Get other rooms in same building
        $otherRooms = Room::where('gedung_id', $room->gedung_id)
            ->where('id', '!=', $room->id)
            ->where('status', 'tersedia')
            ->with('building')
            ->limit(3)
            ->get();

        return view('guest.rooms.show', compact(
            'room',
            'bookedToday',
            'todayBookings',
            'weekBookings',
            'monthBookings',
            'totalBookings',
            'otherRooms'
        ));
    }
}
