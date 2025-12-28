@extends('admin.Layouts.app')
@section('title', 'Edit Fasilitas')

@section('content')
<div class="card">
    <h5 class="card-header">Edit Fasilitas</h5>

    <div class="card-body">
        <form action="{{ route('Property.update', $facility->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Nama Fasilitas</label>
                <input type="text"
                       name="nama_fasilitas"
                       class="form-control"
                       value="{{ old('nama_fasilitas', $facility->nama_fasilitas) }}"
                       required>
            </div>

            <div class="mb-3">
                <label class="form-label">Keterangan</label>
                <textarea name="keterangan"
                          class="form-control"
                          rows="3">{{ old('keterangan', $facility->keterangan) }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select" required>
                    <option value="aktif"
                        {{ $facility->status == 'aktif' ? 'selected' : '' }}>
                        Aktif
                    </option>
                    <option value="nonaktif"
                        {{ $facility->status == 'nonaktif' ? 'selected' : '' }}>
                        Nonaktif
                    </option>
                </select>
            </div>

            <div class="mt-3">
                <button class="btn btn-primary">Update</button>
                <a href="{{ route('Property.index') }}" class="btn btn-secondary">
                    Kembali
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
