<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Building;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BuildingController extends Controller
{
    public function index(Request $request)
    {
        $query = Building::query();

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kode_gedung', 'LIKE', "%{$search}%")
                ->orWhere('nama_gedung', 'LIKE', "%{$search}%")
                ->orWhere('keterangan', 'LIKE', "%{$search}%");
            });
        }

        $buildings = $query->latest()->paginate(5);

        return view('admin.buildings.index', compact('buildings'));
    }

    public function create()
    {
        return view('admin.buildings.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_gedung' => 'required|unique:buildings,kode_gedung',
            'nama_gedung' => 'required|string|max:255',
            'jumlah_lantai' => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only([
            'kode_gedung',
            'nama_gedung',
            'jumlah_lantai',
            'keterangan',
        ]);

        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('buildings', 'public');
            $data['gambar'] = $path;
        }

        Building::create($data);

        return redirect()->route('admin.buildings.index')
            ->with('success', 'Gedung berhasil ditambahkan.');
    }

    public function edit(Building $building)
    {
        return view('admin.buildings.edit', compact('building'));
    }

    public function update(Request $request, Building $building)
    {
        $request->validate([
            'kode_gedung' => 'required|unique:buildings,kode_gedung,' . $building->id,
            'nama_gedung' => 'required|string|max:255',
            'jumlah_lantai' => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only([
            'kode_gedung',
            'nama_gedung',
            'jumlah_lantai',
            'keterangan',
        ]);

        if ($request->hasFile('gambar')) {
            if ($building->gambar) {
                Storage::disk('public')->delete($building->gambar);
            }
            $path = $request->file('gambar')->store('buildings', 'public');
            $data['gambar'] = $path;
        }

        $building->update($data);

        return redirect()->route('admin.buildings.index')
            ->with('success', 'Gedung berhasil diperbarui.');
    }

    public function destroy(Building $building)
    {
        if ($building->gambar) {
            Storage::disk('public')->delete($building->gambar);
        }

        $building->delete();

        return redirect()->route('admin.buildings.index')
            ->with('success', 'Gedung berhasil dihapus.');
    }
}
