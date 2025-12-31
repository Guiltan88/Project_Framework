<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use Illuminate\Http\Request;

class FacilityController extends Controller
{
    public function index()
    {
        $facilities = Facility::withCount('rooms')->latest()->get();
        return view('admin.Property.index', compact('facilities'));
    }

    public function create()
    {
        return view('admin.Property.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_fasilitas' => 'required|unique:facilities',
            'status' => 'required'
        ]);

        Facility::create($request->all());

        return redirect()
            ->route('Property.index')
            ->with('success', 'Fasilitas berhasil ditambahkan');
    }

    public function edit(Facility $facility)
    {
        return view('admin.Property.edit', compact('facility'));
    }

    public function update(Request $request, Facility $facility)
    {
        $request->validate([
            'nama_fasilitas' => 'required|unique:facilities,nama_fasilitas,' . $facility->id,
            'status' => 'required'
        ]);

        $facility->update($request->all());

        return redirect()
            ->route('Property.index')
            ->with('success', 'Fasilitas berhasil diperbarui');
    }

    public function destroy(Facility $facility)
    {
        $facility->delete();

        return redirect()
            ->route('Property.index')
            ->with('success', 'Fasilitas berhasil dihapus');
    }
}
