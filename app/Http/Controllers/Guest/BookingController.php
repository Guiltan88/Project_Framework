<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Auth::user()->bookings()->with('room');

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->has('date_range') && $request->date_range) {
            $today = now()->startOfDay();

            switch($request->date_range) {
                case 'today':
                    $query->whereDate('tanggal_mulai', $today);
                    break;
                case 'this_week':
                    $query->whereBetween('tanggal_mulai', [
                        $today->startOfWeek(),
                        $today->endOfWeek()
                    ]);
                    break;
                case 'this_month':
                    $query->whereBetween('tanggal_mulai', [
                        $today->startOfMonth(),
                        $today->endOfMonth()
                    ]);
                    break;
                case 'upcoming':
                    $query->where('tanggal_mulai', '>=', $today);
                    break;
                case 'past':
                    $query->where('tanggal_mulai', '<', $today);
                    break;
            }
        }

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kode_booking', 'like', "%{$search}%")
                ->orWhere('tujuan', 'like', "%{$search}%")
                ->orWhereHas('room', function($q2) use ($search) {
                    $q2->where('nama_ruangan', 'like', "%{$search}%");
                });
            });
        }

        // Sorting
        $sort = $request->get('sort', 'latest');
        switch($sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'date':
                $query->orderBy('tanggal_mulai');
                break;
            case 'status':
                $query->orderBy('status');
                break;
            default: // latest
                $query->latest();
                break;
        }

        $bookings = $query->paginate($request->get('per_page', 10));

        return view('guest.bookings.history', compact('bookings'));
    }

    public function create(Request $request)
    {
        $rooms = Room::where('status', 'tersedia')
            ->with('building')
            ->get();

        $room = null;
        if ($request->has('room')) {
            $room = Room::with('building')->find($request->room);
        }

        return view('guest.bookings.create', compact('rooms', 'room'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'tujuan' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
            'jumlah_peserta' => 'required|integer|min:1',
            'kebutuhan_khusus' => 'nullable|string',
            'catatan' => 'nullable|string',
        ]);

        $room = Room::find($request->room_id);

        // Cek kapasitas
        if ($request->jumlah_peserta > $room->kapasitas) {
            return back()->withErrors([
                'jumlah_peserta' => "Kapasitas ruangan hanya {$room->kapasitas} orang."
            ])->withInput();
        }

        // Cek ketersediaan (sederhana)
        $conflict = Booking::where('room_id', $request->room_id)
            ->where('status', 'approved')
            ->where(function($q) use ($request) {
                $q->whereBetween('tanggal_mulai', [$request->tanggal_mulai, $request->tanggal_selesai])
                  ->orWhereBetween('tanggal_selesai', [$request->tanggal_mulai, $request->tanggal_selesai]);
            })
            ->exists();

        if ($conflict) {
            return back()->withErrors([
                'room_id' => 'Ruangan tidak tersedia pada tanggal yang dipilih.'
            ])->withInput();
        }

        $booking = Booking::create([
            'kode_booking' => 'BK-' . Str::upper(Str::random(8)),
            'user_id' => Auth::id(),
            'room_id' => $request->room_id,
            'tujuan' => $request->tujuan,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'waktu_mulai' => $request->waktu_mulai,
            'waktu_selesai' => $request->waktu_selesai,
            'jumlah_peserta' => $request->jumlah_peserta,
            'kebutuhan_khusus' => $request->kebutuhan_khusus,
            'catatan' => $request->catatan,
            'status' => Booking::STATUS_PENDING,
        ]);

        return redirect()->route('guest.bookings.history')
            ->with('success', 'Booking berhasil dibuat. Menunggu persetujuan admin.');
    }

    public function show($id)
    {
        $booking = Booking::with(['room', 'room.building', 'user'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('guest.bookings.show', compact('booking'));
    }

    public function edit(Booking $booking)
    {
        abort_if($booking->user_id !== Auth::id(), 403);
        abort_if(!$booking->isPending(), 403, 'Hanya booking pending yang bisa diedit.');

        $rooms = Room::where('status', 'tersedia')->get();
        return view('guest.bookings.edit', compact('booking', 'rooms'));
    }

    public function update(Request $request, Booking $booking)
    {
        abort_if($booking->user_id !== Auth::id(), 403);
        abort_if(!$booking->isPending(), 403);

        $request->validate([
            'tujuan' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
            'jumlah_peserta' => 'required|integer|min:1',
            'kebutuhan_khusus' => 'nullable|string',
            'catatan' => 'nullable|string',
        ]);

        $booking->update($request->all());

        return redirect()->route('guest.bookings.show', $booking)
            ->with('success', 'Booking berhasil diperbarui.');
    }

    public function cancel(Booking $booking)
    {
        abort_if($booking->user_id !== Auth::id(), 403);

        $booking->update([
            'status' => Booking::STATUS_CANCELLED,
        ]);

        return redirect()->route('guest.bookings.history')
            ->with('success', 'Booking berhasil dibatalkan.');
    }
}
