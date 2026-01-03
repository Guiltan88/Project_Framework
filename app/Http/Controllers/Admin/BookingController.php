<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'room', 'room.building']);

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kode_booking', 'LIKE', "%{$search}%")
                  ->orWhere('tujuan', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('email', 'LIKE', "%{$search}%");
                  })
                  ->orWhereHas('room', function($q) use ($search) {
                      $q->where('nama_ruangan', 'LIKE', "%{$search}%")
                        ->orWhereHas('building', function($q) use ($search) {
                            $q->where('nama_gedung', 'LIKE', "%{$search}%");
                        });
                  });
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        // Filter by date
        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('tanggal_mulai', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('tanggal_mulai', '<=', $request->date_to);
        }

        $bookings = $query->latest()->paginate(10);

        // Statistics
        $stats = [
            'total' => Booking::count(),
            'pending' => Booking::where('status', 'pending')->count(),
            'approved' => Booking::where('status', 'approved')->count(),
            'rejected' => Booking::where('status', 'rejected')->count(),
            'cancelled' => Booking::where('status', 'cancelled')->count(),
            'today' => Booking::whereDate('created_at', Carbon::today())->count()
        ];

        return view('admin.bookings.index', compact('bookings', 'stats'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['user', 'room', 'room.building', 'room.facilities']);
        return view('admin.bookings.show', compact('booking'));
    }

    public function approve(Booking $booking)
    {
        $booking->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => auth()->id()
        ]);

        return back()->with('success', 'Booking approved successfully.');
    }

    public function reject(Request $request, Booking $booking)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string|max:500'
        ]);

        $booking->update([
            'status' => 'rejected',
            'alasan_penolakan' => $request->alasan_penolakan,
            'rejected_at' => now(),
            'rejected_by' => auth()->id()
        ]);

        return back()->with('success', 'Booking rejected successfully.');
    }

    public function cancel(Booking $booking)
    {
        $booking->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancelled_by' => auth()->id()
        ]);

        return back()->with('success', 'Booking cancelled successfully.');
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();
        return back()->with('success', 'Booking deleted successfully.');
    }
}
