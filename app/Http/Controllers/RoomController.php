<?php

namespace App\Http\Controllers;

use App\Models\Room;
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
        return view('admin.Room.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_ruangan' => 'required|unique:rooms',
            'nama_ruangan' => 'required',
            'lokasi'       => 'required',
            'kapasitas'    => 'required|integer',
            'status'       => 'required',
            'gambar'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('rooms', $filename, 'public');
            $data['gambar'] = $filename;
        }

        Room::create($data);

        return redirect()
            ->route('Room.index')
            ->with('success', 'Room berhasil ditambahkan');
    }



    /**
     * Form edit room
     */
    public function edit(Room $room)
    {
        return view('admin.Room.edit', compact('room'));
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

        $data = $request->all();

        // Jika upload gambar baru
        if ($request->hasFile('gambar')) {

            // hapus gambar lama (jika ada)
            if ($room->gambar && file_exists(public_path('storage/rooms/' . $room->gambar))) {
                unlink(public_path('storage/rooms/' . $room->gambar));
            }

            $file = $request->file('gambar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('rooms', $filename, 'public');
            $data['gambar'] = $filename;
        }

        $room->update($data);

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
