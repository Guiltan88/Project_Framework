<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Building;
use App\Models\Booking;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $query = Room::with(['building', 'facilities']);

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kode_ruangan', 'LIKE', "%{$search}%")
                  ->orWhere('nama_ruangan', 'LIKE', "%{$search}%")
                  ->orWhereHas('building', function($q) use ($search) {
                      $q->where('nama_gedung', 'LIKE', "%{$search}%");
                  })
                  ->orWhereHas('facilities', function($q) use ($search) {
                      $q->where('nama_fasilitas', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter by building
        if ($request->has('building_id') && $request->building_id != '') {
            $query->where('gedung_id', $request->building_id);
        }

        // Filter by room type
        if ($request->has('tipe_ruangan') && $request->tipe_ruangan != '') {
            $query->where('tipe_ruangan', $request->tipe_ruangan);
        }

        $rooms = $query->latest()->paginate(12);
        $buildings = Building::all();
        $roomTypes = Room::select('tipe_ruangan')->distinct()->whereNotNull('tipe_ruangan')->pluck('tipe_ruangan');

        return view('staff.rooms.index', compact('rooms', 'buildings', 'roomTypes'));
    }

    public function show(Room $room)
    {
        $room->load(['building', 'facilities']);

        $bookedToday = Booking::where('room_id', $room->id)
            ->whereDate('tanggal_mulai', today())
            ->whereIn('status', ['pending', 'approved'])
            ->exists();

        return view('staff.rooms.show', compact('room', 'bookedToday'));
    }
}
