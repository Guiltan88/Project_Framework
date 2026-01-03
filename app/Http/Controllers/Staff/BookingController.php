<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'room', 'approver'])
            ->where('status', 'pending');

        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('kode_booking', 'like', "%{$request->search}%")
                  ->orWhereHas('user', function($q) use ($request) {
                      $q->where('name', 'like', "%{$request->search}%");
                  });
            });
        }

        $bookings = $query->latest()->paginate(10);

        return view('staff.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['user', 'room', 'approver']);
        return view('staff.bookings.show', compact('booking'));
    }

    public function approve(Booking $booking)
    {
        $booking->update([
            'status' => Booking::STATUS_APPROVED,
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('staff.bookings.index')
            ->with('success', 'Booking berhasil disetujui');
    }

    public function reject(Request $request, Booking $booking)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string|max:500',
        ]);

        $booking->update([
            'status' => Booking::STATUS_REJECTED,
            'alasan_penolakan' => $request->alasan_penolakan,
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('staff.bookings.index')
            ->with('success', 'Booking berhasil ditolak');
    }
}
