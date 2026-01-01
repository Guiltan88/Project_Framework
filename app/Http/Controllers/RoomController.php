<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Facility;
use App\Models\Building;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RoomController extends Controller
{
    /**
     * 📋 List semua room
     * (SESUAI route resource → TANPA parameter)
     */
    public function index()
    {
        $rooms = Room::with(['facilities', 'building'])
            ->latest()
            ->get();

        return view('admin.Room.index', compact('rooms'));
    }

    /**
     * ➕ Form tambah room
     */
    public function create()
    {
        $buildings  = Building::all();
        $facilities = Facility::all();

        return view('admin.Room.create', compact('buildings', 'facilities'));
    }

    /**
     * 💾 Simpan room
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'kode_ruangan' => 'required|unique:rooms',
            'nama_ruangan' => 'required',
            'gedung_id' => 'required|exists:buildings,id',
            'kapasitas' => 'required|integer',
            'status' => 'required',
            'gambar' => 'nullable|image',
            'facilities' => 'array'
        ]);

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('rooms', 'public');
        }

        $room = Room::create($data);

        if ($request->facilities) {
            $room->facilities()->sync($request->facilities);
        }

        return redirect()->route('Room.index')->with('success', 'Room berhasil ditambahkan');
    }


    public function edit(Room $room)
    {
        return view('admin.Room.edit', [
            'room' => $room,
            'buildings' => Building::all(),
            'facilities' => Facility::all(),
            'selectedFacilities' => $room->facilities->pluck('id')->toArray()
        ]);
    }

    public function update(Request $request, Room $room)
    {
        $request->validate([
            'kode_ruangan' => 'required|unique:rooms,kode_ruangan,' . $room->id,
            'nama_ruangan' => 'required',
            'gedung_id'    => 'required|exists:buildings,id',
            'kapasitas'    => 'required|integer',
            'status'       => 'required',
            'gambar'       => 'nullable|image'
        ]);

        if ($request->hasFile('gambar')) {
            if ($room->gambar) {
                Storage::disk('public')->delete($room->gambar);
            }
            $room->gambar = $request->file('gambar')->store('rooms', 'public');
        }

        $room->update($request->except('facilities', 'gambar'));
        $room->facilities()->sync($request->facilities ?? []);

        return redirect()
            ->route('Room.index')
            ->with('success', 'Ruangan berhasil diperbarui');
    }

    public function destroy(Room $room)
    {
        if ($room->gambar) {
            Storage::disk('public')->delete($room->gambar);
        }

        $room->delete();

        return redirect()
            ->route('Room.index')
            ->with('success', 'Ruangan berhasil dihapus');
    }
}
