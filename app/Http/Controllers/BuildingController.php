<?php

namespace App\Http\Controllers;

use App\Models\Building;
use Illuminate\Http\Request;

class BuildingController extends Controller
{
    /**
     * Tampilkan semua gedung
     */
    public function index()
    {
        $buildings = Building::latest()->paginate(10);
        return view('admin.Building.index', compact('buildings'));
    }

    /**
     * Tampilkan form tambah gedung
     */
    public function create()
    {
        return view('admin.Building.create');
    }

    /**
     * Simpan gedung baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_gedung' => 'required|unique:buildings,kode_gedung',
            'nama_gedung' => 'required|string|max:255',
            'jumlah_lantai' => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/gedung'), $filename);
            $data['gambar'] = 'uploads/gedung/' . $filename;
        }

        Building::create($data);

        return redirect()->route('Building.index')->with('success', 'Gedung berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit gedung
     */
    public function edit(Building $building)
    {
        return view('admin.Building.edit', compact('building'));
    }

    /**
     * Update data gedung
     */
    public function update(Request $request, Building $building)
    {
        $request->validate([
            'kode_gedung' => 'required|unique:buildings,kode_gedung,' . $building->id,
            'nama_gedung' => 'required|string|max:255',
            'jumlah_lantai' => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('gambar')) {
            // hapus gambar lama jika ada
            if ($building->gambar && file_exists(public_path($building->gambar))) {
                unlink(public_path($building->gambar));
            }

            $file = $request->file('gambar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/gedung'), $filename);
            $data['gambar'] = 'uploads/gedung/' . $filename;
        }

        $building->update($data);

        return redirect()->route('Building.index')->with('success', 'Gedung berhasil diperbarui.');
    }

    /**
     * Hapus gedung
     */
    public function destroy(Building $building)
    {
        $building->delete();
        return redirect()->route('Building.index')->with('success', 'Gedung berhasil dihapus.');
    }
}
