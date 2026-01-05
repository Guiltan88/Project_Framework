<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Facility;
use App\Models\Building;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

        $rooms = $query->latest()->paginate(10);
        $buildings = Building::all();

        return view('admin.rooms.index', compact('rooms', 'buildings'));
    }

    public function create()
    {
        $buildings = Building::all();
        $facilities = Facility::all();
        return view('admin.rooms.create', compact('buildings', 'facilities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_ruangan' => 'required|unique:rooms',
            'nama_ruangan' => 'required',
            'gedung_id' => 'required|exists:buildings,id',
            'lantai' => 'required|integer|min:1',
            'kapasitas' => 'required|integer|min:1',
            'status' => 'required|in:tersedia,tidak tersedia,dalam perbaikan',
            'gambar' => 'nullable|image|max:2048',
            'facilities' => 'nullable|array'
        ]);

        $data = $request->except('facilities');

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('rooms', 'public');
        }

        $room = Room::create($data);

        if ($request->facilities) {
            $room->facilities()->sync($request->facilities);
        }

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Ruangan berhasil ditambahkan');
    }

    public function edit(Room $room)
    {
        $buildings = Building::all();
        $facilities = Facility::all();
        $selectedFacilities = $room->facilities->pluck('id')->toArray();

        return view('admin.rooms.edit', compact('room', 'buildings', 'facilities', 'selectedFacilities'));
    }

    public function update(Request $request, Room $room)
    {
        $request->validate([
            'kode_ruangan' => 'required|unique:rooms,kode_ruangan,' . $room->id,
            'nama_ruangan' => 'required',
            'gedung_id' => 'required|exists:buildings,id',
            'lantai' => 'required|integer|min:1',
            'kapasitas' => 'required|integer|min:1',
            'status' => 'required|in:tersedia,tidak tersedia,dalam perbaikan',
            'gambar' => 'nullable|image|max:2048'
        ]);

        $data = $request->except('facilities', 'gambar');

        if ($request->hasFile('gambar')) {
            if ($room->gambar) {
                Storage::disk('public')->delete($room->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('rooms', 'public');
        }

        $room->update($data);
        $room->facilities()->sync($request->facilities ?? []);

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Ruangan berhasil diperbarui');
    }

    public function destroy(Room $room)
    {
        // Cek apakah room memiliki booking
        if ($room->bookings()->count() > 0) {
            return redirect()->route('admin.rooms.index')
                ->with('error', 'Tidak bisa menghapus ruangan karena memiliki booking history.');
        }

        if ($room->gambar) {
            Storage::disk('public')->delete($room->gambar);
        }

        $room->delete();

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Ruangan berhasil dihapus');
    }

    public function getFloorsByBuilding(Request $request)
    {
        $request->validate([
            'building_id' => 'required|exists:buildings,id'
        ]);

        $building = Building::find($request->building_id);

        $floors = [];
        for ($i = 1; $i <= $building->jumlah_lantai; $i++) {
            $floors[] = [
                'id' => $i,
                'name' => "Lantai $i"
            ];
        }

        return response()->json([
            'success' => true,
            'floors' => $floors,
            'total_floors' => $building->jumlah_lantai,
        ]);
    }
}
