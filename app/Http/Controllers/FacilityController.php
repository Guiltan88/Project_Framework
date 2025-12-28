<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use Illuminate\Http\Request;

class FacilityController extends Controller
{
    // 📋 List fasilitas
    public function index()
    {
        $facilities = Facility::latest()->get();
        return view('admin.Property.index', compact('facilities'));
    }

    // ➕ Form tambah
    public function create()
    {
        return view('admin.Property.create');
    }

    // 💾 Simpan
    public function store(Request $request)
    {
        $request->validate([
            'nama_fasilitas' => 'required|unique:facilities,nama_fasilitas',
            'keterangan'     => 'nullable'
        ]);

        Facility::create($request->all());

        return redirect()
            ->route('Property.index')
            ->with('success', 'Fasilitas berhasil ditambahkan');
    }

    // ✏️ Form edit
    public function edit(Facility $facility)
    {
        return view('admin.Property.edit', compact('facility'));
    }

    // 🔄 Update
    public function update(Request $request, Facility $facility)
    {
        $request->validate([
            'nama_fasilitas' => 'required|unique:facilities,nama_fasilitas,' . $facility->id,
            'keterangan'     => 'nullable'
        ]);

        $facility->update($request->all());

        return redirect()
            ->route('Property.index')
            ->with('success', 'Fasilitas berhasil diperbarui');
    }

    // 🗑️ Hapus
    public function destroy(Facility $facility)
    {
        $facility->delete();

        return redirect()
            ->route('Property.index')
            ->with('success', 'Fasilitas berhasil dihapus');
    }
}
