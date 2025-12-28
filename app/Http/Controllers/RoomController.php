<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Facility;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * Tampilkan daftar room
     */
    public function index()
    {
        $rooms = Room::latest()->get();
        return view('admin.Room.index', compact('rooms'));
    }

    /**
     * Form tambah room
     */
    public function create()
    {
        $facilities = Facility::all();
        return view('admin.Room.create', compact('facilities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_ruangan' => 'required|unique:rooms',
            'nama_ruangan' => 'required',
            'lokasi'       => 'required',
            'kapasitas'    => 'required|integer',
            'status'       => 'required',
            'gambar'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'facilities'   => 'nullable|array'
        ]);

        // upload gambar
        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('rooms', 'public');
        }

        $room = Room::create([
            'kode_ruangan' => $request->kode_ruangan,
            'nama_ruangan' => $request->nama_ruangan,
            'lokasi'       => $request->lokasi,
            'kapasitas'    => $request->kapasitas,
            'status'       => $request->status,
            'gambar'       => $path ?? null
        ]);

        // simpan relasi fasilitas (many to many)
        if ($request->facilities) {
            $room->facilities()->sync($request->facilities);
        }

        return redirect()
            ->route('Room.index')
            ->with('success', 'Ruangan berhasil ditambahkan');
    }


    /**
     * Form edit room
     */
    public function edit(Room $room)
    {
        $facilities = Facility::all();
        $selectedFacilities = $room->facilities->pluck('id')->toArray();

        return view('admin.Room.edit', compact(
            'room',
            'facilities',
            'selectedFacilities'
        ));
    }


    public function update(Request $request, Room $room)
    {
        $request->validate([
            'kode_ruangan' => 'required|unique:rooms,kode_ruangan,' . $room->id,
            'nama_ruangan' => 'required',
            'lokasi'       => 'required',
            'kapasitas'    => 'required|integer',
            'status'       => 'required',
            'gambar'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $data = $request->except('facilities');

        // Upload gambar baru
        if ($request->hasFile('gambar')) {

            if ($room->gambar && file_exists(public_path('storage/rooms/' . $room->gambar))) {
                unlink(public_path('storage/rooms/' . $room->gambar));
            }

            $file = $request->file('gambar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('rooms', $filename, 'public');
            $data['gambar'] = $filename;
        }

        // Update room
        $room->update($data);

        // ✅ SYNC FASILITAS
        $room->facilities()->sync($request->facilities ?? []);

        return redirect()
            ->route('Room.index')
            ->with('success', 'Room berhasil diperbarui');
    }



    /**
     * Hapus room
     */
    public function destroy(Room $room)
    {
        $room->delete();

        return redirect()
            ->route('Room.index')
            ->with('success', 'Room berhasil dihapus');
    }
}
